<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 10/01/2022
 * Time: 18:29
 */

namespace Modules\Payment\Models;


use Illuminate\Database\Eloquent\Model;

class OrderDetailTable extends Model
{
    protected $table = "order_details";
    protected $primaryKey = "order_detail_id";

    /**
     * Lấy chi tiết đơn hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function getDetail($orderId)
    {
        return $this
            ->select(
                "{$this->table}.order_detail_id",
                "{$this->table}.order_id",
                "{$this->table}.object_id",
                "{$this->table}.object_name",
                "{$this->table}.object_type",
                "{$this->table}.object_code",
                "{$this->table}.price",
                "{$this->table}.quantity",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.voucher_code",
                "{$this->table}.refer_id",
                "{$this->table}.staff_id",
                "{$this->table}.is_change_price",
                "{$this->table}.is_check_promotion",
                "orders.order_code"
            )
            ->join("orders", "orders.order_id", "=", "order_details.order_id")
            ->where("{$this->table}.order_id", $orderId)
            ->where("{$this->table}.is_deleted", 0)
            ->get();
    }
}