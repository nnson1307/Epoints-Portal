<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/15/2018
 * Time: 1:35 PM
 */

namespace Modules\Admin\Repositories\InventoryOutputDetail;


interface InventoryOutputDetailRepositoryInterface
{
    /**
     * Add inventory output detail.
     * @param array $data
     * @return number
     */
    public function add(array $data);
    /*
     * get inventory output detail by inventory input id
     */
    public function getInventoryInputDetailByParentId($id);
    public function getDataInventoryOutputEdit($parentId, $productCode, $warehouseId);
    /*
     * update inventory output detail.
     */
    public function editByOutIdAndProductCode(array $data, $inventoryOutputId, $productCode);
    /*
     * delete inventory output detail.
     */
    public function removeByParentIdAndProductCode($parentId, $productCode);
    /*
   * get history inventory output
   */
    public function getHistory($code);

    /**
     * Lấy danh sách sản phẩm đơn hàng đã xuât
     *
     * @param $parentId
     * @param $warehouseId
     * @return mixed
     */
    public function getListDetailByParentId($parentId, $warehouseId);
}