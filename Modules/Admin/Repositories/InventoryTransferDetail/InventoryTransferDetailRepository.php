<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/15/2018
 * Time: 4:20 PM
 */

namespace Modules\Admin\Repositories\InventoryTransferDetail;

use Modules\Admin\Models\InventoryTransferDetailTable;

class InventoryTransferDetailRepository implements InventoryTransferDetailRepositoryInterface
{
    protected $inventoryTransferDetail;
    protected $timestamps = true;

    public function __construct(InventoryTransferDetailTable $inventoryTransferDetail)
    {
        $this->inventoryTransferDetail = $inventoryTransferDetail;
    }

    /**
     * add inventory input.
     */
    public function add(array $data)
    {
        return $this->inventoryTransferDetail->add($data);
    }

    /*
     * get inventory transfer by parent id.
     */
    public function getInventoryTransfer($parentId)
    {
        return $this->inventoryTransferDetail->getInventoryTransfer($parentId);
    }

    public function getDataInventoryTransferEdit($parentId, $productCode, $warehouseId)
    {
        return $this->inventoryTransferDetail->getDataInventoryTransferEdit($parentId, $productCode, $warehouseId);
    }

    /*
    * update inventory transfer detail.
    */
    public function editByParentIdAndProductCode(array $data, $parentId, $productCode)
    {
        return $this->inventoryTransferDetail->editByParentIdAndProductCode($data, $parentId, $productCode);
    }

    //Xóa với điều kiện: inventory_tranfer_id và product_code
    public function removeByParentIdAndProductCode($parentId, $productCode)
    {
        return $this->inventoryTransferDetail->removeByParentIdAndProductCode($parentId, $productCode);
    }
}