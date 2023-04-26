<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 29/1/2019
 * Time: 15:55
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Admin\Models\EmailDealDetailTable;
use Modules\Admin\Models\EmailDealTable;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Customer\CustomerRepository;
use Modules\Admin\Repositories\Customer\CustomerRepositoryInterface;
use Modules\Admin\Repositories\EmailCampaign\EmailCampaignRepositoryInterface;
use Modules\Admin\Repositories\EmailCampaignDetail\EmailCampaignDetailRepositoryInterface;
use Modules\Admin\Repositories\EmailLog\EmailLogRepositoryInterface;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;
use Modules\Admin\Repositories\EmailProvider\EmailProviderRepositoryInterface;
use App\Mail\SendMailable;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmailExport;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Modules\CustomerLead\Models\CustomerDealDetailTable;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Models\CustomerSourceTable;
use Modules\CustomerLead\Models\PipelineTable;

class EmailCampaignController extends Controller
{
    protected $email_campaign;
    protected $email_campaign_detail;
    protected $branch;
    protected $customer;
    protected $email_log;
    protected $staff;
    protected $email_provider;

    public function __construct(EmailCampaignRepositoryInterface $email_campaigns,
                                EmailCampaignDetailRepositoryInterface $email_campaign_details,
                                BranchRepositoryInterface $branches, CustomerRepository $customers,
                                EmailLogRepositoryInterface $email_logs,
                                StaffRepositoryInterface $staffs, EmailProviderRepositoryInterface $email_provider)
    {
        $this->email_campaign = $email_campaigns;
        $this->email_campaign_detail = $email_campaign_details;
        $this->branch = $branches;
        $this->customer = $customers;
        $this->email_log = $email_logs;
        $this->staff = $staffs;
        $this->email_provider = $email_provider;
    }

    public function indexAction()
    {

        $list = $this->email_campaign->listNew();
        $log = $this->email_campaign->getLog();

        $listEmailCampaign = $this->listEmailCampaign($list, $log);
        $result = collect($listEmailCampaign)->forPage(1, 10);
        return view('admin::marketing.email.campaign', [
            'LIST' => $result,
            'FILTER' => $this->filters(),
            'page' => 1,
            'data' => $listEmailCampaign,
        ]);
    }

    private function listEmailCampaign($campaign, $log)
    {
        $result = [];
        if ($campaign != null && $log != null) {
            foreach ($campaign as $item) {
                $sentBy = '';
                $queryStaff = $this->staff->getItem($item['sent_by']);

                if ($queryStaff != null) {
                    $sentBy = $queryStaff->full_name;
                }

                $result[] = [
                    'campaign_id' => $item['campaign_id'],
                    'name' => $item['name'],
                    'status' => $item['status'],
                    'created_by' => $item['full_name'],
                    'sent_by' => $sentBy,
                    'created_at' => $item['created_at'],
                    'time_sent' => $item['time_sent'],
                ];

            }
            foreach ($result as $key => $value) {
                $new = 0;
                $totalSuccess = 0;
                $totalCancel = 0;

                foreach ($log as $kk => $vv) {

                    if ($value['campaign_id'] == $vv['campaign_id']) {

                        if ($vv['email_status'] == 'sent') {
                            $totalSuccess += 1;
                        } else if ($vv['email_status'] == 'cancel') {
                            $totalCancel += 1;
                        }else if($vv['email_status'] == 'new')
                        {
                            $new +=1;
                        }
                    }
                }

                $result[$key]['total'] = $new+$totalCancel+$totalSuccess;
                $result[$key]['totalSuccess'] = $totalSuccess;
                $result[$key]['totalCancel'] = $totalCancel;
            }

            return $result;
        }
    }

    public function pagingAction(Request $request)
    {
        $page = $request->page;
        //Danh sách chi nhánh.
        $list = $this->email_campaign->listNew();
        $log = $this->email_campaign->getLog();
        $listEmailCampaign = $this->listEmailCampaign($list, $log);
        $result = collect($listEmailCampaign)->forPage($page, 10);

        $contents = view('admin::marketing.email.paging.paging', [
            'data' => $listEmailCampaign,
            'LIST' => $result,
            'page' => $page
        ])->render();
        return $contents;
    }

