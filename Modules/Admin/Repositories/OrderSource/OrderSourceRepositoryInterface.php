<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 10:29 AM
 */

namespace Modules\Admin\Repositories\OrderSource;


interface OrderSourceRepositoryInterface
{
    /**
     * Get order source list
     *
     * @param array $filters
     */
    public function list(array $filters = []);
    /**
     * Delete order source
     *
     * @param number $id
     */
    public function remove($id);
    /**
     * Add order source
     * @param array $data
     * @return number
     */
    public function add(array $data);
    /**
     * Update order source
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
    * check oder source
    */
    public function check($name);
    /*
     * check oder source edit
     */
    public function checkEdit($id,$name);
    /*
 * test is deleted
 */
    public function testIsDeleted($name);

    /*
    * edit by department name
    */
    public function editByName($name);

    public function getOption();
}