<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBranchPriceTable extends Model
{
    protected $table = 'product_branch_prices';
    protected $primaryKey = 'product_branch_price_id';
    protected $fillable = ['product_branch_price_id', 'product_id', 'branch_id', 'product_code',
        'old_price', 'new_price', 'created_by', 'updated_by', 'created_at', 'updated_at', 'is_actived', 'is_deleted'];
    const NOT_DELETE = 0;
    const IS_ACTIVE = 1;

    public function getProductBranchPriceByCode($branch, $code)
    {
        $select = $this
            ->select(
                'product_branch_prices.branch_id as branch_id',
                'product_branch_prices.product_branch_price_id as product_branch_price_id',
                'product_branch_prices.old_price as old_price',
                'product_branch_prices.new_price as new_price',
                'branches.branch_name as branch_name',
                'product_branch_prices.product_id as product_id',
                'product_branch_prices.product_code as product_code',
                'product_childs.product_child_name as product_child_name',
                'products.avatar as avatar',
                'product_childs.product_child_id as product_child_id')
            ->leftJoin('product_childs', 'product_childs.product_child_id', '=', 'product_branch_prices.product_id')
            ->leftJoin('products', 'products.product_id', '=', 'product_childs.product_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'product_branch_prices.branch_id')
            ->where('product_branch_prices.branch_id', $branch)
            ->where('product_childs.product_code', $code)
            ->where('product_branch_prices.is_deleted', self::NOT_DELETE)
            ->where('product_branch_prices.is_actived', self::IS_ACTIVE)
            ->where('product_childs.is_deleted', self::NOT_DELETE)
            ->where('product_childs.is_actived', self::IS_ACTIVE)
            ->where('products.is_deleted', self::NOT_DELETE)
            ->where('products.is_actived', self::IS_ACTIVE);
        return $select->first();
    }
}