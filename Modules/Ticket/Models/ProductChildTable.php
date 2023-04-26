<?php

namespace Modules\Ticket\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ProductChildTable extends Model
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
        $ds = $this
            ->select(
                "{$this->table}.product_child_id as product_id",
                "{$this->table}.product_code as product_code",
                "{$this->table}.product_child_name as product_name",
                "{$this->table}.cost as old_price",
                "{$this->table}.price as new_price",
                "products.description",
                "products.description_detail",
                "units.name as unit_name"
            )
            ->join("products", "products.product_id", "=", "{$this->table}.product_id")
            ->join("product_categories", "product_categories.product_category_id", "=", "products.product_category_id")
            ->leftJoin("units", "units.unit_id", "=", "products.unit_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_display", self::IS_DISPLAY)
            ->where("{$this->table}.is_surcharge", self::SURCHARGE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_deleted", self::NOT_DELETE);


        if (isset($filter["arrProductIdNot"]) && $filter["arrProductIdNot"] != null) {
            $ds->whereNotIn("{$this->table}.product_child_id", $filter["arrProductIdNot"]);
        }

        return $ds->get();
    }
}