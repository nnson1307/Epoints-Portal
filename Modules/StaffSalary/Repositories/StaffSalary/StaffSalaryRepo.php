<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\StaffSalary\Repositories\StaffSalary;

use Modules\StaffSalary\Models\StaffSalaryTable;
use Modules\StaffSalary\Models\StaffSalaryTypeTable;
use Modules\StaffSalary\Models\StaffSalaryBonusMinusTable;
use Modules\StaffSalary\Models\StaffSalaryAllowanceTable;
use Modules\StaffSalary\Models\StaffSalaryOvertimeTable;
use Modules\StaffSalary\Models\TimekeepingStaffsTable;
use Modules\StaffSalary\Models\TimeWorkingStaffsTable;
use Carbon\Carbon;

class StaffSalaryRepo implements StaffSalaryRepoInterface
{


    public function add($input)
    {
        $dataMaster = [
            "staff_salary_type_code" => $input['staff_salary_type_code'],
            "staff_salary_pay_period_code" => $input['staff_salary_pay_period_code'],
            "staff_salary_days" => $input['staff_salary_days'],
            "staff_salary_months" => $input['staff_salary_months'],
            "staff_salary_years" => $input['staff_salary_years'],
            "staff_salary_weeks" => $input['staff_salary_weeks'],
            "start_date" => $input['start_date'],
            "end_date" => $input['end_date'],
            "updated_by"    =>  Auth()->id(),
            "created_by"    =>  Auth()->id(),
            "created_at"    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ];
        $mStaffSalary = app()->get(StaffSalaryTable::class);
        $staffSalaryDetailId = $mStaffSalary->add($dataMaster);
        return $staffSalaryDetailId;
    }

    public function getList($filters)
    {
        $mStaffSalary = app()->get(StaffSalaryTable::class);
        $mStaffSalaryData = $mStaffSalary->getList($filters);
        return $mStaffSalaryData;
    }

    /**
     * Lấy chi tiết bảng lương
     */
    public function getDetail($id)
    {
        $mStaffSalary = app()->get(StaffSalaryTable::class);
        $mStaffSalaryData = $mStaffSalary->getDetail($id);
        return $mStaffSalaryData;
    }

    /**
     *Câp nhật bảng lương
     */
    public function edit($data, $id)
    {
        $mStaffSalary = app()->get(StaffSalaryTable::class);
        $mStaffSalaryId = $mStaffSalary->edit($data, $id);
        return $mStaffSalaryId;
    }

    /**
     * Lấy chi tiết bảng lương theo ngày
     */

    public function getDetailByDate($startDate, $endDate)
    {
        $mStaffSalary = app()->get(StaffSalaryTable::class);
        $mStaffSalaryData = $mStaffSalary->getDetailByDate($startDate, $endDate);
        return $mStaffSalaryData;
    }

    public function getListStaffSalaryType()
    {
        $mStaffSalaryType = app()->get(StaffSalaryTypeTable::class);
        $mStaffSalaryType = $mStaffSalaryType->getList();
        return $mStaffSalaryType;
    }

