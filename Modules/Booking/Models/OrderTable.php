<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/20/2019
 * Time: 5:21 PM
 */

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTable extends Model
{
    protected $table = "orders";
    protected $primaryKey = "order_id";
    protected $fillable
        = [
            'is_deleted', 'order_id', 'order_code', 'customer_id', 'branch_id',
            'refer_id', 'total', 'discount', 'amount', 'tranport_charge',
            'created_by', 'updated_by', 'created_at', 'updated_at',
            'process_status', 'order_description', 'customer_description',
            'payment_method_id', 'order_source_id', 'transport_id',
            'voucher_code'
        ];

    /**
     * Get detail order.
     * @param $orderId
     *
     * @return mixed
     */
    public function getDetail($orderId)
    {
        $select = $this->select(
            'orders.order_id',
            'customers.customer_id',
            'customers.full_name',
            'customers.full_name',
            'customers.point',
            'customers.member_level_id',
            'member_levels.name',
            'member_levels.code',
            'customers.point_balance'
        )
            ->join(
                'customers', 'customers.customer_id', '=', 'orders.customer_id'
            )
            ->leftJoin(
                'member_levels', 'member_levels.member_level_id', '=', 'customers.member_level_id'
            )
            ->where('orders.order_id', $orderId)
            ->where('customers.customer_id', '<>', 1)
            ->where('orders.is_deleted', 0)
            ->first();
        return $select;
    }

    /**
     * Get item detail by order id
     * @param $id
     *
     * @return mixed
     */
    public function getItemDetail($id)
    {
        $ds = $this->leftJoin('customers', 'customers.customer_id', '=', 'orders.customer_id')
            ->leftJoin('receipts', 'receipts.order_id', '=', 'orders.order_id')
            ->leftJoin('member_levels', 'member_levels.member_level_id', '=', 'customers.member_level_id')
            ->select(
                'customers.full_name as full_name',
                'customers.phone1 as phone',
                'customers.address as address',
                'customers.customer_avatar as customer_avatar',
                'customers.customer_id as customer_id',
                'customers.phone1 as phone1',
                'orders.order_code as order_code',
                'orders.total as total',
                'orders.discount as discount',
                'orders.tranport_charge as tranport_charge',
                'orders.voucher_code as voucher_code',
                'orders.amount as amount',
                'orders.process_status as process_status',
                'orders.order_id as order_id',
                'receipts.amount_paid as amount_paid',
                'customers.gender as gender',
                'orders.order_id as order_id',
                'receipts.note as note',
                'receipts.receipt_id',
                'orders.refer_id',
                'customers.member_level_id',
                'member_levels.name as member_level_name',
                'member_levels.discount as member_level_discount',
                'orders.discount_member'
            )
            ->where('orders.order_id', $id);
        if (Auth::user()->is_admin != 1) {
            $ds->where('orders.branch_id', Auth::user()->branch_id);
        }
        return $ds->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function detailCustomer($id)
    {
        $ds = $this->leftJoin('branches', 'branches.branch_id', '=', 'orders.branch_id')
            ->leftJoin('receipts', 'receipts.order_id', '=', 'orders.order_id')
            ->select('orders.order_code',
                'orders.amount',
                'orders.process_status',
                'branches.branch_name as branch_name',
                'orders.created_at as created_at',
                'orders.order_id',
                'receipts.note',
                'orders.order_description'
            )
            ->where('orders.customer_id', $id)
            ->orderBy('orders.created_at', 'DESC')->get();
        return $ds;
    }
}