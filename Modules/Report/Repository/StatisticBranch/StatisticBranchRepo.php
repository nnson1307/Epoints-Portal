<?php

namespace Modules\Report\Repository\StatisticBranch;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\BranchTable;
use Modules\Report\Models\OrderDetailTable;
use Modules\Report\Models\OrderTable;
use Modules\Report\Models\StatisticCustomerTable;

class StatisticBranchRepo implements StatisticBranchRepoInterface
{
    /**
     * Data cho View thống kê theo chi nhánh
     *
     * @return array
     */
    public function dataViewIndex()
    {
        $mBranch = new BranchTable();
        $optionBranch = $mBranch->getOption();
        return [
            'optionBranch' => $optionBranch
        ];
    }

    public function filterAction($input)
    {
        $time = $input['time'];
        $branchId = $input['branch'];
        $startTime = $endTime = null;
        $mOrder = new OrderTable();
        $mOrderDetail = new OrderDetailTable();
        $mBranch = new BranchTable();

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        // Lấy dữ liệu theo type (product, service, service card)
        $dataProduct = [];
        $dataService = [];
        $dataServiceCard = [];
        $dataOrderDetail = $mOrderDetail->getAllDetailByFilter($startTime, $endTime, $branchId)->toArray();
        $dataGroupByObjType = collect($dataOrderDetail)->groupBy('object_type');
        if (count($dataGroupByObjType) > 0) {
            foreach ($dataGroupByObjType as $key => $val) {
                if ($key == 'product') {
                    $dataProduct = $val;
                } elseif ($key == 'service') {
                    $dataService = $val;
                } elseif ($key == 'service_card')  {
                    $dataServiceCard = $val;
                }
            }
        }
        // Lấy dữ liệu voucher (theo order)
        $orderUseVoucher = $mOrder->getOrderUseVoucher($startTime, $endTime, $branchId)->toArray();
        // Lấy dữ liệu voucher (theo order detail)
        $objectUseVoucher = $mOrderDetail->getObjectHaveUseVoucher($startTime, $endTime, $branchId)->toArray();
        // Nếu tất cả chi nhánh thi từng cột là chi nhánh, nếu một chi nhánh thì từng cột là ngày
        $arrayCategory = [];
        if ($branchId != null) {
            // Array days
            $dateDiff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            for ($i = 0; $i < $dateDiff; $i++) {
                $timeTmp = Carbon::parse($startTime)->addDay($i)->format('d/m/Y');
                $arrayCategory [$timeTmp] = [
                    'category_name' => $timeTmp,
                    'totalProduct' => 0,
                    'totalService' => 0,
                    'totalServiceCard' => 0,
                    'totalVoucher' => 0,
                ];
            }
            // Xử lí biểu đồ cột
            $chartColumn = $this->dataChartGrowthByBranch($dataProduct, $dataService, $dataServiceCard, $orderUseVoucher, $objectUseVoucher, $arrayCategory, false);
        } else {
            $optionBranch = $mBranch->getOption();
            foreach ($optionBranch as $key => $value) {
                $arrayCategory [$value['branch_id']] = [
                    'category_name' => $value['branch_name'],
                    'totalProduct' => 0,
                    'totalService' => 0,
                    'totalServiceCard' => 0,
                    'totalVoucher' => 0,
                ];
            }
            // Xử lí biểu đồ cột
            $chartColumn = $this->dataChartGrowthByBranch($dataProduct, $dataService, $dataServiceCard, $orderUseVoucher, $objectUseVoucher, $arrayCategory);
        }

        // Xử lí các biểu đồ tròn
            // Nhóm khách hàng
        $chartCustomerGroup = $this->dataChartCustomerGroup($startTime, $endTime, $branchId);
            // Nhóm dịch vụ
        $chartServiceCategory = $this->dataChartServiceCategory($startTime, $endTime, $branchId);
            // Nhóm sản phẩm
        $chartProductCategory = $this->dataChartProductCategory($startTime, $endTime, $branchId);
            // Nhóm thẻ dịch vụ
        $chartServiceCardGroup = $this->dataChartServiceCardGroup($startTime, $endTime, $branchId);
            // Tỉ lệ sử dụng thẻ dịch vụ
        $chartServiceCardUsage = $this->dataChartServiceCardUsage($dataOrderDetail, $dataServiceCard);
            // Tỉ lệ sử dụng voucher
        $chartVoucherUsage = $this->dataChartVoucherUsage($dataOrderDetail,$orderUseVoucher, $objectUseVoucher);

        return [
            'dataChartColumn' => $chartColumn,
            'dataChartCustomerGroup' => $chartCustomerGroup,
            'dataChartServiceCategory' => $chartServiceCategory,
            'dataChartProductCategory' => $chartProductCategory,
            'dataChartServiceCardGroup' => $chartServiceCardGroup,
            'dataChartServiceCardUsage' => $chartServiceCardUsage,
            'dataChartVoucherUsage' => $chartVoucherUsage,
        ];
    }

