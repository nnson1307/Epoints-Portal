<?php

namespace Modules\Report\Repository\StatisticService;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\OrderDetailTable;
use Modules\Report\Models\ServiceTable;

class StatisticServiceRepo implements StatisticServiceRepoInterface
{
    /**
     * Data option service view index
     *
     * @return array|mixed
     */
    public function dataViewIndex()
    {
        $mService = new ServiceTable();
        $optionService = $mService->getOption();
        return [
            'optionService' => $optionService
        ];
    }

    public function filterAction($input)
    {
        $time = $input['time'];
        $serviceId = $input['service'];
        $startTime = $endTime = null;
        $mOrderDetail = new OrderDetailTable();
        $mService = new ServiceTable();

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        // Lấy danh sách order detail theo service
        $dataService = $mOrderDetail->getAllDetailByFilterAndObjectType($startTime, $endTime, $serviceId, 'service')->toArray();
        // Nếu tất cả thẻ dịch vụ thi từng cột là dịch vụ, nếu một dịch vụ thì từng cột là ngày
        $arrayCategory = [];
        if ($serviceId != null) {
            $dateDiff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            for ($i = 0; $i < $dateDiff; $i++) {
                $timeTmp = Carbon::parse($startTime)->addDay($i)->format('d/m/Y');
                $arrayCategory [$timeTmp] = [
                    'category_name' => $timeTmp,
                    'total' => 0,
                ];
            }
            // Xử lý data cho biểu đồ cột (tất cả dịch vụ hoặc 1 dịch vụ)
            $chartColumn = $this->dataChartColumn($dataService, $arrayCategory, false);
        } else {
            $optionService = $mService->getOption();
            foreach ($optionService as $key => $value) {
                $arrayCategory [$value['service_id']] = [
                    'category_name' => $value['service_name'],
                    'total' => 0,
                ];
            }
            // Xử lý data cho biểu đồ cột (tất cả dịch vụ hoặc 1 dịch vụ)
            $chartColumn = $this->dataChartColumn($dataService, $arrayCategory);
        }
        // Xử lý data các biểu đồ tròn
        // customer group
        $chartCustomerGroup = $this->dataChartCustomerGroup($startTime, $endTime, $serviceId);
        // service card group
        $chartServiceCardGroup = $this->dataChartServiceCardGroup($startTime, $endTime, $serviceId);
        // branch
        $chartBranch = $this->dataChartBranch($startTime, $endTime, $serviceId);
        return [
            'dataChartColumn' => $chartColumn,
            'dataChartCustomerGroup' => $chartCustomerGroup,
            'dataChartServiceCardGroup' => $chartServiceCardGroup,
            'dataChartBranch' => $chartBranch
        ];
    }

    // Xử lý data cho biểu đồ cột
    private function dataChartColumn($dataService, $arrayCategory, $isAllService = true)
    {
        if ($isAllService) {
            foreach ($dataService as $value) {
                if (isset($arrayCategory[$value['object_id']])) {
                    $arrayCategory[$value['object_id']]['total'] += $value['quantity'];
                }
            }
        } else {
            foreach ($dataService as $value) {
                $timeTemp = Carbon::parse($value['created_at'])->format('d/m/Y');
                if (isset($arrayCategory[$timeTemp])) {
                    $arrayCategory[$timeTemp]['total'] += $value['quantity'];
                }
            }
        }
        $dataReturn [] = ['', __('Số lần sử dụng')];
        foreach ($arrayCategory as $key => $value) {
            $dataReturn[] = [
                $value['category_name'], (int)$value['total']
            ];
        }
        return $dataReturn;
    }

    // Xử lý data các biểu đồ tròn: customer group
    private function dataChartCustomerGroup($startTime, $endTime, $serviceId)
    {
        $dataReturn = [['Task', 'Amount']];
        $mOrderDetail = new OrderDetailTable();
        // Lấy theo khách hàng vãng lai (những khách hàng không có customer group)
        $getDataCustomerCurrent = $mOrderDetail->getQuantityObjectGroupByCustomerGroupCurrent($startTime, $endTime, $serviceId, 'service');
        if ($getDataCustomerCurrent != null) {
            $dataReturn [] = [
                __('Khách hàng khác'),
                (int)$getDataCustomerCurrent['quantity']
            ];
        } else {
            $dataReturn [] = [__('Khách hàng khác'), 0];
        }

        // Các nhóm khách hàng còn lại
        $getDataCustomerRest = $mOrderDetail->getQuantityObjectGroupByCustomerGroup($startTime, $endTime, $serviceId, 'service')->toArray();
        foreach ($getDataCustomerRest as $value) {
            $dataReturn [] = [
                $value['object_name'], (int)$value['quantity']
            ];
        }
        return $dataReturn;
    }

    // Xử lý data các biểu đồ tròn: service category
    private function dataChartServiceCardGroup($startTime, $endTime, $serviceId)
    {
        $dataReturn = [['Task', 'Amount']];
        $mOrderDetail = new OrderDetailTable();
        $getData = $mOrderDetail->getQuantityServiceGroupByServiceCategory($startTime, $endTime, $serviceId)->toArray();
        foreach ($getData as $value) {
            $dataReturn [] = [
                $value['object_name'], (int)$value['quantity']
            ];
        }
        return $dataReturn;
    }

    // Xử lý data các biểu đồ tròn: branch
    private function dataChartBranch($startTime, $endTime, $serviceId)
    {
        $dataReturn = [['Task', 'Amount']];
        $mOrderDetail = new OrderDetailTable();
        $getData = $mOrderDetail->getQuantityServiceGroupByBranch($startTime, $endTime, $serviceId)->toArray();
        foreach ($getData as $value) {
            $dataReturn [] = [
                $value['object_name'], (int)$value['quantity']
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
        $mOrders = new OrderDetailTable();
        $list = $mOrders->getListDetailStatisticsService($input);

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
            __('TÊN DỊCH VỤ'),
            __('SỐ LẦN SỬ DỤNG')
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mOrderDetails = new OrderDetailTable();
        $allData = $mOrderDetails->getListExportTotalStatisticsService($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['branch_name'],
                    $item['object_name'],
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
            __('TÊN DỊCH VỤ'),
            __('NGÀY MUA'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mOrders = new OrderDetailTable();
        $allData = $mOrders->getListExportDetailStatisticsService($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['order_code'],
                    $item['branch_name'],
                    $item['object_name'],
                    date("d/m/Y h:i",strtotime($item['created_at']))
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-detail.xlsx');
    }
}