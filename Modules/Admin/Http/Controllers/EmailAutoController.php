<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 13/2/2019
 * Time: 14:32
 */

namespace Modules\Admin\Http\Controllers;

use App\Mail\SendMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\CustomerLeadTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\DealDetailTable;
use Modules\Admin\Models\DealTable;
use Modules\Admin\Models\EmailCampaignTable;
use Modules\Admin\Models\EmailConfigTable;
use Modules\Admin\Models\EmailDealDetailTable;
use Modules\Admin\Models\EmailDealTable;
use Modules\Admin\Models\EmailLogTable;
use Modules\Admin\Repositories\EmailCampaign\EmailCampaignRepositoryInterface;
use Modules\Admin\Repositories\EmailConfig\EmailConfigRepositoryInterface;
use Modules\Admin\Repositories\EmailProvider\EmailProviderRepositoryInterface;
use Modules\Admin\Repositories\EmailLog\EmailLogRepositoryInterface;
use Illuminate\Support\Facades\Crypt;
use App\Jobs\CheckMailJob;
use Modules\Admin\Repositories\EmailTemplate\EmailTemplateRepositoryInterface;

class EmailAutoController extends Controller
{
    protected $email_provider;
    protected $email_config;
    protected $email_log;
    protected $email_template;
    protected $email_campaign;

    public function __construct(EmailProviderRepositoryInterface $email_providers,
                                EmailConfigRepositoryInterface $email_configs,
                                EmailLogRepositoryInterface $email_logs,
                                EmailTemplateRepositoryInterface $email_templates,
                                EmailCampaignRepositoryInterface $email_campaign)
    {
        $this->email_provider = $email_providers;
        $this->email_config = $email_configs;
        $this->email_log = $email_logs;
        $this->email_template = $email_templates;
        $this->email_campaign = $email_campaign;
    }

    public function indexAction()
    {
        $mEmailConfig = new EmailConfigTable();
        //Lấy ds cấu hình email
        $optionConfig = $mEmailConfig->getListConfig();

        return view('admin::marketing.email.auto.index', [
            'LIST' => $optionConfig,
//            'FILTER' => $this->filters()
        ]);
    }

    public function listAction(Request $request)
    {
//        $list = $this->email_config->list();
//        return view('admin::marketing.email.auto.list', [
//            'LIST' => $list,
//        ]);
    }

    public function getConfigAction(Request $request)
    {
        $item = $this->email_provider->getItem($request->id);
        if ($item['password'] != null) {
            $pass = Crypt::decryptString($item['password']);
        } else {
            $pass = null;
        }
        $data = [
            'id' => $item['id'],
            'type' => $item['type'],
            'email' => $item['email'],
            'name_email' => $item['name_email'],
            'is_actived' => $item['is_actived'],
            'password' => $pass,

        ];
        return response()->json([
            'item' => $data
        ]);
    }

    public function submitConfigAction(Request $request)
    {
//        $hashed = Hash::make($request->password);
//        if (Hash::check($request->pass_check, $hashed)) {
//            print_r('Đúng');
//        } else {
//            print_r('Sai');
//        }
//        die();
        if ($request->is_actived == 0) {
            $data = [
                'is_actived' => $request->is_actived
            ];
        } else {
            $data = [
                'type' => $request->type,
                'email' => $request->email,
                'name_email' => $request->name_email,
                'is_actived' => $request->is_actived,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ];
            if ($request->type == 'gmail' || $request->type == 'clicksend') {
                $data['password'] = Crypt::encryptString($request->password);
            } else {
                $data['password'] = null;
            }
        }
        $this->email_provider->edit($data, $request->id);
        return response()->json([
            'success' => 1,
            'message' => 'Cập nhật cấu hình thành công'
        ]);
    }

