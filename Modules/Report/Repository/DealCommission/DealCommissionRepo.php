<?php

namespace Modules\Report\Repository\DealCommission;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\OrderCommissionTable;

class DealCommissionRepo implements DealCommissionRepoInterface
{
    /**
     * filter time, number deal cho biểu đồ
     *
     * @param $input
     * @return array|mixed
     */
    public function filterAction($input)
    {
        $mOrderCommission = new OrderCommissionTable();
        $time = $input['time'];
        $numberDeal = $input['numberDeal'];
        $dealId = $input['dealId'];
        $startTime = $endTime = null;
        $dataSeries = [];       // Data các cột biểu đồ
        $dataCategories = [];   // Data danh mục cho biểu đồ
        $totalMoney = 0;        // Tổng tiền

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        // Lấy deal id, name, tổng tiền hoa hồng của mỗi deal
        $dataCommission = $mOrderCommission->getInfoCommissionGroupByDeal($startTime, $endTime, $numberDeal, $dealId)->toArray();
        if (count($dataCommission) > 0) {
            foreach ($dataCommission as $value) {
                $dataCategories [] = $value['deal_name'] . '<br>';
                $dataSeries [] = floatval($value['total_deal_money']);
                $totalMoney += $value['total_deal_money'];
            }
        }

        $dataReturn = [
            'arrayCategories' => $dataCategories,
            'dataSeries' => $dataSeries,
            'totalMoney' => floatval($totalMoney),
            'countListDeal' => count($dataCategories),
            'arrDeal' => $dataCommission
        ];
        return response()->json($dataReturn);
    }

    /**
     * Ds chi tiết báo cáo hoa hồng cho deal
     *
     * @param $input
     * @return array|mixed
     */
    public function listDetail($input)
    {
        $arrDealId = [];
        $arrDeal[] = json_decode($input['number_deal_detail']);
        for ($i = 0;$i < count($arrDeal);$i++){
            foreach($arrDeal[$i] as $item){
                $val = (array)$item;
                array_push($arrDealId,"{$val['deal_id']}");
            }
        }
        $input['arr_deal'] = $arrDealId;
        $mDealDebt = new OrderCommissionTable();
        $list = $mDealDebt->getListDetailDealCommission($input);

        return [
            'list' => $list
        ];
    }

    /**
     * Export excel chi tiết hoa hồng cho deal
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportDetail($input)
    {
        $heading = [
            __('TÊN DEAL'),
            __('HOA HỒNG NHẬN ĐƯỢC'),
            __('NGÀY NHẬN'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $arrDealId = [];
        $arrDeal[] = json_decode($input['export_number_deal_detail']);
        for ($i = 0;$i < count($arrDeal);$i++){
            foreach($arrDeal[$i] as $item){
                $val = (array)$item;
                array_push($arrDealId,"{$val['deal_id']}");
            }
        }
        $input['arr_deal'] = $arrDealId;
        $mOrderCommission = new OrderCommissionTable();
        $getDeal = $mOrderCommission->getListExportDetailDealCommission($input);

        if (count($getDeal) > 0) {
            foreach ($getDeal as $v) {
                $data [] = [
                    $v['deal_name'],
                    $v['total_deal_money'],
                    date("d/m/Y h:i",strtotime($v['created_at']))
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-detail.xlsx');
    }

    /**
     * Export excel tổng hoa hồng cho deal
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportTotal($input)
    {
        $heading = [
            __('TÊN DEAL'),
            __('TỔNG HOA HỒNG'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $arrDealId = [];
        $arrDeal[] = json_decode($input['export_number_deal_total']);
        for ($i = 0;$i < count($arrDeal);$i++){
            foreach($arrDeal[$i] as $item){
                $val = (array)$item;
                array_push($arrDealId,"{$val['deal_id']}");
            }
        }
        $input['arr_deal'] = $arrDealId;
        $mOrderCommission = new OrderCommissionTable();
        $getDeal = $mOrderCommission->getListExportTotalDealCommission($input);

        if (count($getDeal) > 0) {
            foreach ($getDeal as $v) {
                $data [] = [
                    $v['deal_name'],
                    $v['total_deal_money'],
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-total.xlsx');
    }
}