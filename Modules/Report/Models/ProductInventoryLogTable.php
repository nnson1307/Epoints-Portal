<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 19/05/2021
 * Time: 15:56
 */

namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;

class ProductInventoryLogTable extends Model
{
    protected $table = "product_inventory_logs";
    protected $primaryKey = "product_inventory_log_id";

    /**
     * Lấy tồn đầu kỳ của sản phẩm theo kho
     *
     * @param $productCode
     * @param $warehouseId
     * @param $date
     * @return mixed
     */
    public function getInventoryLog($productCode, $warehouseId, $date)
    {
        return $this
            ->select(
                "product_inventory_log_id",
                "warehouse_id",
                "product_id",
                "product_code",
                "inventory",
                "inventory_value",
                "export",
                "import"
            )
            ->where("product_code", $productCode)
            ->where("warehouse_id", $warehouseId)
            ->whereDate("created_at", $date)
            ->first();
    }
}