<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/2/2018
 * Time: 12:06 PM
 */

namespace Modules\Admin\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ProductTable extends Model
{
    use ListTableTrait;
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'product_id',
        'product_category_id',
        'product_model_id',
        'product_name',
        'product_name_en',
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
//        'description_en',
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
//        'slug_en',
        'type_refer_commission',
        'refer_commission_value',
        'type_staff_commission',
        'staff_commission_value',
        'type_deal_commission',
        'deal_commission_value',
        'description_detail',
//        'description_detail_en',
        'type_app',
        'percent_sale',
        'product_code',
        'inventory_management',
        'avatar_app',
        'is_topping'
    ];

    /**
     * Insert or update
     *
     * @param array $data
     * @param $productCode
     * @return mixed
     */
    public function insertOrUpdate($dataMaster)
    {
        return $this->updateOrCreate(
            [
                'product_code' => $dataMaster['product_code']
            ],
            $dataMaster);
    }

    public function getProductByCode($code)
    {
        return $this->select()
                    ->where('is_deleted', 0)
                    ->where('product_code', $code)
                    ->first();
    }

    protected function _getList(&$filter = [])
    {
        $select = $this
            ->leftJoin('product_categories', 'product_categories.product_category_id', '=', 'products.product_category_id')
            ->leftJoin('product_model', 'product_model.product_model_id', '=', 'products.product_model_id')
//            ->join('product_branch_prices', 'product_branch_prices.product_id', '=', 'products.product_id')
//            ->join('branches', 'branches.branch_id', '=', 'product_branch_prices.branch_id')
            ->select(
                'product_categories.product_category_id as proCategoryId',
                'product_categories.category_name as proCategoryName',
//                'product_model.product_model_id as proModelId',
                'product_model.product_model_name as proModelName',
                'products.product_name as proName',
//                'products.product_name_en as proNameEn',
                'products.product_short_name as proShortName',
                'products.cost as proCost',
                'products.is_topping',
                'products.price_standard as proPriceStandard',
                'products.price_standard as proPriceStandard',
                'products.is_sales as proIsSale',
                'products.is_promo as proIsPromo',
                'products.type as proType',
                'products.is_actived as proIsActived',
                'products.product_id as proId',
                'products.is_sales as proSale',
                'products.is_promo as proPromo',
                'products.avatar as avatar',
                'products.avatar_app as avatar_app'
            )
            ->where('products.is_deleted', 0)->groupBy('products.product_id');
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            if ($startTime == $endTime) {
                $select->whereBetween('products.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            } else {
                $select->whereBetween('products.created_at', [$startTime, $endTime]);
            }
        }


        if( $filter['sort'] ?? false ){
            switch ($filter['sort']){
                case ('price_asc'):
                    $select->orderBy('products.price_standard','ASC');
                    break;
                case ('price_desc'):
                    $select->orderBy('products.price_standard','DESC');
                    break;
            }
        }else{
            $select->orderBy('products.product_id', 'desc');
        }

        unset($filter["created_at"], $filter['sort']);
        return $select;
    }

    public function getAll($filter = [])
    {
        $oSelect = $this->leftJoin('product_categories', 'product_categories.product_category_id', '=', 'products.product_category_id')
            ->select(
                'product_categories.product_category_id as proCategoryId',
                'product_categories.category_name as proCategoryName',
                'products.product_name as proName',
//                'products.product_name_en as proNameEn',
                'products.cost as proCost',
                'products.type as proType',
                'products.is_topping',
                'products.product_id as proId'
            )
            ->where('products.is_deleted', 0)
            ->where("products.is_actived", 1)
            ->groupBy('products.product_id');


        if (isset($filter["product_type"]) && $filter["product_type"] != "") {
            $oSelect->where("product_categories.product_category_id", $filter["product_type"]);
        }

        if (isset($filter["keyword"]) && $filter["keyword"] != "") {
            $oSelect->where("products.product_name", "LIKE", "%" . $filter["keyword"] . "%");
        }


        return $oSelect->get();
    }

    public function getProductInId($arr_id)
    {
        $oSelect = $this->leftJoin('product_categories', 'product_categories.product_category_id', '=', 'products.product_category_id')
            ->select(
                'products.product_id as productId',
                'products.product_name as productName',
//                'products.product_name_en as productNameEn',
                'products.cost as cost',
                'products.price_standard as price',
                'products.is_promo as isPromo',
                'products.is_inventory_warning as isInventoryWarning',
                'products.inventory_warning as inventoryWarning',
                'products.is_actived as isActived',
                'products.is_topping',
                'product_categories.category_name as categoryName'
            )
            ->where('products.is_deleted', 0)
            ->where("products.is_actived", 1)
            ->whereIn('products.product_id', $arr_id)->get();
        return $oSelect;
    }

    //Function add product
    public function add(array $data)
    {
        $product = $this->create($data);
        return $product->product_id;
    }

    //Function add product
    public function createProduct(array $data)
    {
        return $this->create($data);
    }

    //Function delete product
    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->update(['is_deleted' => 1]);
    }

    /*
     * get item
     */

    public function getItem($id)
    {
        $oSelect = $this->leftJoin('product_categories', 'product_categories.product_category_id', '=', 'products.product_category_id')
            ->leftJoin('product_model', 'product_model.product_model_id', '=', 'products.product_model_id')
            ->leftJoin('units', 'units.unit_id', '=', 'products.unit_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'products.supplier_id')
            ->select(
                'products.product_id as productId',
                'products.product_name as productName',
//                'products.product_name_en as productNameEn',
                'products.cost as cost',
                'products.price_standard as price',
                'products.is_promo as isPromo',
                'products.is_inventory_warning as isInventoryWarning',
                'products.inventory_warning as inventoryWarning',
                'products.is_actived as isActived',
                'product_categories.category_name as categoryName',
                'product_categories.product_category_id as productCategoryId',
                'product_model.product_model_name as productModelName',
                'product_model.product_model_id as productModelId',
                'suppliers.supplier_name as supplierName',
                'suppliers.supplier_id as supplierId',
                'units.name as unitName',
                'units.unit_id as unitId',
                'products.is_all_branch as isAllBranchPrice',
                'products.avatar as avatar',
                'products.avatar_app as avatar_app',
                'products.description as description',
//                'products.description_en as description_en',
                'products.is_sales as isSale',
                'products.type_refer_commission',
                'products.refer_commission_value',
                'products.type_staff_commission',
                'products.staff_commission_value',
                'products.type_deal_commission',
                'products.deal_commission_value',
                'products.description_detail',
//                'products.description_detail_en',
                'products.type_app',
                'products.percent_sale',
                'products.product_code',
                'products.inventory_management',
                'products.is_topping',
                'inventory_management'
            )
            ->where('products.is_deleted', 0)->where('products.product_id', $id)->first();
        return $oSelect;
    }

    /*
     *Function edit product
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /*
     * Function test code product
     */
    public function testCode($code, $id)
    {
        return $this->where('product_id', '<>', $id)
            ->where('product_code', $code)->first();
    }

    // Get option
    public function getOption()
    {
        return $this->select('product_id', 'product_name')->where('is_deleted', 0)->get()->toArray();
    }

    // Get detail product
    public function getDetailProduct($id)
    {
        $dataDetail = $this->leftJoin('product_branch_prices', 'product_branch_prices.product_id', '='
            , 'products.product_id')
            ->leftJoin('product_categories', 'product_categories.product_category_id', '=',
                'products.product_category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'products.supplier_id')
            ->leftJoin('units', 'units.unit_id', '=', 'products.unit_id')
            ->leftJoin('product_model', 'product_model.product_model_id', '=', 'products.product_model_id')
            ->select(
                'products.product_id as productId',
                'products.product_name as productName',
//                'products.product_name_en as productNameEn',
                'products.cost as productCost',
                'products.price_standard as productPrice',
                'products.is_inventory_warning as isInventoryWarning',
                'products.is_promo	 as isPromo',
                'product_branch_prices.product_code as productCode',
                'product_categories.category_name as productCategory',
                'suppliers.supplier_name as supplierName',
                'units.name as unitName',
                'product_model.product_model_name as productModelName',
                'products.avatar as avatar',
                'products.avatar_app as avatar_app',
                'products.product_code',
                'products.is_topping',
                'products.inventory_management'
            )
            ->where('products.is_deleted', 0)
            ->where('products.product_id', $id)->first();
        return $dataDetail;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function searchProduct($data)
    {
        $ds = $this->select('product_id', 'product_name', 'price_standard')->where('product_name', 'like', '%' . $data . '%')->get();
        return $ds;
    }

    public function getListAdd()
    {
        $ds = $this->select('product_id', 'product_name', 'price_standard')->where('is_deleted', 0)->get();
        return $ds;
    }

    //Kiểm tra trùng tên sản phẩm.
    public function checkName($name, $id)
    {
        $select = $this->where('slug', $name)->where('is_deleted', 0);
        if ($id != null) {
            $select->where('product_id', '<>', $id);
        }
        return $select->first();
    }

    public function checkNameEN($name, $id)
    {
        $select = $this->where('slug_en', $name)->where('is_deleted', 0);
        if ($id != null) {
            $select->where('product_id', '<>', $id);
        }
        return $select->first();
    }

    //Kiểm tra trùng tên sản phẩm.
    public function checkSku($sku, $id)
    {
        $select = $this->where('product_sku', $sku)->where('is_deleted', 0);
        if ($id != null) {
            $select->where('product_id', '<>', $id);
        }
        return $select->first();
    }

    public function getProduct()
    {
        return $this->where('is_deleted', 0)->get()->toArray();
    }

    public function getProductTopId()
    {
        $select = $this
            ->select('product_id')
            ->where('is_deleted', 0)
            ->orderBy('product_id', 'desc')
            ->limit(1);
        return $select->first();
    }
}