<?php


namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class ProductTable extends Model
{
    protected $table = "products";

    protected $fillable = [
        'product_id',
        'product_category_id',
        'product_model_id',
        'product_name',
        'product_short_name',
        'unit_id',
        'cost',
        'price_standard',
        'is_sales',
        'is_promo',
        'type',
        'type_manager',
        'count_version',
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
        'product_code',
        'is_all_branch',
        'avatar',
        'slug'
    ];

    public function getProductList($filter)
    {
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['display'] ?? PAGING_ITEM_PER_PAGE);
        $select = $this->select(
            'product_id', 'product_name',
            'product_category_id', 'product_code',
            'avatar', 'price_standard',
            'type', 'description'
        )
            ->where('is_deleted', 0)
            ->where('is_actived', 1);
        if (isset($filter['product_category_id']) && $filter['product_category_id'] != null) {
            if ($filter['product_category_id'] != 'all'){
                $select->where("product_category_id", $filter['product_category_id']);
            }
        }
        $select->orderBy('updated_at', 'desc');
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getProductDetailGroup($id)
    {
        $select = $this->select(
            'products.product_id', 'products.product_name',
            'products.product_category_id', 'products.product_code',
            'products.avatar', 'products.price_standard',
            'products.type', 'products.description' , 'product_categories.category_name as category_name'
        )
            ->join('product_categories' , 'product_categories.product_category_id','products.product_category_id')
            ->where('products.product_id', $id)
            ->where('products.is_deleted', 0)
            ->where('products.is_actived', 1);
        return $select->first();

    }
}