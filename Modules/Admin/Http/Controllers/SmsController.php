<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/13/2019
 * Time: 2:28 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Libs\sms\SendSms;

use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\CustomerLeadTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\DealDetailTable;
use Modules\Admin\Models\DealTable;
use Modules\Admin\Models\SmsCampaignTable;
use function Modules\Admin\Libs\SmsFpt\getTechAuthorization;

use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Api\SendBrandnameOtp;
use Modules\Admin\Models\SmsDealDetailTable;
use Modules\Admin\Models\SmsDealTable;
use Modules\Admin\Models\SmsLogTable;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\ServiceCardList\ServiceCardListRepositoryInterface;
use Modules\Admin\Repositories\SmsConfig\SmsConfigRepositoryInterface;
use Modules\Admin\Repositories\SmsLog\SmsLogRepositoryInterface;
use Modules\Admin\Repositories\SmsProvider\SmsProviderRepositoryInterface;
use Modules\Admin\Libs\SmsFpt\TechAPI\bootstrap;

use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Api\SendMtActive;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception as TechException;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Auth\AccessToken;

class SmsController extends Controller
{
    protected $smsProvider;
    protected $smsConfig;
    protected $smsLog;
    protected $sendSms;
    protected $order;
    protected $serviceCardList;
    protected $smsCampaign;

    public function __construct(
        SmsProviderRepositoryInterface $smsProvider,
        SmsConfigRepositoryInterface $smsConfig,
        SmsLogRepositoryInterface $smsLog,
        SendSms $sendSms,
        OrderRepositoryInterface $order,
        ServiceCardListRepositoryInterface $serviceCardList,
        SmsCampaignTable $smsCampaign
    )
    {
        $this->smsProvider = $smsProvider;
        $this->smsConfig = $smsConfig;
        $this->smsLog = $smsLog;
        $this->sendSms = $sendSms;
        $this->order = $order;
        $this->serviceCardList = $serviceCardList;
        $this->smsCampaign = $smsCampaign;
    }

    //function view index
    public function settingSmsAction()
    {
        $smsProvider = $this->smsProvider->getItem(1);
        $allKey = $this->smsConfig->getAllKey();
        $allType = [];
        foreach ($allKey as $key => $value) {
            $allType[$value['key']] = [
                'content' => $value['content'],
                'is_active' => $value['is_active'],
                'value' => $value['value'],
                'time_sent' => $value['time_sent']
            ];
        }
        return view('admin::marketing.sms.setting-sms.index', [
            'smsProvider' => $smsProvider,
            'allType' => $allType
        ]);
    }

    public function configSmsAction(Request $request)
    {
        $isActived = $request->is_actived;
        $provider = $request->provider;
        $type = $request->type;
        $value = $request->value;
        $account = $request->account;
        $password = $request->password;
        if ($isActived == 1) {
            $data = [
                'is_actived' => 1,
                'provider' => $provider,
                'type' => $type,
                'value' => $value,
                'account' => $account,
                'password' => $password,
                'updated_by' => Auth::id()
            ];
            $this->smsProvider->edit($data, $request->brand_name_id);
        } else {
            $data = [
                'is_actived' => 0,
                'updated_by' => Auth::id()
            ];
            $this->smsProvider->edit($data, $request->brand_name_id);
        }
        return response()->json(['error' => 0]);
    }

    public function getConfig(Request $request)
    {
        $data = $this->smsProvider->getItem($request->brand_name_id);
        return response()->json($data);
    }

    public function activeSmsConfigAction(Request $request)
    {
        $smsType = $request->smsType;
        $actived = $request->actived;
        $date = date('Y-m-d H:i:s');

        $data = [
            'is_active' => $actived,
            'updated_by' => Auth::id(),
            'actived_by' => Auth::id(),
            'updated_at' => $date,
            'datetime_actived' => $date,

        ];
        $this->smsConfig->activeConfig($smsType, $data);
        if ($actived == "0") {
            $this->smsLog->cancelLog($smsType);
        }
    }

