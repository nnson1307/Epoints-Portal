<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/04/2022
 * Time: 11:00
 */

namespace Modules\Shift\Models;


use Illuminate\Database\Eloquent\Model;

class MapWorkScheduleStaffTable extends Model
{
    protected $table = "sf_map_work_schedule_staffs";
    protected $primaryKey = "map_work_schedule_staff_id";

    /**
     * Lấy data map lịch làm việc - nhân viên
     *
     * @param $workScheduleId
     * @return mixed
     */
    public function getWorkScheduleStaff($workScheduleId)
    {
        return $this->where("work_schedule_id", $workScheduleId)->get();
    }

    /**
     * Xoá tất cả data map của lịch làm việc
     *
     * @param $workScheduleId
     * @return mixed
     */
    public function removeBySchedule($workScheduleId)
    {
        return $this->where("work_schedule_id", $workScheduleId)->delete();
    }
}