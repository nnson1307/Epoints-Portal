<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 3/2/2021
 * Time: 2:49 PM
 */

namespace Modules\Warranty\Repository\Maintenance;


use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Warranty\Http\Api\PaymentOnline;
use Modules\Warranty\Models\CustomerTable;
use Modules\Warranty\Models\MaintenanceCostTable;
use Modules\Warranty\Models\MaintenanceCostTypeTable;
use Modules\Warranty\Models\MaintenanceImageTable;
use Modules\Warranty\Models\MaintenanceTable;
use Modules\Warranty\Models\ParameterVnPayTable;
use Modules\Warranty\Models\PaymentMethodTable;
use Modules\Warranty\Models\ProductChildTable;
use Modules\Warranty\Models\ReceiptDetailTable;
use Modules\Warranty\Models\ReceiptOnlineTable;
use Modules\Warranty\Models\ReceiptTable;
use Modules\Warranty\Models\ServiceCardTable;
use Modules\Warranty\Models\ServiceTable;
use Modules\Warranty\Models\StaffTable;
use Modules\Warranty\Models\WarrantyCardTable;
use Modules\Warranty\Models\WarrantyImageTable;

class MaintenanceRepo implements MaintenanceRepoInterface
{
    protected $maintenance;

    public function __construct(
        MaintenanceTable $maintenance
    )
    {
        $this->maintenance = $maintenance;
    }

    const PER_PAGE = 10;
    const MAINTENANCE = "maintenance";
    const FINISH = 'finish';

    /**
     * Danh sách phiếu bảo trì điện tử
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->maintenance->getList($filters);

        if (count($list->items()) > 0) {
            $mReceipt = app()->get(ReceiptTable::class);

            foreach ($list->items() as $item) {
                $totalReceipt = 0;
                //Lấy thông tin thanh toán phiếu bảo trì
                $getReceipt = $mReceipt->getReceipt(self::MAINTENANCE, $item['maintenance_id']);
                if (count($getReceipt) > 0) {
                    foreach ($getReceipt as $v) {
                        $totalReceipt += $v['amount_paid'];
                    }
                }
                $item['total_receipt'] = $totalReceipt;
            }
        }

        return [
            'list' => $list
        ];
    }

    /**
     * Load data view thêm phiếu bảo trì
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function dataViewCreate($input)
    {
        $mStaff = app()->get(StaffTable::class);
        $mCustomer = app()->get(CustomerTable::class);
        $mCostType = app()->get(MaintenanceCostTypeTable::class);

        $dataLoad = [];

        if (isset($input['warranty_code'])) {
            $mWarrantyCard = app()->get(WarrantyCardTable::class);

            //Lấy thông tin phiếu bảo hành
            $getInfo = $mWarrantyCard->getInfo($input['warranty_code']);

            $dataLoad['customer_code'] = $getInfo['customer_code'];
            $dataLoad['warranty_card_code'] = $getInfo['warranty_card_code'];
            $dataLoad['info'] = $getInfo;

            session()->put('warranty_choose', $getInfo['warranty_card_code']);
        }
        //Lấy option nv thực hiện
        $optionStaff = $mStaff->getStaff();
        //Lấy option khách hàng
        $optionCustomer = $mCustomer->getCustomer();
        //Lấy option chi phí phát sinh
        $optionCostType = $mCostType->getCostType();

        return [
            'optionStaff' => $optionStaff,
            'optionCustomer' => $optionCustomer,
            'optionCostType' => $optionCostType,
            'dataLoad' => $dataLoad
        ];
    }

    /**
     * Chọn phiếu bảo hành
     *
     * @param $input
     * @return mixed|void
     */
    public function chooseWarranty($input)
    {
        session()->put('temp', $input['warranty_code']);
    }