    public function submitSettingSms(Request $request)
    {
        $type = $request->type;
        $timeSend = $request->timeSend;
        $numberDay = $request->numberDay;
        $hour = $request->hour;
        $messageContent = $request->messageContent;
        $dateTime = date('Y-m-d H:i:s');
        $updateBy = Auth::id();
        $data = [];
        //Cập nhật tin nhắn theo loại tin.
        switch ($type) {
            case 'birthday':
                $data = [
                    'time_sent' => $timeSend,
                    'content' => $messageContent
                ];
                break;
            case 'new_appointment':
                $data = [
                    'content' => $messageContent
                ];
                break;
            case 'cancel_appointment':
                $data = [
                    'content' => $messageContent
                ];
                break;
            case 'remind_appointment':
                $data = [
                    'value' => $hour * 60,
                    'content' => $messageContent
                ];
                break;
            case 'paysuccess':
                $data = [
                    'content' => $messageContent
                ];
                break;
            case 'new_customer':
                $data = [
                    'content' => $messageContent
                ];
                break;
            case 'service_card_nearly_expired':
                $data = [
                    'value' => $numberDay,
                    'content' => $messageContent
                ];
                break;
            case 'service_card_expires':
                $data = [
                    'content' => $messageContent
                ];
                break;
            case 'service_card_over_number_used':
                $data = [
                    'content' => $messageContent
                ];
                break;
            case 'delivery_note':
                $data = [
                    'content' => $messageContent,
                ];
                break;
            case 'confirm_deliveried':
                $data = [
                    'content' => $messageContent
                ];
                break;
            case 'order_success':
                $data = [
                    'content' => $messageContent
                ];
                break;
            case 'active_warranty_card':
                $data = [
                    'content' => $messageContent
                ];
                break;
            case 'otp':
                $data = [
                    'content' => $messageContent
                ];
                break;
            case 'is_remind_use':
                $data = [
                    'content' => $messageContent
                ];
                break;
        }
        $data['updated_by'] = $updateBy;
        $data['updated_at'] = $dateTime;

        $update = $this->smsConfig->activeConfig($type, $data);
        if ($update == 1) {
            return response()->json(['error' => 0]);
        } else {
            return response()->json(['error' => 1]);
        }
    }

    public function sendSmsNoEvent()
    {
        $this->smsLog->getList('birthday');
        $this->smsLog->getList('remind_appointment');
        $this->smsLog->getList('service_card_nearly_expired');
        $this->smsLog->getList('service_card_expires');
    }