    /**
     * Thêm report bảng công
     */
    public function addTimeKeepingStaff($input)
    {

        $dataMaster = [
            "staff_id" => $input['staff_id'],
            "staff_salary_id" => $input['staff_salary_id'],
            "total_working_day" => $input['total_working_day'],
            "total_day_saturday" => $input['total_day_saturday'],
            "total_day_sunday" => $input['total_day_sunday'],
            "total_day_holiday" => $input['total_day_holiday'],
            "total_working_ot_day" => $input['total_working_ot_day'],
            "total_working_ot_saturday" => $input['total_working_ot_saturday'],
            "total_working_ot_sunday" => $input['total_working_ot_sunday'],
            "total_working_ot_holiday" => $input['total_working_ot_holiday'],
            "total_working_time" => $input['total_working_time'],
            "total_time_saturday" => $input['total_time_saturday'],
            "total_time_sunday" => $input['total_time_sunday'],
            "total_time_holiday" => $input['total_time_holiday'],
            "total_working_ot_time" => $input['total_working_ot_time'],
            "total_time_ot_saturday" => $input['total_time_ot_saturday'],
            "total_time_ot_sunday" => $input['total_time_ot_sunday'],
            "total_time_ot_holiday" => $input['total_time_ot_holiday'],
            "total_day_late" => $input['total_day_late'],
            "total_late_time" => $input['total_late_time'],
            "total_day_back_soon" => $input['total_day_back_soon'],
            "total_time_back_soon" => $input['total_time_back_soon'],
            "total_shift_off" => $input['total_shift_off'],
            "total_day_not_check_in" => $input['total_day_not_check_in'],
            "total_day_not_check_out" => $input['total_day_not_check_out'],
            "total_day_paid_leave" => $input['total_day_paid_leave'],
            "total_saturday_paid_leave" => $input['total_saturday_paid_leave'],
            "total_sunday_paid_leave" => $input['total_sunday_paid_leave'],
            "total_holiday_paid_leave" => $input['total_holiday_paid_leave'],
            "total_day_unpaid_leave" => $input['total_day_unpaid_leave'],
            "total_saturday_unpaid_leave" => $input['total_saturday_unpaid_leave'],
            "total_sunday_unpaid_leave" => $input['total_sunday_unpaid_leave'],
            "total_holiday_unpaid_leave" => $input['total_holiday_unpaid_leave'],
            "total_time_paid_leave" => $input['total_time_paid_leave'],
            "total_saturday_time_paid_leave" => $input['total_saturday_time_paid_leave'],
            "total_sunday_time_paid_leave" => $input['total_sunday_time_paid_leave'],
            "total_holiday_time_paid_leave" => $input['total_holiday_time_paid_leave'],
            "total_time_unpaid_leave" => $input['total_time_unpaid_leave'],
            "total_saturday_time_unpaid_leave" => $input['total_saturday_time_unpaid_leave'],
            "total_sunday_time_unpaid_leave" => $input['total_sunday_time_unpaid_leave'],
            "total_holiday_time_unpaid_leave" => $input['total_holiday_time_unpaid_leave'],
            "total_timekeeping_coefficient" => $input['total_timekeeping_coefficient'],
            "total_timekeeping_coefficient_saturday" => $input['total_timekeeping_coefficient_saturday'],
            "total_timekeeping_coefficient_sunday" => $input['total_timekeeping_coefficient_sunday'],
            "total_timekeeping_coefficient_holiday" => $input['total_timekeeping_coefficient_holiday'],
            "total_timekeeping_coefficient_ot" => $input['total_timekeeping_coefficient_ot'],
            "total_timekeeping_coefficient_saturday_ot" => $input['total_timekeeping_coefficient_saturday_ot'],
            "total_timekeeping_coefficient_sunday_ot" => $input['total_timekeeping_coefficient_sunday_ot'],
            "total_timekeeping_coefficient_holiday_ot" => $input['total_timekeeping_coefficient_holiday_ot'],
            "start_date" => $input['start_date'],
            "end_date" => $input['end_date'],
            "updated_by"    =>  Auth()->id(),
            "created_by"    =>  Auth()->id(),
            "created_at"    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ];
        $mStaffSalary = app()->get(TimekeepingStaffsTable::class);
        $staffSalaryDetailId = $mStaffSalary->add($dataMaster);
        return $staffSalaryDetailId;
    }

    //Xóa bảng công
    public function deleteTimeKeepingStaff($staff_salary_id)
    {

        $mStaffSalary = app()->get(TimekeepingStaffsTable::class);
        $staffSalaryDetailId = $mStaffSalary->deleteBysalary($staff_salary_id);
        return $staffSalaryDetailId;
    }

    /**
     * Lấy chi tiết bảng chấm công
     */
    public function getDetailTimeKeepingStaff($staffId, $staffSalaryId)
    {
        $mStaffSalary = app()->get(TimekeepingStaffsTable::class);
        $staffSalaryDetail = $mStaffSalary->getDetail($staffId, $staffSalaryId);
        return $staffSalaryDetail;
    }

    /**
     * Lấy danh sách ca làm việc nhân viên
     * @param $startDate
     * @param $endDate
     * @param $staffId
     * @return mixed
     */
    public function getListWorkingStaff($startDate, $endDate, $staffId)
    {
        $mTimeWorkingStaff = app()->get(TimeWorkingStaffsTable::class);
        $mTimeWorkingStaffData = $mTimeWorkingStaff->getList($startDate, $endDate, $staffId);
        return $mTimeWorkingStaffData;
    }

    /**
     * Lấy tổng đi trễ
     * @param $startDate
     * @param $endDate
     * @param $staffId
     * @return mixed
     */
    public function getTotalLate($startDate, $endDate, $staffId)
    {

        $mTimeWorkingStaff = app()->get(TimeWorkingStaffsTable::class);
        $mTimeWorkingStaffData = $mTimeWorkingStaff->getTotalLate($startDate, $endDate, $staffId);
        return $mTimeWorkingStaffData;
    }

    /**
     * Lấy tổng về sớm
     * @param $startDate
     * @param $endDate
     * @param $staffId
     * @return mixed
     */
    public function getTotalSoon($startDate, $endDate, $staffId)
    {

        $mTimeWorkingStaff = app()->get(TimeWorkingStaffsTable::class);
        $mTimeWorkingStaffData = $mTimeWorkingStaff->getTotalSoon($startDate, $endDate, $staffId);
        return $mTimeWorkingStaffData;
    }

    /**
     *Câp nhật bảng lương
     */
    public function editWorkingStaff($data, $id)
    {
        $mTimeWorkingStaff = app()->get(TimeWorkingStaffsTable::class);
        $mTimeWorkingStaffId = $mTimeWorkingStaff->edit($data, $id);
        return $mTimeWorkingStaffId;
    }

