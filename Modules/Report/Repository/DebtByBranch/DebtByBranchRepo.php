<?php

namespace Modules\Report\Repository\DebtByBranch;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\BranchTable;
use Modules\Report\Models\CustomerDebtTable;

class DebtByBranchRepo implements DebtByBranchRepoInterface
{
    /**
     * Data cho View báo cáo công nợ theo chi nhánh
     *
     * @return array|mixed
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
        $arrayCategories = [];
        $dataSeries = [];
        $mCustomerDebt = new CustomerDebtTable();
        $mBranch = new BranchTable();

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $allDataDebt = $mCustomerDebt->getAllDataDebt($startTime, $endTime, $branchId)->toArray();
        if ($branchId != "") {
            // Chia cột theo ngày
            $arrayDate = [];
            $dateDiff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            for ($i = 0; $i < $dateDiff; $i++) {
                $timeTmp = Carbon::parse($startTime)->addDay($i)->format('d/m/Y');
                $arrayDate [$timeTmp] = [
                    'totalMoneyPaid' => 0,
                    'totalMoneyUnPaid' => 0
                ];
                $arrayCategories [] = $timeTmp;
            }
            // Xử lý data cho biểu đổ
            $dataForChart = $this->processDataForChart($arrayDate, $allDataDebt, false);
        } else {
            // Chia cột theo chi nhánh
            $arrayBranchId = [];
            $optionBranch = $mBranch->getOption();
            foreach ($optionBranch as $key => $value) {
                $arrayCategories[] = $value['branch_name'];
                $arrayBranchId[$value['branch_id']] = [
                    'totalMoneyPaid' => 0,
                    'totalMoneyUnPaid' => 0
                ];
            }
            // Xử lý data cho biểu đổ, 4 tag
            $dataForChart = $this->processDataForChart($arrayBranchId, $allDataDebt, true);
        }
        return [
            'arrayCategories' => $arrayCategories,
            'dataSeries' => $dataForChart['dataSeries'],
            'totalAll' => $dataForChart['totalAll'],
            'amountAll' => $dataForChart['amountAll'],
            'totalPaid' => $dataForChart['totalPaid'],
            'amountPaid' => $dataForChart['amountPaid'],
            'totalUnPaid' => $dataForChart['totalUnPaid'],
            'amountUnPaid' => $dataForChart['amountUnPaid']
        ];
    }

    /**
     * Hàm xử lý data công nợ cho biểu đồ
     *
     * @param $arrayObject
     * @param $allDataDebt
     * @param $isAllBranch
     * @return array[]
     */
    private function processDataForChart($arrayObject, $allDataDebt, $isAllBranch)
    {
        $amountAll = 0;      // Tổng tiền
        $totalAll = 0;       // Tổng đơn hàng
        $amountPaid = 0;     // Tổng tiền đã thanh toán
        $totalPaid = 0;      // Tổng đơn hàng đã thanh toán
        $amountUnPaid = 0;   // Tổng tiền chưa đã thanh toán
        $totalUnPaid = 0;    // Tổng đơn hàng chưa đã thanh toán

        // Nếu là tất cả chi nhánh thì chia theo chi nhánh, nếu là 1 chi nhánh thì chia theo ngày
        if ($isAllBranch == true) {
            foreach ($allDataDebt as $value) {
                // Tiền đã thanh toán từng chi nhánh
                if (isset($arrayObject[$value['branch_id']])) {
                    $arrayObject[$value['branch_id']]['totalMoneyPaid'] += $value['amount_paid'];
                    $arrayObject[$value['branch_id']]['totalMoneyUnPaid'] += ($value['amount'] - $value['amount_paid']);
                }
                // Dữ liệu tiền + số đơn hàng cho 4 tag
                switch ($value['status']) {
                    case 'paid':
                        $amountPaid += $value['amount_paid'];
                        $totalPaid += 1;
                        break;
                    case 'part-paid':
                        $amountPaid += $value['amount_paid'];
                        $amountUnPaid = $amountUnPaid + $value['amount'] - $value['amount_paid'];
                        $totalPaid += 1;
                        $totalUnPaid += 1;
                        break;
                    case 'unpaid' :
                        $amountUnPaid += $value['amount'];
                        $totalUnPaid += 1;
                        break;
                }
            }
        } else {
            foreach ($allDataDebt as $value) {
                // Tiền đã thanh toán từng ngày
                $timeTemp = Carbon::parse($value['created_at'])->format('d/m/Y');
                if (isset($arrayObject[$timeTemp])) {
                    $arrayObject[$timeTemp]['totalMoneyPaid'] += $value['amount_paid'];
                    $arrayObject[$timeTemp]['totalMoneyUnPaid'] += ($value['amount'] - $value['amount_paid']);
                }
                // Dữ liệu tiền + số đơn hàng cho 4 tag
                switch ($value['status']) {
                    case 'paid':
                        $amountPaid += $value['amount_paid'];
                        $totalPaid += 1;
                        break;
                    case 'part-paid':
                        $amountPaid += $value['amount_paid'];
                        $amountUnPaid = $amountUnPaid + $value['amount'] - $value['amount_paid'];
                        $totalPaid += 1;
                        $totalUnPaid += 1;
                        break;
                    case 'unpaid' :
                        $amountUnPaid += $value['amount'];
                        $totalUnPaid += 1;
                        break;
                }
            }
        }
        // Chuyển data cho đúng định dạng của biểu đồ
        $arrTotalPaid = [];
        $arrTotalUnPaid = [];
        foreach ($arrayObject as $k => $v) {
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
            'dataSeries' => $dataSeries,
            'totalAll' => count($allDataDebt),
            'amountAll' => round($amountPaid + $amountUnPaid, 2),
            'totalPaid' => $totalPaid,
            'amountPaid' => round($amountPaid, 2),
            'totalUnPaid' => $totalUnPaid,
            'amountUnPaid' => round($amountUnPaid, 2),
        ];
    }
    /**
     * Ds chi tiết của chart công nợ chi nhánh
     *
     * @param $input
     * @return array|mixed
     */
    public function listDetail($input)
    {
        $mCustomerDebt = new CustomerDebtTable();
        $list = $mCustomerDebt->getListDetailDebtByBranch($input);

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
        $mCustomerDebt = new CustomerDebtTable();
        $allData = $mCustomerDebt->getListExportTotalDebtByBranch($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['branch_name'],
                    $item['amount_paid'],
                    (string)((float)$item['amount'] - (float)$item['amount_paid'])
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
            __('MÃ CÔNG NỢ'),
            __('TÊN KHÁCH HÀNG'),
            __('TÊN CHI NHÁNH'),
            __('TIỀN ĐÃ THANH TOÁN'),
            __('TIỀN CHƯA THANH TOÁN'),
            __('NGÀY MUA HÀNG'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mCustomerDebt = new CustomerDebtTable();
        $allData = $mCustomerDebt->getListExportDetailDebtByBranch($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['debt_code'],
                    $item['full_name'],
                    $item['branch_name'],
                    $item['amount_paid'],
                    (string)((float)$item['amount'] - (float)$item['amount_paid']),
                    date("d/m/Y h:i",strtotime($item['created_at']))
                ];
            }
        }
        return Excel::download(new ExportFile($heading, $data), 'export-detail.xlsx');
    }

}