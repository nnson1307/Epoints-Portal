<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/07/2022
 * Time: 15:36
 */

namespace Modules\Ticket\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderTable extends Model
{
    protected $table = "orders";
    protected $primaryKey = "order_id";

    protected $casts = [
        'total' => 'float',
        'discount' => 'float',
        'amount' => 'float',
        'discount_member' => 'float',
        'tranport_charge' => 'float'
    ];


    /**
     * Thông tin đơn hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function orderInfo($orderId)
    {
        $textTransportSave = __('Tiết kiệm');
        $textTransportSpeed = __('Hoả tốc');

        return $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.order_code",
                "{$this->table}.branch_id",
                "branches.branch_name",
                "refer.full_name as refer_name",
                "{$this->table}.total",
                "{$this->table}.discount",
                "{$this->table}.tranport_charge",
                "{$this->table}.amount",
                "{$this->table}.process_status",
                "{$this->table}.voucher_code",
                "{$this->table}.discount_member",
                "{$this->table}.created_at as order_date",
                "deliveries.contact_address",
                "deliveries.delivery_status",
                "deliveries.is_actived as delivery_active",
                "{$this->table}.customer_contact_code",
                "{$this->table}.order_description",
                "{$this->table}.payment_method_id",
                "{$this->table}.created_at",
                "customers.full_name",
                "customers.phone1 as phone",
                "{$this->table}.customer_id",
                "{$this->table}.order_source_id",
                "{$this->table}.receive_at_counter",
                "{$this->table}.type_shipping",
                "{$this->table}.delivery_cost_id",
                DB::raw("(CASE
                    WHEN  orders.type_shipping = 1 THEN '$textTransportSpeed'
                    ELSE  '$textTransportSave' 
                    END
                ) as type_shipping_text"),
                "customers.address",
                "{$this->table}.created_at",
                "customers.customer_type"
            )
            ->join("branches", function ($join) {
                $join->on("branches.branch_id", "=", "{$this->table}.branch_id")
                    ->where("branches.is_deleted", 0);
            })
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->leftJoin("customers as refer", "refer.customer_id", "=", "{$this->table}.refer_id")
            ->leftJoin("deliveries", "deliveries.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.order_id", $orderId)
            ->first();
    }
}