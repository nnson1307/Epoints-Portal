<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/07/2021
 * Time: 13:45
 */

namespace Modules\OnCall\Repositories\Extension;


use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Modules\Contract\Models\ContractMapOrderTable;
use Modules\Contract\Models\ContractSpendTable;
use Modules\Contract\Models\ContractTable;
use Modules\Admin\Models\CustomerGroupTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\OrderTable;
use Modules\Contract\Models\ReceiptTable;
use Modules\CustomerLead\Models\CustomerCareTable;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Models\CustomerSourceTable;
use Modules\CustomerLead\Models\DistrictTable;
use Modules\CustomerLead\Models\JourneyTable;
use Modules\CustomerLead\Models\PipelineTable;
use Modules\CustomerLead\Models\ProvinceTable;
use Modules\CustomerLead\Models\StaffsTable;
use Modules\CustomerLead\Repositories\CustomerLead\CustomerLeadRepoInterface;
use Modules\ManagerWork\Http\Api\SendNotificationApi;
use Modules\ManagerWork\Models\ManageRedmindTable;
use Modules\ManagerWork\Models\ManageRepeatTimeTable;
use Modules\ManagerWork\Models\ManagerWorkTable;
use Modules\ManagerWork\Models\ManagerWorkTagTable;
use Modules\ManagerWork\Models\ManageWorkSupportTable;
use Modules\ManagerWork\Models\TypeWorkTable;
use Modules\OnCall\Models\AccountTable;
use Modules\OnCall\Models\ConfigTable;
use Modules\OnCall\Models\CustomerRealCareTable;
use Modules\OnCall\Models\ExtensionTable;
use Modules\OnCall\Models\HistoryTable;
use Modules\OnCall\Models\StaffTable;
use Modules\Ticket\Models\TicketTable;

class ExtensionRepo implements ExtensionRepoInterface
{
    protected $extension;

    public function __construct(
        ExtensionTable $extension
    )
    {
        $this->extension = $extension;
    }

