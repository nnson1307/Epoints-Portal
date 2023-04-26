<?php


namespace Modules\FNB\Repositories\ProductInventory;


interface ProductInventoryRepositoryInterface
{
    /*
     * check product inventory
     */
    public function checkProductInventory($productCode,$warehouseId);

    /*
     * edit product inventory in db
     */
    public function edit(array $data,$id);

    /**

     * Add product child
     * @param array $data
     * @return number
     */
    public function add(array $data);

    public function getQuantityByProdCodeAndWarehouseId($productCode, $warehouseId);

    public function editQuantityByCode(array $data, $productCode, $warehouseId);

}