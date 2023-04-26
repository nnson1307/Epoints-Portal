<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/16/2018
 * Time: 4:46 PM
 */

namespace Modules\Admin\Repositories\InventoryCheckingDetail;


interface InventoryCheckingDetailRepositoryInterface
{
    /**
     * Add  inventory checking
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /*
     * get detail inventory checking detail
     */
    public function getDetailInventoryCheckingDetailView($parentId);

    public function getDetailInventoryCheckingDetail($parentId);

    /*
     * get detail inventory checking detail update
     */
    public function getDetailInventoryCheckingDetailUpdate($parentId);

    /*
     * edit by inventory checking id and product code
     */
    public function editByParentIdAndProductCode($parentId, $productCode, array $data);

    /*
   * Xóa khỏi db với điều kiện inventory_checking_id và product_code.
   */
    public function removeByParentIdAndProductCode($parentId, $productCode);
}