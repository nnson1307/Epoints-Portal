<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 19/05/2021
 * Time: 17:14
 */

namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryInputDetailTable extends Model
{
    protected $table = "inventory_input_details";
    protected $primaryKey = "inventory_input_detail_id";

    const SUCCESS = "success";

    /**
     * Lấy số lượng nhập kho sản phẩm (từ ngày - đến ngày)
     *
     * @param $productCode
     * @param $warehouseId
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getInputToDate($productCode, $warehouseId, $startTime, $endTime)
    {
        return $this
            ->select(
                "{$this->table}.product_code",
                DB::raw("SUM({$this->table}.quantity) as quantity"),
                DB::raw("SUM({$this->table}.total) as total")
            )
            ->join("inventory_inputs as ip", "ip.inventory_input_id", "=", "{$this->table}.inventory_input_id")
            ->where("{$this->table}.product_code", $productCode)
            ->where("ip.warehouse_id", $warehouseId)
            ->whereBetween('ip.created_at', [$startTime, $endTime])
            ->where("ip.status", self::SUCCESS)
            ->groupBy("{$this->table}.product_code")
            ->first();
    }
}