<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/20/2019
 * Time: 5:21 PM
 */

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderDetailTable extends Model
{
    protected $table = "order_details";
    protected $primaryKey = "order_detail_id";
    protected $fillable = [
        'order_detail_id', 'order_id', 'object_id', 'object_name', 'object_type', 'object_code', 'price',
        'quantity', 'discount', 'amount', 'voucher_code', 'staff_id', 'refer_id',
        'updated_at', 'created_at', 'created_by', 'updated_by', 'is_deleted'
    ];

    public function getDetail($orderId)
    {
        $select = $this->select(
            'order_details.order_id',
            'order_details.order_detail_id',
            'orders.customer_id',
            'orders.branch_id',
            'orders.process_status',
            'customers.member_level_id',
            'member_levels.code as member_level_code',
            'order_details.object_type',
            'order_details.object_id',
            'order_details.object_name',
            'order_details.amount'
        )
            ->join('orders', 'orders.order_id', 'order_details.order_id')
            ->join('customers', 'customers.customer_id', '=', 'orders.customer_id')
            ->leftJoin('member_levels', 'member_levels.member_level_id', '=', 'customers.member_level_id')
            ->where('orders.order_id', $orderId)
            ->where('customers.customer_id', '<>', 1)
            ->where('orders.is_deleted', 0)
//            ->where('orders.process_status', 'paysuccess')
            ->get();
        return $select;
    }

    /**
     * Lấy chi tiết đơn hàng theo type
     *
     * @param $orderId
     * @param $objectType
     * @return mixed
     */
    public function getDetailByType($orderId, $objectType)
    {
        return $this
            ->select(
                "order_detail_id",
                "order_id",
                "object_id",
                "object_name",
                "object_type",
                "object_code",
                "quantity",
                "price",
                DB::raw("SUM(quantity) as quantity"),
                "discount",
                DB::raw("SUM(amount) as amount")
            )
            ->where("order_id", $orderId)
            ->where("object_type", $objectType)
            ->groupBy("object_id")
            ->get();
    }
}