    /**
     * Xử lý data cho biểu đồ cột
     *
     * @param $dataProduct
     * @param $dataService
     * @param $dataServiceCard
     * @param $orderUseVoucher
     * @param $objectUseVoucher
     * @param array $arrayCategory
     * @param bool $isAllBranch
     * @return mixed
     */
    private function dataChartGrowthByBranch($dataProduct, $dataService, $dataServiceCard, $orderUseVoucher, $objectUseVoucher, $arrayCategory = [], $isAllBranch = true)
    {
        if ($isAllBranch) {
            // Số lượng sản phẩm
            foreach ($dataProduct as $key => $value) {
                if (isset($arrayCategory[$value['branch_id']])) {
                    $arrayCategory[$value['branch_id']]['totalProduct'] += (int)$value['quantity'];
                }
            }
            // Số lượng dịch vụ
            foreach ($dataService as $key => $value) {
                if (isset($arrayCategory[$value['branch_id']])) {
                    $arrayCategory[$value['branch_id']]['totalService'] += (int)$value['quantity'];
                }
            }
            // Số lượng thẻ dịch vụ
            foreach ($dataServiceCard as $key => $value) {
                if (isset($arrayCategory[$value['branch_id']])) {
                    $arrayCategory[$value['branch_id']]['totalServiceCard'] += (int)$value['quantity'];
                }
            }
            // Số lượng voucher (theo object (order_details))
            foreach ($objectUseVoucher as $key => $value) {
                if (isset($arrayCategory[$value['branch_id']])) {
                    $arrayCategory[$value['branch_id']]['totalVoucher'] += 1;
                }
            }
            // Số lượng voucher (theo order)
            foreach ($orderUseVoucher as $key => $value) {
                if (isset($arrayCategory[$value['branch_id']])) {
                    $arrayCategory[$value['branch_id']]['totalVoucher'] += 1;
                }
            }
        } else {
            // Số lượng sản phẩm
            foreach ($dataProduct as $key => $value) {
                $timeTemp = Carbon::parse($value['created_at'])->format('d/m/Y');
                if (isset($arrayCategory[$timeTemp])) {
                    $arrayCategory[$timeTemp]['totalProduct'] += (int)$value['quantity'];
                }
            }
            // Số lượng dịch vụ
            foreach ($dataService as $key => $value) {
                $timeTemp = Carbon::parse($value['created_at'])->format('d/m/Y');
                if (isset($arrayCategory[$timeTemp])) {
                    $arrayCategory[$timeTemp]['totalService'] += (int)$value['quantity'];
                }
            }
            // Số lượng thẻ dịch vụ
            foreach ($dataServiceCard as $key => $value) {
                $timeTemp = Carbon::parse($value['created_at'])->format('d/m/Y');
                if (isset($arrayCategory[$timeTemp])) {
                    $arrayCategory[$timeTemp]['totalServiceCard'] += (int)$value['quantity'];
                }
            }
            // Số lượng voucher (theo object (order_details))
            foreach ($objectUseVoucher as $key => $value) {
                $timeTemp = Carbon::parse($value['created_at'])->format('d/m/Y');
                if (isset($arrayCategory[$timeTemp])) {
                    $arrayCategory[$timeTemp]['totalVoucher'] += 1;
                }
            }
            // Số lượng voucher (theo order)
            foreach ($orderUseVoucher as $key => $value) {
                $timeTemp = Carbon::parse($value['created_at'])->format('d/m/Y');
                if (isset($arrayCategory[$timeTemp])) {
                    $arrayCategory[$timeTemp]['totalVoucher'] += 1;
                }
            }
        }
        // Đưa về đúng dạng biểu đồ
        $dataReturn[] = ['', __('SẢN PHẨM'), __('DỊCH VỤ'), __('THẺ DỊCH VỤ'), __('VOUCHER')];
        foreach ($arrayCategory as $key => $value) {
            $dataReturn[] = [
                $value['category_name'], $value['totalProduct'],
                $value['totalService'], $value['totalServiceCard'], $value['totalVoucher']
            ];
        }

        return $dataReturn;
    }

