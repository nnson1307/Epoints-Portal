<?php

namespace Modules\Admin\Repositories\ProductBranchPrice;

use Modules\Admin\Models\ProductBranchPriceTable;

class ProductBranchPriceRepository implements ProductBranchPriceRepositoryInterface
{
    /**
     * @var ProductBranchPriceTable
     */
    protected $productBranchPrice;
    protected $timestamps = true;

    public function __construct(ProductBranchPriceTable $productBranchPrice)
    {
        $this->productBranchPrice = $productBranchPrice;
    }

    public function getList(array $filters = [])
    {
        return $this->productBranchPrice->getList($filters);
    }

    /**
     *get list product branch price
     */
    public function list(array $filters = [], $id, array $listId = [])
    {
        return $this->productBranchPrice->getListBr($filters = [], $id, $listId);
    }

    /**
     * delete product branch price
     */
    public function remove($id)
    {
        $this->productBranchPrice->remove($id);
    }

    /**
     * add product branch price
     */
    public function add(array $data)
    {

        return $this->productBranchPrice->add($data);
    }

    /*
     * edit product branch price
     */
    public function edit(array $data, $id)
    {
        return $this->productBranchPrice->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->productBranchPrice->getItem($id);
    }

    /*
     * Get product branch price by product.
     */
    public function getProductBranchPriceByProduct($idProduct)
    {
        $productBranchPrice = $this->productBranchPrice->getProductBranchPriceByProduct($idProduct);
        $arrayProductBranchPrice = [];
        foreach ($productBranchPrice as $item) {
            $arrayProductBranchPrice[] = $item['branchId'];
        }
        return $arrayProductBranchPrice;
    }

    /*
     * Update product branch price by product id
     */
    public function updateProductBranchPriceByProductId($productId)
    {
        return $this->productBranchPrice->updateProductBranchPriceByProductId($productId);
    }

    /*
     * Get product code by product id
     */
    public function getProductCodeByProductId($productId)
    {
        return $this->productBranchPrice->getProductCodeByProductId($productId);
    }

    public function getAllProductBranchPriceByProductId($product)
    {
        return $this->productBranchPrice->getAllProductBranchPriceByProductId($product);
    }

    /*
   * test branch id by product id
   */
    public function testBanchId($productId, $branchId)
    {
        return $this->productBranchPrice->testBanchId($productId, $branchId);
    }

    public function deleteBranchPrice($productId, $branchId)
    {
        return $this->productBranchPrice->deleteBranchPrice($productId, $branchId);

    }

    /*
    * Test product code
    */
    public function testProductCode($code)
    {
        return $this->productBranchPrice->testProductCode($code);
    }

    /**
     * Lấy toàn bộ danh sách product branch price
     *
     * @param $productId
     * @return mixed
     */
    public function getProductBranchPrice($productId)
    {
        return $this->productBranchPrice->getProductBranchPrice($productId);
    }

    public function getProductBranchPriceArrayProduct($arrProduct)
    {
        return $this->productBranchPrice->getProductBranchPriceArrayProduct($arrProduct);
    }

    /**
     * Lấy danh sách product branch price theo branch_id
     *
     * @param $id
     * @return mixed
     */
    public function getProductBranchPriceByBranchId($id)
    {
        return $this->productBranchPrice->getProductBranchPriceByBranchId($id);
    }

    /**
     * Lưu và cập nhật cấu hình giá
     *
     * @param array $data
     * @param $branchId
     * @return mixed
     */
    public function editConfigPrice(array $data, $branchId)
    {
        if ($data[2] != 0) {
            return $this->productBranchPrice->editConfigPrice($data, $branchId);
        }
    }

    public function getItemBranch($branch)
    {
        // TODO: Implement getItemBranch() method.
        return $this->productBranchPrice->getItemBranch($branch);
    }

    public function getItemBranchLimit($branch, $categoryId, $search, $page)
    {
        // TODO: Implement getItemBranch() method.
        return $this->productBranchPrice->getItemBranchLimit($branch, $categoryId, $search, $page);
    }

    public function getItemBranchSearch($search, $branch)
    {
        // TODO: Implement getItemBranchSearch() method.
        return $this->productBranchPrice->getItemBranchSearch($search, $branch);
    }

    public function getProductBanchPrice($id)
    {
        return $this->productBranchPrice->getProductBanchPrice($id);
    }

    public function getProductBranchPriceByProductChild($idProduct)
    {
        $productBranchPrice = $this->productBranchPrice->getProductBranchPriceByProductChild($idProduct);
        $arrayProductBranchPrice = [];
        foreach ($productBranchPrice as $item) {
            $arrayProductBranchPrice[] = $item['branchId'];
        }
        return $arrayProductBranchPrice;
    }

    public function getProductChildBranchPriceByParentId($id)
    {
        return $this->productBranchPrice->getProductChildBranchPriceByParentId($id);
    }

    public function checkProductChildIssetBranchPrice($branchId, $productCode)
    {
        return $this->productBranchPrice->checkProductChildIssetBranchPrice($branchId, $productCode);
    }

    public function getProductBranchPriceByCode($branch, $code)
    {
        return $this->productBranchPrice->getProductBranchPriceByCode($branch, $code);
    }
}