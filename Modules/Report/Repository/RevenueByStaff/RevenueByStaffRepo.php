<?php

namespace Modules\Report\Repository\RevenueByStaff;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\StaffTable;
use Modules\Report\Models\BranchTable;
use Modules\Report\Models\OrderTable;
use Modules\Report\Models\ReceiptTable;

class RevenueByStaffRepo implements RevenueByStaffRepoInterface
{
    /**
     * Data cho View báo cáo doanh thu theo nhân viên
     *
     * @return mixed
     */
    public function dataViewIndex()
    {
        $mBranch = new BranchTable();
        $mStaff = new StaffTable();
        $optionBranch = $mBranch->getOption();
        $optionStaff = $mStaff->getOption();
        return [
            'optionBranch' => $optionBranch,
            'optionStaff' => $optionStaff
        ];
    }

    /**
     * filter thời gian, chi nhánh, số lượng nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function filterAction($input)
    {
        $mReceipt = new ReceiptTable();
        $mOrder = new OrderTable();

        // Declare input: khai báo
        $time = $input['time'];
        $branchId = $input['branch'];
        $numberStaff = $input['numberStaff'];
        $staffId = $input['staffId'];
        $startTime = $endTime = null;

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        // START CHART COLUMN
        // Tổng doanh thu
        $arrReceiptStatus = ['paid', 'part-paid'];
        $arrOrderStatus = ['paysuccess', 'pay-half'];
        // Query: Lấy dữ liệu doanh thu theo đơn đã thanh toán và thanh toán 1 phần
        $dataGroupByOrderId = $mOrder->getAllOrderByStatus($startTime, $endTime, $arrOrderStatus, $branchId, null)->toArray();
        $totalOrderPaid = count($dataGroupByOrderId); // Tổng đơn đã thanh toán và thanh toán 1 phần

        // Lấy tất cả nhân viên trong bảng receipt (created_by) theo filter -> tính doanh thu -> limit
        $arrayStaff = $mOrder->getAllStaff($startTime, $endTime, $branchId, $numberStaff,$staffId)->toArray();
        $dataStaff  = $this->processDataStaff($arrayStaff, $dataGroupByOrderId);

        // data total (số tiền, số đơn) cho 4 tag
        $dataTotal = [
            'totalOrder' => $dataStaff['totalOrderPaid'],
            'totalMoney' => $dataStaff['totalMoneyPaid'] + $dataStaff['totalMoneyUnpaid'],
            'totalOrderPaySuccess' => $dataStaff['totalOrderPaid'],
            'totalMoneyOrderPaySuccess' => $dataStaff['totalMoneyPaid'],
            'totalOrderNew' => $dataStaff['totalOrderUnpaid'],
            'totalMoneyOrderNew' => $dataStaff['totalMoneyUnpaid'],
        ];

        $dataReturn = [
            'arrayCategories' => $dataStaff['dataCategories'],
            'dataSeries' => $dataStaff['dataSeries'],
            'countListStaff' => $dataStaff['countListStaff'],
            'total' => $dataTotal,
            'dataByReceiptType' => '',
            'dataList' => '',
            'arrayStaff' => $arrayStaff
        ];
        return response()->json($dataReturn);
    }

    /**
     * Xử lý data của nhân viên đưa về dạng chart và 4 tag
     *
     * @param $arrayStaff
     * @param $dataPaidUnPaid
     * @return array
     */
    private function processDataStaff($arrayStaff, $dataPaidUnPaid)
    {
        $listStaffForTable = [];
        $arrayStaffId = [];
        $arrayStaffName = [];
        $totalMoneyPaid = 0; // Tổng tiền đã thanh toán
        $totalOrderMoney = 0; // Tổng tiền đơn hàng
        $totalMoneyUnpaid = 0; // Tổng tiền chưa thanh toán
        $totalOrderUnpaid = 0; // Tổng đơn chưa thanh toán
        $totalMoneyCancel = 0; // Tổng tiền huỷ
        $totalOrderPaid = 0; // tổng đơn

        if (count($arrayStaff) > 0) {
            foreach ($arrayStaff as $value) {
                $listStaffForTable[$value['staff_id']] = [
                    'staffName' => $value['staff_name'],
                    'totalMoney' => 0.00,  // Tiền hàng
                    'totalMoneyDiscount' => 0.00,  // Tiền giảm giá
                    'totalDeliveryCharge' => 0.00,  // Phí giao hàng
                    'totalMoneyReturn' => 0.00,     // Tiền hàng trả lại
                    'totalMoneyRevenue' => 0.00     // Doanh thu
                ];
                // Mảng staff id để tính tổng tiền
                $arrayStaffId[$value['staff_id']] = [
                    'totalMoneyPaid' => 0,
                    'totalMoneyUnPaid' => 0
                ];
                // Mảng staff name để làm danh mục cho biểu đồ
                $arrayStaffName [] = $value['staff_name'];
            }
        }
        // Data: Đã thanh toán + chưa thanh toán
        foreach ($dataPaidUnPaid as $key => $value) {
            if (isset($arrayStaffId[$value['staff_id']])) {
                $arrayStaffId[$value['staff_id']]['totalMoneyPaid'] += $value['total_receipt'];
                $arrayStaffId[$value['staff_id']]['totalMoneyUnPaid'] += ($value['order_amount'] - $value['total_receipt']);
                // data cho 4 tag
                $totalMoneyPaid += $value['total_receipt'];
                $totalOrderMoney += $value['order_amount'];
                if ($value['status'] == 'pay-half') {
                    $totalOrderUnpaid += 1;
                }
                $totalOrderPaid += 1;
            }
        }
        $totalMoneyUnpaid = $totalOrderMoney - $totalMoneyPaid; // Tổng tiền chưa thanh toán
        // Chuyển data cho đúng định dạng của biểu đồ
        $arrTotalPaid = [];
        $arrTotalUnPaid = [];
        foreach ($arrayStaffId as $k => $v) {
            $arrTotalPaid [] = round($v['totalMoneyPaid'], 2);
            $arrTotalUnPaid [] = round($v['totalMoneyUnPaid'], 2);
        }
        $dataSeries = [
            [
                'name' => __('Số tiền chưa thanh toán'),
                'data' => $arrTotalUnPaid
            ],
            [
                'name' => __('Số tiền đã thanh toán'),
                'data' => $arrTotalPaid
            ],
        ];

        return [
            'dataCategories' => $arrayStaffName,
            'dataSeries' => $dataSeries,
            'countListStaff' => count($arrayStaffName), // Số lượng nhân viên
            'totalOrderMoney' => $totalOrderMoney,
            'totalOrderUnpaid' => $totalOrderUnpaid,
            'totalOrderPaid' => $totalOrderPaid,
            'totalMoneyPaid' => $totalMoneyPaid,
            'totalMoneyUnpaid' => $totalMoneyUnpaid,
            'totalMoneyCancel' => $totalMoneyCancel,
        ];
    }
    /**
     * Ds chi tiết của chart
     *
     * @param $input
     * @return array|mixed
     */
    public function listDetail($input)
    {
        $arrStaffId = [];
        $arrStaff[] = json_decode($input['number_staff_detail']);
        for ($i = 0;$i < count($arrStaff);$i++){
            foreach($arrStaff[$i] as $item){
                $val = (array)$item;
                array_push($arrStaffId,"{$val['staff_id']}");
            }
        }
        $input['arr_staff'] = $arrStaffId;
        $mOrder = new OrderTable();
        $list = $mOrder->getListDetailStaff($input);

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
            __('TỔNG TIỀN ĐÃ THANH TOÁN'),
            __('TIỀN TIỀN CHƯA THANH TOÁN')
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $arrStaffId = [];
        $arrStaff[] = json_decode($input['export_number_staff_total']);
        for ($i = 0;$i < count($arrStaff);$i++){
            foreach($arrStaff[$i] as $item){
                $val = (array)$item;
                array_push($arrStaffId,"{$val['staff_id']}");
            }
        }
        $input['arr_staff'] = $arrStaffId;
        $mOrder = new OrderTable();
        $allData = $mOrder->getListExportTotalStaff($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['branch_name'],
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
            __('TÊN NHÂN VIÊN'),
            __('TÊN CHI NHÁNH'),
            __('TIỀN ĐÃ THANH TOÁN'),
            __('TIỀN CHƯA THANH TOÁN'),
            __('NGÀY MUA'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $arrStaffId = [];
        $arrStaff[] = json_decode($input['export_number_staff_detail']);
        for ($i = 0;$i < count($arrStaff);$i++){
            foreach($arrStaff[$i] as $item){
                $val = (array)$item;
                array_push($arrStaffId,"{$val['staff_id']}");
            }
        }
        $input['arr_staff'] = $arrStaffId;
        $mOrder = new OrderTable();
        $allData = $mOrder->getListExportDetailStaff($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['order_code'],
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