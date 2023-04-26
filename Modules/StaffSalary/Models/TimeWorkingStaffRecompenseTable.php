<?php

namespace Modules\StaffSalary\Models;

use Illuminate\Database\Eloquent\Model;

class TimeWorkingStaffRecompenseTable extends Model
{
    protected $table = "sf_time_working_staff_recompense";
    protected $primaryKey = "time_working_staff_recompense_id";

    const NOT_DELETED = 0;

    /**
     * Lấy thưởng phạt của nhân viên
     *
     * @param $staffId
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getRecompenseByStaff($staffId, $startTime, $endTime)
    {
        return $this
            ->select(
                "{$this->table}.time_working_staff_recompense_id",
                "{$this->table}.money",
                "r.type",
                "t.staff_id",
                "t.working_time"
            )
            ->join("sf_time_working_staffs as t", "t.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->join("sf_recompense as r", "r.recompense_id", "=", "{$this->table}.recompense_id")
            ->where("t.staff_id", $staffId)
            ->whereBetween("t.working_day", [$startTime, $endTime])
            ->where("t.is_deleted", self::NOT_DELETED)
            ->get();
    }
}