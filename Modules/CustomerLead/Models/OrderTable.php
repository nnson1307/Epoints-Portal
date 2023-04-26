<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTable extends Model
{
    protected $table = "orders";
    protected $primaryKey = "order_id";
    protected $fillable = [
        'order_id',
        'order_code',
        'customer_id',
        'total',
        'discount',
        'amount',
        'tranport_charge',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'process_status',
        'order_description',
        'customer_description',
        'payment_method_id',
        'order_source_id',
        'transport_id',
        'voucher_code',
        'is_deleted',
        'branch_id',
        'refer_id',
        'discount_member',
        'is_apply',
        'customer_contact_code',
        'shipping_address',
        'receive_at_counter',
        'deal_code',
        'cashier_by',
        'cashier_date',
        'customer_contact_id',
        'receipt_info_check',
        'type_time',
        'time_address',
        'type_shipping',
        'delivery_cost_id'
    ];

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->order_id;
    }

    /**
     * Cập nhật đơn hàng
     *
     * @param $data
     * @param $orderId
     * @return mixed
     */
    public function edit($data, $orderId)
    {
        return $this->where('order_id', $orderId)->update($data);
    }

    /**
     * Lấy thông tin đơn hàng theo mã deal
     *
     * @param $dealCode
     * @return mixed
     */
    public function getItemByDealCode($dealCode)
    {
        $select = $this->select(
            'order_id',
            'order_code',
            'customer_id',
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
            'is_deleted',
            'branch_id',
            'refer_id',
            'discount_member',
            'is_apply',
            'customer_contact_code',
            'shipping_address',
            'receive_at_counter',
            'deal_code'
        )
            ->where('deal_code', $dealCode)
            ->where('is_deleted', 0);
        return $select->first();
    }
}