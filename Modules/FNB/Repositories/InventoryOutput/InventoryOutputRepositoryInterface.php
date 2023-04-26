<?php


namespace Modules\FNB\Repositories\InventoryOutput;


interface InventoryOutputRepositoryInterface
{
    public function add(array $data);

    /**
     * Update product attribute group
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);

    public function getInfoByOrderId($orderId, $type);
}