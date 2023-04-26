<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/15/2018
 * Time: 4:14 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransferDetailTable extends Model
{
    protected $table = 'inventory_tranfer_details';
    protected $primaryKey = 'inventory_tranfer_detail_id';

    protected $fillable = ['inventory_tranfer_detail_id', 'inventory_tranfer_id', 'product_code', 'quantity', 'unit_id', 'current_price', 'quantity_tranfer', 'total', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    /**
     * Insert inventory transfer detail to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $inventoryInput = $this->create($data);
        return $inventoryInput->inventory_tranfer_detail_id;
    }

    /*
     * get inventory transfer by parent id and product code.
     */
    public function getInventoryTransfer($parentId)
    {
        $select = $this->leftJoin('product_childs', 'product_childs.product_code', '=', 'inventory_tranfer_details.product_code')
            ->leftJoin('units', 'units.unit_id', '=', 'inventory_tranfer_details.unit_id')
            ->select(
                'product_childs.product_child_name as productName',
                'inventory_tranfer_details.product_code as productCode',
                'inventory_tranfer_details.unit_id as unitId',
                'inventory_tranfer_details.quantity as quantity',
                'inventory_tranfer_details.current_price as currentPrice',
                'units.name as unitName',
                'inventory_tranfer_details.total as total'
            )->where('inventory_tranfer_details.inventory_tranfer_id', $parentId)->get();
        return $select;
    }
    public function getDataInventoryTransferEdit($parentId, $productCode, $warehouseId)
    {
        $data = $this->leftJoin('inventory_tranfers', 'inventory_tranfers.inventory_tranfer_id', '=', 'inventory_tranfer_details.inventory_tranfer_id')
            ->leftJoin('product_inventorys', 'product_inventorys.warehouse_id', '=', 'inventory_tranfers.warehouse_from')
            ->leftJoin('product_childs', 'product_childs.product_code', '=', 'inventory_tranfer_details.product_code')
            ->select(
                'inventory_tranfer_details.product_code as productCode',
                'product_childs.product_child_name as productName',
                'product_inventorys.quantity as productInventoryQuantity',
                'inventory_tranfer_details.quantity as transferQuantity',
                'inventory_tranfer_details.unit_id as unitId',
                'inventory_tranfer_details.current_price as currentPrice'
            )
            ->where('inventory_tranfer_details.inventory_tranfer_id', $parentId)
            ->where('inventory_tranfers.warehouse_to', $warehouseId)
            ->where('inventory_tranfer_details.product_code', $productCode)->first();
        return $data;
    }
    /*
    * update inventory transfer detail.
    */
    public function editByParentIdAndProductCode(array $data, $parentId, $productCode)
    {
        return $this->where('inventory_tranfer_id', $parentId)
            ->where('product_code', $productCode)->update($data);
    }

    //Xóa với điều kiện: inventory_tranfer_id và product_code
    public function removeByParentIdAndProductCode($parentId, $productCode)
    {
        return $this->where('inventory_tranfer_id', $parentId)
        ->where('product_code', $productCode)->delete();
    }
}
