<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/15/2018
 * Time: 1:33 PM
 */

namespace Modules\FNB\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryOutputDetailTable extends Model
{
    protected $table = 'inventory_output_details';
    protected $primaryKey = 'inventory_output_detail_id';

    protected $fillable = ['inventory_output_detail_id', 'inventory_output_id', 'product_code', 'unit_id', 'quantity', 'current_price', 'total', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    /**
     * Insert inventory output detail to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $inventoryInput = $this->create($data);
        return $inventoryInput->inventory_output_detail_id;
    }

    /*
     * get inventory output detail by inventory input id
     */
    public function getInventoryInputDetailByParentId($id)
    {
        $data = $this->leftJoin('product_childs', 'product_childs.product_code', '=', 'inventory_output_details.product_code')
            ->leftJoin('units', 'units.unit_id', '=', 'inventory_output_details.unit_id')
            ->select(
                'inventory_output_details.inventory_output_detail_id as inventory_output_detail_id',
                'inventory_output_details.product_code as code',
                'inventory_output_details.unit_id as unitId',
                'inventory_output_details.quantity as quantity',
                'inventory_output_details.current_price as currentPrice',
                'inventory_output_details.total as total',
                'product_childs.product_child_name as childName',
                'product_childs.price as price',
                'units.name as unitName',
                'product_childs.cost as cost',
                'product_childs.price as price',
                'product_childs.inventory_management',
                DB::raw("(SELECT COUNT(inventory_output_detail_serial.inventory_output_detail_id) FROM inventory_output_detail_serial WHERE inventory_output_detail_serial.inventory_output_detail_id = inventory_output_details.inventory_output_detail_id) as total_serial")
            )
            ->where('inventory_output_details.inventory_output_id', $id)->get();
        return $data;
    }

    public function getDataInventoryOutputEdit($parentId, $productCode, $warehouseId)
    {
        $data = $this->leftJoin('inventory_outputs', 'inventory_outputs.inventory_output_id', '=', 'inventory_output_details.inventory_output_id')
            ->leftJoin('product_inventorys', 'product_inventorys.warehouse_id', '=', 'inventory_outputs.warehouse_id')
            ->leftJoin('product_childs', 'product_childs.product_code', '=', 'inventory_output_details.product_code')
            ->select(
                'inventory_output_details.product_code as productCode',
                'product_childs.product_child_name as productName',
                'product_inventorys.quantity as productInventoryQuantity',
                'inventory_output_details.quantity as productInventoryQuantity'
            )
            ->where('inventory_output_details.inventory_output_id', $parentId)
            ->where('inventory_outputs.warehouse_id', $warehouseId)
            ->where('inventory_output_details.product_code', $productCode)->get();
        return $data;
    }

    /*
     * update inventory output detail.
     */
    public function editByOutIdAndProductCode(array $data, $inventoryOutputId, $productCode)
    {
        return $this->where('inventory_output_id', $inventoryOutputId)
            ->where('product_code', $productCode)->update($data);
    }

    /*
     * delete inventory output detail.
     */
    public function removeByParentIdAndProductCode($parentId, $productCode)
    {
        $del = $this->where('inventory_output_id', $parentId)
            ->where('product_code', $productCode)->delete();
        return $del;
    }

    /*
     * get history inventory output
     */
    public function getHistory($code)
    {
        $select = $this
            ->leftJoin('inventory_outputs', 'inventory_outputs.inventory_output_id', '=', 'inventory_output_details.inventory_output_id')
            ->leftJoin('warehouses', 'warehouses.warehouse_id', '=', 'inventory_outputs.warehouse_id')
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'inventory_outputs.created_by')
            ->select(
                'inventory_outputs.po_code as code',
                'warehouses.name as warehouse',
                'inventory_outputs.type as type',
                'inventory_output_details.quantity as quantity',
                'inventory_outputs.status as status',
                'staffs.full_name as user',
                'inventory_outputs.created_at as createdAt'
            )
            ->where('inventory_output_details.product_code', $code)
            ->where('inventory_outputs.status', 'success');
        if (Auth::user()->is_admin != 1) {
            $select->where('warehouses.branch_id', Auth::user()->branch_id);
        }
        return $select->get();
    }

