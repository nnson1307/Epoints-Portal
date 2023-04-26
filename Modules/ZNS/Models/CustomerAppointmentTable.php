<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 14-04-02020
 * Time: 3:02 PM
 */

namespace Modules\ZNS\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerAppointmentTable extends Model
{
    protected $table = "customer_appointments";
    protected $primaryKey = "customer_appointment_id";

    /**
     * Lấy thông tin lịch hẹn
     *
     * @param $customerAppointmentId
     * @return mixed
     */
    public function getInfo($customerAppointmentId)
    {
        return $this
            ->select(
                "branches.branch_name",
                "branches.address",
                \DB::raw("CONCAT(province.type,' ',province.name) AS province_name"),
                \DB::raw("CONCAT(district.type,' ',district.name) AS district_name"),
                "{$this->table}.customer_appointment_id",
                "{$this->table}.customer_appointment_code",
                "{$this->table}.customer_appointment_type",
                "{$this->table}.date",
                "{$this->table}.time",
                "{$this->table}.created_at",
                "{$this->table}.status"
            )
            ->leftJoin("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("province", "province.provinceid", "=", "branches.provinceid")
            ->leftJoin("district", "district.districtid", "=", "branches.districtid")
            ->where("{$this->table}.customer_appointment_id", $customerAppointmentId)
            ->first();
    }
}