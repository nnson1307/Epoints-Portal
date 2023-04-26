<?php

namespace Modules\Report\Repository\StatisticServiceCard;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\OrderDetailTable;
use Modules\Report\Models\ServiceCardTable;

class StatisticServiceCardRepo implements StatisticServiceCardRepoInterface
{
    /**
     * Option service card
     *
     * @return mixed
     */
    public function dataViewIndex()
    {
        $mServiceCard = new ServiceCardTable();
        $optionServiceCard = $mServiceCard->getOption();
        return [
            'optionServiceCard' => $optionServiceCard
        ];
    }

    /**
     * filter
     *
     * @param $input
     * @return array|mixed
     */
    public function filterAction($input)
    {
        $time = $input['time'];
        $serviceCardId = $input['serviceCard'];
        $startTime = $endTime = null;
        $mOrderDetail = new OrderDetailTable();
        $mServiceCard = new ServiceCardTable();

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        // Lấy danh sách order detail theo service_card
        $dataServiceCard = $mOrderDetail->getAllDetailByFilterAndObjectType($startTime, $endTime, $serviceCardId, 'service_card')->toArray();
        // Nếu tất cả thẻ dịch vụ thi từng cột là thẻ dịch vụ, nếu một thẻ dịch vụ thì từng cột là ngày
        $arrayCategory = [];
        if ($serviceCardId != null) {
            $dateDiff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            for ($i = 0; $i < $dateDiff; $i++) {
                $timeTmp = Carbon::parse($startTime)->addDay($i)->format('d/m/Y');
                $arrayCategory [$timeTmp] = [
                    'category_name' => $timeTmp,
                    'total' => 0,
                ];
            }
            // Xử lý data cho biểu đồ cột (tất cả dịch vụ hoặc 1 dịch vụ)
            $chartColumn = $this->dataChartColumn($dataServiceCard, $arrayCategory, false);
        } else {
            $optionServiceCard = $mServiceCard->getOption();
            foreach ($optionServiceCard as $key => $value) {
                $arrayCategory [$value['service_card_id']] = [
                    'category_name' => $value['name'],
                    'total' => 0,
                ];
            }
            // Xử lý data cho biểu đồ cột (tất cả dịch vụ hoặc 1 dịch vụ)
            $chartColumn = $this->dataChartColumn($dataServiceCard, $arrayCategory);
        }

        // Xử lý data các biểu đồ tròn
            // customer group
        $chartCustomerGroup = $this->dataChartCustomerGroup($startTime, $endTime, $serviceCardId);
            // service card group
        $chartServiceCardGroup = $this->dataChartServiceCardGroup($startTime, $endTime, $serviceCardId);
            // branch
        $chartBranch = $this->dataChartBranch($startTime, $endTime, $serviceCardId);
        return [
            'dataChartColumn' => $chartColumn,
            'dataChartCustomerGroup' => $chartCustomerGroup,
            'dataChartServiceCardGroup' => $chartServiceCardGroup,
            'dataChartBranch' => $chartBranch
        ];
    }

    // Xử lý data cho biểu đồ cột
    private function dataChartColumn($dataServiceCard, $arrayCategory, $isAllServiceCard = true)
    {
        if ($isAllServiceCard) {
            foreach ($dataServiceCard as $value) {
                if (isset($arrayCategory[$value['object_id']])) {
                    $arrayCategory[$value['object_id']]['total'] += $value['quantity'];
                }
            }
        } else {
            foreach ($dataServiceCard as $value) {
                $timeTemp = Carbon::parse($value['created_at'])->format('d/m/Y');
                if (isset($arrayCategory[$timeTemp])) {
                    $arrayCategory[$timeTemp]['total'] += $value['quantity'];
                }
            }
        }
        $dataReturn [] = ['', __('Số lần sử dụng')];
        foreach ($arrayCategory as $key => $value) {
            if($isAllServiceCard){
                if((int)$value['total'] != 0){
                    $dataReturn[] = [
                        $value['category_name'], (int)$value['total']
                    ];
                }
            }
            else{
                $dataReturn[] = [
                    $value['category_name'], (int)$value['total']
                ];
            }
        }
        return $dataReturn;
    }

    // Xử lý data các biểu đồ tròn: customer group
    private function dataChartCustomerGroup($startTime, $endTime, $serviceCardId)
    {
        $dataReturn = [['Task', 'Amount']];
        $mOrderDetail = new OrderDetailTable();
        // Lấy theo khách hàng vãng lai (những khách hàng không có customer group)
        $getDataCustomerCurrent = $mOrderDetail->getQuantityObjectGroupByCustomerGroupCurrent($startTime, $endTime, $serviceCardId, 'service_card');
        if ($getDataCustomerCurrent != null) {
            $dataReturn [] = [
                __('Khách hàng khác'),
                (int)$getDataCustomerCurrent['quantity']
            ];
        } else {
            $dataReturn [] = [__('Khách hàng khác'), 0];
        }

        // Các nhóm khách hàng còn lại
        $getDataCustomerRest = $mOrderDetail->getQuantityObjectGroupByCustomerGroup($startTime, $endTime, $serviceCardId, 'service_card')->toArray();
        foreach ($getDataCustomerRest as $value) {
            $dataReturn [] = [
                $value['object_name'], (int)$value['quantity']
            ];
        }
        return $dataReturn;
    }

    // Xử lý data các biểu đồ tròn: service card group
    private function dataChartServiceCardGroup($startTime, $endTime, $serviceCardId)
    {
        $dataReturn = [['Task', 'Amount']];
        $mOrderDetail = new OrderDetailTable();
        $getData = $mOrderDetail->getQuantityServiceCardGroupByServiceCardGroup($startTime, $endTime, $serviceCardId)->toArray();
        foreach ($getData as $value) {
            $dataReturn [] = [
                $value['object_name'], (int)$value['quantity']
            ];
        }
        return $dataReturn;
    }

    // Xử lý data các biểu đồ tròn: branch
    private function dataChartBranch($startTime, $endTime, $serviceCardId)
    {
        $dataReturn = [['Task', 'Amount']];
        $mOrderDetail = new OrderDetailTable();
        $getData = $mOrderDetail->getQuantityServiceCardGroupByBranch($startTime, $endTime, $serviceCardId)->toArray();
        foreach ($getData as $value) {
            $dataReturn [] = [
                $value['object_name'],   (int)$value['quantity']
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
        $list = $mOrders->getListDetailStatisticsServiceCard($input);

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
            __('TÊN THẺ DỊCH VỤ'),
            __('SỐ LẦN SỬ DỤNG')
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mOrderDetails = new OrderDetailTable();
        $allData = $mOrderDetails->getListExportTotalStatisticsServiceCard($input);
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
            __('TÊN THẺ DỊCH VỤ'),
            __('NGÀY MUA'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mOrders = new OrderDetailTable();
        $allData = $mOrders->getListExportDetailStatisticsServiceCard($input);
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