    public function changeStatusAction(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            $data = [
                'is_actived' => $request->is_actived,
                'actived_by' => Auth::id(),
                'datetime_actived' => date('Y-m-d H:i'),
            ];
            $this->email_config->edit($data, $id);
            $item_config = $this->email_config->getItem($id);
            if ($request->is_actived == 0) {
                $get_log = $this->email_log->getTypeLog($item_config['key']);
                foreach ($get_log as $item) {
                    if ($item['email_status'] == 'new') {
                        $this->email_log->edit([
                            'email_status' => 'cancel',
                            'updated_by' => Auth::id()
                        ], $item['id']);
                    }

                }
            }

            DB::commit();
            return response()->json([
                'success' => 1,
                'message' => 'Cập nhật trạng thái thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getSettingContentAction(Request $request)
    {

        $id = $request->id;
        $item_content = $this->email_config->getItem($id);
        return response()->json([
            'item' => $item_content
        ]);
    }

    public function submitSettingContentAction(Request $request)
    {
        /*        if (!preg_match('#(?<=<)\w+(?=[^<]*?>)#', $request->content_email) == false) {*/
//            return response()->json([
//                'error_content' => 1,
//                'message' => 'Nội dung không hợp lệ'
//            ]);
//        }
        $data = [
            'title' => $request->title,
            'content' => $request->content_email,
            'updated_by' => Auth::id()
        ];
        if ($request->type == 'birthday') {
            $data['time_sent'] = $request->time_sent;
        } else if ($request->type == 'remind_appointment') {
            $data['value'] = $request->value_time;
        } else if ($request->type == 'service_card_nearly_expired') {
            $data['value'] = $request->value_day;
        }
        $this->email_config->edit($data, $request->id);
        return response()->json([
            'success' => 1,
            'message' => 'Lưu thành công'
        ]);
    }

    public function runAutoAction()
    {
        CheckMailJob::dispatch('not_event', '', '');
    }

    /**
     * Job send email
     *
     */
    public function sendEmailJobAction()
    {
        $timeSent = Carbon::now()->format('Y-m-d H:i:s');

        //Lấy thông tin cấu hình email
        $getInfoSendMail = $this->getEmailAddresses();
        //Lấy cấu hình email provider
        $get_provider = $this->email_provider->getItem(1);

        if ($get_provider != null && $get_provider['is_actived'] == 1) {
            $mEmailLog = new EmailLogTable();
            //Lấy sms log chăm sóc KH (campaign_id = null)
            $listLog = $mEmailLog->getLogLoyalty($timeSent);

            foreach ($listLog as $item) {
                //Lấy title email
                if ($item['email_type'] == 'print_card') {
                    $subject = __('Danh sách thẻ dịch vụ');
                } else {
                    $subject = $item['title'];
                }

                if ($get_provider->type == 'clicksend' && $getInfoSendMail['error'] == false) {
                    $tmp = [
                        'to' => [
                            [
                                'email' => $item['email'],
                                'name' => $item['customer_name']
                            ]
                        ],
                        'from' => [
                            'name' => $get_provider->name_email,
                            'email_address_id' => $getInfoSendMail['data']['email_address_id']
                        ],
                        'subject' => $subject['title'],
                        'body' => $item['content_sent']
                    ];
                    //Call send email click send
                    $this->callApiClickSend('https://rest.clicksend.com/v3/email/send', 'post', $tmp);
                } else {
                    Mail::to($item['email'])->send(new SendMailable($item['customer_name'], $subject, $item['content_sent'], $item['email_type'], $item['object_id'], $get_provider->email_template_id));
                }
                //Cập nhật trạng thái email_log
                $this->email_log->edit([
                    'time_sent_done' => date('Y-m-d H:i'),
                    'provider' => $get_provider['type'],
                    'sent_by' => Auth::id(),
                    'email_status' => 'sent'
                ], $item['id']);
            }

            $mBranch = new BranchTable();

            $mEmailCampaign = new EmailCampaignTable();
            //Lấy danh sách chiến dịch email marketing
            $getCampaign = $mEmailCampaign->getCampaignNew();

            foreach ($getCampaign as $item) {
                //Check giờ gửi
                if ($item['is_now'] == 0
                    && Carbon::parse($item['value'])->format('Y-m-d H:i')  > $timeSent) {
                    //Chưa tới giờ gửi
                    continue;
                }

                $infoDeal = null;
                $infoDealDetail = [];

                //Kiểm tra xem có tạo deal không
                if ($item['is_deal_created'] == 1) {
                    $mEmailDeal = new EmailDealTable();
                    //Lấy thông tin hành trình deal
                    $infoDeal = $mEmailDeal->getDealCampaign($item['campaign_id']);

                    if ($infoDeal != null) {
                        $mEmailDealDetail = new EmailDealDetailTable();
                        //Lấy thông tin chi tiết deal
                        $infoDealDetail = $mEmailDealDetail->getDealDetail($infoDeal['email_deal_id']);
                    }
                }

                //Cập nhật trạng thái email campaign
                $this->email_campaign->edit([
                    'sent_by' => $item['created_by'],
                    'time_sent' => date('Y-m-d H:i'),
                    'status' => 'sent'
                ], $item['campaign_id']);

                //Lấy sms log của từng chiến dịch
                $getLog = $mEmailLog->getLogMarketing($item['campaign_id']);

                foreach ($getLog as $v) {
                    if ($get_provider->type == 'clicksend' && $getInfoSendMail['error'] == false) {
                        $tmp = [
                            'to' => [
                                [
                                    'email' => $v['email'],
                                    'name' => $v['customer_name']
                                ]
                            ],
                            'from' => [
                                'name' => $get_provider->name_email,
                                'email_address_id' => $getInfoSendMail['data']['email_address_id']
                            ],
                            'subject' => $item['name'],
                            'body' => $v['content_sent']
                        ];
                        //Call send email click send
                        $this->callApiClickSend('https://rest.clicksend.com/v3/email/send', 'post', $tmp);
                    } else {
                        Mail::to($v['email'])->send(new SendMailable($v['customer_name'], $item['name'], $v['content_sent'], $v['email_type'], $v['object_id'], $get_provider->email_template_id));
                    }
                    //Cập nhật trạng thái email log
                    $this->email_log->edit([
                        'time_sent_done' => date('Y-m-d H:i'),
                        'provider' => $get_provider['type'],
                        'sent_by' => $item['created_by'],
                        'email_status' => 'sent'
                    ], $v['id']);

                    //Kiểm tra xem có tạo deal không
                    if ($item['is_deal_created'] == 1) {
                        $mDeal = new DealTable();

                        $objectCode = null;
                        $typeCustomer = null;
                        $phone = null;

                        //Lấy thông tin object_code
                        if ($v['type_customer'] == "customer") {
                            $mCustomer = new CustomerTable();
                            //Lấy thông tin khách hàng
                            $infoCustomer = $mCustomer->getItem($v['object_id']);
                            if ($infoCustomer != null) {
                                $objectCode = $infoCustomer['customer_code'];
                                $phone = $infoCustomer['phone1'];
                            }

                            $typeCustomer = "customer";
                        } else if ($v['type_customer'] == "lead") {
                            $mCustomerLead = new CustomerLeadTable();
                            //Lấy thông tin khách hàng tiềm năng
                            $infoLead = $mCustomerLead->getInfo($v['object_id']);

                            if ($infoLead != null) {
                                $objectCode = $infoLead['customer_lead_code'];
                                $phone = $infoLead['phone'];
                            }
                            $typeCustomer = "lead";
                        }

                        //Tạo deal
                        $idDeal = $mDeal->add([
                            'deal_name' => 'Email_'. $v['customer_name'] .'_'. $item['name'],
                            'owner' => $infoDeal['owner_id'],
                            'type_customer' => $typeCustomer,
                            'customer_code' => $objectCode,
                            'pipeline_code' => $infoDeal['pipeline_code'],
                            'journey_code' => $infoDeal['journey_code'],
                            'branch_code' => null,
                            'closing_date' => $infoDeal['closing_date'],
                            'created_by' => $item['created_by'],
                            'updated_by' => $item['created_by'],
                            'deal_type_code' => 'email',
                            'deal_type_object_id' => $item['campaign_id'],
                            'phone' => $phone,
                            'amount' => $infoDeal['amount']
                        ]);
                        //Cập nhật deal code
                        $dealCode = 'DEALS_' . date('dmY') . sprintf("%02d", $idDeal);
                        $mDeal->edit($idDeal, ['deal_code' => $dealCode]);

                        if (isset($infoDealDetail) && count($infoDealDetail) > 0) {
                            foreach ($infoDealDetail as $v1) {
                                $mDealDetail = new DealDetailTable();
                                //Tạo chi tiết deal
                                $mDealDetail->add([
                                    'deal_code' => $dealCode,
                                    'object_id' => isset($v1['object_id']) ? $v1['object_id'] : '',
                                    'object_name' => $v1['object_name'],
                                    'object_type' => $v1['object_type'],
                                    'object_code' => $v1['object_code'],
                                    'price' => $v1['price'],
                                    'quantity' => $v1['quantity'],
                                    'discount' => $v1['discount'],
                                    'amount' => $v1['amount'],
                                    'voucher_code' => $v1['voucher_code'],
                                    'created_by' => $item['created_by'],
                                    'updated_by' => $item['created_by']
                                ]);
                            }
                        }
                        //Cập nhật deal_code cho email_log
                        $this->email_log->edit([
                            'deal_code' => $dealCode,
                        ], $v['id']);
                    }
                }
            }

            echo 'Gửi thành công';
        }
    }

    public function getEmailAddresses()
    {
        $email = $this->callApiClickSend('https://rest.clicksend.com/v3/email/addresses', 'get', []);
        if ($email['http_code'] == 200) {
            if (count($email['data']['data']) != 0) {
                foreach ($email['data']['data'] as $item) {
                    if ($item['verified'] == 1) {
                        return [
                            'error' => false,
                            'data' => $item
                        ];
                    }
                }
                return [
                    'error' => true,
                    'data' => null
                ];
            } else {
                return [
                    'error' => true,
                    'data' => null
                ];
            }
        } else {
            return [
                'error' => true,
                'data' => null
            ];
        }
    }

    public function callApiClickSend($_URL, $post, $sendEmail)
    {
        $provider = DB::table('email_provider')->where('id', 1)->first();
        $provider->password = Crypt::decryptString($provider->password);
        $oURL = curl_init();
        curl_setopt($oURL, CURLOPT_URL, $_URL);
//            curl_setopt($oURL, CURLOPT_HEADER, TRUE);
        if ($post == 'post') {
            curl_setopt($oURL, CURLOPT_POST, TRUE);
        } else {
            curl_setopt($oURL, CURLOPT_HTTPGET, TRUE);
        }
        curl_setopt($oURL, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($oURL, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($oURL, CURLOPT_USERPWD, $provider->email . ":" . $provider->password);
        if (count($sendEmail) != 0) {
            curl_setopt($oURL, CURLOPT_POSTFIELDS, json_encode($sendEmail));
        }
        $response = curl_exec($oURL);
//        $response = curl_getinfo($oURL, CURLINFO_HTTP_CODE);
        curl_close($oURL);
        return json_decode($response, true);
    }

    public function emailTemplateAction(Request $request)
    {
        $id = $request->id;
        $item = $this->email_provider->getItem($id);
        $getTemplate = $this->email_template->getAll();
        return response()->json([
            'item' => $item,
            'template' => $getTemplate
        ]);
    }

    public function submitEmailTemplateAction(Request $request)
    {
        $id = $request->id;
        $data = [
            'email_template_id' => $request->email_template_id
        ];
        $this->email_provider->edit($data, $id);
        return response()->json([
            'success' => 1,
            'message' => 'Chọn template thành công'
        ]);
    }
}