<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/27/2021
 * Time: 10:25 AM
 */

namespace Modules\Report\Models;

use Illuminate\Database\Eloquent\Model;

class ProductChildTable extends Model
{
    protected $table = "product_childs";
    protected $primaryKey = "product_child_id";

    const IS_NOT_DELETED = 0;
    const IS_ACTIVE = 1;
    const IS_SALE = 1;
    const SURCHARGE = 0;

    /**
     * Lấy thông tin sản phẩm
     *
     * @param $productCode
     * @return mixed
     */
    public function getProduct($productCode)
    {
        return $this
            ->select(
                "product_child_id",
                "product_child_name"
            )
            ->where("product_code", $productCode)
            ->first();
    }

    /**
     * Lấy ds sản phẩm
     *
     * @param $param
     * @return mixed
     */
    public function getListChild($param)
    {
        $page = (int)(isset($param['page']) ? $param['page'] : 1);
        $display = (int)(isset($param['perpage']) ? $param['perpage'] : FILTER_ITEM_PAGE);

        $ds = $this->leftJoin('products', 'products.product_id', '=', 'product_childs.product_id')
            ->leftJoin('product_categories', 'product_categories.product_category_id', '=', 'products.product_category_id')
            ->leftJoin('units', 'units.unit_id', '=', 'product_childs.unit_id')
            ->select('product_childs.product_child_id', 'product_childs.product_code',
                'product_childs.product_child_name',
                'product_childs.price',
                'units.name',
                'product_childs.product_child_id as product_id', 'products.avatar as avatar',
                'product_childs.product_code as product_code',
                'product_categories.category_name as category_name')
            ->where('product_childs.is_deleted', self::IS_NOT_DELETED)
            ->where('products.is_deleted', self::IS_NOT_DELETED)
            ->where('products.is_actived', self::IS_ACTIVE)
            ->where("{$this->table}.is_surcharge", self::SURCHARGE);
        if (isset($param['search_keyword'])) {
            $ds->where('product_childs.product_child_name', 'LIKE', '%' . $param['search_keyword'] . '%');
        }
        if (isset($param['products$product_category_id'])) {
            $ds->where('products.product_category_id', $param['products$product_category_id']);
        }
        $ds->where('product_childs.is_deleted', self::IS_NOT_DELETED)
            ->where('product_categories.is_deleted', self::IS_NOT_DELETED);
        return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}