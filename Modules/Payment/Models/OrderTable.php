<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 10/01/2022
 * Time: 18:29
 */

namespace Modules\Payment\Models;


use Illuminate\Database\Eloquent\Model;

class OrderTable extends Model
{
    protected $table = "orders";
    protected $primaryKey = "order_id";
    protected $fillable = [
        "order_id",
        "order_code",
        "customer_id",
        "total",
        "discount",
        "amount",
        "tranport_charge",
        "process_status",
        "order_description",
        "customer_description",
        "payment_method_id",
        "order_source_id",
        "transport_id",
        "voucher_code",
        "is_deleted",
        "branch_id",
        "refer_id",
        "discount_member",
        "is_apply",
        "customer_contact_code",
        "shipping_address",
        "receive_at_counter",
        "cashier_by",
        "cashier_date",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETE = 0;

    /**
     * Lấy thông tin đơn hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function getInfo($orderId)
    {
        return $this
            ->select(
                "order_id",
                "order_code",
                "customer_id",
                "total",
                "discount",
                "amount",
                "tranport_charge",
                "process_status",
                "order_description",
                "customer_description",
                "payment_method_id",
                "order_source_id",
                "transport_id",
                "voucher_code",
                "is_deleted",
                "branch_id",
                "refer_id",
                "discount_member",
                "is_apply",
                "customer_contact_code",
                "shipping_address",
                "receive_at_counter",
                "cashier_by",
                "cashier_date"
            )
            ->where("order_id", $orderId)
            ->where("is_deleted", self::NOT_DELETE)
            ->first();
    }

    /**
     * Chỉnh sửa đơn hàng
     *
     * @param array $data
     * @param $orderId
     * @return mixed
     */
    public function edit(array $data, $orderId)
    {
         return $this->where("order_id", $orderId)->update($data);
    }
}