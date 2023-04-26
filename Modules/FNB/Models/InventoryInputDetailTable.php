<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/13/2018
 * Time: 2:06 PM
 */

namespace Modules\FNB\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryInputDetailTable extends Model
{
    protected $table = 'inventory_input_details';
    protected $primaryKey = 'inventory_input_detail_id';

    protected $fillable = ['inventory_input_detail_id', 'inventory_input_id', 'product_code', 'unit_id', 'quantity', 'current_price', 'quantity_recived', 'total', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    /**
     * Insert inventory input to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $inventoryInputDetail = $this->create($data);
        return $inventoryInputDetail->inventory_input_detail_id;
    }

    public function editDetail($data,$inventory_input_detail_id,$product_code)
    {
        return $this->where('inventory_input_detail_id',$inventory_input_detail_id)->where('product_code',$product_code)->update($data);
    }

    /*
     * get inventory input detail by inventory input id
     */
    public function getInventoryInputDetailByParentId($id)
    {
        $data = $this->leftJoin('product_childs', 'product_childs.product_code', '=', 'inventory_input_details.product_code')
            ->leftJoin('units', 'units.unit_id', '=', 'inventory_input_details.unit_id')
            ->select(
                'inventory_input_details.inventory_input_detail_id',
                'inventory_input_details.product_code as code',
                'inventory_input_details.unit_id as unitId',
                'inventory_input_details.quantity as quantity',
                'inventory_input_details.current_price as currentPrice',
                'inventory_input_details.quantity_recived as quantityRecived',
                'inventory_input_details.total as total',
                'product_childs.product_child_name as childName',
                'product_childs.price as price',
                'units.name as unitName',
                'product_childs.inventory_management',
                DB::raw("(SELECT COUNT(inventory_input_detail_serial.inventory_input_detail_id) FROM inventory_input_detail_serial WHERE inventory_input_detail_serial.inventory_input_detail_id = inventory_input_details.inventory_input_detail_id) as total_serial")
            )
            ->where('inventory_input_details.inventory_input_id', $id)
            ->orderBy('inventory_input_details.inventory_input_detail_id','DESC')
            ->get();
        return $data;
    }

    /*
     * edit inventory output detail.
     */
    public function editByInputIdAndProductCode(array $data, $inventoryInputId, $productCode)
    {
        return $this->where('inventory_input_id', $inventoryInputId)
            ->where('product_code', $productCode)->update($data);
    }

    /*
     * get history inventory input
     */
    public function getHistory($code)
    {
        $select = $this
            ->leftJoin('inventory_inputs', 'inventory_inputs.inventory_input_id', '=', 'inventory_input_details.inventory_input_id')
            ->leftJoin('warehouses', 'warehouses.warehouse_id', '=', 'inventory_inputs.warehouse_id')
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'inventory_inputs.created_by')
            ->select(
                'inventory_inputs.pi_code as code',
                'inventory_inputs.warehouse_id as warehouses',
                'warehouses.name as warehouse',
                'inventory_inputs.type as type',
                'inventory_input_details.quantity as quantity',
                'inventory_inputs.status as status',
                'staffs.full_name as user',
                'inventory_inputs.created_at as createdAt'
            )
            ->where('inventory_input_details.product_code', $code)
            ->where('inventory_inputs.status', 'success');
        if (Auth::user()->is_admin != 1) {
            $select->where('warehouses.branch_id', Auth::user()->branch_id);
        }
        return $select->get();
    }

    //Xóa với điều kiện id phiếu nhập và mã sản phẩm.
    public function removeByParentIdAndProductCode($parentId, $productCode)
    {
        return $this->where('inventory_input_id', $parentId)
            ->where('product_code', $productCode)->delete();
    }

    /**
     * Xoá sản phẩm chi tiết nhập kho
     * @param $inventory_input_detail_id
     */
    public function deleteDetailInput($inventory_input_detail_id){
        return $this->where('inventory_input_detail_id',$inventory_input_detail_id)->delete();
    }

    /**
     * Lấy thong tin chi tiết sản phẩm nhập kho
     * @param $inventory_input_detail_id
     * @return mixed
     */
    public function getDetail($inventory_input_detail_id){
        return $this
            ->select(
                $this->table.'.inventory_input_detail_id',
                $this->table.'.product_code',
                $this->table.'.quantity',
                $this->table.'.current_price',
                $this->table.'.quantity_recived',
                $this->table.'.total',
                'product_childs.product_child_name'
            )
            ->leftJoin('product_childs','product_childs.product_code',$this->table.'.product_code')
            ->where($this->table.'.inventory_input_detail_id',$inventory_input_detail_id)
            ->first();
    }

    /**
     * Kiểm tra id inventory
     * @param $idInventoryInput
     * @param $productCode
     */
    public function checkInventoryInput($idInventoryInput,$productCode){
        return $this
            ->where('inventory_input_id',$idInventoryInput)
            ->where('product_code',$productCode)
            ->first();
    }

    /**
     * Xoá serial theo kiểm kho
     */
    public function removeProductByChecking($productCode, $idChecking){
        return $this
            ->join('inventory_inputs','inventory_inputs.inventory_input_id',$this->table.'.inventory_input_id')
            ->where('inventory_inputs.inventory_checking_id',$idChecking)
            ->where($this->table.'.product_code',$productCode)
            ->where('inventory_inputs.status','<>','success')
            ->delete();
    }

    /**
     * Lấy danh sách sản phẩm nhập kho
     * @return void
     */
    public function getListProductInventoryInput($inventory_checking_id){
        return $this
            ->select(
                'inventory_inputs.warehouse_id',
                $this->table.'.quantity_recived',
                $this->table.'.product_code',
                'product_childs.product_child_id'
            )
            ->join('inventory_inputs','inventory_inputs.inventory_input_id',$this->table.'.inventory_input_id')
            ->join('product_childs','product_childs.product_code',$this->table.'.product_code')
            ->where('inventory_inputs.inventory_checking_id',$inventory_checking_id)
            ->get();
    }

    /**
     * Lấy chi tiết nhập kho theo id kiểm kho
     * @return void
     */
    public function getDetailByCheckingId($inventory_checking_id,$product_code){
        return $this
            ->select(
                'inventory_inputs.warehouse_id',
                $this->table.'.inventory_input_detail_id',
                $this->table.'.current_price',
                $this->table.'.quantity_recived',
                $this->table.'.product_code',
                'product_childs.product_child_id'
            )
            ->join('inventory_inputs','inventory_inputs.inventory_input_id',$this->table.'.inventory_input_id')
            ->join('product_childs','product_childs.product_code',$this->table.'.product_code')
            ->where('inventory_inputs.inventory_checking_id',$inventory_checking_id)
            ->where($this->table.'.product_code',$product_code)
            ->first();
    }

    /**
     * Xoá chi tiết phiếu nhập kho
     */
    public function removeDetailByCheckingId($idChecking){
        return $this
            ->join('inventory_inputs','inventory_inputs.inventory_input_id',$this->table.'.inventory_input_id')
            ->where('inventory_inputs.inventory_checking_id',$idChecking)
            ->delete();
    }

    public function editDetailById($data,$inventoryInputDetailId){
        return $this
            ->where('inventory_input_detail_id',$inventoryInputDetailId)
            ->update($data);
    }
}