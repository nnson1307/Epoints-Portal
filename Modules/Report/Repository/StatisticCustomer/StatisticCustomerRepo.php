<?php

namespace Modules\Report\Repository\StatisticCustomer;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\BranchTable;
use Modules\Report\Models\OrderTable;
use Modules\Report\Models\StatisticCustomerTable;

class StatisticCustomerRepo implements StatisticCustomerRepoInterface
{
    /**
     * Data cho view index (danh sách chi nhánh)
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
        $arrayCategory = [];
        $mStatisticCustomer = new StatisticCustomerTable();
        $mBranch = new BranchTable();

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $getStatisticCusAll = $mStatisticCustomer->getAllByFilter($startTime, $endTime, $branchId)->toArray();
//        dd($getStatisticCusAll);
        if ($branchId != null) {
            // Array days
            $dateDiff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            for ($i = 0; $i < $dateDiff; $i++) {
                $timeTmp = Carbon::parse($startTime)->addDay($i)->format('d/m/Y');
                $arrayCategory [$timeTmp] = [
                    'category_name' => $timeTmp,
                    'totalCus' => 0,
                    'totalCusNew' => 0,
                    'totalCusOld' => 0,
                    'totalCusHaunt' => 0,
                ];
            }
            // Xử lí biểu đồ miền
            $chartCustomer = $this->dataChartCustomer($arrayCategory, $getStatisticCusAll, false);
        } else {
            $optionBranch = $mBranch->getOption();
            foreach ($optionBranch as $key => $value) {
                $arrayCategory [$value['branch_id']] = [
                    'category_name' => $value['branch_name'],
                    'totalCus' => 0,
                    'totalCusNew' => 0,
                    'totalCusOld' => 0,
                    'totalCusHaunt' => 0,
                ];
            }
            // Xử lí biểu đồ miền
            $chartCustomer = $this->dataChartCustomer($arrayCategory, $getStatisticCusAll);
        }
        // Biểu đồ tròn: giới tính
        $getStatisticCusByGender = $mStatisticCustomer->getAllByFilterGroupByGender($startTime, $endTime, $branchId)->toArray();
        $chartGender = $this->dataChartGender($getStatisticCusByGender);
        // Biểu đồ tròn: nguồn khách hàng (CS: customer source)
        $getStatisticCusByCS = $mStatisticCustomer->getAllByFilterGroupByCS($startTime, $endTime, $branchId)->toArray();
        $chartCustomerSource = $this->dataChartCustomerSource($getStatisticCusByCS);

        return [
            'chartCustomer' => $chartCustomer,
            'chartCustomerSource' => $chartCustomerSource,
            'chartGender' => $chartGender
        ];
    }

    // Xử lý data cho biểu đồ miền
    private function dataChartCustomer($arrayCategory, $allStatisticCusByGender, $isAllBranch = true)
    {
        if ($isAllBranch) {
            foreach ($allStatisticCusByGender as $value) {
                if (isset($arrayCategory[$value['branch_id']])) {
                    $arrayCategory[$value['branch_id']]['totalCus'] += ($value['customer_new'] + $value['customer_old'] + $value['customer_haunt']);
                    $arrayCategory[$value['branch_id']]['totalCusNew'] += $value['customer_new'];
                    $arrayCategory[$value['branch_id']]['totalCusOld'] += $value['customer_old'];
                    $arrayCategory[$value['branch_id']]['totalCusHaunt'] += $value['customer_haunt'];
                }
            }
        } else {
            foreach ($allStatisticCusByGender as $value) {
                $timeTemp = Carbon::parse($value['created_at'])->format('d/m/Y');
                if (isset($arrayCategory[$timeTemp])) {
                    $arrayCategory[$timeTemp]['totalCus'] += ($value['customer_new'] + $value['customer_old'] + $value['customer_haunt']);
                    $arrayCategory[$timeTemp]['totalCusNew'] += $value['customer_new'];
                    $arrayCategory[$timeTemp]['totalCusOld'] += $value['customer_old'];
                    $arrayCategory[$timeTemp]['totalCusHaunt'] += $value['customer_haunt'];
                }
            }
        }
        // Đưa về đúng dạng biểu đồ
        $dataReturn[] = ['', __('TỔNG SỐ KH'), __('KHÁCH MỚI'), __('KHÁCH CŨ'), __('KH VÃNG LAI')];
        foreach ($arrayCategory as $key => $value) {
            $dataReturn[] = [
                $value['category_name'], (int)$value['totalCus'],
                (int)$value['totalCusNew'], (int)$value['totalCusOld'], (int)$value['totalCusHaunt']
            ];
        }
        return $dataReturn;
    }

    // Xử lý data cho biểu đồ tròn: giới tính
    private function dataChartGender($allStatisticCusByGender)
    {
        $dataReturn = [['Gender', 'Amount']];
        foreach ($allStatisticCusByGender as $value) {
            if ($value['gender'] == 'male') {
                $dataReturn [] = [
                      __('Nam'), $value['customer_new'] + $value['customer_old'] + $value['customer_haunt']
                ];
            } elseif ($value['gender'] == 'female') {
                $dataReturn [] = [
                    __('Nữ'), $value['customer_new'] + $value['customer_old'] + $value['customer_haunt']
                ];
            } else {
                $dataReturn [] = [
                    __('Khác'), $value['customer_new'] + $value['customer_old'] + $value['customer_haunt']
                ];
            }
        }
        return $dataReturn;
    }

    // Xử lý data cho biểu đồ tròn: nguồn khách hàng (CS: customer source)
    private function dataChartCustomerSource($allStatisticCusByCS)
    {
        $dataReturn = [['Customer source', 'Amount']];
        foreach ($allStatisticCusByCS as $value) {
            $dataReturn [] = [
                $value['customer_source_name'], $value['customer_new'] + $value['customer_old'] + $value['customer_haunt']
            ];
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
        $mOrders = new OrderTable();
        $list = $mOrders->getListDetailStatisticsCustomer($input);

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
            __('KHÁCH HÀNG MỚI'),
            __('KHÁCH HÀNG CŨ'),
            __('KHÁCH HÀNG VÃNG LAI')
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mStatisticCustomer = new StatisticCustomerTable();
        $allData = $mStatisticCustomer->getListExportTotalStatisticsCustomer($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['branch_name'],
                    $item['customer_new'],
                    $item['customer_old'],
                    $item['customer_haunt'],
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
            __('NGÀY MUA'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mOrders = new OrderTable();
        $allData = $mOrders->getListExportDetailStatisticsCustomer($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['order_code'],
                    $item['full_name'],
                    $item['branch_name'],
                    $item['status'],
                    date("d/m/Y h:i",strtotime($item['created_at']))
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-detail.xlsx');
    }
}