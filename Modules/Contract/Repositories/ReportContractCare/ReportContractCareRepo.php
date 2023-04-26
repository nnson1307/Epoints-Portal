<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/23/2021
 * Time: 11:28 AM
 * @author nhandt
 */


namespace Modules\Contract\Repositories\ReportContractCare;


use Carbon\Carbon;
use Modules\Contract\Models\BranchTable;
use Modules\Contract\Models\ContractCareTable;
use Modules\Contract\Models\StaffTable;

class ReportContractCareRepo implements ReportContractCareRepoInterface
{

    /**
     * view báo cáo chăm sóc
     *
     * @param $input
     * @return array
     */
    public function getDataViewIndex($input)
    {
        $mBranch = new BranchTable();
        $optionBranches = $mBranch->getBranchOption();
        return [
          'optionBranches' => $optionBranches
        ];
    }

    /**
     * ds phòng ban tồn tại trong chi nhánh
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDepartment($input)
    {
        $mStaff = new StaffTable();
        $optionDepartment = $mStaff->getDepartmentByBranch($input['branch_id']);
        return response()->json([
            'optionDepartment' => $optionDepartment
        ]);
    }

    /**
     * ds nhân viên thuộc chi nhánh, phòng ban
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStaff($input)
    {
        $mStaff = new StaffTable();
        $optionStaffs = $mStaff->getStaffByDepartmentBranch($input['branch_id'], $input['department_id']);
        return response()->json([
            'optionStaffs' => $optionStaffs
        ]);
    }

    /**
     * data chart (series + categories)
     *
     * @param $input
     * @return array
     */
    public function getChart($input)
    {
        $mContractCare = new ContractCareTable();
        $data = $mContractCare->getReportContractCare($input);
        $dataSuccess = $mContractCare->getReportContractCareSuccess($input);
        $time2 = explode(" - ", $input['time']);
        $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
        $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        $dataChart = $this->processCategoriesChart($startTime, $endTime, $data, $dataSuccess);
        return $dataChart;
    }

    /**
     * xử lý format data chart
     *
     * @param $startTime
     * @param $endTime
     * @param $dataNeed
     * @param $dataSuccess
     * @return array
     */
    private function processCategoriesChart($startTime, $endTime, $dataNeed, $dataSuccess){
        $arrayCategories = $arrayDate = $dataCategories = [];
        $dateDiff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;

        if($dateDiff < 10){
            for ($i = 0; $i < $dateDiff; $i++) {
                $arrayDate [] = Carbon::parse($startTime)->addDays($i)->format('d/m/Y');
            }
            $arrayCategories = $arrayDate;
            $dataCategories = $this->processData10Days($arrayDate, $dataNeed, $dataSuccess);
        }
        elseif($dateDiff >= 10 && $dateDiff < 90){
            $countWeek = ceil((float)$dateDiff / 7);
            for ($i = 0; $i < $dateDiff; $i += 7) {
                if($dateDiff - $i < 7){
                    $arrayDate [] = Carbon::parse($startTime)->addDays($i)->format('d/m/Y') . ' - ' . Carbon::parse($startTime)->addDays($dateDiff - 1)->format('d/m/Y');
                }
                else{
                    $arrayDate [] = Carbon::parse($startTime)->addDays($i)->format('d/m/Y') . ' - ' . Carbon::parse($startTime)->addDays($i + 6)->format('d/m/Y');
                }
            }
            $arrayCategories = $arrayDate;
            $dataCategories = $this->processDataMoreThan10Days($arrayDate, $dataNeed, $dataSuccess);
        }
        elseif($dateDiff >= 90 && $dateDiff < 366){
            //28/10/2019 - 02/08/2021
            $startMonth = Carbon::parse($startTime)->format('m');
            $endMonth = Carbon::parse($endTime)->format('m');
            $startYear = Carbon::parse($startTime)->format('Y');
            $endYear = Carbon::parse($endTime)->format('Y');
            $start = Carbon::parse($startTime)->startOfMonth();
            $end   = Carbon::parse($endTime)->startOfMonth();
            $key = 0;
            do
            {
                $endOfMonthStart = Carbon::parse($start)->endOfMonth();
                $months[$key] = $start->format('d/m/Y') .
                    ' - ' .
                    $endOfMonthStart->format('d/m/Y');
                if($start->format('m-Y') == Carbon::parse($startTime)->format('m-Y')){
                    $months[$key] = Carbon::parse($startTime)->format('d/m/Y').
                        ' - ' .
                        $endOfMonthStart->format('d/m/Y');
                }
                if($start->format('m-Y') == Carbon::parse($endTime)->format('m-Y')){
                    $months[$key] = $start->format('d/m/Y') .
                        ' - ' .
                        Carbon::parse($endTime)->format('d/m/Y');
                }
                $key++;
            } while ($start->addMonth() <= $end);
            $arrayCategories = $arrayDate = $months;
            $dataCategories = $this->processDataMoreThan10Days($arrayDate, $dataNeed, $dataSuccess);
        }
        else{
            //23/11/2020 - 23/11/2021
            $startYear = (int)Carbon::parse($startTime)->format('Y');
            $endYear = (int)Carbon::parse($endTime)->format('Y');
            $months = [];
            $key = 0;
            for($i = $startYear;$i <= $endYear;$i++)
            {
                $months[$key] = Carbon::createFromFormat('Y', $i)->startOfYear()->format('d/m/Y') .
                    ' - ' .
                    Carbon::createFromFormat('Y', $i)->endOfYear()->format('d/m/Y');
                $key++;
            }
            $arrayCategories = $arrayDate = $months;
            $dataCategories = $this->processDataMoreThan10Days($arrayDate, $dataNeed, $dataSuccess);
        }
        return [
            'dataCategories' => $dataCategories,
            'arrayCategories' => $arrayCategories,
        ];
    }

