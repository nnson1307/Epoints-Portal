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

class ProductInventorySerialTable extends Model
{
    use ListTableTrait;
    protected $table = 'product_inventory_serial';
    protected $primaryKey = 'product_inventory_serial_id';
    public $timestamps = true;

    protected $fillable = [
        'product_inventory_serial_id',
        'warehouse_id',
        'product_code',
        'serial',
        'barcode',
        'status',
        'created_at',
        'updated_at'
    ];

    const IS_NOT_DELETED = 0;
    const IS_ACTIVE = 1;
    const IS_SALE = 1;
    const IS_NOT_EXPORT = 0;

    /**
     * Kiểm tra số lượng serial tồn kho
     * @param $arrCode
     */
    public function checkTotalSerial($arrCode){
        return $this
            ->whereIn('product_code',$arrCode)
            ->where('status','new')
            ->count();
    }

    /**
     * Xoá số serial
     * @param $arrCodeProduct
     * @return mixed
     */
    public function removeSerial($arrCodeProduct){
        return $this
            ->whereIn('product_code',$arrCodeProduct)
            ->where('status','new')
            ->delete();
    }

    /**
     * Thêm số serial
     */
    public function insertListSerrial($data){
        return $this->insert($data);
    }

    /**
     * Cập nhật trạng thái số serial
     */
    public function updateSerial($data,$warehouse,$productCode,$serial){
        return $this
            ->where('warehouse_id',$warehouse)
            ->where('product_code',$productCode)
            ->where('serial',$serial)
            ->update($data);
    }

    /**
     * Lấy danh sách số serial theo mã sản phẩm có phân trang
     */
    public function getListSerialByCodeProduct($filter = []){

        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                $this->table.'.product_inventory_serial_id',
                $this->table.'.warehouse_id',
                $this->table.'.product_code',
                $this->table.'.serial',
                $this->table.'.barcode',
                $this->table.'.inventory_checking_status_id',
                'inventory_checking_status.name as inventory_checking_status_name',
                'warehouses.name as warehouses_name'
            )
            ->leftJoin('inventory_checking_status','inventory_checking_status.inventory_checking_status_id',$this->table.'.inventory_checking_status_id')
            ->join('warehouses','warehouses.warehouse_id',$this->table.'.warehouse_id')
            ->where($this->table.'.status','new');

        if (isset($filter['arr_warehouse_id'])){
            $oSelect = $oSelect->whereIn($this->table.'.warehouse_id',$filter['arr_warehouse_id']);
        }

        if (isset($filter['serial'])){
            $oSelect = $oSelect->where($this->table.'.serial','like','%'.$filter['serial'].'%');
        }

        if (isset($filter['warehouse_id'])){
            $oSelect = $oSelect->where($this->table.'.warehouse_id',$filter['warehouse_id']);
        }

        if (isset($filter['inventory_checking_status_id'])){
            $oSelect = $oSelect->where($this->table.'.inventory_checking_status_id',$filter['inventory_checking_status_id']);
        }

        if (isset($filter['product_code'])){
            $oSelect = $oSelect->where($this->table.'.product_code',$filter['product_code']);
        }

        return $oSelect->orderBy($this->table.'.product_inventory_serial_id','DESC')->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Kiểm tra số serial đã từng trong kho
     * @param $tmpSerial
     */
    public function checkSeialInventoryProduct($tmpSerial){
        return $this->whereIn('serial',$tmpSerial)->get();
    }

    /**
     * @param $warehouseId
     * @param $serial
     */
    public function getListSerialWarehouse($warehouseId){
        return $this
            ->select(
                $this->table.'.product_code',
                $this->table.'.serial',
                DB::raw("CONCAT({$this->table}.product_code,'-',{$this->table}.serial) as key_group")
            )
            ->where('warehouse_id',$warehouseId)
            ->where($this->table.'.status','new')
            ->get();
    }

    /**
     * CheckSerial
     */
    public function checkSerialOrder($productCode,$serial){
        return $this
            ->where('product_code',$productCode)
            ->where('serial',$serial)
            ->where('status','new')
            ->first();
    }

    /**
     * lấy danh sách serial theo sản phẩm và không nằm trong mảng serial được truyền vào có phân trang
     * @param $productCode
     * @param $arrSerial
     */
    public function getListSerialForOrder($filter=[],$arrSerial){
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select('serial')
            ->where('status','new')
            ->whereNotIn('serial',$arrSerial);

        if (isset($filter['productCode'])){
            $oSelect = $oSelect->where('product_code',$filter['productCode']);
        }

        return $oSelect->orderBy($this->table.'.product_inventory_serial_id','DESC')->paginate($display, $columns = ['*'], $pageName = 'page', $page);

    }

    /**
     * Cập nhật lại tình trạng theo serial
     * @param $arrSerial
     * @param $data
     */
    public function updateByArrSerial($arrSerial,$data){
        return $this
            ->whereIn('serial',$arrSerial)
            ->update($data);
    }

    /**
     * Kiểm tra serial
     */
    public function checkSerialChecking($warehouseId,$productCode,$itemSerial){
        return $this
            ->where('warehouse_id',$warehouseId)
            ->where($this->table.'.product_code',$productCode)
            ->where($this->table.'.serial',$itemSerial)
            ->where($this->table.'.status','new')
            ->first();
    }

