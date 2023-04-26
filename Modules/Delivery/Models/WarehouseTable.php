<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/6/2021
 * Time: 5:20 PM
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WarehouseTable extends Model
{
    protected $table = "warehouses";
    protected $primaryKey = "warehouse_id";

    const NOT_DELETED = 0;
    const IS_RETAIL = 1;
    const IS_ACTIVE = 1;

    /**
     * Lấy thông tin kho lấy hàng
     *
     * @return mixed
     */
    public function getWarehouse()
    {
        return $this
            ->select(
                "{$this->table}.warehouse_id",
                "{$this->table}.name",
                "{$this->table}.address",
                "{$this->table}.branch_id",
                "{$this->table}.province_id",
                "{$this->table}.district_id",
                "{$this->table}.ghn_shop_id"
            )
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.is_retail", self::IS_RETAIL)
            ->where("branches.is_actived", self::IS_ACTIVE)
            ->where("branches.is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy thông tin kho lấy hàng chi tiết
     *
     * @return mixed
     */
    public function getWarehouseDetail($warehouse_id)
    {
        return $this
            ->select(
                "{$this->table}.warehouse_id",
                "{$this->table}.name",
                "{$this->table}.address",
                "{$this->table}.branch_id",
                "{$this->table}.province_id",
                "{$this->table}.district_id",
                "{$this->table}.ward_id",
                "{$this->table}.ghn_shop_id"
            )
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.is_retail", self::IS_RETAIL)
            ->where("{$this->table}.warehouse_id", $warehouse_id)
            ->where("branches.is_actived", self::IS_ACTIVE)
            ->where("branches.is_deleted", self::NOT_DELETED)
            ->first();
    }
}