    /**
     * Job send email
     *
     * @param null $timeSent
     */
    public function sendSmsAction($timeSent = null)
    {
        $timeSent = Carbon::now()->format('Y-m-d H:i:s');

        $mSmsLog = new SmsLogTable();
        //Lấy cấu hình send sms CSKH
        $smsSettingBrandName = $this->smsProvider->getItem(1);

        if ($smsSettingBrandName != null && $smsSettingBrandName->is_actived == 1) {
            $userName = $smsSettingBrandName->account;
            $password = $smsSettingBrandName->password;
            $brandName = $smsSettingBrandName->value;
            //Lấy sms log chăm sóc KH (campaign_id = null)
            $listLog = $mSmsLog->getLogLoyalty($timeSent);

            $idTransaction = 1;
            if ($listLog->count() != 0) {
                foreach ($listLog as $item) {
                    $arrayConfig['phone'] = $item['phone'];
                    $arrayConfig['message'] = $item['message'];
                    $arrayConfig['_USER_NAME'] = $userName;
                    $arrayConfig['_PASSWORD'] = $password;
                    $arrayConfig['_BRAND_NAME'] = $brandName;
                    $arrayConfig['idTransaction'] = $idTransaction;

                    $response = [];
                    if ($smsSettingBrandName['provider'] == 'vietguys') {
                        $sendSms = $this->sendSms->send($arrayConfig);
                        $response = json_decode($sendSms, true);
                    } else if ($smsSettingBrandName['provider'] == 'fpt') {
                        $sendSms = $this->sendSmsFptAction($arrayConfig);
                        $response = json_decode($sendSms, true);
                    } else if ($smsSettingBrandName['provider'] == 'clicksend') {
                        $config = [
                            'source' => 'php',
                            'message' => $item['message'],
                            'phone' => $item['phone'],
                            'brand_name' => $item['brandname']
                        ];
                        $sendSms = $this->sendSms->clickSend($config);
                        $response = json_decode($sendSms, true);
                    }

                    if (isset($response['error'])) {
                        if ($response['error'] == false) {
                            //Send được không lỗi
                            $this->smsLog->edit([
                                'sms_status' => 'sent',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'time_sent_done' => date('Y-m-d H:i:s'),
                                'sent_by' => $item['created_by']
                            ], $item['id']);
                        } else {
                            //Send được có lỗi
                            $this->smsLog->edit([
                                'sms_status' => 'sent',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'time_sent_done' => date('Y-m-d H:i:s'),
                                'sent_by' => $item['created_by'],
                                'error_code' => $response['errorCode'],
                                'error_description' => $response['message']
                            ], $item['id']);
                        }
                    } else {
                        //Update trạng thái của log sms (ko có response)
                        $this->smsLog->edit([
                            'sms_status' => 'sent',
                            'updated_at' => date('Y-m-d H:i:s'),
                            'time_sent_done' => date('Y-m-d H:i:s'),
                            'sent_by' => $item['created_by']
                        ], $item['id']);
                    }
                }
            }
        }

        //Lấy cấu hình send sms marketing
        $brandNameMarketing = $this->smsProvider->getItem(2);

        if ($brandNameMarketing != null && $brandNameMarketing->is_actived == 1) {
            $userName = $brandNameMarketing->account;
            $password = $brandNameMarketing->password;
            $brandName = $brandNameMarketing->value;
            $idTransaction = 1;

            $mBranch = new BranchTable();

            $mSmsCampaign = new SmsCampaignTable();
            //Lấy danh sách chiến dịch sms marketing
            $getCampaign = $mSmsCampaign->getCampaignNew();

            if (count($getCampaign) > 0) {
                foreach ($getCampaign as $item) {

                    if ($item['is_now'] == 0 && Carbon::parse($item['value'])->format('Y-m-d H:i') > $timeSent) {
                        //Chưa tới giờ gửi
                        continue;
                    }

                    $infoDeal = null;
                    $infoDealDetail = [];

                    //Kiểm tra xem có tạo deal không
                    if ($item['is_deal_created'] == 1) {
                        $mSmsDeal = new SmsDealTable();
                        //Lấy thông tin hành trình deal
                        $infoDeal = $mSmsDeal->getDealCampaign($item['campaign_id']);

                        if ($infoDeal != null) {
                            $mSmsDealDetail = new SmsDealDetailTable();
                            //Lấy thông tin chi tiết deal
                            $infoDealDetail = $mSmsDealDetail->getDealDetail($infoDeal['sms_deal_id']);
                        }
                    }

                    //Update trạng thái campaign
                    $this->smsCampaign->edit([
                        'status' => 'sent',
                        'sent_by' => $item['created_by'],
                        'time_sent' => date('Y-m-d H:i:s'),
                    ],$item['campaign_id']);

                    //Lấy sms log của từng chiến dịch
                    $getLog = $mSmsLog->getLogMarketing($item['campaign_id']);

                    foreach ($getLog as $v) {
                        $arrayConfig['phone'] = $v['phone'];
                        $arrayConfig['message'] = $v['message'];
                        $arrayConfig['_USER_NAME'] = $userName;
                        $arrayConfig['_PASSWORD'] = $password;
                        $arrayConfig['_BRAND_NAME'] = $brandName;
                        $arrayConfig['idTransaction'] = $idTransaction;


                        //Check dịch vụ gửi + gửi tin dạng marketing (tạm thời chưa có service làm theo kiểu cũ trước)
                        $response = [];
                        if ($brandNameMarketing['provider'] == 'vietguys') {
                            $sendSms = $this->sendSms->send($arrayConfig);
                            $response = json_decode($sendSms, true);
                        } else if ($brandNameMarketing['provider'] == 'fpt') {
                            $sendSms = $this->sendSmsFptAction($arrayConfig);
                            $response = json_decode($sendSms, true);
                        } else if ($brandNameMarketing['provider'] == 'clicksend') {
                            $config = [
                                'source' => 'php',
                                'message' => $v['message'],
                                'phone' => $v['phone'],
                                'brand_name' => $v['brandname']
                            ];
                            $sendSms = $this->sendSms->clickSend($config);
                            $response = json_decode($sendSms, true);
                        }

                        if (isset($response['error'])) {
                            if ($response['error'] == false) {
                                //Send được không lỗi
                                $this->smsLog->edit([
                                    'sms_status' => 'sent',
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'time_sent_done' => date('Y-m-d H:i:s'),
                                    'sent_by' => $item['created_by']
                                ], $v['id']);
                            } else {
                                //Send được có lỗi
                                $this->smsLog->edit([
                                    'sms_status' => 'sent',
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'time_sent_done' => date('Y-m-d H:i:s'),
                                    'sent_by' => 0,
                                    'error_code' => $response['errorCode'],
                                    'error_description' => $response['message']
                                ], $v['id']);
                            }
                        } else {
                            //Update trạng thái của log sms (ko có response)
                            $this->smsLog->edit([
                                'sms_status' => 'sent',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'time_sent_done' => date('Y-m-d H:i:s'),
                                'sent_by' => $item['created_by']
                            ], $v['id']);
                        }

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
                                'deal_name' => 'Sms_'. $v['customer_name'] .'_'. $item['name'],
                                'owner' => $infoDeal['owner_id'],
                                'type_customer' => $typeCustomer,
                                'customer_code' => $objectCode,
                                'pipeline_code' => $infoDeal['pipeline_code'],
                                'journey_code' => $infoDeal['journey_code'],
                                'branch_code' => null,
                                'closing_date' => $infoDeal['closing_date'],
                                'created_by' => $item['created_by'],
                                'updated_by' => $item['created_by'],
                                'deal_type_code' => 'sms',
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
                            $this->smsLog->edit([
                                'deal_code' => $dealCode,
                            ], $v['id']);
                        }
                    }
                }
            }
        }

