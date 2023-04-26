<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 19/05/2021
 * Time: 11:40
 */

namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;

class WarehouseTable extends Model
{
    protected $table = "warehouses";
    protected $primaryKey = "warehouse_id";

    const NOT_DELETED = 0;

    /**
     * Lấy danh sách kho
     *
     * @return mixed
     */
    public function optionWarehouse()
    {
        return $this
            ->select(
                "warehouse_id",
                "name"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy danh sách kho bằng warehouse_id
     *
     * @param $warehouseId
     * @return mixed
     */
    public function getWarehouse($warehouseId)
    {
        $ds = $this
            ->select(
                "warehouse_id",
                "name"
            )
            ->where("is_deleted", self::NOT_DELETED);

        if (isset($warehouseId) && $warehouseId != null) {
            $ds->where("warehouse_id", $warehouseId);
        }

        return $ds->get();
    }
}