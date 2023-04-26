<?php


namespace Modules\FNB\Repositories\ProductInventory;


use Modules\FNB\Models\ProductInventoryTable;

class ProductInventoryRepository implements ProductInventoryRepositoryInterface
{
    /**
     * @var ProductInventoryTable
     */
    protected $productInventory;
    protected $timestamps = true;

    public function __construct(
        ProductInventoryTable $productInventory
    )
    {
        $this->productInventory = $productInventory;
    }

    public function checkProductInventory($productCode, $warehouseId)
    {
        return $this->productInventory->checkProductInventory($productCode, $warehouseId);
    }

    public function add(array $data)
    {
        return $this->productInventory->add($data);
    }

    public function edit(array $data, $id)
    {
        return $this->productInventory->edit($data, $id);
    }

    public function getQuantityByProdCodeAndWarehouseId($productCode, $warehouseId) {
        return $this->productInventory->getQuantityByProdCodeAndWarehouseId($productCode, $warehouseId);
    }

    public function editQuantityByCode(array $data, $productCode, $warehouseId) {
        return $this->productInventory->editQuantityByCode($data, $productCode, $warehouseId);
    }
}