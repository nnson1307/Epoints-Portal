<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/16/2018
 * Time: 4:40 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryCheckingDetailTable extends Model
{
    protected $table = 'inventory_checking_details';
    protected $primaryKey = 'inventory_checking_detail_id';

    protected $fillable = ['inventory_checking_detail_id', 'inventory_checking_id', 'product_code', 'quantity_old', 'quantity_new', 'quantity_difference', 'current_price', 'total', 'type_resolve', 'updated_by', 'created_by', 'updated_at', 'created_at','note'];

    /**
     * Insert inventory input to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $inventoryInput = $this->create($data);
        return $inventoryInput->inventory_checking_detail_id;
    }

    /*
     * get detail inventory checking detail
     */
    public function getDetailInventoryCheckingDetail($parentId,$productCode = null)
    {
        $select = $this->leftJoin('product_childs', 'product_childs.product_code', '=', 'inventory_checking_details.product_code')
            ->leftJoin('units', 'units.unit_id', '=', 'product_childs.unit_id')
            ->leftJoin('inventory_checkings', 'inventory_checkings.inventory_checking_id', '=', 'inventory_checking_details.inventory_checking_id')
//            ->leftJoin('product_inventorys', 'product_inventorys.product_code', '=', 'inventory_checking_details.product_code')
//            ->leftJoin('product_inventorys', 'product_inventorys.warehouse_id', '=', 'inventory_checkings.warehouse_id')
//            ->leftJoin('product_inventorys', function ($join) {
//                $join->on('inventory_checking_details.product_code', '=', 'product_inventorys.product_code');
//                $join->on('inventory_checkings.warehouse_id', '=', 'product_inventorys.warehouse_id');
//            })
            ->select(
                'product_childs.inventory_management as inventory_management',
                'product_childs.product_child_name as productName',
                'inventory_checking_details.inventory_checking_detail_id as inventory_checking_detail_id',
                'inventory_checking_details.product_code',
                'inventory_checking_details.quantity_old as quantityOld',
                'inventory_checking_details.quantity_new as quantityNew',
                'inventory_checking_details.quantity_difference as quantityDifference',
                'inventory_checking_details.current_price as currentPrice',
                'inventory_checking_details.total as total',
                'inventory_checking_details.type_resolve as typeResolve',
                'units.name as unitName',
                'units.unit_id as unitId',
                'inventory_checking_details.product_code as productCode',
                'inventory_checking_details.note as note',
                'inventory_checkings.warehouse_id',
                DB::raw("(SELECT COUNT(*) FROM inventory_checking_detail_serial where inventory_checking_detail_serial.inventory_checking_detail_id = inventory_checking_details.inventory_checking_detail_id and inventory_checking_detail_serial.is_new = 1) as total_import"),
                DB::raw("(SELECT COUNT(*) FROM inventory_checking_detail_serial where inventory_checking_detail_serial.inventory_checking_detail_id = inventory_checking_details.inventory_checking_detail_id and inventory_checking_detail_serial.is_new = 0) as total_export")
            )
            ->where('inventory_checking_details.inventory_checking_id', $parentId);

        if($productCode != null){
            $select = $select->where('inventory_checking_details.product_code',$productCode);
        }

        return $select->get();
    }

    /*
     * edit by inventory checking id and product code
     */
    public function editByParentIdAndProductCode($parentId, $productCode, array $data)
    {
        return $this->where('inventory_checking_id', $parentId)
            ->where('product_code', $productCode)->update($data);
    }

    /*
     * Xóa khỏi db với điều kiện inventory_checking_id và product_code.
     */
    public function removeByParentIdAndProductCode($parentId, $productCode)
    {
        return $this->where('inventory_checking_id', $parentId)
            ->where('product_code', $productCode)->delete();
    }

    /**
     * lấy chi tiết
     * @param $inventory_checking_detail_id
     */
    public function getDetail($inventory_checking_detail_id){
        return $this
            ->select(
                $this->table.'.inventory_checking_detail_id',
                $this->table.'.inventory_checking_id',
                $this->table.'.product_code',
                'product_childs.product_child_name',
                'inventory_checkings.warehouse_id'
            )
            ->leftJoin('product_childs','product_childs.product_code',$this->table.'.product_code')
            ->join('inventory_checkings','inventory_checkings.inventory_checking_id',$this->table.'.inventory_checking_id')
            ->where($this->table.'.inventory_checking_detail_id',$inventory_checking_detail_id)
            ->first();
    }

    /**
     * Lấy chi tiết
     * @param $inventory_checking_detail_id
     * @param $product_code
     */
    public function getDetailChecking($inventory_checking_id,$product_code){
        return $this->where('inventory_checking_id',$inventory_checking_id)->where('product_code',$product_code)->first();
    }

    /**
     * Xoá sản phẩm
     */
    public function removeDetail($inventory_checking_detail_id){
        return $this->where('inventory_checking_detail_id',$inventory_checking_detail_id)->delete();
    }

    public function getListProductChecking($inventory_checking_id){
        return $this
            ->select(
                'product_childs.product_child_name',
                'product_childs.inventory_management',
                $this->table.'.quantity_old',
                $this->table.'.quantity_new'
            )
            ->join('product_childs','product_childs.product_code',$this->table.'.product_code')
            ->where('inventory_checking_id',$inventory_checking_id)
            ->get();
    }
}
//