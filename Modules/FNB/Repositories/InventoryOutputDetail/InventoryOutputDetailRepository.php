<?php


namespace Modules\FNB\Repositories\InventoryOutputDetail;


use Modules\FNB\Models\InventoryOutputDetailTable;

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

    public function getListDetailByParentId($parentId, $warehouseId) {
        return $this->inventoryOutputDetail->getListDetailByParentId($parentId, $warehouseId);
    }
}