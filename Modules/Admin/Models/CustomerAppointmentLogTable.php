<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/23/2020
 * Time: 2:35 PM
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerAppointmentLogTable extends Model
{
    protected $table = "customer_appointment_log";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "customer_appointment_id",
        "created_type",
        "status",
        "note",
        "created_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy thông tin log lịch hẹn
     *
     * @param $appointmentId
     * @return mixed
     */
    public function getLog($appointmentId)
    {
        return $this
            ->select(
                "id",
                "status",
                "created_at",
                "note"
            )
            ->orderBy("created_at", "asc")
            ->where("customer_appointment_id", $appointmentId)
            ->get();
    }

    /**
     * Thêm log lịch hẹn
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Xóa tất cả log của lịch hẹn
     *
     * @param $appointmentId
     * @return mixed
     */
    public function remove($appointmentId)
    {
        return $this->where("customer_appointment_id", $appointmentId)->delete();
    }

    /**
     * Lấy thông tin của log bằng status
     *
     * @param $appointmentId
     * @param $status
     * @return mixed
     */
    public function getLogByStatus($appointmentId, $status)
    {
        return $this
            ->select(
                "id",
                "status",
                "created_at"
            )
            ->where("customer_appointment_id", $appointmentId)
            ->where("status", $status)
            ->first();
    }
}