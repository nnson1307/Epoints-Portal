<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetailTable extends Model
{
    protected $table = "order_details";
    protected $primaryKey = "order_detail_id";
    protected $fillable = [
        'order_detail_id',
        'order_id',
        'object_id',
        'object_name',
        'object_type',
        'object_code',
        'price',
        'quantity',
        'discount',
        'amount',
        'voucher_code',
        'staff_id',
        'refer_id',
        'updated_at',
        'created_at',
        'created_by',
        'updated_by',
        'is_deleted',
        'quantity_type',
        'case_quantity',
        'saving',
        'is_change_price',
        'is_check_promotion',
        "order_detail_id_parent",
        "created_at_day",
        "created_at_month",
        "created_at_year",
        "delivery_date",
        "note"
    ];

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->order_detail_id;
    }
    public function removeOrderDetailById($orderId)
    {
        return $this->where("order_id", $orderId)->delete();
    }
}