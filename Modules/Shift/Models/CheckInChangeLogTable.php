<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 21/04/2022
 * Time: 17:01
 */

namespace Modules\Shift\Models;


use Illuminate\Database\Eloquent\Model;

class CheckInChangeLogTable extends Model
{
    protected $table = "sf_check_in_change_log";
    protected $primaryKey = "check_in_change_log_id";
    protected $fillable = [
        "check_in_change_log_id",
        "check_in_log_id",
        "time_working_staff_id",
        "staff_id",
        "branch_id",
        "shift_id",
        "check_in_day_old",
        "check_in_time_old",
        "status_old",
        "reason_old",
        "created_type_old",
        "created_by_old",
        "check_in_day_new",
        "check_in_time_new",
        "status_new",
        "reason_new",
        "created_type_new",
        "created_by_new",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm log thay đổi check in
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Lấy log thay đổi check in
     *
     * @param $checkInLogId
     * @return mixed
     */
    public function getLogChange($checkInLogId)
    {
        return $this
            ->select(
                "check_in_change_log_id",
                "check_in_log_id",
                "time_working_staff_id",
                "staff_id",
                "branch_id",
                "shift_id",
                "check_in_day_old",
                "check_in_time_old",
                "status_old",
                "reason_old",
                "created_type_old",
                "created_by_old",
                "check_in_day_new",
                "check_in_time_new",
                "status_new",
                "reason_new",
                "created_type_new",
                "created_by_new"
            )
            ->where("check_in_log_id", $checkInLogId)
            ->orderBy("check_in_change_log_id", "desc")
            ->first();
    }
}