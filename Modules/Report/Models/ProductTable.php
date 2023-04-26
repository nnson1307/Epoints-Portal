<?php

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductTable extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    const NOT_DELETE = 0;
    const IS_ACTIVE = 1;
    const SLUG_REGISTRATION_DATE = 'ngay-dang-kiem';
    const DATE = 'date';

    public function getAllProductHaveRegistrationDate()
    {
        $select = $this->select(
            "products.product_name",
            DB::raw("DATE_FORMAT(STR_TO_DATE(pa.product_attribute_label, '%d/%m/%Y'), '%Y-%m-%d') as product_attribute_label")
        )
            ->join("map_product_attributes as mpa", "mpa.product_id", "=", "{$this->table}.product_id")
            ->join("product_attribute_groups as pag", "pag.product_attribute_group_id" ,"=", "mpa.product_attribute_groupd_id")
            ->join("product_attributes as pa", "pa.product_attribute_id", "=", "mpa.product_attribute_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("pag.slug", self::SLUG_REGISTRATION_DATE)
            ->where("pa.type", self::DATE)
            ->orderBy("product_attribute_label");

        return $select->get();
    }
}