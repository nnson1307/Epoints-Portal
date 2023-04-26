<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/08/2021
 * Time: 11:12
 */

namespace Modules\Contract\Repositories\Contract;


use App\Exports\ExportFile;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\CustomerGroupTable;
use Modules\Admin\Models\DepartmentTable;
use Modules\Admin\Models\MapRoleGroupStaffTable;
use Modules\Admin\Models\ProvinceTable;
use Modules\Admin\Models\StaffTitleTable;
use Modules\Contract\Models\ContractAnnexTable;
use Modules\Contract\Models\ContractBrowserTable;
use Modules\Contract\Models\ContractCareTable;
use Modules\Contract\Models\ContractCategoriesTable;
use Modules\Contract\Models\ContractCategoryConfigTabTable;
use Modules\Contract\Models\ContractCategoryStatusApproveTable;
use Modules\Contract\Models\ContractCategoryStatusTable;
use Modules\Contract\Models\ContractCategoryStatusUpdateTable;
use Modules\Contract\Models\ContractConfigTabTable;
use Modules\Contract\Models\ContractExpectedRevenueLogTable;
use Modules\Contract\Models\ContractExpectedRevenueTable;
use Modules\Contract\Models\ContractFollowMapTable;
use Modules\Contract\Models\ContractGoodsTable;
use Modules\Contract\Models\ContractLogGeneralTable;
use Modules\Contract\Models\ContractLogGoodsTable;
use Modules\Contract\Models\ContractLogPartnerTable;
use Modules\Contract\Models\ContractLogPaymentTable;
use Modules\Contract\Models\ContractLogReceiptSpendTable;
use Modules\Contract\Models\ContractLogTable;
use Modules\Contract\Models\ContractMapOrderTable;
use Modules\Contract\Models\ContractNotifyConfigMethodMapTable;
use Modules\Contract\Models\ContractNotifyConfigTable;
use Modules\Contract\Models\ContractOverviewLogTable;
use Modules\Contract\Models\ContractPartnerTable;
use Modules\Contract\Models\ContractPaymentTable;
use Modules\Contract\Models\ContractReceiptDetailTable;
use Modules\Contract\Models\ContractReceiptTable;
use Modules\Contract\Models\ContractSignMapTable;
use Modules\Contract\Models\ContractSpendTable;
use Modules\Contract\Models\ContractStaffQueueTable;
use Modules\Contract\Models\ContractTable;
use Modules\Contract\Models\ContractTagMapTable;
use Modules\Contract\Models\ContractTagTable;
use Modules\Contract\Models\CustomerTable;
use Modules\Contract\Models\DealTable;
use Modules\Contract\Models\ProductChildTable;
use Modules\Contract\Models\StaffNotificationDetailTable;
use Modules\Contract\Models\OrderDetailTable;
use Modules\Contract\Models\OrderTable;
use Modules\Contract\Models\PaymentMethodTable;
use Modules\Contract\Models\PaymentTable;
use Modules\Contract\Models\PaymentUnitTable;
use Modules\Contract\Models\ReceiptDetailTable;
use Modules\Contract\Models\ReceiptTable;
use Modules\Contract\Models\StaffEmailLogTable;
use Modules\Contract\Models\StaffTable;
use Modules\Contract\Models\SupplierTable;
use Modules\Contract\Models\VatTable;
use Modules\Contract\Repositories\ExpectedRevenue\ExpectedRevenueRepoInterface;
use Modules\CustomerLead\Models\CustomerDealDetailTable;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\Contract\Models\UnitTable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ContractRepo implements ContractRepoInterface
{
    /**
     * view + ds hợp đồng
     *
     * @param array $filter
     * @return array
     */
    public function getDataViewIndex(&$filter = [])
    {
        $mContractCategory = app()->get(ContractCategoriesTable::class);
        $mCustomerGroup = app()->get(CustomerGroupTable::class);
        $mStaff = app()->get(StaffTable::class);
        $mStaffTitle = app()->get(StaffTitleTable::class);
        $mDepartment = app()->get(DepartmentTable::class);
        $mContractTag = app()->get(ContractTagTable::class);
        $mPaymentMethod = app()->get(PaymentMethodTable::class);
        //Lấy option loại HĐ
        $optionCategory = $mContractCategory->getOption();
        $optionCustomerGroup = $mCustomerGroup->getOption();
        $optionStaff = $mStaff->getOption();
        $optionStaffTitle = $mStaffTitle->getOption();
        $optionDepartment = $mDepartment->getStaffDepartmentOption();
        $optionTag = $mContractTag->getOption();
        $optionPaymentMethod = $mPaymentMethod->getOption();

        $mContract = app()->get(ContractTable::class);
        $page = (int)($filter['page'] ?? 1);
        // get phân quyền data
        $mRoleGroupStaff = app()->get(MapRoleGroupStaffTable::class);
        $lstRoleDataContract = $mRoleGroupStaff->getRoleDataContractByStaffId(auth()->id());
        $groupRoleData = collect($lstRoleDataContract)->groupBy("role_data_type");
        if (count($groupRoleData) > 0) {
            // có phân quyền thì lấy cao nhất (all -> branch -> department)
            if (isset($groupRoleData['department'])) {
                $filter['role_data'] = 'department';
            }
            if (isset($groupRoleData['branch'])) {
                $filter['role_data'] = 'branch';
            }
            if (isset($groupRoleData['all'])) {
                $filter['role_data'] = 'all';
            }
        }
        $lstContract = $mContract->getList($filter);
        $currItems = $lstContract->getCollection();
        foreach ($currItems as $key => $value) {
            $dataFile = $mContract->getListFileNameOfContract($value['contract_id']);
            $dataGood = $mContract->getListGoodOfContract($value['contract_id']);
            $currItems[$key]['list_file_name'] = '';
            $currItems[$key]['list_link'] = '';
            $currItems[$key]['list_object_name'] = $dataGood['list_object_name'];
            if ($dataFile != null) {
                $currItems[$key]['list_file_name'] = $dataFile['list_file_name'];
                $currItems[$key]['list_link'] = $dataFile['list_link'];
            }
        }
        $lstContract->setCollection($currItems);
     
        return [
            'LIST' => $lstContract,
            'page' => $page,
            'optionCategory' => $optionCategory,
            'optionCustomerGroup' => $optionCustomerGroup,
            'optionStaff' => $optionStaff,
            'optionStaffTitle' => $optionStaffTitle,
            'optionDepartment' => $optionDepartment,
            'optionTag' => $optionTag,
            'optionPaymentMethod' => $optionPaymentMethod,
        ];
    }

    /**
     * Lấy data view thêm HĐ
     *
     * @param $filter
     * @return mixed|void
     */
    public function getDataViewCreate($filter)
    {
        $mContractCategory = app()->get(ContractCategoriesTable::class);
        $mOrder = app()->get(OrderTable::class);

        //Lấy option loại HĐ
        $optionCategory = $mContractCategory->getOption();

        $type = isset($filter['type']) ? $filter['type'] : '';
        $dealCode = isset($filter['deal_code']) ? $filter['deal_code'] : '';
        $mCustomer = new \Modules\CustomerLead\Models\CustomerTable();
        $mDeal = app()->get(CustomerDealTable::class);
        $dataCustomer = null;
        $dataDeal = null;

        if ($type != '' && $dealCode != '') {
            $dataDeal = $mDeal->getDealByCode($dealCode);
            $dataCustomer = $mCustomer->getCustomerByCode($dataDeal['customer_code']);

            //Lấy thông tin đơn hàng từ deal
            $infoOrderByDeal = $mOrder->getItemByDealCode($dealCode);

            if ($infoOrderByDeal != null) {
                $filter['order_code'] = $infoOrderByDeal['order_code'];
            }
        }

        $showCategory = 1;
        $categoryIdLoad = null;
        $infoOrder = null;

        if (isset($filter['order_code'])) {
            $mContractMapOrder = app()->get(ContractMapOrderTable::class);

            //Kiểm tra đơn hàng có tồn tại không
            $infoOrder = $mOrder->getInfoByCode($filter['order_code']);

            if ($infoOrder == null) {
                return [
                    'error' => 1,
                    'route' => route('contract.contract')
                ];
            }

            //Kiểm tra đơn hàng đó có link với hđ nào chưa
            $checkOrderMap = $mContractMapOrder->getContractMapOrder($filter['order_code']);

            if ($checkOrderMap != null || $checkOrderMap != null && $checkOrderMap['process_status'] == 'ordercancle') {
                return [
                    'error' => 1,
                    'route' => route('contract.contract')
                ];
            }

            //Kiểm tra có loại hđ bán ko
            $getCategoryFirst = $mContractCategory->getCategorySell();

            if ($getCategoryFirst == null) {
                return [
                    'error' => 1,
                    'route' => route('contract.contract')
                ];
            }

            $showCategory = 0;
            $categoryIdLoad = $getCategoryFirst['contract_category_id'];
        }

        return [
            'optionCategory' => $optionCategory,
            'type' => $type,
            'dealCode' => $dealCode,
            'dataCustomer' => $dataCustomer,
            'showCategory' => $showCategory,
            'categoryIdLoad' => $categoryIdLoad,
            'infoOrder' => $infoOrder
        ];
    }


    public function loadStatusAction($contractCategoryId)
    {
        $mCcStatus = app()->get(ContractCategoryStatusTable::class);
        $optionStatus = $mCcStatus->getOptionByCategory($contractCategoryId);
        return [
            'optionStatus' => $optionStatus,
        ];
    }

    /**
     * Chọn loại HĐ
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function chooseCategory($input)
    {
        $mContractCategory = app()->get(ContractCategoriesTable::class);
        $mStaff = app()->get(StaffTable::class);
        $mContractTag = app()->get(ContractTagTable::class);
        $mPaymentMethod = app()->get(PaymentMethodTable::class);
        $mPaymentUnit = app()->get(PaymentUnitTable::class);
        $mContractStatus = app()->get(ContractCategoryStatusTable::class);
        $mOrder = app()->get(OrderTable::class);
        $mVat = app()->get(VatTable::class);

        //Lấy dữ liệu load động của tab thông tin HĐ
        $data = $this->_loadDataConfigTab($input['contract_category_id']);
        //Lấy option loại HĐ
        $optionCategory = $mContractCategory->getOption();
        //Lấy option Nhân viên
        $optionStaff = $mStaff->getOption();
        //Lấy option tag
        $optionTag = $mContractTag->getOption();
        //Lấy option phương thức thanh toán
        $optionPaymentMethod = $mPaymentMethod->getOption();
        //Lấy option đơn vị thanh toán
        $optionPaymentUnit = $mPaymentUnit->getOption();
        //Lấy option trạng thái HĐ
        $optionStatus = $mContractStatus->getOptionByCategory($input['contract_category_id']);
        //Lấy option VAT
        $optionVat = $mVat->getOption();
        //Lấy thông tin loại HĐ
        $infoCategory = $mContractCategory->getItem($input['contract_category_id']);

        $mCustomer = new \Modules\CustomerLead\Models\CustomerTable();
        $mDeal = app()->get(CustomerDealTable::class);

        $type = isset($input['type']) ? $input['type'] : '';
        $dealCode = isset($input['deal_code']) ? $input['deal_code'] : '';
        $dataCustomer = null;
        $dataDeal = null;

        if ($type != '' && $dealCode != '') {
            $dataDeal = $mDeal->getDealByCode($dealCode);
            //Lấy thông tin khách hàng
            $dataCustomer = $mCustomer->getCustomerByCode($dataDeal['customer_code']);
        }

        $infoOrder = null;

        if (isset($input['order_code_load']) && $input['order_code_load']) {
            //Kiểm tra đơn hàng có tồn tại không
            $infoOrder = $mOrder->getInfoByCode($input['order_code_load']);
        }

        $html = \View::make('contract::contract.inc.info.view-info', [
            "tabGeneral" => $data['tabGeneral'],
            "tabPartner" => $data['tabPartner'],
            "tabPayment" => $data['tabPayment'],
            'optionCategory' => $optionCategory,
            'optionStaff' => $optionStaff,
            'optionTag' => $optionTag,
            'categoryId' => $input['contract_category_id'],
            'optionPaymentMethod' => $optionPaymentMethod,
            'optionPaymentUnit' => $optionPaymentUnit,
            'optionStatus' => $optionStatus,
            'type' => $type,
            'dealCode' => $dealCode,
            'dataCustomer' => $dataCustomer,
            'infoCategory' => $infoCategory,
            'infoOrder' => $infoOrder,
            'optionVat' => $optionVat
        ])->render();

        return response()->json([
            'html' => $html,
            'infoOrder' => $infoOrder,
            'dataCustomer' => $dataCustomer,
        ]);
    }

    /**
     * Lấy cấu hình động các trường dữ liệu theo loại HĐ
     *
     * @param $categoryId
     * @return array
     */
    private function _loadDataConfigTab($categoryId)
    {
        $mConfigTab = app()->get(ContractCategoryConfigTabTable::class);

        //Lấy cấu hình trường dữ liệu theo tab
        $getConfigTab = $mConfigTab->getConfigTabByCategory($categoryId);

        $tabGeneral = [];
        $tabPartner = [];
        $tabPayment = [];

        if (count($getConfigTab) > 0) {
            foreach ($getConfigTab as $v) {
                if ($v['tab'] == 'general') {
                    $tabGeneral [] = $v;
                } else if ($v['tab'] == 'partner') {
                    $tabPartner [] = $v;
                } else if ($v['tab'] == 'payment') {
                    $tabPayment [] = $v;
                }
            }
        }

        return [
            'tabGeneral' => $tabGeneral,
            'tabPartner' => $tabPartner,
            'tabPayment' => $tabPayment
        ];
    }

    /**
     * Lấy cấu hình động các trường dữ liệu theo HĐ
     *
     * @param $contractId
     * @return array[]
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function _loadDataConfigTabContract($contractId)
    {
        $mConfigTab = app()->get(ContractConfigTabTable::class);

        //Lấy cấu hình trường dữ liệu theo tab
        $getConfigTab = $mConfigTab->getConfigTabByContract($contractId);

        $tabGeneral = [];
        $tabPartner = [];
        $tabPayment = [];

        if (count($getConfigTab) > 0) {
            foreach ($getConfigTab as $v) {
                if ($v['tab'] == 'general') {
                    $tabGeneral [] = $v;
                } else if ($v['tab'] == 'partner') {
                    $tabPartner [] = $v;
                } else if ($v['tab'] == 'payment') {
                    $tabPayment [] = $v;
                }
            }
        }

        return [
            'tabGeneral' => $tabGeneral,
            'tabPartner' => $tabPartner,
            'tabPayment' => $tabPayment
        ];
    }

    /**
     * Lưu tag
     *
     * @param $input
     * @return array|mixed
     */
    public function insertTag($input)
    {
        try {
            $mContractTag = app()->get(ContractTagTable::class);

            //Insert tag
            $data = [
                'name' => $input['tag_name'],
                'keyword' => str_slug($input['tag_name'])
            ];

            $tagId = $mContractTag->add($data);

            return [
                'error' => false,
                'tag_id' => $tagId,
                'message' => __('Thêm mới thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm mới thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Chọn loại đối tác
     *
     * @param $input
     * @return mixed|void
     */
    public function changePartnerType($input)
    {
        $mCustomer = app()->get(CustomerTable::class);
        $mSupplier = app()->get(SupplierTable::class);

        $data = [];

        if ($input['partner_object_type'] == "supplier") {
            //Nhà cung cấp
            $data = $mSupplier->getOption();
        } else {
            //Khách hàng (cá nhân or doanh nghiệp)
            $data = $mCustomer->getCustomer($input['partner_object_type']);
        }

        return response()->json([
            'option' => $data,
            'placeholder' => __('Chọn đối tác')
        ]);
    }

    /**
     * Chọn đối tác
     *
     * @param $input
     * @return mixed|void
     */
    public function changePartner($input)
    {
        $mCustomer = app()->get(CustomerTable::class);
        $mSupplier = app()->get(SupplierTable::class);

        $name = "";
        $address = '';
        $representative = '';
        $hotline = '';
        $staffTitle = '';
        $phone = "";
        $email = "";

        $info = null;

        if ($input['partner_object_type'] == "supplier") {
            //Nhà cung cấp
            $info = $mSupplier->getInfoById($input['partner_object_id']);

            if ($info != null) {
                $name = $info['name'];
                $address = $info['address'];
                $representative = $info['contact_name'];
                $staffTitle = $info['contact_title'];
                $phone = $info['phone'];
            }
        } else {
            //Khách hàng (cá nhân or doanh nghiệp)
            $info = $mCustomer->getInfoById($input['partner_object_id']);

            if ($info != null) {
                $name = $info['full_name'];
                $address = $info['address'];
                $phone = $info['phone'];
                $email = $info['email'];

                if ($info['district_name'] != null) {
                    $address .= ', ' . $info['district_name'];
                }

                if ($info['province_name'] != null) {
                    $address .= ', ' . $info['province_name'];
                }
            }
        }

        return [
            'name' => $name,
            'address' => $address,
            'representative' => $representative,
            'hotline' => $hotline,
            'staffTitle' => $staffTitle,
            'phone' => $phone,
            'email' => $email
        ];
    }

    /**
     * Lưu phương thức thanh toán
     *
     * @param $input
     * @return array|mixed
     */
    public function insertPaymentMethod($input)
    {
        try {
            $mPaymentMethod = app()->get(PaymentMethodTable::class);

            //Insert phương thức thanh toán
            $paymentMethodId = $mPaymentMethod->add([
                'payment_method_name_vi' => $input['payment_method_name'],
                'payment_method_name_en' => $input['payment_method_name'],
                'payment_method_type' => 'auto',
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            //Update mã phương thức thanh toán
            $paymentMethodCode = "PTTT_" . date("dmY") . sprintf("%02d", $paymentMethodId);

            $mPaymentMethod->edit([
                "payment_method_code" => $paymentMethodCode
            ], $paymentMethodId);

            return [
                'error' => false,
                'payment_method_id' => $paymentMethodId,
                'message' => __('Thêm mới thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm mới thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lưu đơn vị thanh toán
     *
     * @param $input
     * @return array|mixed
     */
    public function insertPaymentUnit($input)
    {
        try {
            $mPaymentUnit = app()->get(PaymentUnitTable::class);

            //Insert đơn vị thanh toán
            $paymentUnitId = $mPaymentUnit->add([
                'name' => $input['name'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            return [
                'error' => false,
                'payment_unit_id' => $paymentUnitId,
                'message' => __('Thêm mới thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm mới thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Thêm HĐ
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $mContract = app()->get(ContractTable::class);
            $mContractTagMap = app()->get(ContractTagMapTable::class);
            $mContractFollowMap = app()->get(ContractFollowMapTable::class);
            $mContractSignMap = app()->get(ContractSignMapTable::class);
            $mContractPartner = app()->get(ContractPartnerTable::class);
            $mContractPayment = app()->get(ContractPaymentTable::class);
            $mContractCategory = app()->get(ContractCategoriesTable::class);

            if ($input['is_renew'] == 1 && !isset($input['dataGeneral']['expired_date'])) {
                return response()->json([
                    "error" => true,
                    "message" => __("Hợp đồng có đánh dấu cần gia hạn, bạn vui lòng nhập ngày hết hạn")
                ]);
            }

            //Lấy thông tin loại HĐ
            $infoCategory = $mContractCategory->getItem($input['dataGeneral']['contract_category_id']);

            $tag = isset($input['dataGeneral']['tag']) ? $input['dataGeneral']['tag'] : [];
            $follow = isset($input['dataGeneral']['follow_by']) ? $input['dataGeneral']['follow_by'] : [];
            $sign = isset($input['dataGeneral']['sign_by']) ? $input['dataGeneral']['sign_by'] : [];

            unset($input['dataGeneral']['tag'], $input['dataGeneral']['follow_by'], $input['dataGeneral']['sign_by']);

            $input['dataGeneral']['status_code'] = $input['status_code'];
            $input['dataGeneral']['is_renew'] = $input['is_renew'];
            $input['dataGeneral']['number_day_renew'] = $input['number_day_renew'];
            $input['dataGeneral']['is_created_ticket'] = $input['is_created_ticket'];
            $input['dataGeneral']['status_code_created_ticket'] = $input['status_code_created_ticket'];
//            $input['dataGeneral']['is_value_goods'] = $input['is_value_goods'];
            $input['dataGeneral']['created_by'] = Auth()->id();
            $input['dataGeneral']['updated_by'] = Auth()->id();
            //Thêm HĐ
            $contractId = $mContract->add($input['dataGeneral']);
            //Update mã HĐ
            $contractCode = $infoCategory['contract_code_format'] . sprintf("%04d", $contractId);

            $mContract->edit([
                "contract_code" => $contractCode
            ], $contractId);

            // bắt case hợp đồng được chỉ định người theo dõi
            if (isset($input['dataGeneral']['performer_by']) && $input['dataGeneral']['performer_by'] != '') {
                $this->saveContractNotification('nominated', $contractId, __('Thông tin chung'));
            }

            $arrTag = [];

            if (count($tag) > 0) {
                foreach ($tag as $v) {
                    $arrTag [] = [
                        "contract_id" => $contractId,
                        "tag_id" => $v
                    ];
                }
            }
            //Thêm tag HĐ
            $mContractTagMap->insert($arrTag);

            $arrFollow = [];

            if (count($follow) > 0) {
                foreach ($follow as $v) {
                    $arrFollow [] = [
                        "contract_id" => $contractId,
                        "follow_by" => $v
                    ];
                }
            }
            //Thêm người theo dõi
            $mContractFollowMap->insert($arrFollow);
            // case send notify
            $this->saveContractNotification('more_followers', $contractId, __('Thông tin chung'));

            $arrSign = [];

            if (count($sign) > 0) {
                foreach ($sign as $v) {
                    $arrSign [] = [
                        "contract_id" => $contractId,
                        "sign_by" => $v
                    ];
                }
            }
            //Thêm người ký
            $mContractSignMap->insert($arrSign);
            // case send notify
            $this->saveContractNotification('more_signed_by', $contractId, __('Thông tin chung'));

            $input['dataPartner']['contract_id'] = $contractId;
            $input['dataPayment']['contract_id'] = $contractId;
            //Thêm thông tin đối tác
            $mContractPartner->add($input['dataPartner']);
            //Thêm thông tin thanh toán
            $mContractPayment->add($input['dataPayment']);

            // xử lý khi thêm từ deal (xử lý thêm hàng hoá, update contract code vào trong deal)
            // xử lý lưu log nếu hợp đồng thoả điều kiện trạng thái đang thực hiện và có ngày có hiệu lực
            $mOrder = app()->get(OrderTable::class);
            $mDeal = app()->get(CustomerDealTable::class);
            $mDealDetail = app()->get(CustomerDealDetailTable::class);
            $mContractGoods = app()->get(ContractGoodsTable::class);
            $mContractCare = app()->get(ContractCareTable::class);
            $mContractOverviewLog = app()->get(ContractOverviewLogTable::class);
            $mContractCategoryStatus = app()->get(ContractCategoryStatusTable::class);
            // kiểm tra trạng thái đang thực hiện
            $dataContractCategoryStatus = $mContractCategoryStatus->getStatusNameByCode($input['dataGeneral']['status_code']);

            if (isset($input['deal_code']) && $input['deal_code'] != '') {
                $mDeal->editByCode($input['deal_code'], [
                    "contract_code" => $contractCode
                ]);
                // nếu thông tin deal được tạo từ chăm sóc hợp đồng hết hạn thì chỉnh sửa lại trạng thái chăm sóc thành công
                $contractCareDeal = $mDeal->getDealByCode($input['deal_code']);
                if ($contractCareDeal['deal_type_code'] == 'contract_expire') {
                    $mContractCare->updateDataByContract(['status' => 'success'], $contractCareDeal['deal_type_object_id']);
                }
                $totalAmount = 0;
                $totalVAT = 0;
                $totalDiscount = 0;
                $lastTotalAmount = 0;
                $countNoKpi = 0;
                $dealGoods = $mDealDetail->getList($input['deal_code']);
                $dataOrder = $mOrder->getItemByDealCode($input['deal_code']);
                $dataContractGoods = [];
                $mProductChild = new \Modules\Admin\Models\ProductChildTable();
                foreach ($dealGoods as $key => $value) {
                    $dataContractGoods[] = [
                        'contract_id' => $contractId,
                        'object_type' => $value['object_type'],
                        'object_name' => $value['object_name'],
                        'object_id' => $value['object_id'],
                        'object_code' => $value['object_code'],
                        'price' => $value['price'],
                        'quantity' => $value['quantity'],
                        'discount' => $value['discount'],
                        'order_code' => isset($dataOrder['order_code']) ? $dataOrder['order_code'] : '',
                        'tax' => 0,
                        'amount' => $value['amount'],
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id()
                    ];
                    if ($value['object_type'] == 'product') {
                        $dataProductChild = $mProductChild->getItem($value['object_id']);
                        if ($dataProductChild['is_applied_kpi'] == 0) {
                            $countNoKpi++;
                        }
                    }
                    $totalAmount += $value['price'] * $value['quantity'];
                    $totalVAT += $value['tax'];
                    $totalDiscount += $value['discount'];
                    $lastTotalAmount += $value['amount'];
                }
                if ($countNoKpi > 0) {
                    $mContract->edit([
                        'is_applied_kpi' => 0
                    ], $contractId);
                }
                // insert goods of contract
                $mContractGoods->insertList($dataContractGoods);

                $mContractPayment->edit([
                    'total_amount' => $totalAmount,
                    'tax' => $totalVAT,
                    'discount' => $totalDiscount,
                    'last_total_amount' => $lastTotalAmount
                ], $contractId);
                // hợp đồng tái ký
                if ($dataContractCategoryStatus['default_system'] == 'processing'
                    && $input['dataGeneral']['effective_date'] != ''
                    && $input['dataGeneral']['performer_by'] != '') {
                    // check exitst log
                    $checkLog = $mContractOverviewLog->checkExistsLog($contractId, 'renew');
                    if ($checkLog == null) {
                        $dataContractOverviewLog = [
                            'contract_id' => $contractId,
                            'contract_overview_type' => 'renew',
                            'effective_date' => $input['dataGeneral']['effective_date'],
                            'performer_by' => $input['dataGeneral']['performer_by'],
                            'total_amount' => $lastTotalAmount,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                        $mContractOverviewLog->createDataLog($dataContractOverviewLog);
                    }
                }
                // đánh dấu hợp đồng tái kí (contract_form)
                $mContract->edit([
                    'contract_form' => 'renew'
                ], $contractId);
            } else {
                // hợp đồng mới
                if ($dataContractCategoryStatus['default_system'] == 'processing'
                    && $input['dataGeneral']['effective_date'] != ''
                    && $input['dataGeneral']['performer_by'] != '') {
                    // check exitst log
                    $checkLog = $mContractOverviewLog->checkExistsLog($contractId, 'new');
                    if ($checkLog == null) {
                        $dataContractOverviewLog = [
                            'contract_id' => $contractId,
                            'contract_overview_type' => 'new',
                            'effective_date' => $input['dataGeneral']['effective_date'],
                            'performer_by' => $input['dataGeneral']['performer_by'],
                            'total_amount' => 0,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                        $mContractOverviewLog->createDataLog($dataContractOverviewLog);
                    }
                }

            }


            if ($input['category_type'] == 'sell' && isset($input['order_code_load']) && $input['order_code_load'] != null) {
                //Link đơn hàng với hợp đồng
                $this->_linkOrderContract($contractId, $contractCode, $input['order_code_load'], $input['contract_source']);
            }
            $isCreateTicket = 1;
            if ($input['category_type'] == 'buy') {
                $isCreateTicket = 0;
            }

            $mContractCategoryConfigTab = app()->get(ContractCategoryConfigTabTable::class);
            $mContractConfigTab = app()->get(ContractConfigTabTable::class);

            //Lấy template loại HĐ để lưu template cho HĐ thời điểm hiện tại
            $getTemplate = $mContractCategoryConfigTab->getConfigTabByCategory($input['dataGeneral']['contract_category_id']);

            $arrayTemplate = [];

            if (count($getTemplate) > 0) {
                foreach ($getTemplate as $v) {
                    $arrayTemplate [] = [
                        'contract_id' => $contractId,
                        'tab' => $v['tab'],
                        'key' => $v['key'],
                        'type' => $v['type'],
                        'key_name' => $v['key_name'],
                        'is_default' => $v['is_default'],
                        'is_show' => $v['is_show'],
                        'is_validate' => $v['is_validate'],
                        'number_col' => $v['number_col']
                    ];
                }
            }

            //Insert template cho HĐ
            $mContractConfigTab->insert($arrayTemplate);

            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Thêm hợp đồng thành công"),
                "is_create_ticket" => $isCreateTicket,
                "contract_id" => $contractId,
                "url" => route('contract.contract.edit', $contractId)
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Thêm hợp đồng thất bại"),
                "_message" => $e->getMessage() . ' line: ' . $e->getLine()
            ]);
        }
    }

    const GOODS = "goods";
    const RECEIPT = "receipt";

    /**
     * Thêm nhanh đơn hàng khi tạo hợp đồng bán
     *
     * @param $contractId
     * @param $contractCode
     * @param $orderCode
     * @param $contractSource
     */
    private function _linkOrderContract($contractId, $contractCode, $orderCode, $contractSource)
    {
        $mOrder = app()->get(OrderTable::class);
        $mOrderDetail = app()->get(OrderDetailTable::class);
        $mContractMapOrder = app()->get(ContractMapOrderTable::class);
        $mLog = app()->get(ContractLogTable::class);
        $mLogGoods = app()->get(ContractLogGoodsTable::class);
        $mContractGoods = app()->get(ContractGoodsTable::class);
        $mReceipt = app()->get(ReceiptTable::class);
        $mReceiptDetail = app()->get(ReceiptDetailTable::class);
        $mContractReceipt = app()->get(ContractReceiptTable::class);
        $mContractReceiptDetail = app()->get(ContractReceiptDetailTable::class);
        $mLogReceipt = app()->get(ContractLogReceiptSpendTable::class);

        //Lấy thông tin đơn hàng
        $infoOrder = $mOrder->getInfoByCode($orderCode);
        //Lấy thông tin chi tiết đơn hàng
        $orderDetail = $mOrderDetail->getDetail($infoOrder['order_id']);

        //Insert bảng map hợp đồng và đơn hàng
        $mContractMapOrder->add([
            'contract_code' => $contractCode,
            'order_code' => $orderCode,
            'source' => $contractSource
        ]);

        if (count($orderDetail)) {
            //Lưu log hợp đồng khi thêm hàng hoá
            $logId = $mLog->add([
                "contract_id" => $contractId,
                "change_object_type" => self::GOODS,
                "note" => __('Thay đổi hàng hoá'),
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);

            foreach ($orderDetail as $v) {
                //Lưu thông tin hàng hoá
                $goodsId = $mContractGoods->add([
                    "contract_id" => $contractId,
                    "object_type" => $v['object_type'],
                    "object_name" => $v['object_name'],
                    "object_id" => $v['object_id'],
                    "object_code" => $v['object_code'],
                    "price" => $v['price'],
                    "quantity" => $v['quantity'],
                    "discount" => $v['discount'],
                    "tax" => $v['tax'],
                    "amount" => $v['amount'],
                    "order_code" => $orderCode,
                    "staff_id" => $v['staff_id'],
                    "created_by" => Auth()->id(),
                    "updated_by" => Auth()->id()
                ]);

                //Log detail
                $mLogGoods->add([
                    "contract_log_id" => $logId,
                    "contract_godds_id" => $goodsId,
                    "object_type" => $v['object_type'],
                    "object_name" => $v['object_name'],
                    "object_id" => $v['object_id'],
                    "object_code" => $v['object_code'],
                    "price" => $v['price'],
                    "quantity" => $v['quantity'],
                    "discount" => $v['discount'],
                    "tax" => $v['tax'],
                    "amount" => $v['amount'],
                    "note" => $v['note']
                ]);
            }
        }

        //Nếu đơn hàng đã thanh toán thì insert chi tiết thu
        if (in_array($infoOrder['process_status'], ['paysuccess', 'pay-half'])) {
            //Lấy thông tin thanh toán của đơn hàng
            $getReceipt = $mReceipt->getTotalReceipt($infoOrder['order_id']);

            if (count($getReceipt) > 0) {
                foreach ($getReceipt as $v) {
                    //Thêm đợt thu
                    $contractReceiptId = $mContractReceipt->add([
                        'contract_id' => $contractId,
                        'receipt_code' => $v['receipt_code'],
                        'content' => __("Thanh toán đơn hàng") . ' ' . $infoOrder['order_code'],
                        'collection_date' => Carbon::now()->format('Y-m-d'),
                        'collection_by' => $v['staff_id'],
                        'total_amount_receipt' => $v['amount_paid'],
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id()
                    ]);
                    //Lấy thông tin chi tiết thanh toán
                    $getReceiptDetail = $mReceiptDetail->getReceiptDetail($v['receipt_id']);

                    $arrReceiptDetail = [];

                    if (count($getReceiptDetail) > 0) {
                        foreach ($getReceiptDetail as $v1) {
                            $arrReceiptDetail [] = [
                                "contract_receipt_id" => $contractReceiptId,
                                "amount_receipt" => $v1['amount'],
                                "payment_method_id" => $v1['payment_method_id'],
                                "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                                "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
                            ];
                        }
                    }
                    //Thêm chi tiết đợt thu
                    $mContractReceiptDetail->insert($arrReceiptDetail);

                    //Lưu log hợp đồng khi trigger thu - chi
                    $logId = $mLog->add([
                        "contract_id" => $contractId,
                        "change_object_type" => self::RECEIPT,
                        "note" => __('Thêm đợt thu'),
                        "created_by" => Auth()->id(),
                        "updated_by" => Auth()->id()
                    ]);
                    //Log detail
                    $mLogReceipt->add([
                        "contract_log_id" => $logId,
                        "object_type" => self::RECEIPT,
                        "object_id" => $contractReceiptId
                    ]);
                }

            }
        }
    }

    /**
     * Lấy dữ liệu view chỉnh sửa HĐ
     *
     * @param $contractId
     * @param $isEdit
     * @return mixed|void
     */
    public function getDataViewEdit($contractId, $isEdit)
    {
        $mContractCategory = app()->get(ContractCategoriesTable::class);
        $mContract = app()->get(ContractTable::class);
        $mStaff = app()->get(StaffTable::class);
        $mContractTag = app()->get(ContractTagTable::class);
        $mPaymentMethod = app()->get(PaymentMethodTable::class);
        $mPaymentUnit = app()->get(PaymentUnitTable::class);
        $mContractStatus = app()->get(ContractCategoryStatusTable::class);
        $mContractTagMap = app()->get(ContractTagMapTable::class);
        $mContractFollowMap = app()->get(ContractFollowMapTable::class);
        $mContractSignMap = app()->get(ContractSignMapTable::class);
        $mContractPartner = app()->get(ContractPartnerTable::class);
        $mContractPayment = app()->get(ContractPaymentTable::class);
        $mCustomer = app()->get(CustomerTable::class);
        $mSupplier = app()->get(SupplierTable::class);
        $mUnit = app()->get(UnitTable::class);
        $mContractMapOrder = app()->get(ContractMapOrderTable::class);
        $mContractStatusUpdate = app()->get(ContractCategoryStatusUpdateTable::class);
        $mVat = app()->get(VatTable::class);

        //Lấy thông tin HĐ
        $info = $mContract->getInfo($contractId);


        if ($isEdit == 1) {
            //View chỉnh sửa thì kiểm tra trạng thái có được chỉnh sửa không
            $getStatus = $mContractStatus->getStatusNameByCode($info['status_code']);

            if ($getStatus != null && $getStatus['is_edit_contract'] == 0) {
                return [
                    'error' => 1,
                    'message' => __('Trạng thái hợp đồng không thể chỉnh sửa')
                ];
            }
        }

        //Lấy dữ liệu load động của tab thông tin HĐ
        $data = $this->_loadDataConfigTabContract($contractId);

        //Lấy option loại HĐ
        $optionCategory = $mContractCategory->getOption();
        //Lấy option Nhân viên
        $optionStaff = $mStaff->getOption();
        //Lấy option tag
        $optionTag = $mContractTag->getOption();
        //Lấy option phương thức thanh toán
        $optionPaymentMethod = $mPaymentMethod->getOption();
        //Lấy option đơn vị thanh toán
        $optionPaymentUnit = $mPaymentUnit->getOption();
        //Lấy option VAT
        $optionVat = $mVat->getOption();

        $statusUpdate = [$info['status_code']];
        //Lấy trạng thái được update của hợp đồng
        $getStatusUpdate = $mContractStatusUpdate->getStatusUpdate($info['status_code']);

        if (count($getStatusUpdate) > 0) {
            foreach ($getStatusUpdate as $v) {
                $statusUpdate [] = $v['status_code_update'];
            }
        }

        //Lấy option trạng thái HĐ
        $optionStatus = $mContractStatus->getOptionByCategory($info['contract_category_id']);
        //Lấy option trạng thái HĐ được update
        $optionStatusUpdate = $mContractStatus->getOptionByCategoryEdit($info['contract_category_id'], $statusUpdate);
        //Lấy thông tin đối tác
        $infoPartner = $mContractPartner->getPartnerByContract($contractId);
        //Lấy thông tin thanh toán
        $infoPayment = $mContractPayment->getPaymentByContract($contractId);
        //Lấy option đơn vị tính
        $optionUnit = $mUnit->getOption();
        //Lấy thông tin loại HĐ
        $infoCategory = $mContractCategory->getItem($info['contract_category_id']);

        $arrTagMap = [];
        $arrFollowMap = [];
        $arrSignMap = [];

        //Lấy tag map theo HĐ
        $getTagMap = $mContractTagMap->getTagMapByContract($contractId);

        if (count($getTagMap) > 0) {
            foreach ($getTagMap as $v) {
                $arrTagMap [] = $v['tag_id'];
            }
        }
        //Lấy người theo dõi map theo HĐ
        $getFollowMap = $mContractFollowMap->getFollowMapByContract($contractId);

        if (count($getFollowMap) > 0) {
            foreach ($getFollowMap as $v) {
                $arrFollowMap [] = $v['follow_by'];
            }
        }
        //Lấy người ký map theo HĐ
        $getSignMap = $mContractSignMap->getSignMapByContract($contractId);

        if (count($getSignMap) > 0) {
            foreach ($getSignMap as $v) {
                $arrSignMap [] = $v['sign_by'];
            }
        }

        //Lấy option đối tác ăn theo loại đối tác
        if ($infoPartner['partner_object_type'] == "supplier") {
            //Nhà cung cấp
            $optionPartnerObject = $mSupplier->getOption();
        } else {
            //Khách hàng (cá nhân or doanh nghiệp)
            $optionPartnerObject = $mCustomer->getCustomer($infoPartner['partner_object_type']);
        }
        $mContractAnnex = app()->get(ContractAnnexTable::class);
        $annexFilter['contract_id'] = $contractId;
        $lstAnnex = $mContractAnnex->getList($annexFilter);

        //Lấy đơn hàng gần nhất map với hợp đồng
        $getOrder = $mContractMapOrder->getOrderMap($info['contract_code']);

        $orderCode = null;
        $totalReceipt = 0;
        $totalNotReceipt = 0;

        if ($info['type'] == 'sell' && $getOrder != null) {
            //Hợp đồng bán
            $orderCode = $getOrder['order_code'];

            $mReceipt = app()->get(ReceiptTable::class);
            //Lấy tiền đã thu của đơn hàng
            $getAmountPaid = $mReceipt->getReceiptOrder($getOrder['order_id']);

            $totalReceipt += $getAmountPaid != null ? $getAmountPaid['amount_paid'] : 0;

            $totalNotReceipt = floatval($getOrder['amount']) - floatval($totalReceipt);
        } else if ($info['type'] == 'buy') {
            //Hợp đồng mua
            $mContractSpend = app()->get(ContractSpendTable::class);

            //Lấy tiền đã thu của HĐ
            $getAmountPaid = $mContractSpend->getAmountSpend($info['contract_id']);

            $totalReceipt += $getAmountPaid != null ? $getAmountPaid['total_amount'] : 0;

            $totalNotReceipt = floatval($infoPayment['last_total_amount']) - floatval($totalReceipt);
        }


        //Lấy thông tin nhân viên phụ trách
        $infoPerformer = null;

        if (!empty($info['performer_by'])) {
            $infoPerformer = $mStaff->getInfo($info['performer_by']);
        }
        $isCreateTicket = 1;
        if ($info['type'] == 'buy') {
            $isCreateTicket = 0;
        } else {
            if ($info['ticket_code'] != "") {
                $isCreateTicket = 0;
            }
        }
        // lấy thông tin duyệt hợp đồng
        $mContractBrowse = app()->get(ContractBrowserTable::class);
        $mContractCategoryStatusApprove = app()->get(ContractCategoryStatusApproveTable::class);
        $mRoleStaff = new \Modules\Contract\Models\MapRoleGroupStaffTable();
        $dataBrowse = null;
        if ($info['is_browse'] == 1) {
            $dataBrowse = $mContractBrowse->getInfoByContract($contractId, $info['status_code']);
            $dataBrowse['can_browse'] = 0;
            $dataRoleApprove = $mContractCategoryStatusApprove->getDetailStatusApprove($dataBrowse['status_code_now']);
            foreach ($dataRoleApprove as $k => $v) {
                // get list staff by role
                $lstStaff = $mRoleStaff->getListStaffByRoleGroup($v['approve_by']);
                $arrStaff = collect($lstStaff)->groupBy('staff_id')->toArray();
                if (in_array(auth()->id(), array_keys($arrStaff))) {
                    $dataBrowse['can_browse'] = 1;
                }
            }
        }

        return [
            "tabGeneral" => $data['tabGeneral'],
            "tabPartner" => $data['tabPartner'],
            "tabPayment" => $data['tabPayment'],
            'optionCategory' => $optionCategory,
            'optionStaff' => $optionStaff,
            'optionTag' => $optionTag,
            'categoryId' => $info['contract_category_id'],
            'optionPaymentMethod' => $optionPaymentMethod,
            'optionPaymentUnit' => $optionPaymentUnit,
            'optionStatus' => $optionStatus,
            'optionStatusUpdate' => $optionStatusUpdate,
            "infoGeneral" => $info,
            "arrTagMap" => $arrTagMap,
            "arrFollowMap" => $arrFollowMap,
            "arrSignMap" => $arrSignMap,
            "optionPartnerObject" => $optionPartnerObject,
            "infoPartner" => $infoPartner,
            "infoPayment" => $infoPayment,
            "optionUnit" => $optionUnit,
            'infoCategory' => $infoCategory,
            'LIST_ANNEX' => $lstAnnex,
            'orderCode' => $orderCode,
            'totalReceipt' => $totalReceipt,
            'totalNotReceipt' => $totalNotReceipt,
            'infoPerformer' => $infoPerformer,
            'isCreateTicket' => $isCreateTicket,
            'browse' => $dataBrowse,
            'optionVat' => $optionVat
        ];
    }

    /**
     * Chỉnh sửa thông tin HĐ
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateInfo($input)
    {
        DB::beginTransaction();
        try {
            $mContract = app()->get(ContractTable::class);
            $mContractTagMap = app()->get(ContractTagMapTable::class);
            $mContractFollowMap = app()->get(ContractFollowMapTable::class);
            $mContractSignMap = app()->get(ContractSignMapTable::class);
            $mContractPartner = app()->get(ContractPartnerTable::class);
            $mContractPayment = app()->get(ContractPaymentTable::class);
            $mContractStatus = app()->get(ContractCategoryStatusTable::class);

            //Lấy thông tin HĐ
            $infoContract = $mContract->getInfo($input['contract_id']);

            //Kiểm tra trạng thái có được chỉnh sửa không
            $checkStatusUpdate = $this->_validateStatusUpdate($infoContract, $input['status_code']);

            if ($checkStatusUpdate == false) {
                return response()->json([
                    "error" => true,
                    "message" => __("Trạng thái không được phép cập nhật")
                ]);
            }

            if ($input['is_renew'] == 1 && !isset($input['dataGeneral']['expired_date'])) {
                return response()->json([
                    "error" => true,
                    "message" => __("Hợp đồng có đánh dấu cần gia hạn, bạn vui lòng nhập ngày hết hạn")
                ]);
            }

            $tag = isset($input['dataGeneral']['tag']) ? $input['dataGeneral']['tag'] : [];
            $follow = isset($input['dataGeneral']['follow_by']) ? $input['dataGeneral']['follow_by'] : [];
            $sign = isset($input['dataGeneral']['sign_by']) ? $input['dataGeneral']['sign_by'] : [];

            unset($input['dataGeneral']['tag'], $input['dataGeneral']['follow_by'], $input['dataGeneral']['sign_by']);

            //Kiểm tra trạng thái có chờ duyệt không
            $infoStatusOld = $mContractStatus->getStatusNameByCode($infoContract['status_code']);

            $isBrowser = 0;

            if ($infoStatusOld['is_approve'] == 1) {
                $isBrowser = 1;
            }

            if ($isBrowser == 0) {
                $input['dataGeneral']['status_code'] = $input['status_code'];
            }

            $input['dataGeneral']['is_renew'] = $input['is_renew'];
            $input['dataGeneral']['number_day_renew'] = $input['number_day_renew'];
            $input['dataGeneral']['is_created_ticket'] = $input['is_created_ticket'];
            $input['dataGeneral']['status_code_created_ticket'] = $input['status_code_created_ticket'];
//            $input['dataGeneral']['is_value_goods'] = $input['is_value_goods'];
            $input['dataGeneral']['updated_by'] = Auth()->id();
            $input['dataGeneral']['is_browse'] = $isBrowser;

            $checkNominated = 0;
            $checkFollower = 0;
            $checkSignBy = 0;
            $checkChangeInfo = 0;
            //Lưu log thay đổi
            $this->_insertLogInfo($input, $infoContract, $tag, $follow, $sign,
                $checkNominated,
                $checkFollower,
                $checkSignBy,
                $checkChangeInfo);
            //Chỉnh sửa thông tin HĐ
            $mContract->edit($input['dataGeneral'], $input['contract_id']);
            //Xoá tag HĐ
            $mContractTagMap->removeTagByContract($input['contract_id']);
            $arrTag = [];

            if (count($tag) > 0) {
                foreach ($tag as $v) {
                    $arrTag [] = [
                        "contract_id" => $input['contract_id'],
                        "tag_id" => $v
                    ];
                }
            }
            //Thêm tag HĐ
            $mContractTagMap->insert($arrTag);
            //Xoá người ký
            $mContractSignMap->removeSignByContract($input['contract_id']);

            $arrSign = [];

            if (count($sign) > 0) {
                foreach ($sign as $v) {
                    $arrSign [] = [
                        "contract_id" => $input['contract_id'],
                        "sign_by" => $v
                    ];
                }
            }
            //Thêm người ký
            $mContractSignMap->insert($arrSign);
            //Xoá người theo dõi
            $mContractFollowMap->removeFollowByContract($input['contract_id']);

            $arrFollow = [];

            if (count($follow) > 0) {
                foreach ($follow as $v) {
                    $arrFollow [] = [
                        "contract_id" => $input['contract_id'],
                        "follow_by" => $v
                    ];
                }
            }
            //Thêm người theo dõi
            $mContractFollowMap->insert($arrFollow);
            //Chỉnh sửa thông tin đối tác
            $mContractPartner->edit($input['dataPartner'], $input['contract_id']);
            //Chỉnh sửa thông tin thanh toán
            $mContractPayment->edit($input['dataPayment'], $input['contract_id']);
            // after update info all -> send notify
            if ($checkNominated) {
                $this->saveContractNotification('nominated', $input['contract_id'], __('Thông tin chung'));
            }
            if ($checkFollower) {
                $this->saveContractNotification('more_followers', $input['contract_id'], __('Thông tin chung'));
            }
            if ($checkSignBy) {
                $this->saveContractNotification('more_signed_by', $input['contract_id'], __('Thông tin chung'));
            }
            if ($checkChangeInfo) {
                $this->saveContractNotification('updated_content', $input['contract_id'], __('Thông tin chung'));
            }
            //Kiểm tra có ngày ký hđ không, có thì check log nhắc nhở thu - chi để insert vào bảng log nhắc cái ngày

            $isCreateTicket = 1;
            if ($infoContract['type'] == 'buy') {
                $isCreateTicket = 0;
            } else {
                if ($infoContract['ticket_code'] != "") {
                    $isCreateTicket = 0;
                }
            }

            //Update sang trạng thái huỷ
            $getStatus = $mContractStatus->getStatusNameByCode($input['status_code']);

            if ($getStatus['default_system'] == 'liquidated' && $isBrowser == 0) {
                //Update trạng thái đã thanh lý
                $updateStatus = $this->_checkStatusLiquidated($infoContract);

                if ($updateStatus['error'] == true) {
                    return response()->json([
                        "error" => true,
                        "message" => $updateStatus['message'],
                    ]);
                }
                $this->saveContractNotification('liquidated', $input['contract_id'], __('Thông tin chung'));
            } else if ($getStatus['default_system'] == 'cancel' && $isBrowser == 0) {
//                $this->saveContractNotification('liquidated', $input['contract_id'], __('Thông tin chung'));
            } else if ($getStatus['default_system'] == 'cancel') {
                //Update trạng thái huỷ
                $updateStatus = $this->_checkStatusCancel($infoContract);

                if ($updateStatus['error'] == true) {
                    return response()->json([
                        "error" => true,
                        "message" => $updateStatus['message'],
                    ]);
                }
            }

            if ($isBrowser == 1) {
                $mContractBrowser = app()->get(ContractBrowserTable::class);
                //Gửi yêu cầu duyệt
                $mContractBrowser->add([
                    'contract_id' => $infoContract['contract_id'],
                    'status_code_now' => $infoContract['status_code'],
                    'status_code_new' => $input['status_code'],
                    'request_by' => Auth()->id()
                ]);
                //Gửi thông báo yêu cầu duyệt
                $mContractRepo = app()->get(ContractRepoInterface::class);
                $mContractRepo->saveContractNotification('need_approved', $infoContract['contract_id']);
            }
            $mContractOverviewLog = app()->get(ContractOverviewLogTable::class);
            $mContractCategoryStatus = app()->get(ContractCategoryStatusTable::class);
            $mContractAnnex = app()->get(ContractAnnexTable::class);
            $mDeal = app()->get(DealTable::class);
            $dataDeal = $mDeal->getDealByContractCode($infoContract['contract_code']);
            $dataContractPayment = $mContractPayment->getPaymentByContract($infoContract['contract_id']);
            // kiểm tra hợp đồng này có được tạo từ đợt tái ký của chăm sóc deal hay không?
            // nếu có thì lưu log hợp đồng tái ký
            if ($dataDeal != null) {
                // hợp đồng tái ký
                if ($getStatus['default_system'] == 'processing'
                    && isset($input['dataGeneral']['effective_date']) && $input['dataGeneral']['effective_date'] != ''
                    && isset($input['dataGeneral']['performer_by']) && $input['dataGeneral']['performer_by'] != '') {
                    // check exitst log
                    $checkLog = $mContractOverviewLog->checkExistsLog($infoContract['contract_id'], 'renew');
                    if ($checkLog == null) {
                        $dataContractOverviewLog = [
                            'contract_id' => $infoContract['contract_id'],
                            'contract_overview_type' => 'renew',
                            'effective_date' => $input['dataGeneral']['effective_date'],
                            'performer_by' => $input['dataGeneral']['performer_by'],
                            'total_amount' => isset($dataContractPayment['last_total_amount']) ? $dataContractPayment['last_total_amount'] : 0,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                        $mContractOverviewLog->createDataLog($dataContractOverviewLog);
                    }
                }
            } // nếu không thì lưu log hợp đồng mới
            else {
                // kiểm tra xem hợp đồng này có đợt gia hạn ko?
                $lstContractAnnex = $mContractAnnex->getContractAnnexRecare($infoContract['contract_id']);
                if (count($lstContractAnnex) > 0) {
                    foreach ($lstContractAnnex as $keyAnnex => $valueAnnex) {
                        if ($getStatus['default_system'] == 'processing'
                            && isset($input['dataGeneral']['effective_date']) && $input['dataGeneral']['effective_date'] != ''
                            && isset($input['dataGeneral']['performer_by']) && $input['dataGeneral']['performer_by'] != '') {
                            // check exitst log
                            $checkLog = $mContractOverviewLog->checkExistsLog($infoContract['contract_id'], 'recare');
                            if ($checkLog == null) {
                                $dataContractOverviewLog = [
                                    'contract_id' => $infoContract['contract_id'],
                                    'contract_overview_type' => 'recare',
                                    'effective_date' => $input['dataGeneral']['effective_date'],
                                    'performer_by' => $input['dataGeneral']['performer_by'],
                                    'total_amount' => isset($dataContractPayment['last_total_amount']) ? $dataContractPayment['last_total_amount'] : 0,
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                ];
                                $mContractOverviewLog->createDataLog($dataContractOverviewLog);
                            }
                        }
                        // make is_checked_recare = 1
                        $mContractAnnex->updateCheckedRecare(['is_checked_recare' => 1], $valueAnnex['contract_annex_id']);
                    }
                } else {
                    // hợp đồng mới
                    if ($getStatus['default_system'] == 'processing'
                        && isset($input['dataGeneral']['effective_date']) && $input['dataGeneral']['effective_date'] != ''
                        && isset($input['dataGeneral']['performer_by']) && $input['dataGeneral']['performer_by'] != '') {
                        // check exitst log
                        $checkLog = $mContractOverviewLog->checkExistsLog($infoContract['contract_id'], 'new');
                        if ($checkLog == null) {
                            $dataContractOverviewLog = [
                                'contract_id' => $infoContract['contract_id'],
                                'contract_overview_type' => 'new',
                                'effective_date' => $input['dataGeneral']['effective_date'],
                                'performer_by' => $input['dataGeneral']['performer_by'],
                                'total_amount' => isset($dataContractPayment['last_total_amount']) ? $dataContractPayment['last_total_amount'] : 0,
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ];
                            $mContractOverviewLog->createDataLog($dataContractOverviewLog);
                        }
                    }
                }
            }

            $mContractExpectedRevenue = app()->get(ContractExpectedRevenueTable::class);

            //Lấy thông tin dự kiến thu-chi của HĐ
            $getExpectedRevenue = $mContractExpectedRevenue->getExpectedRevenueByContract($infoContract['contract_id']);


            if (count($getExpectedRevenue) > 0) {
                foreach ($getExpectedRevenue as $v) {
                    //Insert log nhắc thu - chi
                    $this->_insertLogRevenue($infoContract['contract_id'], $v, $v['contract_expected_revenue_id']);
                }
            }

            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Chỉnh sửa hợp đồng thành công"),
                "is_create_ticket" => $isCreateTicket,
                "contract_id" => $input['contract_id'],
                "url" => ""
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => true,
                "message" => __("Chỉnh sửa hợp đồng thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine() . $e->getFile()
            ]);
        }
    }

    /**
     * Lưu log nhắc dự kiến thu - chi
     *
     * @param $contractId
     * @param $infoExpectedRevenue
     */
    protected function _insertLogRevenue($contractId, $infoExpectedRevenue)
    {
        $mRevenueLog = app()->get(ContractExpectedRevenueLogTable::class);
        $mContract = app()->get(ContractTable::class);
        $infoContract = $mContract->getInfo($contractId);

        $arrLog = [];

        if ($infoExpectedRevenue['send_type'] == 'after' && $infoContract['sign_date'] != null) {
            //Sau ngày ký HĐ
            $date = Carbon::parse($infoContract['sign_date'])->addDays($infoExpectedRevenue['send_value'])->format('Y-m-d');

            $arrLog = [
                "contract_expected_revenue_id" => $infoExpectedRevenue['contract_expected_revenue_id'],
                "contract_id" => $infoContract['contract_id'],
                "date_send" => $date
            ];
        }

        if ($infoExpectedRevenue['send_type'] == 'hard' && $infoContract['effective_date'] != null && $infoContract['expired_date']) {
            //Cố định
            $dtStart = Carbon::parse($infoContract['effective_date']);
            $dtEnd = Carbon::parse($infoContract['expired_date']);
            $monthStart = Carbon::parse($infoContract['effective_date'])->format('Y-m');
            $monthStart = Carbon::parse($monthStart);
            $monthEnd = Carbon::parse($infoContract['expired_date'])->format('Y-m');
            $monthEnd = Carbon::parse($monthEnd);
            // get diff month
            $part1 = ($monthStart->format('Y') * 12) + $monthStart->format('m');
            $part2 = ($monthEnd->format('Y') * 12) + $monthEnd->format('m');
            $diffMonth = abs($part1 - $part2);
//            dd(dump($diff));
//            $diffMonth = $monthStart->diffInMonths($monthEnd);
            if ($diffMonth > 0 && $infoExpectedRevenue['send_value_child'] <= $diffMonth) {
                //Chia mỗi chu kỳ (làm tròn)
                $number = intval($diffMonth / $infoExpectedRevenue['send_value_child']);

                for ($i = 1; $i <= $number; $i++) {
                    $format = Carbon::parse($monthStart)->addMonths($i);
                    $date = Carbon::parse($monthStart)->addMonths($i)->format('Y-m') . '-' . sprintf("%02d", $infoExpectedRevenue['send_value']);
                    //Check ngày có tồn tại ko
                    if (checkdate($format->format('m'), sprintf("%02d", $infoExpectedRevenue['send_value']), $format->format('Y')) == true) {
                        if ($dtStart->lte($format) && $dtEnd->gte($format)) {
                            $arrLog [] = [
                                "contract_expected_revenue_id" => $infoExpectedRevenue['contract_expected_revenue_id'],
                                "contract_id" => $infoContract['contract_id'],
                                "date_send" => $date
                            ];
                        }
                    }
                }

            }
        }

        if ($infoExpectedRevenue['send_type'] == 'custom') {
            //Lấy ngày custom của log
            $getLog = $mRevenueLog->getLogByRevenue($infoExpectedRevenue['contract_expected_revenue_id']);

            //Tuỳ chọn ngày
            foreach ($getLog as $v) {
                if ($v['date_send'] != null) {
                    $arrLog [] = [
                        "contract_expected_revenue_id" => $infoExpectedRevenue['contract_expected_revenue_id'],
                        "contract_id" => $infoContract['contract_id'],
                        "date_send" => $v['date_send']
                    ];
                }
            }
        }

        //Xoá log nhắc thu - chi
        $mRevenueLog->removeLogByRevenue($infoExpectedRevenue['contract_expected_revenue_id']);
        //Insert log
        $mRevenueLog->insert($arrLog);
    }

    /**
     * Validate check trạng thái được update của HĐ
     *
     * @param $infoContract
     * @param $statusUpdate
     * @return bool
     */
    protected function _validateStatusUpdate($infoContract, $statusUpdate)
    {
        if ($infoContract['status_code'] == $statusUpdate) {
            //Cập nhật được chính nó
            return true;
        }

        $mStatusUpdate = app()->get(ContractCategoryStatusUpdateTable::class);

        //Lấy trạng thái được cập nhật
        $getStatusUpdate = $mStatusUpdate->getStatusUpdate($infoContract['status_code']);

        if (count($getStatusUpdate) == 0) {
            return false;
        } else {
            $arrayUpdate = [];

            foreach ($getStatusUpdate as $v) {
                $arrayUpdate [] = $v['status_code_update'];
            }

            if (!in_array($statusUpdate, $arrayUpdate)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Lưu log thông tin chung
     *
     * @param $input
     * @param $infoContract
     * @param $tag
     * @param $follow
     * @param $sign
     * @param int $checkNominated
     * @param int $checkFollower
     * @param int $checkSignBy
     * @param int $checkChangeInfo
     */
    protected function _insertLogInfo($input, $infoContract, $tag, $follow, $sign,
                                      &$checkNominated = 0,
                                      &$checkFollower = 0,
                                      &$checkSignBy = 0,
                                      &$checkChangeInfo = 0)
    {
        $mContractPartner = app()->get(ContractPartnerTable::class);
        $mContractPayment = app()->get(ContractPaymentTable::class);
        $mContractTagMap = app()->get(ContractTagMapTable::class);
        $mContractFollowMap = app()->get(ContractFollowMapTable::class);
        $mContractSignMap = app()->get(ContractSignMapTable::class);
        $mContractLog = app()->get(ContractLogTable::class);
        $mContractLogGeneral = app()->get(ContractLogGeneralTable::class);
        $mContractLogPartner = app()->get(ContractLogPartnerTable::class);
        $mContractLogPayment = app()->get(ContractLogPaymentTable::class);


        //Lấy thông tin đói tác HĐ
        $infoPartner = $mContractPartner->getPartnerByContract($infoContract['contract_id']);
        //Lấy thông tin thanh toán HĐ
        $infoPayment = $mContractPayment->getPaymentByContract($infoContract['contract_id']);
        //Lấy thông tin tag
        $getTagMap = $mContractTagMap->getTagMapByContract($infoContract['contract_id']);
        //Lấy thông tin theo dõi
        $getFollowMap = $mContractFollowMap->getFollowMapByContract($infoContract['contract_id']);
        //Lấy thông tin người ký
        $getSignMap = $mContractSignMap->getSignMapByContract($infoContract['contract_id']);

        //Lưu log
        $logId = $mContractLog->add([
            "contract_id" => $infoContract['contract_id'],
            "change_object_type" => "info",
            "note" => __('Cập nhật thông tin chung'),
            "created_by" => Auth()->id(),
            "updated_by" => Auth()->id(),
        ]);

        $arrTagOld = [];
        $arrFollowOld = [];
        $arrSignOld = [];

        if (count($getTagMap) > 0) {
            foreach ($getTagMap as $v) {
                $arrTagOld [] = $v['tag_id'];
            }
        }

        if (count($getFollowMap) > 0) {
            foreach ($getFollowMap as $v) {
                $arrFollowOld [] = $v['follow_by'];
            }
        }

        if (count($getSignMap) > 0) {
            foreach ($getSignMap as $v) {
                $arrSignOld [] = $v['sign_by'];
            }
        }

        unset($input['dataGeneral']['created_by'], $input['dataGeneral']['updated_by']);

        $input['dataGeneral']['contract_code'] = $infoContract['contract_code'];

        $dataLogGeneral = [];
        //Lấy data log thông tin chung
        foreach ($input['dataGeneral'] as $k => $v) {
            $dataLogGeneral[$k] = $infoContract[$k];
            $dataLogGeneral[$k . '_new'] = $v;
            if ($k == 'performer_by') {
                // bắt case hợp đồng được chỉ định người theo dõi
                if ($infoContract[$k] != $v && $v != '') {
                    $checkNominated = 1;
                }
            }
            if ($infoContract[$k] != $v) {
                $checkChangeInfo = 1;
            }
        }
        $dataLogGeneral['tag'] = implode(",", $arrTagOld);
        $dataLogGeneral['tag_new'] = implode(",", $tag);
        if ($dataLogGeneral['tag'] != $dataLogGeneral['tag_new']) {
            $checkChangeInfo = 1;
        }
        $dataLogGeneral['sign_by'] = implode(",", $arrSignOld);
        $dataLogGeneral['sign_by_new'] = implode(",", $sign);
        // bắt case hợp đồng được thêm/cập nhật người ký
        if ($dataLogGeneral['sign_by'] != $dataLogGeneral['sign_by_new'] && $dataLogGeneral['sign_by_new'] != '') {
            // case send notify
            $checkSignBy = 1;
        }
        $dataLogGeneral['follow_by'] = implode(",", $arrFollowOld);
        $dataLogGeneral['follow_by_new'] = implode(",", $follow);
        // bắt case hợp đồng được thêm/cập nhật người theo dõi
        if ($dataLogGeneral['follow_by'] != $dataLogGeneral['follow_by_new'] && $dataLogGeneral['follow_by_new'] != '') {
            // case send notify
            $checkFollower = 1;
        }
        $dataLogGeneral['contract_log_id'] = $logId;
        //Lưu log thông tin chung
        $mContractLogGeneral->add($dataLogGeneral);

        $dataLogPartner = [];
        //Lấy data log đối tác
        foreach ($input['dataPartner'] as $k => $v) {
            $dataLogPartner[$k] = $infoPartner[$k];
            $dataLogPartner[$k . '_new'] = $v;
            if ($infoContract[$k] != $v) {
                $checkChangeInfo = 1;
            }
        }
        $dataLogPartner['contract_log_id'] = $logId;
        //Lưu log thông tin đối tác
        $mContractLogPartner->add($dataLogPartner);

        $dataLogPayment = [];
        //Lấy data log thanh toán
        foreach ($input['dataPayment'] as $k => $v) {
            $dataLogPayment[$k] = isset($infoPayment[$k]) ? $infoPayment[$k] : null;
            $dataLogPayment[$k . '_new'] = $v;
            if ($infoContract[$k] != $v) {
                $checkChangeInfo = 1;
            }
        }
        $dataLogPayment['contract_log_id'] = $logId;
        //Lưu log thông tin thanh toán
        $mContractLogPayment->add($dataLogPayment);
    }

    /**
     * Kiểm tra hợp đồng sang trạng thái thanh lý
     *
     * @param $infoContract
     * @return array
     */
    protected function _checkStatusLiquidated($infoContract)
    {
        $mContractPayment = app()->get(ContractPaymentTable::class);

        //Lấy giá trị hợp đồng
        $payment = $mContractPayment->getPaymentByContract($infoContract['contract_id']);

        $lastTotalAmount = $payment['last_total_amount'] != null ? floatval($payment['last_total_amount']) : 0;

        if ($infoContract['type'] == 'sell') {
            $mContractMapOrder = app()->get(ContractMapOrderTable::class);
            $mReceipt = app()->get(ReceiptTable::class);

            //Lấy thông tin đơn hàng gần nhất map với hđ
            $getOrder = $mContractMapOrder->getOrderMap($infoContract['contract_code']);

            if ($getOrder == null) {
                return [
                    'error' => true,
                    'message' => __('Hợp đồng chưa có đơn hàng không thể thanh lý')
                ];
            }

            //Lấy tiền đã thanh toán của đơn hàng
            $getAmountPaid = $mReceipt->getReceiptOrder($getOrder['order_id']);

            $amountPaid = $getAmountPaid != null ? floatval($getAmountPaid['amount_paid']) : 0;

            if (($lastTotalAmount - $amountPaid) > 0) {
                return [
                    'error' => true,
                    'message' => __('Hợp đồng chưa được thanh toán hết')
                ];
            }
        } else {
            $mContractSpend = app()->get(ContractSpendTable::class);

            //Lấy tiền đã thu của HĐ
            $getAmountPaid = $mContractSpend->getAmountSpend($infoContract['contract_id']);

            $amountPaid = $getAmountPaid != null ? floatval($getAmountPaid['total_amount']) : 0;

            //Nếu hđ mua thì check đã chi hết tiền chua mới cho update
            if (($lastTotalAmount - $amountPaid) > 0) {
                return [
                    'error' => true,
                    'message' => __('Hợp đồng chưa được thanh toán hết')
                ];
            }
        }

        return [
            'error' => false
        ];
    }

    /**
     * Kiểm tra hợp đồng sang trạng thái huỷ
     *
     * @param $infoContract
     * @return array
     */
    protected function _checkStatusCancel($infoContract)
    {
        if ($infoContract['type'] == 'sell') {
            //Nếu hđ bán thì Kiểm tra đơn hàng có map với hđ chưa, và trạng thái đơn hàng đã thanh toán or thanh toán 1 phần thì ko cho update
            $mContractMapOrder = app()->get(ContractMapOrderTable::class);

            //Lấy thông tin đơn hàng gần nhất map với hđ
            $getOrder = $mContractMapOrder->getOrderMap($infoContract['contract_code']);

            if ($getOrder != null && in_array($getOrder['process_status'], ['paysuccess', 'pay-half'])) {
                return [
                    'error' => true,
                    'message' => __('Hợp đồng này đã được thanh toán, bạn không thể hủy')
                ];
            }
        } else {
            $mPayment = app()->get(PaymentTable::class);
            $mContractSpend = app()->get(ContractSpendTable::class);

            //Nếu hđ mua thì huỷ tất cả phiếu chi nếu có
            $mContractSpend->editByContract([
                'is_deleted' => 1,
                'reason' => __('Huỷ hợp đồng')
            ], $infoContract['contract_id']);

            //Phiếu chi của hợp đồng sẽ bị xoá
            $mPayment->editByContract([
                'status' => 'unpaid'
            ], $infoContract['contract_id']);
        }

        return [
            'error' => false
        ];
    }

    /**
     * Lấy giá trị theo hàng hoá
     *
     * @param $input
     * @return mixed|void
     */
    public function changeValueGoods($input)
    {
        $mContractCategory = app()->get(ContractCategoriesTable::class);
        $mPaymentMethod = app()->get(PaymentMethodTable::class);
        $mPaymentUnit = app()->get(PaymentUnitTable::class);

        //Lấy dữ liệu load động của tab thông tin HĐ
        $data = $this->_loadDataConfigTab($input['category_id']);
        //Lấy thông tin loại HĐ
        $infoCategory = $mContractCategory->getItem($input['category_id']);
        //Lấy option phương thức thanh toán
        $optionPaymentMethod = $mPaymentMethod->getOption();
        //Lấy option đơn vị thanh toán
        $optionPaymentUnit = $mPaymentUnit->getOption();


        $html = \View::make('contract::contract.inc.info.view-payment-load', [
            "tabPayment" => $data['tabPayment'],
            'infoCategory' => $infoCategory,
            'optionPaymentMethod' => $optionPaymentMethod,
            'optionPaymentUnit' => $optionPaymentUnit,
            'is_value_goods' => $input['is_value_goods']
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Lấy trạng thái đơn hàng gần nhất
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function getStatusOrder($input)
    {
        $mContract = app()->get(ContractTable::class);
        $mContractMapOrder = app()->get(ContractMapOrderTable::class);

        //Lấy thông tin HĐ
        $info = $mContract->getInfo($input['contract_id']);
        //Lấy thông tin đơn hàng gần nhất để thanh toán
        $getOrder = $mContractMapOrder->getOrderMap($info['contract_code']);

        return response()->json([
            'infoOrder' => $getOrder
        ]);
    }

    /**
     * Show modal nhập lý do xoá
     *
     * @param $input
     * @return mixed|void
     */
    public function showModalReason($input)
    {
        $html = \View::make('contract::contract.pop.modal-reason-remove', [
            "contract_id" => $input['contract_id'],
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Xoá hợp đồng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function destroy($input)
    {
        DB::beginTransaction();
        try {
            $mContract = app()->get(ContractTable::class);
            $mContractMapOrder = app()->get(ContractMapOrderTable::class);
            $mPayment = app()->get(PaymentTable::class);

            //Lấy thông tin hợp đồng
            $infoContract = $mContract->getInfo($input['contract_id']);

            //Xoá hợp đồng
            $mContract->edit([
                'is_deleted' => 1,
                'reason_remove' => $input['reason']
            ], $infoContract['contract_id']);

            //Kiểm tra đơn hàng đã liên kết với hợp đồng
            $getTotalOrderMap = $mContractMapOrder->getAllContractMapOrder($infoContract['contract_code']);

            if (count($getTotalOrderMap)) {
                $mOrder = app()->get(OrderTable::class);
                $mReceipt = app()->get(ReceiptTable::class);

                foreach ($getTotalOrderMap as $v) {
                    if ($v['source'] == "contract") {
                        //Đơn hàng được tạo từ hợp đồng (xoá đơn hàng và huỷ thanh toán của đơn hàng đó)

                        //Xoá đơn hàng
                        $mOrder->edit([
                            'is_deleted' => 1
                        ], $v['order_id']);

                        //Huỷ các lần thanh toán của đơn hàng đó
                        $mReceipt->editByOrder([
                            'is_deleted' => 1
                        ], $v['order_id']);
                    } else {
                        //Đơn hàng được link về từ đơn hàng có sẵn (huỷ liên kết với đơn hàng đó)
                        $mContractMapOrder->removeContractMapOrder($v['contract_map_order_id']);
                    }
                }
            }

            //Phiếu chi của hợp đồng sẽ bị xoá
            $mPayment->editByContract([
                'is_delete' => 1
            ], $infoContract['contract_id']);

            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Xoá thành công"),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Xoá thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    public function exportExcel($input)
    {
        $heading = [];
        if (json_decode(Cookie::get('arrColumn')) != null) {
            foreach (json_decode(Cookie::get('arrColumn')) as $key => $value) {
                $value = (array)$value;
                switch ($value['key']) {
                    case('stt'):
                        $heading[] = $value['value'];
                        break;
                }
            }
        }
        $heading[] = __('Mã hợp đồng');
        $heading[] = __('Tên hợp đồng');
        if (json_decode(Cookie::get('arrColumn')) != null) {
            foreach (json_decode(Cookie::get('arrColumn')) as $key => $value) {
                $value = (array)$value;
                if ($value['key'] != 'stt') {
                    $heading[] = $value['value'];
                }
            }
        }

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mContract = app()->get(ContractTable::class);
        // get phân quyền data
        $mRoleGroupStaff = app()->get(MapRoleGroupStaffTable::class);
        $lstRoleDataContract = $mRoleGroupStaff->getRoleDataContractByStaffId(auth()->id());
        $groupRoleData = collect($lstRoleDataContract)->groupBy("role_data_type");
        if (count($groupRoleData) > 0) {
            // có phân quyền thì lấy cao nhất (all -> branch -> department)
            if (isset($groupRoleData['department'])) {
                $input['role_data'] = 'department';
            }
            if (isset($groupRoleData['branch'])) {
                $input['role_data'] = 'branch';
            }
            if (isset($groupRoleData['all'])) {
                $input['role_data'] = 'all';
            }
        }
        $lstContract = $mContract->getExport($input);
        foreach ($lstContract as $key => $value) {
            $dataFile = $mContract->getListFileNameOfContract($value['contract_id']);
            $dataGood = $mContract->getListGoodOfContract($value['contract_id']);
            $lstContract[$key]['list_file_name'] = '';
            $lstContract[$key]['list_link'] = '';
            $lstContract[$key]['list_object_name'] = $dataGood['list_object_name'];
            if ($dataFile != null) {
                $lstContract[$key]['list_file_name'] = $dataFile['list_file_name'];
                $lstContract[$key]['list_link'] = $dataFile['list_link'];
            }
        }
        $data = [];
        $allData = $lstContract;
        $myFile = null;
        if (count($allData) > 0) {
            foreach ($allData as $k => $item) {
                $itemData = [];
                if (json_decode(Cookie::get('arrColumn')) != null) {
                    foreach (json_decode(Cookie::get('arrColumn')) as $key1 => $value1) {
                        $value1 = (array)$value1;
                        switch ($value1['key']) {
                            case('stt'):
                                $itemData[] = $k + 1;
                                break;
                        }
                    }
                }
                $itemData[] = $item['contract_code'];
                $itemData[] = $item['contract_name'];

                if (json_decode(Cookie::get('arrColumn')) != null) {
                    foreach (json_decode(Cookie::get('arrColumn')) as $key1 => $value1) {
                        $value1 = (array)$value1;
                        switch ($value1['key']) {
                            case('contract_code'):
                                $itemData[] = $item['contract_code'];
                                break;
                            case('contract_no'):
                                $itemData[] = $item['contract_no'];
                                break;
                            case('contract_name'):
                                $itemData[] = $item['contract_name'];
                                break;
                            case('content'):
                                $itemData[] = $item['content'];
                                break;
                            case('partner_name'):
                                $itemData[] = $item['partner_name'];
                                break;
                            case('customer_group_id'):
                                switch ($item['partner_object_type']) {
                                    case('personal'):
                                        $itemData[] = __('Cá nhân');
                                        break;
                                    case('business'):
                                        $itemData[] = __('Doanh nghiệp');
                                        break;
                                    case('supplier'):
                                        $itemData[] = __('Nhà cung cấp');
                                        break;
                                }
                                break;
                            case('address'):
                                $itemData[] = $item['address'];
                                break;
                            case('representative'):
                                $itemData[] = $item['representative'];
                                break;
                            case('hotline'):
                                $itemData[] = $item['hotline'];
                                break;
                            case('staff_title'):
                                $itemData[] = $item['staff_title_name'];
                                break;
                            case('is_renew'):
                                if ($item['is_renew']) {
                                    $itemData[] = __('Có');
                                } else {
                                    $itemData[] = __('Không');
                                }
                                break;
                            case('phone'):
                                $itemData[] = $item['phone'];
                                break;
                            case('email'):
                                $itemData[] = $item['email'];
                                break;
                            case('goods'):
                                $itemData[] = $item['list_object_name'];
                                break;
                            case('contract_category_id'):
                                $itemData[] = $item['contract_category_name'];
                                break;
                            case('effective_date'):
                                $itemData[] = $item['effective_date'] != "" ? date("d/m/Y", strtotime($item['effective_date'])) : "";
                                break;
                            case('expired_date'):
                                $itemData[] = $item['expired_date'] != "" ? date("d/m/Y", strtotime($item['expired_date'])) : "";
                                break;
                            case('sign_date'):
                                $itemData[] = $item['sign_date'] != "" ? date("d/m/Y", strtotime($item['sign_date'])) : "";
                                break;
                            case('status_code'):
                                $itemData[] = $item['status_name'];
                                break;
                            case('total_amount'):
                                $itemData[] = $item['total_amount'];
                                break;
                            case('tax'):
                                $itemData[] = $item['tax'];
                                break;
                            case('discount'):
                                $itemData[] = $item['discount'];
                                break;
                            case('last_total_amount'):
                                $itemData[] = $item['last_total_amount'];
                                break;
                            case('performer_by'):
                                $itemData[] = $item['staff_performer_name'];
                                break;
                            case('department'):
                                $itemData[] = $item['department_name'];
                                break;
                            case('created_by'):
                                $itemData[] = $item['staff_created_by_name'];
                                break;
                            case('updated_by'):
                                $itemData[] = $item['staff_updated_by_name'];
                                break;
                            case('warranty_start_date'):
                                $itemData[] = $item['warranty_start_date'] != "" ? date("d/m/Y", strtotime($item['warranty_start_date'])) : "";
                                break;
                            case('warranty_end_date'):
                                $itemData[] = $item['warranty_end_date'] != "" ? date("d/m/Y", strtotime($item['warranty_end_date'])) : "";
                                break;
                            case('reason'):
                                $itemData[] = $item['reason'];
                                break;
                            case('contract_file'):
                                $itemData[] = $item['list_link'];
                                break;
                            case('note'):
                                $itemData[] = $item['note'];
                                break;
                        }
                    }
                    $data [] = $itemData;
                }
            }
        }
        return Excel::download(new ExportFile($heading, $data), 'export-detail.xlsx');
    }


    /**
     * get popup create customer quickly
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPopupCustomerQuickly($input)
    {
        $mCustomerGroup = app()->get(CustomerGroupTable::class);
        $mProvince = app()->get(ProvinceTable::class);
        $optionCustomerGroup = $mCustomerGroup->getOption();
        $array = array();
        foreach ($optionCustomerGroup as $item) {
            $array[$item['customer_group_id']] = $item['group_name'];

        }
        $optionProvince = $mProvince->getOptionProvince();
        $listData = array();
        foreach ($optionProvince as $value) {
            $listData[$value['provinceid']] = $value['name'];
        }
        $html = \View::make('contract::contract.pop.add-customer', [
            'optionGroup' => $array,
            'optionProvince' => $listData,
        ])->render();

        return response()->json([
            'html' => $html,
        ]);
    }

    /**
     * submit create customer quickly
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitCustomerQuickly($data)
    {
        $mCustomer = app()->get(CustomerTable::class);
        $mStaff = app()->get(StaffTable::class);
        $staff = $mStaff->getItem(Auth::id());
        $data = [
            'customer_group_id' => $data['customer_group_id'],
            'full_name' => $data['full_name'],
            'branch_id' => $staff['branch_id'],
            'gender' => isset($data['gender']) ? $data['gender'] : 'other',
            'phone1' => $data['phone1'],
            'province_id' => $data['province_id'],
            'district_id' => $data['district_id'],
            'address' => $data['address'],
            'customer_source_id' => 1,
            'is_actived' => 1,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'customer_type' => $data['customer_type'],
            'tax_code' => $data['tax_code'] ?? '',
            'representative' => $data['representative'] ?? '',
            'hotline' => $data['hotline'] ?? '',
        ];
        $test_phone1 = $mCustomer->testPhone($data['phone1'], 0);
        if ($test_phone1 != "") {
            return response()->json([
                'error' => 1,
                'error_phone1' => 1,
                'message' => __('Số điện thoại đã tồn tại')
            ]);
        }
        $customerId = $mCustomer->createData($data);
        // auto create order
        $day_code = date('dmY');
        if ($customerId < 10) {
            $customerId = '0' . $customerId;
        }
        $data_code = [
            'customer_code' => 'KH_' . $day_code . $customerId
        ];
        $mCustomer->updateData($data_code, $customerId);
        $item = $mCustomer->getInfoById($customerId);
        return response()->json([
            'error' => 0,
            'data' => $item
        ]);
    }

    /**
     * submit supplier quickly
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitSupplierQuickly($data)
    {
        $mSupplier = app()->get(SupplierTable::class);
        $name = $data['supplier_name'];
        $checkExist = $mSupplier->checkExist($name, 0);
        if ($checkExist == null) {
            $data = [
                'supplier_name' => $name,
                'address' => $data['address'],
                'contact_name' => $data['contact_name'],
                'contact_phone' => $data['contact_phone'],
                'slug' => str_slug($name)
            ];
            $supplierId = $mSupplier->createData($data);
            $item = $mSupplier->getInfoById($supplierId);
            return response()->json(['error' => 0, 'data' => $item]);
        } else {
            return response()->json(['error' => 1, 'message' => __('Tên nhà cung cấp đã tồn tại')]);
        }
    }

    /**
     * Show modal cập nhật trạng thái HĐ
     *
     * @param $input
     * @return mixed|void
     */
    public function showModalStatus($input)
    {
        $mContract = app()->get(ContractTable::class);
        $mContractStatusUpdate = app()->get(ContractCategoryStatusUpdateTable::class);
        $mContractStatus = app()->get(ContractCategoryStatusTable::class);

        //Lấy thông tin HĐ
        $info = $mContract->getInfo($input['contract_id']);

        //Lấy trạng thái HĐ được cập nhật
        $statusUpdate = [$info['status_code']];
        //Lấy trạng thái được update của hợp đồng
        $getStatusUpdate = $mContractStatusUpdate->getStatusUpdate($info['status_code']);

        if (count($getStatusUpdate) > 0) {
            foreach ($getStatusUpdate as $v) {
                $statusUpdate [] = $v['status_code_update'];
            }
        }

        //Lấy option trạng thái HĐ được update
        $optionStatusUpdate = $mContractStatus->getOptionByCategoryEdit($info['contract_category_id'], $statusUpdate);

        $html = \View::make('contract::contract.pop.modal-update-status', [
            "contract_id" => $input['contract_id'],
            'item' => $info,
            'statusUpdate' => $statusUpdate,
            'optionStatusUpdate' => $optionStatusUpdate
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Cập nhật trạng thái HĐ
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function updateStatus($input)
    {
        DB::beginTransaction();
        try {
            $mContract = app()->get(ContractTable::class);
            $mContractStatus = app()->get(ContractCategoryStatusTable::class);

            //Lấy thông tin HĐ
            $infoContract = $mContract->getInfo($input['contract_id']);

            //Kiểm tra trạng thái có được chỉnh sửa không
            $checkStatusUpdate = $this->_validateStatusUpdate($infoContract, $input['status_code']);

            if ($checkStatusUpdate == false) {
                return response()->json([
                    "error" => true,
                    "message" => __("Trạng thái không được phép cập nhật")
                ]);
            }

            //Kiểm tra trạng thái có chờ duyệt không
            $infoStatusOld = $mContractStatus->getStatusNameByCode($infoContract['status_code']);

            if ($infoStatusOld['is_approve'] == 1) {
                $mContractBrowser = app()->get(ContractBrowserTable::class);
                //Gửi yêu cầu duyệt
                $mContractBrowser->add([
                    'contract_id' => $infoContract['contract_id'],
                    'status_code_now' => $infoContract['status_code'],
                    'status_code_new' => $input['status_code'],
                    'request_by' => Auth()->id()
                ]);
                //Gửi thông báo yêu cầu duyệt
                $mContractRepo = app()->get(ContractRepoInterface::class);
                $mContractRepo->saveContractNotification('need_approved', $infoContract['contract_id']);


                //Cập nhật cờ chờ duyệt của HĐ
                $mContract->edit([
                    'is_browse' => 1
                ], $input['contract_id']);

                DB::commit();

                return response()->json([
                    "error" => false,
                    "message" => __("Gửi yêu cầu duyệt trạng thái thành công"),
                ]);
            }

            //Chỉnh sửa thông tin HĐ
            $mContract->edit([
                'status_code' => $input['status_code']
            ], $input['contract_id']);

            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Thay đổi trạng thái thành công"),
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => true,
                "message" => __("Thay đổi trạng thái thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Show modal import HĐ
     *
     * @return mixed|void
     */
    public function showModalImport()
    {
        $html = \View::make('contract::contract.pop.modal-import-excel')->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Import file HĐ
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function importExcel($input)
    {
        try {
            if (isset($input['file'])) {
                $typeFileExcel = $input['file']->getClientOriginalExtension();

                if ($typeFileExcel != "xlsx") {
                    return response()->json([
                        'error' => true,
                        'message' => __('File không đúng định dạng')
                    ]);
                }

                $reader = ReaderFactory::create(Type::XLSX);
                $reader->open($input['file']);

                //Khai báo model
                $mContract = app()->get(ContractTable::class);
                $mContractFollowMap = app()->get(ContractFollowMapTable::class);
                $mContractSignMap = app()->get(ContractSignMapTable::class);
                $mContractPartner = app()->get(ContractPartnerTable::class);
                $mContractCategory = app()->get(ContractCategoriesTable::class);
                $mContractCategoryStatus = app()->get(ContractCategoryStatusTable::class);

                $arrError = [];
                $numberSuccess = 0;
                $numberError = 0;

                //Sẽ trả về các object gồm các sheet
                foreach ($reader->getSheetIterator() as $sheet) {
                    //Đọc từng dòng trong 1 sheet
                    foreach ($sheet->getRowIterator() as $key => $row) {
                        if ($key != 1) {
                            $contractNo = isset($row[0]) ? $row[0] : '';
                            $contractName = isset($row[1]) ? $row[1] : '';
                            $contractCategoryName = isset($row[2]) ? $row[2] : '';
                            $partnerType = isset($row[3]) ? $row[3] : '';
                            $partnerName = isset($row[4]) ? $row[4] : '';
                            $partnerPhone = isset($row[5]) ? $row[5] : '';
                            $signDate = isset($row[6]) ? $row[6] : ''; //Ngày ký HĐ
                            $effectiveDate = isset($row[7]) ? $row[7] : ''; //Ngày có hiệu lực
                            $expiredDate = isset($row[8]) ? $row[8] : ''; //Ngày hết hiệu lực
                            $performerBy = isset($row[9]) ? $row[9] : ''; //Người thực hiện
                            $signBy = isset($row[10]) ? $row[10] : ''; //Người ký
                            $followBy = isset($row[11]) ? $row[11] : ''; //Người theo dõi
                            $warrantyStartDate = isset($row[12]) ? $row[12] : ''; //Ngày bắt đầu bảo hành
                            $warrantyEndDate = isset($row[13]) ? $row[13] : ''; //Ngày kết thúc bảo hành

                            //Lưu log lỗi có gì xuất excel
                            $errorRow = [
                                'contract_no' => $contractNo,
                                'contract_name' => $contractName,
                                'contract_category_name' => $contractCategoryName,
                                'partner_type' => $partnerType,
                                'partner_name' => $partnerName,
                                'partner_phone' => $partnerPhone,
                                'sign_date' => $signDate,
                                'effective_date' => $effectiveDate,
                                'expired_date' => $expiredDate,
                                'performer_by' => $performerBy,
                                'sign_by' => $signBy,
                                'follow_by' => $followBy,
                                'warranty_start_date' => $warrantyStartDate,
                                'warranty_end_date' => $warrantyEndDate,
                                'error' => ''
                            ];

                            //Validate các trường import của HĐ
                            $validate = $this->_validateImportExcel([
                                'contract_no' => $contractNo,
                                'contract_name' => $contractName,
                                'contract_category_name' => $contractCategoryName,
                                'partner_type' => $partnerType,
                                'partner_name' => $partnerName,
                                'partner_phone' => $partnerPhone,
                                'sign_date' => $signDate,
                                'effective_date' => $effectiveDate,
                                'expired_date' => $expiredDate,
                                'performer_by' => $performerBy,
                                'sign_by' => $signBy,
                                'follow_by' => $followBy,
                                'warranty_start_date' => $warrantyStartDate,
                                'warranty_end_date' => $warrantyEndDate,
                            ]);

                            $errorRow['error'] = $validate['error'];
                            //Lấy loại HĐ
                            $getCategory = $mContractCategory->getCategoryByName($contractCategoryName);

                            if ($getCategory == null) {
                                $errorRow['error'] .= __('Loại hợp đồng không tồn tại');
                            }
                            //Lấy loại đối tác
                            if (!in_array($partnerType, [__("Cá nhân"), __("Doanh nghiệp"), __("Nhà cung cấp")])) {
                                $errorRow['error'] .= __('Loại đối tác không tồn tại');
                            }

                            $partnerObjectType = "";
                            $partnerObjectId = "";
                            $partnerAddress = null;
                            $partnerEmail = null;
                            $representative = null;

                            switch ($partnerType) {
                                case __('Cá nhân'):
                                    $partnerObjectType = "personal";
                                    break;
                                case __('Doanh nghiệp'):
                                    $partnerObjectType = "business";
                                    break;
                                case __('Nhà cung cấp'):
                                    $partnerObjectType = "supplier";
                                    break;
                            }

                            //Lấy đối tác
                            if (in_array($partnerObjectType, ['personal', 'business'])) {
                                $mCustomer = app()->get(CustomerTable::class);

                                $getCustomer = $mCustomer->getCustomerByPhone($partnerPhone);

                                if ($getCustomer == null) {
                                    $errorRow['error'] .= __('Đối tác không tồn tại');
                                } else {
                                    $partnerObjectId = $getCustomer['customer_id'];
                                    $partnerAddress = $getCustomer['address'];
                                    $partnerEmail = $getCustomer['email'];

                                    if ($getCustomer['district_name'] != null) {
                                        $partnerAddress .= ', ' . $getCustomer['district_name'];
                                    }

                                    if ($getCustomer['province_name'] != null) {
                                        $partnerAddress .= ', ' . $getCustomer['province_name'];
                                    }
                                }
                            } else if ($partnerObjectType == "supplier") {
                                $mSupplier = app()->get(SupplierTable::class);

                                $getSupplier = $mSupplier->getInfoByName($partnerName);

                                if ($getSupplier == null) {
                                    $errorRow['error'] .= __('Đối tác không tồn tại');
                                } else {
                                    $partnerObjectId = $getSupplier['id'];
                                    $partnerAddress = $getSupplier['address'];
                                    $representative = $getSupplier['contact_name'];
                                }
                            }

                            if (!empty($errorRow['error'])) {
                                $numberError++;
                                $arrError [] = $errorRow;
                                continue;
                            }

                            //Lấy trạng thái nháp của loại HĐ
                            $getStatus = $mContractCategoryStatus->getStatusDraft($getCategory['contract_category_id']);

                            //Thêm HĐ
                            $contractId = $mContract->add([
                                'contract_no' => $contractNo,
                                'contract_name' => $contractName,
                                'contract_category_id' => $getCategory['contract_category_id'],
                                'performer_by' => $validate['performer_by'],
                                'sign_date' => Carbon::createFromFormat('d/m/Y', $signDate)->format('Y-m-d'),
                                'effective_date' => Carbon::createFromFormat('d/m/Y', $effectiveDate)->format('Y-m-d'),
                                'expired_date' => Carbon::createFromFormat('d/m/Y', $expiredDate)->format('Y-m-d'),
                                'warranty_start_date' => Carbon::createFromFormat('d/m/Y', $warrantyStartDate)->format('Y-m-d'),
                                'warranty_end_date' => Carbon::createFromFormat('d/m/Y', $warrantyEndDate)->format('Y-m-d'),
                                'status_code' => $getStatus['status_code'],
                                'created_by' => Auth()->id(),
                                'updated_by' => Auth()->id()
                            ]);
                            //Update mã HĐ
                            $contractCode = $getCategory['contract_code_format'] . sprintf("%04d", $contractId);

                            $mContract->edit([
                                "contract_code" => $contractCode
                            ], $contractId);

                            $arrFollow = [];

                            if (count($validate['follow_by']) > 0) {
                                foreach ($validate['follow_by'] as $v) {
                                    $arrFollow [] = [
                                        "contract_id" => $contractId,
                                        "follow_by" => $v
                                    ];
                                }
                            }
                            //Thêm người theo dõi
                            $mContractFollowMap->insert($arrFollow);

                            $arrSign = [];
                            if (count($validate['sign_by']) > 0) {
                                foreach ($validate['sign_by'] as $v) {
                                    $arrSign [] = [
                                        "contract_id" => $contractId,
                                        "sign_by" => $v
                                    ];
                                }
                            }
                            //Thêm người ký
                            $mContractSignMap->insert($arrSign);


                            //Thêm thông tin đối tác HĐ
                            $mContractPartner->add([
                                'contract_id' => $contractId,
                                'partner_object_type' => $partnerObjectType,
                                'partner_object_id' => $partnerObjectId,
                                'address' => $partnerAddress,
                                'email' => $partnerEmail,
                                'representative' => $representative
                            ]);

                            //Thành công
                            $numberSuccess++;
                        }
                    }
                }

                return response()->json([
                    'error' => false,
                    'message' => __('Số dòng thành công') . ':' . $numberSuccess . '<br/>' . __('Số dòng thất bại') . ':' . $numberError,
                    'number_error' => $numberError,
                    'data_error' => $arrError
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => __("Import file hợp đồng thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Validate import HĐ
     *
     * @param $input
     * @return string
     */
    private function _validateImportExcel($input)
    {
        $mContract = app()->get(ContractTable::class);
        $mStaff = app()->get(StaffTable::class);

        $error = '';
        $performer_by = '';
        $sign_by = [];
        $follow_by = [];

        //Validate số HĐ
        if ($input['contract_no'] == '') {
            $error .= __('Số hợp đồng không được trống') . ';';
        } else {
            //Kiểm tra số HĐ đã tồn tại chưa
            $checkContractNo = $mContract->checkExistContractNo($input['contract_no']);

            if ($checkContractNo != null) {
                $error .= __('Số hợp đồng đã tồn tại') . ';';
            }
        }

        //Validate tên HĐ
        if ($input['contract_name'] == '') {
            $error .= __('Tên hợp đồng không được trống') . ';';
        }

        if (strlen($input['contract_name']) > 190) {
            $error .= __('Tên hợp đồng tối đa 190 kí tự') . ';';
        }

        //Validate loại HĐ
        if ($input['contract_category_name'] == '') {
            $error .= __('Loại hợp đồng không được trống') . ';';
        }

        //Validate loại đối tác
        if ($input['partner_type'] == '') {
            $error .= __('Loại đối tác không được trống') . ';';
        }

        //Validate tên đối tác
        if ($input['partner_name'] == '') {
            $error .= __('Tên đối tác không được trống') . ';';
        }

        //Validate sđt đối tác + định dạng
        if ($input['partner_phone'] == '') {
            $error .= __('Số điện thoại không được trống') . ';';
        } else {
            //Kiểm tra định dạng sđt
            $checkFormatPhone = $this->checkPhoneNumberVN($input['partner_phone'], $resPhone);

            if ($checkFormatPhone == false) {
                $error .= __('Số điện thoại không hợp lệ') . ';';
            }
        }

        //Validate format ngày ký HĐ
        if ($input['sign_date'] != '') {
            $checkFormatDate = $this->validateDate($input['sign_date']);

            if ($checkFormatDate == false) {
                $error .= __('Ngày ký không đúng định dạng') . ';';
            }
        }

        //Validate format ngày có hiệu lực
        if ($input['effective_date'] != '') {
            $checkFormatDate = $this->validateDate($input['effective_date']);

            if ($checkFormatDate == false) {
                $error .= __('Ngày có hiệu lực không đúng định dạng') . ';';
            }
        }

        //Validate format ngày hết hiệu lực
        if ($input['expired_date'] != '') {
            $checkFormatDate = $this->validateDate($input['expired_date']);

            if ($checkFormatDate == false) {
                $error .= __('Ngày hết hiệu lực không đúng định dạng') . ';';
            }
        }

        //Kiểm tra người thực hiện có tồn tại không
        if ($input['performer_by'] != '') {
            $checkPerformerBy = $mStaff->getInfoByName($input['performer_by']);

            if ($checkPerformerBy == null) {
                $error .= __('Người thực hiện không tồn tại') . ';';
            } else {
                $performer_by = $checkPerformerBy['staff_id'];
            }
        }

        //Kiểm tra người ký có tồn tại không
        if ($input['sign_by'] != '') {
            $signBy = explode(",", $input['sign_by']);

            if (count($signBy) > 0) {
                foreach ($signBy as $v) {
                    $checkSignBy = $mStaff->getInfoByName($v);

                    if ($checkSignBy == null) {
                        $error .= $v . ' ' . __('người ký không tồn tại') . ';';
                    } else {
                        $sign_by [] = $checkSignBy['staff_id'];
                    }
                }
            }
        }

        //Kiểm tra người theo dõi có tồn tại không
        if ($input['follow_by'] != '') {
            $followBy = explode(",", $input['follow_by']);

            if (count($followBy) > 0) {
                foreach ($followBy as $v) {
                    $checkFollowBy = $mStaff->getInfoByName($v);

                    if ($checkFollowBy == null) {
                        $error .= $v . ' ' . __('người theo dõi không tồn tại') . ';';
                    } else {
                        $follow_by [] = $checkFollowBy['staff_id'];
                    }
                }
            }
        }

        //Validate format ngày bắt đầu bảo hành
        if ($input['warranty_start_date'] != '') {
            $checkFormatDate = $this->validateDate($input['warranty_start_date']);

            if ($checkFormatDate == false) {
                $error .= __('Ngày bắt đầu bảo hành không đúng định dạng') . ';';
            }
        }
        //Validate format ngày kết thúc bảo hành
        if ($input['warranty_end_date'] != '') {
            $checkFormatDate = $this->validateDate($input['warranty_end_date']);

            if ($checkFormatDate == false) {
                $error .= __('Ngày hết hạn bảo hành không đúng định dạng') . ';';
            }
        }

        return [
            'error' => $error,
            'performer_by' => $performer_by,
            'sign_by' => $sign_by,
            'follow_by' => $follow_by
        ];
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
     * Validate date
     *
     * @param $date
     * @param string $format
     * @return bool
     */
    private function validateDate($date, $format = 'd/m/Y')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    /**
     * Xuất file lỗi khi import HĐ
     *
     * @param $input
     * @return mixed|void
     */
    public function exportError($input)
    {
        $header = [
            __('SỐ HỢP ĐỒNG (*)'),
            __('TÊN HỢP ĐỒNG  (*)'),
            __('LOẠI HỢP ĐỒNG  (*)'),
            __('LOẠI ĐỐI TÁC  (*)'),
            __('TÊN ĐỐI TÁC  (*)'),
            __('SĐT ĐỐI TÁC (*)'),
            __('NGÀY KÝ HỢP ĐỒNG'),
            __('NGÀY CÓ HIỆU LỰC'),
            __('NGÀY HẾT HIỆU LỰC'),
            __('NGƯỜI THỰC HIỆN'),
            __('NGƯỜI KÝ'),
            __('NGƯỜI THEO DÕI'),
            __('NGÀY BẮT ĐẦU BẢO HÀNH'),
            __('NGÀY KẾT THÚC BẢO HÀNH'),
            __('LỖI')
        ];

        $data = [];

        if (isset($input['contract_no']) && count($input['contract_no']) > 0) {
            foreach ($input['contract_no'] as $k => $v) {
                $data [] = [
                    isset($v) ? $v : '',
                    isset($input['contract_name'][$k]) ? $input['contract_name'][$k] : '',
                    isset($input['contract_category_name'][$k]) ? $input['contract_category_name'][$k] : '',
                    isset($input['partner_type'][$k]) ? $input['partner_type'][$k] : '',
                    isset($input['partner_name'][$k]) ? $input['partner_name'][$k] : '',
                    isset($input['partner_phone'][$k]) ? $input['partner_phone'][$k] : '',
                    isset($input['sign_date'][$k]) ? $input['sign_date'][$k] : '',
                    isset($input['effective_date'][$k]) ? $input['effective_date'][$k] : '',
                    isset($input['expired_date'][$k]) ? $input['expired_date'][$k] : '',
                    isset($input['performer_by'][$k]) ? $input['performer_by'][$k] : '',
                    isset($input['sign_by'][$k]) ? $input['sign_by'][$k] : '',
                    isset($input['follow_by'][$k]) ? $input['follow_by'][$k] : '',
                    isset($input['warranty_start_date'][$k]) ? $input['warranty_start_date'][$k] : '',
                    isset($input['warranty_end_date'][$k]) ? $input['warranty_end_date'][$k] : '',
                    isset($input['error'][$k]) ? $input['error'][$k] : '',
                ];
            }
        }

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        return Excel::download(new ExportFile($header, $data), 'error-contract.xlsx');
    }

    /**
     * function lưu vào (contract_staff_queue, notification_detail) và staff_email_log
     *
     * @param $key : case để lấy data insert nội dung
     * @param $contractId : để lấy staff và các thông tin liên quan
     * @param string $tab
     */
    public function saveContractNotification($key, $contractId, $tab = '')
    {
        $mContract = app()->get(ContractTable::class);
        $mContractSignMap = app()->get(ContractSignMapTable::class);
        $mContractFollowMap = app()->get(ContractFollowMapTable::class);
        $mContractNotifyConfig = app()->get(ContractNotifyConfigTable::class);
        $mStaff = app()->get(StaffTable::class);
        $mNotificationDetail = app()->get(StaffNotificationDetailTable::class);
        $mContractStaffQueue = app()->get(ContractStaffQueueTable::class);
        $mStaffEmailLog = app()->get(StaffEmailLogTable::class);
        $mContractBrowse = app()->get(ContractBrowserTable::class);
        $mContractCategoryStatusApprove = app()->get(ContractCategoryStatusApproveTable::class);
        $mRoleStaff = new \Modules\Contract\Models\MapRoleGroupStaffTable();


        // all info contract
        $info = $mContract->getInfo($contractId);
        // make_request => gửi yêu cầu
        if ($key != 'make_request') {
            // data sign map
            $lstSignMap = $mContractSignMap->getSignMapByContract($contractId);
            $staffNameSignMap = $mContractSignMap->getStaffNameSignMap($contractId);
            // data follow map
            $lstFollowMap = $mContractFollowMap->getFollowMapByContract($contractId);
            $staffNameFollowMap = $mContractFollowMap->getStaffNameFollowMap($contractId);
            // $key để bắt case gửi thông báo (content, sent type, staff type)
            $infoConfig = $mContractNotifyConfig->getItem($key);
            // get content & params replace
            $content = $infoConfig['contract_notify_config_content'];
            $staffCreated = $mStaff->getInfo($info['created_by']);
            $staffUpdated = $mStaff->getInfo($info['updated_by']);
            $staffPerformer = $mStaff->getInfo($info['performer_by']);
            $staffSignBy = $staffNameSignMap != null ? $staffNameSignMap['list_name'] : '';
            $staffFollower = $staffNameFollowMap != null ? $staffNameFollowMap['list_name'] : '';
            // replace text able to replace
            $content = str_replace('{creator}', $staffCreated['full_name'], $content);
            $content = str_replace('{updater}', $staffUpdated['full_name'], $content);
            $content = str_replace('{performer}', isset($staffPerformer['full_name']) ? $staffPerformer['full_name'] : '', $content);
            $content = str_replace('{signed_by}', $staffSignBy, $content);
            $content = str_replace('{follower}', $staffFollower, $content);
            $content = str_replace('{contract_code}_{contract_title}', $info['contract_code'] . '_' . $info['contract_name'], $content);
            if ($tab != '') {
                $content = str_replace('{tab_contract}', $tab, $content);
            }
            // {approve_by}
            $mDataApprove = $mContractBrowse->getInfoByContract($contractId, $info['status_code']);
            if ($mDataApprove != null) {
                if (isset($mDataApprove['updated_by']) && $mDataApprove['updated_by'] != '') {
                    $staffApprove = $mStaff->getInfo($mDataApprove['updated_by']);
                    $content = str_replace('{approve_by}', $staffApprove['full_name'], $content);
                    $content = str_replace('{reason_deny}', $mDataApprove['reason_refuse'], $content);
                }
            }

            // {reason_deny}

            // data notification detail
            $infoConfig['detail_action_params'] = str_replace('[:contract_id]', $contractId, $infoConfig['detail_action_params']);
            $dataNotificationDetail = [
                'tenant_id' => '',
                'background' => '',
                'action_name' => $infoConfig['detail_action_name'],
                'action' => $infoConfig['detail_action'],
                'action_params' => $infoConfig['detail_action_params'],
                'is_brand' => 1,
                'created_by' => Auth::id(),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            // data contract staff queue
            $dataContractStaffQueue = [
                'tenant_id' => '',
                'contract_id' => $contractId,
                'staff_notification_title' => $infoConfig['contract_notify_config_name'],
                'send_at' => date('Y-m-d H:i:s'),
                'is_actived' => 1,
                'is_send' => 0,
                'created_by' => Auth::id(),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            // data staff email log
            $dataStaffEmailLog = [
                'email_type' => 'contract_notify',
                'email_subject' => $infoConfig['contract_notify_config_name'],
                'email_from' => env('MAIL_USERNAME'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // xử lý nếu gửi cho người tạo
            if ($infoConfig['is_created_by']) {
                $contentCreatedBy = str_replace('{receiver}', $staffCreated['full_name'], $content);
                $dataNotificationDetail['content'] = $contentCreatedBy;
                if (str_contains($infoConfig['notify_method'], 'notify')) {
                    $idNotifyDetail = $mNotificationDetail->createNotiDetail($dataNotificationDetail);
                    $dataContractStaffQueue['staff_notification_detail_id'] = $idNotifyDetail;
                    $dataContractStaffQueue['staff_notification_message'] = $contentCreatedBy;
                    $dataContractStaffQueue['staff_id'] = $staffCreated['staff_id'];
                    $mContractStaffQueue->createContractStaffQueue($dataContractStaffQueue);
                }
                if (str_contains($infoConfig['notify_method'], 'email') && $staffCreated['email'] != '') {
                    $dataStaffEmailLog['email_to'] = $staffCreated['email'];
                    $dataStaffEmailLog['email_params'] = json_encode([
                        'content' => $contentCreatedBy,
                        'title' => $infoConfig['contract_notify_config_name']
                    ]);
                    $mStaffEmailLog->createStaffEmailLog($dataStaffEmailLog);
                }
            }
            // xử lý nếu gửi cho người thực hiện
            if ($infoConfig['is_performer_by'] && $staffPerformer != null) {
                $contentPerformerBy = str_replace('{receiver}', $staffPerformer['full_name'], $content);
                $dataNotificationDetail['content'] = $contentPerformerBy;
                if (str_contains($infoConfig['notify_method'], 'notify')) {
                    $idNotifyDetail = $mNotificationDetail->createNotiDetail($dataNotificationDetail);
                    $dataContractStaffQueue['staff_notification_detail_id'] = $idNotifyDetail;
                    $dataContractStaffQueue['staff_notification_message'] = $contentPerformerBy;
                    $dataContractStaffQueue['staff_id'] = $staffPerformer['staff_id'];
                    $idQueue = $mContractStaffQueue->createContractStaffQueue($dataContractStaffQueue);
                }
                if (str_contains($infoConfig['notify_method'], 'email') && $staffPerformer['email'] != '') {
                    $dataStaffEmailLog['email_to'] = $staffPerformer['email'];
                    $dataStaffEmailLog['email_params'] = json_encode([
                        'content' => $contentPerformerBy,
                        'title' => $infoConfig['contract_notify_config_name']
                    ]);
                    $mStaffEmailLog->createStaffEmailLog($dataStaffEmailLog);
                }
            }
            // xử lý nếu gửi cho người theo dõi
            if ($infoConfig['is_follow_by']) {
                foreach ($lstFollowMap as $k => $v) {
                    $contentFollower = str_replace('{receiver}', $v['full_name'], $content);
                    $dataNotificationDetail['content'] = $contentFollower;
                    if (str_contains($infoConfig['notify_method'], 'notify')) {
                        $idNotifyDetail = $mNotificationDetail->createNotiDetail($dataNotificationDetail);
                        $dataContractStaffQueue['staff_notification_detail_id'] = $idNotifyDetail;
                        $dataContractStaffQueue['staff_notification_message'] = $contentFollower;
                        $dataContractStaffQueue['staff_id'] = $v['staff_id'];
                        $mContractStaffQueue->createContractStaffQueue($dataContractStaffQueue);
                    }
                    if (str_contains($infoConfig['notify_method'], 'email') && $v['email'] != '') {
                        $dataStaffEmailLog['email_to'] = $v['email'];
                        $dataStaffEmailLog['email_params'] = json_encode([
                            'content' => $contentFollower,
                            'title' => $infoConfig['contract_notify_config_name']
                        ]);
                        $mStaffEmailLog->createStaffEmailLog($dataStaffEmailLog);
                    }

                }
            }
            // xử lý nếu gửi cho người ký
            if ($infoConfig['is_signer_by']) {
                foreach ($lstSignMap as $k => $v) {
                    $contentSign = str_replace('{receiver}', $v['full_name'], $content);
                    $dataNotificationDetail['content'] = $contentSign;
                    if (str_contains($infoConfig['notify_method'], 'notify')) {
                        $idNotifyDetail = $mNotificationDetail->createNotiDetail($dataNotificationDetail);
                        $dataContractStaffQueue['staff_notification_detail_id'] = $idNotifyDetail;
                        $dataContractStaffQueue['staff_notification_message'] = $contentSign;
                        $dataContractStaffQueue['staff_id'] = $v['staff_id'];
                        $mContractStaffQueue->createContractStaffQueue($dataContractStaffQueue);
                    }
                    if (str_contains($infoConfig['notify_method'], 'email') && $v['email'] != '') {
                        $dataStaffEmailLog['email_to'] = $v['email'];
                        $dataStaffEmailLog['email_params'] = json_encode([
                            'content' => $contentSign,
                            'title' => $infoConfig['contract_notify_config_name']
                        ]);
                        $mStaffEmailLog->createStaffEmailLog($dataStaffEmailLog);
                    }

                }
            }
        } else {
            //Lấy cấu hình thông báo hđ cần phê duyệt
            $configNeedApproved = $mContractNotifyConfig->getItem($key);

            $content = $configNeedApproved['contract_notify_config_content'];
            $content = str_replace('{contract_code}_{contract_title}', $info['contract_code'] . '_' . $info['contract_name'], $content);

            $configNeedApproved['detail_action_params'] = str_replace('[:contract_id]', $contractId, $configNeedApproved['detail_action_params']);

            // data notification detail
            $dataNotificationDetail = [
                'tenant_id' => '',
                'background' => '',
                'action_name' => $configNeedApproved['detail_action_name'],
                'action' => $configNeedApproved['detail_action'],
                'action_params' => $configNeedApproved['detail_action_params'],
                'is_brand' => 1,
                'created_by' => Auth::id(),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            // data contract staff queue
            $dataContractStaffQueue = [
                'tenant_id' => '',
                'contract_id' => $contractId,
                'staff_notification_title' => $configNeedApproved['contract_notify_config_name'],
                'send_at' => date('Y-m-d H:i:s'),
                'is_actived' => 1,
                'is_send' => 0,
                'created_by' => Auth::id(),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            // data staff email log
            $dataStaffEmailLog = [
                'email_type' => 'contract_notify',
                'email_subject' => $configNeedApproved['contract_notify_config_name'],
                'email_from' => Auth()->user()->email,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            // lấy người duyệt của hợp đồng
            $mDataApprove = $mContractBrowse->getInfoByContract($contractId, $info['status_code']);
            $dataRoleApprove = $mContractCategoryStatusApprove->getDetailStatusApprove($mDataApprove['status_code_now']);
            foreach ($dataRoleApprove as $k => $v) {
                // get list staff by role
                $lstStaff = $mRoleStaff->getListStaffByRoleGroup($v['approve_by']);
                foreach ($lstStaff as $k1 => $v1) {
                    // save noti
                    $dataNotificationDetail['content'] = $content;
                    $idNotifyDetail = $mNotificationDetail->createNotiDetail($dataNotificationDetail);
                    $dataContractStaffQueue['staff_notification_detail_id'] = $idNotifyDetail;
                    $dataContractStaffQueue['staff_notification_message'] = $content;
                    $dataContractStaffQueue['staff_id'] = $v1['staff_id'];
                    $mContractStaffQueue->createContractStaffQueue($dataContractStaffQueue);
                    // save email
                    if ($v1['email'] != '') {
                        $dataStaffEmailLog['email_to'] = $v1['email'];
                        $dataStaffEmailLog['email_params'] = json_encode([
                            'content' => $content,
                            'title' => __('Hợp đồng được đánh dấu duyệt')
                        ]);
                        $mStaffEmailLog->createStaffEmailLog($dataStaffEmailLog);
                    }
                }
            }

        }
    }

    /**
     * Đồng bộ template HĐ
     *
     * @return mixed|void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function syncTemplateContract()
    {
        $mContractCategoryConfigTab = app()->get(ContractCategoryConfigTabTable::class);
        $mContractConfigTab = app()->get(ContractConfigTabTable::class);
        $mContract = app()->get(ContractTable::class);

        //Lấy danh sách HĐ
        $getContract = $mContract->getAllContract();

        if (count($getContract) > 0) {
            foreach ($getContract as $contract) {
                //Lấy template loại HĐ để lưu template cho HĐ thời điểm hiện tại
                $getTemplate = $mContractCategoryConfigTab->getConfigTabByCategory($contract['contract_category_id']);

                $arrayTemplate = [];

                if (count($getTemplate) > 0) {
                    foreach ($getTemplate as $v) {
                        $arrayTemplate [] = [
                            'contract_id' => $contract['contract_id'],
                            'tab' => $v['tab'],
                            'key' => $v['key'],
                            'type' => $v['type'],
                            'key_name' => $v['key_name'],
                            'is_default' => $v['is_default'],
                            'is_show' => $v['is_show'],
                            'is_validate' => $v['is_validate'],
                            'number_col' => $v['number_col']
                        ];
                    }
                }

                //Xoá template HĐ cũ trước khi insert vào
                $mContractConfigTab->removeConfigTabByContract($contract['contract_id']);
                //Insert template cho HĐ
                $mContractConfigTab->insert($arrayTemplate);
            }
        }

        echo 'Đồng bộ thành công';
    }
}