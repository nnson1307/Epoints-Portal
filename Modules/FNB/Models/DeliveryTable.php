<?php


namespace Modules\FNB\Models;


use Illuminate\Database\Eloquent\Model;

class DeliveryTable extends Model
{
    protected $table = "deliveries";
    protected $primaryKey = "delivery_id";
    protected $fillable = [
        "delivery_id",
        "order_id",
        "customer_id",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "contact_name",
        "contact_phone",
        "contact_address",
        "total_transport_estimate",
        "is_deleted",
        "is_actived",
        "delivery_status",
        "time_order"
    ];

    /**
     * Chỉnh sửa đơn hàng cần giao bằng order_id
     *
     * @param array $data
     * @param $orderId
     * @return mixed
     */
    public function edit(array $data, $orderId)
    {
        return $this->where("order_id", $orderId)->update($data);
    }

    /**
     * Lấy thông tin đơn hàng cần giao bằng order_id
     *
     * @param $orderId
     * @return mixed
     */
    public function getInfo($orderId)
    {
        return $this
            ->select(
                "delivery_id",
                "order_id",
                "customer_id",
                "created_by",
                "updated_by",
                "created_at",
                "updated_at",
                "contact_name",
                "contact_phone",
                "contact_address",
                "total_transport_estimate",
                "delivery_status"
            )
            ->where("order_id", $orderId)
            ->first();
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