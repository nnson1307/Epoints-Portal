<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/7/22
 * Time: 5:48 PM
 */

namespace Modules\Shift\Models;


use Illuminate\Database\Eloquent\Model;

class ShiftCheckOutLogTable extends Model
{
    protected $table = "sf_check_out_log";
    protected $primaryKey = "check_out_log_id";
    protected $fillable = [
        "check_out_log_id",
        "time_working_staff_id",
        "staff_id",
        "branch_id",
        "shift_id",
        "check_out_day",
        "check_out_time",
        "status",
        "reason",
        "created_type",
        "created_by",
        "created_at",
        "updated_at",
    ];
    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Thêm mới checkin
     *
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->check_out_log_id;
    }

    /**
     * Lấy thông tin ra ca của ca làm việc
     *
     * @param $timeWorkingStaffId
     * @return mixed
     */
    public function getInfoLog($timeWorkingStaffId)
    {
        return $this->where("time_working_staff_id", $timeWorkingStaffId)->first();
    }

    /**
     * Chỉnh sửa log check out của nhân viên
     *
     * @param array $data
     * @param $checkOutLogId
     * @return mixed
     */
    public function edit(array $data, $checkOutLogId)
    {
        return $this->where("check_out_log_id", $checkOutLogId)->update($data);
    }
}