<?php


namespace Modules\Report\Repository\RevenueByBranch;


use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\BranchTable;
use Modules\Report\Models\CustomerGroupTable;
use Modules\Report\Models\OrderTable;
use Modules\Report\Models\ReceiptDetailTable;
use Modules\Report\Models\ReceiptTable;

class RevenueByBranchRepo implements RevenueByBranchRepoInterface
{
    /**
     * View báo cáo doanh thu theo chi nhánh
     *
     * @return array|mixed
     */
    public function dataViewIndex()
    {
        $mBranch = new BranchTable();
        $mCustomerGroup = new CustomerGroupTable();
        $optionBranch = $mBranch->getOption();
        $optionCustomerGroup = $mCustomerGroup->getOption();
        return [
            'optionBranch' => $optionBranch,
            'optionCustomerGroup' => $optionCustomerGroup
        ];
    }

    /**
     * filter time, branch, customer group
     *
     * @param $data
     * @return mixed
     */
    public function filterAction($data)
    {
        $mBranch = new BranchTable();
        $mOrder = new OrderTable();

        // Declare input: khai báo
        $time = $data['time'];
        $branchId = $data['branch'];
        $customerGroupId = $data['customerGroup'];
        $startTime = $endTime = null;
        $dataSeries = [];
        $dataCategories = [];

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        // START CHART COLUMN
        // Tổng doanh thu
        $arrReceiptStatus = ['paid', 'part-paid'];
        $arrOrderStatus = ['paysuccess', 'pay-half'];
        // Query: Lấy dữ liệu doanh thu theo status
        $dataGroupByOrderId = $mOrder->getAllOrderByStatus($startTime, $endTime, $arrOrderStatus, $branchId, $customerGroupId)->toArray();
        $totalOrder = 0; // Tổng đơn hàng
        $totalOrderPaid = count($dataGroupByOrderId); // Tổng đơn đã thanh toán và thanh toán 1 phần
        $totalMoneyPaid = 0; // Tổng tiền đã thanh toán
        $totalOrderMoney = 0; // Tổng tiền đơn hàng
        $totalMoneyUnpaid = 0; // Tổng tiền chưa thanh toán
        $totalOrderUnpaid = 0; // Tổng đơn chưa thanh toán
        if ($totalOrderPaid > 0) {
            foreach ($dataGroupByOrderId as $value) {
                $totalMoneyPaid += $value['total_receipt'];
                $totalOrderMoney += $value['order_amount'];
                if ($value['status'] == 'pay-half') {
                    $totalOrderUnpaid += 1;
                }
            }
        }
        $totalMoneyUnpaid = $totalOrderMoney - $totalMoneyPaid; // Tổng tiền chưa thanh toán

        if ($branchId != "") {
            // Array days
            $arrayDate = [];
            $dateDiff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            for ($i = 0; $i < $dateDiff; $i++) {
                $arrayDate [] = Carbon::parse($startTime)->addDay($i)->format('d/m/Y');
            }
            $arrayCategories = $arrayDate;
            $dataCategories = $this->dataByDays($arrayDate, $dataGroupByOrderId);
        } else {
            // Array branch
            $arrayBranchName = [];
            $arrayBranchId = [];
            $optionBranch = $mBranch->getOption();
            foreach ($optionBranch as $key => $value) {
                $arrayBranchName[] = $value['branch_name'];
                $arrayBranchId[$value['branch_id']] = [
                    'totalMoneyPaid' => 0,
                    'totalMoneyUnPaid' => 0,
                ];
            }
            $arrayCategories = $arrayBranchName;
            $dataCategories = $this->dataAllBranch($arrayBranchId, $dataGroupByOrderId);
        }
        // Data series
        $dataSeries = $this->dataSeries($dataCategories);
        // END CHART COLUMN

        // Chart by payment method
        $dataReceiptType = $this->dataChartByPaymentMethod($startTime, $endTime, $branchId, $customerGroupId);
        // List
//        $dataList = $this->dataListTable($dataGroupByOrderId);

        // data total (số tiền, số đơn)
        $dataTotal = [
            'totalOrder' => $totalOrderPaid,
            'totalMoney' => $totalMoneyPaid + $totalMoneyUnpaid,
            'totalOrderPaySuccess' => $totalOrderPaid,
            'totalMoneyOrderPaySuccess' => $totalMoneyPaid,
            'totalOrderNew' => $totalOrderUnpaid,
            'totalMoneyOrderNew' => $totalMoneyUnpaid,
        ];

        $dataReturn = [
            'arrayCategories' => $arrayCategories,
            'total' => $dataTotal,
            'dataSeries' => $dataSeries,
            'dataByReceiptType' => $dataReceiptType,
//            'dataList' => $dataList
        ];
        return response()->json($dataReturn);
    }

