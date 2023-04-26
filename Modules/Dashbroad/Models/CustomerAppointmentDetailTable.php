<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 16/1/2019
 * Time: 17:32
 */

namespace Modules\Dashbroad\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerAppointmentDetailTable extends Model
{
    use ListTableTrait;
    protected $table = 'customer_appointment_details';
    protected $primaryKey = 'customer_appointment_detail_id';
    protected $fillable = [
        'customer_appointment_detail_id',
        'customer_appointment_id',
        'service_id',
        'staff_id',
        'room_id',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'is_deleted',
        'customer_order',
        'price',
        "object_type",
        "object_id",
        "object_code"
    ];

    const SERVICE = "service";

    const NOT_DELETE = 0;

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->customer_appointment_detail_id;
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where('customer_appointment_detail_id', $id)->update($data);
    }

    public function remove($id)
    {
        return $this->where('customer_appointment_detail_id', $id)->delete();
    }

    public function groupItem($customer_appointment_id)
    {
        $ds = $this->select('customer_order', 'room_id', 'staff_id')
            ->where('customer_appointment_id', $customer_appointment_id)
            ->groupBy('customer_order')
            ->get();
        return $ds;
    }

    public function getItem($customer_appointment_id)
    {
        $ds = $this
            ->select(
                "customer_appointment_details.customer_appointment_detail_id",
                "customer_appointment_details.customer_appointment_id",
                "customer_appointment_details.service_id",
                "customer_appointment_details.staff_id",
                "customer_appointment_details.room_id",
                "customer_appointment_details.customer_order",
                "customer_appointment_details.price",
                DB::raw("(CASE
                    WHEN  {$this->table}.object_type = 'service' THEN services.service_name
                    WHEN  {$this->table}.object_type = 'member_card' THEN service_cards.name
                    END
                ) as service_name"),
                "{$this->table}.object_type",
                "{$this->table}.object_id",
                DB::raw("(CASE
                    WHEN  {$this->table}.object_type = 'service' THEN services.service_code
                    WHEN  {$this->table}.object_type = 'member_card' THEN customer_service_cards.card_code
                    END
                ) as object_code"),
                "customer_service_cards.number_using",
                "customer_service_cards.count_using"
            )
            ->leftJoin('services', 'services.service_id', '=', 'customer_appointment_details.service_id')
            ->leftJoin("customer_service_cards", "customer_service_cards.customer_service_card_id", "=", "{$this->table}.object_id")
            ->leftJoin("service_cards", "service_cards.service_card_id", "=", "customer_service_cards.service_card_id")
            ->where("{$this->table}.customer_appointment_id", $customer_appointment_id)
            ->get();
        return $ds;
    }

    public function groupItemDetail($customer_appointment_id)
    {
        $ds = $this->leftJoin('services', 'services.service_id', '=', 'customer_appointment_details.service_id')
            ->select('customer_appointment_details.customer_appointment_id',
                'customer_appointment_details.service_id',
                'customer_appointment_details.staff_id',
                'customer_appointment_details.room_id',
                'customer_appointment_details.customer_order',
                'customer_appointment_details.price',
                'services.service_name',
                DB::raw('customer_appointment_details.service_id as quantity'))
            ->where('customer_appointment_details.customer_appointment_id', $customer_appointment_id)
            ->groupBy('customer_appointment_details.service_id')
            ->get();
        return $ds;
    }

    /**
     * Lấy thông tin chi tiết lịch hẹn
     *
     * @param $appointmentId
     * @return mixed
     */
    public function getDetail($appointmentId)
    {
        return $this
            ->select(
                "customer_appointment_detail_id",
                "service_id",
                "staff_id",
                "room_id",
                "price",
                "object_type",
                "object_id",
                "object_code"
            )
            ->where("customer_appointment_id", $appointmentId)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Lấy chi tiết lịch hẹn bằng dịch vụ
     *
     * @param $serviceId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getDetailByService($serviceId, $startDate, $endDate)
    {
        return $this
            ->select(
                "customer_appointments.customer_appointment_id",
                "customers.full_name",
                "customers.phone1 as phone",
                "customer_appointments.date",
                "customer_appointments.time",
                "customer_appointments.end_date",
                "customer_appointments.end_time",
                "customer_appointments.status"
            )
            ->join("customer_appointments", "customer_appointments.customer_appointment_id", "=", "{$this->table}.customer_appointment_id")
            ->join("customers", "customers.customer_id", "=", "customer_appointments.customer_id")
            ->where("{$this->table}.object_type", self::SERVICE)
            ->where("{$this->table}.object_id", $serviceId)
            ->where(function ($query) use ($startDate) {
                $query->where('customer_appointments.date', '>=', $startDate)
                    ->orWhere('customer_appointments.end_date', '>=', $startDate);
            })->where(function ($query) use ($endDate) {
                $query->where('customer_appointments.date', '<=', $endDate)
                    ->orWhere('customer_appointments.end_date', '<=', $endDate);
            })
            ->where("customer_appointments.status", "<>", "cancel")
            ->get();
    }

    /**
     * Service đã đặt trong ngày
     * @param array $param
     * @return mixed
     */
    public function getServiceByCondition($param = [])
    {
        $select = $this->select(
            $this->table . ".customer_appointment_detail_id",
            "customer_appointments.customer_appointment_id",
            "customer_appointments.date",
            "customer_appointments.time",
            "customer_appointments.end_date",
            "customer_appointments.end_time",
            "customer_appointments.status",
            DB::raw('CONCAT(customer_appointments.date, " " ,customer_appointments.time) as start_datetime'),
            DB::raw('CONCAT(customer_appointments.end_date, " " ,customer_appointments.end_time) as end_datetime')
        )
            ->join(
                "customer_appointments",
                "customer_appointments.customer_appointment_id",
                "{$this->table}.customer_appointment_id"
            )
            ->join('services', function ($join) {
                $join->on('services.service_id', $this->table . '.object_id')
                    ->where('services.is_deleted', 0)
                    ->where('services.is_actived', 1);
            })
            ->whereNotIn("customer_appointments.status", ["cancel", "finish"]);
        if (isset($param['datetime'])) {
            $timeCheck = $param['datetime'];

            $select->where(function ($query) use ($timeCheck) {
                $query->where(DB::raw('CONCAT(customer_appointments.date)'), '>=', $timeCheck)
                    ->orWhere(DB::raw('CONCAT(customer_appointments.end_date)'), '>=', $timeCheck);
            })->where(function ($query) use ($timeCheck) {
                $query->where(DB::raw('CONCAT(customer_appointments.date)'), '<=', $timeCheck)
                    ->orWhere(DB::raw('CONCAT(customer_appointments.end_date)'), '<=', $timeCheck);
            });

        }
        if (isset($param['object_type'])) {
            $select->where('object_type', $param['object_type']);
        }
        $select->where("services.is_surcharge", 0);
        $select->groupBy($this->table . ".object_id");
        return $select->get();
    }
}