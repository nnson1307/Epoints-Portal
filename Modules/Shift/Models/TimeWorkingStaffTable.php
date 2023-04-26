<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 10:50
 */

namespace Modules\Shift\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class TimeWorkingStaffTable extends Model
{
    use ListTableTrait;
    protected $table = "sf_time_working_staffs";
    protected $primaryKey = "time_working_staff_id";
    protected $fillable = [
        "time_working_staff_id",
        "work_schedule_id",
        "shift_id",
        "branch_id",
        "staff_id",
        "working_day",
        "working_time",
        "start_working_format_day",
        "start_working_format_week",
        "start_working_format_month",
        "start_working_format_year",
        "working_end_day",
        "working_end_time",
        "timekeeping_coefficient",
        "is_check_in",
        "is_check_out",
        "is_deducted",
        "is_close",
        "is_ot",
        "is_deleted",
        "note",
        "created_at",
        "updated_at",
        "updated_by",
        "is_approve_late",
        "is_approve_soon",
        "approve_late_by",
        "approve_soon_by",
        "check_in_by",
        "check_out_by",
        "time_work",
        "min_time_work",
        "overtime_type",
        "actual_time_work",
        "time_working_staff_id"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;
    const IS_CHECK_IN = 1;
    const IS_CHECK_OUT = 1;

    /**
     * Lấy ds lịch làm việc của nhân viên
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {

        $ds = $this
            ->select(
                "{$this->table}.time_working_staff_id",
                "{$this->table}.work_schedule_id",
                "{$this->table}.shift_id",
                "{$this->table}.is_approve_late",
                "{$this->table}.is_approve_soon",
                "st.full_name as staff_name",
                "st.phone1 as phone",
                "st.staff_id",
                "br.branch_name",
                "dp.department_name",
                "dp.department_id",
                "sh.shift_name",
                "br1.branch_name as branch_shift_name",
                "{$this->table}.working_time",
                "{$this->table}.working_end_time",
                "{$this->table}.branch_id",
                "sh.is_monday",
                "sh.is_tuesday",
                "sh.is_wednesday",
                "sh.is_thursday",
                "sh.is_friday",
                "sh.is_saturday",
                "sh.is_sunday"
            )
            ->join("sf_shifts as sh", "sh.shift_id", "=", "{$this->table}.shift_id")
            ->join("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("branches as br", "br.branch_id", "=", "st.branch_id")
            ->leftJoin("departments as dp", "dp.department_id", "=", "st.department_id")
            ->leftJoin("branches as br1", "br1.branch_id", "=", "{$this->table}.branch_id")
            ->where("st.is_actived", self::IS_ACTIVE)
            ->where("st.is_deleted", self::NOT_DELETED)
            //            ->where("sh.is_actived", self::IS_ACTIVE)
            ->where("sh.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED);

        //Filter ngày làm việc
        if (isset($filter["date_type"]) && $filter["date_type"] != "") {
            switch ($filter['date_type']) {
                case 'by_week':
                    $ds->where("{$this->table}.start_working_format_week", $filter['date_object']);
                    break;
                case 'by_month':
                    $ds->where("{$this->table}.start_working_format_month", $filter['date_object']);
                    break;
            }
        }

        if (isset($filter['group_by_type']) && $filter['group_by_type'] != "") {
            switch ($filter['group_by_type']) {
                case 'staff':
                    $ds->groupBy("{$this->table}.staff_id");
                    break;
                case 'shift':
                    $ds->groupBy("{$this->table}.shift_id")->groupBy("{$this->table}.branch_id");
                    break;
            }
        }

        unset($filter["date_type"], $filter['date_object'], $filter['group_by_type']);

        return $ds;
    }

    /**
     * Lấy lịch làm việc của nv ở ds theo nhân viên
     *
     * @param $staffId
     * @param $workingDay
     * @param $shiftId
     * @param $branchId
     * @return mixed
     */
    public function getTimeWorkingByStaffOnList($staffId, $workingDay, $shiftId, $branchId)
    {
        $ds = $this
            ->select(
                "{$this->table}.time_working_staff_id",
                "{$this->table}.work_schedule_id",
                "{$this->table}.shift_id",
                "{$this->table}.branch_id",
                "{$this->table}.staff_id",
                "{$this->table}.working_day",
                "{$this->table}.working_time",
                "{$this->table}.working_end_day",
                "{$this->table}.working_end_time",
                "{$this->table}.is_check_in",
                "{$this->table}.is_check_out",
                "{$this->table}.is_deducted",
                "{$this->table}.is_close",
                "{$this->table}.is_ot",
                "{$this->table}.is_approve_late",
                "{$this->table}.is_approve_soon",
                "{$this->table}.number_late_time",
                "{$this->table}.time_off_days_id",
                "st.full_name as staff_name",
                "st.phone1 as phone",
                "st.staff_id",
                "br.branch_name",
                "ci.check_in_day",
                "ci.check_in_time",
                "ci.created_type as created_type_ci",
                "co.check_out_day",
                "co.check_out_time",
                "co.created_type as created_type_co",
                "sh.shift_name",
                "sh.start_timekeeping_on",
                "sh.end_timekeeping_on",
                "sh.start_timekeeping_out",
                "sh.end_timekeeping_out",
                "tio.is_approve as is_approve_time_off",
            )
            ->join("sf_shifts as sh", "sh.shift_id", "=", "{$this->table}.shift_id")
            ->join("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->join("branches as br", "br.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("sf_check_in_log as ci", "ci.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->leftJoin("sf_check_out_log as co", "co.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->leftJoin("time_off_days as tio", "tio.time_off_days_id", "=", "{$this->table}.time_off_days_id")
            ->where("st.is_actived", self::IS_ACTIVE)
            ->where("st.is_deleted", self::NOT_DELETED)
            //            ->where("sh.is_actived", self::IS_ACTIVE)
            ->where("sh.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.staff_id", $staffId)
            ->whereDate("{$this->table}.working_day", $workingDay)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED);

        //Filter theo ca
        if (isset($shiftId) && $shiftId != null) {
            $ds->where("{$this->table}.shift_id", $shiftId);
        }

        //Filter theo chi nhánh
        if (isset($branchId) && $branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }

        return $ds->get();
    }

    /**
     * Lấy ca làm việc theo ngày của nhân viên
     *
     * @param $staffId
     * @param $workingDay
     * @return mixed
     */
    public function getTimeWorkingByStaff($staffId, $workingDay)
    {

        return $this
            ->select(
                "{$this->table}.time_working_staff_id",
                "{$this->table}.work_schedule_id",
                "{$this->table}.shift_id",
                "{$this->table}.branch_id",
                "{$this->table}.staff_id",
                "{$this->table}.working_day",
                "{$this->table}.working_time",
                "{$this->table}.working_end_day",
                "{$this->table}.working_end_time",
                "{$this->table}.is_check_in",
                "{$this->table}.is_check_out",
                "{$this->table}.is_deducted",
                "{$this->table}.is_close",
                "{$this->table}.is_ot",
                "{$this->table}.is_approve_late",
                "{$this->table}.is_approve_soon",
                "st.full_name as staff_name",
                "st.phone1 as phone",
                "st.staff_id",
                "br.branch_name",
                "ci.check_in_day",
                "ci.check_in_time",
                "ci.created_type as created_type_ci",
                "co.check_out_day",
                "co.check_out_time",
                "co.created_type as created_type_co",
                "sh.shift_name",
                "sh.start_timekeeping_on",
                "sh.end_timekeeping_on",
                "sh.start_timekeeping_out",
                "sh.end_timekeeping_out"
            )
            ->join("sf_shifts as sh", "sh.shift_id", "=", "{$this->table}.shift_id")
            ->join("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->join("branches as br", "br.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("sf_check_in_log as ci", "ci.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->leftJoin("sf_check_out_log as co", "co.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->where("st.is_actived", self::IS_ACTIVE)
            ->where("st.is_deleted", self::NOT_DELETED)
            ->where("sh.is_actived", self::IS_ACTIVE)
            ->where("sh.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.working_day", $workingDay)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy thời gian làm việc của nhân viên theo ca
     *
     * @param $staffId
     * @param $workingDay
     * @param $shiftId
     * @param $branchId
     * @return mixed
     */
    public function getTimeWorkingByShift($staffId, $workingDay, $shiftId, $branchId)
    {
        $ds = $this
            ->select(
                "{$this->table}.time_working_staff_id",
                "{$this->table}.work_schedule_id",
                "{$this->table}.shift_id",
                "{$this->table}.branch_id",
                "{$this->table}.staff_id",
                "{$this->table}.working_day",
                "{$this->table}.working_time",
                "{$this->table}.working_end_day",
                "{$this->table}.working_end_time",
                "{$this->table}.is_check_in",
                "{$this->table}.is_check_out",
                "{$this->table}.is_deducted",
                "{$this->table}.is_close",
                "{$this->table}.is_ot",
                "{$this->table}.is_approve_late",
                "{$this->table}.is_approve_soon",
                "{$this->table}.number_late_time",
                "{$this->table}.time_off_days_id",
                "st.full_name as staff_name",
                "st.phone1 as phone",
                "st.staff_id",
                "br.branch_name",
                "ci.check_in_day",
                "ci.check_in_time",
                "ci.created_type as created_type_ci",
                "co.check_out_day",
                "co.check_out_time",
                "co.created_type as created_type_co",
                "sh.shift_name",
                "sh.start_timekeeping_on",
                "sh.end_timekeeping_on",
                "sh.start_timekeeping_out",
                "sh.end_timekeeping_out",
                "tio.is_approve as is_approve_time_off",
            )
            ->join("sf_shifts as sh", "sh.shift_id", "=", "{$this->table}.shift_id")
            ->join("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->join("branches as br", "br.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("sf_check_in_log as ci", "ci.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->leftJoin("sf_check_out_log as co", "co.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->leftJoin("time_off_days as tio", "tio.time_off_days_id", "=", "{$this->table}.time_off_days_id")
            ->where("st.is_actived", self::IS_ACTIVE)
            ->where("st.is_deleted", self::NOT_DELETED)
            ->where("sh.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.shift_id", $shiftId)
            ->where("{$this->table}.working_day", $workingDay)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED);

        //Filter theo chi nhánh
        if (isset($branchId) && $branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }

        return $ds->first();
    }

    /**
     * Kiểm tra thời gian làm việc của nhân viên
     *
     * @param $workScheduleId
     * @param $staffId
     * @param $workingDateTime
     * @param $workingEndDateTime
     * @param $timeWorkingStaff
     * @return mixed
     */
    public function checkTimeWorkingStaff($workScheduleId, $staffId, $workingDateTime, $workingEndDateTime, $timeWorkingStaff = null)
    {
        //Xử lý cho ngày giờ bắt đầu lớn hơn 1p để cho chọn ca nối tiếp giờ kết thúc của ca cũ
        $workingDateTime = Carbon::parse($workingDateTime)->addMinutes(1)->format('Y-m-d H:i');

        $dateTimeFormatDay = Carbon::parse($workingDateTime)->format('Y-m-d');

        $ds = $this
            ->where("staff_id", $staffId)
            ->where(function ($query) use ($workingDateTime, $workingEndDateTime, $dateTimeFormatDay) {
                $query->whereRaw("CONCAT(working_day, ' ', working_time) <= '{$workingDateTime}' and CONCAT(working_end_day, ' ', working_end_time) >= '{$workingDateTime}' ")
                    ->orWhereRaw("CONCAT(working_day, ' ', working_time) <= '{$workingDateTime}' and working_day = {$dateTimeFormatDay} and CONCAT(working_end_day, ' ', working_end_time) < '{$workingDateTime}' ")
                    ->orWhereRaw("CONCAT(working_day, ' ', working_time) <= '{$workingEndDateTime}' and CONCAT(working_end_day, ' ', working_end_time) >= '{$workingEndDateTime}' ");
            })
            ->where("{$this->table}.is_deleted", self::NOT_DELETED);

        if ($workScheduleId != 0) {
            $ds->where("work_schedule_id", "<>", $workScheduleId);
        }

        if ($timeWorkingStaff != null) {
            $ds->where("time_working_staff_id", "<>", $timeWorkingStaff);
        }

        return $ds->first();
    }

    /**
     * Xoá lịch làm việc của nhân viên (khi chỉnh sửa lịch làm việc) trong khoản thời gian
     *
     * @param $workScheduleId
     * @param $startDay
     * @param $endDay
     * @return mixed
     */
    public function removeTimeWorkingByScheduleTime($workScheduleId, $startDay, $endDay)
    {
        return $this->where("work_schedule_id", $workScheduleId)->whereBetween("{$this->table}.working_day", [$startDay, $endDay])->delete();
    }

    /**
     * Xoá lịch làm việc của nhân viên (khi xoá lịch làm việc)
     *
     * @param $workScheduleId
     * @return mixed
     */
    public function removeTimeWorkingBySchedule($workScheduleId)
    {
        return $this->where("work_schedule_id", $workScheduleId)->delete();
    }

    public function _getListShiftCheckin($staffId, $working_day, $time_day)
    {
        $ds = $this
            ->select(
                "{$this->table}.time_working_staff_id",
                "{$this->table}.working_time",
                "{$this->table}.working_end_time",
                "{$this->table}.is_check_in",
                "{$this->table}.is_check_out",
                'sh.shift_id',
                "sh.shift_name",
                "br.branch_name",
                "br.branch_id",
                "ci.check_in_day",
                "ci.check_in_time",
                "co.check_out_day",
                "co.check_out_time"
            )
            ->join("sf_shifts as sh", "sh.shift_id", "=", "{$this->table}.shift_id")
            ->join("branches as br", "br.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("sf_check_in_log as ci", "ci.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->leftJoin("sf_check_out_log as co", "co.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->where(function ($query) use ($working_day, $time_day) {
                $query->where("{$this->table}.working_day", '=', $working_day)
                    ->orWhere("{$this->table}.working_end_day", '=', $working_day);
            })
            ->where(function ($query) use ($working_day, $time_day) {
                $query->where("{$this->table}.is_check_in", '!=', 1)
                    ->orWhere("{$this->table}.is_check_out", '!=', 1);
            })
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->where("sh.is_deleted", self::NOT_DELETED)
            ->orderBy('working_day', 'ASC')
            ->orderBy('working_time', 'ASC');

        return $ds->first();
    }

    /**
     * Lấy thông tin lịch làm việc đã sử dụng
     *
     * @param $workScheduleId
     * @return mixed
     */
    public function getUsingBySchedule($workScheduleId)
    {
        return $this
            ->select(
                "time_working_staff_id",
                "work_schedule_id",
                "shift_id",
                "branch_id",
                "staff_id",
                "working_day",
                "working_time",
                "working_end_day",
                "working_end_time",
                "is_check_in",
                "is_check_out",
                "is_deducted",
                "is_close",
                "is_ot"
            )
            ->where("work_schedule_id", $workScheduleId)
            ->where("is_check_in", self::IS_CHECK_IN)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Kiểm tra ngày làm việc đã được áp dụng chưa
     *
     * @param $timeWorkingId
     * @return mixed
     */
    public function getApplyByTimeWorking($timeWorkingId)
    {
        return $this
            ->select(
                "time_working_staff_id",
                "work_schedule_id",
                "shift_id",
                "branch_id",
                "staff_id",
                "working_day",
                "working_time",
                "working_end_day",
                "working_end_time",
                "is_check_in",
                "is_check_out",
                "is_deducted",
                "is_close",
                "is_ot"
            )
            ->where("time_working_staff_id", $timeWorkingId)
            ->where(function ($query) {
                $query->where("is_check_in", self::IS_CHECK_IN)
                    ->orWhere("is_check_out", self::IS_CHECK_OUT);
            })
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->first();
    }

    /**
     * Thêm lịch làm việc
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->time_working_staff_id;
    }

    /**
     * Chỉnh sửa lịch làm việc
     *
     * @param array $data
     * @param $timeWorkingStaffId
     * @return mixed
     */
    public function edit(array $data, $timeWorkingStaffId)
    {
        return $this->where("time_working_staff_id", $timeWorkingStaffId)->update($data);
    }

    /**
     * Lấy thông tin ngày làm việc
     *
     * @param $timeWorkingStaffId
     * @return mixed
     */
    public function getInfo($timeWorkingStaffId)
    {
        return $this
            ->select(
                "{$this->table}.time_working_staff_id",
                "{$this->table}.work_schedule_id",
                "{$this->table}.shift_id",
                "{$this->table}.branch_id",
                "{$this->table}.staff_id",
                "{$this->table}.working_day",
                "{$this->table}.working_time",
                "{$this->table}.working_end_day",
                "{$this->table}.working_end_time",
                "{$this->table}.is_check_in",
                "{$this->table}.is_check_out",
                "{$this->table}.is_deducted",
                "{$this->table}.is_close",
                "{$this->table}.is_ot",
                "{$this->table}.timekeeping_coefficient",
                "{$this->table}.min_time_work",
                "{$this->table}.time_work",
                "sh.shift_name",
                "st.full_name",
                "br.branch_name",
                "br.branch_name",
                "ci.check_in_log_id",
                "ci.check_in_day",
                "ci.check_in_time",
                "ci.created_type as created_type_ci",
                "ci.reason as reason_ci",
                "co.check_out_log_id",
                "co.check_out_day",
                "co.check_out_time",
                "co.created_type as created_type_co",
                "co.reason as reason_co",
                "{$this->table}.overtime_type"
            )
            ->join("sf_shifts as sh", "sh.shift_id", "=", "{$this->table}.shift_id")
            ->join("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->join("branches as br", "br.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("sf_check_in_log as ci", "ci.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->leftJoin("sf_check_out_log as co", "co.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->where("{$this->table}.time_working_staff_id", $timeWorkingStaffId)
            ->where("sh.is_deleted", self::NOT_DELETED)
            ->first();
    }

    /**
     * Lấy ds nhân viên làm việc theo ca (từ ngày - ngày)
     *
     * @param $shiftId
     * @param $startTime
     * @param $endTime
     * @param $staffId
     * @param $departmentId
     * @param $branchId
     * @return mixed
     */
    public function getListStaffByShift($shiftId, $startTime, $endTime, $staffId, $departmentId, $branchId)
    {
        $ds = $this
            ->select(
                "{$this->table}.time_working_staff_id",
                "{$this->table}.work_schedule_id",
                "{$this->table}.shift_id",
                "{$this->table}.time_off_days_id",
                "st.full_name as staff_name",
                "st.phone1 as phone",
                "st.staff_id",
                "br.branch_name",
                "dp.department_name",
                "dp.department_id",
                "{$this->table}.staff_id",
                "tio.is_approve as is_approve_time_off",
            )
            ->join("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("branches as br", "br.branch_id", "=", "st.branch_id")
            ->leftJoin("departments as dp", "dp.department_id", "=", "st.department_id")
            ->leftJoin("time_off_days as tio", "tio.time_off_days_id", "=", "{$this->table}.time_off_days_id")
            ->whereBetween("{$this->table}.working_day", [$startTime, $endTime])
            ->where("{$this->table}.shift_id", $shiftId)
            ->where("st.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED);

        //Filter theo nhân viên
        if (isset($staffId) && $staffId != null) {
            $ds->where("st.staff_id", $staffId);
        }

        //Filter theo phòng ban
        if (isset($departmentId) && $departmentId != null) {
            $ds->where("st.department_id", $departmentId);
        }

        //Filter theo chi nhánh
        if (isset($branchId) && $branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }

        return $ds->groupBy("{$this->table}.staff_id")->get();
    }

    /**
     * Lấy nhân viên làm việc trong khoảng thời gian
     *
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getListStaffWorking($startTime, $endTime)
    {
        $ds = $this
            ->select(
                "{$this->table}.time_working_staff_id",
                "{$this->table}.work_schedule_id",
                "{$this->table}.shift_id",
                "st.full_name as staff_name",
                "st.phone1 as phone",
                "st.staff_id",
                "br.branch_name",
                "dp.department_name",
                "dp.department_id",
                "{$this->table}.staff_id"
            )
            ->join("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("branches as br", "br.branch_id", "=", "st.branch_id")
            ->leftJoin("departments as dp", "dp.department_id", "=", "st.department_id")
            ->whereBetween("{$this->table}.working_day", [$startTime, $endTime])
            ->where("{$this->table}.is_deleted", self::NOT_DELETED);

        return $ds->groupBy("{$this->table}.staff_id")->get();
    }

    /**
     * Kiểm tra ngày làm việc theo ca đã được áp dụng chưa
     *
     * @param $staffId
     * @param $shiftId
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getApplyByShift($staffId, $shiftId, $startTime, $endTime)
    {
        return $this
            ->select(
                "time_working_staff_id",
                "work_schedule_id",
                "shift_id",
                "branch_id",
                "staff_id",
                "working_day",
                "working_time",
                "working_end_day",
                "working_end_time",
                "is_check_in",
                "is_check_out",
                "is_deducted",
                "is_close",
                "is_ot"
            )
            ->where("staff_id", $staffId)
            ->where("shift_id", $shiftId)
            ->where(function ($query) {
                $query->where("is_check_in", self::IS_CHECK_IN)
                    ->orWhere("is_check_out", self::IS_CHECK_OUT);
            })
            ->whereBetween("{$this->table}.working_day", [$startTime, $endTime])
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Xoá lịch làm việc của nhân viên theo ca trong khoản thời gian
     *
     * @param $staffId
     * @param $shiftId
     * @param $startDay
     * @param $endDay
     * @return mixed
     */
    public function removeTimeWorkingByShift($staffId, $shiftId, $startDay, $endDay)
    {
        return $this
            ->where("staff_id", $staffId)
            ->where("shift_id", $shiftId)
            ->whereBetween("{$this->table}.working_day", [$startDay, $endDay])->delete();
    }

    /**
     * update checkin
     */
    public function updateCheckin(array $data, $id)
    {
        return $this->where('time_working_staff_id', $id)->update($data);
    }

    /**
     * duyệt đi trễ về sớm
     */
    public function approveLateSoon(array $data, $id)
    {
        return $this->where('time_working_staff_id', $id)->update($data);
    }
    /**
     * Lấy thông tin ca làm việc đã sử dụng
     *
     * @param $shiftId
     * @return mixed
     */
    public function getUsingByShift($shiftId)
    {
        return $this
            ->select(
                "time_working_staff_id",
                "work_schedule_id",
                "shift_id",
                "branch_id",
                "staff_id",
                "working_day",
                "working_time",
                "working_end_day",
                "working_end_time",
                "is_check_in",
                "is_check_out",
                "is_deducted",
                "is_close",
                "is_ot"
            )
            ->where("shift_id", $shiftId)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy lịch làm việc của nhân viên theo tháng
     *
     * @param $staffId
     * @param $month
     * @param $year
     * @return mixed
     */
    public function getTimeWorkingStaffByMonth($staffId, $month, $year)
    {

        return $this
            ->select(
                "{$this->table}.time_working_staff_id",
                "{$this->table}.work_schedule_id",
                "{$this->table}.shift_id",
                "{$this->table}.branch_id",
                "{$this->table}.staff_id",
                "{$this->table}.working_day",
                "{$this->table}.working_time",
                "{$this->table}.working_end_day",
                "{$this->table}.working_end_time",
                "{$this->table}.is_check_in",
                "{$this->table}.is_check_out",
                "{$this->table}.is_deducted",
                "{$this->table}.is_close",
                "{$this->table}.is_ot",
                "{$this->table}.number_late_time",
                "{$this->table}.number_time_back_soon",
                "sh.shift_name",
                "sh.min_time_work",
                "{$this->table}.time_work",
                "st.full_name",
                "br.branch_name",
                "br.branch_name",
                "ci.check_in_log_id",
                "ci.check_in_day",
                "ci.check_in_time",
                "ci.created_type as created_type_ci",
                "ci.reason as reason_ci",
                "co.check_out_log_id",
                "co.check_out_day",
                "co.check_out_time",
                "co.created_type as created_type_co",
                "co.reason as reason_co"
            )
            ->join("sf_shifts as sh", "sh.shift_id", "=", "{$this->table}.shift_id")
            ->join("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->join("branches as br", "br.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("sf_check_in_log as ci", "ci.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->leftJoin("sf_check_out_log as co", "co.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.start_working_format_month", $month)
            ->where("{$this->table}.start_working_format_year", $year)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->where("sh.is_deleted", self::NOT_DELETED)
            ->get();
    }

    public function getTimeWorkingStaffByWorkingDay($staffId, $month, $year, $day)
    {
        return $this
            ->select(
                "{$this->table}.time_working_staff_id",
                "{$this->table}.work_schedule_id",
                "{$this->table}.shift_id",
                "{$this->table}.branch_id",
                "{$this->table}.staff_id",
                "{$this->table}.working_day",
                "{$this->table}.working_time",
                "{$this->table}.working_end_day",
                "{$this->table}.working_end_time",
                "{$this->table}.is_check_in",
                "{$this->table}.is_check_out",
                "{$this->table}.is_deducted",
                "{$this->table}.is_close",
                "{$this->table}.is_ot",
                "{$this->table}.number_late_time",
                "{$this->table}.number_time_back_soon",
                "{$this->table}.is_approve_late",
                "{$this->table}.is_approve_soon",
                "{$this->table}.time_off_days_id",
                "sh.shift_name",
                "sh.min_time_work",
                "sh.time_work",
                "st.full_name",
                "br.branch_name",
                "br.branch_name",
                "ci.check_in_log_id",
                "ci.check_in_day",
                "ci.check_in_time",
                "ci.created_type as created_type_ci",
                "ci.reason as reason_ci",
                "co.check_out_log_id",
                "co.check_out_day",
                "co.check_out_time",
                "co.created_type as created_type_co",
                "co.reason as reason_co",
                "tio.is_approve as is_approve_time_off",
            )
            ->join("sf_shifts as sh", "sh.shift_id", "=", "{$this->table}.shift_id")
            ->join("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->join("branches as br", "br.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("sf_check_in_log as ci", "ci.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->leftJoin("sf_check_out_log as co", "co.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->leftJoin("time_off_days as tio", "tio.time_off_days_id", "=", "{$this->table}.time_off_days_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.start_working_format_day", $day)
            ->where("{$this->table}.start_working_format_month", $month)
            ->where("{$this->table}.start_working_format_year", $year)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->where("sh.is_deleted", self::NOT_DELETED)
            ->get();
    }
}
