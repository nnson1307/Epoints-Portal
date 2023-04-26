<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/28/2018
 * Time: 4:57 PM
 */

namespace Modules\Admin\Repositories\ProductModel;


interface ProductModelRepositoryInterface
{
    /**
     * Get product model list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Delete product model
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add product model
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update product model
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
     * get all product model
     */
    public function getAll();

    /*
    * get option edit product
    */
    public function getOptionEditProduct($id);

    //Kiểm tra tồn tại của nhãn sp.
    public function check($name, $isDelete);

    /*
   * Cập nhật với tên nhãn
   */
    public function editByName($name);

    /*
   * check unique.
   */
    public function checkEdit($id, $name);
}