<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/15/2018
 * Time: 1:37 PM
 */

namespace Modules\Admin\Repositories\InventoryOutputDetail;

use Modules\Admin\Models\InventoryOutputDetailTable;

class InventoryOutputDetailRepository implements InventoryOutputDetailRepositoryInterface
{
    protected $inventoryOutputDetail;
    protected $timestamps = true;

    public function __construct(InventoryOutputDetailTable $inventoryOutputDetail)
    {
        $this->inventoryOutputDetail = $inventoryOutputDetail;
    }

    /**
     * add inventory input.
     */
    public function add(array $data)
    {
        return $this->inventoryOutputDetail->add($data);
    }

    /*
     * get inventory output detail by inventory input id
     */
    public function getInventoryInputDetailByParentId($id)
    {
        return $this->inventoryOutputDetail->getInventoryInputDetailByParentId($id);
    }

    public function getDataInventoryOutputEdit($parentId, $productCode, $warehouseId)
    {
        return $this->inventoryOutputDetail->getDataInventoryOutputEdit($parentId, $productCode, $warehouseId);
    }

    /*
     * update inventory output detail.
     */
    public function editByOutIdAndProductCode(array $data, $inventoryOutputId, $productCode)
    {
        return $this->inventoryOutputDetail->editByOutIdAndProductCode($data, $inventoryOutputId, $productCode);
    }

    /*
     * delete inventory output detail.
     */
    public function removeByParentIdAndProductCode($parentId, $productCode)
    {
        return $this->inventoryOutputDetail->removeByParentIdAndProductCode($parentId, $productCode);
    }

    /*
     * get history inventory output
     */
    public function getHistory($code)
    {
        return $this->inventoryOutputDetail->getHistory($code);
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
        return $this->inventoryOutputDetail->getListDetailByParentId($parentId, $warehouseId);
    }
}