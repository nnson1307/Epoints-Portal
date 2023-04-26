<?php

namespace Modules\Report\Repository\StaffCommission;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\OrderCommissionTable;

class StaffCommissionRepo implements StaffCommissionRepoInterface
{

    /**
     * filter time, number staff cho biểu đồ
     *
     * @param $input
     * @return array|mixed
     */
    public function filterAction($input)
    {
        $mOrderCommission = new OrderCommissionTable();
        $time = $input['time'];
        $numberStaff = $input['numberStaff'];
        $staffId = $input['staffId'];
        $startTime = $endTime = null;
        $dataSeries = [];       // Data các cột biểu đồ
        $dataCategories = [];   // Data danh mục cho biểu đồ
        $totalMoney = 0;        // Tổng tiền

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        // Lấy staff id, name, tổng tiền hoa hồng của mỗi nhân viên
        $dataCommission = $mOrderCommission->getInfoCommissionGroupByStaff($startTime, $endTime, $numberStaff,$staffId)->toArray();
        if (count($dataCommission) > 0) {
            foreach ($dataCommission as $value) {
                $dataCategories [] = $value['staff_name'] . '<br>';
                $dataSeries [] = round(floatval($value['total_staff_money']), isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0);
                $totalMoney += $value['total_staff_money'];
            }
        }
        $dataReturn = [
            'arrayCategories' => $dataCategories,
            'dataSeries' => $dataSeries,
            'totalMoney' => round(floatval($totalMoney), isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
            'countListStaff' => count($dataCategories),
            'arrStaff' => $dataCommission
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
        $arrStaffId = [];
        $arrStaff[] = json_decode($input['number_staff_detail']);
        for ($i = 0;$i < count($arrStaff);$i++){
            foreach($arrStaff[$i] as $item){
                $val = (array)$item;
                array_push($arrStaffId,"{$val['staff_id']}");
            }
        }
        $input['arr_staff'] = $arrStaffId;
        $mStaffDebt = new OrderCommissionTable();
        $list = $mStaffDebt->getListStaffCommission($input);

        return [
            'list' => $list
        ];
    }

    /**
     * Export excel chi tiết hoa hồng nhân viên
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportDetail($input)
    {
        $startTime = null;
        $endTime = null;

        if ($input['time_export_detail'] != null) {
            $time = explode(" - ", $input['time_export_detail']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');
        }

        $staffId = $input['export_staff_id_detail'];
        $heading = [
            __('TÊN NHÂN VIÊN'),
            __('CHI NHÁNH'),
            __('HOA HỒNG SẢN PHẨM'),
            __('HỆ SỐ HOA HỒNG'),
            __('HOA HỒNG THỰC LÃNH'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $arrStaffId = [];
        $arrStaff[] = json_decode($input['export_number_staff_detail']);
        for ($i = 0;$i < count($arrStaff);$i++){
            foreach($arrStaff[$i] as $item){
                $val = (array)$item;
                array_push($arrStaffId,"{$val['staff_id']}");
            }
        }
        $mOrderCommission = app()->get(OrderCommissionTable::class);
        //Lấy thông tin chi tiết hoa hồng nv
        $getStaff = $mOrderCommission->getCommissionStaff($startTime, $endTime,$arrStaffId,$staffId);

        if (count($getStaff) > 0) {
            foreach ($getStaff as $v) {
                $data [] = [
                    $v['staff_name'],
                    $v['branch_name'],
                    $v['staff_commission_rate'] != 0 ? floatval($v['staff_money'] / $v['staff_commission_rate']) : 0,
                    floatval($v['staff_commission_rate']),
                    floatval($v['staff_money'])
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-detail.xlsx');
    }

    /**
     * Export excel tổng hoa hồng nhân viên
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportTotal($input)
    {
        $startTime = null;
        $endTime = null;

        if ($input['time_export_total'] != null) {
            $time = explode(" - ", $input['time_export_total']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');
        }
        $staffId = $input['export_staff_id_total'];
        $heading = [
            __('TÊN NHÂN VIÊN'),
            __('CHI NHÁNH'),
            __('HOA HỒNG THỰC LÃNH'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $arrStaffId = [];
        $arrStaff[] = json_decode($input['export_number_staff_total']);
        for ($i = 0;$i < count($arrStaff);$i++){
            foreach($arrStaff[$i] as $item){
                $val = (array)$item;
                array_push($arrStaffId,"{$val['staff_id']}");
            }
        }
        $mOrderCommission = app()->get(OrderCommissionTable::class);
        //Lấy thông tin hoa hồng nhân viên
        $getStaff = $mOrderCommission->getStaffGroupBranch($startTime, $endTime,$arrStaffId,$staffId);

        if (count($getStaff) > 0) {
            foreach ($getStaff as $v) {
                $data [] = [
                    $v['staff_name'],
                    $v['branch_name'],
                    floatval($v['staff_money'])
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-total.xlsx');
    }
}