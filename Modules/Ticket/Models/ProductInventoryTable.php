<?php

namespace Modules\Ticket\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ProductInventoryTable extends Model
{
    use ListTableTrait;
    protected $table = "product_childs";
    protected $primaryKey = "product_child_id";

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;
    const IS_DISPLAY = 1;
    const SURCHARGE = 0;

    /**
     * Lấy ds sản phẩm tồn kho 
     *
     * @param $filter
     */
    public function getListProductInventory($filter = [])
    {
        $query = $this
            ->select(
                "{$this->table}.product_id",
                "{$this->table}.product_child_name as product_name",
                "{$this->table}.product_code",
                "pi.quantity",
                "units.name as unitName",
                "units.unit_id as unitId"
            )
            ->join("product_inventorys as pi", "pi.product_id", "=", "{$this->table}.product_id")
            ->join("units", "units.unit_id", "=", "{$this->table}.unit_id")
            ->join("products", "products.product_id", "=", "{$this->table}.product_id")
            ->where("pi.warehouse_id", "<>", 0)
            ->where("pi.quantity", ">", 0)
            ->where("products.is_actived", 1)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->groupBy("pi.product_id");
            if (isset($filter['product_id']) && $filter['product_id'] != null) {
                if (isset($filter['warehouse_id']) && $filter['warehouse_id'] != null) {
                    $query->where("pi.warehouse_id", $filter['warehouse_id']);
                }
                $query->where("pi.product_id", $filter['product_id']);
                return $query->first()->toArray();
            }
            if (isset($filter['warehouse_id']) && $filter['warehouse_id'] != null) {
                $query->where("pi.warehouse_id", $filter['warehouse_id']);
                return $query->get()->pluck("product_name","product_id")->toArray();
            }
            if (isset($filter['get_option']) && $filter['get_option'] != null) {
               return $query->get()->pluck("product_name","product_id")->toArray();
            }
        return $query->get()->toArray();
    }

    /**
     * Lấy ds sản phẩm tồn kho 
     *
     * @param $filter
     */
    public function checkProductExist($filter = [])
    {
        $query = $this
            ->select(
                "{$this->table}.product_id",
                "{$this->table}.product_child_name as product_name",
                "{$this->table}.product_code",
                "pi.quantity",
                "pi.warehouse_id",
                "warehouses.name as warehouse_name",
                "units.name as unitName",
                "units.unit_id as unitId"
            )
            ->join("product_inventorys as pi", "pi.product_id", "=", "{$this->table}.product_id")
            ->join("units", "units.unit_id", "=", "{$this->table}.unit_id")
            ->join("products", "products.product_id", "=", "{$this->table}.product_id")
            ->join("product_categories", "product_categories.product_category_id", "=", "products.product_category_id")
            ->leftjoin("warehouses", "warehouses.warehouse_id", "=", "pi.warehouse_id")
            ->where("pi.warehouse_id", "<>", 0)
            ->where("pi.quantity", ">", 0)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            // ->where("{$this->table}.is_display", self::IS_DISPLAY)
            // ->where("{$this->table}.is_surcharge", self::SURCHARGE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_deleted", self::NOT_DELETE)
            ->groupBy("pi.product_id");
            if (isset($filter['product_code']) && $filter['product_code'] != null) {
                $query->where("{$this->table}.product_code", $filter['product_code']);
            }
            if (isset($filter['product_child_name']) && $filter['product_child_name'] != null) {
                $query->where("{$this->table}.product_child_name", $filter['product_child_name']);
            }
            if (isset($filter['quantity']) && $filter['quantity'] != null) {
                $query->where("pi.quantity",'>=', $filter['quantity']);
            }
            if (isset($filter['warehouse_name']) && $filter['warehouse_name'] != null) {
                $query->where("warehouses.name",'like', $filter['warehouse_name']);
            }
        return $query->first();
    }
}