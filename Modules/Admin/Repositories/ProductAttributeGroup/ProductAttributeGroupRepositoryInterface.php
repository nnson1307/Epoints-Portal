<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/27/2018
 * Time: 3:54 PM
 */

namespace Modules\Admin\Repositories\ProductAttributeGroup;


interface ProductAttributeGroupRepositoryInterface
{
    /**
     * Get product attribute group list
     *
     * @param array $filters
     */
    public function list(array $filters = []);
    /**
     * Delete product attribute group
     *
     * @param number $id
     */
    public function remove($id);
    /**
     * Add product attribute group
     * @param array $data
     * @return number
     */
    public function add(array $data);
    /**
     * Update product attribute group
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
     * Get option product attribute group
     */
    public function getOption();
    /**
     * test product attribute group name
     */
    public function testProductAttGroupName($name, $id);
    public function getOptionAttributeGroup(array $productAttributeGroupId);
    /*
* test is deleted
*/
    public function testIsDeleted($name);

    /*
    * edit by department name
    */
    public function editByName($name);
}