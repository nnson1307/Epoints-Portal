<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/6/2021
 * Time: 6:14 PM
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;

class ProductInventoryTable extends Model
{
    protected $table = "product_inventorys";
    protected $primaryKey = "product_inventory_id";
    protected $fillable = [
        "product_inventory_id",
        "product_id",
        "product_code",
        "warehouse_id",
        "import",
        "export",
        "quantity",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by"
    ];

    const IS_RETAIL = 1;

    /**
     * Lấy thông tin tồn kho của sp
     *
     * @param $productCode
     * @param $idWarehouse
     * @return mixed
     */
    public function getInventory($productCode, $idWarehouse)
    {
        return $this
            ->select(
                "{$this->table}.product_inventory_id",
                "{$this->table}.product_code",
                "{$this->table}.import",
                "{$this->table}.export",
                "{$this->table}.quantity"
            )
            ->join("warehouses", "warehouses.warehouse_id", "=", "{$this->table}.warehouse_id")
            ->where("{$this->table}.product_code", $productCode)
            ->where("{$this->table}.warehouse_id", $idWarehouse)
            ->where("warehouses.is_retail", self::IS_RETAIL)
            ->first();
    }

    /**
     * Cập nhật tồn kho
     *
     * @param array $data
     * @param $idInventory
     * @return mixed
     */
    public function edit(array $data, $idInventory)
    {
        return $this->where("product_inventory_id", $idInventory)->update($data);
    }

    /**
     * Tạo tồn kho
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->product_inventory_id;
    }
}