<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-26
 * Time: 2:26 PM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class DeliveryHistoryTable extends Model
{
    use ListTableTrait;
    protected $table = "delivery_history";
    protected $primaryKey = "delivery_history_id";
    protected $fillable = [
        "delivery_history_id",
        "delivery_id",
        "delivery_history_code",
        "transport_id",
        "transport_code",
        "delivery_staff",
        "delivery_start",
        "delivery_end",
        "contact_phone",
        "contact_name",
        "contact_address",
        "amount",
        "verified_payment",
        "verified_by",
        "status",
        "note",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "time_ship",
        "pick_up",
        "image_pick_up",
        "image_drop",
        "time_pick_up",
        "reason_delivery_fail_id",
        "reason_name",
        "delivery_history_code",
        "time_drop",
        "pickup_address_code",
        "warehouse_id_pick_up",
        "province_id",
        "district_id",
        "ward_id",
        "weight",
        "type_weight",
        "length",
        "width",
        "height",
        "shipping_unit",
        "is_insurance",
        "is_post_office",
        "is_cod_amount",
        "cod_amount",
        "required_note",
        "service_id",
        "service_type_id",
        "fee",
        "name_service",
        "total_fee",
        "insurance_fee",
        "partner",
        "ghn_order_code",
        "status_ghn"
    ];

    /**
     * Thêm lịch sử giao hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->delivery_history_id;
    }

    /**
     * Lấy thông tin tất cả lịch sử giao hàng của phiếu giao hàng
     *
     * @param $deliveryId
     * @return mixed
     */
    public function getInfo($deliveryId)
    {
        return $this
            ->select(
                "{$this->table}.delivery_history_id",
                "{$this->table}.delivery_id",
                "{$this->table}.transport_id",
                "{$this->table}.transport_code",
                "{$this->table}.delivery_staff",
                "{$this->table}.delivery_start",
                "{$this->table}.delivery_end",
                "{$this->table}.contact_phone",
                "{$this->table}.contact_name",
                "{$this->table}.contact_address",
                "{$this->table}.amount",
                "{$this->table}.verified_payment",
                "{$this->table}.verified_by",
                "{$this->table}.status",
                "transports.transport_name",
                "user_carrier.full_name as staff_name",
                "{$this->table}.time_ship",
                "{$this->table}.pick_up",
                "{$this->table}.time_pick_up",
                "{$this->table}.time_drop"
            )
            ->leftJoin("transports", "transports.transport_id", "=", "{$this->table}.transport_id")
            ->leftJoin("user_carrier", "user_carrier.user_carrier_id", "=", "{$this->table}.delivery_staff")
            ->where("{$this->table}.delivery_id", $deliveryId)
            ->orderBy("{$this->table}.time_ship", "asc")
            ->get();
    }

    /**
     * Đếm số lần giao hàng thành công
     *
     * @param $deliveryId
     * @return mixed
     */
    public function countHistorySuccess($deliveryId)
    {
        return $this
            ->select(
                "deliveries.delivery_id",
                DB::raw('count(delivery_history.delivery_history_id) as total')
            )
            ->join("deliveries", "deliveries.delivery_id", "=", "{$this->table}.delivery_id")
            ->where("{$this->table}.status", "success")
            ->where("{$this->table}.delivery_id", $deliveryId)
            ->groupBy("{$this->table}.delivery_id")
            ->first();
    }

    /**
     * Lấy số lượng sản phẩm trong 1 lịch sử giao hàng
     *
     * @param $deliveryId
     * @return mixed
     */
    public function getQuantityProductHistory($deliveryId)
    {
        return $this
            ->select(
                "{$this->table}.delivery_history_id",
                "delivery_detail.object_type",
                "delivery_detail.object_id",
                "delivery_detail.quantity",
                "{$this->table}.status"
            )
            ->join("delivery_detail", "delivery_detail.delivery_history_id", "=", "{$this->table}.delivery_history_id")
            ->where("{$this->table}.delivery_id", $deliveryId)
            ->whereNotIn("{$this->table}.status", ['fail', 'cancel'])
            ->get();
    }

    /**
     * Chỉnh sửa lịch sử giao hàng
     *
     * @param array $data
     * @param $historyId
     * @return mixed
     */
    public function edit(array $data, $historyId)
    {
        return $this->where("delivery_history_id", $historyId)->update($data);
    }

    /**
     * Lấy thông tin chi tiết lịch sử giao hàng
     *
     * @param $historyId
     * @return mixed
     */
    public function getItem($historyId)
    {
        return $this
            ->select(
                "{$this->table}.delivery_history_id",
                "{$this->table}.delivery_id",
                "{$this->table}.transport_id",
                "{$this->table}.transport_code",
                "{$this->table}.delivery_staff",
                "{$this->table}.delivery_start",
                "{$this->table}.delivery_end",
                "{$this->table}.contact_phone",
                "{$this->table}.contact_name",
                "{$this->table}.contact_address",
                "{$this->table}.amount",
                "{$this->table}.verified_payment",
                "{$this->table}.verified_by",
                "{$this->table}.status",
                "transports.transport_name",
                "user_carrier.full_name as staff_name",
                "{$this->table}.time_ship",
                "{$this->table}.pick_up",
                "{$this->table}.time_pick_up",
                "{$this->table}.time_drop",
                "{$this->table}.image_pick_up",
                "{$this->table}.image_drop",
                "{$this->table}.reason_name",
                "{$this->table}.delivery_staff",
                "{$this->table}.pickup_address_code",
                "orders.order_code",
                "customers.full_name as orderer",
                "{$this->table}.note",
                "orders.customer_id",
                "orders.order_id",
                "{$this->table}.delivery_note",
                "{$this->table}.warehouse_id_pick_up",
                "{$this->table}.province_id",
                "{$this->table}.district_id",
                "{$this->table}.ward_id",
                "{$this->table}.weight",
                "{$this->table}.type_weight",
                "{$this->table}.length",
                "{$this->table}.width",
                "{$this->table}.height",
                "{$this->table}.shipping_unit",
                "{$this->table}.is_insurance",
                "{$this->table}.is_post_office",
                "{$this->table}.required_note",
                "{$this->table}.is_cod_amount",
                "{$this->table}.cod_amount",
                "{$this->table}.fee",
                "{$this->table}.total_fee",
                "{$this->table}.name_service",
                "{$this->table}.service_id",
                "{$this->table}.service_type_id",
                "{$this->table}.partner",
                "{$this->table}.ghn_order_code",
                "province_history.name as province_name",
                "district.name as district_name",
                "ward.name as ward_name"
            )
            ->join("deliveries", "deliveries.delivery_id", "=", "{$this->table}.delivery_id")
            ->join("orders", "orders.order_id", "deliveries.order_id")
            ->leftJoin("transports", "transports.transport_id", "=", "{$this->table}.transport_id")
            ->leftJoin("user_carrier", "user_carrier.user_carrier_id", "=", "{$this->table}.delivery_staff")
            ->leftJoin("province", "user_carrier.user_carrier_id", "=", "{$this->table}.delivery_staff")
            ->join("customers", "customers.customer_id", "=", "deliveries.customer_id")
            ->leftJoin('province as province_history','province_history.provinceid',$this->table.'.province_id')
            ->leftJoin('district','district.districtid',$this->table.'.district_id')
            ->leftJoin('ward','ward.ward_id',$this->table.'.ward_id')
            ->where("$this->primaryKey", $historyId)
            ->first();
    }

    /**
     * Danh sách phiếu giao hàng
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.delivery_history_id",
                "{$this->table}.transport_code",
                "deliveries.delivery_status",
                "user_carrier.full_name",
                "{$this->table}.time_ship",
                "{$this->table}.time_pick_up",
                "{$this->table}.time_drop",
                "{$this->table}.contact_phone",
                "{$this->table}.contact_name",
                "{$this->table}.contact_address",
                "{$this->table}.pick_up",
                "{$this->table}.amount",
                "{$this->table}.status",
                "{$this->table}.ghn_order_code",
                "{$this->table}.partner",
                "{$this->table}.status_ghn",
                "orders.order_code",
                "deliveries.delivery_id",
                "pickup_address.address",
                "transports.transport_name",
                "warehouses.ghn_shop_id"
            )
            ->join("deliveries", "deliveries.delivery_id", "=", "{$this->table}.delivery_id")
            ->leftJoin("transports", "transports.transport_code", "=", "{$this->table}.transport_code")
            ->join("orders", "orders.order_id", "deliveries.order_id")
            ->leftJoin("user_carrier", "user_carrier.user_carrier_id", "=", "{$this->table}.delivery_staff")
            ->leftJoin("pickup_address", "pickup_address.pickup_address_code", "=", "{$this->table}.pickup_address_code")
            ->leftJoin("warehouses", "warehouses.warehouse_id", "=", "{$this->table}.warehouse_id_pick_up")
            ->orderBy("{$this->table}.delivery_history_id", "desc");

        // filter tên nv giao hàng, mã ĐH
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('user_carrier.full_name', 'like', '%' . $search . '%')
                    ->orWhere('orders.order_code', 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (isset($filter["time_ship_search"]) != "") {
            $arr_filter = explode(" - ", $filter["time_ship_search"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.time_ship", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

            unset($filter["time_ship_search"]);
        }

//        Đối tác giao hàng
        if (isset($filter["transport_id"]) ) {
            $ds->where("transports.transport_id", $filter['transport_id']);
            unset($filter["transport_id"]);
        }

//        Cách thức giao hàng
        if (isset($filter["shipping_unit"]) ) {
            $ds->where($this->table.".shipping_unit", $filter['shipping_unit']);
            unset($filter["shipping_unit"]);
        }

        return $ds;
    }
}