<?php

namespace Modules\Report\Repository\RevenueByServiceCard;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\BranchTable;
use Modules\Report\Models\OrderDetailTable;
use Modules\Report\Models\ServiceCardTable;

class RevenueByServiceCardRepo implements RevenueByServiceCardRepoInterface
{
    /**
     * Data cho View báo cáo doanh thu theo thẻ dịch vụ
     *
     * @return mixed
     */
    public function dataViewIndex()
    {
        $mBranch = new BranchTable();
        $mServiceCard = new ServiceCardTable();
        $optionBranch = $mBranch->getOption();
        $optionServiceCard = $mServiceCard->getOption();
        return [
            'optionBranch' => $optionBranch,
            'optionServiceCard' => $optionServiceCard,
        ];
    }

    /**
     * filter thời gian, chi nhánh, số lượng thẻ dịch vụ
     *
     * @param $input
     * @return mixed
     */
    public function filterAction($input)
    {
        $mOrderDetail = new OrderDetailTable();
        $time = $input['time'];
        $branchId = $input['branch'];
        $numberObject = $input['numberServiceCard'];
        $serviceCardId = $input['serviceCardId'];
        $startTime = $endTime = null;
        $arrCategories = []; // Danh mục cho biểu đồ
        $dataSeries = []; // Data cho biểu đồ
        $totalRevenue = 0; // Tổng doanh thu

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

//        $arrDataObject = $mOrder->getObjectAndSumAmountObject($startTime, $endTime, $branchId, $numberObject, 'service_card');
        $arrDataObject = $mOrderDetail->getRevenueByObject($startTime, $endTime, $branchId, $numberObject, 'service_card',$serviceCardId)->toArray();

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
            'arrServiceCard' => $arrDataObject
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
        $arrServiceCardId = [];
        $arrServiceCard[] = json_decode($input['number_service_card_detail']);
        for ($i = 0;$i < count($arrServiceCard);$i++){
            foreach($arrServiceCard[$i] as $item){
                $val = (array)$item;
                array_push($arrServiceCardId,"{$val['obj_id']}");
            }
        }
        $input['arr_service_card'] = $arrServiceCardId;
        $mOrderDetail = new OrderDetailTable();
        $list = $mOrderDetail->getListDetailServiceCard($input);

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
        $arrServiceCardId = [];
        $arrServiceCard[] = json_decode($input['export_number_service_card_total']);
        for ($i = 0;$i < count($arrServiceCard);$i++){
            foreach($arrServiceCard[$i] as $item){
                $val = (array)$item;
                array_push($arrServiceCardId,"{$val['obj_id']}");
            }
        }
        $input['arr_service_card'] = $arrServiceCardId;
        $mOrderDetail = new OrderDetailTable();
        $allData = $mOrderDetail->getListExportTotalServiceCard($input);
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
        $arrServiceCardId = [];
        $arrServiceCard[] = json_decode($input['export_number_service_card_detail']);
        for ($i = 0;$i < count($arrServiceCard);$i++){
            foreach($arrServiceCard[$i] as $item){
                $val = (array)$item;
                array_push($arrServiceCardId,"{$val['obj_id']}");
            }
        }
        $input['arr_service_card'] = $arrServiceCardId;
        $mOrderDetail = new OrderDetailTable();
        $allData = $mOrderDetail->getListExportDetailServiceCard($input);
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