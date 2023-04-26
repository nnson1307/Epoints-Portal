<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/23/2021
 * Time: 11:28 AM
 * @author nhandt
 */


namespace Modules\Contract\Repositories\ReportContractOverview;


use Carbon\Carbon;
use Modules\Contract\Models\BranchTable;
use Modules\Contract\Models\ContractCareTable;
use Modules\Contract\Models\ContractOverviewLogTable;
use Modules\Contract\Models\ContractTable;
use Modules\Contract\Models\StaffTable;

class ReportContractOverviewRepo implements ReportContractOverViewRepoInterface
{

    /**
     * view chart
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
     * data chart (series + categories)
     *
     * @param $input
     * @return array
     */
    public function getChart($input)
    {
        $mContract = new ContractTable();
        $mContractOverviewLog = new ContractOverviewLogTable();
        $data = $mContractOverviewLog->getReportOverview($input);
        $time2 = explode(" - ", $input['time']);
        $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
        $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        $dataChart = $this->processCategoriesChart($startTime, $endTime, $data);
        $dataTotalContract = $mContract->getOverviewAllContract();
        $dataValidated = $mContract->getOverviewValidated();
        $dataLiquidated = $mContract->getOverviewLiquidated();
        $dataWaitingLiquidation = $mContract->getOverviewWaitingLiquidation();
        return [
            'dataChart' => $dataChart,
            'countTotalContract' => count($dataTotalContract),
            'amountTotalContract' => collect($dataTotalContract)->sum('last_total_amount'),
            'countValidated' => count($dataValidated),
            'amountValidated' => collect($dataValidated)->sum('last_total_amount'),
            'countLiquidated' => count($dataLiquidated),
            'amountLiquidated' => collect($dataLiquidated)->sum('last_total_amount'),
            'countWaitingLiquidation' => count($dataWaitingLiquidation),
            'amountWaitingLiquidation' => collect($dataWaitingLiquidation)->sum('last_total_amount'),
        ];
    }

    /**
     * xử lý trả về serise + categories
     *
     * @param $startTime
     * @param $endTime
     * @param $dataChart
     * @return array
     */
    private function processCategoriesChart($startTime, $endTime, $dataChart){
        $arrayCategories = $arrayDate = $dataCategories = [];
        $dateDiff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;

        if($dateDiff < 10){
            for ($i = 0; $i < $dateDiff; $i++) {
                $arrayDate [] = Carbon::parse($startTime)->addDays($i)->format('d/m/Y');
            }
            $arrayCategories = $arrayDate;
            $dataCategories = $this->processData10Days($arrayDate, $dataChart);
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
            $dataCategories = $this->processDataMoreThan10Days($arrayDate, $dataChart);
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
            $dataCategories = $this->processDataMoreThan10Days($arrayDate, $dataChart);
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
            $dataCategories = $this->processDataMoreThan10Days($arrayDate, $dataChart);
        }
        return [
            'dataCategories' => $dataCategories,
            'arrayCategories' => $arrayCategories,
        ];
    }

    /**
     * lấy data chart theo cateogries (<10 days)
     *
     * @param array $arrayDate
     * @param $dataChart
     * @return array
     */
    private function processData10Days(array $arrayDate, $dataChart)
    {
        $data = [];
        foreach ($arrayDate as $key => $value) {

            $data['total_new'][$value] = 0;
            $data['total_renew'][$value] = 0;
            $data['total_recare'][$value] = 0;
            $data['total_date_amount'][$value] = 0;
        }
        foreach ($dataChart as $key => $value) {
            $timeTemp = $value['created_group'];
            if (isset($data['total_new'][$timeTemp])) {
                $data['total_new'][$timeTemp] = $value['total_new'];
            }
            if (isset($data['total_renew'][$timeTemp])) {
                $data['total_renew'][$timeTemp] = $value['total_renew'];
            }
            if (isset($data['total_recare'][$timeTemp])) {
                $data['total_recare'][$timeTemp] = $value['total_recare'];
            }
            if (isset($data['total_date_amount'][$timeTemp])) {
                $data['total_date_amount'][$timeTemp] = $value['total_date_amount'];
            }
        }
        $dataFinal = [];
        $dataFinal[] = [
            'yAxis'=> 0,
            'name' => __('Hợp đồng mới'),
            'type' => 'column',
            'data' => array_values($data['total_new'])
        ];
        $dataFinal[] = [
            'yAxis'=> 0,
            'name' => __('Hợp đồng đã gia hạn'),
            'type' => 'column',
            'data' => array_values($data['total_renew'])
        ];
        $dataFinal[] = [
            'yAxis'=> 0,
            'name' => __('Hợp đồng đã tái ký'),
            'type' => 'column',
            'data' => array_values($data['total_recare'])
        ];
        $dataFinal[] = [
            'yAxis'=> 1,
            'name' => __('Giá trị hợp đồng'),
            'tooltip' => [
                'valueSuffix' => __('VNĐ')
            ],
            'type' => 'spline',
            'data' => array_values($data['total_date_amount'])
        ];
        return $dataFinal;
    }

    /**
     * lấy data chart theo categories (>10 days)
     *
     * @param array $arrayDate
     * @param $dataChart
     * @return array
     */
    private function processDataMoreThan10Days(array $arrayDate, $dataChart)
    {
        $data = [];
        foreach ($arrayDate as $key => $value) {

            $data['total_new'][$value] = 0;
            $data['total_renew'][$value] = 0;
            $data['total_recare'][$value] = 0;
            $data['total_date_amount'][$value] = 0;
        }
        foreach ($dataChart as $key => $value) {
            $timeTemp = $value['created_group'];
            foreach ($arrayDate as $k => $v){
                $arr_filter = explode(" - ", $v);
                $t = Carbon::createFromFormat('d/m/Y', $timeTemp);
                $a0 = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
                $a1 = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
                if($t->gte($a0) && $t->lte($a1)){
                    $data['total_new'][$v] += $value['total_new'];
                    $data['total_renew'][$v] += $value['total_renew'];
                    $data['total_recare'][$v] += $value['total_recare'];
                    $data['total_date_amount'][$v] += $value['total_date_amount'];
                }
            }
        }
        $dataFinal = [];
        $dataFinal[] = [
            'yAxis'=> 0,
            'name' => __('Hợp đồng mới'),
            'type' => 'column',
            'data' => array_values($data['total_new'])
        ];
        $dataFinal[] = [
            'yAxis'=> 0,
            'name' => __('Hợp đồng đã gia hạn'),
            'type' => 'column',
            'data' => array_values($data['total_renew'])
        ];
        $dataFinal[] = [
            'yAxis'=> 0,
            'name' => __('Hợp đồng đã tái ký'),
            'type' => 'column',
            'data' => array_values($data['total_recare'])
        ];
        $dataFinal[] = [
            'yAxis'=> 1,
            'name' => __('Giá trị hợp đồng'),
            'tooltip' => [
                'valueSuffix' => __('VNĐ')
            ],
            'type' => 'spline',
            'data' => array_values($data['total_date_amount'])
        ];
        return $dataFinal;
    }
}