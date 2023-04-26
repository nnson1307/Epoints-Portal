<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 21/04/2022
 * Time: 16:59
 */

namespace Modules\Shift\Models;


use Illuminate\Database\Eloquent\Model;

class TimeWorkingStaffChangeLogTable extends Model
{
    protected $table = "sf_time_working_staff_change_log";
    protected $primaryKey = "time_working_staff_change_log_id";
    protected $fillable = [
        "time_working_staff_change_log_id",
        "time_working_staff_id",
        "is_deducted_old",
        "is_ot_old",
        "is_off_old",
        "note_old",
        "is_deducted_new",
        "is_ot_new",
        "is_off_new",
        "note_new",
        "created_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm lịch sử thay đổi thời gian làm việc
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Lấy thông tin lịch sử thay đổi
     *
     * @param $timeWorkingLogId
     * @return mixed
     */
    public function getChangeLog($timeWorkingLogId)
    {
        return $this
            ->select(
                "time_working_staff_change_log_id",
                "time_working_staff_id",
                "is_deducted_old",
                "is_ot_old",
                "is_off_old",
                "note_old",
                "is_deducted_new",
                "is_ot_new",
                "is_off_new",
                "note_new",
                "created_by"
            )
            ->where("time_working_staff_id", $timeWorkingLogId)
            ->orderBy("time_working_staff_change_log_id", "desc")
            ->first();
    }
}