    /**
     * Xử lý phần data cho biểu đồ (1 chi nhánh, theo ngày)
     *
     * @param array $arrayDate
     * @param $dataGroupByOrderId
     * @return mixed
     */
    private function dataByDays(array $arrayDate, $dataGroupByOrderId)
    {
        $data = [];
        // Data by every day init
        foreach ($arrayDate as $key => $value) {
            $data[$value] = [
                'totalMoneyPaid' => 0,
                'totalMoneyUnPaid' => 0,
            ];
        }
        // Data: Đã thanh toán + chưa thanh toán
        foreach ($dataGroupByOrderId as $key => $value) {
            $timeTemp = Carbon::parse($value['created_at'])->format('d/m/Y');
            if (isset($data[$timeTemp])) {
                $data[$timeTemp]['totalMoneyPaid'] += $value['total_receipt'];
                $data[$timeTemp]['totalMoneyUnPaid'] += ($value['order_amount'] - $value['total_receipt']);
            }
        }
        return $data;
    }

    /**
     * Xử lý phần data cho biểu đồ (Tất cả chi nhánh)
     *
     * @param array $branch
     * @param $dataPaidUnPaid
     * @return array
     */
    private function dataAllBranch(array $branch, $dataPaidUnPaid)
    {
        $data = $branch;
        // Data: Đã thanh toán + chưa thanh toán
        foreach ($dataPaidUnPaid as $key => $value) {
            if (isset($data[$value['branch_id']])) {
                $data[$value['branch_id']]['totalMoneyPaid'] += $value['total_receipt'];
                $data[$value['branch_id']]['totalMoneyUnPaid'] += ($value['order_amount'] - $value['total_receipt']);
            }
        }
        return $data;
    }

    /**
     * Xử lý data series
     *
     * @param $dataByCategories
     * @return array
     */
    private function dataSeries($dataByCategories)
    {
        $arrTotalPaid = [];
        $arrTotalUnPaid = [];

        foreach ($dataByCategories as $k => $v) {
            $arrTotalPaid [] = $v['totalMoneyPaid'];
            $arrTotalUnPaid [] = $v['totalMoneyUnPaid'];
        }

        return [
            [
                'name' => __('Số tiền chưa thanh toán'),
                'data' => $arrTotalUnPaid
            ],
            [
                'name' => __('Số tiền đã thanh toán'),
                'data' => $arrTotalPaid
            ],
        ];
    }

