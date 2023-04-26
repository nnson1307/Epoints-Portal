<?php


namespace Modules\Report\Models;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class DeliveryCostTable extends Model
{
    protected $table = "delivery_costs";
    protected $primaryKey = "delivery_cost_id";

    /**
     * Lấy các option chi phí vận chuyển
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this->select('delivery_cost_id', 'delivery_cost_code', 'delivery_cost_name')
            ->where('is_actived', 1)
            ->get();
    }

    /**
     * Lấy data cho báo cáo dựa theo post code
     *
     * @param $deliveryCostCode
     * @param $time
     * @return mixed
     */
    public function getPostcode($deliveryCostCode, $time)
    {
        $res = $this
            ->select(
                "delivery_cost_detail.postcode as postcode",
//                DB::raw("count(orders.order_id) as total")
                "orders.order_id"
            )
            ->join("delivery_cost_detail", "{$this->table}.delivery_cost_code", "=", "delivery_cost_detail.delivery_cost_code")
            ->join("customers", "delivery_cost_detail.postcode", "=", "customers.postcode")
            ->join("orders", "orders.customer_id", "=", "customers.customer_id")
            ->where("{$this->table}.delivery_cost_code", $deliveryCostCode)
            ->where("customers.is_deleted", 0)
            ->where("orders.is_deleted", 0)
            ->where("orders.process_status", "<>", "ordercancle")
            ->groupBy("orders.order_id");

        if ($time != null) {
            $arr_filter = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $res->whereBetween("orders.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        return $res->get();
    }
}