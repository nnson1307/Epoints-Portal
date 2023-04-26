<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/13/2018
 * Time: 2:09 PM
 */

namespace Modules\Admin\Repositories\InventoryInputDetail;


interface InventoryInputDetailRepositoryInterface
{
    /**
     * Add inventory input detail
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Kiểm tra id inventory
     * @param array $data
     * @return mixed
     */
    public function checkIdInventoryInput($idInventoryInput,$productCode);

    /*
    * get inventory input detail by inventory input id
    */
    public function getInventoryInputDetailByParentId($id);

    public function editByInputIdAndProductCode(array $data, $inventoryInputId, $productCode);

    /*
     * get history inventory input
     */
    public function getHistory($code);
    //Xóa với điều kiện id phiếu nhập và mã sản phẩm.
    public function removeByParentIdAndProductCode($parentId,$productCode);
}