    /**
     * save salary bonus minus
     * @param $input
     * @return mixed
     */
    public function addSalaryBonusMinus($input)
    {
        $dataMaster = [
            "staff_id" => $input['staff_id'],
            "salary_bonus_minus_id" => $input['salary_bonus_minus_id'],
            "staff_salary_bonus_minus_num" => $input['staff_salary_bonus_minus_num'],
            "created_by"    =>  Auth()->id(),
            "updated_by"    =>  Auth()->id(),
            "created_at"    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ];
        $mStaffSalaryBonusMinus = app()->get(StaffSalaryBonusMinusTable::class);
        $mStaffSalaryBonusMinusId = $mStaffSalaryBonusMinus->add($dataMaster);
        return $mStaffSalaryBonusMinusId;
    }

    /**
     * get detail salary bonus minus
     * @param $input
     * @return mixed
     */
    public function getDetailSalaryBonusMinusByStaff($staffId)
    {

        $mStaffSalaryBonusMinus = app()->get(StaffSalaryBonusMinusTable::class);
        $mStaffSalaryBonusMinus = $mStaffSalaryBonusMinus->getDetailByStaff($staffId);
        return $mStaffSalaryBonusMinus;
    }

    /**
     * delete salary bonus minus
     * @param $input
     * @return mixed
     */
    public function deleteSalaryBonusMinusByStaff($staffId)
    {

        $mStaffSalaryBonusMinus = app()->get(StaffSalaryBonusMinusTable::class);
        $mStaffSalaryBonusMinus = $mStaffSalaryBonusMinus->deleteByStaff($staffId);
        return $mStaffSalaryBonusMinus;
    }

    /**
     * save salary allowance
     * @param $input
     * @return mixed
     */
    public function addSalaryAllowance($input)
    {
        $dataMaster = [
            "staff_id" => $input['staff_id'],
            "salary_allowance_id" => $input['salary_allowance_id'],
            "staff_salary_allowance_num" => $input['staff_salary_allowance_num'],
            "created_by"    =>  Auth()->id(),
            "updated_by"    =>  Auth()->id(),
            "created_at"    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $mStaffSalaryAllowance = app()->get(StaffSalaryAllowanceTable::class);
        $mStaffSalaryAllowanceId = $mStaffSalaryAllowance->add($dataMaster);
        return $mStaffSalaryAllowanceId;
    }

    /**
     * get detail salary allowance
     * @param $input
     * @return mixed
     */
    public function getDetailSalaryAllowanceByStaff($staffId)
    {

        $mStaffSalaryAllowance = app()->get(StaffSalaryAllowanceTable::class);
        $mStaffSalaryAllowance = $mStaffSalaryAllowance->getDetailByStaff($staffId);
        return $mStaffSalaryAllowance;
    }

    /**
     * get detail salary allowance
     * @param $input
     * @return mixed
     */
    public function deleteSalaryAllowanceByStaff($staffId)
    {

        $mStaffSalaryAllowance = app()->get(StaffSalaryAllowanceTable::class);
        $mStaffSalaryAllowance = $mStaffSalaryAllowance->deleteByStaff($staffId);
        return $mStaffSalaryAllowance;
    }

    /**
     * save salary overtime
     * @param $input
     * @return mixed
     */
    public function addSalaryOvertime($input)
    {
        $dataMaster = [
            "staff_id" => $input['staff_id'],
            "branch_id" => $input['branch_id'],
            "staff_salary_overtime_weekday" => $input['staff_salary_overtime_weekday'],
            "staff_salary_overtime_holiday" => $input['staff_salary_overtime_holiday'],
            "staff_salary_overtime_holiday_type" => $input['staff_salary_overtime_holiday_type'],
            "staff_salary_overtime_saturday" => $input['staff_salary_overtime_saturday'],
            "staff_salary_overtime_saturday_type" => $input['staff_salary_overtime_saturday_type'],
            "staff_salary_overtime_sunday" => $input['staff_salary_overtime_sunday'],
            "staff_salary_overtime_sunday_type" => $input['staff_salary_overtime_sunday_type'],
            "created_by"    =>  Auth()->id(),
            "updated_by"    =>  Auth()->id(),
            "created_at"    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ];
        $mTimekeepingStaffs = app()->get(StaffSalaryOvertimeTable::class);
        $mTimekeepingStaffsId = $mTimekeepingStaffs->add($dataMaster);
        return $mTimekeepingStaffsId;
    }

    /**
     * get detail salary overtime
     * @param $input
     * @return mixed
     */
    public function getDetailSalaryOvertimeByStaff($staffId)
    {

        $mStaffSalaryOvertime = app()->get(StaffSalaryOvertimeTable::class);
        $dataStaffSalaryOvertime = $mStaffSalaryOvertime->getDetailByStaff($staffId);
        return $dataStaffSalaryOvertime;
    }

    /**
     * get detail salary overtime
     * @param $input
     * @return mixed
     */
    public function deleteSalaryOvertimeByStaff($staffId)
    {

        $mStaffSalaryOvertime = app()->get(StaffSalaryOvertimeTable::class);
        $mStaffSalaryOvertimeId = $mStaffSalaryOvertime->deleteByStaff($staffId);
        return $mStaffSalaryOvertimeId;
    }
}
