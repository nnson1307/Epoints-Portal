<?php

namespace Modules\Admin\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class Order extends Model
{
    use ListTableTrait;

    protected $table = "orders";
    protected $primaryKey = "order_id";
    protected $fillable = ['order_id', 'order_code', 'customer_id', 'total', 'discount', 'amount', 'tranport_charge', 'created_by', 'updated_by', 'created_at', 'updated_at', 'process_status', 'order_description', 'customer_description', 'payment_method_id', 'order_source_id', 'transport_id'];

    protected  function _getList(&$filter=[])
    {
        $oSelect = $this
            ->leftJoin("customers as customers","customers.customer_id","=","orders.customer_id")
            ->leftJoin("order_sources","order_sources.order_source_id","=","orders.order_source_id")
            ->select('order_code',
                "order_id",
                'customers.full_name as customer_name',
                'total',
                'discount',
                'amount',
                'tranport_charge',
                'process_status',
                'orders.created_at as created_at',
                'order_source_name');
        if(isset($filter["created_at"]) && $filter["created_at"] != ""){
            $arr_filter = explode(" - ",$filter["created_at"]);
            $from  = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $to  = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $oSelect->whereBetween('orders.created_at', [$from, $to]);
        }
        unset($filter["created_at"]);
        return $oSelect;
    }

    public function add(array $data){
        return self::create($data);
    }
}