        echo 'Chạy thành công';
    }

    public function sendCodeServiceCard(Request $request)
    {
        $orderId = $request->orderId;

        $arrayCode = $request->arrayCode;
        $type = $request->type;

        $getInfoCustomer = $this->order->getCustomerDetail($orderId);
        $phoneTemp = $request->phone;
        $code = '';
        foreach ($arrayCode as $key => $value) {
            $code .= $value . ", ";
        }
        $message = 'Ma the quy khach da mua la: ' . substr($code, 0, -2);

        if ($phoneTemp == 0) {
            if ($getInfoCustomer['customer_id'] == 1 || $getInfoCustomer['phone'] == null || $getInfoCustomer['phone'] == '') {
                return response()->json(['error' => 'notphone']);
            } else {
                $smsSettingBrandName = $this->smsProvider->getItem(1);
                if ($smsSettingBrandName != null) {
                    if ($smsSettingBrandName->is_actived == 1) {
                        $userName = $smsSettingBrandName->account;
                        $password = $smsSettingBrandName->password;
                        $brandName = $smsSettingBrandName->value;

                        //Lưu sms log.
                        $dataLog = [
                            'brandname' => $brandName,
                            'phone' => $getInfoCustomer['phone'],
                            'customer_name' => $getInfoCustomer['full_name'],
                            'message' => $message,
                            'sms_status' => 'new',
                            'sms_type' => 'paysuccess',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::user()->staff_id
                        ];
                        $idSmsLog = $this->smsLog->add($dataLog);

                        //Gửi sms
                        $arrayConfig['phone'] = $getInfoCustomer['phone'];
                        $arrayConfig['message'] = $message;
                        $arrayConfig['_USER_NAME'] = $userName;
                        $arrayConfig['_PASSWORD'] = $password;
                        $arrayConfig['_BRAND_NAME'] = $brandName;
                        $arrayConfig['idTransaction'] = 1;

                        $sendSms = $this->sendSms->send($arrayConfig);
                        $response = json_decode($sendSms, true);
                        if ($response['error'] == false) {
                            $data = [
                                'sms_status' => 'sent',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'time_sent_done' => date('Y-m-d H:i:s'),
                                'sent_by' => Auth::id()
                            ];
                        } else {
                            $data = [
                                'sms_status' => 'sent',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'time_sent_done' => date('Y-m-d H:i:s'),
                                'sent_by' => Auth::id(),
                                'error_code' => $response['errorCode'],
                                'error_description' => $response['message']
                            ];
                        }
                        $this->smsLog->edit($data, $idSmsLog);
                    }
                }
            }
        } else {
            if ($type != 'all') {
                $smsSettingBrandName = $this->smsProvider->getItem(1);
                if ($smsSettingBrandName != null) {
                    if ($smsSettingBrandName->is_actived == 1) {
                        $userName = $smsSettingBrandName->account;
                        $password = $smsSettingBrandName->password;
                        $brandName = $smsSettingBrandName->value;

                        //Lưu sms log.
                        $dataLog = [
                            'brandname' => $brandName,
                            'phone' => $phoneTemp,
                            'customer_name' => $getInfoCustomer['full_name'],
                            'message' => $message,
                            'sms_status' => 'new',
                            'sms_type' => 'paysuccess',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::user()->staff_id
                        ];
                        $idSmsLog = $this->smsLog->add($dataLog);

                        //Gửi sms
                        $arrayConfig['phone'] = $phoneTemp;
                        $arrayConfig['message'] = $message;
                        $arrayConfig['_USER_NAME'] = $userName;
                        $arrayConfig['_PASSWORD'] = $password;
                        $arrayConfig['_BRAND_NAME'] = $brandName;
                        $arrayConfig['idTransaction'] = 1;

                        $sendSms = $this->sendSms->send($arrayConfig);
                        $response = json_decode($sendSms, true);
                        if ($response['error'] == false) {
                            $data = [
                                'sms_status' => 'sent',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'time_sent_done' => date('Y-m-d H:i:s'),
                                'sent_by' => Auth::id()
                            ];
                        } else {
                            $data = [
                                'sms_status' => 'sent',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'time_sent_done' => date('Y-m-d H:i:s'),
                                'sent_by' => Auth::id(),
                                'error_code' => $response['errorCode'],
                                'error_description' => $response['message']
                            ];
                        }
                        $this->smsLog->edit($data, $idSmsLog);
                    }
                }
            } else {
                $listCardOrder = $this->serviceCardList->getServiceCardListByOrderCode($getInfoCustomer['order_code']);
                $code = '';
                foreach ($listCardOrder as $item) {
                    $code .= $item['code'] . ", ";
                }

                $message = 'Ma the quy khach da mua la: ' . substr($code, 0, -2);

                $smsSettingBrandName = $this->smsProvider->getItem(1);
                if ($smsSettingBrandName != null) {
                    if ($smsSettingBrandName->is_actived == 1) {
                        $userName = $smsSettingBrandName->account;
                        $password = $smsSettingBrandName->password;
                        $brandName = $smsSettingBrandName->value;

                        //Lưu sms log.
                        $dataLog = [
                            'brandname' => $brandName,
                            'phone' => $phoneTemp,
                            'customer_name' => $getInfoCustomer['full_name'],
                            'message' => $message,
                            'sms_status' => 'new',
                            'sms_type' => 'paysuccess',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::user()->staff_id
                        ];
                        $idSmsLog = $this->smsLog->add($dataLog);

                        //Gửi sms
                        $arrayConfig['phone'] = $phoneTemp;
                        $arrayConfig['message'] = $message;
                        $arrayConfig['_USER_NAME'] = $userName;
                        $arrayConfig['_PASSWORD'] = $password;
                        $arrayConfig['_BRAND_NAME'] = $brandName;
                        $arrayConfig['idTransaction'] = 1;

                        $sendSms = $this->sendSms->send($arrayConfig);
                        $response = json_decode($sendSms, true);
                        if ($response['error'] == false) {
                            $data = [
                                'sms_status' => 'sent',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'time_sent_done' => date('Y-m-d H:i:s'),
                                'sent_by' => Auth::id()
                            ];
                        } else {
                            $data = [
                                'sms_status' => 'sent',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'time_sent_done' => date('Y-m-d H:i:s'),
                                'sent_by' => Auth::id(),
                                'error_code' => $response['errorCode'],
                                'error_description' => $response['message']
                            ];
                        }
                        $this->smsLog->edit($data, $idSmsLog);
                    }
                }
            }


        }

    }

    public function sendAllCodeServiceCard(Request $request)
    {
        $orderId = $request->orderId;

        $getItemOrder = $this->order->getCustomerDetail($orderId);
        if ($request->phone == 0) {
            if ($getItemOrder['customer_id'] == 1 || $getItemOrder['phone'] == null || $getItemOrder['phone'] == '') {
                return response()->json(['error' => 'notphone']);
            } else {
                $listCardOrder = $this->serviceCardList->getServiceCardListByOrderCode($getItemOrder['order_code']);
                $code = '';
                foreach ($listCardOrder as $item) {
                    $code .= $item['code'] . ", ";
                }

                $message = 'Ma the quy khach da mua la: ' . substr($code, 0, -2);

                $smsSettingBrandName = $this->smsProvider->getItem(1);
                if ($smsSettingBrandName != null) {
                    if ($smsSettingBrandName->is_actived == 1) {
                        $userName = $smsSettingBrandName->account;
                        $password = $smsSettingBrandName->password;
                        $brandName = $smsSettingBrandName->value;

                        //Lưu sms log.
                        $dataLog = [
                            'brandname' => $brandName,
                            'phone' => $getItemOrder['phone'],
                            'customer_name' => $getItemOrder['full_name'],
                            'message' => $message,
                            'sms_status' => 'new',
                            'sms_type' => 'paysuccess',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::user()->staff_id
                        ];
                        $idSmsLog = $this->smsLog->add($dataLog);

                        //Gửi sms
                        $arrayConfig['phone'] = $getItemOrder['phone'];
                        $arrayConfig['message'] = $message;
                        $arrayConfig['_USER_NAME'] = $userName;
                        $arrayConfig['_PASSWORD'] = $password;
                        $arrayConfig['_BRAND_NAME'] = $brandName;
                        $arrayConfig['idTransaction'] = 1;

                        $sendSms = $this->sendSms->send($arrayConfig);
                        $response = json_decode($sendSms, true);
                        if ($response['error'] == false) {
                            $data = [
                                'sms_status' => 'sent',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'time_sent_done' => date('Y-m-d H:i:s'),
                                'sent_by' => Auth::id()
                            ];
                        } else {
                            $data = [
                                'sms_status' => 'sent',
                                'updated_at' => date('Y-m-d H:i:s'),
                                'time_sent_done' => date('Y-m-d H:i:s'),
                                'sent_by' => Auth::id(),
                                'error_code' => $response['errorCode'],
                                'error_description' => $response['message']
                            ];
                        }
                        $this->smsLog->edit($data, $idSmsLog);
                    }
                }
            }
        }

    }

    /**
     * Gửi sms FPT loại chăm sóc KH
     *
     * @param $arrMessage
     * @return false|string
     */
    private function sendSmsFptAction($arrMessage)
    {
        $arrMessage['Phone'] = $arrMessage['phone'];
        $arrMessage['Message'] = $arrMessage['message'];
        $arrMessage['ServiceNum'] = 8700;
        $arrMessage['BrandName'] = $arrMessage['_BRAND_NAME'];
        unset($arrMessage['_USER_NAME'], $arrMessage['_PASSWORD'], $arrMessage['phone'], $arrMessage['message'], $arrMessage['_BRAND_NAME']);

        // Khởi tạo đối tượng API với các tham số phía trên.
        $apiSendBrandname = new SendBrandnameOtp($arrMessage);

        try {
            // Lấy đối tượng Authorization để thực thi API
            $oGrantType = getTechAuthorization();

            // Thực thi API
            $arrResponse = $oGrantType->execute($apiSendBrandname);

            // kiểm tra kết quả trả về có lỗi hay không
            if (!empty($arrResponse['error'])) {
                // Xóa cache access token khi có lỗi xảy ra từ phía server
                AccessToken::getInstance()->clear();

                // quăng lỗi ra, và ghi log
                throw new TechException($arrResponse['error_description'], $arrResponse['error']);
            }


            return json_encode([
                'error' => false,
                'errorCode' => 0,
                'message' => 'Gửi thành công',
                'data' => null,
            ]);

        } catch (\Exception $ex) {

            return json_encode([
                'error' => true,
                'errorCode' => $ex->getCode(),
                'message' => $ex->getMessage(),
                'data' => null,
            ]);
        }
    }

    private function sendSmsVietguyMarketing($data)
    {

    }
}
