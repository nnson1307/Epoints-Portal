<?php


namespace Modules\FNB\Repositories\Warehouse;


interface WarehouseRepositoryInterface
{
    public function getWarehouseByBranch($branchId);
}