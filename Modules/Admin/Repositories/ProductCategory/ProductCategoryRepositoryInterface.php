<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/29/2018
 * Time: 12:05 PM
 */

namespace Modules\Admin\Repositories\ProductCategory;


interface ProductCategoryRepositoryInterface
{
    /**
     * Get product category list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Delete product category
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Show modal thêm loại sản phẩm
     *
     * @return mixed
     */
    public function showModalAdd();

    /**
     * Add product category
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update product category
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
     * get product category
     */
    public function getAll();
    /**
     * test product category name
     */
    public function testProductCategoryName($id, $name);
    /*
     * test product category name
     */
    public function checkProductCategoryCode($id, $code);
    /*
     * get option edit product
     */
    public function getOptionEditProduct($id);
    /*
* test is deleted
*/
    public function testIsDeleted($name);

    /*
    * edit by department name
    */
    public function editByName($name);
}