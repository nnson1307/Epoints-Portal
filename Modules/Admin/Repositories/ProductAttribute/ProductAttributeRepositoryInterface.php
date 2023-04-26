<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/27/2018
 * Time: 5:52 PM
 */

namespace Modules\Admin\Repositories\ProductAttribute;


interface ProductAttributeRepositoryInterface
{
    /**
     * Get product attribute list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Delete product attribute
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add product attribute
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update product attribute
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
     * Test unique product_attribute_code
     * @param $code
     * @return $data
     */
    public function testCode($code, $id);

    /**
     * get product attribute by group
     * @param $code
     * @return $data
     */
    public function getProductAttributeByGroup($idGroup);

    /*
     * Get product attribute group
     */
    public function getProductAttributeGroup($attributeId);

    /*
     * Get product attribute where not in
     */
    public function getProductAttributeWhereNotIn(array $data);

    /*
    * Get option product attribute
    */
    public function getOption();

    // test unique product attribute label
    public function testLabel($label, $id);

    /*
     * check exist
     */
    public function checkExist($group, $label, $isDelete);

    // Kiểm tra thuộc tính sản phẩm theo id,  id nhóm và thuộc tính (is_deleted=0)
    public function testEdit($id, $groupId, $label);
}