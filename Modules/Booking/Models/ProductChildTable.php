<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/5/2018
 * Time: 11:24 AM
 */

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;

class ProductChildTable extends Model
{

    protected $table = 'product_childs';
    protected $primaryKey = 'product_child_id';
    protected $fillable = ['product_child_id', 'product_id', 'product_code', 'product_child_name',
        'unit_id', 'cost', 'price', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted', 'is_actived', 'slug'];


    /*
     * get product child by id
     */
    public function getProductChildById($id)
    {
        $select = $this
            ->select(
                'product_childs.product_child_id as product_child_id',
                'product_childs.product_id as product_id',
                'product_childs.product_code as product_code',
                'product_childs.product_child_name as product_child_name',
                'product_childs.unit_id as unit_id',
                'units.name as unit_name',
                'product_childs.cost as cost',
                'product_childs.price as price',
                'product_childs.created_at as created_at',
                'product_childs.updated_at as updated_at',
                'product_childs.created_by as created_by',
                'product_childs.updated_by as updated_by',
                'product_childs.is_deleted as is_deleted',
                'product_childs.is_actived as is_actived',
                'products.description as description'
            )
            ->leftJoin('units', 'units.unit_id', '=', 'product_childs.unit_id')
            ->leftJoin('products', 'products.product_id', '=', 'product_childs.product_id')
            ->where('product_childs.product_child_id', $id)
            ->first();
        return $select;
    }

    public function getProductChild($filter)
    {
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $select = $this->select(
                'product_childs.product_child_id as product_child_id',
                'product_childs.product_id as product_id',
                'product_childs.product_code as product_code',
                'product_childs.product_child_name as product_child_name',
                'product_childs.unit_id as unit_id',
                'units.name as unit_name',
                'product_childs.cost as cost',
                'product_childs.price as price',
                'product_childs.created_at as created_at',
                'product_childs.updated_at as updated_at',
                'product_childs.created_by as created_by',
                'product_childs.updated_by as updated_by',
                'product_childs.is_deleted as is_deleted',
                'product_childs.is_actived as is_actived',
                'products.product_category_id as product_category_id')
            ->leftJoin('products', 'products.product_id', '=', 'product_childs.product_id')
            ->leftJoin('units', 'units.unit_id', '=', 'product_childs.unit_id')
            ->leftJoin('product_categories', 'product_categories.product_category_id', '=', 'products.product_category_id')
            ->where('products.is_deleted',0)
            ->where('products.is_actived',1)
            ->where('product_childs.is_deleted',0)
            ->where('product_childs.is_actived',1)
            ->where('product_categories.is_deleted',0)
            ->where('product_categories.is_actived',1)
        ;
        if (isset($filter['product_category_id']) && $filter['product_category_id'] != null) {
            $select->whereIn('products.product_category_id', $filter['product_category_id']);
        }
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}