    // Xử lý data cho biểu đồ nhóm khách hàng
    private function dataChartCustomerGroup($startTime, $endTime, $branchId)
    {
        $dataReturn = [['Task', 'Hours per Day']];
        $mOrderDetail = new OrderDetailTable();
        // Lấy theo khách hàng vãng lai (customer_id = 1, customer_group_id = 0)
        $getDataCustomerCurrent = $mOrderDetail->getQuantityObjectByCustomerCurrent($startTime, $endTime, $branchId);
        if ($getDataCustomerCurrent != null) {
            $dataReturn [] = [
                __('Khách hàng khác'),
                (int)$getDataCustomerCurrent['quantity']
            ];
        }
        // Các nhóm khách hàng còn lại
        $getDataCustomerRest = $mOrderDetail->getQuantityObjectGroupCustomer($startTime, $endTime, $branchId);
        foreach ($getDataCustomerRest as $key => $value) {
            $dataReturn [] = [
                $value['customer_group_name'],
                (int)$value['quantity']
            ];
        }
        return $dataReturn;
    }

    // Xử lý data cho biểu đồ nhóm dịch vụ
    private function dataChartServiceCategory($startTime, $endTime, $branchId)
    {
        $dataReturn = [['Task', 'Hours per Day']];
        $mOrderDetail = new OrderDetailTable();
        $getData = $mOrderDetail->getQuantityGroupObject($startTime, $endTime, $branchId, 'service')->toArray();
        foreach ($getData as $key => $value) {
            $dataReturn [] = [
                $value['service_category_name'],
                (int)$value['quantity']
            ];
        }
        return $dataReturn;
    }

    // Xử lý data cho biểu đồ nhóm sản phẩm
    private function dataChartProductCategory($startTime, $endTime, $branchId)
    {
        $dataReturn = [['Task', 'Hours per Day']];
        $mOrderDetail = new OrderDetailTable();
        $getData = $mOrderDetail->getQuantityGroupObject($startTime, $endTime, $branchId, 'product')->toArray();
        foreach ($getData as $key => $value) {
            $dataReturn [] = [
                $value['product_category_name'],
                (int)$value['quantity']
            ];
        }
        return $dataReturn;
    }

    // Xử lý data cho biểu đồ nhóm thẻ dịch vụ
    private function dataChartServiceCardGroup($startTime, $endTime, $branchId)
    {
        $dataReturn = [['Task', 'Hours per Day']];
        $mOrderDetail = new OrderDetailTable();
        $getData = $mOrderDetail->getQuantityGroupObject($startTime, $endTime, $branchId, 'service_card')->toArray();
        foreach ($getData as $key => $value) {
            $dataReturn [] = [
                $value['service_card_group_name'],
                (int)$value['quantity']
            ];
        }
        return $dataReturn;
    }

