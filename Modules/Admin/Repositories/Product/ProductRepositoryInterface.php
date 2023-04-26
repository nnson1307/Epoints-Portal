<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/2/2018
 * Time: 12:13 PM
 */

namespace Modules\Admin\Repositories\Product;


interface ProductRepositoryInterface
{
    /**
     * Get product list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Delete product
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add product
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update product
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
     * test code
     * @param $code, $id
     * @return $data
     */
    public function testCode($code, $id);
    /*
     * get option
     */
    public function getOption();
    /*
     * Get detail product
     */
    public function getDetailProduct($id);

    /**
     * @param $data
     * @return mixed
     */
    public function searchProduct($data);
    public function getListAdd();

    //Kiểm tra trùng tên sản phẩm.
    public function checkName($name, $id);
    public function checkNameEN($name, $id);

    //Kiểm tra trùng tên sản phẩm.
    public function checkSku($sku, $id);

    /**
     * Lấy toàn bộ danh sách sản phẩm
     *
     * @return mixed
     */
    public function getProduct();

    public function list2(array $filters = []);

    /**
     * Import excel file image
     *
     * @param $input
     * @return mixed
     */
    public function importFileImage($input);

    /**
     * Tắt hiển thị sp không có hình ảnh
     *
     * @return mixed
     */
    public function unDisplay();

    /**
     * Check số serial tồn kho
     * @return mixed
     */
    public function checkSerialEdit($data);

    /**
     * Check tồn kho
     * @param $data
     * @return mixed
     */
    public function checkBasicEdit($data);

    /**
     * Lấy danh sách code sản phẩm con
     * @param $data
     * @return mixed
     */
    public function getListProductCode($data);


    public function removeSerial($productId);

    /**
     * Hiển thị popup serial
     * @param $data
     * @return mixed
     */
    public function showPopupSerial($data);

    /**
     * Lấy danh sách serial
     * @param $data
     * @return mixed
     */
    public function searchSerial($data);

    /**
     * Lấy mã id sản phẩm gần nhất
     * @param $data
     * @return mixed
     */
    public function getProductTopId();

    public function searchProductChild($data);

    /**
     * Lấy danh sách sản phẩm con có phân trang
     * @param $data
     * @return mixed
     */
    public function getListProductChild(array $filters = []);

    public function importProduct(array $array);
}