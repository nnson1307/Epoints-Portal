<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-26
 * Time: 6:09 PM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;

class OrderTable extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'order_id',
        'order_code',
        'customer_id',
        'branch_id',
        'refer_id',
        'total',
        'discount',
        'amount',
        'tranport_charge',
        'process_status',
        'order_description',
        'customer_description',
        'payment_method_id',
        'order_source_id',
        'transport_id',
        'voucher_code',
        'discount_member',
        'is_apply',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'is_deleted',
        'receive_at_counter',
        'customer_contact_id',
    ];

    /**
     * Chỉnh sửa đơn hàng
     *
     * @param array $data
     * @param $orderId
     * @return mixed
     */
    public function edit(array $data, $orderId)
    {
        return $this->where("$this->primaryKey", $orderId)->update($data);
    }

    public function getItem($id)
    {
        $kq = $this->select(
            'orders.order_id',
            'orders.order_code',
            'orders.customer_id',
            'orders.branch_id',
            'orders.refer_id',
            'orders.total',
            'orders.discount',
            'orders.amount',
            'orders.tranport_charge',
            'orders.process_status',
            'orders.order_description',
            'orders.customer_description',
            'orders.payment_method_id',
            'orders.order_source_id',
            'orders.transport_id',
            'orders.voucher_code',
            'orders.discount_member',
            'orders.is_apply',
            'orders.created_by',
            'orders.updated_by',
            'orders.created_at',
            'orders.updated_at',
            'orders.receive_at_counter',
            'orders.customer_contact_id',
            'orders.is_deleted'
        )->where('orders.order_id', $id);
        return $kq->first();
    }
}