<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/9/2018
 * Time: 4:00 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class ProductBranchPriceTable extends Model
{
    use ListTableTrait;
    protected $table = 'product_branch_prices';
    protected $primaryKey = 'product_branch_price_id';
    protected $fillable = ['product_branch_price_id', 'product_id', 'branch_id', 'product_code',
        'old_price', 'new_price', 'created_by', 'updated_by', 'created_at', 'updated_at', 'is_actived', 'is_deleted'];

    protected function _getList()
    {
        return $this->select('product_branch_price_id', 'product_id', 'branch_id', 'product_code',
            'old_price', 'new_price', 'created_by', 'updated_by', 'created_at', 'updated_at', 'is_actived', 'is_deleted')
            ->where('is_deleted', 0)->orderBy($this->primaryKey, 'desc');
    }

    //Add product branch price
    public function add(array $data)
    {
        $productCategory = $this->create($data);
        return $productCategory->product_category_id;
    }


    //Add product branch price
    public function createProductBranchPrice(array $data)
    {
        return $this->create($data);
    }

    /*
    * Delete product branch price
    */
    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->update(['is_deleted' => 1]);
    }

    /*
         * Edit product branch price
         */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /*
     * get item
     */

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    /*
     * Get product branch price by product
     */
    public function getProductBranchPriceByProduct($idProduct)
    {
        $listProductBranchPriceByProduct = $this->leftJoin('branches', 'branches.branch_id', '=', 'product_branch_prices.branch_id')
            ->select(
                'product_branch_prices.branch_id as branchId',
                'branches.branch_name as branchName'
            )->where('product_branch_prices.product_id', $idProduct)
            ->where('product_branch_prices.is_deleted', 0)->get()->toArray();
        return $listProductBranchPriceByProduct;
    }

    /*
     * Update product branch price by product id
     */
    public function updateProductBranchPriceByProductId($productId)
    {
        return $this->where('product_id', $productId)->where('is_deleted', 0)->delete();
    }

    /*
     * Get product code by product id
     */
    public function getProductCodeByProductId($productId)
    {
        $result = $this->select('product_code', 'created_by')->where('product_id', $productId)->where('is_deleted', 0)->first();
        return $result;
    }

    public function getAllProductBranchPriceByProductId($product)
    {
        return $this->where('product_id', $product)->where('is_deleted', 0)->get();
    }

    /*
    * test branch id by product id
    */
    public function testBanchId($productId, $branchId)
    {
        return $this->where('product_id', $productId)->where('branch_id', $branchId)->where('is_deleted', 0)->first();
    }

    public function deleteBranchPrice($productId, $branchId)
    {
        return $this->where('product_id', $productId)->where('branch_id', $branchId)->update(['is_deleted' => 1]);
    }

    /*
         * Test product code
         */
    public function testProductCode($code)
    {
        return $this->where('product_code', $code)->first();
    }

    public function getProductBranchPrice($productId)
    {
        $ds = $this
            ->leftJoin('branches', 'branches.branch_id', '=', 'product_branch_prices.branch_id')
//            ->leftJoin('products', 'products.product_id', '=', 'product_branch_prices.product_id')
            ->leftJoin('product_childs', 'product_childs.product_child_id', '=', 'product_branch_prices.product_id')
            ->select(
                'product_branch_prices.product_branch_price_id',
                'product_branch_prices.branch_id',
                'product_branch_prices.product_id',
                'product_branch_prices.new_price'
            )
            ->where("{$this->table}.product_id", $productId)
            ->get();
        return $ds;
    }

    public function getProductBranchPriceArrayProduct($arrProduct)
    {
        $ds = $this->leftJoin('branches', 'branches.branch_id', '=', 'product_branch_prices.branch_id')
//            ->leftJoin('products', 'products.product_id', '=', 'product_branch_prices.product_id')
            ->leftJoin('product_childs', 'product_childs.product_child_id', '=', 'product_branch_prices.product_id')
            ->whereIn('product_branch_prices.product_id', $arrProduct)
            ->selectRaw('product_branch_prices.product_branch_price_id,
                        product_branch_prices.branch_id,
                        product_branch_prices.product_id,
                        product_branch_prices.new_price')
            ->get();
        return $ds;
    }

    /**
     * Lấy danh sách branch theo id product và các branch tồn tại trong danh sách listId
     *
     * @param array $filter
     * @param $id
     * @param array $listId
     * @return mixed
     */
    public function getListBr(array $filter = [], $id, array $listId = [])
    {
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['display'] ?? PAGING_ITEM_PER_PAGE);
        $ds = self::leftJoin('products', 'products.product_id', '=', 'product_branch_prices.product_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'product_branch_prices.branch_id')
            ->select('product_branch_prices.branch_id as branch_id',
                'product_branch_prices.product_branch_price_id as product_branch_price_id',
                'product_branch_prices.old_price as old_price',
                'product_branch_prices.new_price as new_price',
                'product_branch_prices.is_actived as is_actived',
                'product_branch_prices.created_at as created_at',
                'product_branch_prices.updated_at as updated_at',
                'product_branch_prices.created_by as created_by',
                'product_branch_prices.updated_by as updated_by',
                'branches.branch_name as branch_name')
            ->where('product_branch_prices.is_deleted', 0)
            ->where('branches.is_deleted', 0)
            ->where('product_branch_prices.product_id', $id);

        if ($listId != null) {
            $ds->whereIn('product_branch_prices.branch_id', $listId);
        }

        if (isset($filter["search_branch"]) && $filter["search_branch"] != "") {
            $ds->where("product_branch_prices.branch_id", $filter["search_branch"]);
        }
        return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getProductBranchPriceByBranchId($id)
    {
        $ds = $this->leftJoin('products', 'products.product_id', '=', 'product_branch_prices.product_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'product_branch_prices.branch_id')
            ->where('product_branch_prices.is_deleted', 0)
            ->where('product_branch_prices.branch_id', $id)
            ->select('product_branch_prices.branch_id as branch_id',
                'product_branch_prices.product_branch_price_id as product_branch_price_id',
                'product_branch_prices.old_price as old_price',
                'product_branch_prices.new_price as new_price',
                'product_branch_prices.is_actived as is_actived',
                'product_branch_prices.product_id as product_id',
                'branches.branch_name as branch_name')->get();

        return $ds;
    }

    public function editConfigPrice(array $data, $branchId)
    {
        $check = $this->where('product_id', $data[0])
            ->where('branch_id', $branchId)
            ->first();
        if ($check != null) {
            $productBranchPrice = $this->where('product_id', $data[0])
                ->where('branch_id', $branchId)
                ->update([
                    'new_price' => $data[2],
                    'is_actived' => ($data[3] == 'true') ? 1 : 0,
                    'updated_by' => Auth::id(),
                    'is_deleted' => 0,
                ]);
        } else {
            if ($data[3] == 'true') {
                $productBranchPrice = $this->create([
                    'branch_id' => $branchId,
                    'product_id' => $data[0],
                    'old_price' => $data[1],
                    'new_price' => $data[2],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'is_actived' => 1,
                    'is_deleted' => 0,
                ]);
            }

        }
    }

    public function getItemBranch($branch)
    {
        $ds = $this
            ->leftJoin('product_childs', 'product_childs.product_child_id', '=', 'product_branch_prices.product_id')
            ->leftJoin('products', 'products.product_id', '=', 'product_childs.product_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'product_branch_prices.branch_id')
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
                'product_childs.product_child_id as product_child_id',
                "product_childs.is_sales",
                "product_childs.percent_sale"
            )
            ->where('product_branch_prices.branch_id', $branch)
            ->where('product_branch_prices.is_deleted', 0)
            ->where('product_branch_prices.is_actived', 1)
            ->where('product_childs.is_deleted', 0)
            ->where('product_childs.is_actived', 1)
            ->where('products.is_deleted', 0)
            ->where('products.is_actived', 1)
            ->get();
        return $ds;
    }

    public function getItemBranchLimit($branch, $categoryId, $search, $page)
    {
       
        $ds = $this
            ->leftJoin('product_childs', 'product_childs.product_child_id', '=', 'product_branch_prices.product_id')
            ->leftJoin('products', 'products.product_id', '=', 'product_childs.product_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'product_branch_prices.branch_id')
            ->leftJoin('product_inventory_serial', 'product_inventory_serial.product_code', '=', 'product_branch_prices.product_code')
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
                'product_childs.product_child_id as product_child_id',
                "product_childs.is_sales",
                "product_childs.percent_sale",
                "product_childs.is_surcharge",
                "product_childs.inventory_management"
            )
            ->where('product_branch_prices.branch_id', $branch)
            ->where('product_branch_prices.is_deleted', 0)
            ->where('product_branch_prices.is_actived', 1)
            ->where('product_childs.is_deleted', 0)
            ->where('product_childs.is_actived', 1)
            ->where('products.is_deleted', 0)
            ->where('products.is_actived', 1)
            ->groupBy('product_branch_prices.product_code')
            ->limit(LIMIT_ITEM);

        //Filter category
        if (isset($categoryId) && $categoryId != 'all') {
            $ds->where("products.product_category_id", $categoryId);
        }

        //Search
        if (isset($search) && $search != null) {
            $ds ->where(function ($query) use ($search) {
                $query->where('product_childs.product_child_name', 'like', '%' . $search . '%')
                    ->orWhere('product_childs.barcode', 'like', '%' . $search . '%')
                    ->orWhere('product_childs.product_child_sku', 'like', '%' . $search . '%')
                    ->orWhere('product_branch_prices.product_code', 'like', '%' . $search . '%')
                    ->orWhere('product_inventory_serial.serial', 'like', '%' . $search . '%');
            });
        }

        $page    = (int) ($page ?? 1);
        $display = (int) ($filters['perpage'] ?? 12);
        return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getItemBranchSearch($search, $branch)
    {
        $ds = $this
            ->leftJoin('product_childs', 'product_childs.product_child_id', '=', 'product_branch_prices.product_id')
            ->leftJoin('products', 'products.product_id', '=', 'product_childs.product_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'product_branch_prices.branch_id')
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
                'product_childs.product_child_id as product_child_id',
                'products.is_sales',
                'products.percent_sale')
            ->where('product_branch_prices.branch_id', $branch)
            ->where('product_childs.product_child_name', 'like', '%' . $search . '%')
            ->where('product_branch_prices.is_deleted', 0)
            ->where('product_branch_prices.is_actived', 1)
            ->where('product_childs.is_deleted', 0)
            ->where('product_childs.is_actived', 1)
            ->where('products.is_deleted', 0)
            ->where('products.is_actived', 1)
            ->get();
        return $ds;
    }

    public function getProductBanchPrice($id)
    {
        $ds = self::leftJoin('products', 'products.product_id', '=', 'product_branch_prices.product_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'product_branch_prices.branch_id')
            ->select('product_branch_prices.branch_id as branch_id',
                'product_branch_prices.product_branch_price_id as product_branch_price_id',
                'product_branch_prices.old_price as old_price',
                'product_branch_prices.new_price as new_price',
                'product_branch_prices.is_actived as is_actived',
                'product_branch_prices.created_at as created_at',
                'product_branch_prices.updated_at as updated_at',
                'product_branch_prices.created_by as created_by',
                'product_branch_prices.updated_by as updated_by',
                'branches.branch_name as branch_name',
                'product_branch_prices.product_id as product_id'
            )
            ->where('product_branch_prices.is_deleted', 0)
            ->where('branches.is_deleted', 0)
            ->where('product_branch_prices.product_id', $id)
            ->get();
        return $ds;
    }

    public function getProductBranchPriceByProductChild($idProduct)
    {
        $list = $this
            ->leftJoin('branches', 'branches.branch_id', '=', 'product_branch_prices.branch_id')
            ->leftJoin('product_childs', 'product_childs.product_child_id', '=', 'product_branch_prices.product_id')
            ->select(
                'product_branch_prices.branch_id as branchId',
                'branches.branch_name as branchName'
            )->where('product_childs.product_id', $idProduct)
            ->where('product_branch_prices.is_deleted', 0)
            ->where('branches.is_deleted', 0)
            ->get()->toArray();
        return $list;
    }

    public function getProductChildBranchPriceByParentId($idProduct)
    {
        $list = $this
            ->leftJoin('branches', 'branches.branch_id', '=', 'product_branch_prices.branch_id')
            ->leftJoin('product_childs', 'product_childs.product_child_id', '=', 'product_branch_prices.product_id')
            ->select(
                'product_branch_price_id',
                'product_branch_prices.product_id as productChildId',
                'product_branch_prices.branch_id as branchId',
                'branches.branch_name as branchName'
            )->where('product_childs.product_id', $idProduct)
            ->where('product_branch_prices.is_deleted', 0)
            ->where('branches.is_deleted', 0)
            ->get()->toArray();
        return $list;
    }

    public function checkProductChildIssetBranchPrice($branchId, $productCode)
    {
        $select = $this->where('branch_id', $branchId)->where('product_code', $productCode);
        return $select->first();
    }

    public function getProductBranchPriceByCode($branch, $code)
    {
        $ds = $this
            ->leftJoin('product_childs', 'product_childs.product_child_id', '=', 'product_branch_prices.product_id')
            ->leftJoin('products', 'products.product_id', '=', 'product_childs.product_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'product_branch_prices.branch_id')
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
            ->where('product_branch_prices.branch_id', $branch)
            ->where('product_childs.product_code', $code)
            ->where('product_branch_prices.is_deleted', 0)
            ->where('product_branch_prices.is_actived', 1)
            ->where('product_childs.is_deleted', 0)
            ->where('product_childs.is_actived', 1)
            ->where('products.is_deleted', 0)
            ->where('products.is_actived', 1)
            ->first();
        return $ds;
    }

    /**
     * Lấy ds product_branch_price by product_code
     *
     * @param $productCode
     * @return mixed
     */
    public function getProductPrice($productCode)
    {
        return $this
            ->select(
                "{$this->table}.product_code",
                "branches.site_id"
            )
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->where("{$this->table}.product_code", $productCode)
            ->where("{$this->table}.is_actived", 1)
            ->where("{$this->table}.is_deleted", 0)
            ->groupBy("branches.site_id")
            ->get();
    }

    public function getProductBranchPriceByPrice($branchId, $productCode, $price){
        return $this->select()
                    ->where("branch_id", $branchId)
                    ->where("product_code", $productCode)
                    ->where("new_price", $price)
                    ->first();
    }
}