    protected function filters()
    {
        $staff = $this->staff->getStaffOption();
        return [
            'created_by' => [
                'data' => (['' => __('Chọn người tạo')]) + $staff
            ],
            'sent_by' => [
                'data' => (['' => __('Chọn người gửi')]) + $staff
            ],
            'status' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    'new' => __('Mới'),
                    'sent' => __('Hoàn thành'),
                    'cancel' => __('Hủy')
                ]
            ],
        ];
    }

    public function filterAction(Request $request)
    {
        $keyWord = $request->keyWord;
        $createdBy = $request->createdBy;
        $sentBy = $request->sentBy;
        $status = $request->status;
        $daySent = null;
        if ($request->daySent != null) {
            $daySent = Carbon::createFromFormat('d/m/Y', $request->daySent)->format('Y-m-d');
        }
        $createdAt = null;
        if ($request->createdAt != null) {
            $createdAt = Carbon::createFromFormat('d/m/Y', $request->createdAt)->format('Y-m-d');
        }

        $filters = ['search_keyword' => $keyWord,
            'created_by' => $createdBy,
            'sent_by' => $sentBy,
            'status' => $status,
            'day_sent' => $daySent,
            'created_at' => $createdAt];
        $listCampaign = $this->email_campaign->getListCampaign($filters);

        $log = $this->email_campaign->getLog();

        $listEmailCampaign = $this->listEmailCampaign($listCampaign, $log);

        $result = collect($listEmailCampaign)->forPage(1, 10);
        $contents = view('admin::marketing.email.filter', [
            'data' => $listEmailCampaign,
            'LIST' => $result,
            'page' => 1
        ])->render();
        return $contents;
    }

    public function pagingFilterAction(Request $request)
    {
        $page = intval($request->page);
        $keyWord = $request->keyWord;
        $createdBy = $request->createdBy;
        $sentBy = $request->sentBy;
        $status = $request->status;
        $daySent = $request->daySent;
        $createdAt = $request->createdAt;
        $filters = ['search_keyword' => $keyWord,
            'created_by' => $createdBy,
            'sent_by' => $sentBy,
            'status' => $status,
            'day_sent' => $daySent,
            'created_at' => $createdAt];
        $listCampaign = $this->email_campaign->getListCampaign($filters);

        $log = $this->email_campaign->getLog();

        $listEmailCampaign = $this->listEmailCampaign($listCampaign, $log);

        $result = collect($listEmailCampaign)->forPage(1, 10);
        $contents = view('admin::marketing.email.paging.paging-filter', [
            'data' => $listCampaign,
            'LIST' => $result,
            'page' => $page
        ])->render();
        return $contents;
    }

    public function listAction(Request $request)
    {
//        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword',
//            'is_actived', 'search', 'options', 'status']);
//        $emailList = $this->email_campaign->list($filter);
//        return view('admin::marketing.email.list', [
//            'LIST' => $emailList,
//            'page' => $filter['page']
//        ]);
    }

    public function addAction()
    {
        $branch=$this->branch->getBranchOption();
        return view('admin::marketing.email.add-campaign',[
            'optionBranch'=>$branch
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAddAction(Request $request)
    {
        $input = $request->all();
        $data = [
            'is_deal_created' => $request->is_deal_created,
            'name' => $request->name,
            'cost' => str_replace(",", "", $request->cost),
            'content' => $request->content_campaign,
            'slug' => str_slug($request->name),
            'is_now' => $request->is_now,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'branch_id'=>$request->branch_id
        ];

        if ($request->is_now == 0) {
            $data['value'] = Carbon::createFromFormat('d/m/Y', $request->day_sent)->format('Y-m-d') . ' ' . $request->time_sent;
            if (Carbon::createFromFormat('d/m/Y', $request->day_sent)->format('Y-m-d') . ' ' . $request->time_sent < date('Y-m-d H:i')) {
                return response()->json([
                    'error_time' => 1,
                    'message' => __('Thời gian gửi không hợp lệ')
                ]);
            }
        } else {
            $data['value'] = date('Y-m-d H:i');
        }
        $test = $this->email_campaign->testName(str_slug($request->name), '0');

        if (!empty($test['slug'])) {
            return response()->json([
                'error_slug' => 1,
                'message' => __('Tên chiến dịch đã tồn tại')
            ]);
        }
        $id_add = $this->email_campaign->add($data);

        if (isset($input['amount']) && $input['amount'] == null) {
            $input['amount'] = 0;
        }
        if($request->is_deal_created == 1){
            $mEmailDeal = new EmailDealTable();
            $mEmailDealDetail = new EmailDealDetailTable();
            $dataDeal = [
                'email_campaign_id' => $id_add,
                'pipeline_code' => $input['pipeline_code'],
                'journey_code' => $input['journey_code'],
                'amount' => (float)str_replace(',', '', $input['amount']),
                'closing_date' => Carbon::createFromFormat('d/m/Y', $input['end_date_expected'])->format('Y-m-d H:i'),
                'created_by' => Auth::id()
            ];
            $emailDealId = $mEmailDeal->add($dataDeal);
            // insert deal_detail, order detail
            if (isset($input['arrObject'])) if ($input['arrObject'] != null) {
                foreach ($input['arrObject'] as $key => $value) {
                    $value['price'] = (float)str_replace(',', '', $value['price']);
                    $value['amount'] = (float)str_replace(',', '', $value['amount']);
                    $value['discount'] = (float)str_replace(',', '', $value['discount']);
                    $value['email_deal_id'] = $emailDealId;
                    $value['created_by'] = Auth::id();
                    $dealDetailId = $mEmailDealDetail->add($value);
                }
            }
        }

        return response()->json([
            'success' => 1,
            'message' => __('Tạo chiến dịch thành công'),
            'id_add' => $id_add
        ]);


    }

    public function removeAction($id)
    {
        $this->email_campaign->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function editAction($id,Request $request)
    {
        $mCustomerSource = new CustomerSourceTable();

        $optionCustomerSource = $mCustomerSource->getOption();
        $mPipeline = new PipelineTable();
        $optionPipeline = $mPipeline->getOption('CUSTOMER');
        $optionBranch = $this->branch->getBranchOption();
        $item = $this->email_campaign->getItem($id);
        $list_log = $this->email_log->getItem($id);
        $request->session()->forget('arrCustomerId');
        $request->session()->forget('arrCustomerLeadId');
        $request->session()->forget('listCustomerId');

        $arrCustomer = [];
        if (count($list_log) != 0) {
            foreach ($list_log as $value) {
                $arrCustomer[$value['email']] = $value['email'];
            }
        }

        $request->session()->put('listCustomerId',$arrCustomer);

        if ($item['status'] == 'new') {
            return view('admin::marketing.email.edit', [
                'item' => $item,
                'optionBranch' => $optionBranch,
                "optionPipeline" => $optionPipeline,
                "optionCustomerSources" => $optionCustomerSource,
                'list_log' => $list_log
            ]);
        } else {
            return redirect()->route('admin.email');
        }

    }

    public function submitEditAction(Request $request)
    {
        $input = $request->all();
        $id = $request->campaign_id;
        $data = [
            'is_deal_created' => $request->is_deal_created,
            'cost' => str_replace(",", "", $request->cost),
            'name' => $request->name,
            'content' => $request->content_campaign,
            'slug' => str_slug($request->name),
            'is_now' => $request->is_now,
            'branch_id'=>$request->branch_id_edit,
            'updated_by' => Auth::id(),
        ];
        if ($request->is_now == 0) {
            $data['value'] = Carbon::createFromFormat('d/m/Y', $request->day_sent)->format('Y-m-d') . ' ' . $request->time_sent;
            if (Carbon::createFromFormat('d/m/Y', $request->day_sent)->format('Y-m-d') . ' ' . $request->time_sent < date('Y-m-d H:i')) {
                return response()->json([
                    'error_time' => 1,
                    'message' => __('Thời gian gửi không hợp lệ')
                ]);
            }
        } else {
            $data['value'] = date('Y-m-d H:i');
        }
        $test = $this->email_campaign->testName(str_slug($request->name), $id);
        if (!empty($test['slug'])) {
            return response()->json([
                'error_slug' => 1,
                'message' => __('Tên chiến dịch đã tồn tại')
            ]);
        }
        $this->email_campaign->edit($data, $id);
        // remove deal, deal detail
        $mEmailDeal = new EmailDealTable();
        $mEmailDealDetail = new EmailDealDetailTable();
        $emailDealItem = $mEmailDeal->getItem($id);
        $mEmailDeal->removeItem($id);
        if(isset($emailDealItem['email_deal_id']) != ''){
            $mEmailDealDetail->removeItem($emailDealItem['email_deal_id']);
        }
        if($request->is_deal_created == 1){
            $dataDeal = [
                'email_campaign_id' => $id,
                'pipeline_code' => $input['pipeline_code'],
                'journey_code' => $input['journey_code'],
                'amount' => (float)str_replace(',', '', $input['amount']),
                'closing_date' => Carbon::createFromFormat('d/m/Y', $input['end_date_expected'])->format('Y-m-d H:i'),
                'created_by' => Auth::id()
            ];
            $emailDealId = $mEmailDeal->add($dataDeal);
            // insert deal_detail, order detail
            if (isset($input['arrObject'])) if ($input['arrObject'] != null) {
                foreach ($input['arrObject'] as $key => $value) {
                    $value['price'] = (float)str_replace(',', '', $value['price']);
                    $value['amount'] = (float)str_replace(',', '', $value['amount']);
                    $value['discount'] = (float)str_replace(',', '', $value['discount']);
                    $value['email_deal_id'] = $emailDealId;
                    $value['created_by'] = Auth::id();
                    $dealDetailId = $mEmailDealDetail->add($value);
                }
            }
        }
        return response()->json([
            'success' => 1,
            'message' => __('Cập nhật chiến dịch thành công'),
            'is_now' => $request->is_now,
            'day_sent' => date('d/m/Y'),
            'time_sent' => date('H:i')
        ]);
    }

    public function searchCustomerAction(Request $request)
    {
        $data = $request->data;
        $birthday = $request->birthday;
        $gender = $request->gender;
        $branch_id = $request->branch;
        if ($birthday != '') {
            $birthday_format = Carbon::createFromFormat('d/m/Y', $birthday)->format('Y-m-d');
        } else {
            $birthday_format = null;
        }
        $listCustomerAdd = $request->session()->get('listCustomerId');
        //Lấy danh sách khách hàng
        $list_customer = $this->customer->searchCustomerPhoneEmail($data, $birthday_format, $gender, $branch_id,[],$listCustomerAdd);

        $arr_data = [];
        $listCustomer = [];
        if ($request->session()->has('arrCustomerId')) {
            $listCustomer = $request->session()->get('arrCustomerId');
        }
        foreach ($list_customer as $item) {
            if($item['email']!=null)
            {
                if ($item['birthday'] != null) {
                    $arr_data[] = [
                        'full_name' => $item['full_name'],
                        'customer_id' => $item['customer_id'],
                        'email' => $item['email'],
                        'birthday' => date("d/m/Y", strtotime($item['birthday'])),
                        'gender' => $item['gender'],
                        'gender_name' => $item['gender'] == 'male' ? __('Nam') : ($item['gender'] == 'female' ? __('Nữ') : __('Khác')),
                        'branch_name' => $item['branch_name'],
                        'is_checked' => isset($listCustomer[$item['customer_id']]) ? 1 : 0
                    ];
                } else {
                    $arr_data[] = [
                        'full_name' => $item['full_name'],
                        'customer_id' => $item['customer_id'],
                        'email' => $item['email'],
                        'gender' => $item['gender'],
                        'gender_name' => $item['gender'] == 'male' ? __('Nam') : ($item['gender'] == 'female' ? __('Nữ') : __('Khác')),
                        'branch_name' => $item['branch_name'],
                        'is_checked' => isset($listCustomer[$item['customer_id']]) ? 1 : 0
                    ];
                }
            }
        }

        return response()->json([
            'arr_data' => $arr_data,
        ]);
    }

    /**
     * Ds khách hàng thuộc nhóm KH đã chọn
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCustomerGroupAction(Request $request)
    {
        $filterTypeGroup = $request->filter_type_group;
        $customerGroupFilter = $request->customer_group_filter;

        $data = $this->customer->searchCustomerGroupFilter($filterTypeGroup,$customerGroupFilter);
        $arr_data = [];
        foreach ($data as $item) {
//            if($item['email']!=null)
//            {
                if ($item['birthday'] != null) {
                    $arr_data[] = [
                        'full_name' => $item['full_name'],
                        'customer_id' => $item['customer_id'],
                        'email' => $item['email'],
                        'birthday' => date("d/m/Y", strtotime($item['birthday'])),
                        'gender' => $item['gender'],
                        'gender_name' => $item['gender'] == 'male' ? __('Nam') : ($item['gender'] == 'female' ? __('Nữ') : __('Khác')),
                        'branch_name' => $item['branch_name'],
                        'is_checked' => 0
                    ];
                } else {
                    $arr_data[] = [
                        'full_name' => $item['full_name'],
                        'customer_id' => $item['customer_id'],
                        'email' => $item['email'],
                        'gender' => $item['gender'],
                        'gender_name' => $item['gender'] == 'male' ? __('Nam') : ($item['gender'] == 'female' ? __('Nữ') : __('Khác')),
                        'branch_name' => $item['branch_name'],
                        'is_checked' => 0
                    ];
                }
//            }
        }

        return response()->json([
            'arr_data' => $arr_data,
        ]);
    }

    /**
     * thông tin KHTN
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCustomerLeadAction(Request $request)
    {
        $input = $request->all();
        $listCustomerAdd = $request->session()->get('listCustomerId');
        $input['listCustomerAdd'] = $listCustomerAdd;
        $listCustomerLead = [];
        if ($request->session()->has('arrCustomerLeadId')) {
            $listCustomerLead = $request->session()->get('arrCustomerLeadId');
        }
        $data = $this->email_campaign->searchCustomerLeadFilter($input);
        $arr_data = [];
        foreach ($data as $item) {
            $arr_data[] = [
                'email' => $item['email'],
                'full_name' => $item['full_name'],
                'sale_name' => $item['sale_name'],
                'sale_status' => $item['sale_status'],
                'customer_lead_id' => $item['customer_lead_id'],
                'customer_type' => $item['customer_type'],
                'customer_source_name' => $item['customer_source_name'],
                'pipeline_name' => $item['pipeline_name'],
                'journey_name' => $item['journey_name'],
                'is_checked' => isset($listCustomerLead[$item['customer_lead_id']]) ? 1 : 0
            ];
        }

        return response()->json([
            'arr_data' => $arr_data,
        ]);
    }

    public function appendAction(Request $request)
    {
        $mCustomerLead = new CustomerLeadTable();
        $item_campaign = $this->email_campaign->getItem($request->campaign_id);
        $data_list = [];
        $listCustomer = [];
        $listCustomerLead = [];
        if ($request->session()->has('arrCustomerId')) {
            $listCustomer = $request->session()->get('arrCustomerId');
        }
        if ($request->session()->has('arrCustomerLeadId')) {
            $listCustomerLead = $request->session()->get('arrCustomerLeadId');
        }
        $listCustomerAdd = $request->session()->get('listCustomerId');

//        foreach ($request->list as $value) {
        foreach ($listCustomer as $value) {
            $customer_list = $this->customer->getItem($value);
            if(isset($customer_list['email']) != ''){
                $gender_sub = null;
                if ($customer_list['gender'] == 'male') {
                    $gender_sub = __('Anh');
                } else if ($customer_list['gender'] == 'female') {
                    $gender_sub = __('Chị');
                } else {
                    $gender_sub = __('Anh/Chị');
                }
                if ($customer_list['birthday'] != null) {
                    $birthday = date('d/m/Y', strtotime($customer_list['birthday']));
                } else {
                    $birthday = '';
                }
                //Lấy tên trong chuỗi full name
                $string = $customer_list['full_name'];
                $pieces = explode(' ', $string);
                $last_name = array_pop($pieces);
                //replace giá trị của tham số
                $search = array('{name}', '{full_name}', '{gender}', '{birthday}');
                $replace = array($last_name, $customer_list['full_name'], $gender_sub, $birthday);
                $subject = $item_campaign['content'];
                $returnValue = str_replace($search, $replace, $subject);
                $data_list[] = [
                    'customer_id' => $customer_list['customer_id'],
                    'customer_name' => $customer_list['full_name'],
                    'email' => $customer_list['email'],
                    'content' => $returnValue,
                ];
                $listCustomerAdd[$customer_list['email']] = $customer_list['email'];
            }
        }

        $request->session()->put('listCustomerId',$listCustomerAdd);

        foreach ($listCustomerLead as $value) {
            $customer_lead_list = $mCustomerLead->getInfo($value);
            if(isset($customer_lead_list['email']) != ''){
                $gender_sub = null;
                if ($customer_lead_list['gender'] == 'male') {
                    $gender_sub = __('Anh');
                } else if ($customer_lead_list['gender'] == 'female') {
                    $gender_sub = __('Chị');
                } else {
                    $gender_sub = __('Anh/Chị');
                }
                if ($customer_lead_list['birthday'] != null) {
                    $birthday = date('d/m/Y', strtotime($customer_lead_list['birthday']));
                } else {
                    $birthday = '';
                }
                //Lấy tên trong chuỗi full name
                $string = $customer_lead_list['full_name'];
                $pieces = explode(' ', $string);
                $last_name = array_pop($pieces);
                //replace giá trị của tham số
                $search = array('{name}', '{full_name}', '{gender}', '{birthday}');
                $replace = array($last_name, $customer_lead_list['full_name'], $gender_sub, $birthday);
                $subject = $item_campaign['content'];
                $returnValue = str_replace($search, $replace, $subject);
                $data_list[] = [
                    'customer_lead_id' => $customer_lead_list['customer_lead_id'],
                    'customer_name' => $customer_lead_list['full_name'],
                    'email' => $customer_lead_list['email'],
                    'content' => $returnValue,
                ];
                $listCustomerAdd[$customer_lead_list['email']] = $customer_lead_list['email'];
            }
        }

        $request->session()->put('listCustomerId',$listCustomerAdd);
        return response()->json([
            'data_list' => $data_list
        ]);

    }

    public function removeLogAction(Request $request) {
        $email = $request->email;
        $listCustomerAdd = $request->session()->get('listCustomerId');
        if (isset($listCustomerAdd[$email])) {
            unset($listCustomerAdd[$email]);
        }

        $request->session()->put('listCustomerId',$listCustomerAdd);

        return response()->json(['error' => 0]);
    }

    public function saveLogAction(Request $request)
    {
        DB::beginTransaction();
        try {
            $item_campaign = $this->email_campaign->getItem($request->campaign_id);
            $list_dt = $this->email_log->getItem($request->campaign_id);
            if ($request->list_old != null) {
                $arr = $request->list_old;
            } else {
                $arr = [];
            }
            $arr_id = [];

            foreach ($list_dt as $k => $v) {
                $arr_id[] = $v['id'];
            }
            $cut = array_diff($arr_id, $arr);
            foreach ($cut as $i) {

                $this->email_log->remove($i);
            }
            if ($request->list_send != null) {
                $aData = array_chunk($request->list_send, 5, false);
                foreach ($aData as $key => $value) {
                    if ($value[1] != null) {
                        $data = [
                            'type_customer' => $value[3],
                            'object_type' => $value[3],
                            'object_id' => $value[4],
                            'customer_name' => $value[0],
                            'email' => $value[1],
                            'content_sent' => $value[2],
                            'email_status' => 'new',
                            'campaign_id' => $request->campaign_id,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'time_sent' => $item_campaign['value']
                        ];
                        $this->email_log->add($data);
                    }

                }
            }

            $request->session()->forget('arrCustomerId');
            $request->session()->forget('arrCustomerLeadId');
            DB::commit();
            return response()->json([
                'success' => 1,
                'message' => 'Lưu log thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function detailAction($id)
    {
        $item = $this->email_campaign->getItem($id);
        $optionBranch = $this->branch->getBranch();
        $groupNew = $this->email_log->groupStatus($id, 'new');
        $groupCancel = $this->email_log->groupStatus($id, 'cancel');
        $groupSent = $this->email_log->groupStatus($id, 'sent');
//        $list_log = $this->email_log->getItem($id);
        $log = $this->email_log->list($id);

        return view('admin::marketing.email.detail', [
            'item' => $item,
            'LIST' => $log,
            'optionBranch' => $optionBranch,
            'groupNew' => $groupNew,
            'groupCancel' => $groupCancel,
            'groupSent' => $groupSent,
        ]);
    }
    public function listDetailAction(Request $request, $id)
    {
        $filter = $request->only(['page', 'display', 'search_keyword']);
        $log = $this->email_log->list($id, $filter);
        return view('admin::marketing.email.list-detail', [
            'LIST' => $log,
            'page' => $filter['page']
        ]);
    }
    public function sendMailAction(Request $request)
    {
        DB::beginTransaction();
        try {
            $get_provider = $this->email_provider->getItem(1);
            $provider = DB::table('email_provider')->select('type','email_template_id')->where('id', 1)->first();
            if ($request->list_old != null) {
                $arr = $request->list_old;
            } else {
                $arr = [];
            }
            $arr_id = [];
            $list_dt = $this->email_log->getItem($request->campaign_id);
            foreach ($list_dt as $k => $v) {
                $arr_id[] = $v['id'];
            }
            $cut = array_diff($arr_id, $arr);
            foreach ($cut as $i) {

                $this->email_log->remove($i);
            }
            $list_dt = $this->email_campaign->getItem($request->campaign_id);
            if ($request->list_send != null) {
                $aData = array_chunk($request->list_send, 3, false);
                foreach ($aData as $key => $value) {
                    if ($value[1] != null) {
                        $data = [
                            'customer_name' => $value[0],
                            'email' => $value[1],
                            'content_sent' => $value[2],
                            'email_status' => 'new',
                            'campaign_id' => $request->campaign_id,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'time_sent' => date('Y-m-d H:i')
                        ];
                        $id_add = $this->email_log->add($data);
                        $name = $value[0];
                        $content = $value[2];
                        //check template

//                        Mail::to($value[1])->send(new SendMailable($name, $list_dt['name'], $content, '', '',$provider->email_template_id));
//                        $data_edit = [
//                            'time_sent_done' => date('Y-m-d H:i'),
//                            'provider' => $get_provider['type'],
//                            'sent_by' => Auth::id(),
//                            'email_status' => 'sent'
//                        ];
//                        $this->email_log->edit($data_edit, $id_add);
                    }

                }
            }

//            if ($request->list_old != null) {
//                $aData = array_chunk($request->list_old, 4, false);
//                foreach ($aData as $item) {
//                    $name = $item[1];
//                    $content = $item[3];
//                    Mail::to($item[2])->send(new SendMailable($name, $list_dt['name'], $content, '', '',$provider->email_template_id));
//                    $data_edit = [
//                        'time_sent_done' => date('Y-m-d H:i'),
//                        'provider' => $get_provider['type'],
//                        'sent_by' => Auth::id(),
//                        'email_status' => 'sent'
//                    ];
//                    $this->email_log->edit($data_edit, $item[0]);
//                }
//            }
            $data_campaign = [
                'sent_by' => Auth::id(),
                'time_sent' => date('Y-m-d H:i'),
                'status' => 'sent'
            ];
            $this->email_campaign->edit($data_campaign, $request->campaign_id);
            DB::commit();
            return response()->json([
                'success' => 1,
                'message' => 'Gửi mail thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function exportExcelAction(Request $request)
    {
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return Excel::download(new EmailExport(), 'email.xlsx');
    }
    public function importExcelAction(Request $request)
    {
        $item_campaign = $this->email_campaign->getItem($request->campaign_id);
        $file = $request->file('file');
        if (isset($file)) {
            $typeFileExcel = $file->getClientOriginalExtension();
            $arrayCustomer = [];
            if ($typeFileExcel == "xlsx") {
                $reader = ReaderFactory::create(Type::XLSX);
                $reader->open($file);
                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $key => $row) {

                        if ($key == 1) {

                        } elseif ($key != 1 && $row[0] != '' && $row[1] != '') {
                            $gender_sub =  'Anh/Chị';
                            $birthday='';
                            //Lấy tên trong chuỗi full name
                            $name =$row[0];
                            $pieces = explode(' ', $name);
                            $last_name = array_pop($pieces);
                            //replace giá trị của tham số
                            $search = array('{name}', '{full_name}', '{gender}', '{birthday}');
                            $replace = array($last_name, $row[0], $gender_sub, $birthday);
                            $subject = $item_campaign['content'];
                            $returnValue = str_replace($search, $replace, $subject);
                            $arrayCustomer[] = [
                                'customer_name' => $row[0],
                                'email' => $row[1],
                                'content' => $returnValue,
                            ];

                        }
                    }
                }

                $reader->close();
            }

            return response()->json([
                'list_customer'=>$arrayCustomer
            ]);
        }
    }

    public function cancelAction($id)
    {
        $data_campaign=[
          'status'=>'cancel'
        ];
        //cập nhật trạng thái campaign
        $this->email_campaign->edit($data_campaign,$id);
        //cập nhật trạng thái log
        $list_log=$this->email_log->getItem($id);
        foreach ($list_log as $item)
        {
            if($item['email_status']=='new')
            {
                $data=[
                    'email_status'=>'cancel'
                ];
                $this->email_log->edit($data,$item['id']);
            }
        }
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    /**
     * Xử lý lưu session khi check chọn customer/customer group define
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCustomer(Request $request) {
        $param = $request->all();
        $listCustomerId = [];
        if ($request->session()->has('arrCustomerId')) {
            $listCustomerId = $request->session()->get('arrCustomerId');
        }

        if (isset($param['customer_id'])) {
            if (isset($param['check']) && $param['check'] == 1) {
                $customerId = $param['customer_id'];
                if (isset($param['status']) && $param['status'] == 'add') {
                    foreach ($customerId as $item) {
                        if (!isset($listCustomerId[$item])){
                            $listCustomerId[$item] = $item;
                        }
                    }
                } else {
                    foreach ($customerId as $item) {
                        if (isset($listCustomerId[$item])){
                            unset($listCustomerId[$item]);
                        }
                    }
                }
            } else {
                $customerId = json_decode($param['customer_id'],true);
                foreach ($customerId as $item) {
                    if (isset($listCustomerId[$item])){
                        unset($listCustomerId[$item]);
                    } else {
                        $listCustomerId[$item] = $item;
                    }
                }
            }

            $request->session()->put('arrCustomerId',$listCustomerId);
        }

        return response()->json($listCustomerId);
    }

    /**
     * Xử lý lưu session khi check chọn lead
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCustomerLead(Request $request) {
        $param = $request->all();
        $listCustomerId = [];
        if ($request->session()->has('arrCustomerLeadId')) {
            $listCustomerId = $request->session()->get('arrCustomerLeadId');
        }
        if (isset($param['customer_lead_id'])) {
            if (isset($param['check']) && $param['check'] == 1) {
                $customerId = $param['customer_lead_id'];
                if (isset($param['status']) && $param['status'] == 'add') {
                    foreach ($customerId as $item) {
                        if (!isset($listCustomerId[$item])){
                            $listCustomerId[$item] = $item;
                        }
                    }
                }
                else {
                    foreach ($customerId as $item) {
                        if (isset($listCustomerId[$item])){
                            unset($listCustomerId[$item]);
                        }
                    }
                }
            }
            else {
                $customerId = json_decode($param['customer_lead_id'],true);
                foreach ($customerId as $item) {
                    if (isset($listCustomerId[$item])){
                        unset($listCustomerId[$item]);
                    }
                    else {
                        $listCustomerId[$item] = $item;
                    }
                }
            }
            $request->session()->put('arrCustomerLeadId',$listCustomerId);
        }

        return response()->json($listCustomerId);
    }


    /**
     * Xoá session customer/group
     *
     * @param Request $request
     */
    public function deleteSession(Request $request) {
        $request->session()->forget('arrCustomerId');
    }

    /**
     * Xoá session lead
     *
     * @param Request $request
     */
    public function deleteSessionLead(Request $request) {
        $request->session()->forget('arrCustomerLeadId');
    }

    /**
     * Popup tạo deal khi click "thêm thông tin deal"
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popupCreateDeal(Request  $request){
        $data = $this->email_campaign->popupCreateDeal($request->all());

        return response()->json($data);
    }

    /**
     * Popup chỉnh sửa deal khi click "thêm thông tin deal
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popupEditDeal(Request  $request){
        $data = $this->email_campaign->popupEditDeal($request->all());

        return response()->json($data);
    }

}