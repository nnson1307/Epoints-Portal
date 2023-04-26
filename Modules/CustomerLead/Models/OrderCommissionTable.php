<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCommissionTable extends Model
{
    protected $table = 'order_commission';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'order_detail_id',
        'refer_id',
        'staff_id',
        'deal_id',
        'refer_money',
        'staff_money',
        'deal_money',
        'status',
        'staff_commission_rate',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->id;
    }
    public function removeByOrderDetailAndDeal($orderDetailId, $dealId)
    {
        return $this->where("order_detail_id", $orderDetailId)->where("deal_id", $dealId)->delete();
    }
}