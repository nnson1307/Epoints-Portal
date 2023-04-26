<?php


namespace Modules\FNB\Repositories\InventoryOutput;


use Modules\FNB\Models\InventoryOutputDetailTable;
use Modules\FNB\Models\InventoryOutputTable;

class InventoryOutputRepository implements InventoryOutputRepositoryInterface
{
    protected $inventoryOutput;
    protected $inventoryOutputDetail;
    protected $timestamps = true;

    public function __construct(InventoryOutputTable $inventoryOutput, InventoryOutputDetailTable $inventoryOutputDetail)
    {
        $this->inventoryOutput = $inventoryOutput;
        $this->inventoryOutputDetail = $inventoryOutputDetail;
    }

    /**
     * add inventory output.
     */
    public function add(array $data)
    {
        return $this->inventoryOutput->add($data);
    }

    /*
     * edit inventory output
     */
    public function edit(array $data, $id)
    {
        return $this->inventoryOutput->edit($data, $id);
    }

    public function getInfoByOrderId($orderId, $type) {
        return $this->inventoryOutput->getInfoByOrderId($orderId, $type);
    }
}