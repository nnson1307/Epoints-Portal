<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ProductChildTable extends Model
{
    use ListTableTrait;
    protected $table = 'product_childs';
    protected $primaryKey = 'product_child_id';
    protected $fillable
        = [
            'product_child_id', 'product_id',
            'product_code', 'product_child_name',
            'unit_id', 'cost', 'price', 'created_at', 'updated_at',
            'created_by', 'updated_by', 'is_deleted', 'is_actived', 'slug',
            'is_sales', 'type_app', 'percent_sale',
            'is_display', 'is_applied_kpi'
        ];

    const IS_NOT_DELETED = 0;
    const IS_ACTIVE = 1;
    const IS_SALE = 1;


    /**
     * Danh sách sản phẩm con phân trang
     *
     * @param $param
     * @return mixed
     */
    public function getListChildOrderPaginate($param)
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
            ->where('products.is_actived', self::IS_ACTIVE);
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

    /**
     * Lấy sản phẩm con theo code
     *
     * @param $code
     * @return mixed
     */
    public function getProductChildByCode($code)
    {
        $select = $this->select(
                'product_childs.product_child_id as product_child_id',
                'product_childs.product_id as product_id',
                'product_childs.product_code as product_code',
                'product_childs.product_child_name as product_child_name',
                'product_childs.unit_id as unit_id',
                'product_childs.cost as cost',
                'product_childs.price as price',
                'product_childs.created_at as created_at',
                'product_childs.updated_at as updated_at',
                'product_childs.created_by as created_by',
                'product_childs.updated_by as updated_by',
                'product_childs.is_deleted as is_deleted',
                'product_childs.is_actived as is_actived')
            ->leftJoin('products', 'products.product_id', '=', 'product_childs.product_id')
            ->where('product_childs.product_code', $code)
            ->where('products.is_deleted', self::IS_NOT_DELETED)
            ->where('product_childs.is_deleted', self::IS_NOT_DELETED);
        return $select->first();
    }

    /**
     * Chi tiết sản phẩm con
     *
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this
            ->select(
                'product_childs.product_child_id',
                'product_childs.product_id',
                'product_childs.product_code',
                'product_childs.product_child_name',
                'product_childs.unit_id',
                'product_childs.cost',
                'product_childs.price',
                'product_childs.created_at',
                'product_childs.updated_at',
                'product_childs.created_by',
                'product_childs.updated_by',
                'product_childs.is_deleted',
                'product_childs.is_actived',
                'product_childs.slug',
                'product_childs.is_sales',
                'product_childs.type_app',
                'product_childs.percent_sale',
                'product_childs.is_applied_kpi',
                'products.type_refer_commission',
                'products.refer_commission_value',
                'products.type_staff_commission',
                'products.staff_commission_value',
                'products.type_deal_commission',
                'products.deal_commission_value'
            )
            ->leftJoin('products', 'products.product_id', '=', 'product_childs.product_id')
            ->where('product_childs.product_child_id', $id)->first();
    }

    /**
     * Lấy thông tin sản phẩm khuyến mãi
     *
     * @param $productCode
     * @return mixed
     */
    public function getProductPromotion($productCode)
    {
        return $this
            ->select(
                "product_child_id",
                "product_code",
                "product_child_name",
                "cost as old_price",
                "price as new_price"
            )
            ->where("product_code", $productCode)
            ->first();
    }
}