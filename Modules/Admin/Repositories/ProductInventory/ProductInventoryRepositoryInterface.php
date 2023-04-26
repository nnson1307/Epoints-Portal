<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/8/2018
 * Time: 10:59 AM
 */

namespace Modules\Admin\Repositories\ProductInventory;


interface ProductInventoryRepositoryInterface
{
    /**
     * Get product image list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**

     * Add product child
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**

     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);
    /*
     * get list product inventory
     */
    public function getListProductInventory();
    /*
     * edit product inventory in db
     */
    public function edit(array $data,$id);
    /*
     * check product inventory
     */
    public function checkProductInventory($productCode,$warehouseId);
    /*
     * get product inventory by warehouse id and product id.
     */
    public function getProductByWarehouseAndProductId($warehouseId, $productId);
    /*
     * get product inventory by warehouse id and product child code.
     */
    public function getProductByWarehouseAndProductCode($warehouseId, $code);
    public function getProduct();
    public function getProductInventoryByWarehouse($productCode);
    public function getProductInventory();
    public function getQuantityProductInventoryByCode($code);
    public function getProductWhereIn(array $warehouse);

    //Tìm kiểm sản phẩm tồn kho.
    public function getProductInventoryByCodeOrName($warehouse, $name, $code);

    //Tìm kiểm sản phẩm tồn kho theo kho.
    public function getProductInventoryByWarehouseId($warehouse);

    /**
     * TÌm kiếm sản phẩm tồn kho theo kho
     * @param $warehouse
     * @return mixed
     */
    public function getProductInventoryByWarehouseIdList($warehouse);

    /**
     * Danh sách sản phẩm tồn kho
     * @param $params
     *
     * @return mixed
     */
    public function listProductInventory($params);

    /**
     * Edit san pham ton kho theo code
     *
     * @param array $data
     * @param $productCode
     * @param $warehouseId
     * @return mixed
     */
    public function editQuantityByCode(array $data, $productCode, $warehouseId);

    /**
     * Lấy số lượng hiện tại trong kho
     *
     * @param $productCode
     * @param $warehouseId
     * @return mixed
     */
    public function getQuantityByProdCodeAndWarehouseId($productCode, $warehouseId);

    /**
     * Lấy data cho trang cấu hình
     *
     * @return mixed
     */
    public function getDataConfig();

    /**
     * submit data config
     *
     * @param $input
     * @return mixed
     */
    public function saveInventoryConfig($input);

    /**
     * Danh sách tồn kho dưới định mức
     *
     * @param array $filter
     * @return mixed
     */
    public function listBelowNorm($filter = []);
}