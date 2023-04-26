<?php

namespace Modules\Report\Repository\RevenueByService;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\BranchTable;
use Modules\Report\Models\OrderDetailTable;
use Modules\Report\Models\ServiceTable;
use Modules\Report\Models\ServiceCategoriesTable;

class RevenueByServiceRepo implements RevenueByServiceRepoInterface
{
    /**
     * Data cho View báo cáo doanh thu theo sản phẩm
     *
     * @return mixed
     */
    public function dataViewIndex()
    {
        $mBranch = new BranchTable();
        $mService = new ServiceTable();
        $mServiceCategories = new ServiceCategoriesTable();
        $optionBranch = $mBranch->getOption();
        $optionService = $mService->getOption();
        $optionServiceCategories = $mServiceCategories->getOption();

        return [
            'optionBranch' => $optionBranch,
            'optionService' => $optionService,
            'optionServiceCategories' => $optionServiceCategories,
        ];
    }

    /**
     * Data cho View báo cáo doanh thu theo nhóm dịch vụ
     *
     * @return mixed
     */
    public function dataViewGroupIndex()
    {
        $mBranch = new BranchTable();
        $mServiceCategories = new ServiceCategoriesTable();
        $optionBranch = $mBranch->getOption();
        $optionServiceCategories = $mServiceCategories->getOption();
        return [
            'optionBranch' => $optionBranch,
            'optionServiceCategories' => $optionServiceCategories,
        ];
    }

    /**
     * filter thời gian, chi nhánh, số lượng dịch vụ
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
        $serviceId = $input['serviceId'];
        $serviceCategoryId = $input['serviceCategoryId'];
        $startTime = $endTime = null;
        $arrCategories = []; // Danh mục cho biểu đồ
        $dataSeries = []; // Data cho biểu đồ
        $totalRevenue = 0; // Tổng doanh thu

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        $arrDataObject = $mOrderDetail->getRevenueByService($startTime, $endTime, $branchId, $numberObject,$serviceId,$serviceCategoryId)->toArray();

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
     * filter thời gian, chi nhánh, số lượng dịch vụ
     *
     * @param $input
     * @return mixed
     */
    public function filterGroupAction($input)
    {
        $mOrderDetail = new OrderDetailTable();
        $time = $input['time'];
        $branchId = $input['branch'];
        $serviceCategoryId = $input['serviceCategoryId'];
        $startTime = $endTime = null;
        $arrCategories = []; // Danh mục cho biểu đồ
        $dataSeries = []; // Data cho biểu đồ
        $totalRevenue = 0; // Tổng doanh thu

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        $arrDataObject = $mOrderDetail->getRevenueByServiceGroup($startTime, $endTime, $branchId,$serviceCategoryId)->toArray();

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
        $list = $mOrderDetail->getListDetailService($input);

        return [
            'list' => $list
        ];
    }

    /**
     * Ds chi tiết của chart group
     *
     * @param $input
     * @return array|mixed
     */
    public function listDetailGroupAction($input)
    {
//        $arrServiceId = [];
        $arrService[] = json_decode($input['number_service_detail']);
//        for ($i = 0;$i < count($arrService);$i++){
//            foreach($arrService[$i] as $item){
//                $val = (array)$item;
//                array_push($arrServiceId,"{$val['obj_id']}");
//            }
//        }
//        $input['arr_service'] = $arrServiceId;
        $input['service_category_id_detail'] = isset($input['service_id_detail'])?$input['service_id_detail']:'';
        unset($input['service_id_detail']);

        $mOrderDetail = new OrderDetailTable();
        $list = $mOrderDetail->getListDetailService($input);

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
        $allData = $mOrderDetail->getListExportTotalService($input);
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
     * Export excel tổng
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelTotalGroup($input)
    {
        $heading = [
            __('TÊN CHI NHÁNH'),
            __('NHÓM DỊCH VỤ'),
            __('TỔNG DOANH THU')
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
//        $arrServiceId = [];
//        $arrService[] = json_decode($input['export_number_service_total']);
//        for ($i = 0;$i < count($arrService);$i++){
//            foreach($arrService[$i] as $item){
//                $val = (array)$item;
//                array_push($arrServiceId,"{$val['obj_id']}");
//            }
//        }
//        $input['arr_service'] = $arrServiceId;
        $mOrderDetail = new OrderDetailTable();
        $allData = $mOrderDetail->getListExportTotalServiceGroup($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['branch_name'],
                    $item['service_category_name'],
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
        $allData = $mOrderDetail->getListExportDetailService($input);
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

    /**
     * Export excel chi tiết
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelGroupDetail($input)
    {
        $heading = [
            __('MÃ ĐƠN HÀNG'),
            __('TÊN DỊCH VỤ'),
            __('NHÓM DỊCH VỤ'),
            __('TÊN CHI NHÁNH'),
            __('DOANH THU'),
            __('NGÀY MUA HÀNG'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
//        $arrServiceId = [];
//        $arrService[] = json_decode($input['export_number_service_detail']);
//        for ($i = 0;$i < count($arrService);$i++){
//            foreach($arrService[$i] as $item){
//                $val = (array)$item;
//                array_push($arrServiceId,"{$val['obj_id']}");
//            }
//        }
//        $input['arr_service'] = $arrServiceId;
        $mOrderDetail = new OrderDetailTable();
        $allData = $mOrderDetail->getListExportDetailServiceGroup($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['order_code'],
                    $item['object_name'],
                    $item['service_category_name'],
                    $item['branch_name'],
                    $item['amount'],
                    date("d/m/Y h:i",strtotime($item['created_at']))
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-detail.xlsx');
    }
}