<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 1:09 PM
 */

namespace Modules\Admin\Repositories\Supplier;


interface SupplierRepositoryInterface
{
    /**
     * Get supplier list
     *
     * @param array $filters
     */
    public function list(array $filters = []);
    /**
     * Delete supplier
     *
     * @param number $id
     */
    public function remove($id);
    /**
     * Add supplier
     * @param array $data
     * @return number
     */
    public function add(array $data);
    /**
     * Update supplier
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
    /**
     * get all supplier
     */
    public function getAll();
    /*
     * get option edit product
     */
    public function getOptionEditProduct($id);
    /*
    * check supplier
    */
    public function check($id,$name);
    /*
    * check exist
    */
    public function checkExist($name,$isDelete);
}