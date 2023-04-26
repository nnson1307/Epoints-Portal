<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/13/2018
 * Time: 2:12 PM
 */

namespace Modules\Admin\Repositories\InventoryInputDetail;

use Modules\Admin\Models\InventoryInputDetailTable;

class InventoryInputDetailRepository implements InventoryInputDetailRepositoryInterface
{
    protected $inventoryInputDetail;
    protected $timestamps = true;

    public function __construct(InventoryInputDetailTable $inventoryInputDetail)
    {
        $this->inventoryInputDetail = $inventoryInputDetail;
    }

    /**
     * add inventory input.
     */
    public function add(array $data)
    {
        return $this->inventoryInputDetail->add($data);
    }

    /**
     * Kiểm tra id inventory
     * @param $idInventoryInput
     * @return mixed|void
     */
    public function checkIdInventoryInput($idInventoryInput,$productCode)
    {
        return $this->inventoryInputDetail->checkInventoryInput($idInventoryInput,$productCode);
    }

    /*
    * get inventory input detail by inventory input id
    */
    public function getInventoryInputDetailByParentId($id)
    {
        return $this->inventoryInputDetail->getInventoryInputDetailByParentId($id);
    }

    /*
     * edit inventory input detail
     */
    public function editByInputIdAndProductCode(array $data, $inventoryInputId, $productCode)
    {
        return $this->inventoryInputDetail->editByInputIdAndProductCode($data, $inventoryInputId, $productCode);
    }

    /*
     * get history inventory input
     */
    public function getHistory($code)
    {
        return $this->inventoryInputDetail->getHistory($code);
    }

    //Xóa với điều kiện id phiếu nhập và mã sản phẩm.
    public function removeByParentIdAndProductCode($parentId, $productCode)
    {
        return $this->inventoryInputDetail->removeByParentIdAndProductCode($parentId, $productCode);
    }
}