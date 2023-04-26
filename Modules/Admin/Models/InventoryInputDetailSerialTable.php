<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryInputDetailSerialTable extends Model
{
    protected $table = 'inventory_input_detail_serial';
    protected $primaryKey = 'inventory_input_detail_serial_id';
    protected $fillable = [
        'inventory_input_detail_serial_id','inventory_input_detail_id','product_code',
        'serial' ,'barcode','created_at','updated_at'
    ];

    const IS_NOT_DELETED = 0;
    const IS_ACTIVE = 1;
    const IS_SALE = 1;
    const IS_NOT_EXPORT = 0;

    /**
     * Lưu cấu hình sản phẩm theo serial
     * @param $data
     */
    public function insertListSerial($data){
        return $this->insert($data);
    }

    /**
     * Kiểm tra số serial đã tồn tại theo sản phẩm
     * @param $product_code
     * @param $serial
     */
    public function checkSerial($product_code,$serial,$idInventoryInputDetail = null){
        $oSelect = $this
            ->where('product_code',$product_code)
            ->where('serial',$serial);

        if ($idInventoryInputDetail != null){
            $oSelect = $oSelect->where('inventory_input_detail_id',$idInventoryInputDetail);
        }

        return $oSelect->first();
    }

    /**
     * Xoá danh sác serial theo input detail
     * @param $inventory_input_detail_id
     */
    public function deleteSerialInput($inventory_input_detail_id){
        return $this->where('inventory_input_detail_id',$inventory_input_detail_id)->delete();
    }

    /**
     * Lấy danh sách serial theo detail
     */
    public function getListSerialByDetailLimit($arr_inventory_input_detail_id){
        return $this
            ->select(
                'inventory_input_detail_serial_id',
                'inventory_input_detail_id',
                'product_code',
                'serial',
                'barcode'
            )
            ->whereIn('inventory_input_detail_id',$arr_inventory_input_detail_id)
            ->orderBy('inventory_input_detail_serial_id','DESC')
            ->get();
    }

    /**
     * Lấy danh sách serial theo detail
     */
    public function getListSerialByDetail($arr_inventory_input_detail_id){
        return $this
            ->select(
                'inventory_input_detail_serial_id',
                'inventory_input_detail_id',
                'product_code',
                'serial',
                'barcode'
            )
            ->whereIn('inventory_input_detail_id',$arr_inventory_input_detail_id)
            ->orderBy('inventory_input_detail_serial_id','DESC')
            ->get();
    }

    /**
     * lấy danh sách serial có phân trang
     * @param $filter
     * @return mixed
     */
    public function getListSerialPaging($filter){
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? 100);

        $oSelect = $this
            ->select(
                'inventory_input_detail_serial_id',
                'inventory_input_detail_id',
                'product_code',
                'serial',
                'barcode'
            )
            ->where('inventory_input_detail_id',$filter['inventory_input_detail_id'])
            ->orderBy('inventory_input_detail_serial_id','DESC');

        if (isset($filter['serial'])){
            $oSelect = $oSelect->where('serial','like','%'.$filter['serial'].'%');
        }

        return $oSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Xoá serial theo id
     * @param $inventory_input_detail_serial_id
     */
    public function deleteSerialById($inventory_input_detail_serial_id){
        return $this->where('inventory_input_detail_serial_id',$inventory_input_detail_serial_id)->delete();
    }

    public function getProductChildInventoryOutput($warehouseId)
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
            ->leftJoin('product_inventorys', 'product_inventorys.product_code', '=', 'product_childs.product_code')
//            ->where('product_inventorys.warehouse_id', $warehouseId)
            ->where('products.is_deleted', self::IS_NOT_DELETED)
            ->where('product_childs.is_deleted', self::IS_NOT_DELETED)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("product_childs.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_export", self::IS_NOT_EXPORT)
            ->groupBy([$this->table.'.serial',$this->table.'.product_code'])
            ->get();
        return $select;
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
            ->leftJoin('product_inventorys', 'product_inventorys.product_code', '=', 'product_childs.product_code')
            ->where('products.is_deleted', self::IS_NOT_DELETED)
            ->where('product_childs.is_deleted', self::IS_NOT_DELETED)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("product_childs.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_export", self::IS_NOT_EXPORT);

        if (isset($filter['warehouse_id'])){
            $select = $select->where('product_inventorys.warehouse_id', $filter['warehouse_id']);
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
            ->leftJoin('product_inventorys', 'product_inventorys.product_code', '=', 'product_childs.product_code')
            ->where('products.is_deleted', self::IS_NOT_DELETED)
            ->where('product_childs.is_deleted', self::IS_NOT_DELETED)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("product_childs.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_export", self::IS_NOT_EXPORT);

        if (isset($filter['warehouse_id'])){
            $select = $select->where('product_inventorys.warehouse_id', $filter['warehouse_id']);
        }

        $select = $select->groupBy([$this->table.'.serial',$this->table.'.product_code']);

        return $select->get();
    }

    /**
     * Kiểm tra số serial có trong kho hay chưa
     */
    public function checkSerialWarehouse($productCode, $serial){
        return $this->where('product_code',$productCode)->where('serial',$serial)->where('is_export', self::IS_NOT_EXPORT)->first();
    }

    /**
     * @param $warehouseId
     * @param $serial
     */
    public function checkSerialWarehouseUse($warehouseId,$serial){
        return $this
            ->join('inventory_input_details','inventory_input_details.inventory_input_detail_id',$this->table.'.inventory_input_detail_id')
            ->join('inventory_inputs','inventory_inputs.inventory_input_id','inventory_input_details.inventory_input_id')
            ->where($this->table.'.serial',$serial)
            ->where('inventory_inputs.warehouse_id',$warehouseId)
            ->where($this->table.'.is_export',self::IS_NOT_EXPORT)
            ->first();
    }

    /**
     * @param $warehouseId
     * @param $serial
     */
    public function getListSerialWarehouse($warehouseId){
        return $this
            ->select(
                $this->table.'.inventory_input_detail_serial_id',
                $this->table.'.product_code',
                $this->table.'.serial',
                DB::raw("CONCAT({$this->table}.product_code,'-',{$this->table}.serial) as key_group")
            )
            ->join('inventory_input_details','inventory_input_details.inventory_input_detail_id',$this->table.'.inventory_input_detail_id')
            ->join('inventory_inputs','inventory_inputs.inventory_input_id','inventory_input_details.inventory_input_id')
            ->where('inventory_inputs.warehouse_id',$warehouseId)
            ->where($this->table.'.is_export',self::IS_NOT_EXPORT)
            ->where('inventory_inputs.status','success')
            ->get();
    }

    /**
     * Cập nhật xuất kho theo danh sách id
     * @param $arrIdDetailSerial
     */
    public function updateExport($arrSerial){
        return $this->whereIn('serial',$arrSerial)->update(['is_export' => 1]);
    }

    public function checkSerialInWarehouse($product_code,$serial,$warehouse_id){
        return $this
            ->join('inventory_input_details','inventory_input_details.inventory_input_detail_id',$this->table.'.inventory_input_detail_id')
            ->join('inventory_inputs','inventory_inputs.inventory_input_id','inventory_input_details.inventory_input_id')
            ->where('inventory_inputs.warehouse_id',$warehouse_id)
            ->where('inventory_inputs.status','success')
            ->where($this->table.'.product_code',$product_code)
            ->where($this->table.'.serial',$serial)
            ->where($this->table.'.is_export',self::IS_NOT_EXPORT)
            ->get();
    }

    /**
     * Kiểm tra serial
     */
    public function checkSerialChecking($warehouseId,$productCode,$itemSerial){
        return $this
            ->join('inventory_input_details','inventory_input_details.inventory_input_detail_id',$this->table.'.inventory_input_detail_id')
            ->join('inventory_inputs','inventory_inputs.inventory_input_id','inventory_input_details.inventory_input_id')
            ->where('inventory_inputs.warehouse_id',$warehouseId)
            ->where($this->table.'.product_code',$productCode)
            ->where($this->table.'.serial',$itemSerial)
            ->where($this->table.'.is_export',0)
            ->where('inventory_inputs.status','success')
            ->first();
    }

    /**
     * Kiểm tra serial
     */
    public function checkSerialCheckingImport($warehouseId,$productCode,$itemSerial){
        return $this
            ->join('inventory_input_details','inventory_input_details.inventory_input_detail_id',$this->table.'.inventory_input_detail_id')
            ->join('inventory_inputs','inventory_inputs.inventory_input_id','inventory_input_details.inventory_input_id')
            ->where('inventory_inputs.warehouse_id',$warehouseId)
            ->where($this->table.'.product_code',$productCode)
            ->where($this->table.'.serial',$itemSerial)
            ->where($this->table.'.is_export',0)
            ->where('inventory_inputs.status','success')
            ->first();
    }

    public function getTotalSerial($inventory_input_id,$productCode){
        return $this
            ->join('inventory_input_details','inventory_input_details.inventory_input_detail_id',$this->table.'.inventory_input_detail_id')
            ->join('inventory_inputs','inventory_inputs.inventory_input_id','inventory_input_details.inventory_input_id')
            ->where('inventory_inputs.inventory_input_id',$inventory_input_id)
            ->where($this->table.'.product_code',$productCode)
            ->count();
    }

    /**
     * Cập nhật nhập kho theo kiểm kho
     */
    public function updateByChecking($data, $idChecking){
        return $this
            ->join('inventory_input_details','inventory_input_details.inventory_input_detail_id',$this->table.'.inventory_input_detail_id')
            ->join('inventory_inputs','inventory_inputs.inventory_input_id','inventory_input_details.inventory_input_id')
            ->where('inventory_inputs.inventory_checking_id',$idChecking)
            ->update($data);
    }

    /**
     * Xoá serial theo kiểm kho
     */
    public function removeProductByChecking($productCode, $idChecking){
        return $this
            ->join('inventory_input_details','inventory_input_details.inventory_input_detail_id',$this->table.'.inventory_input_detail_id')
            ->join('inventory_inputs','inventory_inputs.inventory_input_id','inventory_input_details.inventory_input_id')
            ->where('inventory_inputs.inventory_checking_id',$idChecking)
            ->where($this->table.'.product_code',$productCode)
            ->where('inventory_inputs.status','<>','success')
            ->delete();
    }

    /**
     * Xoá serial theo kiểm kho
     */
    public function removeSerialByChecking($productCode, $idChecking,$serial){
        return $this
            ->join('inventory_input_details','inventory_input_details.inventory_input_detail_id',$this->table.'.inventory_input_detail_id')
            ->join('inventory_inputs','inventory_inputs.inventory_input_id','inventory_input_details.inventory_input_id')
            ->where('inventory_inputs.inventory_checking_id',$idChecking)
            ->where($this->table.'.product_code',$productCode)
            ->where($this->table.'.serial',$serial)
            ->where('inventory_inputs.status','<>','success')
            ->delete();
    }

    /**
     * Xoá số serial by code
     * @param $arrCode
     */
    public function removeSerialByCode($arrCode){
        return $this
            ->whereIn('product_code',$arrCode)
            ->where('is_export',0)
            ->delete();
    }

    /**
     * Lấy danh sách số serial theo input id
     */
    public function getListSerialByInputId($inputId){
        return $this
            ->select(
                $this->table.'.product_code',
                $this->table.'.serial'
            )
            ->join('inventory_input_details','inventory_input_details.inventory_input_detail_id',$this->table.'.inventory_input_detail_id')
            ->where('inventory_input_details.inventory_input_id',$inputId)
            ->get();
    }

    /**
     * Cập nhật danh sách serial sau khi thanh toán đơn hàng
     */
    public function updateSerialOrder($arrSerial,$data){
        return $this
            ->join('inventory_input_details','inventory_input_details.inventory_input_detail_id',$this->table.'.inventory_input_detail_id')
            ->join('inventory_inputs','inventory_inputs.inventory_input_id','inventory_input_details.inventory_input_id')
            ->whereIn($this->table.'.serial',$arrSerial)
            ->where('inventory_inputs.status','success')
            ->update($data);
    }

    /**
     * Lấy tổng số serial import
     */
    public function getTotalSerialImport($inventory_checking_id,$productCode = null){
        $oSelect =  $this
            ->join('inventory_input_details','inventory_input_details.inventory_input_detail_id',$this->table.'.inventory_input_detail_id')
            ->join('inventory_inputs','inventory_inputs.inventory_input_id','inventory_input_details.inventory_input_id')
            ->where('inventory_inputs.inventory_checking_id',$inventory_checking_id);

        if ($productCode != null){
            $oSelect = $oSelect->where($this->table.'.product_code',$productCode);
        }

        return $oSelect->count();
    }

    /**
     * Xóa danh sách serial theo id kiểm kho
     * @param $inventory_checking_id
     * @return mixed
     */
    public function deleteSerialByChecking($inventory_checking_id){
        return $this
            ->join('inventory_input_details','inventory_input_details.inventory_input_detail_id',$this->table.'.inventory_input_detail_id')
            ->join('inventory_inputs','inventory_inputs.inventory_input_id','inventory_input_details.inventory_input_id')
            ->where('inventory_inputs.inventory_checking_id',$inventory_checking_id)
            ->delete();
    }
}