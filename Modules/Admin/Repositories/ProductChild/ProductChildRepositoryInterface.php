<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/5/2018
 * Time: 11:31 AM
 */

namespace Modules\Admin\Repositories\ProductChild;


interface ProductChildRepositoryInterface
{
    /**
     * Get product child list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Delete product child
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add product child
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update product child
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
     * test product code
     * @param array $data
     * @return $data
     */
    public function testProductCode($code);

    /**
     * get product child by product id
     */
    public function getProductChildByProductId($id);

    /*
    *search product child
    */
    public function searchProductChild($name);

    /*
     * get product child by id
     */
    public function getProductChildById($id);

    /*
     * get product child by code
     */
    public function getProductChildByCode($code);

    public function getProductChildByMatrix($code,$matrix = []);

    public function searchProductChildInventoryOutput($warehouseId, $name);

    /*
     * get product child by warehouse and code.
     */
    public function getProductChildByWarehouseAndCode($warehouseId, $code);

    /*
       * search product child by warehouse and code.
       */
    public function searchProductChildByWarehouseAndCode($warehouseId, $code);

    public function getProductChildByWarehouseAndProductCode($warehouseId, $code);

    public function getProductChildOption();

    public function getOptionChildSonService();

    //search product by keyword
    public function searchProduct($keyword);

    /**
     * @param $id
     * @return mixed
     */
    public function getListChildOrder($productName = null, $productCategory = null);

    public function getListChildOrderPaginate(array $filters = []);

    public function getListChildOrderSearch($search);

    public function removeByCode($code);

    public function removeByArrChildId($productId,$arrChildId);

    public function updateOrCreates(array $condition, array $data);

    public function updateByCode(array $data, $code);

    public function getProductChildOptionIdName();

    public function getListProductChild();

    public function getProductChildInventoryOutput($warehouseId);

    public function getListProductChildInventoryOutput($warehouseId);

    public function getProductChildByBranchesWarehouses($warehouseId);

    public function getProductChildByBranchesWarehousesList($warehouseId);

    public function checkProductChildName($name);

    public function checkSlug($slug);
    public function checkSlugEN($slug);

    /**
     * Danh sách product childs theo tab
     * @param array $filters
     *
     * @return mixed
     */
    public function listTab(array $filters = []);

    /**
     * Option product child để thêm với vào 3 tab: Mới, giảm giá, bán chạy.
     * @param array $filters
     * @param $listNotIn
     *
     * @return mixed
     */
    public function getOptionAddTab($listNotIn, array $filters = []);

    /**
     * Chọn sản phẩm
     * @param $id
     *
     * @return mixed
     */
    public function selectedProductChild($id);

    /**
     * Thêm cấu hình sản phẩm thương mại.
     * @param $params
     *
     * @return mixed
     */
    public function submitAddProductChild($params);

    /**
     * Remove product child.
     * @param $params
     *
     * @return mixed
     */
    public function removeList($params);

    /**
     * Danh sách option của product child load more theo trang
     * @param array $filter
     *
     * @return mixed
     */
    public function getProductChildOptionPage($filter = []);

    /**
     * Inventory output
     * Danh sách option của product child load more theo trang
     * @param array $filter
     *
     * @return mixed
     */
    public function getProductChildInventoryOutputOptionPage($filter = []);

    /**
     * Thêm điều kiện cấu hình sản phẩm gợi ý
     * @param $data
     * @return mixed
     */
    public function addConditionSuggest($data);

    /**
     * lấy danh sách điều kiện
     * @return mixed
     */
    public function getListCondition();

    /**
     * lấy danh sách tags sản phẩm
     * @return mixed
     */
    public function getListTags();

    /**
     * Insert cấu hình sản phẩm gợi ý
     * @param $data
     * @return mixed
     */
    public function insertConditionSuggest($data);

    /**
     * Lấy thông tin cấu hình sản phẩm gợi ý
     * @param $data
     * @return mixed
     */
    public function getListProductSuggestConfig();

    public function getProductChildTopId();

    //Kiểm tra trùng tên sản phẩm.
    public function checkSku($sku, $id);
}