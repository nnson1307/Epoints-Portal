<?php

namespace Modules\Report\Repository\StatisticOrder;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\BranchTable;
use Modules\Report\Models\CustomerAppointmentTable;
use Modules\Report\Models\OrderTable;

class StatisticOrderRepo implements StatisticOrderRepoInterface
{
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
        $mBranch = new BranchTable();
        $arrayCategory = [];

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        // Lấy những đơn hàng có status là: new, paysuccess, ordercancle
        $getAllOrder = $mOrder->getOrderByFilterAndStatus($startTime, $endTime, $branchId)->toArray();
        // Biểu đồ cột
        if ($branchId != null) {
            // array days
            $dateDiff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            for ($i = 0; $i < $dateDiff; $i++) {
                $timeTmp = Carbon::parse($startTime)->addDay($i)->format('d/m/Y');
                $arrayCategory [$timeTmp] = [
                    'category_name' => $timeTmp,
                    'new' => 0,
                    'confirmed' => 0,
                    'ordercancle' => 0,
                    'paysuccess' => 0,
                    'pay-half' => 0,
                    'payfail' => 0,
                ];
            }
            $chartOrder = $this->dataChartOrder($getAllOrder, $arrayCategory, false);
        } else {
            // array branch
            $optionBranch = $mBranch->getOption();
            foreach ($optionBranch as $key => $value) {
                $arrayCategory [$value['branch_id']] = [
                    'category_name' => $value['branch_name'],
                    'new' => 0,
                    'confirmed' => 0,
                    'ordercancle' => 0,
                    'paysuccess' => 0,
                    'pay-half' => 0,
                    'payfail' => 0,
                ];
            }
            $chartOrder = $this->dataChartOrder($getAllOrder, $arrayCategory);
        }

        // Biểu đồ tròn: Nhóm khách hàng
        $chartCustomerGroup = $this->dataChartCustomerGroup($startTime, $endTime, $branchId);
        // Biểu đồ tròn: Trạng thái
        $chartStatus = $this->dataChartStatus($startTime, $endTime, $branchId);
        // Biểu đồ tròn: Nguồn đơn hàng
        $chartOrderSource = $this->dataChartOrderSource($startTime, $endTime, $branchId);