    /**
     * Show pop cấu hình tài khoản
     *
     * @return mixed|void
     */
    public function showModalAccount()
    {
        $mAccount = app()->get(AccountTable::class);
        //Lấy thông tin account
        $info = $mAccount->getInfo();

        if (isset($info['password']) && !empty($info['password'])) {
            $info['password'] = Crypt::decryptString($info['password']);
        }

        $html = \View::make('on-call::extension.pop.pop-account', [
            "item" => $info,
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Cấu hình tài khoản
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function submitSetting($input)
    {
        try {
            $mAccount = app()->get(AccountTable::class);

            //Call api setting account on call
            $setting = $this->_callApiSetting($input);

            if ($setting->ErrorCode != 1) {
                $input['password'] = Crypt::encryptString($input['password']);

                if ($input['id'] != null) {
                    //Chỉnh sửa
                    $mAccount->edit($input, $input['id']);
                } else {
                    //Tạo mới
                    $mAccount->add($input);
                }
            } else {
                return response()->json([
                    'error' => true,
                    'message' => $setting->ErrorDescription,
                ]);
            }

            return response()->json([
                'error' => false,
                'message' => __('Cấu hình tài khoản thành công'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Cấu hình tài khoản thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Call api lấy cấu hình on call
     *
     * @param $input
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function _callApiSetting($input)
    {
        $oClient = new Client();

        $mAccount = app()->get(AccountTable::class);
        $mConfig = app()->get(ConfigTable::class);

        //Lấy thông tin cấu hình key + secret
        $key = $mConfig->getInfoByKey('oncall_key')['value'];
        $secret = $mConfig->getInfoByKey('oncall_secret')['value'];

        //Lấy thông tin account
        $info = $mAccount->getInfo();

        //Gọi api thực hiện cuộc gọi
        $response = $oClient->request('POST', env('DOMAIN_ONCALL') . '/oncall/setting/verify', [
            'headers' => [
                'tenant' => session()->get('brand_code'),
                'key' => $key,
                'secret' => $secret
            ],
            'json' => [
                'name' => $input['user_name'],
                'password' => $input['password'],
                'enable_webhook' => $info['enabled_webhook'],
                'webhook' => $info['link_webhook']
            ]
        ]);

        return json_decode($response->getBody());
    }

    /**
     * Sync dữ liệu tự on call
     *
     * @return mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function syncExtension()
    {
        try {
            //Call api lấy ds extension
            $getExtensionOnCall = $this->_callApiGetExtension();

            if ($getExtensionOnCall->ErrorCode == 0 && count($getExtensionOnCall->Data) > 0) {

                $arrExtension = [];

                foreach ($getExtensionOnCall->Data as $v) {
                    $status = $v->status == 'OFFLINE' ? 0 : 1;

                    //Lấy thông tin extension
                    $info = $this->extension->getInfoByExtension($v->extension_number);

                    if ($info != null) {
                        //Update
                        $this->extension->edit([
                            "extension_number" => $v->extension_number,
                            "full_name" => $v->first_name . ' ' . $v->last_name,
                            "user_agent" => $v->user_agent,
                            "email" => $v->email,
                        ], $info['extension_id']);
                    } else {
                        //Insert
                        $this->extension->add([
                            "extension_number" => $v->extension_number,
                            "full_name" => $v->first_name . ' ' . $v->last_name,
                            "user_agent" => $v->user_agent,
                            "email" => $v->email,
                            "created_by" => Auth()->id(),
                            "updated_by" => Auth()->id()
                        ]);
                    }

                    $arrExtension [] = $v->extension_number;
                }

                //Xoá những extension không tồn tại
                $this->extension->removeExtensionNotExist($arrExtension);
            }

            return response()->json([
                'error' => false,
                'message' => __('Đồng bộ thành công'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Đồng bộ thất bại'),
            ]);
        }
    }

    /**
     * Call api lấy extension
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function _callApiGetExtension()
    {
        $oClient = new Client();

        $mConfig = app()->get(ConfigTable::class);
        //Lấy thông tin cấu hình key + secret
        $key = $mConfig->getInfoByKey('oncall_key')['value'];
        $secret = $mConfig->getInfoByKey('oncall_secret')['value'];

        //Gọi api thực hiện cuộc gọi
        $response = $oClient->request('POST', env('DOMAIN_ONCALL') . '/oncall/extension/list', [
            'headers' => [
                'tenant' => session()->get('brand_code'),
                'key' => $key,
                'secret' => $secret
            ],
            'json' => [
                'pagination' => 1
            ]
        ]);

        return json_decode($response->getBody());
    }

    /**
     * Lấy danh sách extension
     *
     * @param $input
     * @return mixed|void
     */
    public function list($input = [])
    {
        //Lấy danh sách extension
        $list = $this->extension->getList($input);

        return [
            "list" => $list
        ];
    }

    /**
     * Lấy option view danh sách
     *
     * @return array|mixed
     */
    public function getOption()
    {
        $mStaff = app()->get(StaffTable::class);
        //Lấy option staff
        $getStaff = $mStaff->getStaff();

        $arrayStaff = [];

        foreach ($getStaff as $item) {
            $arrayStaff[$item['staff_id']] = $item['full_name'];
        }

        return [
            'optionStaff' => $arrayStaff
        ];
    }

    /**
     * Show modal phân bổ nhân viên
     *
     * @param $input
     * @return mixed|void
     */
    public function showModalAssign($input)
    {

        //Lấy thông tin extension
        $info = $this->extension->getInfo($input['extension_id']);
        $mStaff = app()->get(StaffTable::class);
        //Lấy option nhân viên
        $getStaff = $mStaff->getStaff();

        $html = \View::make('on-call::extension.pop.pop-assign', [
            "item" => $info,
            "optionStaff" => $getStaff
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Phân bổ nhân viên
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function submitAssign($input)
    {
        try {
            //Cập nhật extension
            $this->extension->edit([
                'staff_id' => $input['staff_id']
            ], $input['extension_id']);

            return response()->json([
                'error' => false,
                'message' => __('Phân bổ nhân viên thành công'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Phân bổ nhân viên thất bại'),
            ]);
        }
    }

    /**
     * Cập nhật trạng thái
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function updateStatus($input)
    {
        try {
            //Cập nhật extension
            $this->extension->edit([
                'status' => $input['status']
            ], $input['extension_id']);

            return response()->json([
                'error' => false,
                'message' => __('Cập nhật trạng thái thành công'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Cập nhật trạng thái thất bại'),
            ]);
        }
    }

    /**
     * show popup khi có cuộc gọi đến
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModalCalling($input)
    {
        try{

            $input = $input['dataArray'];
            // đánh dấu popup đã show
            $historyId = $input['history_id'];
            $mOcHistory = new HistoryTable();
            $dataHistory = $mOcHistory->getInfo($historyId);

            if($dataHistory != null && $dataHistory['history_type'] == 'in'){
                $mOcHistory->edit([
                    'status' => 1,
                    'error_text' => ''
                ], $historyId);
            }

            $mCustomerLeadRepoInterface = app()->get(CustomerLeadRepoInterface::class);
            $mCustomer = app()->get(CustomerTable::class);
            $mCustomerLead = app()->get(CustomerLeadTable::class);
            $mProvince = app()->get(ProvinceTable::class);
            $mDistrict = app()->get(DistrictTable::class);
            $mCustomerGroup = app()->get(CustomerGroupTable::class);
            $mCustomerSource = app()->get(CustomerSourceTable::class);
            $mPipeline = app()->get(PipelineTable::class);
            $mJourney = app()->get(JourneyTable::class);
            $mCustomerCare = app()->get(CustomerCareTable::class);
            $mManageTypeWork = app()->get(TypeWorkTable::class);
            $mStaff = app()->get(StaffsTable::class);
            $mManageWork = app()->get(ManagerWorkTable::class);
            // region data info
            $item = $input['dataCustomer'];
            $type = $item['type'];
            $id = '';
            $phone = '';
            $code = '';
            if($type == 'customer'){
                $id = $item['customer_id'];
                $item = $mCustomer->getItemFull($id);
                $code = $item['customer_code'];
                $phone = $item['phone1'];
            } else{

                $id = $item['customer_lead_id'];
                $item = $mCustomerLead->getInfo($id);
                $code = $item['customer_lead_code'];
                $phone = $item['phone'];
            }
            $optionProvinces = $mProvince->getOptionProvince();
            $optionDistricts = $mDistrict->getOptionDistrict($item['province_id']);
            $optionCustomerGroups = $mCustomerGroup->getOption();
            $optionCustomerSources = $mCustomerSource->getOption();
            $optionStaffs = $mStaff->getOption();
            $optionPipelines = $mPipeline->getOption('CUSTOMER');
            $optionJourney = $mJourney->getJourneyByPipeline(isset($item['pipeline_code']) ? $item['pipeline_code'] : '');
            // end region

            // region history order
            $mOrder = new OrderTable();
            $dataOrder = $mOrder->getOrderByCustomer($id);
            // end region

            // region list contract
            $mConfig = new \Modules\Admin\Models\ConfigTable();
            $config = $mConfig->getInfoByKey('contract');
            $dataContract = null;
            if(($config != null && $config['value'] == 1) && $type == 'customer'){
                $mContract = new ContractTable();
                $mContractMapOrder = app()->get(ContractMapOrderTable::class);
                $mReceipt = app()->get(ReceiptTable::class);
                $mContractSpend = app()->get(ContractSpendTable::class);
                $dataContract = $mContract->getListContractByCustomer($id);
                foreach ($dataContract as $k => $v) {
                    //Lấy đơn hàng gần nhất map với hợp đồng
                    $getOrder = $mContractMapOrder->getOrderMap($v['contract_code']);

                    $orderCode = null;
                    $totalReceipt = 0;
                    $totalNotReceipt = 0;
                    if ($v['type'] == 'sell' && $getOrder != null) {
                        //Hợp đồng bán
                        $orderCode = $getOrder['order_code'];

                        //Lấy tiền đã thu của đơn hàng
                        $getAmountPaid = $mReceipt->getReceiptOrder($getOrder['order_id']);

                        $totalReceipt += $getAmountPaid != null ? $getAmountPaid['amount_paid'] : 0;

                        $totalNotReceipt = floatval($getOrder['amount']) - floatval($totalReceipt);
                    }
                    else if ($v['type'] == 'buy') {
                        //Hợp đồng mua

                        //Lấy tiền đã thu của HĐ
                        $getAmountPaid = $mContractSpend->getAmountSpend($v['contract_id']);

                        $totalReceipt += $getAmountPaid != null ? $getAmountPaid['total_amount'] : 0;

                        $totalNotReceipt = floatval($v['last_total_amount']) - floatval($totalReceipt);
                    }
                    $dataContract[$k]['total_receipt'] = $totalReceipt;
                    $dataContract[$k]['total_not_receipt'] = $totalNotReceipt;
                }
            }
            // end region

            // region history ticket
            $mTicket = new TicketTable();
            $dataTicket = $mTicket->getListTicketFromOncall($id);
            // end region

            // region care

            //Lấy lịch sử chăm sóc KH
            $mCustomerRealCare = new CustomerRealCareTable();
            if($type == 'lead'){
                $getCare = collect($mCustomerCare->getCustomerCare($item['customer_lead_code'])->toArray());
                $dataCare = $getCare->groupBy('created_group');
            } else {
                $getCare = collect($mCustomerRealCare->getCustomerCare($item['customer_code'])->toArray());
                $dataCare = $getCare->groupBy('created_group');
            }

            $detailWork = null;
            $is_booking = 0;
            $listStatus = $mCustomerLeadRepoInterface->getListStatusWork();
            $listTypeWork = $mManageTypeWork->getListTypeWork(1);
            $listStaff = $mStaff->getListStaffByFilter([]);
            $listCustomer = [];
            if($type == 'lead') {
                $listCustomer = $mCustomerLead->getAllListCustomerLeadWorkManagement();
            } else {
                $listCustomer = $mCustomer->getAllListCustomerWorkManagement();
            }

            $data = [
                'customer_id' => $id,
                'manage_work_customer_type' => $type,
                'type_search' => 'support'
            ];

            $listWork = $mManageWork->getListWorkByCustomer($data);
            // end region care
            // get list deal
            $mCustomerDeal = new CustomerDealTable();
            $filterDeal['perpage'] = 3;
            $filterDeal['oncall_type'] = $type;
            $filterDeal['oncall_code'] = $code;
            $listDeal = $mCustomerDeal->getListFromOncall($filterDeal);

            $html = \View::make('on-call::on-calling.modal-calling', [
                'optionProvinces' => $optionProvinces,
                'optionDistricts' => $optionDistricts,
                'optionCustomerGroups' => $optionCustomerGroups,
                'optionCustomerSources' => $optionCustomerSources,
                'optionStaffs' => $optionStaffs,
                'optionPipelines' => $optionPipelines,
                'optionJourney' => $optionJourney,
                'item' => $item,
                'history_id' => $historyId,
                'phone' => $phone,
                'id' => $id,
                'code' => $code,
                'type' => $type,
                'LIST_ORDER' => $dataOrder,
                'LIST_CONTRACT' => $dataContract,
                'LIST_TICKET' => $dataTicket,
                'LIST_DEAL' => $listDeal,
                'dataCare' => $dataCare,
                'listTypeWork' => $listTypeWork,
                'listStaff' => $listStaff,
                'listCustomer' => $listCustomer,
                'detailWork' => $detailWork,
                'listStatus' => $listStatus,
                'listWork' => $listWork
            ])->render();


            return response()->json([
                'html' => $html
            ]);

        }
        catch (\Exception $e){
            return response()->json([
                'html' => '',
                'message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    /**
     * Search ds công việc ở popup cuộc gọi đến
     *
     * @param $input
     * @return array
     * @throws \Throwable
     */
    public function searchWorkLead($input)
    {
        try {
            $mMangeWork = new ManagerWorkTable();
            $listWork = $mMangeWork->getListWorkByCustomer($input);

            if ($input['type_search'] == 'history'){
                $view = view('on-call::on-calling.append.append-list-history-work-child',[
                    'historyWork' => $listWork
                ])->render();
            } else {
                $view = view('on-call::on-calling.append.append-list-work-child',[
                    'listWork' => $listWork
                ])->render();
            }

            return [
                'error' => false,
                'view' => $view
            ];

        } catch(\Exception $e){
            return [
                'error' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lưu chăm sóc/công việc ở popup cuộc gọi đến
     *
     * @param $data
     * @return array
     */
    public function submitCareFromOncall($data)
    {
        try {
            $mCustomerCare = app()->get(CustomerCareTable::class);
            $mCustomerRealCare = app()->get(CustomerRealCareTable::class);
            $mManageWork = app()->get(ManagerWorkTable::class);
            $sendNoti = app()->get(SendNotificationApi::class);
            $mManageTypeWork = app()->get(TypeWorkTable::class);
            $mCustomerLead = app()->get(CustomerLeadTable::class);
            $mCustomer = app()->get(CustomerTable::class);

            DB::beginTransaction();

            if (isset($data['date_start'])){
                $date_start = isset($data['time_start']) ? Carbon::createFromFormat('d/m/Y',$data['date_start'])->format('Y-m-d '.$data['time_start'].':00') : Carbon::createFromFormat('d/m/Y',$data['date_start'])->format('Y-m-d 00:00:00');
                $date_end = isset($data['time_end']) ? Carbon::createFromFormat('d/m/Y',$data['date_end'])->format('Y-m-d '.$data['time_end'].':00') : Carbon::createFromFormat('d/m/Y',$data['date_end'])->format('Y-m-d 23:59:59');
                if ($date_start > $date_end){
                    return [
                        'error' => true,
                        'message' => 'Thời gian bắt đầu phải nhỏ hơn thời gian kết thúc'
                    ];
                }
            }

            $mManageRemind = app()->get(ManageRedmindTable::class);
            $messageErrorRemind = null;
            if (isset($data['date_remind']) || (isset($data['description_remind']) && strlen(strip_tags($data['description_remind'])) != 0)){

                if (!isset($data['staff'])) {
                    $messageErrorRemind = $messageErrorRemind. __('Vui lòng chọn nhân viên được nhắc'). '<br>';
                }
                if (!isset($data['date_remind'])){
                    $messageErrorRemind = $messageErrorRemind.__('Vui lòng chọn thời gian nhắc'). '<br>';
                }
                if ( isset($data['description_remind']) && strlen(strip_tags($data['description_remind'])) == 0){
                    $messageErrorRemind = $messageErrorRemind.__('Vui lòng nhập nội dung nhắc'). '<br>';
                }
            }

            if ($messageErrorRemind != null){
                return [
                    'error' => true,
                    'message'=> $messageErrorRemind
                ];
            }

            if (isset($data['date_remind']) && strlen(strip_tags($data['description_remind'])) != 0){
                $data['time_remind'] = str_replace(',', '', $data['time_remind']);
                $messageError = $this->checkRemind($data);
                if ($messageError != null){
                    return [
                        'error' => true,
                        'message'=> $messageError
                    ];
                }
            }

            $dataWork = [
                'manage_work_code' => $this->codeWork(),
                'manage_type_work_id' => $data['manage_type_work_id'],
                'manage_work_customer_type' => $data['manage_work_customer_type'],
                'manage_work_title' => strip_tags($data['manage_work_title']),
                'date_end' => $data['is_booking'] == 0 ? Carbon::now() : (isset($data['time_end']) ? Carbon::createFromFormat('d/m/Y',$data['date_end'])->format('Y-m-d '.$data['time_end'].':00') : Carbon::createFromFormat('d/m/Y',$data['date_end'])->format('Y-m-d 23:59:59')),
                'processor_id' => isset($data['processor_id']) ? $data['processor_id'] : Auth::id(),
                'assignor_id' => Auth::id(),
                'obj_id' => $data['obj_id'],
                'time' => isset($data['time']) ? strip_tags($data['time']) : null,
                'time_type' => isset($data['time_type']) ? $data['time_type'] : null,
                'progress' => isset($data['progress']) ? $data['progress'] : 0,
                'customer_id' => $data['obj_id'],
                'description' => isset($data['content']) ? $data['content'] : null,
                'approve_id' => isset($data['approve_id']) ? $data['approve_id'] : null,
                'is_approve_id' => isset($data['is_approve_id']) ? 1 : 0,
                'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : null,
                'type_card_work' => 'bonus',
                'priority' => isset($data['priority']) ? $data['priority'] : null,
                'manage_status_id' => $data['is_booking'] == 0 ? 6 : $data['manage_status_id'],
                'is_booking' => $data['is_booking'],
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_by' => Auth::id(),
                'created_by' => Auth::id(),
            ];

            if (isset($data['date_start'])){
                $dataWork['date_start'] = isset($data['time_start']) ? Carbon::createFromFormat('d/m/Y',$data['date_start'])->format('Y-m-d '.$data['time_start'].':00') : Carbon::createFromFormat('d/m/Y',$data['date_start'])->format('Y-m-d 00:00:00');
            }

            $idWork = $mManageWork->createdWork($dataWork);

            if (isset($data['date_remind']) && strlen(strip_tags($data['description_remind'])) != 0){
                $dataRemind = [
                    'staff_id' => isset($data['processor_id']) ? $data['processor_id'] : Auth::id(),
                    'manage_work_id' => $idWork,
                    'date_remind' => Carbon::createFromFormat('d/m/Y H:i',$data['date_remind'])->format('Y-m-d H:i:00'),
                    'time' => isset($data['time_remind']) ? $data['time_remind'] : null,
                    'time_type' => isset($data['time_remind']) ? $data['time_type_remind'] : null,
                    'description' => strip_tags($data['description_remind']),
                    'is_sent' => 0,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];

                $mManageRemind->insertArrayRemind($dataRemind);
            }

            if($data['manage_work_customer_type'] == 'lead'){
                $item = $mCustomerLead->getInfo($data['obj_id']);

                $typeDetail = $mManageTypeWork->getItem($data['manage_type_work_id']);

                //Insert customer care
                $mCustomerCare->add([
                    "customer_lead_code" => $item['customer_lead_code'],
                    "care_type" => $typeDetail['manage_type_work_key'],
                    "content" => $data['content'],
                    "created_by" => Auth()->id(),
                    "object_id" => $data['history_id']
                ]);
            } else {
                $item = $mCustomer->getItem($data['obj_id']);

                $typeDetail = $mManageTypeWork->getItem($data['manage_type_work_id']);

                //Insert customer care
                $mCustomerRealCare->add([
                    "customer_code" => $item['customer_code'],
                    "care_type" => $typeDetail['manage_type_work_key'],
                    "content" => $data['content'],
                    "created_by" => Auth()->id(),
                    "object_id" => $data['history_id']
                ]);
            }

            // get list care
            //Lấy lịch sử chăm sóc KH
            if($data['manage_work_customer_type'] == 'lead'){
                $getCare = collect($mCustomerCare->getCustomerCare($item['customer_lead_code'])->toArray());
                $dataCare = $getCare->groupBy('created_group');
            } else {
                $getCare = collect($mCustomerRealCare->getCustomerCare($item['customer_code'])->toArray());
                $dataCare = $getCare->groupBy('created_group');
            }
            $list_care_html = \View::make('on-call::on-calling.template.list-care-template', [
                'dataCare' => $dataCare,
            ])->render();

            // get list work
            $data = [
                'customer_id' => $data['obj_id'],
                'manage_work_customer_type' => $data['manage_work_customer_type'],
                'type_search' => 'support'
            ];
            $listWork = $mManageWork->getListWorkByCustomer($data);

            $list_work_html = \View::make('on-call::on-calling.append.append-list-work-child', [
                'listWork' => $listWork,
            ])->render();

            DB::commit();

            if ($dataWork['processor_id'] != Auth::id()){
                $dataNoti = [
                    'key' => 'work_assign',
                    'object_id' => $idWork,
                ];
            }


            if (isset($dataNoti)){
                $sendNoti->sendStaffNotification($dataNoti);
            }

            return [
                'error' => false,
                'list_care_html' => $list_care_html,
                'list_work_html' => $list_work_html,
                'message' => __('Chăm sóc khách hàng thành công')
            ];
        } catch (\Exception $e) {
            Db::rollBack();
            return [
                'error' => true,
                'message' => __('Chăm sóc khách hàng thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ];
        }
    }

    public function getInfoDeal($input)
    {
        $mCustomerDeal = new CustomerDealTable();
        $dataDeal = $mCustomerDeal->getItem($input['deal_id']);
        return response()->json([
            'deal_object_id' => $dataDeal['customer_id_join'],
            'deal_object_type' => $dataDeal['type_customer']
        ]);
    }

    /**
     * validate remind
     *
     * @param $data
     * @return string|null
     */
    private function checkRemind($data){
        $messageError = 'Thời gian trước nhắc nhở cho thời gian nhắc đã qua vui lòng chọn thời gian khác';
        if ($data['time_type_remind'] == 'm'){
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i',$data['date_remind'])->subMinutes($data['time_remind'] == '' ? 0 : $data['time_remind'])){
                return $messageError;
            }
        } else if($data['time_type_remind'] == 'h'){
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i',$data['date_remind'])->subHours($data['time_remind'] == '' ? 0 : $data['time_remind'])){
                return $messageError;
            }
        } else if($data['time_type_remind'] == 'd'){
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i',$data['date_remind'])->subDays($data['time_remind'] == '' ? 0 : $data['time_remind'])){
                return $messageError;
            }
        } else if($data['time_type_remind'] == 'w'){
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i',$data['date_remind'])->subWeeks($data['time_remind'] == '' ? 0 : $data['time_remind'])){
                return $messageError;
            }
        }

        return null;
    }

    /**
     * generate code manage_work
     *
     * @return string
     */
    private function codeWork(){
        $mManageWork = new ManagerWorkTable();
        $codeWork = 'CV_'.Carbon::now()->format('Ymd').'_';
        $workCodeDetail = $mManageWork->getCodeWork($codeWork);

        if ($workCodeDetail == null) {
            return $codeWork.'001';
        } else {
            $arr = explode($codeWork,$workCodeDetail);
            $value = strval(intval($arr[1]) + 1);
            $zero_str = "";
            if (strlen($value) < 7) {
                for ($i = 0; $i < (3 - strlen($value)); $i++) {
                    $zero_str .= "0";
                }
            }
            return $codeWork.$zero_str.$value;
        }

    }
}