    /**
     * data biểu đồ tròn doanh thu theo phương thức thanh toán
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @param $customerGroupId
     * @return array
     */
    private function dataChartByPaymentMethod($startTime, $endTime, $branchId, $customerGroupId)
    {
        $mReceiptDetail = new ReceiptDetailTable();
        // START CHART PIE
        $listReceiptType = ['cash','transfer','visa','member_card','member_point','member_money'];
        $dataChartReceiptType = $mReceiptDetail->getSumMoneyByReceiptTypeFilter($startTime, $endTime, $branchId, $customerGroupId);
        $dataTotalMoneyByReceiptType = [];
        $dataReturn = [];
        $listReceiptTypeReal = []; // danh sách receipt type thực tế
        $sum = 0;
        // tính tổng và lấy danh sách receipt type thực tế
        foreach ($dataChartReceiptType as $k => $v) {
            $sum += $v['sum_type'];
            $listReceiptTypeReal[] = $v['receipt_type'];
        }
        // Những receipt type nào không có thì gán bằng 0
        foreach ($listReceiptType as $key => $v) {
            if (!in_array($v, $listReceiptTypeReal)) {
                $dataTotalMoneyByReceiptType[] = [
                    'name' => $v,
                    'y' => 0,
                    'sum_type' => 0
                ];
            }
        }
       
        // Những receipt type thực tế
        foreach ($dataChartReceiptType as $k => $v) {
            $data = [
                'name' => $v['receipt_type'] ? "" : $v['receipt_type'],
                'y' => (float)number_format($v['sum_type'] ? 0 : $v['sum_type'] * 100 / $sum, 2),
                'sum_type' => number_format($v['sum_type'] ? 0 : $v['sum_type'], 2) . __('đ')
            ];
           
            $dataTotalMoneyByReceiptType[] = $data;
        }
       
        // Đổi tên receipt_type
        foreach ($dataTotalMoneyByReceiptType as $value) {
            switch ($value['name']) {
                case 'cash': $value['name'] = __('Tiền mặt'); break;
                case 'transfer': $value['name'] = __('Chuyển khoản'); break;
                case 'visa': $value['name'] = __('Visa'); break;
                case 'member_card': $value['name'] = __('Thẻ thành viên'); break;
                case 'member_point': $value['name'] = __('Điểm thành viên'); break;
                case 'member_money': $value['name'] = __('Tiền thành viên'); break;
            }
            $dataReturn[] = $value;
        }
        // END CHART PIE
        return $dataReturn;
    }

    /**
     * Xử lý data cho phần danh sách
     *
     * @param $dataPaidUnPaid
     * @return array
     */
    private function dataListTable($dataPaidUnPaid)
    {
        $arrayResult = [];
        $mBranch = new BranchTable();
        $optionBranch = $mBranch->getOption();
        foreach ($optionBranch as $key => $value) {
            $arrayResult[$value['branch_id']] = [
                'branchName' => $value['branch_name'],
                'totalOrder' => 0,
                'totalMoney' => 0,
                'totalMoneyDiscount' => 0,
                'totalMoneyShip' => 0,
            ];
        }

        // Data: Đã thanh toán
        foreach ($dataPaidUnPaid as $key => $value) {
            if (isset($arrayResult[$value['branch_id']])) {
                $arrayResult[$value['branch_id']]['totalOrder'] += 1;
                $arrayResult[$value['branch_id']]['totalMoney'] += $value['total_receipt'];
            }
        }
        return $arrayResult;
    }

    /**
     * Ds chi tiết của chart
     *
     * @param $input
     * @return array|mixed
     */
    public function listDetail($input)
    {
        $mOrder = new OrderTable();
        $list = $mOrder->getListDetailBranch($input);

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
            __('TÊN NHÓM KHÁCH HÀNG'),
            __('TỔNG TIỀN ĐÃ THANH TOÁN'),
            __('TIỀN TIỀN CHƯA THANH TOÁN')
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mOrder = new OrderTable();
        $allData = $mOrder->getListExportTotal($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['branch_name'],
                    $item['group_name'],
                    $item['total_receipt'],
                    (string)((float)$item['amount'] - (float)$item['total_receipt'])
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
            __('NHÓM KHÁCH HÀNG'),
            __('TÊN KHÁCH HÀNG'),
            __('TÊN CHI NHÁNH'),
            __('TIỀN ĐÃ THANH TOÁN'),
            __('TIỀN CHƯA THANH TOÁN'),
            __('NGÀY MUA'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mOrder = new OrderTable();
        $allData = $mOrder->getListExportDetail($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['order_code'],
                    $item['group_name'],
                    $item['full_name'],
                    $item['branch_name'],
                    $item['total_receipt'],
                    (string)((float)$item['amount'] - (float)$item['total_receipt']),
                    date("d/m/Y h:i",strtotime($item['created_at']))
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-detail.xlsx');
    }
}