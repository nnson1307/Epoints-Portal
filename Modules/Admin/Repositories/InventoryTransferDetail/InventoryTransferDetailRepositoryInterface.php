<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/15/2018
 * Time: 4:19 PM
 */

namespace Modules\Admin\Repositories\InventoryTransferDetail;


interface InventoryTransferDetailRepositoryInterface
{
    public function add(array $data);

    /*
     * get inventory transfer by parent id.
     */
    public function getInventoryTransfer($parentId);

    public function getDataInventoryTransferEdit($parentId, $productCode, $warehouseId);

    /*
    * update inventory transfer detail.
    */
    public function editByParentIdAndProductCode(array $data, $parentId, $productCode);

    //Xóa với điều kiện: inventory_tranfer_id và product_code
    public function removeByParentIdAndProductCode($parentId, $productCode);
}