    /**
     * lấy danh sách serial trong kho cần xuất
     * @param $warehouseId
     * @param $productCode
     * @param $arrSerial
     */
    public function getListSerialExport($filter=[]){

        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect =  $this
            ->select(
                $this->table.'.product_inventory_serial_id',
                $this->table.'.warehouse_id',
                $this->table.'.product_code',
                $this->table.'.serial',
                $this->table.'.barcode',
                $this->table.'.inventory_checking_status_id',
                'inventory_checking_status.name as inventory_checking_status_name'
            )
            ->leftJoin('inventory_checking_status','inventory_checking_status.inventory_checking_status_id',$this->table.'.inventory_checking_status_id')
            ->where($this->table.'.warehouse_id',$filter['warehouse_id'])
            ->where($this->table.'.product_code',$filter['product_code'])
            ->whereNotIn($this->table.'.serial',$filter['arrSerial'])
            ->where($this->table.'.status','new');

        if (isset($filter['serial'])){
            $oSelect = $oSelect->where($this->table.'.serial','like','%'.$filter['serial'].'%');
        }

        if (isset($filter['checking_status'])){
            $oSelect = $oSelect->where($this->table.'.inventory_checking_status_id',$filter['checking_status']);
        }

        return $oSelect->orderBy($this->table.'.product_inventory_serial_id','DESC')->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * lấy danh sách serial trong kho cần xuất để insert
     * @param $warehouseId
     * @param $productCode
     * @param $arrSerial
     */
    public function getListSerialExportInsert($filter=[]){

        $oSelect =  $this
            ->select(
                $this->table.'.product_inventory_serial_id',
                $this->table.'.warehouse_id',
                $this->table.'.product_code',
                $this->table.'.serial',
                $this->table.'.barcode',
                $this->table.'.inventory_checking_status_id',
                'inventory_checking_status.name as inventory_checking_status_name'
            )
            ->leftJoin('inventory_checking_status','inventory_checking_status.inventory_checking_status_id',$this->table.'.inventory_checking_status_id')
            ->where($this->table.'.warehouse_id',$filter['warehouse_id'])
            ->where($this->table.'.product_code',$filter['product_code'])
            ->whereNotIn($this->table.'.serial',$filter['arrSerial'])
            ->where($this->table.'.status','new');

        return $oSelect->get();
    }

//    Lấy danh sách serial theo sản phẩm và kho
    public function getListSerialByProductWarehouse($warehouseId,$productCode,$arrSerial = []){
        $oSelect = $this
            ->where($this->table.'.warehouse_id',$warehouseId)
            ->where($this->table.'.product_code',$productCode)
            ->where('status','new');

        if (count($arrSerial) != 0){
            $oSelect = $oSelect->whereNotIn('serial',$arrSerial);
        }

        return $oSelect->get();
    }

    /**
     * Danh sách số serial theo sản phẩm có phân trang
     * @param $warehouseId
     * @return mixed
     */
    public function getProductChildInventoryOutputPage($filter)
    {
        $select = $this
            ->join('product_childs', 'product_childs.product_code', '=', $this->table.'.product_code')
            ->leftJoin('products', 'products.product_id', '=', 'product_childs.product_id')
            ->select(
                'product_childs.product_child_id as product_child_id',
                'product_childs.product_child_name as product_child_name',
                'product_childs.product_code as product_code',
                $this->table.'.serial as serial'
            )
            ->where('products.is_deleted', self::IS_NOT_DELETED)
            ->where('product_childs.is_deleted', self::IS_NOT_DELETED)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("product_childs.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.status", 'new');

        if (isset($filter['warehouse_id'])){
            $select = $select->where("{$this->table}.warehouse_id", $filter['warehouse_id']);
        }

        if (isset($filter['keyword'])){
            $select = $select
                ->where("{$this->table}.serial", 'like','%'.$filter['keyword'].'%')
                ->orWhere("product_childs.product_child_name", 'like','%'.$filter['keyword'].'%');
        }

        $select = $select->groupBy([$this->table.'.serial',$this->table.'.product_code']);

        return $select->paginate($filter['perpage'], $columns = ['*'], $pageName = 'page', $filter['page']);
    }

    /**
     * Danh sách số serial theo sản phẩm không có phân trang
     * @param $warehouseId
     * @return mixed
     */
    public function getProductChildInventoryOutputNotPage($filter)
    {
        $select = $this
            ->join('product_childs', 'product_childs.product_code', '=', $this->table.'.product_code')
            ->leftJoin('products', 'products.product_id', '=', 'product_childs.product_id')
            ->select(
                'product_childs.product_child_id as product_child_id',
                'product_childs.product_child_name as product_child_name',
                'product_childs.product_code as product_code',
                $this->table.'.serial as serial'
            )
            ->where('products.is_deleted', self::IS_NOT_DELETED)
            ->where('product_childs.is_deleted', self::IS_NOT_DELETED)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("product_childs.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.status", 'new');

        if (isset($filter['warehouse_id'])){
            $select = $select->where($this->table.'.warehouse_id', $filter['warehouse_id']);
        }

        $select = $select->groupBy([$this->table.'.serial',$this->table.'.product_code']);

        return $select->get();
    }

    public function checkSerialAdd($warehouseId,$productCode,$serial){
        $oSelect = $this
            ->where($this->table.'.warehouse_id',$warehouseId)
            ->where($this->table.'.product_code',$productCode)
            ->where($this->table.'.serial',$serial)
            ->where('status','new');

        return $oSelect->first();
    }
}