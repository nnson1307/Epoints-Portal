<?php


namespace Modules\Report\Repository\RevenueByCustomer;


use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\CustomerTable;
use Modules\Report\Models\BranchTable;
use Modules\Report\Models\OrderTable;
use Modules\Report\Models\ReceiptTable;

class RevenueByCustomerRepo implements RevenueByCustomerRepoInterface
{
    /**
     * View báo cáo doanh thu theo khách hàng
     *
     * @return array
     */
    public function dataViewIndex()
    {
        $mBranch = new BranchTable();
        $mCustomer = new CustomerTable();
        $optionBranch = $mBranch->getOption();
        $optionCustomer = $mCustomer->getCustomerOption();
        return [
            'optionBranch' => $optionBranch,
            'optionCustomer' => $optionCustomer
        ];
    }

    /**
     * filter time, branch, number customer
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function filterAction($data)
    {
        $mOrder = new OrderTable();
        // Declare input: khai báo
        $time = $data['time'];
        $branchId = $data['branch'];
        $numberCustomer = $data['numberCustomer'];
        $customerId = isset($data['customerId']) != '' ? $data['customerId'] : null;
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
        $dataGroupByOrderId = $mOrder->getAllOrderByStatus($startTime, $endTime, $arrOrderStatus, $branchId, null,$customerId)->toArray();
        $totalOrderPaid = count($dataGroupByOrderId); // Tổng đơn đã thanh toán và thanh toán 1 phần

        // Lấy tất cả khách hàng trong bảng order theo filter -> limit -> tính doanh thu
        $arrayCustomer = $mOrder->getAllCustomer($startTime, $endTime, $branchId, $numberCustomer, $customerId)->toArray();
        $dataCustomer  = $this->processCustomerData($arrayCustomer, $dataGroupByOrderId);
        // data total (số tiền, số đơn) cho 4 tag
        $dataTotal = [
            'totalOrder' => $dataCustomer['totalOrderPaid'],
            'totalMoney' => $dataCustomer['totalMoneyPaid'] + $dataCustomer['totalMoneyUnpaid'],
            'totalOrderPaySuccess' => $dataCustomer['totalOrderPaid'],
            'totalMoneyOrderPaySuccess' => $dataCustomer['totalMoneyPaid'],
            'totalOrderNew' => $dataCustomer['totalOrderUnpaid'],
            'totalMoneyOrderNew' => $dataCustomer['totalMoneyUnpaid'],
        ];
        $dataReturn = [
            'arrayCategories' => $dataCustomer['dataCategories'],
            'dataSeries' => $dataCustomer['dataSeries'],
            'countListCustomer' => $dataCustomer['countListCustomer'],
            'total' => $dataTotal,
            'dataByReceiptType' => '',
            'dataList' => '',
            'arrayCustomer' => $arrayCustomer
        ];
        return response()->json($dataReturn);
    }

    /**
     * Xử lý data của khách hàng đưa về dạng chart và 4 tag
     *
     * @param $arrayCustomer
     * @param $dataPaidUnPaid
     * @return array[]
     */
    private function processCustomerData($arrayCustomer, $dataPaidUnPaid)
    {
        $listCustomerForTable = [];
        $arrayCustomerId = [];
        $arrayCustomerName = [];
        $totalMoneyPaid = 0; // Tổng tiền đã thanh toán
        $totalOrderMoney = 0; // Tổng tiền đơn hàng
        $totalMoneyUnpaid = 0; // Tổng tiền chưa thanh toán
        $totalOrderUnpaid = 0; // Tổng đơn chưa thanh toán
        $totalMoneyCancel = 0; // Tổng tiền huỷ
        $totalOrderPaid = 0; // tổng đơn

        if (count($arrayCustomer) > 0) {
            foreach ($arrayCustomer as $value) {
                $listCustomerForTable[$value['customer_id']] = [
                    'customerName' => $value['customer_name'],
                    'customerPhone' => $value['customer_phone'],
                    'totalOrder' => 0,
                    'totalProductSold' => 0,
                    'totalProductReturn' => 0,
                    'totalMoneyReturn' => 0.00,
                    'totalMoneyRevenue' => 0.00
                ];
                // Mảng customer id để tính tổng tiền
                $arrayCustomerId[$value['customer_id']] = [
                    'totalMoneyPaid' => 0,
                    'totalMoneyUnPaid' => 0
                ];
                // Mảng customer name để làm danh mục cho biểu đồ
                $arrayCustomerName [] = $value['customer_name'];
            }
        }
        // Data: Đã thanh toán + chưa thanh toán
        foreach ($dataPaidUnPaid as $key => $value) {
            if (isset($arrayCustomerId[$value['customer_id']])) {
                $arrayCustomerId[$value['customer_id']]['totalMoneyPaid'] += $value['total_receipt'];
                $arrayCustomerId[$value['customer_id']]['totalMoneyUnPaid'] += ($value['order_amount'] - $value['total_receipt']);
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
        foreach ($arrayCustomerId as $k => $v) {
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
            'dataCategories' => $arrayCustomerName,
            'dataSeries' => $dataSeries,
            'countListCustomer' => count($arrayCustomerName), // Số lượng khách hàng
            'totalOrderMoney' => $totalOrderMoney,
            'totalOrderUnpaid' => $totalOrderUnpaid,
            'totalOrderPaid' => $totalOrderPaid,
            'totalMoneyPaid' => $totalMoneyPaid,
            'totalMoneyUnpaid' => $totalMoneyUnpaid,
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
        $arrCustomerId = [];
        $arrCustomer[] = json_decode($input['number_customer_detail']);
        for ($i = 0;$i < count($arrCustomer);$i++){
            foreach($arrCustomer[$i] as $item){
                $val = (array)$item;
                array_push($arrCustomerId,"{$val['customer_id']}");
            }
        }
        $input['arr_customer'] = $arrCustomerId;
        $mOrder = new OrderTable();
        $list = $mOrder->getListDetailCustomer($input);

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
        $arrCustomerId = [];
        $arrCustomer[] = json_decode($input['export_number_customer_total']);
        for ($i = 0;$i < count($arrCustomer);$i++){
            foreach($arrCustomer[$i] as $item){
                $val = (array)$item;
                array_push($arrCustomerId,"{$val['customer_id']}");
            }
        }
        $input['arr_customer'] = $arrCustomerId;
        $mOrder = new OrderTable();
        $allData = $mOrder->getListExportTotalCustomer($input);
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
        $arrCustomerId = [];
        $arrCustomer[] = json_decode($input['export_number_customer_detail']);
        for ($i = 0;$i < count($arrCustomer);$i++){
            foreach($arrCustomer[$i] as $item){
                $val = (array)$item;
                array_push($arrCustomerId,"{$val['customer_id']}");
            }
        }
        $input['arr_customer'] = $arrCustomerId;
        $mOrder = new OrderTable();
        $allData = $mOrder->getListExportDetailCustomer($input);
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