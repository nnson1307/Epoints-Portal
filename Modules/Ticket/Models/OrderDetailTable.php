<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/07/2022
 * Time: 15:36
 */

namespace Modules\Ticket\Models;


use Illuminate\Database\Eloquent\Model;

class OrderDetailTable extends Model
{
    protected $table = 'order_details';
    protected $primaryKey = 'order_detail_id';

    /**
     * Chi tiết đơn hàng
     *
     * @param $orderId
     * @param $customerId
     * @return mixed
     */
    public function orderDetail($orderId)
    {
        return $this
            ->select(
                "{$this->table}.object_name",
                "{$this->table}.object_type",
                "{$this->table}.object_code",
                "staffs.full_name as staff_name",
                "customers.full_name as refer_name",
                "{$this->table}.price",
                "{$this->table}.quantity",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.voucher_code",
                "{$this->table}.created_at as created_at"
            )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("customers", "customers.customer_id", "=", "{$this->table}.refer_id")
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.order_id", $orderId)
            ->get();
    }

}