    // Xử lý data cho biểu đồ tỉ lệ sử dụng thẻ dịch vụ (số đơn hàng sử dụng thẻ dịch vụ, không sử dụng thẻ dịch vụ)
    private function dataChartServiceCardUsage($dataOrderDetail, $dataServiceCard)
    {
        // Số lượng đơn hàng sử dụng thẻ dịch vụ (process_status = pay_success)
        $arrTempUse = collect($dataServiceCard)->groupBy("order_id");
        // Tổng số đơn hàng (process_status = pay_success)
        $arrTempTotal = collect($dataOrderDetail)->groupBy("order_id");
        $dataReturn = [
            ['Task', 'Amount'],
            [__('Đã sử dụng'), count($arrTempUse)],
            [__('Không sử dụng'), count($arrTempTotal) - count($arrTempUse)]
        ];
        return $dataReturn;
    }

    // Xử lý data cho biểu đồ tỉ lệ sử dụng voucher
    private function dataChartVoucherUsage($dataOrderDetail, $orderUseVoucher, $objectUseVoucher)
    {
        // Số đơn hàng sử dụng voucher (process_status = pay_success)
        $arrTemp = collect($objectUseVoucher)->groupBy("order_id");
        // Tổng số đơn hàng (process_status = pay_success)
        $arrTempTotal = collect($dataOrderDetail)->groupBy("order_id");
        $totalUse = count($arrTemp) + count($orderUseVoucher);
        $dataReturn = [
            ['Task', 'Amount'],
            [__('Đã sử dụng'), $totalUse],
            [__('Không sử dụng'), count($arrTempTotal) - $totalUse]
        ];
        return $dataReturn;
    }
    /**
     * Ds chi tiết của chart
     *
     * @param $input
     * @return array|mixed
     */
    public function listDetail($input)
    {
        $mOrders = new OrderDetailTable();
        $list = $mOrders->getListDetailStatisticsBranch($input);

        return [
            'list' => $list
        ];
    }

    /**
     * Export excel tổng
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelTotal($input)
    {
        $heading = [
            __('TÊN CHI NHÁNH'),
            __('LOẠI'),
            __('SỐ LẦN MUA')
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mOrderDetails = new OrderDetailTable();
        $allData = $mOrderDetails->getListExportTotalStatisticsBranch($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $objName = '';
                switch ($item['object_type']){
                    case 'product': $objName = __("Sản phẩm");break;
                    case 'service': $objName = __("Dịch vụ");break;
                    case 'service_card': $objName = __("Thẻ dịch vụ");break;
                    case 'member_card': $objName = __("Thẻ thành viên");break;
                }
                $data [] = [
                    $item['branch_name'],
                    $objName,
                    $item['usages']
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-total.xlsx');
    }

    /**
     * Export excel chi tiết
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelDetail($input)
    {
        $heading = [
            __('MÃ ĐƠN HÀNG'),
            __('TÊN CHI NHÁNH'),
            __('LOẠI'),
            __('TÊN'),
            __('SỐ LƯỢNG'),
            __('NGÀY MUA'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mOrders = new OrderDetailTable();
        $allData = $mOrders->getListExportDetailStatisticsBranch($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $objName = '';
                switch ($item['object_type']){
                    case 'product': $objName = __("Sản phẩm");break;
                    case 'service': $objName = __("Dịch vụ");break;
                    case 'service_card': $objName = __("Thẻ dịch vụ");break;
                    case 'member_card': $objName = __("Thẻ thành viên");break;
                }
                $data [] = [
                    $item['order_code'],
                    $item['branch_name'],
                    $objName,
                    $item['object_name'],
                    $item['quantity'],
                    date("d/m/Y h:i",strtotime($item['created_at']))
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-detail.xlsx');
    }
}