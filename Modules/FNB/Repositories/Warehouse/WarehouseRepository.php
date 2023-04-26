<?php


namespace Modules\FNB\Repositories\Warehouse;


use Modules\FNB\Models\WarehouseTable;

class WarehouseRepository implements WarehouseRepositoryInterface
{
    protected $warehouse;
    protected $timestamps = true;

    public function __construct(WarehouseTable $warehouses)
    {
        $this->warehouse = $warehouses;
    }

    public function getWarehouseByBranch($branchId)
    {
        return $this->warehouse->getWarehouseByBranch($branchId);
    }
}