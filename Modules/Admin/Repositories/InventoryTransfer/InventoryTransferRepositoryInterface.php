<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/15/2018
 * Time: 4:16 PM
 */

namespace Modules\Admin\Repositories\InventoryTransfer;


interface InventoryTransferRepositoryInterface
{
    public function add(array $data);
    /**
     * Get product list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Delete inventory transfer
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Update inventory transfer
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);

    /**
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);
    /*
     * detail inventory transfer
     */
    public function detail($id);
    /*
    * get inventory transfer edit
    */
    public function getInventoryTransferEdit($id);
    public function list2($filters);
}