<?php


namespace Modules\CustomerLead\Repositories\CustomerDeal;


use App;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Helpers\Helper;
use App\Jobs\SaveLogZns;
use App\Jobs\CheckMailJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\ProvinceTable;
use Modules\CustomerLead\Models\TagTable;
use Modules\Contract\Models\ContractTable;
use Modules\CustomerLead\Models\RoomTable;
use Modules\CustomerLead\Models\OrderTable;
use Modules\Admin\Models\ReceiptOnlineTable;
use Modules\CustomerLead\Models\BranchTable;
use Modules\CustomerLead\Models\ConfigTable;
use Modules\CustomerLead\Models\StaffsTable;
use Modules\CustomerLead\Models\HistoryTable;
use Modules\CustomerLead\Models\JourneyTable;
use Modules\CustomerLead\Models\ReceiptTable;
use Modules\CustomerLead\Models\ServiceTable;
use Modules\CustomerLead\Models\SpaInfoTable;
use Modules\CustomerLead\Models\VoucherTable;
use Modules\ManagerWork\Models\TypeWorkTable;
use Modules\Contract\Models\ContractCareTable;
use Modules\CustomerLead\Models\CustomerTable;
use Modules\CustomerLead\Models\DealCareTable;
use Modules\CustomerLead\Models\OrderLogTable;
use Modules\CustomerLead\Models\PipelineTable;
use Modules\Admin\Http\Api\SendNotificationApi;
use Modules\CustomerLead\Models\ExtensionTable;
use Modules\CustomerLead\Models\WarehouseTable;
use Modules\CustomerLead\Models\DepartmentTable;
use Modules\ManagerWork\Models\ManagerWorkTable;
use Modules\CustomerLead\Models\OrderDetailTable;
use Modules\CustomerLead\Models\OrderSourceTable;
use Modules\CustomerLead\Models\ServiceCardTable;
use Modules\ManagerWork\Models\ManageStatusTable;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\CustomerLead\Models\CustomerDebtTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Models\ProductChildTable;
use Modules\CustomerLead\Models\PromotionLogTable;
use Modules\CustomerLead\Models\WarrantyCardTable;
use Modules\CustomerLead\Models\CustomerEmailTable;
use Modules\CustomerLead\Models\CustomerPhoneTable;
use Modules\CustomerLead\Models\PaymentMethodTable;
use Modules\CustomerLead\Models\ReceiptDetailTable;
use Modules\CustomerLead\Models\CpoCustomerLogTable;
use Modules\CustomerLead\Models\CustomerSourceTable;
use Modules\CustomerLead\Models\MapCustomerTagTable;
use Modules\CustomerLead\Models\OrderConfigTabTable;
use Modules\Admin\Repositories\OrderApp\OrderAppRepo;
use Modules\CustomerLead\Models\CustomerContactTable;
use Modules\CustomerLead\Models\CustomerFanpageTable;
use Modules\CustomerLead\Models\InventoryOutputTable;
use Modules\CustomerLead\Models\OrderCommissionTable;
use Modules\CustomerLead\Models\PromotionDetailTable;
use Modules\CustomerLead\Models\ServiceCardListTable;
use Modules\CustomerLead\Models\ServiceMaterialTable;
use Modules\CustomerLead\Models\WarrantyPackageTable;
use Modules\CustomerLead\Models\CustomerContactsTable;
use Modules\CustomerLead\Models\ProductInventoryTable;
use Modules\CustomerLead\Models\PromotionDateTimeTable;
use Modules\CustomerLead\Models\CustomerDealDetailTable;
use Modules\CustomerLead\Models\ProductBranchPriceTable;
use Modules\CustomerLead\Models\PromotionDailyTimeTable;
use Modules\CustomerLead\Models\ServiceBranchPriceTable;
use Modules\CustomerLead\Models\CustomerBranchMoneyTable;
use Modules\CustomerLead\Models\CustomerDealCommentTable;
use Modules\CustomerLead\Models\CustomerServiceCardTable;
use Modules\CustomerLead\Models\PromotionWeeklyTimeTable;
use Modules\CustomerLead\Models\PromotionMonthlyTimeTable;
use Modules\CustomerLead\Models\PromotionObjectApplyTable;
use Modules\CustomerLead\Models\InventoryOutputDetailTable;
use Modules\CustomerLead\Models\WarrantyPackageDetailTable;
use Modules\CustomerLead\Models\CustomerBranchMoneyLogTable;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderApp\OrderAppRepoInterface;
use Modules\Admin\Repositories\SmsLog\SmsLogRepositoryInterface;
use Modules\CustomerLead\Repositories\CustomerLead\CustomerLeadRepoInterface;

class CustomerDealRepo implements CustomerDealRepoInterface
{
    protected $customerDeal;

    public function __construct(CustomerDealTable $customerDeal)
    {
        $this->customerDeal = $customerDeal;
    }

    const JOURNEY_DEAL_END = 'PJD_DEAL_END';

    public function dataViewIndex()
    {
        $mPipeline = new PipelineTable();
        $mOrderSource = new OrderSourceTable();
        $mBranches = new BranchTable();
        $mStaff = new StaffsTable();

        $optionPipeline = $mPipeline->getOption('DEAL');
        $optionOrderSource = $mOrderSource->getOption();
        $optionBranches = $mBranches->getBranchOption();
        $optionStaffs = $mStaff->getStaffOption();


        return [
            "optionPipeline" => $optionPipeline,
            "optionOrderSource" => $optionOrderSource,
            "optionBranches" => $optionBranches,
            "optionStaffs" => $optionStaffs,
        ];
    }

