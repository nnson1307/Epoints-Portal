<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/8/2018
 * Time: 12:33 AM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ProductInventoryTable extends Model
{
    use ListTableTrait;
    protected $table = 'product_inventorys';
    protected $primaryKey = 'product_inventory_id';
    public $timestamps = true;

    protected $fillable = ['product_inventory_id', 'product_id', 'product_code', 'warehouse_id', 'import', 'export', 'quantity', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    /**
     * Danh sách sản phẩm tồn kho
     *
     * @param array $filter
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $oSelect = $this
            ->join('product_childs', 'product_childs.product_id', '=', 'product_inventorys.product_id')
            ->join('warehouses', 'warehouses.warehouse_id', '=', 'product_inventorys.warehouse_id')
            ->select(
                'product_inventorys.product_inventory_id as productInventoryId',
                'product_childs.product_code as productCode',
                'product_childs.product_child_name as productChildName',
                'warehouses.name as warehouseName',
                'product_inventorys.quantity as quantity'
            )
            ->where('product_childs.is_deleted', 0)
            ->groupBy('product_inventorys.product_inventory_id');

        if (isset($filter['number_report_inventory'])) {
            $oSelect->where("{$this->table}.quantity", "<=", $filter['number_report_inventory']);

            unset($filter['number_report_inventory']);
        }

        if (isset($filter['search']) && $filter['search'] != null) {
            $search = $filter['search'];

            $oSelect->where('product_childs.product_child_name', 'like', '%' . $search . '%')
                ->orWhere('warehouses.name', 'like', '%' . $search . '%');
        }

        return $oSelect;
    }

    protected function getItem($id)
    {
        return $this->where('product_inventory_id', $id)->first();
    }

    public function getListProductInventory()
    {
        $oSelect = $this
            ->leftjoin('product_childs', 'product_childs.product_id', '=', 'product_inventorys.product_id')
            ->leftjoin('warehouses', 'warehouses.warehouse_id', '=', 'product_inventorys.warehouse_id')
            ->select(
                'product_inventorys.product_inventory_id as productInventoryId',
                'product_childs.product_code as productCode',
                'product_childs.product_child_name as productChildName',
                'warehouses.name as warehouseName',
                'product_inventorys.quantity as quantity'

            )
            ->where('product_childs.is_deleted', 0)
            ->where('products.is_deleted', 0)->groupBy('product_childs.product_code')->paginate(10);
        return $oSelect;
    }

    /**
     * Insert product inventory to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $oInsert = $this->create($data);

        return $oInsert->product_inventory_id;
    }

    /**
     * Insert product inventory to database
     *
     * @param array $data
     * @return number
     */
    public function createProductInventory(array $data)
    {
        return $this->create($data);
    }

    /**
     * Edit product inventory in database
     *
     * @param array $data , $id
     * @return number
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);

    }

    /**
     * Check product inventory in database
     *
     * @param $productCode , $warehouseId
     * @return array
     */
    public function checkProductInventory($productCode, $warehouseId)
    {
        return $this->where('product_code', $productCode)->where('warehouse_id', $warehouseId)->first();
    }

    /*
     * get product inventory by warehouse id and product id.
     */
    public function getProductByWarehouseAndProductId($warehouseId, $productId)
    {
        $select = $this->leftJoin('product_childs', 'product_childs.product_child_id', '=', 'product_inventorys.product_id')
            ->leftJoin('units', 'units.unit_id', '=', 'product_childs.unit_id')
            ->select(
                'product_inventorys.product_id as productId',
                'product_childs.product_child_name as name',
                'product_inventorys.product_code as code',
                'units.unit_id as unitId',
                'units.name as unitName',
                'product_inventorys.quantity as quantitys',
                'product_childs.cost as cost'
            )
            ->where('product_inventorys.warehouse_id', $warehouseId)
            ->where('product_inventorys.product_id', $productId)
            ->where('product_childs.is_deleted', 0)
            ->first();
        return $select;
    }

    /*
     * get product inventory by warehouse id and product child code.
     */
    public function getProductByWarehouseAndProductCode($warehouseId, $code)
    {
        $select = $this->leftJoin('product_childs', 'product_childs.product_child_id', '=', 'product_inventorys.product_id')
            ->leftJoin('units', 'units.unit_id', '=', 'product_childs.unit_id')
            ->select(
                'product_inventorys.product_id as productId',
                'product_childs.product_child_name as name',
                'product_inventorys.product_code as code',
                'units.unit_id as unitId',
                'units.name as unitName',
                'product_inventorys.quantity as quantitys',
                'product_childs.cost as cost'
            )
            ->where('product_inventorys.warehouse_id', $warehouseId)
            ->where('product_inventorys.product_code', $code)
            ->first();
        return $select;
    }

    /**
     * Tồn kho của product child
     * @param array $arrayProductChildId
     *
     * @return mixed
     */
    public function getProduct($arrayProductChildId = [])
    {
        $select = $this->select(
            'product_inventorys.warehouse_id as warehouse_id',
            'product_inventorys.product_code as product_code',
            'product_inventorys.quantity as quantity'
        )
            ->join('warehouses', 'warehouses.warehouse_id', '=', 'product_inventorys.warehouse_id')
            ->join('product_childs', 'product_childs.product_child_id',
                '=',
                'product_inventorys.product_id')
//            ->leftJoin('products', 'products.product_id', '=', 'product_childs.product_id')
            ->where('warehouses.is_deleted', 0);
//            ->where('products.is_deleted', 0)
//            ->where('product_childs.is_deleted', 0);
        if ($arrayProductChildId != []) {
            $select->whereIn('product_inventorys.product_id', $arrayProductChildId);
        }
        return $select->get();
    }

    public function getProductInventoryByWarehouse($productCode)
    {
        $oSelect = $this->leftJoin('warehouses', 'warehouses.warehouse_id', '=', 'product_inventorys.warehouse_id')
            ->select(
                'warehouses.name as warehouseName',
                'warehouses.warehouse_id as warehouseId',
                'product_inventorys.quantity as quantity'
            )
            ->where('product_inventorys.product_code', $productCode)->get();
        return $oSelect;
    }

    public function getProductInventory()
    {
        return $this->select('product_code', 'warehouse_id', 'quantity')->get();
    }

    public function getQuantityProductInventoryByCode($code)
    {
        return $this->select(DB::raw('SUM(quantity) as quantityInventory'))
            ->leftJoin('warehouses', 'warehouses.warehouse_id', '=', 'product_inventorys.warehouse_id')
            ->where('warehouses.is_deleted', 0)
            ->where('product_code', $code)->first();
    }

    public function getProductWhereIn(array $warehouse)
    {
        return $this->select('warehouse_id', 'product_code', 'quantity')
            ->whereIn('warehouse_id', $warehouse)->get();
    }

    //Tìm kiểm sản phẩm tồn kho.
    public function getProductInventoryByCodeOrName($warehouse, $name, $code)
    {
        $select = $this->leftJoin('product_childs', 'product_childs.product_code', '=', 'product_inventorys.product_code')
            ->select(
                'product_childs.product_child_id as product_child_id',
                'product_childs.product_child_name as product_child_name'
            )
            ->where(function ($query) use ($name, $code) {
                $query->where('product_inventorys.product_code', 'like', '%' . $code . '%')
                    ->orWhere('product_childs.product_child_name', 'like', '%' . $name . '%');
            })
            ->where('product_inventorys.warehouse_id', $warehouse)->get();
        return $select;
    }

    //Tìm kiểm sản phẩm tồn kho theo kho.
    public function getProductInventoryByWarehouseId($warehouse)
    {
        $select = $this->leftJoin('product_childs', 'product_childs.product_code', '=', 'product_inventorys.product_code')
            ->select(
                'product_childs.product_child_id as product_child_id',
                'product_childs.product_code as product_code',
                'product_childs.product_child_name as product_child_name'
            )
            ->where('is_deleted', 0)
            ->where('product_inventorys.warehouse_id', $warehouse)->get();
        return $select;
    }

    /**
     * Tồn kho của sản phẩm
     * @param array $arrayProductChildId
     *
     * @return mixed
     */
    public function productInventory($arrayProductChildId = [])
    {
        $select = $this
            ->select(
                'product_inventorys.product_inventory_id',
                'product_inventorys.product_id',
                'product_inventorys.warehouse_id',
                'product_inventorys.product_code',
//                DB::raw('SUM(quantity) as quantity'),
                "quantity"
            )
            ->join('warehouses', 'warehouses.warehouse_id', '=', 'product_inventorys.warehouse_id')
            ->join('product_childs', 'product_childs.product_child_id', '=', 'product_inventorys.product_id');

        if ($arrayProductChildId != []) {
            $select->whereIn('product_inventorys.product_id', $arrayProductChildId);
        }

//        $select->groupBy('product_inventorys.product_id');

        return $select->get();
    }

    /**
     * Edit san pham ton kho theo code
     *
     * @param array $data
     * @param $productCode
     * @param $warehouseId
     * @return mixed
     */
    public function editQuantityByCode(array $data, $productCode, $warehouseId)
    {
        return $this->where('product_inventorys.product_code', $productCode)
            ->where('product_inventorys.warehouse_id', $warehouseId)
            ->update($data);
    }

    /**
     * Lấy số lượng hiện tại trong kho
     *
     * @param $productCode
     * @param $warehouseId
     * @return mixed
     */
    public function getQuantityByProdCodeAndWarehouseId($productCode, $warehouseId)
    {
        return $this->where('product_inventorys.product_code', $productCode)
            ->where('product_inventorys.warehouse_id', $warehouseId)->first();
    }

    public function getListInventoryByCodeProduct($filter = []){
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                $this->table.'.quantity',
                $this->table.'.warehouse_id',
                $this->table.'.product_code',
                'product_childs.price',
                'product_childs.cost',
                'warehouses.name as warehouse_name',
                'product_childs.inventory_management'
            )
            ->join('product_childs','product_childs.product_code',$this->table.'.product_code')
            ->join('warehouses','warehouses.warehouse_id',$this->table.'.warehouse_id');

        if (isset($filter['product_code'])){
            $oSelect = $oSelect->where($this->table.'.product_code',$filter['product_code']);
        }

        if (isset($filter['warehouse_id'])){
            $oSelect = $oSelect->where($this->table.'.warehouse_id',$filter['warehouse_id']);
        }

        return $oSelect->orderBy($this->table.'.product_inventory_id','DESC')->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function checkTotalSerial($arrCode){
        return $this
            ->select(DB::raw("SUM(quantity) as quantity"))
            ->whereIn('product_code',$arrCode)
            ->first();
    }

    public function checkProductInventoryByWarehouse($warehouseId, $productId){
        return $this
            ->select()
            ->where('warehouse_id',$warehouseId)
            ->where('product_id',$productId)
            ->first();
    }
}