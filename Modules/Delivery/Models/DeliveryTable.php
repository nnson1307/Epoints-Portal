<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-26
 * Time: 12:09 PM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class DeliveryTable extends Model
{
    use ListTableTrait;
    protected $table = "deliveries";
    protected $primaryKey = "delivery_id";
    protected $fillable = [
        "delivery_id",
        "order_id",
        "customer_id",
        "contact_name",
        "contact_phone",
        "contact_address",
        "total_transport_estimate",
        "delivery_status",
        "is_deleted",
        "is_actived",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "time_order"
    ];

    const NOT_DELETED = 0;
    const IN_ACTIVE = 1;

    /**
     * Danh sách đơn hàng cần giao
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.delivery_id",
                "orders.order_code",
                "customers.full_name",
                "{$this->table}.contact_name",
                "{$this->table}.contact_phone",
                "{$this->table}.contact_address",
                "{$this->table}.total_transport_estimate",
                "{$this->table}.delivery_status",
                "{$this->table}.is_actived",
                "branches.branch_name",
                "{$this->table}.created_at",
                "{$this->table}.time_order"
            )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "=", "orders.branch_id")
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->where("customers.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.is_actived", self::IN_ACTIVE)
            ->orderBy("$this->primaryKey", "desc");

        // filter tên KH, mã ĐH
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('customers.full_name', 'like', '%' . $search . '%')
                    ->orWhere('orders.order_code', 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (isset($filter["created_at"]) != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        return $ds;
    }

    /**
     * Thông tin đơn hàng cần giao
     *
     * @param $deliveryId
     * @return mixed
     */
    public function getInfo($deliveryId)
    {
        return $this
            ->select(
                "{$this->table}.delivery_id",
                "orders.order_code",
                "customers.full_name",
                "{$this->table}.contact_name",
                "{$this->table}.contact_phone",
                "{$this->table}.contact_address",
                "{$this->table}.total_transport_estimate",
                "{$this->table}.delivery_status",
                "{$this->table}.is_actived",
                "{$this->table}.order_id",
                "orders.total",
                "orders.discount",
                "orders.amount",
                "orders.process_status",
                "orders.customer_id",
                "branches.address as branch_address",
                "orders.tranport_charge",
                "orders.customer_contact_code",
                "orders.customer_contact_id",
                "customers.full_name as customer_name",
                "customers.phone1 as customer_phone",
                "customers.address as customer_address",
                DB::raw("CONCAT(province.type, ' ', province.name) as province_name"),
                DB::raw("CONCAT(district.type, ' ', district.name) as district_name"),
                "orders.branch_id"
            )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "=", "orders.branch_id")
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->leftJoin("province", "province.provinceid", "=", "customers.province_id")
            ->leftJoin("district", "district.districtid", "=", "customers.district_id")
            ->where("{$this->primaryKey}", $deliveryId)
            ->first();
    }

    /**
     * Chỉnh sửa đơn hàng cần giao
     *
     * @param array $data
     * @param $deliveryId
     * @return mixed
     */
    public function edit(array $data, $deliveryId)
    {
        return $this->where("{$this->primaryKey}", $deliveryId)->update($data);
    }

    /**
     * Thêm đơn hàng cần giao
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->delivery_id;
    }
}