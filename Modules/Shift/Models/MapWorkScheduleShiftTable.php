<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/04/2022
 * Time: 11:00
 */

namespace Modules\Shift\Models;


use Illuminate\Database\Eloquent\Model;

class MapWorkScheduleShiftTable extends Model
{
    protected $table = "sf_map_work_schedule_shifts";
    protected $primaryKey = "map_work_schedule_shift_id";

    /**
     * Lấy data map lịch làm việc - ca làm việc
     *
     * @param $workScheduleId
     * @return mixed
     */
    public function getWorkScheduleShift($workScheduleId)
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