    /**
     * Lấy những sản phẩm đã xuất kho theo id phiếu xuất & id kho
     *
     * @param $parentId
     * @param $warehouseId
     * @return mixed
     */
    public function getListDetailByParentId($parentId, $warehouseId)
    {
        $select = $this->select(
            "{$this->table}.inventory_output_detail_id",
            "{$this->table}.inventory_output_id",
            "{$this->table}.product_code",
            "{$this->table}.quantity"
        )
            ->where("{$this->table}.inventory_output_id", $parentId);
        return $select->get();
    }

    /**
     * Lấy thong tin chi tiết sản phẩm xuất kho
     * @param $inventory_input_detail_id
     * @return mixed
     */
    public function getDetail($inventory_output_detail_id){
        return $this
            ->select(
                $this->table.'.inventory_output_detail_id',
                $this->table.'.product_code',
                'product_childs.product_child_name'
            )
            ->leftJoin('product_childs','product_childs.product_code',$this->table.'.product_code')
            ->where($this->table.'.inventory_output_detail_id',$inventory_output_detail_id)
            ->first();
    }

    /**
     * Kiểm tra id inventory
     * @param $idInventoryInput
     * @param $productCode
     */
    public function checkInventoryOutput($idInventoryOutput,$productCode){
        return $this
            ->where('inventory_output_id',$idInventoryOutput)
            ->where('product_code',$productCode)
            ->first();
    }

    /*
     * edit inventory output detail.
     */
    public function editByInputIdAndProductCode(array $data, $inventoryOutputId, $productCode)
    {
        return $this->where('inventory_output_id', $inventoryOutputId)
            ->where('product_code', $productCode)->update($data);
    }

    /**
     * Xoá sản phẩm xuất kho
     * @param $inventory_output_detail_id'
     */
    public function deleteDetailInput($inventory_output_detail_id){
        return $this->where('inventory_output_detail_id',$inventory_output_detail_id)->delete();
    }

    /**
     * Lấy chi tiết
     * @param $inventory_output_detail_id
     * @param $product_code
     */
    public function getDetailOuput($inventory_output_id,$product_code){
        return $this->where('inventory_output_id',$inventory_output_id)->where('product_code',$product_code)->first();
    }

    /**
     * Xoá sản phẩm trong phiếu xuất
     * @param $inventory_output_id
     */
    public function removeProductByDetail($inventory_output_id){
        return $this
            ->where('inventory_output_id',$inventory_output_id)
            ->delete();
    }

    /**
     * Xoá serial theo kiểm kho
     */
    public function removeProductByChecking($productCode, $idChecking){
        return $this
            ->join('inventory_outputs','inventory_outputs.inventory_output_id',$this->table.'.inventory_output_id')
            ->where('inventory_outputs.inventory_checking_id',$idChecking)
            ->where($this->table.'.product_code',$productCode)
            ->where('inventory_outputs.status','<>','success')
            ->delete();
    }

    public function checkProductInventotyOutput($inventoryOutputId,$productCode){
        return $this
            ->where('inventory_output_id',$inventoryOutputId)
            ->where('product_code',$productCode)
            ->get();
    }

    /**
     * Cập nhật chi tiết
     */
    public function editDetail($inventoryOutputDetailId,$data){
        return $this
            ->where('inventory_output_detail_id',$inventoryOutputDetailId)
            ->update($data);
    }

    /**
     * Lấy danh sách sản phẩm xuất kho
     * @return void
     */
    public function getListProductInventoryOutput($inventory_checking_id){
        return $this
            ->select(
                'inventory_outputs.warehouse_id',
                $this->table.'.quantity',
                $this->table.'.product_code'
            )
            ->join('inventory_outputs','inventory_outputs.inventory_output_id',$this->table.'.inventory_output_id')
            ->where('inventory_outputs.inventory_checking_id',$inventory_checking_id)
            ->get();
    }

    /**
     * Xóa danh sách chi tiết xuất kho
     * @param $inventory_checking_id
     * @return mixed
     */
    public function removeDetailByCheckingId($inventory_checking_id){
        return $this
            ->join('inventory_outputs','inventory_outputs.inventory_output_id',$this->table.'.inventory_output_id')
            ->where('inventory_outputs.inventory_checking_id',$inventory_checking_id)
            ->delete();
    }

}
//