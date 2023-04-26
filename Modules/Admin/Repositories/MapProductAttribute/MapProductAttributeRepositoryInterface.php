<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/12/2018
 * Time: 3:28 PM
 */

namespace Modules\Admin\Repositories\MapProductAttribute;


interface MapProductAttributeRepositoryInterface
{
    public function list(array $filterts = []);

    /**
     * Add map product attribute
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Edit map product attribute
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id);

    /**
     * Remove map product attribute
     * @param $id
     * @return number
     */
    public function remove($id);

    /**
     * Get item
     * @param $id
     * @return mixed
     */
    public function getItem($id);

    /*
     * Get map product attribute group by product id
     */
    public function getMapProductAttributeGroupByProductId($idProduct);
    /*
     * Get product attribute by product id
     */
    public function getProductAttributeByProductId($idProduct);
    /*
     * test map product attribute
     */
    public function testMapProductAttributeIsset($idProduct,$attribute);
    /*
   * get all product attribute by product id
   */
    public function getAllAttrByProductId($product);
    public function deleteMapProductAttrByAttrId($idProduct,$attribute);
    /*
     * delete all by product id.
     */
    public function deleleAllByProductId($idProduct);

    /**
     * Thêm mảng dữ liệu
     * @param $data
     * @return mixed
     */
    public function insertArr($data);

    /**
     * Lấy danh sách attribute theo productId
     * @param $productId
     * @return mixed
     */
    public function getListMapProductAttribute($productId);
}