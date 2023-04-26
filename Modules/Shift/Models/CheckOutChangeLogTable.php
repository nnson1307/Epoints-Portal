<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 21/04/2022
 * Time: 17:02
 */

namespace Modules\Shift\Models;


use Illuminate\Database\Eloquent\Model;

class CheckOutChangeLogTable extends Model
{
    protected $table = "sf_check_out_change_log";
    protected $primaryKey = "check_out_change_log_id";
    protected $fillable = [
        "check_out_change_log_id",
        "check_out_log_id",
        "time_working_staff_id",
        "staff_id",
        "branch_id",
        "shift_id",
        "check_out_day_old",
        "check_out_time_old",
        "status_old",
        "reason_old",
        "created_type_old",
        "created_by_old",
        "check_out_day_new",
        "check_out_time_new",
        "status_new",
        "reason_new",
        "created_type_new",
        "created_by_new",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm log thay đổi check out
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Lấy log thay đổi check out
     *
     * @param $checkOutLogId
     * @return mixed
     */
    public function getLogChange($checkOutLogId)
    {
        return $this
            ->select(
                "check_out_change_log_id",
                "check_out_log_id",
                "time_working_staff_id",
                "staff_id",
                "branch_id",
                "shift_id",
                "check_out_day_old",
                "check_out_time_old",
                "status_old",
                "reason_old",
                "created_type_old",
                "created_by_old",
                "check_out_day_new",
                "check_out_time_new",
                "status_new",
                "reason_new",
                "created_type_new",
                "created_by_new"
            )
            ->where("check_out_log_id", $checkOutLogId)
            ->orderBy("check_out_change_log_id", "desc")
            ->first();
    }
}