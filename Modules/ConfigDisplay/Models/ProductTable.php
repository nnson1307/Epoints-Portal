<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/2/2018
 * Time: 12:06 PM
 */

namespace Modules\ConfigDisplay\Models;


use Illuminate\Database\Eloquent\Model;

class ProductTable extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'product_id',
        'product_category_id',
        'product_model_id',
        'product_name',
        'product_sku',
        'product_short_name',
        'unit_id',
        'cost',
        'price_standard',
        'is_sales',
        'is_promo',
        'type',
        'is_inventory_warning',
        'inventory_warning',
        'description',
        'supplier_id',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'is_deleted',
        'is_actived',
        'is_all_branch',
        'avatar',
        'slug',
        'type_refer_commission',
        'refer_commission_value',
        'type_staff_commission',
        'staff_commission_value',
        'type_deal_commission',
        'deal_commission_value',
        'description_detail',
        'type_app',
        'percent_sale',
        'product_code',
        'inventory_management'
    ];

    const IS_ACTIVE = 1;
    const IS_DELETED = 0;

    public function getAll()
    {
        return $this->select("product_id", "product_name")
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::IS_DELETED)
            ->get();
    }
}
