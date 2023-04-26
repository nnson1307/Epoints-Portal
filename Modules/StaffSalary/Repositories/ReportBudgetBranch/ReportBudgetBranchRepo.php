<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/06/2022
 * Time: 10:02
 */

namespace Modules\StaffSalary\Repositories\ReportBudgetBranch;


use Modules\StaffSalary\Models\BranchTable;
use Modules\StaffSalary\Models\EstimateBranchTimeTable;
use Modules\StaffSalary\Models\TimekeepingStaffsTable;

class ReportBudgetBranchRepo implements ReportBudgetBranchRepoInterface
{
    /**
     * Lấy data filter
     *
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataFilter()
    {
        $mBranch = app()->get(BranchTable::class);

        $data = [
            'optionBranch' => []
        ];

        //Lấy option chi nhánh
        $getOptionBranch = $mBranch->getOption();

        if (count($getOptionBranch) > 0) {
            foreach ($getOptionBranch as $v) {
                $data['optionBranch'][$v['branch_id']] = $v['branch_name'];
            }
        }

        return $data;
    }

    /**
     * Lấy ds ngân sách theo chi nhánh
     *
     * @param array $filter
     * @return mixed|void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getList($filter = [])
    {
        $mEstimateBranchTime = app()->get(EstimateBranchTimeTable::class);

        //Lấy data ngân sách
        $getData = $mEstimateBranchTime->getList($filter);

        if (count($getData->items()) > 0) {
            $mTimeKeepingStaff = app()->get(TimekeepingStaffsTable::class);

            foreach ($getData->items() as $v) {
                $totalWorkingTime = 0;
                $ratioExpectedHour = 0;
                $totalSalary = 0;
                $ratioExpectedSalary = 0;

                $filter['branch_id'] = $v['branch_id'];

                //Lấy số giờ làm việc
                $getTimeKeeping = $mTimeKeepingStaff->getTimeKeepingByBranch($filter);

                if ($getTimeKeeping != null) {
                    $totalWorkingTime = $getTimeKeeping['total_working_time'] + $getTimeKeeping['total_time_saturday'] + $getTimeKeeping['total_time_sunday'] + $getTimeKeeping['total_time_holiday'] + $getTimeKeeping['total_working_ot_time'] + $getTimeKeeping['total_time_ot_saturday'] + $getTimeKeeping['total_time_ot_sunday'] + $getTimeKeeping['total_time_ot_holiday'];

                    $totalSalary = $getTimeKeeping['total_salary'];
                }
                // dd(($totalWorkingTime - $v['estimate_time']));
                //Tính % vượt số giờ làm
                if ($totalWorkingTime > 0 && $totalWorkingTime > $v['estimate_time'] && $v['estimate_time'] > 0) {
                    $ratioExpectedHour = ($totalWorkingTime - $v['estimate_time']) / ($v['estimate_time'] / 100);
                }

                //Tính % vượt quỹ lương
                if ($totalSalary > 0 && $totalSalary > $v['estimate_money'] &&  $v['estimate_money'] > 0) {
                    $ratioExpectedSalary = ($totalSalary - $v['estimate_money']) / ($v['estimate_money'] / 100);
                }


                $v['total_working_time'] = $totalWorkingTime;
                $v['ratio_expected_hour'] = $ratioExpectedHour;
                $v['total_salary'] = $totalSalary;
                $v['ratio_expected_salary'] = $ratioExpectedSalary;
            }
        }

        return [
            'list' => $getData
        ];
    }

    /**
     * Lấy ds ngân sách theo chi nhánh biểu đồ
     *
     * @param array $filter
     * @return mixed|void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getListChart($filter = [])
    {
        $mEstimateBranchTime = app()->get(EstimateBranchTimeTable::class);
        $mTimeKeepingStaff = app()->get(TimekeepingStaffsTable::class);
        $mBranch = app()->get(BranchTable::class);

        $arrBranch = null;
        if(isset($filter['branch_id']) && $filter['branch_id'] != null){
            $arrBranch = $filter['branch_id'];
        }else {
            return [
                'category' =>[],
                'seriesMoney' => [],
                'seriesTime' => [],
            ];
        }
        //Lấy dánh sách branch
        $lstBranch = $mBranch->getBranchFilter($arrBranch);
        
        $arrCategory =[];
        $arrEstimateMoney =[];
        $arrEstimateTime =[];
        $arrTimeKeepingMoney =[];
        $arrTimeKeepingTime =[];
        if(count($lstBranch) > 0){
            foreach ($lstBranch as $key => $itemBranch) {
                $filter['branch_id'] = $itemBranch['branch_id'];
                $dataEstimate = $mEstimateBranchTime->getEstimateByBranch($itemBranch['branch_id'], $filter['date_type'], $filter['date_object']);
                $dataTimeKeeping = $mTimeKeepingStaff->getTimeKeepingByBranch($filter);
                $arrCategory[] = $itemBranch['branch_name'];
                if($dataEstimate != null){
                    $arrEstimateMoney[] = (float)$dataEstimate['estimate_money'];
                    $arrEstimateTime[] = (int)$dataEstimate['estimate_time'];
                }else {
                    $arrEstimateMoney[] = 0;
                    $arrEstimateTime[] = 0;
                }
                if($dataTimeKeeping != null){
                    $arrTimeKeepingMoney[] = (float)$dataTimeKeeping['total_salary'];
                    $totalWorkingTime = $dataTimeKeeping['total_working_time'] + $dataTimeKeeping['total_time_saturday'] + $dataTimeKeeping['total_time_sunday'] + $dataTimeKeeping['total_time_holiday'] + $dataTimeKeeping['total_working_ot_time'] + $dataTimeKeeping['total_time_ot_saturday'] + $dataTimeKeeping['total_time_ot_sunday'] + $dataTimeKeeping['total_time_ot_holiday'];
                    $arrTimeKeepingTime[] = $totalWorkingTime;
                }else {
                    $arrTimeKeepingMoney[] = 0;
                    $arrTimeKeepingTime[] = 0;
                }
                
            }
        }
        $seriesMoney = [
           [
            'maxPointWidth' => 50,
            'name' => __('Ngân sách lương thực tế'),
            'data' => $arrTimeKeepingMoney,
            'color' => '#4f71be' 
           ],
           [
            'maxPointWidth' => 50,
            'name' => __('Ngân sách lương dự kiến'),
            'data' => $arrEstimateMoney,
            'color' => '#de8443' 
           ]
        ];
        $seriesTime = [
            [
             'maxPointWidth' => 50,
             'name' => __('Số giờ thực tế'),
             'data' => $arrTimeKeepingTime,
             'color' => '#4f71be' 
            ],
            [
             'maxPointWidth' => 50,
             'name' => __('Số giờ dự kiến'),
             'data' => $arrEstimateTime,
             'color' => '#de8443' 
            ]
         ];
        return [
            'category' => $arrCategory,
            'seriesMoney' => $seriesMoney,
            'seriesTime' => $seriesTime,
        ];
    }
}
