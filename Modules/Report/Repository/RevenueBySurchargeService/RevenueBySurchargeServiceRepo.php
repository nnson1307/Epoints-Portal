<?php

namespace Modules\Report\Repository\RevenueBySurchargeService;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\BranchTable;
use Modules\Report\Models\OrderDetailTable;
use Modules\Report\Models\ServiceTable;

class RevenueBySurchargeServiceRepo implements RevenueBySurchargeServiceRepoInterface
{
    /**
     * Data cho View báo cáo doanh thu theo dịch vụ phụ thu
     *
     * @return mixed
     */
    public function dataViewIndex()
    {
        $mBranch = new BranchTable();
        $mService = new ServiceTable();
        $optionBranch = $mBranch->getOption();
        $optionSurchargeService = $mService->getOptionSurchargeService();
        return [
            'optionBranch' => $optionBranch,
            'optionSurchargeService' => $optionSurchargeService,
        ];
    }

    /**
     * filter thời gian, chi nhánh, số lượng dịch vụ phụ thu
     *
     * @param $input
     * @return mixed
     */
    public function filterAction($input)
    {
        $mOrderDetail = new OrderDetailTable();
        $time = $input['time'];
        $branchId = $input['branch'];
        $numberObject = $input['numberService'];
        $surchargeServiceId = $input['surchargeServiceId'];
        $startTime = $endTime = null;
        $arrCategories = []; // Danh mục cho biểu đồ
        $dataSeries = []; // Data cho biểu đồ
        $totalRevenue = 0; // Tổng doanh thu

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        $arrDataObject = $mOrderDetail->getRevenueBySurchargeService($startTime, $endTime, $branchId, $numberObject,$surchargeServiceId)->toArray();

        // Tách ra thành dataCategories, dataSeries, tổng tiền
        foreach ($arrDataObject as $key => $value) {
            $arrCategories [] = $value['obj_name'];
            $dataSeries [] = round($value['total_obj_amount'], 2);
            $totalRevenue += $value['total_obj_amount'];
        }

        $dataReturn = [
            'arrayCategories' => $arrCategories,
            'dataSeries' => $dataSeries,
            'totalRevenue' => round($totalRevenue, 2),
            'countListObject' => count($arrCategories),
            'arrService' => $arrDataObject
        ];

        return response()->json($dataReturn);
    }
    /**
     * Ds chi tiết của chart
     *
     * @param $input
     * @return array|mixed
     */
    public function listDetail($input)
    {
        $arrServiceId = [];
        $arrService[] = json_decode($input['number_service_detail']);
        for ($i = 0;$i < count($arrService);$i++){
            foreach($arrService[$i] as $item){
                $val = (array)$item;
                array_push($arrServiceId,"{$val['obj_id']}");
            }
        }
        $input['arr_service'] = $arrServiceId;
        $mOrderDetail = new OrderDetailTable();
        $list = $mOrderDetail->getListDetailSurchargeService($input);

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
            __('TỔNG DOANH THU')
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $arrServiceId = [];
        $arrService[] = json_decode($input['export_number_service_total']);
        for ($i = 0;$i < count($arrService);$i++){
            foreach($arrService[$i] as $item){
                $val = (array)$item;
                array_push($arrServiceId,"{$val['obj_id']}");
            }
        }
        $input['arr_service'] = $arrServiceId;
        $mOrderDetail = new OrderDetailTable();
        $allData = $mOrderDetail->getListExportTotalSurchargeService($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['branch_name'],
                    $item['object_name'],
                    $item['amount']
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
            __('TÊN DỊCH VỤ'),
            __('TÊN CHI NHÁNH'),
            __('DOANH THU'),
            __('NGÀY MUA HÀNG'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $arrServiceId = [];
        $arrService[] = json_decode($input['export_number_service_detail']);
        for ($i = 0;$i < count($arrService);$i++){
            foreach($arrService[$i] as $item){
                $val = (array)$item;
                array_push($arrServiceId,"{$val['obj_id']}");
            }
        }
        $input['arr_service'] = $arrServiceId;
        $mOrderDetail = new OrderDetailTable();
        $allData = $mOrderDetail->getListExportDetailSurchargeService($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['order_code'],
                    $item['object_name'],
                    $item['branch_name'],
                    $item['amount'],
                    date("d/m/Y h:i",strtotime($item['created_at']))
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-detail.xlsx');
    }
}