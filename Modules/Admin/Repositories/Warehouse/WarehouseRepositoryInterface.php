<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/25/2018
 * Time: 10:16 AM
 */

namespace Modules\Admin\Repositories\Warehouse;


interface WarehouseRepositoryInterface
{

    public function list(array $filters = []);

    /**
     * Add Warehouse
     *
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Delete Warehouse
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Edit Warehouse
     *
     * @param array $data ,$id
     * @return number
     */
    public function edit(array $data, $id);

    public function getItem($id);

    public function getWareHouseOption();

    public function testName($name, $id);

    /*
     * get warehouse not id parameter
     */
    public function getWarehouseNotId($id);

    //search where in warehouse.
    public function searchWhereIn(array $warehouse);

    public function checkIsRetail($branchId, $id);

    public function getWarehouseByBranch($branchId);

    public function changeIsRetailAction($branchId);

    //Kiểm tra kho đầu tiên của chi nhánh( để đặt kho đầu tiên là kho bán lẻ).
    public function checkIsFirstWarehouse($branchId);

    /**
     * Lấy thông tin kho bán lẻ theo branch id
     *
     * @param $branchId
     * @return mixed
     */
    public function getWarehouseRetailByBranchId($branchId);

    /**
     * Tạo cửa hàng ở giao hàng nhanh
     * @return mixed
     */
    public function createStoreGHN();
}