<?php


namespace Modules\FNB\Repositories\InventoryOutputDetail;


interface InventoryOutputDetailRepositoryInterface
{
    /**
     * Add inventory output detail.
     * @param array $data
     * @return number
     */
    public function add(array $data);

    public function getListDetailByParentId($parentId, $warehouseId);
}