    /**
     * lấy data từ categories chart (categories < 10 ngày)
     *
     * @param array $arrayDate
     * @param $dataNeed
     * @param $dataSuccess
     * @return array
     */
    private function processData10Days(array $arrayDate, $dataNeed, $dataSuccess)
    {
        $data = [];
        foreach ($arrayDate as $key => $value) {

            $data['total_expire'][$value] = 0;
            $data['total_success_expire'][$value] = 0;
            $data['total_soon_expire'][$value] = 0;
            $data['total_success_soon_expire'][$value] = 0;
        }
        foreach ($dataNeed as $key => $value) {
            $timeTemp = $value['created_group'];
            if (isset($data['total_expire'][$timeTemp])) {
                $data['total_expire'][$timeTemp] = $value['total_expire'];
            }
            if (isset($data['total_soon_expire'][$timeTemp])) {
                $data['total_soon_expire'][$timeTemp] = $value['total_soon_expire'];
            }
        }
        foreach ($dataSuccess as $key => $value) {
            $timeTemp = $value['created_group'];
            if (isset($data['total_success_expire'][$timeTemp])) {
                $data['total_success_expire'][$timeTemp] = $value['total_success_expire'];
            }
            if (isset($data['total_success_soon_expire'][$timeTemp])) {
                $data['total_success_soon_expire'][$timeTemp] = $value['total_success_soon_expire'];
            }
        }
        $dataFinal = [];
        $dataFinal[] = [
            'name' => __('Cần gia hạn'),
            'data' => array_values($data['total_soon_expire'])
        ];
        $dataFinal[] = [
            'name' => __('Gia hạn thành công'),
            'data' => array_values($data['total_success_soon_expire'])
        ];
        $dataFinal[] = [
            'name' => __('Cần tái ký'),
            'data' => array_values($data['total_expire'])
        ];
        $dataFinal[] = [
            'name' => __('Tái ký thành công'),
            'data' => array_values($data['total_success_expire'])
        ];
        return $dataFinal;
    }

    /**
     * lấy data từ categories chart (categories > 10 ngày, từ ngày đến ngày)
     *
     * @param array $arrayDate
     * @param $dataNeed
     * @param $dataSuccess
     * @return array
     */
    private function processDataMoreThan10Days(array $arrayDate, $dataNeed, $dataSuccess)
    {
        $data = [];
        foreach ($arrayDate as $key => $value) {

            $data['total_expire'][$value] = 0;
            $data['total_success_expire'][$value] = 0;
            $data['total_soon_expire'][$value] = 0;
            $data['total_success_soon_expire'][$value] = 0;
        }
        foreach ($dataNeed as $key => $value) {
            $timeTemp = $value['created_group'];
            foreach ($arrayDate as $k => $v){
                $arr_filter = explode(" - ", $v);
                $t = Carbon::createFromFormat('d/m/Y', $timeTemp);
                $a0 = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
                $a1 = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
                if($t->gte($a0) && $t->lte($a1)){
                    $data['total_expire'][$v] += $value['total_expire'];
                    $data['total_soon_expire'][$v] += $value['total_soon_expire'];
                }
            }
        }
        foreach ($dataSuccess as $key => $value) {
            $timeTemp = $value['created_group'];
            foreach ($arrayDate as $k => $v){
                $arr_filter = explode(" - ", $v);
                $t = Carbon::createFromFormat('d/m/Y', $timeTemp);
                $a0 = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
                $a1 = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
                if($t->gte($a0) && $t->lte($a1)){
                    $data['total_success_expire'][$v] += $value['total_success_expire'];
                    $data['total_success_soon_expire'][$v] += $value['total_success_soon_expire'];
                }
            }
        }
        $dataFinal = [];
        $dataFinal[] = [
            'name' => __('Cần gia hạn'),
            'data' => array_values($data['total_soon_expire'])
        ];
        $dataFinal[] = [
            'name' => __('Gia hạn thành công'),
            'data' => array_values($data['total_success_soon_expire'])
        ];
        $dataFinal[] = [
            'name' => __('Cần tái ký'),
            'data' => array_values($data['total_expire'])
        ];
        $dataFinal[] = [
            'name' => __('Tái ký thành công'),
            'data' => array_values($data['total_success_expire'])
        ];
        return $dataFinal;
    }
}