    /**
     * Danh sách customer deal
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array &$filters = [])
    {
        $list = $this->customerDeal->getList($filters);

        return [
            "list" => $list,
//            "optionBusiness" => $optionBusiness
        ];
    }

    /**
     * View tạo deal
     *
     * @param $input
     * @return array|mixed
     */
    public function dataViewCreate($input)
    {
        $mTag = new TagTable();
        $mPipeline = new PipelineTable();
        $mStaff = new StaffsTable();
        $mOrderSource = new OrderSourceTable();
        $mBranches = new BranchTable();
        $mCustomer = new CustomerTable();
        $mCustomerLead = new CustomerLeadTable();

        $optionTag = $mTag->getOption();
        $optionPipeline = $mPipeline->getOption('DEAL');
        $optionStaff = $mStaff->getStaffOption();
        $optionOrderSource = $mOrderSource->getOption();
        $optionBranches = $mBranches->getBranchOption();

        $infoUser = [];

        if (isset($input['object_type']) && $input['object_type'] == 'customer') {
            //Lấy thông tin khách hàng
            $info = $mCustomer->getItem($input['object_id']);

            if ($info != null) {
                $infoUser = [
                    "full_name" => $info['full_name'],
                    "user_code" => $info['customer_code']
                ];
            }
        } else if (isset($input['object_type']) && $input['object_type'] == 'lead') {
            //Lấy thông tin lead
            $info = $mCustomerLead->getInfo($input['object_id']);

            if ($info != null) {
                $infoUser = [
                    "full_name" => $info['full_name'],
                    "user_code" => $info['customer_lead_code']
                ];
            }
        }

        $html = \View::make('customer-lead::customer-deal.popup-create', [
            "optionTag" => $optionTag,
            "optionPipeline" => $optionPipeline,
            "optionStaff" => $optionStaff,
            "optionOrderSource" => $optionOrderSource,
            "optionBranches" => $optionBranches,
            'load' => $input['load'],
            'infoUser' => $infoUser
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Tìm kiếm khách hàng
     *
     * @param $data
     * @return array|mixed
     */
    public function searchCustomerAction($data)
    {
        if (isset($data['search'])) {
            $mCustomer = new CustomerTable();
            $mCustomerLead = new CustomerLeadTable();
            if ($data['type'] == 'customer') {
                $value = $mCustomer->getCustomerSearch($data['search']);
                $search = [];
                foreach ($value as $item) {
                    $search['results'][] = [
                        'id' => $item['customer_code'],
                        'text' => $item['full_name'],
                        'address' => $item['address'],
                        'name' => $item['full_name'],
                        'phone' => $item['phone1'],
                        'image' => $item['customer_avatar'],
                        'group_name' => $item['group_name'],
                        'postcode' => $item['postcode'],
                        'customer_group_id' => $item['customer_group_id'],
                        'province_id' => $item['province_id'],
                        'district_id' => $item['district_id'],
                        'province_name' => $item['province_name'],
                        'district_name' => $item['district_name'],
                    ];
                }
            } else {
                $value = $mCustomerLead->getCustomerLeadSearch($data['search']);
                $search = [];
                foreach ($value as $item) {
                    $search['results'][] = [
                        'id' => $item['customer_lead_code'],
                        'text' => $item['full_name'],
                        'address' => $item['address'],
                        'name' => $item['full_name'],
                        'phone' => $item['phone'],
                        'image' => $item['avatar'],
                        'group_name' => '',
                        'postcode' => '',
                        'customer_group_id' => '',
                        'province_id' => '',
                        'district_id' => '',
                        'province_name' => '',
                        'district_name' => '',

                    ];
                }
            }
            return $search;
        }
    }

    /**
     * Danh sách liên hệ theo customer code
     *
     * @param $input
     * @return mixed
     */
    public function optionCustomerContact($input)
    {
        $mCustomerContact = new CustomerTable();
        $item = $mCustomerContact->getCustomerByCode($input['customer_code']);

        $data = [
            'contact_phone' => $item != null ? $item['phone1'] : ''
        ];
        $mCustomerLead = new CustomerLeadTable();

        if (isset($input['type_customer']) != '') {
            if ($input['type_customer'] == 'lead') {
                $item = $mCustomerLead->getCustomerLeadByLeadCode($input['customer_code']);

                $data = [
                    'representative' => $item['representative'],
                    'customer_type' => $item['customer_type'],
                    'contact_phone' => $item['phone']
                ];
            }
        }
        return $data;
    }

    /**
     * Load danh sách các object theo object type (product, service, service_card)
     *
     * @param array $filter
     * @return array|mixed
     */
    public function loadObject($filter = [])
    {
        $mProduct = new ProductChildTable();
        $mService = new ServiceTable();
        $mServiceCard = new ServiceCardTable();

        if ($filter['type'] == 'product') {
            $filter['search_keyword'] = isset($filter['search']) ? $filter['search'] : '';

            unset($filter['search'], $filter['type']);

            $data = $mProduct->getListChildOrderPaginate($filter);

            return [
                'items' => $data->items(),
                'pagination' => range($data->currentPage(),
                    $data->lastPage()) ? true : false
            ];
        } else if ($filter['type'] == 'service') {
            unset($filter['type']);

            $data = $mService->getList($filter);

            return [
                'items' => $data->items(),
                'pagination' => range($data->currentPage(),
                    $data->lastPage()) ? true : false
            ];
        } else if ($filter['type'] == 'service_card') {
            $filter['search_keyword'] = isset($filter['search']) ? $filter['search'] : '';

            unset($filter['search'], $filter['type']);

            $data = $mServiceCard->getList($filter);

            return [
                'items' => $data->items(),
                'pagination' => range($data->currentPage(),
                    $data->lastPage()) ? true : false
            ];
        }
    }

    /**
     * Lay gia cua object
     *
     * @param $input
     * @return mixed
     */
    public function getPriceObject($input)
    {
        if ($input['object_type'] == 'product') {
            $mProduct = new ProductChildTable();
            return $mProduct->getProductChildByCode($input['object_code']);
        } else if ($input['object_type'] == 'service') {
            $mService = new ServiceTable();
            return $mService->getItemByCode($input['object_code']);

        } else if ($input['object_type'] == 'service_card') {
            $mServiceCard = new ServiceCardTable();
            return $mServiceCard->getItemByCode($input['object_code']);
        }
    }

    /**
     * luu deal
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($input)
    {
        try {
            if (isset($input['amount']) && $input['amount'] == null) {
                $input['amount'] = 0;
            }
            $deal_type_code = 'online';
            if (isset($input['deal_type_code'])) {
                if ($input['deal_type_code'] != '') {
                    $deal_type_code = $input['deal_type_code'];
                }
            }
            $data = [
                'deal_type_code' => $deal_type_code,
                'type_customer' => $input['type_customer'],
                'phone' => isset($input['phone']) != '' ? $input['phone'] : '',
                'deal_name' => $input['deal_name'],
                'owner' => $input['staff'],
                'customer_code' => $input['customer_code'],
                'pipeline_code' => $input['pipeline_code'],
                'journey_code' => $input['journey_code'],
                'branch_code' => isset($input['branch_code']) ? $input['branch_code'] : '',
                'tag' => isset($input['tag_id']) != '' ? implode(',', $input['tag_id']) : null,
                'order_source_id' => $input['order_source_id'],
                'amount' => (float)str_replace(',', '', $input['amount']),
                'probability' => (float)str_replace(',', '', $input['probability']),
                'closing_date' => Carbon::createFromFormat('d/m/Y', $input['end_date_expected'])->format('Y-m-d H:i'),
                'deal_description' => $input['deal_description'],
                'created_by' => Auth::id()
            ];
            $dealId = $this->customerDeal->add($data);
            // update deal_code
            $dealCode = 'DEALS_' . date('dmY') . sprintf("%02d", $dealId);
            $this->customerDeal->edit($dealId, ['deal_code' => $dealCode]);

            if ($deal_type_code == 'lead') {
                // nếu type = lead => truy ngược về lead để đánh dấu deal
                $mCustomerLead = new CustomerLeadTable();
                $mCustomerLead->updateByCode([
                    'convert_object_type' => 'deal',
                    'convert_object_code' => $dealCode,
                ], $input['customer_code']);
            }
            // insert deal_detail, order detail
            if (isset($input['arrObject'])) if ($input['arrObject'] != null) {
                $mDealDetail = new CustomerDealDetailTable();
                foreach ($input['arrObject'] as $key => $value) {
                    $value['price'] = (float)str_replace(',', '', $value['price']);
                    $value['amount'] = (float)str_replace(',', '', $value['amount']);
                    $value['discount'] = (float)str_replace(',', '', $value['discount']);
                    $value['deal_code'] = $dealCode;
                    $value['created_by'] = Auth::id();
                    $dealDetailId = $mDealDetail->add($value);
                }
            }

            $mCpoCustomerLog = app()->get(CpoCustomerLogTable::class);
            $mJourney = app()->get(JourneyTable::class);
            $dataCustomerLog = [];
            $getListJourney = $mJourney->getJourneyByPipeline($data["pipeline_code"]);
            $newJourney = $mJourney->getInfoJourney($input["journey_code"],$input["pipeline_code"]);
            foreach ($getListJourney as $key => $item){
                if(($newJourney['default_system'] == 'win' && $item['default_system'] == 'fail') || $newJourney['default_system'] == 'fail' && $item['default_system'] == 'win'){
                    continue;
                }
                $dataCustomerLog[] = [
                    'object_type' => $input['type_customer'] == 'customer' ? 'customer' : 'customer_deal',
                    'object_id' => $dealId,
                    'type' => 'deal',
                    'key_table' => 'cpo_deal',
                    'value_old' => $key == 0 ? '' : $getListJourney[$key-1]['journey_code'],
                    'value_new' => $item['journey_code'],
                    'title' => __('Tạo cơ hội bán hàng'),
                    'day' => (int)Carbon::now()->format('d'),
                    'month' => (int)Carbon::now()->format('m'),
                    'year' => (int)Carbon::now()->format('Y'),
                    'created_by' => Auth()->id(),
                    'updated_by' => Auth()->id(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'is_deal_created' => 0
                ];

                if ($item['journey_code'] == $data['journey_code']) {
                    break 1;
                }
            }

            if (count($dataCustomerLog) != 0){
                $mCpoCustomerLog->insertArrData($dataCustomerLog);
            }

            return response()->json([
                "error" => false,
                "message" => __("Tạo thành công"),
                'data' => [
                    'dead_id' => $dealId,
                    'dead_code' => $dealCode
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => __("Tạo thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Cập nhật deak
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        try {
            DB::beginTransaction();
            $mPipeline = app()->get(PipelineTable::class);
            $mJourney = new JourneyTable();
            $mCustomer = new CustomerTable();
            if (isset($input['amount']) && $input['amount'] == null) {
                $input['amount'] = 0;
            }

            $mCpoCustomerLog = app()->get(CpoCustomerLogTable::class);

            $oldLog = $mCpoCustomerLog->getLastLog($input['deal_id'],'deal',$input['type_customer'] == 'customer' ? 'customer' : 'customer_deal');

//            Nếu ghi log các deal cũ chưa có thì mặc định tạo log cho đến hành trình đang chọn
            if($oldLog == null){
                $listJourneyNew = $mJourney->getJourneyByPipeline($input['pipeline_code']);
                $dataJourney = [];
                foreach ($listJourneyNew as $keyJourney => $itemJourney){
                    $dataJourney[] = [
                        'object_type' => $input['type_customer'] == 'customer' ? 'customer' : 'customer_deal',
                        'object_id' => $input['deal_id'],
                        'type' => 'deal',
                        'key_table' => 'cpo_deal',
                        'value_old' => $keyJourney == 0 ? '' : $listJourneyNew[$keyJourney-1]['journey_code'],
                        'value_new' => $itemJourney["journey_code"],
                        'title' => $keyJourney == 0 ? 'Tạo cơ hội bán hàng' : __('Chỉnh sửa cơ hội bán hàng'),
                        'day' => (int)Carbon::now()->format('d'),
                        'month' => (int)Carbon::now()->format('m'),
                        'year' => (int)Carbon::now()->format('Y'),
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id(),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'is_deal_created' => 0
                    ];

                    if ($itemJourney["journey_code"] == $input['journey_code']) {
                        break;
                    }
                }
                if (count($dataJourney) != 0){
                    $mCpoCustomerLog->insertArrData($dataJourney);
                }
            }

            $oldLog = $mCpoCustomerLog->getLastLog($input['deal_id'],'deal',$input['type_customer'] == 'customer' ? 'customer' : 'customer_deal');

            $detailPipeline = $mPipeline->getDetailByCode($input['pipeline_code']);

            $data = [
                'type_customer' => $input['type_customer'],
                'deal_name' => $input['deal_name'],
                'owner' => $input['staff'],
                'phone' => isset($input['phone']) != '' ? $input['phone'] : '',
                'customer_code' => $input['customer_code'],
                'pipeline_code' => $input['pipeline_code'],
                'journey_code' => $input['journey_code'],
                'branch_code' => $input['branch_code'],
                'tag' => isset($input['tag_id']) != '' ? implode(',', $input['tag_id']) : null,
                'order_source_id' => $input['order_source_id'],
                'amount' => (float)str_replace(',', '', $input['amount']),
                'probability' => (float)str_replace(',', '', $input['probability']),
                'closing_date' => Carbon::createFromFormat('d/m/Y', $input['end_date_expected'])->format('Y-m-d H:i'),
                'reason_lose_code' => $input['reason_lose_code'],
                'deal_description' => $input['deal_description'],
                'updated_by' => Auth::id(),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
            if (isset($input['end_date_actual']) && $input['end_date_actual'] != null) {
                $data['closing_due_date'] = Carbon::createFromFormat('d/m/Y', $input['end_date_actual'])->format('Y-m-d H:i');
            }
            $this->customerDeal->edit($input['deal_id'], $data);

            // delete list object in detail-> insert
            $mDealDetail = new CustomerDealDetailTable();
            $mDealDetail->deleteByDealCode($input['deal_code']);

            // insert deal_detail
            if (isset($input['arrObject']) && $input['arrObject'] != null) {
                $mDealDetail = new CustomerDealDetailTable();
                foreach ($input['arrObject'] as $key => $value) {
                    $value['price'] = (float)str_replace(',', '', $value['price']);
                    $value['amount'] = (float)str_replace(',', '', $value['amount']);
                    $value['discount'] = (float)str_replace(',', '', $value['discount']);
                    $value['deal_code'] = $input['deal_code'];
                    $value['created_by'] = Auth::id();
                    $dealDetailId = $mDealDetail->add($value);
                }
            }


//           Check customer có tồn tại chưa
            $checkCustomer = $mCustomer->getCustomerByPhone($input["phone"]);
            $dataJourney = $mJourney->getInfoJourney($input['journey_code'], $input['pipeline_code']);


            $checkCreateCustomer = 0;
            if ($input['type_customer'] == 'lead') {
                if (isset($dataJourney['default_system']) != '') {
                    if ($dataJourney['default_system'] == 'win' && $checkCustomer == null) {
                        $checkCreateCustomer = 1;
                    }
                }
            }
            $checkInsertOrder = 0;
            $checkCreateContract = 0;
            $checkCreateContractAnnex = 0;

            // kiểm tra không thể tạo hợp đồng ở hành trình có tạo hợp đồng (lí do: sản phẩm có kpi và không có kpi)
            $checkCannotCreateContract = 0;
            $contractId = 0;
            // check có tích hợp module hợp đồng?
            $mConfig = new \Modules\Admin\Models\ConfigTable();
            $mOrder = new OrderTable();
            $mOrderDetail = new OrderDetailTable();
            $config = $mConfig->getInfoByKey('contract');
            // check có hợp đồng từ deal rồi thì ko tạo hợp đồng nữa
            $dataDeal = $this->customerDeal->getItem($input['deal_id']);
            // get thông tin hợp đồng thì deal (nếu có)
            if ($config != null && $config['value'] == 1) {
                $mContract = new ContractTable();
                $mContractCare = new ContractCareTable();
                if (isset($dataJourney['is_contract_created'])) {
                    if ($dataJourney['is_contract_created'] == '1') {
                        $checkCreateContract = 1;
                    }
                }
                // kiểm tra đã tạo hợp đồng thì không hỏi tạo nữa
                if ($dataDeal['contract_code'] != null && $dataDeal['contract_code'] != '') {
                    $checkCreateContract = 0;
                }
                if (isset($dataJourney['default_system'])) {
                    if ($dataJourney['default_system'] == 'win') {
                        if ($dataDeal['deal_type_code'] == 'contract_expire') {
                            // đổi sang update khi thật sự tạo
//                            $mContractCare->updateDataByContract([
//                                'status' => 'success'
//                            ],$dataDeal['deal_type_object_id']);
                            $checkCreateContract = 1;
                        }
                        if ($dataDeal['deal_type_code'] == 'contract_soon_expire') {
                            $checkCreateContractAnnex = 1;
                            $contractId = $dataDeal['deal_type_object_id'];
                            // đổi sang update khi thật sự tạo
//                            $mContractCare->updateDataByContract([
//                                'status' => 'success'
//                            ],$dataDeal['deal_type_object_id']);
                        }
                    } else if ($dataJourney['default_system'] == 'fail') {
                        if ($dataDeal['deal_type_code'] == 'contract_expire' || $dataDeal['deal_type_code'] == 'contract_soon_expire') {
                            $mContractCare->updateDataByContract([
                                'status' => 'fail'
                            ], $dataDeal['deal_type_object_id']);
                        }
                    }
                }
            }
            // update T11
            // vừa tạo hợp đồng, vừa tạo KH => auto tạo KH, đơn hàng => popup hợp đồng
            if ($checkCreateContract == 1 && $checkCreateCustomer == 1) {
                // get info customer deal
                $infoDeal = $this->customerDeal->getInfoCustomerDeal($input['deal_id']);
                $checkCustomerFromDeal = $mCustomer->getCustomerByPhone($infoDeal["phone"]);
                // nếu tồn tại KH rồi thì ko tạo nữa
                if ($checkCustomerFromDeal == null) {
                    // auto create customer
                    $id_add = $mCustomer->add([
                        "full_name" => $infoDeal["full_name"],
                        "customer_type" => $infoDeal["customer_type"],
                        "hotline" => $infoDeal["hotline"],
                        "tax_code" => $infoDeal["tax_code"],
                        "representative" => $infoDeal["representative"],
                        "phone1" => $infoDeal["phone"],
                        "gender" => $infoDeal["gender"],
                        "address" => $infoDeal["address"],
                        "province_id" => $infoDeal["province_id"],
                        "district_id" => $infoDeal["district_id"],
                        "email" => $infoDeal["email"],
                        "branch_id" => Auth()->user()->branch_id,
                        "member_level_id" => 1,
                        "created_by" => Auth()->id(),
                        "updated_by" => Auth()->id()
                    ]);
                    $day_code = date('dmY');
                    if ($id_add < 10) {
                        $id_add = '0' . $id_add;
                    }
                    $data_code = [
                        'customer_code' => 'KH_' . $day_code . $id_add
                    ];
                    $mCustomer->edit($data_code, $id_add);

                    // có sản phẩm, dịch vụ...
                    if (isset($input['arrObject']) && $input['arrObject'] != null) {
                        // nếu chưa tồn tại đơn hàng thì tạo đơn và chi tiết đơn, nếu có rồi thì update chi tiết đơn
                        $dataOrder = $mOrder->getItemByDealCode($input['deal_code']);
                        if ($dataOrder == null) {
                            $customerInfo = $mCustomer->getItem($id_add);
                            $dataOrder = [
                                'customer_id' => $id_add,
                                'branch_id' => $customerInfo['branch_id'],
                                'deal_code' => $input['deal_code'],
                                'created_by' => Auth::id(),
                            ];
                            $orderId = $mOrder->add($dataOrder);
                            $amount = 0;
                            // insert order detail
                            foreach ($input['arrObject'] as $item) {
                                $dataOrderDetail = [
                                    'order_id' => $orderId,
                                    'object_id' => $item['object_id'],
                                    'object_code' => $item['object_code'],
                                    'object_name' => $item['object_name'],
                                    'object_type' => $item['object_type'],
                                    'price' => str_replace(',', '', $item['price']),
                                    'discount' => $item['discount'],
                                    'amount' => str_replace(',', '', $item['amount']),
                                    'quantity' => $item['quantity'],
                                    'created_by' => Auth::id(),
                                ];
                                $orderDetailId = $mOrderDetail->add($dataOrderDetail);
                                $amount += str_replace(',', '', $item['amount']);
                                // check SP, DV, thẻ DV có được tính hoa hồng cho deal không, có thì insert order commission
//                                    $this->insertOrderCommission($item['object_type'], $item['object_id'], $input['deal_id'], $orderDetailId);
                            }
                            // update order code
                            $orderCode = 'DH_' . date('dmY') . sprintf("%02d", $orderId);
                            $mOrder->edit([
                                'order_code' => $orderCode,
                                'total' => $amount,
                                'amount' => $amount,
                            ], $orderId);
                        }
                        // auto create order
                    }
                }

                $checkCreateCustomer = 0;

            }
            if ($checkCreateContract == 1) {
                // kiểm tra số sp kpi và không kpi
                if (isset($input['arrObject']) && $input['arrObject'] != null) {
                    $mProductChild = new ProductChildTable();
                    $countKpi = $countNoKpi = 0;
                    foreach ($input['arrObject'] as $key => $value) {
                        $objectType = $value['object_type'];
                        $objectId = $value['object_id'];
                        if ($objectType == 'product') {
                            $dataProductChild = $mProductChild->getItem($objectId);
                            if ($dataProductChild['is_applied_kpi'] == 1) {
                                $countKpi++;
                            } else {
                                $countNoKpi++;
                            }
                        } else {
                            $countKpi++;
                        }
                    }
                    if ($countNoKpi > 0 && $countKpi > 0) {
                        $checkCannotCreateContract = 1;
                    } else if ($countNoKpi > 1 && $countKpi == 0) {
                        $checkCannotCreateContract = 1;
                    }
                }
            }
            // không tạo hợp đồng, chỉ tạo KH => auto tạo KH => chuyển màn hình thanh toán sau deal
            if ($checkCreateContract == 0 && $checkCreateCustomer == 0) {
                if (isset($dataJourney['default_system'])) {
                    if ($dataJourney['default_system'] == 'win') {
                        $dataOrder = $mOrder->getItemByDealCode($input['deal_code']);
                        if ($dataOrder == null) {
                            $checkInsertOrder = 1;
                        }
                    }
                }
            }

            $oldJourney = $mJourney->getInfoJourney($oldLog["value_new"],$input["pipeline_code"]);
            $newJourney = $mJourney->getInfoJourney($input["journey_code"],$input["pipeline_code"]);

            if ($oldJourney['position'] > $newJourney['position']){
                $listJourney = $mJourney->getOptionEditNew($input["pipeline_code"],$newJourney['position']);

                if (count($listJourney) != 0 ){
                    $listJourney = collect($listJourney)->pluck('journey_code')->toArray();
//                    $mCpoCustomerLog->removeLogByJourney('customer_lead',$input["customer_lead_id"],$listJourney);
                    $mCpoCustomerLog->removeLogByJourney($input['type_customer'] == 'customer' ? 'customer' : 'customer_deal',$input['deal_id'],$listJourney);
                }
            }

            $listJourney = $mJourney->getOptionEditNewFix($input["pipeline_code"],$oldJourney['position'],$newJourney['position']);
            $dataCustomerLog = [];

            foreach ($listJourney as $key => $item){
                if(($newJourney['default_system'] == 'win' && $item['default_system'] == 'fail') || $newJourney['default_system'] == 'fail' && $item['default_system'] == 'win'){
                    continue;
                }
                $dataCustomerLog[] = [
                    'object_type' => $input['type_customer'] == 'customer' ? 'customer' : 'customer_deal',
                    'object_id' => $input['deal_id'],
                    'type' => 'deal',
                    'key_table' => 'cpo_deal',
                    'value_old' => $key == 0 ? $oldLog["value_new"] : $item['journey_code'],
                    'value_new' => $item["journey_code"],
                    'title' => __('Chỉnh sửa cơ hội bán hàng'),
                    'day' => (int)Carbon::now()->format('d'),
                    'month' => (int)Carbon::now()->format('m'),
                    'year' => (int)Carbon::now()->format('Y'),
                    'created_by' => Auth()->id(),
                    'updated_by' => Auth()->id(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'is_deal_created' => 0
                ];

                if ($item['journey_code'] == $input["journey_code"]){
                    break 1;
                }
            }

            if (count($dataCustomerLog) != 0){
                $mCpoCustomerLog->insertArrData($dataCustomerLog);
            }

            DB::commit();
            return response()->json([
                "check_create_customer" => $checkCreateCustomer,
                "check_create_contract" => $checkCreateContract,
                "check_cannot_create_contract" => $checkCannotCreateContract,
                "check_create_contract_annex" => $checkCreateContractAnnex,
                "checkInsertOrder" => $checkInsertOrder,
                "contract_id" => $contractId,
                "deal_id" => $input['deal_id'],
                "deal_code" => $input['deal_code'],
                "error" => false,
                "message" => __("Chỉnh sửa thành công")
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "error" => true,
                "message" => __("Chỉnh sửa thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Xoá deal
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function destroy($id)
    {
        try {
            $this->customerDeal->edit($id, ['is_deleted' => 1]);

            return response()->json([
                "error" => false,
                "message" => __("Xoá thành công")
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => __("Xoá thất bại")
            ]);
        }
    }

    /**
     * View chỉnh sửa deal
     *
     * @param $input
     * @return array|mixed
     */
    public function dataViewEdit($input)
    {
        $mTag = new TagTable();
        $mPipeline = new PipelineTable();
        $mJourney = new JourneyTable();
        $mStaff = new StaffsTable();
        $mOrderSource = new OrderSourceTable();
        $mDealDetail = new CustomerDealDetailTable();
        $mBranches = new BranchTable();
        $mOrder = new OrderTable();

        $item = $this->customerDeal->getItem($input['deal_id']);
        $item['tag'] = $item['tag'] != '' ? explode(',', $item['tag']) : [];
        $optionTag = $mTag->getOption();
        $optionPipeline = $mPipeline->getOption('DEAL');
        $optionJourney = $mJourney->getOptionEdit($item["pipeline_code"], $item["journey_position"]);

        $getDetailJourney = $mJourney->getByCodeJourneyPipeline($item["pipeline_code"], $item["journey_code"]);
        $arrJourney = explode(',',$getDetailJourney["journey_updated"]);
        $arrJourney[] = $item["journey_id"];
        $optionJourney = $mJourney->getListJourneyByArrId($arrJourney);
        $optionStaff = $mStaff->getStaffOption();
        $optionOrderSource = $mOrderSource->getOption();
        $optionBranches = $mBranches->getBranchOption();
        $listObject = $mDealDetail->getList($item['deal_code']);
        // check đã insert đơn hàng hay chưa, có rồi thì k show button thanh toán
        $checkOrder = true;
        $orderInfo = $mOrder->getItemByDealCode($item['deal_code']);
        if ($orderInfo == null) {
            $checkOrder = false;
        }
        // check KH của deal đã là KH của hệ thống chưa? nếu chưa thì không cho chuyển sang thanh toán deal
        // nếu KH này đã được tạo thì cho phép
        $mCustomer = new CustomerTable();
        $checkCreateCustomer = true;
        if ($item['type_customer'] == 'lead') {
            if ($item['phone'] == '') {
                $checkCreateCustomer = false;
            } else {
                $cus = $mCustomer->getCustomerByPhone($item['phone']);
                if ($cus == null) {
                    $checkCreateCustomer = false;
                }
            }
        }
        $html = \View::make('customer-lead::customer-deal.popup-edit', [
            "optionTag" => $optionTag,
            "optionPipeline" => $optionPipeline,
            "optionJourney" => $optionJourney,
            "optionStaff" => $optionStaff,
            "optionOrderSource" => $optionOrderSource,
            "listObject" => $listObject,
            "optionBranches" => $optionBranches,
            "item" => $item,
            'load' => $input['load'],
            "checkOrder" => $checkOrder,
            "checkCreateCustomer" => $checkCreateCustomer,
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * View chi tiết deal
     *
     * @param $input
     * @return array|mixed
     */
    public function dataViewDetail($input)
    {
        $mJourney = new JourneyTable();
        $mDealDetail = new CustomerDealDetailTable();
        $mCustomer = new CustomerTable();
        $mCustomerLead = new CustomerLeadTable();
        $mMangeWork = new ManagerWorkTable();
        $mManageTypeWork = new TypeWorkTable();
        $mManageStatus = new ManageStatusTable();
        $mStaff = new \Modules\ManagerWork\Models\StaffsTable();
        $mTag = new TagTable();
        $item = $this->customerDeal->getItem($input['deal_id']);
        
        $stringTag = '';
        if(isset($item['tag']) && $item['tag'] != ""){
            $arrTag = isset($item['tag']) ? explode(',', $item['tag']) : [];
            if(count($arrTag) > 0){
                foreach ($arrTag as $key => $value) {
                    $dataTag = $mTag->getInfo($value);
                    if ($key == count($arrTag) - 1) {
                        $stringTag .= $dataTag['name'];
                    } else {
                        $stringTag .= $dataTag['name'] . ', ';
                    }
                }
            }
           
        }
       
       
        $item['tag_name'] = $stringTag;
       
        $listJourney = $mJourney->getJourneyByPipeline($item['pipeline_code']);
       
        $listObject = $mDealDetail->getList($item['deal_code']);
   
        $infor = null;
        if ($item['type_customer'] == 'lead') {
            $infor = $mCustomerLead->getCustomerLeadByLeadCode($item['customer_code']);
        } else {
            $infor = $mCustomer->getCustomerByCustomerCode($item['customer_code']);
        }


        $data = [
            'customer_id' => $input['deal_id'],
            'manage_work_customer_type' => 'deal',
            'type_search' => 'support'
        ];

        $listWork = $mMangeWork->getListWorkByCustomer($data);

        $data1 = [
            'customer_id' => $input['deal_id'],
            'manage_work_customer_type' => 'deal',
            'type_search' => 'history'
        ];

        $historyWork = $mMangeWork->getListWorkByCustomer($data1);

        $listTypeWork = $mManageTypeWork->getListTypeWork(1);

        $listStatusWork = $mManageStatus->getAll();

        $liststaff = $mStaff->getAll();

        $html = \View::make('customer-lead::customer-deal.popup-detail', [
            "item" => $item,
            "listJourney" => $listJourney,
            "listObject" => $listObject,
            "infor" => $infor,
            'listWork' => $listWork,
            'historyWork' => $historyWork,
            'listTypeWork' => $listTypeWork,
            'listStatusWork' => $listStatusWork,
            'liststaff' => $liststaff,
            'data' => $input
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Các option trong view kanban
     *
     * @return mixed|void
     */
    public function optionViewKanban()
    {
        $mPipeline = new PipelineTable();
        $mManageTypeWork = new TypeWorkTable();

        $optionPipeline = $mPipeline->getOption('DEAL');
        $mOrderSource = new OrderSourceTable();
        $mBranches = new BranchTable();

        $optionOrderSource = $mOrderSource->getOption();
        $optionBranches = $mBranches->getBranchOption();
        $listWorkType = $mManageTypeWork->getListDefault('ASC');
        return [
            'optionPipeline' => $optionPipeline,
            'optionOrderSource' => $optionOrderSource,
            'optionBranches' => $optionBranches,
            'listWorkType' => $listWorkType
        ];
    }

    /**
     * Load kanban view
     *
     * @param $input
     * @return array|mixed
     */
    public function loadKanbanView(array &$input = [])
    {
        $mPipeline = new PipelineTable();
        $mJourney = new JourneyTable();
        $mManageTypeWork = new TypeWorkTable();
        $mManageWork = new ManagerWorkTable();
        $mTag = new TagTable();

        if ($input['pipeline_id'] == null) {
            return [
                'error' => true
            ];
        }

        if(isset($input['date_from'])){
            $startDate = Carbon::parse(str_replace('/', '-', $input['date_from']))->format('d/m/Y');
        }
        else{
            $startDate = Carbon::now()->startOfMonth()->format('d/m/Y');
        }

        if(isset($input['date_to'])){
            $endDate = Carbon::parse(str_replace('/', '-', $input['date_to']))->format('d/m/Y');
        }
        else{
            $endDate = Carbon::now()->endOfMonth()->format('d/m/Y');
        }

        if($startDate && $endDate){
            $input['closing_date'] = sprintf('%s - %s', $startDate, $endDate);
        }

        $getPipeline = $mPipeline->getDetail($input['pipeline_id']);
        $getJourney = $mJourney->getJourneyByPipeline($getPipeline['pipeline_code']);
        $input['pipeline_code'] = $getPipeline['pipeline_code'];
        $getCustomerDeal = $this->customerDeal->getCustomerByFilterKanban($input);
        $dataDeal = collect($getCustomerDeal->toArray())
            ->groupBy("journey_code");
        $newJourneyWithTotal = [];
        foreach ($getJourney as $item) {
            $total = 0;
            foreach ($dataDeal as $item2) {
                foreach ($item2 as $item3) {
                    if ($item3['journey_code'] == $item['journey_code']) {
                        $total += $item3['amount'];
                    }
                }
            }
            $item['total'] = $total;
            $newJourneyWithTotal[] = $item;
        }

        foreach($getCustomerDeal as $deal){

            $listTags = [];
            if($deal->tag){
                $listTagIds = explode(',', $deal->tag);
                $listTags = $mTag->getTagByIds($listTagIds)->pluck('name')->toArray();
            }

            $deal->list_tag = $listTags;

            $deal->diff_day = 0;
            if ($deal->closing_date) {
                $closingDate = Carbon::parse(str_replace('/', '-', $deal->closing_date))->format('Y-m-d H:i:s');
                $deal->diff_day = Helper::getAgoTime($closingDate);
            }

            $deal->diff_day_last_care = 0;
            if ($deal->date_last_care) {
                $lastCare = Carbon::parse(str_replace('/', '-', $deal->date_last_care))->format('Y-m-d H:i:s');
                $deal->diff_day_last_care = Helper::getAgoTime($lastCare);
                $deal->date_last_care = Carbon::parse($lastCare)->format('d/m/Y');
            }

            $numberOfWork = $mManageWork->getWorkLead($deal->customer_lead_id, 'deal');
            $deal->related_work = $numberOfWork ? $numberOfWork->count() : 0;

            $numberOfWorkLeadOverdue = $mManageWork->getWorkLeadOverdue($deal->customer_lead_id, 'deal');
            $deal->appointment = $numberOfWorkLeadOverdue;
        }

        $listTypeWork = [];
        $listTotalWork = [];

        foreach (collect($getJourney)->pluck('journey_code') as $key => $item) {
            $listTotalWork[$item] = $mManageTypeWork->getListDefault();
        }

        if (count($getCustomerDeal) != 0) {
            $groupCustomer = collect($getCustomerDeal)->groupBy('journey_code');
            foreach ($groupCustomer as $key => $item) {
                if (count($item) != 0) {
                    $listCustomer = collect($item)->pluck('deal_id')->toArray();

                    $listTotalWorkType = $mManageTypeWork->getTotalTypeWorkByLead(implode(',', $listCustomer), 'deal');

                    if (count($listTotalWorkType) == 0) {
                        $listTotalWorkType = $mManageTypeWork->getListDefault();
                    }

                    $listTotalWork[$key] = $listTotalWorkType;
                }
            }
        }

        //Lấy quyền gọi
        $isCall = 0;

        if (in_array('customer-lead.modal-call', session('routeList'))) {
            $isCall = 1;
        }

        if (isset($input['dataField'])) {
            foreach ($getCustomerDeal as $key => $item) {
                if ($input['dataField'] == $item['journey_code'] && $input['search_manage_type_work_id'] != $item['manage_type_work_id']) {
                    unset($getCustomerDeal[$key]);
                }
            }
            $getCustomerDeal = collect(array_values(collect($getCustomerDeal)->toArray()));
        }

        $getCustomerDeal = $getCustomerDeal->groupBy('journey_code')->toArray();

        $listCustomerDeal = [];
        foreach ($newJourneyWithTotal as $key => $value) {
            $listCustomerDeal[$key] = $value;
            $listCustomerDeal[$key]['items'] = $getCustomerDeal[$value['journey_code']] ?? [];
            $listCustomerDeal[$key]['count'] = isset($getCustomerDeal[$value['journey_code']]) ? count($getCustomerDeal[$value['journey_code']]) : 0;
        }

        return [
            'error' => false,
            'pipeline' => $getPipeline,
            'journey' => $newJourneyWithTotal,
            'customerDeal' => $listCustomerDeal,
            'isCall' => $isCall,
            'listTotalWork' => $listTotalWork
        ];
    }

    /**
     * Cập nhật hành trình khách hàng
     *
     * @param $input
     * @return mixed|void
     */
    public function updateJourney($input)
    {
        try {
            DB::beginTransaction();
            $mJourney = new JourneyTable();
            $mCustomer = new CustomerTable();

            //Get deal
            $getInfo = $this->customerDeal->getItem($input['deal_id']);
            $input['pipeline_code'] = $getInfo['pipeline_code'];
            $input["journey_code"] = $input['journey_new'];
            $input["type_customer"] = $getInfo['journey_new'];

            //Get journey old
            $getOld = $mJourney->getInfoJourney($input['journey_old'], $getInfo['pipeline_code']);
            //Get journey new
            $getNew = $mJourney->getInfoJourney($input['journey_new'], $getInfo['pipeline_code']);
            // check journey_new = default system 'new'
            $check = 0;
            if (isset($getNew['default_system']) != '') {
                if ($getNew['default_system'] == 'new') {
                    $check = 1; // tạo khách hàng từ deal này
                }
            }
            //Check journey old dc update qua journey new ko
            if (!in_array($getNew['journey_id'], explode(',', $getOld['journey_updated']))) {
                return [
                    'error' => true,
                    'message' => __('Chỉnh sửa thất bại'),
                    '_message' => 'Journey new ko có trong journey_updated của journey cũ'
                ];
            }
            //Check vị trí journey new nhỏ hơn journey old thì ko cho update
//            if ($getOld['position'] > $getNew['position']) {
//                return [
//                    'error' => true,
//                    'message' => __('Chỉnh sửa thất bại'),
//                    '_message' => 'Không thể cập nhật hành trình nhỏ hơn'
//                ];
//            }

            $mCpoCustomerLog = app()->get(CpoCustomerLogTable::class);

            $oldLog = $mCpoCustomerLog->getLastLog($input['deal_id'],'deal');

            if($oldLog == null){
                $listJourneyNew = $mJourney->getJourneyByPipeline($input['pipeline_code']);
                $dataJourney = [];
                foreach ($listJourneyNew as $keyJourney => $itemJourney){
                    $dataJourney[] = [
                        'object_type' => $input['type_customer'] == 'customer' ? 'customer' : 'customer_deal',
                        'object_id' => $input['deal_id'],
                        'type' => 'deal',
                        'key_table' => 'cpo_deal',
                        'value_old' => $keyJourney == 0 ? '' : $listJourneyNew[$keyJourney-1]['journey_code'],
                        'value_new' => $itemJourney["journey_code"],
                        'title' => $keyJourney == 0 ? 'Tạo cơ hội bán hàng' : __('Chỉnh sửa cơ hội bán hàng'),
                        'day' => (int)Carbon::now()->format('d'),
                        'month' => (int)Carbon::now()->format('m'),
                        'year' => (int)Carbon::now()->format('Y'),
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id(),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'is_deal_created' => 0
                    ];

                    if ($itemJourney["journey_code"] == $input['journey_code']) {
                        break;
                    }
                }
                if (count($dataJourney) != 0){
                    $mCpoCustomerLog->insertArrData($dataJourney);
                }
            }

            $oldLog = $mCpoCustomerLog->getLastLog($input['deal_id'],'deal');


            //Update journey trong deals
            $this->customerDeal->edit($input['deal_id'], [
                'journey_code' => $getNew['journey_code']
            ]);

            $oldJourney = $mJourney->getInfoJourney($oldLog["value_new"],$input["pipeline_code"]);
            $newJourney = $mJourney->getInfoJourney($input["journey_code"],$input["pipeline_code"]);

            if ($oldJourney['position'] > $newJourney['position']){
                $listJourney = $mJourney->getOptionEditNew($input["pipeline_code"],$newJourney['position']);

                if (count($listJourney) != 0 ){
                    $listJourney = collect($listJourney)->pluck('journey_code')->toArray();
//                    $mCpoCustomerLog->removeLogByJourney('customer_lead',$input["customer_lead_id"],$listJourney);
                    $mCpoCustomerLog->removeLogByJourney($input['type_customer'] == 'customer' ? 'customer' : 'customer_deal',$input['deal_id'],$listJourney);
                }
            }

            $listJourney = $mJourney->getOptionEditNewFix($input["pipeline_code"],$oldJourney['position'],$newJourney['position']);
            $dataCustomerLog = [];

            foreach ($listJourney as $key => $item){
                if(($newJourney['default_system'] == 'win' && $item['default_system'] == 'fail') || $newJourney['default_system'] == 'fail' && $item['default_system'] == 'win'){
                    continue;
                }
                $dataCustomerLog[] = [
                    'object_type' => $input['type_customer'] == 'customer' ? 'customer' : 'customer_deal',
                    'object_id' => $input['deal_id'],
                    'type' => 'deal',
                    'key_table' => 'cpo_deal',
                    'value_old' => $key == 0 ? $oldLog["value_new"] : $item['journey_code'],
                    'value_new' => $item["journey_code"],
                    'title' => __('Chỉnh sửa cơ hội bán hàng'),
                    'day' => (int)Carbon::now()->format('d'),
                    'month' => (int)Carbon::now()->format('m'),
                    'year' => (int)Carbon::now()->format('Y'),
                    'created_by' => Auth()->id(),
                    'updated_by' => Auth()->id(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'is_deal_created' => 0
                ];

                if ($item['journey_code'] == $input["journey_code"]){
                    break 1;
                }
            }

            if (count($dataCustomerLog) != 0) {
                $mCpoCustomerLog->insertArrData($dataCustomerLog);
            }

            $mJourney = new JourneyTable();
            $dataJourney = $mJourney->getInfo($getNew['journey_code']);
            $checkCreateCustomer = 0;
            $checkInsertOrder = 0;
            if ($getInfo['type_customer'] == 'lead') {
                if (isset($dataJourney['default_system']) != '') {
                    if ($dataJourney['default_system'] == 'win') {
                        $checkCreateCustomer = 1;
                    }
                }
            }
            $checkCreateContract = 0;
            $checkCreateContractAnnex = 0;
            // kiểm tra không thể tạo hợp đồng ở hành trình có tạo hợp đồng (lí do: sản phẩm có kpi và không có kpi)
            $checkCannotCreateContract = 0;
            $contractId = 0;
            // check có tích hợp module hợp đồng?
            $mConfig = new \Modules\Admin\Models\ConfigTable();
            $mOrder = new OrderTable();
            $mOrderDetail = new OrderDetailTable();
            $mDealDetail = new CustomerDealDetailTable();
            $config = $mConfig->getInfoByKey('contract');
            // check có hợp đồng từ deal rồi thì ko tạo hợp đồng nữa
            $dataDeal = $this->customerDeal->getItem($input['deal_id']);
            // get thông tin hợp đồng thì deal (nếu có)
            if ($config != null && $config['value'] == 1) {
                $mContract = new ContractTable();
                $mContractCare = new ContractCareTable();
                if (isset($dataJourney['is_contract_created'])) {
                    if ($dataJourney['is_contract_created'] == '1') {
                        $checkCreateContract = 1;
                    }
                }
                if ($dataDeal['contract_code'] != null && $dataDeal['contract_code'] != '') {
                    $checkCreateContract = 0;
                }
                if (isset($dataJourney['default_system'])) {
                    if ($dataJourney['default_system'] == 'win') {
                        if ($dataDeal['deal_type_code'] == 'contract_expire') {
//                            $mContractCare->updateDataByContract([
//                                'status' => 'success'
//                            ],$dataDeal['deal_type_object_id']);
                            $checkCreateContract = 1;
                        }
                        if ($dataDeal['deal_type_code'] == 'contract_soon_expire') {
                            $checkCreateContractAnnex = 1;
                            $contractId = $dataDeal['deal_type_object_id'];
//                            $mContractCare->updateDataByContract([
//                                'status' => 'success'
//                            ],$dataDeal['deal_type_object_id']);
                        }
                    } else if ($dataJourney['default_system'] == 'fail') {
                        if ($dataDeal['deal_type_code'] == 'contract_expire' || $dataDeal['deal_type_code'] == 'contract_soon_expire') {
                            $mContractCare->updateDataByContract([
                                'status' => 'fail'
                            ], $dataDeal['deal_type_object_id']);
                        }
                    }
                }
            }
            // update T11
            // vừa tạo hợp đồng, vừa tạo KH => auto tạo KH, đơn hàng => popup hợp đồng
            if ($checkCreateContract == 1 && $checkCreateCustomer == 1) {
                // get info customer deal
                $infoDeal = $this->customerDeal->getInfoCustomerDeal($input['deal_id']);
                $checkCustomerFromDeal = $mCustomer->getCustomerByPhone($infoDeal["phone"]);
                if ($checkCustomerFromDeal == null) {
                    // auto create customer
                    $id_add = $mCustomer->add([
                        "full_name" => $infoDeal["full_name"],
                        "customer_type" => $infoDeal["customer_type"],
                        "hotline" => $infoDeal["hotline"],
                        "tax_code" => $infoDeal["tax_code"],
                        "representative" => $infoDeal["representative"],
                        "phone1" => $infoDeal["phone"],
                        "gender" => $infoDeal["gender"],
                        "address" => $infoDeal["address"],
                        "province_id" => $infoDeal["province_id"],
                        "district_id" => $infoDeal["district_id"],
                        "email" => $infoDeal["email"],
                        "branch_id" => Auth()->user()->branch_id,
                        "member_level_id" => 1,
                        "created_by" => Auth()->id(),
                        "updated_by" => Auth()->id()
                    ]);
                    // auto create order
                    $day_code = date('dmY');
                    if ($id_add < 10) {
                        $id_add = '0' . $id_add;
                    }
                    $data_code = [
                        'customer_code' => 'KH_' . $day_code . $id_add
                    ];
                    $mCustomer->edit($data_code, $id_add);

                    $dataDealDetail = $mDealDetail->getDetailByDealCode($dataDeal['deal_code']);
                    // có sản phẩm, dịch vụ...
                    if ($dataDealDetail != null) {
                        // nếu chưa tồn tại đơn hàng thì tạo đơn và chi tiết đơn, nếu có rồi thì update chi tiết đơn
                        $dataOrder = $mOrder->getItemByDealCode($dataDeal['deal_code']);
                        if ($dataOrder == null) {
                            $customerInfo = $mCustomer->getItem($id_add);
                            $dataOrder = [
                                'customer_id' => $id_add,
                                'branch_id' => $customerInfo['branch_id'],
                                'deal_code' => $dataDeal['deal_code'],
                                'created_by' => Auth::id(),
                            ];
                            $orderId = $mOrder->add($dataOrder);
                            $amount = 0;
                            // insert order detail
                            foreach ($dataDealDetail as $item) {
                                $dataOrderDetail = [
                                    'order_id' => $orderId,
                                    'object_id' => $item['object_id'],
                                    'object_code' => $item['object_code'],
                                    'object_name' => $item['object_name'],
                                    'object_type' => $item['object_type'],
                                    'price' => $item['price'],
                                    'discount' => $item['discount'],
                                    'amount' => $item['amount'],
                                    'quantity' => $item['quantity'],
                                    'created_by' => Auth::id(),
                                ];
                                $orderDetailId = $mOrderDetail->add($dataOrderDetail);
                                $amount += $item['amount'];
                                // check SP, DV, thẻ DV có được tính hoa hồng cho deal không, có thì insert order commission
//                                    $this->insertOrderCommission($item['object_type'], $item['object_id'], $input['deal_id'], $orderDetailId);
                            }
                            // update order code
                            $orderCode = 'DH_' . date('dmY') . sprintf("%02d", $orderId);
                            $mOrder->edit([
                                'order_code' => $orderCode,
                                'total' => $amount,
                                'amount' => $amount,
                            ], $orderId);
                        }
                        // auto create order
                    }
                }

                $checkCreateCustomer = 0;
            }
            if ($checkCreateContract == 1) {
                // kiểm tra số sp kpi và không kpi
                if (isset($input['arrObject']) && $input['arrObject'] != null) {
                    $mProductChild = new ProductChildTable();
                    $countKpi = $countNoKpi = 0;
                    foreach ($input['arrObject'] as $key => $value) {
                        $objectType = $value['object_type'];
                        $objectId = $value['object_id'];
                        if ($objectType == 'product') {
                            $dataProductChild = $mProductChild->getItem($objectId);
                            if ($dataProductChild['is_applied_kpi'] == 1) {
                                $countKpi++;
                            } else {
                                $countNoKpi++;
                            }
                        } else {
                            $countKpi++;
                        }
                    }
                    if ($countNoKpi > 0 && $countKpi > 0) {
                        $checkCannotCreateContract = 1;
                    } else if ($countNoKpi > 1 && $countKpi == 0) {
                        $checkCannotCreateContract = 1;
                    }
                }
            }
            // không tạo hợp đồng, chỉ tạo KH => auto tạo KH => chuyển màn hình thanh toán sau deal
            if ($checkCreateContract == 0 && $checkCreateCustomer == 0) {
                if (isset($dataJourney['default_system'])) {
                    if ($dataJourney['default_system'] == 'win') {
                        $dataOrder = $mOrder->getItemByDealCode($dataDeal['deal_code']);
                        if ($dataOrder == null) {
                            $checkInsertOrder = 1;
                        }
                    }
                }
            }



            DB::commit();
            return [
                "check_create_customer" => $checkCreateCustomer,
                "check_create_contract" => $checkCreateContract,
                "check_cannot_create_contract" => $checkCannotCreateContract,
                "check_create_contract_annex" => $checkCreateContractAnnex,
                "checkInsertOrder" => $checkInsertOrder,
                "contract_id" => $contractId,
                "deal_id" => $input['deal_id'],
                "deal_code" => $dataDeal['deal_code'],
                "deal_name" => $dataDeal['deal_name'],
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage(),
                '__line' => $e->getLine()
            ];
        }
    }

    /**
     * check SP, DV, thẻ DV có được tính hoa hồng cho deal không, có thì insert order commission
     *
     * @param $objectType
     * @param $objectId
     * @param $dealId
     * @param $orderDetailId
     */
    public function insertOrderCommission($objectType, $objectId, $dealId, $orderDetailId)
    {
        $mOrderCommission = new OrderCommissionTable();
        $dataOrderCommission = [
            'order_detail_id' => $orderDetailId,
            'deal_id' => $dealId,
            'created_by' => Auth::id(),
        ];
        if ($objectType == 'product') {
            $mProduct = new ProductChildTable();
            $product = $mProduct->getItem($objectId);
            if ($product['deal_commission_value'] != null && $product['deal_commission_value'] > 0) {
                if ($product['type_deal_commission'] == 'money') {
                    $dataOrderCommission['deal_money'] = $product['deal_commission_value'];
                } elseif ($product['type_deal_commission'] == 'percent') {
                    $dataOrderCommission['deal_money'] = round(($product['price'] * $product['deal_commission_value']) / 100, 2);
                }
                $mOrderCommission->add($dataOrderCommission);
            }
        } elseif ($objectType == 'service') {
            $mService = new ServiceTable();
            $service = $mService->getItem($objectId);
            if ($service['deal_commission_value'] != null && $service['deal_commission_value'] > 0) {
                if ($service['type_deal_commission'] == 'money') {
                    $dataOrderCommission['deal_money'] = $service['deal_commission_value'];
                } elseif ($service['type_deal_commission'] == 'percent') {
                    $dataOrderCommission['deal_money'] = round(($service['price_standard'] * $service['deal_commission_value']) / 100, 2);
                }
                $mOrderCommission->add($dataOrderCommission);
            }
        } elseif ($objectType == 'service_card') {
            $mServiceCard = new ServiceCardTable();
            $serviceCard = $mServiceCard->getItem($objectId);
            if ($serviceCard['deal_commission_value'] != null && $serviceCard['deal_commission_value'] > 0) {
                if ($serviceCard['type_deal_commission'] == 'money') {
                    $dataOrderCommission['deal_money'] = $serviceCard['deal_commission_value'];
                } elseif ($serviceCard['type_deal_commission'] == 'percent') {
                    $dataOrderCommission['deal_money'] = round(($serviceCard['price'] * $serviceCard['deal_commission_value']) / 100, 2);
                }
                $mOrderCommission->add($dataOrderCommission);
            }
        }
    }

    const PLUS = "plus";
    const SUBTRACT = "subtract";

    /**
     * Data cho view thanh toan don hang cho deal
     *
     * @param $dealId
     * @return array|mixed
     */
    public function dataViewPayment($dealId)
    {
        $mPaymentMethod = new PaymentMethodTable();
        $mCustomer = new CustomerTable();
        $mStaff = new StaffsTable();
        $mCustomerDealDetail = new CustomerDealDetailTable();
        $mMoneyBranch = new CustomerBranchMoneyTable();
        $mConfig = new ConfigTable();
        $mService = new ServiceTable();
        $mProductChild = new ProductChildTable();
        $mServiceCard = new ServiceCardTable();
        $mServiceBranchPrice = new ServiceBranchPriceTable();
        $mCustomerBranchMoney = new CustomerBranchMoneyTable();
        $mRoom = new RoomTable();
        $mBranchMoneyLog = app()->get(CustomerBranchMoneyLogTable::class);
       

        $optionPaymentMethod = $mPaymentMethod->getOption();  // lấy phương thức thanh toán
        $optionCustomer = $mCustomer->getOption(); // danh sách người giới thiệu
        $optionStaff = $mStaff->getOption();  // danh sach nhan vien phuc vu
        //Lấy thông tin deal
        $getDeal = $this->customerDeal->getDealForPayment($dealId);
       
        //Lấy thông tin nhân viên
        $staff = $mStaff->getItem(Auth::id());
       
        //Lấy chi tiết deal
        $getDealDetail = $mCustomerDealDetail->getDetailByDealCode($getDeal['deal_code']);
        //Lấy tiền KH
        $branchId = null;
        //Lấy cấu hình 1 chi nhánh or liên chi nhánh
        $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];

        if ($configBranch == 0) {
            //Lấy chi nhánh của nv đăng nhập
            $branchId = Auth()->user()->branch_id;
        }

        //Lấy tổng tiền thành viên cộng vào
        $totalPlusMoney = $mBranchMoneyLog->getTotalMoney($getDeal['customer_id'], $branchId, self::PLUS);
        //Lấy tổng tiền thành viên trừ ra
        $totalSubtractMoney = $mBranchMoneyLog->getTotalMoney($getDeal['customer_id'], $branchId, self::SUBTRACT);

        $moneyCustomer = floatval($totalPlusMoney['total']) - floatval($totalSubtractMoney['total']);

        $dataDetail = [];
        foreach ($getDealDetail as $v) {
            if ($v['object_id'] != null) {
                $dataDetail [] = [
                    'order_detail_id' => '',
                    'object_id' => $v['object_id'],
                    'object_type' => $v['object_type'],
                    'object_name' => $v['object_name'],
                    'object_code' => $v['object_code'],
                    'price' => $v['price'],
                    'quantity' => $v['quantity'],
                    'discount' => $v['discount'],
                    'amount' => $v['amount'],
                    'voucher_code' => '',
                    'max_quantity_card' => '',
                    'staff_id' => '',
                    'refer_id' => '',
                    'number_ran' => md5(uniqid(rand(1, 8), true)),
                    'is_change_price' => 0,
                    'is_check_promotion' => 1,
                    'note' => '',
                    'inventory_management' => ''
                ];
            }
        }
        //Lấy cấu hình thay đổi giá
        $customPrice = $mConfig->getInfoByKey('customize_price')['value'];
        //Lấy option dịch vụ
        $optionService = $mServiceBranchPrice->getOptionService(Auth()->user()->branch_id);
        //Lấy option phòng
        $optionRoom = $mRoom->getRoomOption();
        if ($getDeal['customer_id'] == '') {
            $getDeal = $this->customerDeal->getDealOfLeadForPayment($dealId);
        }
        // sale : 1 1 0 1
        // bs: 0 1 0 1
        // thu ngân: 0 0 1 0

        $is_edit_full = 0;
        $is_edit_staff = 0;
        $is_payment_order = 0;
        if (in_array('is_payment_order', session('routeList'))) {
            $is_payment_order = 1;
        }
        if (in_array('is_edit_full', session('routeList'))) {
            $is_edit_full = 1;
        }
        if (in_array('is_edit_staff', session('routeList'))) {
            $is_edit_staff = 1;
        }

        $is_update_order = 0;
        if ($is_edit_full == 1) {
            $is_update_order = 1;
            $is_edit_staff = 1;
        }
        if ($is_edit_staff == 1) {
            $is_update_order = 1;
        }

        $mCustomerDebt = app()->get(CustomerDebtTable::class);

        //Lấy công nợ của KH
        $amountDebt = $mCustomerDebt->getDebtByCustomer($getDeal['customer_id']);

        $debt = 0;
        if (count($amountDebt) > 0) {
            foreach ($amountDebt as $item) {
                $debt += $item['amount'] - $item['amount_paid'];
            }
        }

        $mConfigTab = app()->get(OrderConfigTabTable::class);

        //Lấy cấu hình tab
        $getTab = $mConfigTab->getConfigTab();

        return [
            'optionPaymentMethod' => $optionPaymentMethod,
            'optionCustomer' => $optionCustomer,
            'optionStaff' => $optionStaff,
            'customPrice' => $customPrice,
            'dataDetail' => $dataDetail,
            'memberMoney' => $moneyCustomer,
            'item' => $getDeal,
            'optionService' => $optionService,
            'optionRoom' => $optionRoom,
            'is_edit_full' => $is_edit_full,
            'is_edit_staff' => $is_edit_staff,
            'is_payment_order' => $is_payment_order,
            'is_update_order' => $is_update_order,
            'debt' => $debt,
            'getTab' => $getTab
        ];
    }

    /**
     * Lấy thông tin khuyến mãi của sp, dv, thẻ dv
     *
     * @param $objectType
     * @param $objectCode
     * @param $customerId
     * @param $orderSource
     * @param $promotionType
     * @param $quantity
     * @return mixed|void
     */
    public function getPromotionDetail($objectType, $objectCode, $customerId, $orderSource, $promotionType, $quantity = null)
    {
        $mPromotionDetail = new PromotionDetailTable();
        $mDaily = new PromotionDailyTimeTable();
        $mWeekly = new PromotionWeeklyTimeTable();
        $mMonthly = new PromotionMonthlyTimeTable();
        $mFromTo = new PromotionDateTimeTable();
        $mCustomer = new CustomerTable();
        $mPromotionApply = new PromotionObjectApplyTable();

        $currentDate = Carbon::now()->format('Y-m-d H:i:s');
        $currentTime = Carbon::now()->format('H:i');

        $price = [];
        $arrGift = [];

        $getDetail = $mPromotionDetail->getPromotionDetail($objectType, $objectCode, $promotionType, $currentDate);

        if (count($getDetail) > 0) {
            foreach ($getDetail as $v) {
                //Check thời gian diễn ra chương trình
                if ($currentDate < $v['start_date'] || $currentDate > $v['end_date']) {
                    //Kết thúc 1 lần for
                    continue;
                }
                //Check chi nhánh áp dụng
                if ($v['branch_apply'] != 'all' &&
                    !in_array(Auth()->user()->branch_id, explode(',', $v['branch_apply']))) {
                    continue;
                }
                //Check KM theo time đặc biệt
                if ($v['is_time_campaign'] == 1) {
                    switch ($v['time_type']) {
                        case 'D':
                            $daily = $mDaily->getDailyByPromotion($v['promotion_code']);

                            if ($daily != null) {
                                $startTime = Carbon::createFromFormat('H:i:s', $daily['start_time'])->format('H:i');
                                $endTime = Carbon::createFromFormat('H:i:s', $daily['end_time'])->format('H:i');
                                //Kiểm tra giờ bắt đầu, giờ kết thúc
                                if ($currentTime < $startTime || $currentTime > $endTime) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            }
                            break;
                        case 'W':
                            $weekly = $mWeekly->getWeeklyByPromotion($v['promotion_code']);
                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['default_start_time'])->format('H:i');
                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['default_end_time'])->format('H:i');

                            switch (Carbon::now()->format('l')) {
                                case 'Monday':
                                    if ($weekly['is_monday'] == 1) {
                                        if ($weekly['is_other_monday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_monday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_monday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Tuesday':
                                    if ($weekly['is_tuesday'] == 1) {
                                        if ($weekly['is_other_tuesday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_tuesday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_tuesday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Wednesday':
                                    if ($weekly['is_wednesday'] == 1) {
                                        if ($weekly['is_other_wednesday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_wednesday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_wednesday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Thursday':
                                    if ($weekly['is_thursday'] == 1) {
                                        if ($weekly['is_other_monday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_thursday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_thursday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Friday':
                                    if ($weekly['is_friday'] == 1) {
                                        if ($weekly['is_other_friday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_friday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_friday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Saturday':
                                    if ($weekly['is_saturday'] == 1) {
                                        if ($weekly['is_other_saturday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_saturday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_saturday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Sunday':
                                    if ($weekly['is_sunday'] == 1) {
                                        if ($weekly['is_other_sunday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_sunday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_sunday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                            }
                            //Kiểm tra giờ bắt đầu, giờ kết thúc
                            if ($currentTime < $startTime || $currentTime > $endTime) {
                                //Kết thúc 1 lần for
                                continue 2;
                            }
                            break;
                        case 'M':
                            $monthly = $mMonthly->getMonthlyByPromotion($v['promotion_code']);

                            if (count($monthly) > 0) {
                                $next = false;

                                foreach ($monthly as $v1) {
                                    $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $v1['run_date'] . ' ' . $v1['start_time'])->format('Y-m-d H:i');
                                    $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $v1['run_date'] . ' ' . $v1['end_time'])->format('Y-m-d H:i');

                                    if ($currentDate > $startDate && $currentDate < $endDate) {
                                        $next = true;
                                    }
                                }

                                if ($next == false) {
                                    //Kết thúc 1 lần for
                                    continue 2;
                                }
                            } else {
                                //Kết thúc vòng for
                                continue 2;
                            }
                            break;
                        case 'R':
                            $fromTo = $mFromTo->getDateTimeByPromotion($v['promotion_code']);

                            if ($fromTo != null) {
                                $startFrom = Carbon::createFromFormat('Y-m-d H:i:s', $fromTo['form_date'] . ' ' . $fromTo['start_time'])->format('Y-m-d H:i');
                                $endFrom = Carbon::createFromFormat('Y-m-d H:i:s', $fromTo['to_date'] . ' ' . $fromTo['end_time'])->format('Y-m-d H:i');

                                if ($currentDate < $startFrom || $currentDate > $endFrom) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            }
                            break;
                    }
                }

                //Check KM theo type = discount or gift
                if ($v['promotion_type'] != $promotionType) {
                    //Kết thúc 1 lần for
                    continue;
                }

                //Check nguồn đơn hàng
                if ($v['order_source'] != 'all' && $v['order_source'] != $orderSource) {
                    //Kết thúc 1 lần for
                    continue;
                }
                //Check đối tượng áp dụng
                if ($v['promotion_apply_to'] != 1 && $v['promotion_apply_to'] != null) {
                    //Lấy thông tin khách hàng
                    $getCustomer = $mCustomer->getItem($customerId);

                    if ($getCustomer == null || $getCustomer['customer_id'] == 1) {
                        //Kết thúc 1 lần for
                        continue;
                    }

                    if ($getCustomer['member_level_id'] == null) {
                        $getCustomer['member_level_id'] = 1;
                    }

                    $objectId = '';
                    if ($v['promotion_apply_to'] == 2) {
                        $objectId = $getCustomer['member_level_id'];
                    } else if ($v['promotion_apply_to'] == 3) {
                        $objectId = $getCustomer['customer_group_id'];
                    } else if ($v['promotion_apply_to'] == 4) {
                        $objectId = $v['customer_id'];
                    }

                    $getApply = $mPromotionApply->getApplyByObjectId($v['promotion_code'], $objectId);

                    if ($getApply == null) {
                        //Kết thúc 1 lần for
                        continue;
                    }
                }

                //Check quota (số tiền)
                if ($promotionType == 1) {
                    $price [] = $v['promotion_price'];
                } else {
                    if ($quantity >= $v['quantity_buy']) {
                        $multiplication = intval($quantity / $v['quantity_buy']);
                        //Số quà được tặng
                        $totalGift = intval($v['quantity_gift'] * $multiplication);
                        //Lấy quota_use nếu tính áp dụng promotion này
                        $quotaUse = floatval($v['quota_use']) + $totalGift;
                        //Check số lượng cần mua để dc quà + quota_use
                        if ($v['quota'] == 0 || $v['quota'] == '' || $quotaUse <= floatval($v['quota'])) {
                            //Lấy giá trị quà tặng
                            $priceGift = $this->getPriceObjectPromotion($v['gift_object_type'], $v['gift_object_code']);
                            $arrGift [] = [
                                'promotion_code' => $v['promotion_code'],
                                'gift_object_type' => $v['gift_object_type'],
                                'gift_object_id' => $v['gift_object_id'],
                                'gift_object_name' => $v['gift_object_name'],
                                'gift_object_code' => $v['gift_object_code'],
                                'multiplication' => $multiplication . '-' . $quantity . '-' . $v['quantity_buy'],
                                'quantity_gift' => $totalGift,
                                //mới update param thêm
                                'quantity_buy' => $v['quantity_buy'],
                                'quota' => !empty($v['quota']) ? $v['quota'] : 0,
                                'quota_use' => floatval($v['quota_use']),
                                'total_price_gift' => $priceGift * $totalGift
                            ];
                        }
                    }
                }
            }
        }

        //Trả về kết quả khuyến mãi
        //Khuyến mãi giảm giá
        if ($promotionType == 1) {
            if (count($price) > 0) {
                //Lấy giả giảm ưu đãi nhất
                return min($price);
            } else {
                return 0;
            }
        } else {
            //Khuyến mãi quà tặng
            if (count($arrGift) > 0) {
                //Lấy quà tặng ưu đãi nhất
                return $this->getGiftMostPreferential($arrGift);
            } else {
                return [];
            }
        }
    }

    /**
     * Lấy giá trị khuyến mãi sp, dv, thẻ dv
     *
     * @param $objectType
     * @param $objectCode
     * @return int
     */
    private function getPriceObjectPromotion($objectType, $objectCode)
    {
        $price = 0;

        switch ($objectType) {
            case 'product':
                $mProduct = new ProductChildTable();
                //Lấy thông tin sp khuyến mãi
                $getProduct = $mProduct->getProductPromotion($objectCode);
                $price = $getProduct['new_price'];

                break;
            case 'service':
                $mService = new ServiceTable();
                //Lấy thông tin dv khuyến mãi
                $getService = $mService->getServicePromotion($objectCode);
                $price = $getService['new_price'];

                break;
            case 'service_card':
                $mServiceCard = new ServiceCardTable();
                //Lấy thông tin thẻ dv khuyến mãi
                $getServiceCard = $mServiceCard->getServiceCardPromotion($objectCode);
                $price = $getServiceCard['new_price'];

                break;
        }
        return floatval($price);
    }

    /**
     * Lấy quà tặng ưu đãi nhất
     *
     * @param $arrGift
     * @return array
     */
    private function getGiftMostPreferential($arrGift)
    {
        $result = [];

        if (count($arrGift) == 1) {
            //Có 1 CTKM quà tặng thì lấy chính nó
            return $arrGift[0];
        } else if (count($arrGift) > 1) {
            //Có nhiều CTKM quà tặng

            //Lấy quà tặng có giá trị cao nhất
            $giftPreferential = $this->chooseGiftPreferential($arrGift);

            $result = $giftPreferential;

            if (count($result) > 1) {
                //Lấy quà tặng có số lượng mua thấp nhất
                $giftMinBuy = $this->chooseGiftMinBuy($result);

                $result = $giftMinBuy;
            }

            if (count($result) > 1) {
                //Lấy quà tặng có quota - quota_use còn nhiều nhất (ưu tiên quota != 0 ko giới hạn)
                $giftQuota = $this->chooseGiftQuota($result);

                $result = $giftQuota;
            }
        }
        return $result[0];
    }

    /**
     * Chọn quà tặng có giá trị cao nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftPreferential($arrGift)
    {
        $result = [];
        //Lấy giá trị quà tặng có giá trị cao nhất
        $giftPrice = array_column($arrGift, 'total_price_gift');
        //Sắp xếp lại array có quà tặng giá trị cao nhất
        array_multisort($giftPrice, SORT_DESC, $arrGift);

        $result [] = [
            'promotion_code' => $arrGift[0]['promotion_code'],
            'multiplication' => $arrGift[0]['multiplication'],
            'gift_object_type' => $arrGift[0]['gift_object_type'],
            'gift_object_id' => $arrGift[0]['gift_object_id'],
            'gift_object_name' => $arrGift[0]['gift_object_name'],
            'gift_object_code' => $arrGift[0]['gift_object_code'],
            'quantity_gift' => $arrGift[0]['quantity_gift'],
            //mới update param thêm
            'quantity_buy' => $arrGift[0]['quantity_buy'],
            'quota' => $arrGift[0]['quota'],
            'quota_use' => $arrGift[0]['quota_use'],
            'total_price_gift' => $arrGift[0]['total_price_gift']
        ];

        unset($arrGift[0]);

        foreach ($arrGift as $v) {
            //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
            if ($v['total_price_gift'] >= $result[0]['total_price_gift']) {
                $result [] = [
                    'promotion_code' => $v['promotion_code'],
                    'multiplication' => $v['multiplication'],
                    'gift_object_type' => $v['gift_object_type'],
                    'gift_object_id' => $v['gift_object_id'],
                    'gift_object_name' => $v['gift_object_name'],
                    'gift_object_code' => $v['gift_object_code'],
                    'quantity_gift' => $v['quantity_gift'],
                    //mới update param thêm
                    'quantity_buy' => $v['quantity_buy'],
                    'quota' => $v['quota'],
                    'quota_use' => $v['quota_use'],
                    'total_price_gift' => $v['total_price_gift']
                ];
            }
        }

        return $result;
    }

    /**
     * Chọn quà tặng có lượng mua thấp nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftMinBuy($arrGift)
    {
        //Có nhiều promotion bằng giá trị thì check số lượng mua (lợi ích khách hàng)
        $result = [];
        //Lấy quà tặng có số lượng mua thấp nhất
        $quantityBuy = array_column($arrGift, 'quantity_buy');
        //Sắp xếp lại array có số lượng cần mua thấp nhất
        array_multisort($quantityBuy, SORT_ASC, $arrGift);

        $result [] = [
            'promotion_code' => $arrGift[0]['promotion_code'],
            'multiplication' => $arrGift[0]['multiplication'],
            'gift_object_type' => $arrGift[0]['gift_object_type'],
            'gift_object_id' => $arrGift[0]['gift_object_id'],
            'gift_object_name' => $arrGift[0]['gift_object_name'],
            'gift_object_code' => $arrGift[0]['gift_object_code'],
            'quantity_gift' => $arrGift[0]['quantity_gift'],
            //mới update param thêm
            'quantity_buy' => $arrGift[0]['quantity_buy'],
            'quota' => $arrGift[0]['quota'],
            'quota_use' => $arrGift[0]['quota_use'],
            'total_price_gift' => $arrGift[0]['total_price_gift']
        ];

        unset($arrGift[0]);

        foreach ($arrGift as $v) {
            //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
            if ($v['quantity_buy'] == $result[0]['quantity_buy']) {
                $result [] = [
                    'promotion_code' => $v['promotion_code'],
                    'multiplication' => $v['multiplication'],
                    'gift_object_type' => $v['gift_object_type'],
                    'gift_object_id' => $v['gift_object_id'],
                    'gift_object_name' => $v['gift_object_name'],
                    'gift_object_code' => $v['gift_object_code'],
                    'quantity_gift' => $v['quantity_gift'],
                    //mới update param thêm
                    'quantity_buy' => $v['quantity_buy'],
                    'quota' => $v['quota'],
                    'quota_use' => $v['quota_use'],
                    'total_price_gift' => $v['total_price_gift']
                ];
            }
        }

        return $result;
    }

    /**
     * Chọn quà tặng có quota còn lại cao nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftQuota($arrGift)
    {
        //Có nhiều promotion bằng giá trị + số lượng mua thì kiểm tra quota_use con lại (ưu tiên promotion có quota != 0)
        $result = [];

        $arrLimited = [];
        $arrUnLimited = [];

        foreach ($arrGift as $v) {
            if ($v['quota'] != 0) {
                $v['quota_balance'] = $v['quota'] - $v['quota_use'];
                $arrLimited [] = $v;
            } else {
                $arrUnLimited [] = $v;
            }
        }

        if (count($arrLimited) > 0) {
            //Ưu tiên lấy quà tặng có giới hạn quota

            //Lấy quà tặng có quota còn lại cao nhất
            $quantityQuota = array_column($arrLimited, 'quota_balance');
            //Sắp xếp lại array có số lượng cần mua thấp nhất
            array_multisort($quantityQuota, SORT_DESC, $arrLimited);

            $result [] = [
                'promotion_code' => $arrLimited[0]['promotion_code'],
                'multiplication' => $arrLimited[0]['multiplication'],
                'gift_object_type' => $arrLimited[0]['gift_object_type'],
                'gift_object_id' => $arrLimited[0]['gift_object_id'],
                'gift_object_name' => $arrLimited[0]['gift_object_name'],
                'gift_object_code' => $arrLimited[0]['gift_object_code'],
                'quantity_gift' => $arrLimited[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrLimited[0]['quantity_buy'],
                'quota' => $arrLimited[0]['quota'],
                'quota_use' => $arrLimited[0]['quota_use'],
                'total_price_gift' => $arrLimited[0]['total_price_gift']
            ];

            unset($arrLimited[0]);

            foreach ($arrLimited as $v) {
                //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
                if ($v['quota_balance'] == ($result[0]['quota'] - $result[0]['quota_use'])) {
                    $result [] = [
                        'promotion_code' => $v['promotion_code'],
                        'multiplication' => $v['multiplication'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_name' => $v['gift_object_name'],
                        'gift_object_code' => $v['gift_object_code'],
                        'quantity_gift' => $v['quantity_gift'],
                        //mới update param thêm
                        'quantity_buy' => $v['quantity_buy'],
                        'quota' => $v['quota'],
                        'quota_use' => $v['quota_use'],
                        'total_price_gift' => $v['total_price_gift']
                    ];
                }
            }
        }

        if (count($result) == 0 && count($arrUnLimited) > 0) {
            //Lấy quà tặng có quota_use thấp nhất
            $quantityQuotaUse = array_column($arrUnLimited, 'quota_use');
            //Sắp xếp lại array có số lượng cần mua thấp nhất
            array_multisort($quantityQuotaUse, SORT_ASC, $arrUnLimited);

            $result [] = [
                'promotion_code' => $arrUnLimited[0]['promotion_code'],
                'multiplication' => $arrUnLimited[0]['multiplication'],
                'gift_object_type' => $arrUnLimited[0]['gift_object_type'],
                'gift_object_id' => $arrUnLimited[0]['gift_object_id'],
                'gift_object_name' => $arrUnLimited[0]['gift_object_name'],
                'gift_object_code' => $arrUnLimited[0]['gift_object_code'],
                'quantity_gift' => $arrUnLimited[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrUnLimited[0]['quantity_buy'],
                'quota' => $arrUnLimited[0]['quota'],
                'quota_use' => $arrUnLimited[0]['quota_use'],
                'total_price_gift' => $arrUnLimited[0]['total_price_gift']
            ];

            unset($arrUnLimited[0]);

            foreach ($arrUnLimited as $v) {
                //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
                if ($v['quota_use'] <= $result[0]['quota_use']) {
                    $result [] = [
                        'promotion_code' => $v['promotion_code'],
                        'multiplication' => $v['multiplication'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_name' => $v['gift_object_name'],
                        'gift_object_code' => $v['gift_object_code'],
                        'quantity_gift' => $v['quantity_gift'],
                        //mới update param thêm
                        'quantity_buy' => $v['quantity_buy'],
                        'quota' => $v['quota'],
                        'quota_use' => $v['quota_use'],
                        'total_price_gift' => $v['total_price_gift']
                    ];
                }
            }
        }
        return $result;
    }

    /**
     * Lưu thông tin đơn hàng từ deal
     *
     * @param $input
     * @return array|mixed
     */
    public function saveOrder($input)
    {
        try {
            // order ( add column deal code )
            $mOrder = new OrderTable();
            $mCustomer = new CustomerTable();
            $mOrderDetailTable = new OrderDetailTable();
            $arrObject = $input['table_add'];

            $customerInfo = $mCustomer->getItem($input['customer_id']);
            $dataOrder = [
                'customer_id' => $input['customer_id'],
                'brand_id' => $customerInfo['brand_id'],
                'refer_id' => $input['refer_id'],
                'discount' => $input['discount_bill'],
                'voucher_code' => $input['voucher_bill'],
                'total' => (float)str_replace(',', '', $input['total_bill']),
                'amount' => (float)str_replace(',', '', $input['amount_bill']),
                'deal_code' => $input['deal_code'],
                'created_by' => Auth::id(),
            ];
            $orderId = $mOrder->add($dataOrder);
            // update order code
            $orderCode = 'DH_' . date('dmY') . sprintf("%02d", $orderId);
            $mOrder->edit(['order_code' => $orderCode], $orderId);
            // insert order detail
            if (isset($arrObject) && $arrObject != null) {
                foreach ($arrObject as $item) {
                    $dataOrderDetail = [
                        'order_id' => $orderId,
                        'object_id' => $item['object_id'],
                        'object_code' => $item['object_code'],
                        'object_name' => $item['object_name'],
                        'object_type' => $item['object_type'],
                        'price' => str_replace(',', '', $item['price']),
                        'discount' => $item['discount'],
                        'amount' => str_replace(',', '', $item['amount']),
                        'quantity' => $item['quantity'],
                        'staff_id' => isset($item['staff_id']) != null ? implode(',', $item['staff_id']) : null,
                        'created_by' => Auth::id(),
                    ];
                    $orderDetailId = $mOrderDetailTable->add($dataOrderDetail);
                    // check SP, DV, thẻ DV có được tính hoa hồng cho deal không, có thì insert order commission
                    $this->insertOrderCommission($item['object_type'], $item['object_id'], $input['deal_id'], $orderDetailId);
                }
            }
            // Hoàn tất deal
            $mDeal = new CustomerDealTable();
//            $mDeal->editByCode($input['deal_code'], ['journey_code' => self::JOURNEY_DEAL_END]);
            return [
                'error' => false,
                'message' => __('Thêm đơn hàng thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm đơn hàng thất bại')
            ];
        }
    }

    /**
     * Tạo mới or chỉnh sửa đơn hàng
     *
     * @param $input
     * @return array
     */
    public function saveOrUpdateOrder($input)
    {
        try {
            $orderId = '';
            $orderCode = '';
            // order ( add column deal code )
            $mOrder = new OrderTable();
            $mCustomer = new CustomerTable();
            $mOrderDetailTable = new OrderDetailTable();

            $arrObject = $input['table_add'];
            //Lấy thông tin KH
            $customerInfo = $mCustomer->getItem($input['customer_id']);

            $mCustomerContact = app()->get(\Modules\Admin\Models\CustomerContactTable::class);

            $detailAddress = $mCustomerContact->getDetailContact($input['customer_contact_id']);

            $dataOrder = [
                'customer_id' => $input['customer_id'],
                'branch_id' => Auth()->user()->branch_id,
                'refer_id' => $input['refer_id'],
                'discount' => $input['discount_bill'],
                'voucher_code' => $input['voucher_bill'],
                'total' => (float)str_replace(',', '', $input['total_bill']),
                'amount' => (float)str_replace(',', '', $input['amount_bill']),
                'deal_code' => $input['deal_code'],
                'customer_contact_code' => $detailAddress != null ? $detailAddress['customer_contact_code'] : '',
                'customer_contact_id' => $input['customer_contact_id'],
                'receive_at_counter' => $input['receipt_info_check'] == 1 ? 0 : 1,
                'type_time' => $input['type_time'],
                'time_address' => $input['time_address'] != '' ? Carbon::createFromFormat('d/m/Y', $input['time_address'])->format('Y-m-d') : '',
                'tranport_charge' => $input['tranport_charge'],
                'type_shipping' => $input['delivery_type'],
                'delivery_cost_id' => $input['delivery_cost_id'],
                'discount_member' => $input['discount_member'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];

            if (isset($input['order_id']) && $input['order_id'] != '') {
                $orderId = $input['order_id'];
                $orderCode = $input['order_code'];
                //Chỉnh sửa đơn hàng
                $mOrder->edit($dataOrder, $orderId);
                // insert order detail
                $mOrderDetailTable->removeOrderDetailById($orderId);

                if (isset($arrObject) && $arrObject != null) {
                    foreach ($arrObject as $item) {
                        $dataOrderDetail = [
                            'order_id' => $orderId,
                            'object_id' => $item['object_id'],
                            'object_name' => $item['object_name'],
                            'object_type' => $item['object_type'],
                            'object_code' => $item['object_code'],
                            'price' => $item['price'],
                            'quantity' => $item['quantity'],
                            'discount' => str_replace(',', '', $item['discount']),
                            'voucher_code' => $item['voucher_code'],
                            'amount' => str_replace(',', '', $item['amount']),
                            'refer_id' => $input['refer_id'],
                            'staff_id' => isset($item['staff_id']) && $item['staff_id'] != null ? implode(',', $item['staff_id']) : null,
                            'is_change_price' => $item['is_change_price'],
                            'is_check_promotion' => $item['is_check_promotion'],
                            'note' => $item['note'] ?? null,
                            'created_at_day' => Carbon::now()->format('d'),
                            'created_at_month' => Carbon::now()->format('m'),
                            'created_at_year' => Carbon::now()->format('Y'),
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ];
                        //Thêm chi tiết đơn hàng
                        $orderDetailId = $mOrderDetailTable->add($dataOrderDetail);

                        //Lưu dịch vụ kèm theo
                        if (isset($item['array_attach']) && count($item['array_attach']) > 0) {
                            foreach ($item['array_attach'] as $v1) {
                                //Lưu chi tiết đơn hàng
                                $mOrderDetailTable->add([
                                    'order_id' => $orderId,
                                    'object_id' => $v1['object_id'],
                                    'object_name' => $v1['object_name'],
                                    'object_type' => $v1['object_type'],
                                    'object_code' => $v1['object_code'],
                                    'price' => $v1['price'],
                                    'quantity' => $v1['quantity'],
                                    'amount' => $v1['price'] * $v1['quantity'],
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
                                    'order_detail_id_parent' => $orderDetailId,
                                    'created_at_day' => Carbon::now()->format('d'),
                                    'created_at_month' => Carbon::now()->format('m'),
                                    'created_at_year' => Carbon::now()->format('Y'),
                                ]);
                            }
                        }

                        //remove order commission
                        $mOrderCommission = new OrderCommissionTable();
                        $mOrderCommission->removeByOrderDetailAndDeal($orderDetailId, $input['deal_id']);
                        // check SP, DV, thẻ DV có được tính hoa hồng cho deal không, có thì insert order commission
                        $this->insertOrderCommission($item['object_type'], $item['object_id'], $input['deal_id'], $orderDetailId);
                    }
                }
            } else {
                //Thêm đơn hàng
                $orderId = $mOrder->add($dataOrder);
                // update order code
                $orderCode = 'DH_' . date('dmY') . sprintf("%02d", $orderId);
                $mOrder->edit(['order_code' => $orderCode], $orderId);
                // insert order detail
                if (isset($arrObject) && $arrObject != null) {
                    foreach ($arrObject as $item) {
                        $dataOrderDetail = [
                            'order_id' => $orderId,
                            'object_id' => $item['object_id'],
                            'object_name' => $item['object_name'],
                            'object_type' => $item['object_type'],
                            'object_code' => $item['object_code'],
                            'price' => $item['price'],
                            'quantity' => $item['quantity'],
                            'discount' => str_replace(',', '', $item['discount']),
                            'voucher_code' => $item['voucher_code'],
                            'amount' => str_replace(',', '', $item['amount']),
                            'refer_id' => $input['refer_id'],
                            'staff_id' => isset($item['staff_id']) && $item['staff_id'] != null ? implode(',', $item['staff_id']) : null,
                            'is_change_price' => $item['is_change_price'],
                            'is_check_promotion' => $item['is_check_promotion'],
                            'note' => $item['note'] ?? null,
                            'created_at_day' => Carbon::now()->format('d'),
                            'created_at_month' => Carbon::now()->format('m'),
                            'created_at_year' => Carbon::now()->format('Y'),
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ];
                        //Thêm chi tiết đơn hàng
                        $orderDetailId = $mOrderDetailTable->add($dataOrderDetail);
                        //Lưu dịch vụ kèm theo
                        if (isset($item['array_attach']) && count($item['array_attach']) > 0) {
                            foreach ($item['array_attach'] as $v1) {
                                //Lưu chi tiết đơn hàng
                                $mOrderDetailTable->add([
                                    'order_id' => $orderId,
                                    'object_id' => $v1['object_id'],
                                    'object_name' => $v1['object_name'],
                                    'object_type' => $v1['object_type'],
                                    'object_code' => $v1['object_code'],
                                    'price' => $v1['price'],
                                    'quantity' => $v1['quantity'],
                                    'amount' => $v1['price'] * $v1['quantity'],
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
                                    'order_detail_id_parent' => $orderDetailId,
                                    'created_at_day' => Carbon::now()->format('d'),
                                    'created_at_month' => Carbon::now()->format('m'),
                                    'created_at_year' => Carbon::now()->format('Y'),
                                ]);
                            }
                        }
                        // check SP, DV, thẻ DV có được tính hoa hồng cho deal không, có thì insert order commission
                        $this->insertOrderCommission($item['object_type'], $item['object_id'], $input['deal_id'], $orderDetailId);
                    }
                }
                // Hoàn tất deal
                $mDeal = new CustomerDealTable();
//            $mDeal->editByCode($input['deal_code'], ['journey_code' => self::JOURNEY_DEAL_END]);
                //Lưu log ZNS (đơn hàng mới)
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'order_success',
                    'customer_id' => $input['customer_id'],
                    'object_id' => $orderId,
                    'tenant_id' => session()->get('idTenant')
                ]);
            }

            $mConfig = app()->get(ConfigTable::class);
            //Kiểm tra có tạo ticket không
            $configCreateTicket = $mConfig->getInfoByKey('save_order_create_ticket')['value'];

            $isCreateTicket = 0;

            if ($input['customer_id'] != 1 && $configCreateTicket == 1) {
                $isCreateTicket = 1;
            }

            return [
                'error' => false,
                'message' => __('Thêm đơn hàng thành công'),
                'order_id' => $orderId,
                'order_code' => $orderCode,
                'is_create_ticket' => $isCreateTicket
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm đơn hàng thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ];
        }
    }

    /**
     * thanh toán đơn hàng trực tiếp từ deal
     *
     * @param $input
     * @return array|mixed
     */
    public function submitPayment($input)
    {
        DB::beginTransaction();
        try {
            $mStaff = new StaffsTable();
            $mOrder = new OrderTable();
            $mOrderDetail = new OrderDetailTable();
            $mVoucher = new VoucherTable();
            $mOrderLog = new OrderLogTable();
            $mOrderApp = app()->get(OrderAppRepoInterface::class);
            $mPromotionLog = new PromotionLogTable();
            $mSpaInfo = new SpaInfoTable();
            $mCustomerDebt = new CustomerDebtTable();
            $mReceipt = new ReceiptTable();
            $mReceiptDetail = new ReceiptDetailTable();
            $mCustomerBranchMoney = new CustomerBranchMoneyTable();
            $mCustomer = new CustomerTable();
            $mBranchMoneyLog = app()->get(CustomerBranchMoneyLogTable::class);

            $mReceipt = new \Modules\Admin\Models\ReceiptTable();
            $mReceiptDetail = new \Modules\Admin\Models\ReceiptDetailTable();

            $dataInsertAndCalculator = [];

            $staffInfo = $mStaff->getItem(Auth::id()); // lấy chi nhánh hiện tại
            $dataOrder = [
                'customer_id' => $input['customer_id'],
                'total' => str_replace(',', '', $input['total_bill']),
                'discount' => str_replace(',', '', $input['discount_bill']),
                'branch_id' => $staffInfo['branch_id'],
                'amount' => str_replace(',', '', $input['amount_bill']),
                'voucher_code' => $input['voucher_bill'],
                'process_status' => 'paysuccess',
                'created_by' => Auth::id(),
                'refer_id' => $input['refer_id'],
                'discount_member' => $input['discount_member'],
                'deal_code' => $input['deal_code'],
                'cashier_by' => Auth()->id(),
                'cashier_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'tranport_charge' => $input['tranport_charge'],
                'type_shipping' => $input['delivery_type'],
                'delivery_cost_id' => $input['delivery_cost_id']
            ];
            $orderId = $input['order_id'];
            $orderCode = $input['order_code'];
            //Chỉnh sửa đơn hàng
            $mOrder->edit($dataOrder, $orderId);
            // check voucher bill
            if (isset($input['voucher_bill']) && $input['voucher_bill'] != null) {
                $voucherInfo = $mVoucher->getCodeItem($input['voucher_bill']);
                $mVoucher->editVoucherOrder(['total_use' => ($voucherInfo['total_use'] + 1)], $input['voucher_bill']);
            }

            $arrRemindUse = [];
            // remove order detail -> add again
            $mOrderDetail->removeOrderDetailById($orderId);

            // insert order detail + calculator commission
            if (isset($input['table_add']) && $input['table_add'] != null) {
                $dataInsertAndCalculator = $this->insertOrderDetailAndCalculatorCommission($input['table_add'], $input, $orderId, $orderCode, $staffInfo['branch_id']);
                $arrRemindUse = $dataInsertAndCalculator['arrRemindUse'];
            } else {
                return response()->json([
                    'table_error' => 1
                ]);
            }
            // insert order log đơn hàng mới, hoàn tất
            $mOrderLog->insert([
                [
                    'order_id' => $orderId,
                    'created_type' => 'backend',
                    'status' => 'new',
                    'created_by' => Auth()->id(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'note_vi' => 'Đặt hàng thành công',
                    'note_en' => 'Order success',
                ],
                [
                    'order_id' => $orderId,
                    'created_type' => 'backend',
                    'status' => 'ordercomplete',
                    'created_by' => Auth()->id(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'note_vi' => 'Hoàn tất',
                    'note_en' => 'Order completed',
                ]
            ]);
            if (isset($input['custom_price']) && $input['custom_price'] == 1) {
                //Lấy thông tin CTKM dc áp dụng cho đơn hàng
                $getPromotionLog = $mOrderApp->groupQuantityObjectBuy($dataInsertAndCalculator['arrObjectBuy']);
                //Insert promotion log
                $arrPromotionLog = $getPromotionLog['promotion_log'];
                $mPromotionLog->insert($arrPromotionLog);
                //Cộng quota_use promotion quà tặng
                $arrQuota = $getPromotionLog['promotion_quota'];
                $mOrderApp->plusQuotaUsePromotion($arrQuota);
            }

            //Lấy phương thức thanh toán
            $arrMethodWithMoney = $input['array_method'];

            $amountBill = str_replace(',', '', $input['amount_bill']);
            $status = '';
            if ($input['amount_all'] != '') {
                $amountReceiptAll = 0;

                foreach ($arrMethodWithMoney as $methodCode => $money) {
                    if ($money > 0) {
                        $amountReceiptAll += $money;
                    }
                }

                if ($amountReceiptAll >= $amountBill) {
                    $status = 'paid';
                } else {
                    //Cập nhật trạng thái đơn hàng thanh toán còn thiếu
                    $mOrder->edit(['process_status' => 'pay-half'], $orderId);
                }
            } else {
                $amountReceiptAll = 0;
            }
            if ($amountBill != 0) {
                if ($amountReceiptAll < $amountBill) {
                    //Check KH là hội viên
                    if ($input['customer_id'] != 1) {
                        //Check cấu hình thanh toán nhiều lần
                        $spaInfo = $mSpaInfo->getItem(1);
                        if ($spaInfo['is_part_paid'] == 1) {
                            if ($input['order_source_id'] != 2) {
                                $status = 'paid';
                                //insert customer debt
                                $dataDebt = [
                                    'customer_id' => $input['customer_id'],
                                    'debt_code' => 'debt',
                                    'staff_id' => Auth::id(),
                                    'note' => $input['note'],
                                    'debt_type' => 'order',
                                    'order_id' => $orderId,
                                    'status' => 'unpaid',
                                    'amount' => $amountBill - $amountReceiptAll,
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id()
                                ];
                                $debtId = $mCustomerDebt->add($dataDebt);
                                //update debt code
                                $mCustomerDebt->edit([
                                    'debt_code' => 'CN_' . date('dmY') . $debtId
                                ], $debtId);
                            }
                        } else {
                            return response()->json([
                                'amount_detail_small' => 1,
                                'message' => __('Số tiền không hợp lệ')
                            ]);
                        }
                    } else {
                        return response()->json([
                            'amount_detail_small' => 1,
                            'message' => __('Số tiền không hợp lệ')
                        ]);
                    }
                }
            }
            $amountReturn = str_replace(',', '', $input['amount_return']);

            // get receipt by order id => remove receipt and receipt detail
            $dataReceipt = $mReceipt->getItem($orderId);
            // no receipt by order_id but $dataReceipt still have data (data with all null field) ?????
            if ($dataReceipt != null && isset($dataReceipt['receipt_id']) && $dataReceipt['receipt_id'] != null) {
                $mReceipt->removeReceipt($orderId);
                $mReceiptDetail->removeReceiptDetail($dataReceipt['receipt_id']);
            }
            // insert receipt
            $dataReceipt = [
                'customer_id' => $input['customer_id'],
                'staff_id' => Auth::id(),
                'object_id' => $orderId,
                'object_type' => 'order',
                'order_id' => $orderId,
                'total_money' => $amountReceiptAll,
                'voucher_code' => $input['voucher_bill'],
                'status' => $status,
                'is_discount' => 1,
                'amount' => $amountBill,
                'amount_paid' => $amountReceiptAll > $amountBill ? $amountBill : $amountReceiptAll,
                'amount_return' => $amountReceiptAll > $amountBill ? $amountReceiptAll - $amountBill : 0,
                'note' => $input['note'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'receipt_type_code' => 'RTC_ORDER',
                'object_accounting_type_code' => $orderCode, // order code
                'object_accounting_id' => $orderId, // order id
            ];
            if ($input['voucher_bill'] != null) {
                $dataReceipt['discount'] = $input['discount_bill'];
            } else {
                $dataReceipt['custom_discount'] = $input['discount_bill'];
            }
            $receiptId = $mReceipt->add($dataReceipt);
            $receiptCode = 'TT_' . date('dmY') . $receiptId;
            $mReceipt->edit(['receipt_code' => $receiptCode], $receiptId);
            // insert receipt detail
            // ở trên check rồi nên k check nữa
            foreach ($input['table_add'] as $item) {
                if ($item['object_type'] == 'member_card') {
                    $mReceiptDetail->add([
                        'receipt_id' => $receiptId,
                        'cashier_id' => Auth::id(),
                        'payment_method_code' => 'MEMBER_CARD',
                        'card_code' => $item['object_code'],
                        'amount' => $item['amount'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ]);
                }
            }

            $arrVnPay = null;

            $mReceiptOnline = new ReceiptOnlineTable();
            $mPaymentMethod = new \Modules\Payment\Models\PaymentMethodTable();

            $isNotifyMinAccount = 0;

            foreach ($arrMethodWithMoney as $methodCode => $money) {
                $itemMethod = $mPaymentMethod->getPaymentMethodByCode($methodCode);
                if ($money > 0) {
                    $dataReceiptDetail = [
                        'receipt_id' => $receiptId,
                        'cashier_id' => Auth::id(),
                        'amount' => $money,
                        'payment_method_code' => $methodCode,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ];
                    if ($methodCode == 'MEMBER_MONEY') {
                        // Check số tiền thành viên
                        if ($money <= $amountBill) { // trừ tiên thành viên
                            if ($money < $input['member_money']) {
                                //Lưu chi tiết thanh toán
                                $mReceiptDetail->add($dataReceiptDetail);
                                //Lấy thông tin KH
                                $customerMoney = $mCustomer->getItem($input['customer_id']);
                                //Cập nhật tài khoản KH
                                $mCustomer->edit([
                                    'account_money' => $customerMoney['account_money'] - $money
                                ], $input['customer_id']);

                                $mConfig = app()->get(ConfigTable::class);
                                //Lấy cấu hình số tiền tối thiểu
                                $configMinAccount = $mConfig->getInfoByKey('money_account_min')['value'];

                                if (($customerMoney['account_money'] - $money) <= $configMinAccount) {
                                    $isNotifyMinAccount = 1;
                                }
                                //Lưu log - tiền
                                $mBranchMoneyLog->add([
                                    "customer_id" => $input['customer_id'],
                                    "branch_id" => Auth()->id(),
                                    "source" => "member_money",
                                    "type" => 'subtract',
                                    "money" => $money,
                                    "screen" => 'order',
                                    "screen_object_code" => $orderCode
                                ]);
                            } else {
                                return response()->json([
                                    'error_account_money' => 1,
                                    'message' => __('Số tiền còn lại trong tài khoản không đủ'),
                                    'money' => $input['member_money']
                                ]);
                            }
                        } else {
                            return response()->json([
                                'money_large_moneybill' => 1,
                                'message' => __('Tiền tài khoản lớn hơn tiền thanh toán')
                            ]);
                        }
                    } elseif ($methodCode == 'VNPAY') {
                        $mReceiptDetail->add($dataReceiptDetail);
                        // update receipt_id of receipt online
                        $mReceiptOnline->updateReceiptOnlineByTypeAndOrderId([
                            'receipt_id' => $receiptId,
                            'status' => 'success'
                        ], 'order', $orderId, $methodCode);
                    } elseif ($methodCode == 'TRANSFER') {
                        $mReceiptDetail->add($dataReceiptDetail);
                        // get receipt_online of method/order
                        $dataReceiptOnline = $mReceiptOnline->getReceiptOnlineByTypeAndOrderId('order', $orderId, $methodCode);
                        if ($dataReceiptOnline != null) {
                            // update status, receipt_id of receipt_online
                            $mReceiptOnline->updateReceiptOnlineByTypeAndOrderId([
                                'amount_paid' => $money,
                                'receipt_id' => $receiptId,
                                'status' => 'success'
                            ], 'order', $orderId, $methodCode);
                        } else {
                            // create status, receipt_id of receipt_online
                            $dataReceiptOnline = [
                                'receipt_id' => $receiptId,
                                'object_type' => 'order',
                                'object_id' => $orderId,
                                'object_code' => $orderCode,
                                'payment_method_code' => $methodCode,
                                'amount_paid' => $money,
                                'payment_time' => Carbon::now(),
                                'status' => 'success',
                                'performer_name' => $staffInfo['name'],
                                'performer_phone' => $staffInfo['phone1'],
                                'type' => $itemMethod['payment_method_type'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                            $mReceiptOnline->createReceiptOnline($dataReceiptOnline);
                        }
                    } else {
                        $mReceiptDetail->add($dataReceiptDetail);
                    }
                }
            }
            // Hoàn tất deal
            $mDeal = new CustomerDealTable();
//            $mDeal->editByCode($input['deal_code'], ['journey_code' => self::JOURNEY_DEAL_END]);
            // xuat kho
            $this->processInventory($orderId, $input['table_add']);
            // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
            if (isset($input['arrAppointment'])) {
                $arrAppointment = $input['arrAppointment'];
                if ($arrAppointment['checked'] == 1) {
                    // Thêm lịch hẹn
                    $repoOrderApp = app()->get(OrderAppRepo::class);
                    $result = $repoOrderApp->_addQuickAppointment($arrAppointment, $input['customer_id']);
                    if ($result['error'] == false) {
                        $result['error'] = true; // ngược lại với js bên order
                        return response()->json($result);
                    } else {
                        $result['error'] = false; // ngược lại với js bên order
                    }
                }
            }
            // END UPDATE

            // bao hanh dien tu
            $customer = $mCustomer->getItem($input['customer_id']);
            $this->addWarrantyCard($customer['customer_code'], $orderCode, $input['table_add']);

            $mOrder = app()->get(OrderRepositoryInterface::class);
            //Lưu log dự kiến nhắc sử dụng lại
            $mOrder->insertRemindUse($orderId, $input['customer_id'], $arrRemindUse);

            DB::commit();

            if ($input['customer_id'] != null && $input['customer_id'] != 1) {
                //Insert email log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_EMAIL_CUSTOMER,
                    'event' => 'is_event',
                    'key' => 'order_success',
                    'object_id' => $orderId,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Insert sms log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_SMS_CUSTOMER,
                    'key' => 'order_success',
                    'object_id' => $orderId,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Insert email log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_EMAIL_CUSTOMER,
                    'event' => 'is_event',
                    'key' => 'paysuccess',
                    'object_id' => $orderId,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Insert sms log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_SMS_CUSTOMER,
                    'key' => 'paysuccess',
                    'object_id' => $orderId,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Lưu log ZNS
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'order_thanks',
                    'customer_id' => $input['customer_id'],
                    'object_id' => $orderId,
                    'tenant_id' => session()->get('idTenant')
                ]);
                if ($isNotifyMinAccount == 1) {
                    //Gửi thông báo tiền trong tài khoản sắp hết
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_NOTIFY_CUSTOMER,
                        'key' => 'money_account_min',
                        'customer_id' => $input['customer_id'],
                        'object_id' => $orderId,
                        'tenant_id' => session()->get('idTenant')
                    ]);
                }
            }

            return [
                'error' => false,
                'message' => __('Thanh toán đơn hàng thành công'),
                'print_card' => $dataInsertAndCalculator['dataPrint'],
                'order_id' => $orderId,
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'error' => true,
                'message' => __('Thanh toán đơn hàng thất bại'),
                '_message' => $e->getMessage() . ' ' . $e->getLine()
            ];
        }
    }

    /**
     * thêm chi tiết đơn hàng và tính hoa hồng (nhân viên, giới thiệu, deal)
     *
     * @param $arrObject
     * @param $input
     * @param $orderId
     * @param $orderCode
     * @param $branchId
     * @throws \MyCore\Api\ApiException
     */
    private function insertOrderDetailAndCalculatorCommission($arrObject, $input, $orderId, $orderCode, $branchId)
    {
        $mStaff = new StaffsTable();
        $mOrderDetail = new OrderDetailTable();
        $mService = new ServiceTable();
        $mServiceCard = new ServiceCardTable();
        $mProduct = new ProductChildTable();
        $mCustomerServiceCard = new CustomerServiceCardTable();
        $mVoucher = new VoucherTable();
        $mCustomer = new CustomerTable();
        $mCustomerBranchMoney = new CustomerBranchMoneyTable();
        $mServiceCardList = new ServiceCardListTable();
        $mBranchMoneyLog = app()->get(CustomerBranchMoneyLogTable::class);

        $arrObjectBuy = [];
        $listCardPrint = [];
        $arrRemindUse = [];

        foreach ($arrObject as $item) {
            // lấy tỉ lệ hoa hồng nhân viên
//            $staffInfo = $mStaff->getCommissionStaff($item['staff_id']);
//            $staffCommission = floatval($staffInfo != null ? $staffInfo['commission_rate'] : 0);
            if (in_array($item['object_type'], ['product', 'service', 'service_card'])) {
                $arrObjectBuy [] = [
                    'object_type' => $item['object_type'],
                    'object_code' => $item['object_code'],
                    'object_id' => $item['object_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'customer_id' => $input['customer_id'],
                    'order_source' => 1,
                    'order_id' => $orderId,
                    'order_code' => $orderCode
                ];
                //Lấy array nhắc sử dụng lại
                $arrRemindUse [] = [
                    'object_type' => $item['object_type'],
                    'object_id' => $item['object_id'],
                    'object_code' => $item['object_code'],
                    'object_name' => $item['object_name']
                ];
            }
            $dataOrderDetail = [
                'order_id' => $orderId,
                'object_id' => $item['object_id'],
                'object_name' => $item['object_name'],
                'object_type' => $item['object_type'],
                'object_code' => $item['object_code'],
                'price' => str_replace(',', '', $item['price']),
                'quantity' => $item['quantity'],
                'discount' => str_replace(',', '', $item['discount']),
                'voucher_code' => $item['voucher_code'],
                'amount' => str_replace(',', '', $item['amount']),
                'created_by' => Auth::id(),
                'staff_id' => isset($item['staff_id']) != '' ? implode(',', $item['staff_id']) : null,
                'refer_id' => $input['refer_id']
            ];
            $orderDetailId = $mOrderDetail->add($dataOrderDetail);
            $item['staff_id'] = isset($item['staff_id']) != '' ? $item['staff_id'] : [];
            switch ($item['object_type']) {
                case 'service':
                    $objectInfo = $mService->getItem($item['object_id']);
                    $this->calculatorCommissionWithArrStaff($objectInfo, $orderDetailId, $input['deal_id'], $input['refer_id'], $item['staff_id'], $branchId, $item['quantity']);
                    break;
                case 'product':
                    $objectInfo = $mProduct->getItem($item['object_id']);
                    $this->calculatorCommissionWithArrStaff($objectInfo, $orderDetailId, $input['deal_id'], $input['refer_id'], $item['staff_id'], $branchId, $item['quantity']);
                    break;
                case 'service_card':
                    $objectInfo = $mServiceCard->getItem($item['object_id']);
                    $resCalculator = $this->calculatorCommissionWithArrStaff($objectInfo, $orderDetailId, $input['deal_id'], $input['refer_id'], $item['staff_id'], $branchId, $item['quantity']);
                    $arrayTemp = [];
                    for ($i = 0; $i < $item['quantity']; $i++) {
                        //Generate mã thẻ liệu trình
                        $code = $this->generateCardListCode();
                        while (array_search($code, $arrayTemp)) {
                            $code = $this->generateCardListCode();
                        }
                        $dataCardList = [
                            'service_card_id' => $item['object_id'],
                            'order_code' => $orderCode,
                            'branch_id' => $branchId,
                            'price' => $item['price'],
                            'code' => $code,
                            'refer_commission' => $resCalculator['referMoney'],
                            'staff_commission' => $resCalculator['staffMoney'],
                            'created_by' => Auth::id()
                        ];
                        if ($input['customer_id'] != 1 && $input['check_active'] == 1) {
                            $dataCardList['is_actived'] = 1;
                            $dataCardList['actived_at'] = date("Y-m-d H:i");
                            $dataCusCard = [
                                'customer_id' => $input['customer_id'],
                                'card_code' => $code,
                                'service_card_id' => $item['object_id'],
                                'number_using' => $objectInfo['number_using'],
                                'count_using' => $objectInfo['service_card_type'] == 'money' ? 1 : 0,
                                'money' => $objectInfo['money'],
                                'actived_date' => date("Y-m-d"),
                                'is_actived' => 1,
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                                'branch_id' => $branchId
                            ];
                            if ($objectInfo['date_using'] != 0) {
                                $dataCusCard['expired_date'] = strftime("%Y-%m-%d", strtotime(date("Y-m-d", strtotime(date("Y-m-d") . '+ ' . $objectInfo['date_using'] . 'days'))));
                            }
                            if ($objectInfo['service_card_type'] == 'money') {
                                //Lấy thông tin KH
                                $customer = $mCustomer->getItem($input['customer_id']);
                                //Cập nhật tài khoản KH
                                $mCustomer->edit([
                                    'account_money' => $customer['account_money'] + $objectInfo['money']
                                ], $input['customer_id']);
                                //Lưu log + tiền
                                $mBranchMoneyLog->add([
                                    "customer_id" => $input['customer_id'],
                                    "branch_id" => Auth()->user()->branch_id,
                                    "source" => "member_money",
                                    "type" => 'plus',
                                    "money" => $objectInfo['money'],
                                    "screen" => 'active_card',
                                    "screen_object_code" => $code
                                ]);
                            }
                            //Thêm vào customer service card thẻ đã active
                            $mCustomerServiceCard->add($dataCusCard);
                            //Thêm vào service card list thẻ đã active
                            $mServiceCardList->add($dataCardList);
                            array_push($listCardPrint, $code);
                            $arrayTemp[] = $code;
                        }
                    }
                    break;
                case 'member_card':
                    // Trừ số lần sử dụng thẻ liệu trình
                    $cardInfo = $mCustomerServiceCard->getItem($item['object_id']);
                    $mCustomerServiceCard->editByCode([
                        'count_using' => $cardInfo['count_using'] + $item['quantity']
                    ], $item['object_code']);
                    // lấy thông tin hoa hồng khi sử dụng thẻ liệu trình
                    $objectInfo = $mCustomerServiceCard->getCommissionMemberCard($item['object_code']);
                    $this->calculatorCommissionWithArrStaff($objectInfo, $orderDetailId, $input['deal_id'], $input['refer_id'], $item['staff_id'], $branchId, $item['quantity']);
                    // send mail, sms
                    $mSmsLog = app()->get(SmsLogRepositoryInterface::class);
                    CheckMailJob::dispatch('is_event', 'service_card_over_number_used', $item['object_id']);
                    $mSmsLog->getList('service_card_over_number_used', $item['object_id']);
                    //Lưu log ZNS
                    SaveLogZns::dispatch('service_card_over_number_used', $input['customer_id'], $item['object_id']);
                    //Send notification
                    $mNotify = new SendNotificationApi();
                    $mNotify->sendNotification([
                        'key' => 'service_card_over_number_used',
                        'customer_id' => $input['customer_id'],
                        'object_id' => $item['object_id']
                    ]);
                    break;
            }
            // cập nhật voucher code
            if ($item['voucher_code'] != null) {
                $voucherInfo = $mVoucher->getCodeItem($item['voucher_code']);
                $mVoucher->editVoucherOrder([
                    'total_use' => $voucherInfo['total_use'] + 1
                ], $item['voucher_code']);
            }
        }

        // data print card
        $dataPrint = [];
        if (count($listCardPrint) > 0) {
            foreach ($listCardPrint as $item) {
                $getCusCard = $mServiceCardList->searchCard($item);
                $getServiceCard = $mServiceCard->getServiceCardInfo($getCusCard['service_card_id']);
                $dataPrint [] = [
                    'customer_id' => $input['customer_id'],
                    'type' => $getServiceCard['service_card_type'],
                    'card_name' => $getCusCard['card_name'],
                    'card_code' => $getCusCard['code'],
                    'number_using' => $getServiceCard['number_using'],
                    'date_using' => $getServiceCard['date_using'],
                    'money' => $getServiceCard['money'],
                    'service_card_id' => $getServiceCard['service_card_id'],
                ];
            }
        }
        return [
            'arrObjectBuy' => $arrObjectBuy,
            'dataPrint' => $dataPrint,
            'arrRemindUse' => $arrRemindUse
        ];
    }

    /**
     * Tính tiền hoa hồng nhân viên, người giới thiệu, deal
     *
     * @param $objectInfo
     * @param $orderDetailId
     * @param $dealId
     * @param $referId
     * @param $staffId
     * @param $branchId
     */
    private function calculatorCommission($objectInfo, $orderDetailId, $dealId, $referId, $staffId, $branchId)
    {
        $mOrderCommission = new OrderCommissionTable();
        $mCustomerBranchMoney = new CustomerBranchMoneyTable();
        $referMoney = $staffMoney = $dealMoney = 0;
        if ($objectInfo != null) {
            $dataOrderCommission = [
                'order_detail_id' => $orderDetailId,
                'created_by' => Auth::id(),
            ];
            // refer commission
            if (isset($referId) && $referId != null) { // nếu có người giới thiệu
                if ($objectInfo['refer_commission_value'] != null && $objectInfo['refer_commission_value'] > 0) {
                    $dataOrderCommission['refer_id'] = $referId;
                    if ($objectInfo['type_refer_commission'] == 'money') {
                        $dataOrderCommission['refer_money'] = $objectInfo['refer_commission_value'];
                    } elseif ($objectInfo['type_refer_commission'] == 'percent') {
                        $dataOrderCommission['refer_money'] = round(($objectInfo['price'] * $objectInfo['refer_commission_value']) / 100, 2);
                    }
                    $referMoney = $dataOrderCommission['refer_money'];
                    $mOrderCommission->add($dataOrderCommission);
                    // Nếu đã có tiền trong chi nhánh thì + thêm, chưa thì thêm mới
                    $customerBranchMoneyInfo = $mCustomerBranchMoney->getPriceBranch($referId, $branchId);
                    if ($customerBranchMoneyInfo != null) {
                        $mCustomerBranchMoney->edit([
                            'commission_money' => $customerBranchMoneyInfo['commission_money'] + $dataOrderCommission['refer_money'],
                            'updated_by' => Auth::id()
                        ], $referId, $branchId);
                    } else {
                        $mCustomerBranchMoney->add([
                            'customer_id' => $referId,
                            'branch_id' => $branchId,
                            'commission_money' => $dataOrderCommission['refer_money'],
                            'created_by' => Auth::id()
                        ]);
                    }
                }
            }
            // staff commission
            if (isset($staffId) && $staffId != null) { // nếu có nhân viên
                if ($objectInfo['staff_commission_value'] != null && $objectInfo['staff_commission_value'] > 0) {
                    $dataOrderCommission['staff_id'] = $staffId;
                    if ($objectInfo['type_staff_commission'] == 'money') {
                        $dataOrderCommission['staff_money'] = $objectInfo['staff_commission_value'];
                    } elseif ($objectInfo['type_staff_commission'] == 'percent') {
                        $dataOrderCommission['staff_money'] = round(($objectInfo['price'] * $objectInfo['staff_commission_value']) / 100, 2);
                    }
                    $staffMoney = $dataOrderCommission['staff_money'];
                    $mOrderCommission->add($dataOrderCommission);
                }
            }
            // deal commission
            if ($objectInfo['deal_commission_value'] != null && $objectInfo['deal_commission_value'] > 0) {
                $dataOrderCommission['deal_id'] = $dealId;
                if ($objectInfo['type_deal_commission'] == 'money') {
                    $dataOrderCommission['deal_money'] = $objectInfo['deal_commission_value'];
                } elseif ($objectInfo['type_deal_commission'] == 'percent') {
                    $dataOrderCommission['deal_money'] = round(($objectInfo['price'] * $objectInfo['deal_commission_value']) / 100, 2);
                }
                $dealMoney = $dataOrderCommission['deal_money'];
                $mOrderCommission->add($dataOrderCommission);
            }
        }
        return [
            'referMoney' => $referMoney,
            'staffMoney' => $staffMoney,
            'dealMoney' => $dealMoney,
        ];
    }

    private function calculatorCommissionWithArrStaff($objectInfo, $orderDetailId, $dealId, $referId, $arrStaffId, $branchId, $quantity = 0)
    {
        $decimal = isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0;
        $mStaff = new \Modules\Admin\Models\StaffsTable();
        $mOrderCommission = new OrderCommissionTable();
        $mCustomerBranchMoney = new CustomerBranchMoneyTable();
        $referMoney = $staffMoney = $dealMoney = 0;
        if ($objectInfo != null) {
            // refer commission
            if (isset($referId) && $referId != null) { // nếu có người giới thiệu

                $dataOrderCommission = [
                    'order_detail_id' => $orderDetailId,
                    'created_by' => Auth::id(),
                ];
                if ($objectInfo['refer_commission_value'] != null && $objectInfo['refer_commission_value'] > 0) {
                    $dataOrderCommission['refer_id'] = $referId;
                    if ($objectInfo['type_refer_commission'] == 'money') {
                        $dataOrderCommission['refer_money'] = $objectInfo['refer_commission_value'] * (int)$quantity;
                    } elseif ($objectInfo['type_refer_commission'] == 'percent') {
                        $dataOrderCommission['refer_money'] = round((($objectInfo['price'] * $objectInfo['refer_commission_value']) / 100) * (int)$quantity, 2);
                    }
                    $referMoney = $dataOrderCommission['refer_money'];
                    $mOrderCommission->add($dataOrderCommission);
                    // Nếu đã có tiền trong chi nhánh thì + thêm, chưa thì thêm mới
                    $customerBranchMoneyInfo = $mCustomerBranchMoney->getPriceBranch($referId, $branchId);
                    if ($customerBranchMoneyInfo != null) {
                        $mCustomerBranchMoney->edit([
                            'commission_money' => $customerBranchMoneyInfo['commission_money'] + $dataOrderCommission['refer_money'],
                            'updated_by' => Auth::id()
                        ], $referId, $branchId);
                    } else {
                        $mCustomerBranchMoney->add([
                            'customer_id' => $referId,
                            'branch_id' => $branchId,
                            'commission_money' => $dataOrderCommission['refer_money'],
                            'created_by' => Auth::id()
                        ]);
                    }
                }
            }
            // staff commission
            if (isset($arrStaffId) && count($arrStaffId) > 0) { // nếu có nhân viên

                $dataOrderCommission = [
                    'order_detail_id' => $orderDetailId,
                    'created_by' => Auth::id(),
                ];
                if ($objectInfo['staff_commission_value'] != null && $objectInfo['staff_commission_value'] > 0) {
                    foreach ($arrStaffId as $staffId) {
                        $getStaff = $mStaff->getCommissionStaff($staffId);
                        $staffCommission = floatval(isset($getStaff) ? $getStaff['commission_rate'] : 0);
                        $dataOrderCommission['staff_id'] = $staffId;
                        $dataOrderCommission['staff_commission_rate'] = $staffCommission;
                        if ($objectInfo['type_staff_commission'] == 'money') {
                            $staff_money = round($objectInfo['staff_commission_value'] * $staffCommission * (int)$quantity, $decimal, PHP_ROUND_HALF_DOWN);
                            $dataOrderCommission['staff_money'] = $staff_money;
                        } elseif ($objectInfo['type_staff_commission'] == 'percent') {
                            $staff_money = round(($objectInfo['price'] * $objectInfo['staff_commission_value'] / 100) * (int)$quantity * $staffCommission, $decimal, PHP_ROUND_HALF_DOWN);
                            $dataOrderCommission['staff_money'] = $staff_money;
                        }

                        $staffMoney = $dataOrderCommission['staff_money'];
                        $mOrderCommission->add($dataOrderCommission);
                    }
                }
            }
            // deal commission
            if ($objectInfo['deal_commission_value'] != null && $objectInfo['deal_commission_value'] > 0) {

                $dataOrderCommission = [
                    'order_detail_id' => $orderDetailId,
                    'created_by' => Auth::id(),
                ];
                $dataOrderCommission['deal_id'] = $dealId;
                if ($objectInfo['type_deal_commission'] == 'money') {
                    $dataOrderCommission['deal_money'] = $objectInfo['deal_commission_value'] * (int)$quantity;
                } elseif ($objectInfo['type_deal_commission'] == 'percent') {
                    $dataOrderCommission['deal_money'] = round((($objectInfo['price'] * $objectInfo['deal_commission_value']) / 100) * (int)$quantity, 2);
                }
                $dealMoney = $dataOrderCommission['deal_money'];
                $mOrderCommission->add($dataOrderCommission);
            }
        }
        return [
            'referMoney' => $referMoney,
            'staffMoney' => $staffMoney,
            'dealMoney' => $dealMoney,
        ];
    }

    /**
     * Xử lý tồn kho
     *
     * @param $orderId
     * @param $arrayObject
     * @return \Illuminate\Http\JsonResponse
     */
    private function processInventory($orderId, $arrayObject)
    {
        $mWarehouse = new WarehouseTable();
        $mInventoryOutput = new InventoryOutputTable();
        $mServiceMaterial = new ServiceMaterialTable();
        $mProductBranchPrice = new ProductBranchPriceTable();
        $mInventoryOutputDetail = new InventoryOutputDetailTable();
        $mProductChild = new ProductChildTable();
        $mProductInventory = new ProductInventoryTable();
        $warehouseId = 0;
        $listServiceMaterials = [];
        $checkWarehouse = $mWarehouse->getWarehouseByBranch(Auth::user()->branch_id);
        foreach ($checkWarehouse as $item) {
            if ($item['is_retail'] == 1) {
                $warehouseId = $item['warehouse_id'];
            }
        }
        $dataInventoryOutput = [
            'warehouse_id' => $warehouseId,
            'po_code' => 'XK',
            'created_by' => Auth::user()->staff_id,
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 'success',
            'note' => '',
            'type' => 'retail',
            'object_id' => $orderId
        ];

        $inventoryOutputId = $mInventoryOutput->add($dataInventoryOutput);
        $codeId = $inventoryOutputId;
        if ($inventoryOutputId < 10) {
            $codeId = '0' . $codeId;
        }
        $mInventoryOutput->edit(['po_code' => $this->codeDMY('XK', $codeId)], $inventoryOutputId);
        // Lấy thông tin bán âm
        $mConfig = new ConfigTable();
        $configSellMinus = $mConfig->getInfoByKey('sell_minus');
        $sellMinus = 1;
        $configSellMinus != null ? $sellMinus = $configSellMinus['value'] : $sellMinus = 1;
        foreach ($arrayObject as $item) {
            if ($item['object_type'] == 'service') {
                //Lấy sản phẩm đi kèm dịch vụ.
                $serviceMaterial = $mServiceMaterial->getItem($item['object_id']);
                foreach ($serviceMaterial as $value) {
                    $currentPrice = $mProductBranchPrice->getProductBranchPriceByCode(Auth::user()->branch_id, $value['material_code'])['new_price'];
                    $listServiceMaterials [] = [
                        'product_code' => $value['material_code'],
                        'quantity' => $item['quantity'] * $value['quantity'],
                        'current_price' => $currentPrice,
                        'total' => $value['quantity'] * $currentPrice * $item['quantity']
                    ];
                }
            }

            if ($item['object_type'] == 'product') {
                $dataInventoryOutputDetail = [
                    'inventory_output_id' => $inventoryOutputId,
                    'product_code' => $item['object_code'],
                    'quantity' => $item['quantity'],
                    'current_price' => $item['price'],
                    'total' => $item['amount'],
                ];
                $idIOD = $mInventoryOutputDetail->add($dataInventoryOutputDetail);
                //Trừ tồn kho.
                //Lấy id của product child bằng code. is deleted=0.
                $productId = $mProductChild->getProductChildByCode($item['object_code']);
                $checkProductInventory = $mProductInventory->checkProductInventory($item['object_code'], $warehouseId);
                $quantity = $checkProductInventory != null ? $checkProductInventory['quantity'] - $item['quantity'] : 0;
                // Nếu k cho bán âm thì kiểm tra số lượng trong kho có đủ cho đơn hàng không
                if ($sellMinus == 0 && $quantity < 0) {
                    // Lấy tên sản phẩm
                    DB::rollback();
                    return response()->json([
                        'error' => false,
                        'message' => __("Trong kho không đủ sản phẩm ") . $productId['product_child_name']
                    ]);
                }
                if ($productId != null) {
                    if ($checkProductInventory != null) {
                        $dataEditProductInventory = [
                            'product_id' => $productId['product_child_id'],
                            'product_code' => $item['object_code'],
                            'warehouse_id' => $warehouseId,
                            'export' => $item['quantity'] + $checkProductInventory['export'],
                            'quantity' => $quantity,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => Auth::id(),
                        ];
                        $mProductInventory->edit($dataEditProductInventory, $checkProductInventory['product_inventory_id']);
                    } else {
                        $dataEditProductInventoryInsert = [
                            'product_id' => $productId,
                            'product_code' => $item['object_code'],
                            'warehouse_id' => $warehouseId,
                            'import' => 0,
                            'export' => $item['quantity'],
                            'quantity' => $quantity,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ];
                        $mProductInventory->add($dataEditProductInventoryInsert);
                    }
                }
            }
        }
        if (count($listServiceMaterials) > 0) {
            foreach ($listServiceMaterials as $item) {
                $dataInventoryOutputDetail = [
                    'inventory_output_id' => $inventoryOutputId,
                    'product_code' => $item['product_code'],
                    'quantity' => $item['quantity'],
                    'current_price' => $item['current_price'],
                    'total' => $item['total'],
                ];
                $idIOD = $mInventoryOutputDetail->add($dataInventoryOutputDetail);

                //Trừ tồn kho.
                $productId = $mProductChild->getProductChildByCode($item['product_code']);
                $checkProductInventory = $mProductInventory->checkProductInventory($item['product_code'], $warehouseId);
                $quantity = $checkProductInventory != null ? $checkProductInventory['quantity'] - $item['quantity'] : 0;;
                if ($productId != null) {
                    if ($checkProductInventory != null) {
                        $dataEditProductInventory = [
                            'product_id' => $productId['product_child_id'],
                            'product_code' => $item['product_code'],
                            'warehouse_id' => $warehouseId,
                            'export' => $item['quantity'] + $checkProductInventory['export'],
                            'quantity' => $quantity,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => Auth::id(),
                        ];
                        $mProductInventory->edit($dataEditProductInventory, $checkProductInventory['product_inventory_id']);
                    } else {
                        $dataEditProductInventoryInsert = [
                            'product_id' => $productId,
                            'product_code' => $item['product_code'],
                            'warehouse_id' => $warehouseId,
                            'import' => 0,
                            'export' => $item['quantity'],
                            'quantity' => $quantity,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ];
                        $mProductInventory->add($dataEditProductInventoryInsert);
                    }
                }
            }
        }
    }

    /**
     * Thêm thẻ bảo hành điện tử
     *
     * @param $customerCode
     * @param $orderCode
     * @param $arrayObject
     */
    private function addWarrantyCard($customerCode, $orderCode, $arrayObject)
    {
        $mWarrantyDetail = new WarrantyPackageDetailTable();
        $mWarranty = new WarrantyPackageTable();
        $mWarrantyCard = new WarrantyCardTable();
        if ($arrayObject != null) {
            foreach ($arrayObject as $item) {
                // value item
                $objectId = $item['object_id'];
                $objectType = $item['object_type'];
                $objectCode = $item['object_code'];
                $objectPrice = $item['price'];
                $objectQuantity = $item['quantity'];
                if ($objectType == 'product' || $objectType == 'service' || $objectType == 'service_card') {
                    // get object code -> get packed_code -> get info warranty package
                    $warrantyDetail = $mWarrantyDetail->getDetailByObjectCode($objectCode, $objectType);
                    if ($warrantyDetail != null) {
                        $warranty = $mWarranty->getInfoByCode($warrantyDetail['warranty_packed_code']);
                        $dataInsert = [
                            'customer_code' => $customerCode,
                            'warranty_packed_code' => $warrantyDetail['warranty_packed_code'],
                            'quota' => $warranty['quota'],
                            'warranty_percent' => $warranty['percent'],
                            'warranty_value' => $warranty['required_price'],
                            'status' => 'new',
                            'object_type' => $objectType,
                            'object_type_id' => $objectId,
                            'object_code' => $objectCode,
                            'object_price' => $objectPrice,
                            'created_by' => Auth::id(),
                            'order_code' => $orderCode,
                            'description' => $warranty['detail_description']
                        ];
                        if ($objectQuantity > 1) {
                            for ($i = 0; $i < $objectQuantity; $i++) {
                                $warrantyCardId = $mWarrantyCard->add($dataInsert);
                                // card code
                                $warrantyCardCode = 'WRC_' . date('dmY') . sprintf("%02d", $warrantyCardId);
                                $mWarrantyCard->edit(['warranty_card_code' => $warrantyCardCode], $warrantyCardId);
                            }
                        } else {
                            $warrantyCardId = $mWarrantyCard->add($dataInsert);
                            // card code
                            $warrantyCardCode = 'WRC_' . date('dmY') . sprintf("%02d", $warrantyCardId);
                            $mWarrantyCard->edit(['warranty_card_code' => $warrantyCardCode], $warrantyCardId);
                        }
                    }
                }
            }
        }
    }

    private function generateCardListCode()
    {
        $text = "";
        $string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for ($i = 0; $i < 3; $i++) {
            $text .= $string[rand(0, (strlen($string) - 1))];

        }
        $str_uniq = uniqid($text);
        return strtoupper($str_uniq);
    }

    //Code theo chữ cái đầu và stt tự tăng.
    public function codeDMY($string, $stt)
    {
        $time = date("dmY");
        return $string . '_' . $time . $stt;
    }

    /**
     * data popup tạo KHTN
     *
     * @param $input
     * @return array|mixed
     */
    public function dataModalAddCustomerLead($input)
    {
        $mPipeline = new PipelineTable();
        $mCustomerSource = new CustomerSourceTable();

        $optionPipeline = $mPipeline->getOption('CUSTOMER');
        $optionCustomerSource = $mCustomerSource->getOption();

        $html = \View::make('customer-lead::customer-deal.popup-create-lead', [
            "optionPipeline" => $optionPipeline,
            "optionCustomerSource" => $optionCustomerSource,
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Data popup tạo KH
     *
     * @param $input
     * @return array|mixed
     */
    public function dataModalAddCustomer($input)
    {
        $mProvince = new \Modules\CustomerLead\Models\ProvinceTable();
        $optionProvince = $mProvince->getOptionProvince();
        $item = $this->customerDeal->getInfoCustomerDeal($input['deal_id']);
        $listData = array();
        foreach ($optionProvince as $value) {
            $listData[$value['provinceid']] = $value['name'];
        }
        $html = \View::make('customer-lead::customer-deal.popup-create-customer', [
            "optionProvince" => $listData,
            "item" => $item,
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Lưu KHTN
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function storeCustomerLead($input)
    {

        $mCustomerLead = new CustomerLeadTable();
        DB::beginTransaction();
        try {
            $data = [
                "full_name" => $input["full_name"],
                "phone" => $input["phone"],
                "pipeline_code" => $input["pipeline_code"],
                "tax_code" => $input["tax_code"],
                "representative" => $input["representative"],
                "journey_code" => $input["journey_code"],
                "customer_type" => $input["customer_type"],
                "ch_customer_id" => $input["ch_customer_id"],
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id(),
                "customer_source" => $input['customer_source'],
                "assign_by" => Auth()->id(),
            ];

            //Insert customer lead
            $customerLeadId = $mCustomerLead->add($data);
            //Update customer_lead_code
            $leadCode = "LEAD_" . date("dmY") . sprintf("%02d", $customerLeadId);
            $mCustomerLead->edit([
                "customer_lead_code" => $leadCode
            ], $customerLeadId);
            $infoLead = $mCustomerLead->getCustomerLeadByLeadCode($leadCode);
            DB::commit();
            return response()->json([
                "error" => false,
                "message" => __("Tạo thành công"),
                "data" => $infoLead
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Tạo thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine(),
                "data" => null
            ]);
        }
    }

    /**
     * Lưu tag mới select
     *
     * @param $input
     * @return array|mixed
     */
    public function storeQuicklyTag($input)
    {
        try {
            $mTag = new TagTable();
            $data = [
                'name' => $input['tag_name'],
                'keyword' => str_slug($input['tag_name'])
            ];
            $tagId = $mTag->add($data);


            return [
                'error' => false,
                'message' => __('Thêm mới thành công'),
                'tag_id' => $tagId
            ];
        } catch (\Exception $ex) {
            return [
                'error' => true,
                'message' => __('Thêm mới thất bại')
            ];
        }
    }


    /**
     * data popup CSKH
     *
     * @param $input
     * @return array|mixed
     */
    public function popupCustomerCare($input)
    {
        $mDealCare = new DealCareTable();
        $mDeal = new CustomerDealTable();
        $mManageWork = new ManagerWorkTable();
        $mManageTypeWork = new TypeWorkTable();
        $mStaff = new StaffsTable();

        $customerDealInterface = app()->get(CustomerLeadRepoInterface::class);

        //Lấy lịch sử chăm sóc KH
        $getCare = collect($mDealCare->getDealCare($input['deal_id'])->toArray());

        $dataCare = $getCare->groupBy('created_group');

//        if (count($dataCare) > 0) {
//            foreach ($dataCare as $k => $v) {
//                $dataCare[$k] = $v->sortBy('created_at');
//            }
//        }

        $detailDeal = $mDeal->getItem($input['deal_id']);

        $detailWork = null;
        $is_booking = 0;
        $listStatus = $customerDealInterface->getListStatusWork();
        if (isset($input['manage_work_id'])) {
            $detailWork = $mManageWork->getDetail($input['manage_work_id']);
            $is_booking = $detailWork['is_booking'];
            $listStatus = $customerDealInterface->getListStatusWork($input['manage_work_id']);
        }

        $listTypeWork = $mManageTypeWork->getListTypeWork(1);
        $listStaff = $mStaff->getListStaffByFilter([]);

        $data = [
//            'customer_id' => $detailDeal['customer_id_join'],
            'customer_id' => $detailDeal['deal_id'],
            'manage_work_customer_type' => 'deal',
            'type_search' => 'support'
        ];

        $listWork = $mManageWork->getListWorkByCustomer($data);

        $html = \View::make('customer-lead::customer-deal.popup-deal-care', [
            'deal_id' => $input['deal_id'],
            'dataCare' => $dataCare,
            'listTypeWork' => $listTypeWork,
            'listStaff' => $listStaff,
            'detailWork' => $detailWork,
            'listStatus' => $listStatus,
            'listWork' => $listWork,
            'detailDeal' => $detailDeal
        ])->render();

        return [
            'html' => $html
        ];
    }


    /**
     * Lưu thông tin CSKH
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function customerCare($input)
    {
        try {
            $mDealCare = new DealCareTable();
            $mManageTypeWork = new TypeWorkTable();

            $customerLeadRepoInterface = app()->get(CustomerLeadRepoInterface::class);

            $adddWork = $customerLeadRepoInterface->customerCare($input);

            $typeDetail = $mManageTypeWork->getItem($input['manage_type_work_id']);

            //Insert customer care
            $mDealCare->add([
                "deal_id" => $input['customer_deal_id'],
                "care_type" => $typeDetail['manage_type_work_key'],
                "content" => $input['content'],
                "created_by" => Auth()->id(),
                "object_id" => $input['history_id']
            ]);

            return $adddWork;
//            return [
//                'error' => false,
//                'message' => __('Chăm sóc khách hàng thành công')
//            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Chăm sóc khách hàng thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Show modal call (on call)
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function showModalCall($input)
    {
        //Lấy thông tin deal
        $item = $this->customerDeal->getItem($input['deal_id']);

        $html = \View::make('customer-lead::customer-deal.popup-call', [
            "item" => $item,
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Gọi (on call)
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function call($input)
    {
        try {
            $mExtension = app()->get(ExtensionTable::class);
            $mHistory = app()->get(HistoryTable::class);
            $mDealCare = new DealCareTable();

            //Kiểm tra định dạng sđt
            $checkFormatPhone = $this->checkPhoneNumberVN($input['phone'], $resPhone);

            if ($checkFormatPhone == false) {
                return response()->json([
                    'error' => true,
                    'message' => __('Số điện thoại không hợp lệ'),
                ]);
            }

            //Lấy thông tin extension
            $infoExtension = $mExtension->getInfoByStaff(Auth()->id());

            if (empty($infoExtension)) {
                return response()->json([
                    'error' => true,
                    'message' => __('Không tìm thấy extension'),
                ]);
            }

            //Lấy thông tin KH tiềm năng
            $item = $this->customerDeal->getItem($input['deal_id']);

            //Lưu history
            $idHistory = $mHistory->add([
                'object_id_call' => Auth()->id(),
                'extension_number' => $infoExtension['extension_number'],
                'source_code' => 'deal',
                'object_id' => $item['deal_id'],
                'object_code' => $item['deal_code'],
                'object_name' => $item['deal_name'],
                'object_phone' => $input['phone'],
                'history_type' => 'out', //Cuộc gọi đi
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            //Call api gọi (on call)
            $call = $this->_apiCall($infoExtension['extension_number'], $input['phone']);

            if ($call->ErrorCode == 1) {
                //Cập nhật lưu lỗi lịch sử
                $mHistory->edit([
                    'error_text' => $call->ErrorDescription
                ], $idHistory);

                return response()->json([
                    'error' => true,
                    'message' => $call->ErrorDescription,
                ]);
            }

            //Call thành công thì update history lại
            $mHistory->edit([
                'uid' => $call->Data->uid,
            ], $idHistory);

            //Lấy lịch sử chăm sóc KH
            $getCare = collect($mDealCare->getDealCare($input['deal_id'])->toArray());

            $dataCare = $getCare->groupBy('created_group');

            $html = \View::make('customer-lead::customer-deal.popup-deal-care', [
                'deal_id' => $input['deal_id'],
                'dataCare' => $dataCare,
                'careType' => 'call',
                'historyId' => $idHistory
            ])->render();

            return response()->json([
                'error' => false,
                'message' => __('Gọi thành công'),
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Gọi thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    /**
     * Check sdt vietnam
     *
     * @param $phone
     * @param $res
     * @return bool
     */
    protected function checkPhoneNumberVN($phone, &$res)
    {
        $regex = "/^(0|\+84)(\s|\.)?((3[2-9])|(5[689])|(7[06-9])|(8[1-689])|(9[0-46-9]))(\d)(\s|\.)?(\d{3})(\s|\.)?(\d{3})$/";
        if (preg_match($regex, $phone, $res)) {
            return true;
        }
        return false;
    }

    /**
     * Gọi api thực hiện cuộc gọi
     *
     * @param $extension
     * @param $phone
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function _apiCall($extension, $phone)
    {
        $oClient = new Client();

        $mConfig = app()->get(ConfigTable::class);
        //Lấy thông tin cấu hình key + secret
        $key = $mConfig->getInfoByKey('oncall_key')['value'];
        $secret = $mConfig->getInfoByKey('oncall_secret')['value'];


        //Gọi api thực hiện cuộc gọi
        $response = $oClient->request('POST', env('DOMAIN_ONCALL') . '/oncall/extension/call', [
            'headers' => [
                'tenant' => session()->get('brand_code'),
                'key' => $key,
                'secret' => $secret
            ],
            'json' => [
                'src' => $extension,
                'to' => $phone,
            ]
        ]);

        return json_decode($response->getBody());
    }

    /**
     *
     * view assign deal
     * @return array
     */
    public function dataViewAssign()
    {
        $mDepartment = new DepartmentTable();
        $mPipeline = new PipelineTable();
        $optionDepartment = $mDepartment->getOption();
        $optionPipeline = $mPipeline->getOption('DEAL');
        return [
            'optionDepartment' => $optionDepartment,
            'optionPipeline' => $optionPipeline,
        ];
    }

    /**
     * list deal not assign with paging
     *
     * @param $filter
     * @return array
     */
    public function listDealNotAssignYet($filter)
    {
//        if (!in_array('customer-lead.permission-assign-revoke', session('routeList'))) {
//            $filter['user_id'] = Auth()->id();
//        }
        $list = $this->customerDeal->listDealNotAssignYet($filter);
        return [
            'list' => $list,
        ];
    }

    /**
     * check all deal per page
     *
     * @param $data
     */
    public function chooseAll($data)
    {
        //Get session 9
        $arrLead = [];
        if (session()->get('lead')) {
            $arrLead = session()->get('lead');
        }
        //Get session temp
        $arrLeadTemp = [];
        if (session()->get('lead_temp')) {
            $arrLeadTemp = session()->get('lead_temp');
        }
        //Merge vào array temp
        $arrLeadNew = [];
        if (count($data['arr_check']) > 0) {
            foreach ($data['arr_check'] as $v) {
                $arrLeadNew[$v['deal_code']] = [
                    'deal_id' => $v['deal_id'],
                    'deal_code' => $v['deal_code'],
                    'time_revoke_lead' => $v['time_revoke_lead'],
                ];
            }
        }
        //Merge 2 array temp + new
        $arrLeadTempNew = array_merge($arrLeadTemp, $arrLeadNew);
        //Merge array 9 + arr new temp
        $arrResult = array_merge($arrLeadTempNew, $arrLead);
        //Lưu session temp mới
        session()->forget('lead_temp');
        session()->put('lead_temp', $arrResult);
    }

    /**
     * check 1 deal
     *
     * @param $data
     */
    public function choose($data)
    {
        //Get session main
        $arrLead = [];
        if (session()->get('lead')) {
            $arrLead = session()->get('lead');
        }
        //Get session temp
        $arrLeadTemp = [];
        if (session()->get('lead_temp')) {
            $arrLeadTemp = session()->get('lead_temp');
        }
        //Merge vào array temp
        $arrLeadNew = [
            $data['deal_code'] => [
                'deal_id' => $data['deal_id'],
                'deal_code' => $data['deal_code'],
                'time_revoke_lead' => $data['time_revoke_lead'],
            ]
        ];
        //Merge 2 array temp + new
        $arrLeadTempNew = array_merge($arrLeadTemp, $arrLeadNew);
        //Merge array 9 + arr new temp
        $arrResult = array_merge($arrLeadTempNew, $arrLead);
        //Lưu session temp mới
        session()->forget('lead_temp');
        session()->put('lead_temp', $arrResult);
    }

    /**
     * un check all deal per page
     *
     * @param $data
     */
    public function unChooseAll($data)
    {
        //Get session 9
        $arrLead = [];
        if (session()->get('lead')) {
            $arrLead = session()->get('lead');
        }
        //Get session temp
        $arrLeadTemp = [];
        if (session()->get('lead_temp')) {
            $arrLeadTemp = session()->get('lead_temp');
        }
        //Merge 2 array 9 + temp
        $arrResult = array_merge($arrLeadTemp, $arrLead);
        $arrRemoveLeadTemp = [];
        //Unset phần tử
        if (count($data['arr_un_check']) > 0) {
            foreach ($data['arr_un_check'] as $v) {
                $arrRemoveLeadTemp [] = $v['deal_code'];
                unset($arrResult[$v['deal_code']]);
            }
        }
        //Lưu session temp mới
        session()->forget('lead_temp');
        session()->put('lead_temp', $arrResult);
        //Get session remove temp
        if (session()->get('remove_lead')) {
            $arrRemoveLeadTemp = session()->get('remove_lead');
        }
        //Lưu session remove temp
        session()->forget('remove_lead');
        session()->put('remove_lead', $arrRemoveLeadTemp);
    }

    /**
     * un check 1 deal
     *
     * @param $data
     */
    public function unChoose($data)
    {
        //Get session 9
        $arrLead = [];
        if (session()->get('lead')) {
            $arrLead = session()->get('lead');
        }
        //Get session temp
        $arrLeadTemp = [];
        if (session()->get('lead_temp')) {
            $arrLeadTemp = session()->get('lead_temp');
        }
        //Merge 2 array 9 + temp
        $arrResult = array_merge($arrLeadTemp, $arrLead);
        //Unset phần tử
        unset($arrResult[$data['deal_code']]);
        //Lưu session temp mới
        session()->forget('lead_temp');
        session()->put('lead_temp', $arrResult);
        //Get session remove temp
        $arrRemoveLeadTemp = [];
        if (session()->get('remove_lead')) {
            $arrRemoveLeadTemp = session()->get('remove_lead');
        }
        //Lưu session remove temp
        $arrRemoveLeadTemp [] = $data['deal_code'];
        session()->forget('remove_lead');
        session()->put('remove_lead', $arrRemoveLeadTemp);
    }

    /**
     * check all deal not assign all page
     *
     * @param $input
     */
    public function checkAllDeal($input)
    {
        // Xoá hết session lead cũ
        if (session()->get('lead')) {
            session()->forget('lead');
        }
        if (session()->get('lead_temp')) {
            session()->forget('lead_temp');
        }
        if (session()->get('remove_lead')) {
            session()->forget('remove_lead');
        }
        // Nếu check all thì lưu lại session tất cả deal
        if ($input['is_check_all']) {
            // Lấy danh sách lead
            $list = $this->customerDeal->listDealNotPaging($input);
            $arrDeal = [];
            if (count($list) > 0) {
                foreach ($list as $v) {
                    $arrDeal[$v['deal_code']] = [
                        'deal_id' => $v['deal_id'],
                        'deal_code' => $v['deal_code'],
                        'time_revoke_lead' => $v['time_revoke_lead'],
                    ];
                }
            }
            session()->put('lead_temp', $arrDeal);
        } else {
            if (session()->get('lead_temp')) {
                session()->forget('lead_temp');
            }
        }
    }

    /**
     * submit deal assign
     *
     * @param $input
     * @return array
     */
    public function submitAssign($input)
    {
        try {
            $arrStaff = $input['arrStaff'];
            $arrLead = [];
            $arrAssign = []; // Mảng sau khi phân bổ
            if (session()->get('lead_temp')) {
                $arrLead = session()->get('lead_temp');
            }
//            dd($arrStaff, $arrLead);
            // Phân bổ đều lead cho mỗi staff
            $amountLead = count($arrLead);
            $amountStaff = count($arrStaff);
            if ($amountLead > 0 && $amountStaff > 0) {
                $i = 0;
                foreach ($arrLead as $value) {
                    $arrAssign[] = [
                        'deal_id' => $value['deal_id'],
                        'sale_id' => $arrStaff[$i % $amountStaff],
                        'time_revoke_lead' => $value['time_revoke_lead']
                    ];
                    $i++;
                }
            } else {
                return [
                    'error' => true,
                    'message' => __('Hãy chọn deal')
                ];
            }
            // Update sale_id trong table cpo_deals
            if (count($arrAssign) > 0) {
                $timeNow = Carbon::now();
                foreach ($arrAssign as $value) {
                    // Lấy thời gian tối đa lead chuyển đổi
                    $timeMax = $value['time_revoke_lead'];
                    $this->customerDeal->edit($value['deal_id'], [
                        'sale_id' => $value['sale_id'],
                        'date_revoke' => $timeNow->addDay($timeMax)
                    ]);
                }
            }
            // Xoa session
            if (session()->get('lead_temp')) {
                session()->forget('lead_temp');
            }
            return [
                'error' => false,
                'message' => __('Phân bổ thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Phân bổ thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }


    /**
     * popup revoke deal
     *
     * @param $input
     * @return array
     */
    public function popupRevoke($input)
    {
        $mStaff = new StaffsTable();
        $optionStaff = $mStaff->getListStaff();

        $html = \View::make('customer-lead::customer-deal.popup-revoke', [
            "optionStaff" => $optionStaff,
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * submit revoke sale_id
     *
     * @param $input
     * @return array
     */
    public function submitRevoke($input)
    {
        try {
            $staffId = $input['staff_id'];
            if ($staffId == "") {
                return [
                    'error' => true,
                    'message' => __('Hãy chọn nhân viên đuọc phân công')
                ];
            }
            // Thu hồi deal theo staff id (xoá data sale_id, date_revoke)

            $dataEdit = [
                'sale_id' => null,
                'date_revoke' => null,
            ];
            $this->customerDeal->editWithStaffId($dataEdit, $staffId);
            return [
                'error' => false,
                'message' => __('Thu hồi thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thu hồi thất bại')
            ];
        }
    }

    public function popupListStaff($input)
    {
        $mStaff = new StaffsTable();
        $optionStaff = $mStaff->getListStaff();

        $html = \View::make('customer-lead::customer-deal.popup-list-staffs', [
            "optionStaff" => $optionStaff,
            "customer_deal_id" => $input['customer_deal_id']
        ])->render();

        return [
            'html' => $html
        ];
    }

    public function saveAssignStaff($input)
    {
        try {
            $mPipeline = new PipelineTable();
            $staffId = $input['staff_id'];
            if ($staffId == "") {
                return [
                    'error' => true,
                    'message' => __('Hãy chọn nhân viên đuọc phân công')
                ];
            }

            $customerDealdId = $input['customer_deal_id'];
            // LẤy thông tin của lead -> get pipeline
            $infoDeal = $this->customerDeal->getItem($customerDealdId);
            // Từ pipeline lấy số giờ tối đa để lead chuyển đổi
            $infoPipeline = $mPipeline->getDetailByCode($infoDeal['pipeline_code']);
            $maxTime = 0;
            if (isset($infoPipeline['time_revoke_lead']) && $infoPipeline['time_revoke_lead'] != null) {
                $maxTime = (int)$infoPipeline['time_revoke_lead'];
            }

            // Cập nhật customer deal: người được phân công và người phân công
            $timeNow = Carbon::now();
            $dataEdit = [
                'owner' => Auth::id(),
                'sale_id' => $staffId,
                'date_revoke' => $timeNow->addDay($maxTime)
            ];
            $this->customerDeal->edit($customerDealdId, $dataEdit);
            return [
                'error' => false,
                'message' => __('Phân công nhân viên thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Phân công nhân viên thất bại')
            ];
        }
    }

    public function revokeOne($input)
    {
        try {
            $customerDealId = $input['customer_deal_id'];
            $dataEdit = [
                'owner' => null,
                'sale_id' => null,
                'date_revoke' => null
            ];
            $this->customerDeal->edit($customerDealId, $dataEdit);

            return [
                'error' => false,
                'message' => __('Cập nhật thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Cập nhật thất bại')
            ];
        }
    }

    /**
     * Thêm bình luận
     * @param $data
     * @return mixed|void
     */
    public function addComment($data)
    {
        try {
            $mCustomerLeadComment = new CustomerDealCommentTable();
            $comment = [
                'message' => $data['description'],
                'deal_id' => $data['deal_id'],
                'deal_parent_comment_id' => isset($data['deal_comment_id']) ? $data['deal_comment_id'] : null,
                'staff_id' => Auth::id(),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];
            //Thêm bình luận ticket
            $idComment = $mCustomerLeadComment->createdComment($comment);

            $detailComment = $mCustomerLeadComment->getDetail($idComment);

            //Gửi notify bình luận ticket
            // $listCustomer = $this->getListStaff($data['ticket_id']);

            // $mNoti = new SendNotificationApi();

            // foreach ($listCustomer as $item) {
            //     if ($item != Auth()->id()) {
            //         $mNoti->sendStaffNotification([
            //             'key' => 'ticket_finish_processor',
            //             'customer_id' => Auth()->id(),
            //             'object_id' => $data['ticket_id']
            //         ]);
            //     }
            // }

            $view = view('manager-work::managerWork.append.append-message', ['detail' => $detailComment, 'data' => $data])->render();

            // tạo lịch sử
            //   $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã bình luận ' );
            //   $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' approved the material requisition form ');
            //   $mMTicketHistory($note,$note_en, $data['ticket_id']);

            return [
                'error' => false,
                'message' => __('Thêm bình luận thành công'),
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm bình luận thất bại') . $e->getMessage()
            ];
        }
    }

    /**
     * hiển thị form comment
     * @param $data
     * @return mixed|void
     */
    public function showFormComment($data)
    {


        try {
            $view = $view = view('customer-lead::customer-deal.append.append-form-chat', ['deal_comment_id' => $data['deal_comment_id']])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Hiển thị form trả lời thất bại')
            ];
        }
    }

    /**
     * Lấy danh sách comment
     * @param $id
     * @return mixed|void
     */
    public function getListComment($id)
    {
        $mManageComment = new CustomerDealCommentTable();
        $listComment = $mManageComment->getListCommentCustomer($id);
        foreach ($listComment as $key => $item) {
            $listComment[$key]['child_comment'] = $mManageComment->getListCommentCustomer($id, $item['deal_comment_id']);
        }
        return $listComment;
    }
}
