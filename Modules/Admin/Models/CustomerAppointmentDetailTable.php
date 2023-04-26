<?php

/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 16/1/2019
 * Time: 17:32
 */

namespace Modules\Admin\Models;


use Carbon\Carbon;
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
        "object_code",
        "object_name",
        "is_check_promotion"
    ];

    const SERVICE = "service";

    const NOT_DELETE = 0;
    const CANCEL = "cancel";

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
                "{$this->table}.customer_appointment_detail_id",
                "{$this->table}.customer_appointment_id",
                "{$this->table}.service_id",
                "{$this->table}.staff_id",
                "{$this->table}.room_id",
                "{$this->table}.customer_order",
                "{$this->table}.price",
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
                "customer_service_cards.count_using",
                "{$this->table}.object_name",
                "{$this->table}.is_check_promotion",
                "staffs.full_name"
            )
            ->leftJoin('services', 'services.service_id', '=', "{$this->table}.object_id")
            ->leftJoin("customer_service_cards", "customer_service_cards.customer_service_card_id", "=", "{$this->table}.object_id")
            ->leftJoin("service_cards", "service_cards.service_card_id", "=", "customer_service_cards.service_card_id")
            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->where("{$this->table}.customer_appointment_id", $customer_appointment_id)
            ->get();
        return $ds;
    }

    public function groupItemDetail($customer_appointment_id)
    {
        $ds = $this->leftJoin('services', 'services.service_id', '=', 'customer_appointment_details.service_id')
            ->select(
                'customer_appointment_details.customer_appointment_id',
                'customer_appointment_details.service_id',
                'customer_appointment_details.staff_id',
                'customer_appointment_details.room_id',
                'customer_appointment_details.customer_order',
                'customer_appointment_details.price',
                'services.service_name',
                DB::raw('customer_appointment_details.service_id as quantity')
            )
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
                "{$this->table}.customer_appointment_detail_id",
                "{$this->table}.service_id",
                "{$this->table}.staff_id",
                "{$this->table}.room_id",
                "{$this->table}.price",
                "{$this->table}.object_type",
                "{$this->table}.object_id",
                "{$this->table}.object_code",
                "{$this->table}.is_check_promotion",
                DB::raw("(CASE
                    WHEN  {$this->table}.object_type = 'service' && {$this->table}.object_name IS NOT NULL THEN {$this->table}.object_name
                    WHEN  {$this->table}.object_type = 'member_card' && {$this->table}.object_name IS NOT NULL THEN {$this->table}.object_name
                    
                    WHEN  {$this->table}.object_type = 'service' && {$this->table}.object_name IS NULL THEN services.service_name
                    WHEN  {$this->table}.object_type = 'member_card' && {$this->table}.object_name IS NULL THEN service_cards.name
                   
                    END
                ) as object_name"),
                "staffs.full_name as staff_name",
                "rooms.name as room_name"
            )
            ->leftJoin("services", "services.service_id", "=", "{$this->table}.service_id")

            ->leftJoin("customer_service_cards", "customer_service_cards.customer_service_card_id", "=", "{$this->table}.object_id")
            ->leftJoin("service_cards", "service_cards.service_card_id", "=", "customer_service_cards.service_card_id")

            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("rooms", "rooms.room_id", "=", "{$this->table}.room_id")
            ->where("{$this->table}.customer_appointment_id", $appointmentId)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
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
            ->whereNotIn("customer_appointments.status", ["cancel", "finish"])
            ->groupBy("customer_appointments.customer_appointment_id")
            ->orderBy("customer_appointments.customer_appointment_id", "desc")
            ->get();
    }

    /**
     * Danh sách dịch vụ đã đặt
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.customer_appointment_id",
                "cs.full_name",
                "cs.phone1 as phone",
                DB::raw("(CASE
                    WHEN  {$this->table}.object_type = 'service' THEN services.service_name
                    WHEN  {$this->table}.object_type = 'member_card' THEN service_cards.name
                   
                    END
                ) as object_name"),
                "ca.date",
                "ca.time",
                "ca.end_date",
                "ca.end_time",
                "ca.status",
                "ca.created_at",
                "st.full_name as staff_name"
            )
            ->join("customer_appointments as ca", "ca.customer_appointment_id", "=", "{$this->table}.customer_appointment_id")
            ->join("customers as cs", "cs.customer_id", "=", "ca.customer_id")
            ->leftJoin("services", "services.service_id", "=", "{$this->table}.service_id")
            ->leftJoin("customer_service_cards", "customer_service_cards.customer_service_card_id", "=", "{$this->table}.object_id")
            ->leftJoin("service_cards", "service_cards.service_card_id", "=", "customer_service_cards.service_card_id")
            ->leftJoin("staffs as st", "st.staff_id", "=", "ca.created_by")
            ->where("{$this->table}.object_type", self::SERVICE)
            ->whereNotIn("ca.status", [self::CANCEL])
            ->groupBy("{$this->table}.customer_appointment_id", "{$this->table}.object_type", "{$this->table}.object_id")
            ->orderBy("ca.created_at", "desc");

        //Filter search
        if (!empty($filter['search'])) {
            $search = $filter['search'];
            $ds->where("cs.full_name", 'like', '%' . $search . '%')
                ->orWhere("cs.phone1", 'like', '%' . $search . '%')
                ->orWhere("object_name", 'like', '%' . $search . '%');
        }
        //Filter ngày tạo
        if (!empty($filter["created_at"])) {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('ca.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        //Filter trạng thái
        if (!empty($filter['status'])) {
            $status = $filter['status'];
            $ds->where("ca.status", $status);
        }
        //Filter nhân viên tạo
        if (!empty($filter['staff_id'])) {
            $ds->where("ca.created_by", $filter['staff_id']);
        }

        unset($filter['staff_id']);


        return $ds;
    }
}