    /**
     * Load đối tượng khi loại đối tượng thay đổi
     *
     * @param $input
     * @return mixed|void
     */
    public function loadObject($input)
    {
        $mProduct = new ProductChildTable();
        $mService = new ServiceTable();
        $mServiceCard = new ServiceCardTable();

        $data = [];

        if ($input['object_type'] == 'product') {
            $input['search_keyword'] = isset($input['search']) ? $input['search'] : '';

            unset($input['search'], $input['type']);

            $data = $mProduct->getListProduct($input);


        } else if ($input['object_type'] == 'service') {
            unset($input['type']);

            $data = $mService->getListService($input);
        } else if ($input['object_type'] == 'service_card') {
            $input['search_keyword'] = isset($input['search']) ? $input['search'] : '';
            unset($input['search'], $input['type']);

            $data = $mServiceCard->getListServiceCard($input);
        }

        return [
            'items' => $data->items(),
            'pagination' => range($data->currentPage(),
                $data->lastPage()) ? true : false
        ];
    }

    /**
     * Show modal chọn phiếu bảo hành
     *
     * @param $input
     * @return mixed|void
     */
    public function modalWarranty($input)
    {
        $mWarrantyCard = app()->get(WarrantyCardTable::class);
        //Lấy danh sách phiếu bảo hành
        $listWarranty = $this->checkQuotaWarrantyCard($mWarrantyCard->getWarrantyCard([
            'customer_code' => $input['customer_code'],
            'object_type' => $input['object_type'],
            'object_type_id' => $input['object_type_id']
        ]), 1);
        //Lấy warranty_code đã được chọn trước đó (session chính)
        $warrantyCode = null;

        if (session()->get('warranty_choose')) {
            $warrantyCode = session()->get('warranty_choose');
        }

        //Render view
        $html = \View::make('warranty::maintenance.pop.modal-warranty-card', [
            'list' => $listWarranty,
            'FILTER' => $this->warrantyFilters(),
            'warrantyCode' => $warrantyCode
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Kiểm tra quota + số lần sử dụng của phiếu bảo hành, phân trang list
     *
     * @param $listCard
     * @param $page
     * @return LengthAwarePaginator
     */
    private function checkQuotaWarrantyCard($listCard, $page)
    {
        $data = [];

        if (count($listCard) > 0) {
            foreach ($listCard as $v) {
                if ($v['quota'] != 0 && $v['quota'] <= $v['count_using']) {
                    continue;
                }
                //Lấy tên đối tượng được bảo hành
                $name = $this->getObjectName($v['object_type'], $v['object_code']);
                $v['object_name'] = $name;

                $data [] = $v;
            }
        }

        //Phân trang
//        $page = 1;
//
//        if (isset($page)) {
//            $page = $page;
//        }

        // Get current page form url e.x. &page=1
        $currentPage = intval($page);

        // Create a new Laravel collection from the array data
        $itemCollection = collect($data);

        // Tổng item trên 1 trang
        $perPage = self::PER_PAGE;

        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        // Create our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);

        // set url path for generted links
        $paginatedItems->setPath(url()->current());

        return $paginatedItems;
    }

    /**
     * Lấy tên của đối tượng được bảo hành
     *
     * @param $objectType
     * @param $objectCode
     * @return string
     */
    private function getObjectName($objectType, $objectCode)
    {
        $name = '';

        switch ($objectType) {
            case 'product':
                $mProduct = app()->get(ProductChildTable::class);
                //Lấy thông tin sản phẩm
                $getInfo = $mProduct->getProduct($objectCode);
                $name = $getInfo != null ? $getInfo['product_child_name'] : '';

                break;
            case 'service':
                $mService = app()->get(ServiceTable::class);
                //Lấy thông tin dịch vụ
                $getInfo = $mService->getService($objectCode);
                $name = $getInfo != null ? $getInfo['service_name'] : '';

                break;
            case 'service_card':
                $mServiceCard = app()->get(ServiceCardTable::class);
                //Lấy thông tin thẻ dịch vụ
                $getInfo = $mServiceCard->getServiceCard($objectCode);
                $name = $getInfo != null ? $getInfo['name'] : '';

                break;
        }

        return $name;
    }


    /**
     * Filter phiếu bảo hành
     *
     * @return array
     */
    protected function warrantyFilters()
    {
        return [];
    }

    /**
     * Ajax filter, phân trang list phiếu bảo hành
     *
     * @param $input
     * @return mixed|void
     */
    public function listWarranty($input)
    {
        $mWarrantyCard = app()->get(WarrantyCardTable::class);
        //Lấy danh sách phiếu bảo hành
        $listWarranty = $this->checkQuotaWarrantyCard($mWarrantyCard->getWarrantyCard([
            'customer_code' => $input['customer_code'],
            'object_type' => $input['object_type'],
            'object_type_id' => $input['object_type_id'],
            'search_keyword' => $input['search_keyword']
        ]), $input['page']);

        $warrantyCode = null;
        $warrantyTemp = null;
        $warrantySubmit = null;
        //Lấy warranty_code đã được chọn trước đó (session chính)
        if (session()->get('warranty_choose')) {
            $warrantySubmit = session()->get('warranty_choose');
        }
        //Lấy warranty_code temp (đã chọn nhưng ko lưu)
        if (session()->get('temp')) {
            $warrantyTemp = session()->get('temp');
        }

        if ($warrantyTemp != null) {
            $warrantyCode = $warrantyTemp;
        } else {
            $warrantyCode = $warrantySubmit;
        }

        return view('warranty::maintenance.pop.list-warranty', [
            'list' => $listWarranty,
            'page' => $input['page'],
            'warrantyCode' => $warrantyCode
        ]);
    }

    /**
     * Chọn phiếu bảo hành áp dụng
     *
     * @return mixed|void
     */
    public function submitChooseWarranty()
    {
        $warrantyCode = null;
        $warrantyTemp = null;
        $warrantySubmit = null;
        //Lấy warranty_code đã được chọn trước đó (session chính)
        if (session()->get('warranty_choose')) {
            $warrantySubmit = session()->get('warranty_choose');
        }
        //Lấy warranty_code temp (đã chọn nhưng ko lưu)
        if (session()->get('temp')) {
            $warrantyTemp = session()->get('temp');
        }

        if ($warrantyTemp != null) {
            $warrantyCode = $warrantyTemp;
        } else {
            $warrantyCode = $warrantySubmit;
        }


        $mWarrantyCard = app()->get(WarrantyCardTable::class);
        //Lấy thông tin phiếu bảo hành
        $info = $mWarrantyCard->getInfo($warrantyCode);

        $warrantyValue = 0;
        if ($info != null) {
            //Lấy tên của đối tượng dc bảo hành
            $info['object_name'] = $this->getObjectName($info['object_type'], $info['object_code']);
            //Giá trị tối đa được bảo hành
            $maxPrice = floatval($info['warranty_value']);
            //Tính giá trị dc bảo hành
            $warrantyValue = floatval(($info['object_price'] / 100) * $info['warranty_percent']);

            if ($warrantyValue > $maxPrice) {
                $warrantyValue = $maxPrice;
            }
        }
        //Lưu warranty_code đã chọn làm chính
        session()->put('warranty_choose', $warrantyCode);
        //Clear session temp
        session()->forget('temp');

        $decimal = isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0;

        return response()->json([
            'info' => $info,
            'warrantyValue' => number_format($warrantyValue, $decimal)
        ]);
    }

    /**
     * Tạo phiếu bảo trì
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $dateEstimateDelivery = Carbon::createFromFormat('d/m/Y H:i', $input['date_estimate_delivery'])->format('Y-m-d H:i');
            $now = Carbon::now()->format('Y-m-d H:i');

            //Check start time > end time
            if ($now >= $dateEstimateDelivery) {
                return response()->json([
                    'error' => true,
                    'message' => __('Ngày trả hàng dự kiến phải lớn hơn thời gian hiện tại'),
                ]);
            }

            //Thêm phiếu bảo trì
            $maintenanceId = $this->maintenance->add([
                "customer_code" => $input['customer_code'],
                "warranty_code" => $input['warranty_code'],
                "maintenance_cost" => $input['maintenance_cost'],
                "warranty_value" => $input['warranty_value'],
                "insurance_pay" => $input['insurance_pay'],
                "amount_pay" => $input['amount_pay'],
                "total_amount_pay" => $input['total_amount_pay'],
                "staff_id" => $input['staff_id'],
                "object_type" => $input['object_type'],
                "object_type_id" => $input['object_type_id'],
                "object_code" => $input['object_code'],
                "object_serial" => $input['object_serial'],
                "object_status" => $input['object_status'],
                "maintenance_content" => $input['maintenance_content'],
                "date_estimate_delivery" => $dateEstimateDelivery,
                "status" => "new",
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);

            $maintenanceCode = 'PBT_' . date('dmY') . sprintf("%02d", $maintenanceId);
            //Cập nhật mã phiếu bảo trì
            $this->maintenance->edit([
                'maintenance_code' => $maintenanceCode
            ], $maintenanceId);

            $arrInsertImage = [];
            $arrInsertCost = [];

            if (isset($input['imageBefore']) && count($input['imageBefore'])) {
                foreach ($input['imageBefore'] as $v) {
                    $arrInsertImage [] = [
                        "maintenance_code" => $maintenanceCode,
                        "type" => "before",
                        "link" => $v,
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }

            if (isset($input['imageAfter']) && count($input['imageAfter']) > 0) {
                foreach ($input['imageAfter'] as $v) {
                    $arrInsertImage [] = [
                        "maintenance_code" => $maintenanceCode,
                        "type" => "after",
                        "link" => $v,
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }

            //Insert hình ảnh trước, sau khi bảo trì
            $mMaintenanceImage = app()->get(MaintenanceImageTable::class);
            $mMaintenanceImage->insert($arrInsertImage);

            if (isset($input['arrayCost']) && count($input['arrayCost']) > 0) {
                foreach ($input['arrayCost'] as $v) {
                    $arrInsertCost [] = [
                        "maintenance_id" => $maintenanceId,
                        "maintenance_cost_type" => $v['costType'],
                        "cost" => $v['cost'],
                        "created_by" => Auth()->id(),
                        "updated_by" => Auth()->id(),
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }

            //Insert chi phí phát sinh
            $mMaintenanceCost = app()->get(MaintenanceCostTable::class);
            $mMaintenanceCost->insert($arrInsertCost);

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => __('Thêm phiếu bảo trì thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Thêm phiếu bảo trì thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Data view chỉnh sửa phiếu bảo trì
     *
     * @param $maintenanceId
     * @return mixed|void
     */
    public function dataViewEdit($maintenanceId)
    {
        $mStaff = app()->get(StaffTable::class);
        $mCustomer = app()->get(CustomerTable::class);
        $mCostType = app()->get(MaintenanceCostTypeTable::class);
        $mMaintenanceImage = app()->get(MaintenanceImageTable::class);
        $mMaintenanceCost = app()->get(MaintenanceCostTable::class);
        $mReceipt = app()->get(ReceiptTable::class);

        //Lấy option nv thực hiện
        $optionStaff = $mStaff->getStaff();
        //Lấy option khách hàng
        $optionCustomer = $mCustomer->getCustomer();
        //Lấy option chi phí phát sinh
        $optionCostType = $mCostType->getCostType();
        //Lấy thông tin phiếu bảo trì
        $info = $this->maintenance->getInfo($maintenanceId);
        $info['object_name'] = $this->getObjectName($info['object_type'], $info['object_code']);
        //Lấy hình ảnh trước, sau khi bảo trì
        $getImage = $mMaintenanceImage->getImage($info['maintenance_code']);
        $imageBefore = [];
        $imageAfter = [];

        if (count($getImage) > 0) {
            foreach ($getImage as $v) {
                if ($v['type'] == "before") {
                    $imageBefore [] = $v['link'];
                } else if ($v['type'] == "after") {
                    $imageAfter [] = $v['link'];
                }
            }
        }
        //Lấy chi phí phát sinh khi bảo trì
        $getCost = $mMaintenanceCost->getCost($maintenanceId);
        //Lấy thông tin thanh toán phiếu bảo trì
        $getReceipt = $mReceipt->getReceipt(self::MAINTENANCE, $info['maintenance_id']);

        $isUpdate = 1;

        if (in_array($info['status'], ['finish', 'cancel']) || count($getReceipt) > 0) {
            $isUpdate = 0;
        }

        session()->put('warranty_choose', $info['warranty_code']);

        $totalReceipt = 0;
        if (count($getReceipt) > 0) {
            foreach ($getReceipt as $v) {
                $totalReceipt += $v['amount_paid'];
            }
        }
        $item['total_receipt'] = $totalReceipt;

        return [
            'optionStaff' => $optionStaff,
            'optionCustomer' => $optionCustomer,
            'optionCostType' => $optionCostType,
            'item' => $info,
            'imageBefore' => $imageBefore,
            'imageAfter' => $imageAfter,
            'cost' => $getCost,
            'isUpdate' => $isUpdate,
            'totalReceipt' => $totalReceipt
        ];
    }

    /**
     * Chỉnh sửa phiếu bảo trì
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $dateEstimateDelivery = Carbon::createFromFormat('d/m/Y H:i', $input['date_estimate_delivery'])->format('Y-m-d H:i');
            //Update phiếu bảo trì
            $this->maintenance->edit([
                "customer_code" => $input['customer_code'],
                "warranty_code" => $input['warranty_code'],
                "maintenance_cost" => $input['maintenance_cost'],
                "warranty_value" => $input['warranty_value'],
                "insurance_pay" => $input['insurance_pay'],
                "amount_pay" => $input['amount_pay'],
                "total_amount_pay" => $input['total_amount_pay'],
                "staff_id" => $input['staff_id'],
                "object_type" => $input['object_type'],
                "object_type_id" => $input['object_type_id'],
                "object_code" => $input['object_code'],
                "object_serial" => $input['object_serial'],
                "object_status" => $input['object_status'],
                "maintenance_content" => $input['maintenance_content'],
                "date_estimate_delivery" => $dateEstimateDelivery,
                "status" => $input['status'],
                "updated_by" => Auth()->id()
            ], $input['maintenance_id']);

            $arrInsertImage = [];
            $arrInsertCost = [];

            if (isset($input['imageBefore']) && count($input['imageBefore'])) {
                foreach ($input['imageBefore'] as $v) {
                    $arrInsertImage [] = [
                        "maintenance_code" => $input['maintenance_code'],
                        "type" => "before",
                        "link" => $v,
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }

            if (isset($input['imageAfter']) && count($input['imageAfter']) > 0) {
                foreach ($input['imageAfter'] as $v) {
                    $arrInsertImage [] = [
                        "maintenance_code" => $input['maintenance_code'],
                        "type" => "after",
                        "link" => $v,
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }

            $mMaintenanceImage = app()->get(MaintenanceImageTable::class);
            //Xóa tất cả hình ảnh
            $mMaintenanceImage->removeImage($input['maintenance_code']);
            //Insert hình ảnh trước, sau khi bảo trì
            $mMaintenanceImage->insert($arrInsertImage);

            if (isset($input['arrayCost']) && count($input['arrayCost']) > 0) {
                foreach ($input['arrayCost'] as $v) {
                    $arrInsertCost [] = [
                        "maintenance_id" => $input['maintenance_id'],
                        "maintenance_cost_type" => $v['costType'],
                        "cost" => $v['cost'],
                        "created_by" => Auth()->id(),
                        "updated_by" => Auth()->id(),
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }

            $mMaintenanceCost = app()->get(MaintenanceCostTable::class);
            //Xóa tất cả chi phí phát sinh
            $mMaintenanceCost->removeCost($input['maintenance_id']);
            //Insert chi phí phát sinh
            $mMaintenanceCost->insert($arrInsertCost);

            if ($input['status'] == 'finish') {
                if ($input['warranty_code'] != null) {
                    $mWarrantyCard = app()->get(WarrantyCardTable::class);
                    //Lấy thông tin của phiếu bảo hành
                    $getWarranty = $mWarrantyCard->getInfo($input['warranty_code']);
                    //Lấy số phiếu bảo trì đã hoàn tất của phiếu bảo hành
                    $getFinish = $this->maintenance->getMaintenanceFinish($input['warranty_code'], $input['maintenance_id']);

                    if ($getWarranty['quota'] != 0 && $getWarranty['quota'] <= count($getFinish)) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Đã vượt quá số lần sử dụng của phiếu bảo hành')
                        ]);
                    } else if ($getWarranty['quota'] != 0 && $getWarranty['quota'] == count($getFinish) + 1) {
                        //Hoàn thành phiếu bảo trì thì check quota phiếu bảo hành để update status
                        $mWarrantyCard->edit([
                            'status' => self::FINISH
                        ], $getWarranty['warranty_card_id']);
                    }

                }
            } else if ($input['status'] == 'cancel') {
                $mReceipt = app()->get(ReceiptTable::class);
                //Hủy phiếu bảo trì hủy phiếu thanh toán
                $mReceipt->cancelReceipt(self::MAINTENANCE, $input['maintenance_id']);
            }

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Modal view thanh toán phí bảo trì
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function modalReceipt($input)
    {
        $mReceipt = app()->get(ReceiptTable::class);
        $mPaymentMethod = app()->get(PaymentMethodTable::class);
        $optionPaymentMethod = $mPaymentMethod->getOption();

        //Lấy thông tin phiếu bảo trì
        $info = $this->maintenance->getInfo($input['maintenance_id']);
        //Lấy thông tin thanh toán phiếu bảo trì
        $totalReceipt = 0;

        $getReceipt = $mReceipt->getReceipt(self::MAINTENANCE, $input['maintenance_id']);
        if (count($getReceipt) > 0) {
            foreach ($getReceipt as $v) {
                $totalReceipt += $v['amount_paid'];
            }
        }

        //Render view
        $html = \View::make('warranty::maintenance.pop.modal-receipt', [
            'info' => $info,
            'totalReceipt' => $totalReceipt,
            'optionPaymentMethod' => $optionPaymentMethod
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Thanh toán phí bảo trì
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function submitReceipt($input)
    {
        DB::beginTransaction();
        try {
            $amountBill = str_replace(',', '', $input['amount_bill']); // tiền phải thanh toán
            $amountReturn = str_replace(',', '', $input['amount_return']); // tiền trả lại khách
            $amountAll = str_replace(',', '', $input['amount_all']); // tổng tiền các phương thức thanh toán
            if ($amountAll <= 0) { // tổng tiền trả
                return response()->json([
                    'error' => true,
                    'message' => __('Hãy nhập tiền thanh toán')
                ]);
            }
            if ($amountAll > $amountBill) {
                $amountReceipt = $amountBill;
            } else {
                $amountReceipt = $amountAll;
            }

            $mReceipt = app()->get(ReceiptTable::class);
            $mReceiptDetail = app()->get(ReceiptDetailTable::class);
            $mCustomer = app()->get(CustomerTable::class);

            if ($input['receipt_id'] == null) {
                //Insert phiếu thanh toán
                $receiptId = $mReceipt->add([
                    'customer_id' => $input['customer_id'],
                    'staff_id' => Auth::id(),
                    'object_type' => self::MAINTENANCE,
                    'object_id' => $input['maintenance_id'],
                    'total_money' => $amountAll,
                    'status' => 'paid',
                    'amount' => $amountAll,
                    'amount_paid' => $amountReceipt,
                    'amount_return' => $amountReturn,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'note' => $input['note'],
                    'receipt_type_code' => 'RTC_MAINTENANCE',
                    'object_accounting_type_code' => '', // order code
                    'object_accounting_id' => $input['maintenance_id'], // order id
                ]);
                $receiptCode = 'TT_' . date('dmY') . sprintf("%02d", $receiptId);
                //Update receipt_code
                $mReceipt->edit([
                    'receipt_code' => $receiptCode
                ], $receiptId);
            } else {
                //Chỉnh sửa phiếu thanh toán
                $receiptId = $input['receipt_id'];

                $mReceipt->edit([
                    'customer_id' => $input['customer_id'],
                    'staff_id' => Auth::id(),
                    'object_type' => self::MAINTENANCE,
                    'object_id' => $input['maintenance_id'],
                    'total_money' => $amountAll,
                    'status' => 'paid',
                    'amount' => $amountAll,
                    'amount_paid' => $amountReceipt,
                    'amount_return' => $amountReturn,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'note' => $input['note'],
                    'receipt_type_code' => 'RTC_MAINTENANCE',
                    'object_accounting_type_code' => '', // order code
                    'object_accounting_id' => $input['maintenance_id'], // order id
                ], $receiptId);

                //Xoá receipt_detail để ở dưới insert zo lại
                $mReceiptDetail->removeByReceipt($receiptId);
            }

            //Insert receipt detail
            $arrMethodWithMoney = $input['array_method'];
            foreach ($arrMethodWithMoney as $methodCode => $money) {
                if ($money > 0) {
                    $dataReceiptDetail = [
                        'receipt_id' => $receiptId,
                        'cashier_id' => Auth::id(),
                        'receipt_type' => 'cash',
                        'amount' => $money,
                        'payment_method_code' => $methodCode,
                        'created_by' => Auth::id(),
                    ];
                    if ($methodCode == 'MEMBER_MONEY') {
                        // Check số tiền thành viên
                        if ($money <= $amountBill) { // trừ tiên thành viên
                            if ($money < $input['member_money']) {
                                $customerMoney = $mCustomer->getItem($input['customer_id']);
                                $dataCusMoney = [
                                    'account_money' => $customerMoney['account_money'] - $money
                                ];
                                $mReceiptDetail->add($dataReceiptDetail);
                                $mCustomer->edit($dataCusMoney, $input['customer_id']);
//                                $customerBranch = $this->customer_branch_money->getPriceBranch($request->customer_id, $staff_branch['branch_id']);
//
//                                if ($customerBranch != null) {
//                                    $dataCusBranchMoney = [
//                                        'total_using' => $customerBranch['total_using'] + $money,
//                                        'balance' => $customerBranch['total_money'] - ($customerBranch['total_using'] + $money)
//                                    ];
//                                    $this->customer_branch_money->edit($dataCusBranchMoney, $request->customer_id, $staff_branch['branch_id']);
//                                }
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
                    } else {
                        $mReceiptDetail->add($dataReceiptDetail);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => __('Thanh toán thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Thanh toán thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Chi tiết phiếu bảo trì
     *
     * @param $maintenanceId
     * @return mixed|void
     */
    public function show($maintenanceId)
    {
        $mStaff = app()->get(StaffTable::class);
        $mCustomer = app()->get(CustomerTable::class);
        $mCostType = app()->get(MaintenanceCostTypeTable::class);
        $mMaintenanceImage = app()->get(MaintenanceImageTable::class);
        $mMaintenanceCost = app()->get(MaintenanceCostTable::class);
        $mReceipt = app()->get(ReceiptTable::class);

        //Lấy option nv thực hiện
        $optionStaff = $mStaff->getStaff();
        //Lấy option khách hàng
        $optionCustomer = $mCustomer->getCustomer();
        //Lấy option chi phí phát sinh
        $optionCostType = $mCostType->getCostType();
        //Lấy thông tin phiếu bảo trì
        $info = $this->maintenance->getInfo($maintenanceId);
        $info['object_name'] = $this->getObjectName($info['object_type'], $info['object_code']);
        //Lấy hình ảnh trước, sau khi bảo trì
        $getImage = $mMaintenanceImage->getImage($info['maintenance_code']);
        $imageBefore = [];
        $imageAfter = [];

        if (count($getImage) > 0) {
            foreach ($getImage as $v) {
                if ($v['type'] == "before") {
                    $imageBefore [] = $v['link'];
                } else if ($v['type'] == "after") {
                    $imageAfter [] = $v['link'];
                }
            }
        }
        //Lấy chi phí phát sinh khi bảo trì
        $getCost = $mMaintenanceCost->getCost($maintenanceId);

        $totalReceipt = 0;
        //Lấy thông tin thanh toán phiếu bảo trì
        $getReceipt = $mReceipt->getReceipt(self::MAINTENANCE, $info['maintenance_id']);
        if (count($getReceipt) > 0) {
            foreach ($getReceipt as $v) {
                $totalReceipt += $v['amount_paid'];
            }
        }
        $item['total_receipt'] = $totalReceipt;

        $mReceiptDetail = app()->get(ReceiptDetailTable::class);
        //Lấy chi tiết thanh toán phiếu bảo trì
        $getReceiptDetail = $mReceiptDetail->getDetailMaintenance($info['maintenance_id']);

        return [
            'optionStaff' => $optionStaff,
            'optionCustomer' => $optionCustomer,
            'optionCostType' => $optionCostType,
            'item' => $info,
            'imageBefore' => $imageBefore,
            'imageAfter' => $imageAfter,
            'cost' => $getCost,
            'totalReceipt' => $totalReceipt,
            'receiptDetail' => $getReceiptDetail
        ];
    }

    /**
     * Tạo qr code thanh toán online
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function genQrCode($input)
    {
        DB::beginTransaction();
        try {
            if (isset($input['amount']) && $input['amount'] <= 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Số tiền không hợp lệ')
                ]);
            }

            //Lấy thông tin phiếu bảo trì
            $info = $this->maintenance->getInfo($input['maintenance_id']);

            $mReceipt = app()->get(ReceiptTable::class);
            $mReceiptDetail = app()->get(ReceiptDetailTable::class);
            //Tạo phiếu thu (trạng thái chưa thanh toán)
            $receiptId = $mReceipt->add([
                'customer_id' => $info['customer_id'],
                'object_id' => $info['maintenance_id'],
                'object_type' => 'maintenance',
                'total_money' => $input['amount'],
                'status' => 'unpaid',
                'is_discount' => 1,
                'amount' => $input['amount'],
                'amount_paid' => $input['amount'],
                'amount_return' => 0,
                'receipt_type_code' => 'RTC_MAINTENANCE',
                'object_accounting_type_code' => $info['maintenance_code'], // order code
                'object_accounting_id' => $info['maintenance_id'], // order id

            ]);

            $receiptCode = 'TT_' . date('dmY') . sprintf("%02d", $receiptId);
            //Update receipt_code
            $mReceipt->edit([
                'receipt_code' => $receiptCode
            ], $receiptId);
            //Tạo chi tiết thu
            $mReceiptDetail->add([
                'receipt_id' => $receiptId,
                'payment_method_code' => $input['payment_method_code'],
                'amount' => $input['amount'],
            ]);

            $mPaymentMethod = app()->get(PaymentMethodTable::class);
            //Lấy thông tin phương thức thanh toán
            $getMethod = $mPaymentMethod->getInfoByCode($input['payment_method_code']);

            $url = "";

            if ($input['payment_method_code'] == "VNPAY") {
                $mReceiptOnline = app()->get(ReceiptOnlineTable::class);
                //Lưu vào bảng receipt_online
                $idReceiptOnline = $mReceiptOnline->add([
                    "receipt_id" => $receiptId,
                    "object_type" => "maintenance",
                    "object_code" => $info['maintenance_code'],
                    "payment_method_code" => $input['payment_method_code'],
                    "amount_paid" => $input['amount'],
                    "payment_time" => Carbon::now()->format('Y-m-d H:i:s'),
                    "type" => $getMethod['payment_method_type'],
                    "performer_name" => $info['full_name'],
                    "performer_phone" => $info['phone']
                ]);
                //Nếu là vn pay thì call api thanh toán vn pay
                $callVnPay = $this->_paymentVnPay(
                    $info['maintenance_code'],
                    $input['amount'],
                    $info['customer_id'],
                    Auth()->user()->branch_id,
                    'web',
                    ""
                );

                if ($callVnPay['ErrorCode'] == 0) {
                    $url = $callVnPay['Data']['payment_url'];

                    //Update transaction_code cho receipt_online khi call api thành công
                    $mReceiptOnline->edit([
                        "payment_transaction_code" => $callVnPay['Data']['payment_transaction_code']
                    ], $idReceiptOnline);
                }
            }

            if ($url == "") {
                return response()->json([
                    'error' => true,
                    'message' => __('Tạo qr thất bại')
                ]);
            }

            DB::commit();

            return response()->json([
                'error' => false,
                'url' => $url,
                "receipt_id" => $receiptId,
                "message" => __("Tạo qr thành công")
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => true,
                'message' => __('Tạo qr thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Call api thanh toán vn pay
     *
     * @param $orderCode
     * @param $amount
     * @param $userId
     * @param $branchId
     * @param $platform
     * @param $paramsExtra
     * @return mixed
     */
    public function _paymentVnPay($orderCode, $amount, $userId, $branchId, $platform, $paramsExtra)
    {
        $mPaymentOnline = app()->get(PaymentOnline::class);

        //Call api thanh toán vn pay
        return $mPaymentOnline->paymentVnPay([
            'method' => 'vnpay',
            'order_id' => $orderCode,
            'amount' => $amount,
            'user_id' => $userId,
            'branch_id' => $branchId,
            'platform' => $platform,
            'params_extra' => $paramsExtra
        ]);
    }
}