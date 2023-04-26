<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/9/2018
 * Time: 4:05 PM
 */

namespace Modules\Admin\Repositories\ProductBranchPrice;


interface ProductBranchPriceRepositoryInterface
{
    public function getList(array $filters = []);

    /**
     * Get product branch price list
     *
     * @param array $filters
     */
    public function list(array $filters = [], $id, array $listId = []);

    /**
     * Delete product branch price
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add product branch price
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update product branch price
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
     * Get product branch price by product
     */
    public function getProductBranchPriceByProduct($idProduct);

    /*
     * Update product branch price by product id
     */
    public function updateProductBranchPriceByProductId($productId);

    /*
     * Get product code by product id
     */
    public function getProductCodeByProductId($productId);

    public function getAllProductBranchPriceByProductId($product);

    /*
   * test branch id by product id
   */
    public function testBanchId($productId, $branchId);

    /*
     * Delete branch price
     */
    public function deleteBranchPrice($productId, $branchId);

    /*
     * Test product code
     */
    public function testProductCode($code);

    /**
     * Lấy toàn bộ danh sách product_branch_prices
     * @param $productId
     * @return mixed
     */
    public function getProductBranchPrice($productId);

    public function getProductBranchPriceArrayProduct($arrProduct);

    /**
     * Lấy danh sách product branch price theo branch_id
     *
     * @param $id
     * @return mixed
     */
    public function getProductBranchPriceByBranchId($id);

    /**
     * Lưu và cập nhật cấu hình giá
     *
     * @param array $data
     * @param $branchId
     * @return mixed
     */
    public function editConfigPrice(array $data, $branchId);

    public function getItemBranch($branch);

    /**
     * Lấy giới hạn 16 sản phẩm
     * @param $branch
     * @param $categoryId
     * @param $search
     * @return mixed
     */
    public function getItemBranchLimit($branch, $categoryId, $search, $page);

    public function getItemBranchSearch($search, $branch);

    public function getProductBanchPrice($id);

    public function getProductBranchPriceByProductChild($idProduct);

    public function getProductChildBranchPriceByParentId($id);

    public function checkProductChildIssetBranchPrice($branchId, $productCode);

    public function getProductBranchPriceByCode($branch,$code);
}