        return response()->json([
            'dataChartOrder' => $chartOrder,
            'dataChartCustomerGroup'=> $chartCustomerGroup,
            'dataChartStatus'=> $chartStatus,
            'dataChartOrderSource'=> $chartOrderSource
        ]);
    }
    // Xử lý data cho biểu đồ cột
    private function dataChartOrder($dataOrder, $arrayCategory, $isAllBranch = true)
    {
        $arrNew = $arrSuccess = $arrFail = [];
        $dataGroup = collect($dataOrder)->groupBy("process_status");
        if ($isAllBranch) {
            foreach ($dataGroup as $k => $v) {
                foreach ($v as $key => $value) {
                    if (isset($arrayCategory[$value['branch_id']])) {
                        $arrayCategory[$value['branch_id']][$k] += 1;
                    }
                }
            }
        } else {
            foreach ($dataGroup as $k => $v) {
                foreach ($v as $key => $value) {
                    $timeTemp = Carbon::parse($value['created_at'])->format('d/m/Y');
                    if (isset($arrayCategory[$timeTemp])) {
                        $arrayCategory[$timeTemp][$k] += 1;
                    }
                }
            }
        }
        // Đưa data về đúng dạng biểu đồ
        $dataReturn  = [[
            '', __('ĐƠN HÀNG MỚI'), __('XÁC NHẬN'), __('HỦY'), __('HOÀN THÀNH'),
            __('THANH TOÁN CÒN THIẾU'), __('THANH TOÁN THẤT BẠI')
        ]];
        foreach ($arrayCategory as $value) {
            $dataReturn [] = [
                $value['category_name'], $value['new'], $value['confirmed'], $value['ordercancle'],
                $value['paysuccess'], $value['pay-half'], $value['payfail'],
            ];
        }
        return $dataReturn;
    }
    // Xử lý data cho biểu đồ tròn: thống kê đơn hàng theo nguồn khách hàng
    // Sửa thành nhóm khách hàng (02/02/2021)
    private function dataChartCustomerGroup($startTime, $endTime, $branchId)
    {
        // customer_id = 1: khách hàng vãng lai -> nhóm khách hàng: Khác
        $mOrder = new OrderTable();
        $dataReturn = [['Customer group', 'Amount']];
        // Khách hàng vãng lai
        $arrNumberOrderCusCurrent = $mOrder->getNumberOrderByCustomerGroup($startTime, $endTime, $branchId);
        if ($arrNumberOrderCusCurrent != null) {
            $dataReturn [] = [
                __('Khách hàng khác'),
                $arrNumberOrderCusCurrent['number']
            ];
        } else {
            $dataReturn [] = [
                __('Khách hàng khác'),
                0
            ];
        }

        // Các nhóm khách hàng còn lại
        $arrNumberOrderCusSrc = $mOrder->getNumberOrderByCustomerGroup($startTime, $endTime, $branchId, false)->toArray();
        foreach ($arrNumberOrderCusSrc as $value) {
            $dataReturn [] = [$value['group_name'], $value['number']];
        }
        return $dataReturn;
    }
    // Xử lý data cho biểu đồ tròn: thống kê đơn hàng theo status
    private function dataChartStatus($startTime, $endTime, $branchId)
    {
        $mOrder = new OrderTable();
        $dataReturn = [
            ['Status', 'Amount'],
            [__('Đơn hàng mới'), 0],
            [__('Xác nhận'), 0],
            [__('Hủy'), 0],
            [__('Hoàn thành'), 0],
            [__('Thanh toán còn thiếu'), 0],
            [__('Thanh toán thất bại'), 0],
        ];
        $arrNumberOrderStatus = $mOrder->getNumberOrderByStatus($startTime, $endTime, $branchId)->toArray();
        foreach ($arrNumberOrderStatus as $value) {
            switch ($value['process_status']) {
                case 'new' : $dataReturn [1][1] = $value['number']; break;
                case 'confirmed' : $dataReturn [2][1] = $value['number']; break;
                case 'ordercancle' : $dataReturn [3][1] = $value['number']; break;
                case 'paysuccess' : $dataReturn [4][1] = $value['number']; break;
                case 'pay-half' : $dataReturn [5][1] = $value['number']; break;
                case 'payfail' : $dataReturn [6][1] = $value['number']; break;
            }
        }
        return $dataReturn;
    }
    // Xử lý data cho biểu đồ tròn: thống kê đơn hàng theo nguồn đơn hàng
    private function dataChartOrderSource($startTime, $endTime, $branchId)
    {
        $mOrder = new OrderTable();
        $dataReturn = [['Order source', 'Amount']];
        $arrNumberOrderSource = $mOrder->getNumberOrderByOrderSource($startTime, $endTime, $branchId)->toArray();
        foreach ($arrNumberOrderSource as $value) {
            $dataReturn [] = [$value['order_source_name'], $value['number']];
        }
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
        $mOrder = new OrderTable();
        $list = $mOrder->getListDetailStatisticsOrder($input);

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
            __('TỔNG SỐ ĐƠN'),
            __('SỐ ĐƠN MỚI'),
            __('SỐ ĐƠN ĐÃ XÁC NHẬN'),
            __('SỐ ĐƠN ĐÃ HUỶ'),
            __('SỐ ĐƠN THANH TOÁN 1 PHẦN'),
            __('SỐ ĐƠN HOÀN THÀNH'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mOrder = new OrderTable();
        $allData = $mOrder->getListExportTotalStatisticsOrder($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['branch_name'],
                    $item['total'] == '' ? '0' : $item['total'],
                    $item['new'] == '' ? '0' : $item['new'],
                    $item['confirmed'] == '' ? '0' : $item['confirmed'],
                    $item['ordercancle'] == '' ? '0' : $item['ordercancle'],
                    $item['payhalf'] == '' ? '0' : $item['payhalf'],
                    $item['paysuccess'] == '' ? '0' : $item['paysuccess']
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
            __('TRẠNG THÁI'),
            __('NGÀY MUA HÀNG'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mOrder = new OrderTable();
        $allData = $mOrder->getListExportDetailStatisticsOrder($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['order_code'],
                    $item['full_name'],
                    $item['branch_name'],
                    $item['process_status'],
                    date("d/m/Y h:i",strtotime($item['created_at']))
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-detail.xlsx');
    }
}