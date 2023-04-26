<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 10:50
 */

namespace Modules\StaffSalary\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TimeWorkingStaffsTable extends Model
{

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
        'timekeeping_coefficient',
        'actual_time_work'
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
    public function getList($startDate, $endDate, $staffId)
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
                "{$this->table}.start_working_format_day",
                "{$this->table}.start_working_format_week",
                "{$this->table}.start_working_format_month",
                "{$this->table}.start_working_format_year",
                "{$this->table}.working_end_day",
                "{$this->table}.working_end_time",
                "{$this->table}.number_late_time",
                "{$this->table}.number_time_back_soon",
                "{$this->table}.is_check_in",
                "{$this->table}.is_check_out",
                "{$this->table}.is_deducted",
                "{$this->table}.is_close",
                "{$this->table}.is_ot",
                "{$this->table}.is_deleted",
                "{$this->table}.note",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "{$this->table}.updated_by",
                "{$this->table}.is_approve_late",
                "{$this->table}.is_approve_soon",
                "{$this->table}.time_work",
                "{$this->table}.min_time_work",
                "{$this->table}.timekeeping_coefficient",
                "{$this->table}.actual_time_work",
                "sh.is_monday",
                "sh.is_tuesday",
                "sh.is_wednesday",
                "sh.is_thursday",
                "sh.is_friday",
                "sh.is_saturday",
                "sh.is_sunday"
            )
            ->join("sf_shifts as sh", "sh.shift_id", "=", "{$this->table}.shift_id")
            ->where('staff_id', '=', $staffId)
            ->where('working_day', '>=', $startDate)
            ->where('working_day', '<=', $endDate)
            ->where("sh.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.working_day", 'ASC');
        return $ds->get();
    }

    public function getTotalLate($startDate, $endDate, $staffId)
    {
        $ds = $this
            ->select(
                DB::raw("sum({$this->table}.number_late_time) as totalLateTime"),
                DB::raw("count({$this->table}.time_working_staff_id) as numberLate")
            )
            ->where('staff_id', '=', $staffId)
            ->where('working_day', '>=', $startDate)
            ->where('working_day', '<=', $endDate);
        $ds->where(function ($query) use ($staffId) {
            $query->where(function ($query2) use ($staffId) {
                $query2->where('number_late_time', '>', 0);
            });
            $query->where(function ($query1) use ($staffId) {
                $query1->where('is_approve_late', '=', 0)
                    ->orWhere('is_approve_late', '=', null);
            });
        });
        return $ds->first();
    }

    public function getTotalSoon($startDate, $endDate, $staffId)
    {
        $ds = $this
            ->select(
                DB::raw("sum({$this->table}.number_time_back_soon) as totalSoonTime"),
                DB::raw("count({$this->table}.time_working_staff_id) as numberSoon")
            )
            ->where('staff_id', '=', $staffId)
            ->where('working_day', '>=', $startDate)
            ->where('working_day', '<=', $endDate);
        $ds->where(function ($query) use ($staffId) {
            $query->where(function ($query2) use ($staffId) {
                $query2->where('number_time_back_soon', '>', 0);
            });
            $query->where(function ($query1) use ($staffId) {
                $query1->where('is_approve_soon', '=', 0)
                    ->orWhere('is_approve_soon', '=', null);
            });
        });
        return $ds->first();
    }

    /**
     * edit
     *
     * @return mixed
     */
    public function edit($data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }
}
