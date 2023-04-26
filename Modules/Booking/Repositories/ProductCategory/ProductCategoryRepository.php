<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/29/2018
 * Time: 12:07 PM
 */

namespace Modules\Booking\Repositories\ProductCategory;

use Modules\Booking\Models\ProductCategoryTable;
use Modules\Booking\Models\ProductTable;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    /**
     * @var ProductCategoryTable
     */
    protected $productCategory;
    protected $product;
    protected $timestamps = true;

    public function __construct(ProductCategoryTable $productCategory,ProductTable $product)
    {
        $this->productCategory = $productCategory;
        $this->product = $product;
    }

    /*
     * get product category
     */
    public function getAll()
    {
        $array = [];
        $data = $this->productCategory->getAll();
        foreach ($data as $item) {
            $array[$item['product_category_id']] = $item['category_name'];
        }
        return $array;
    }

    public function getListProduct(array $filter = [])
    {
        return $this->product->getProductList($filter);
    }

    public function getProductDetailGroup($id)
    {
        // TODO: Implement getProductDetailGroup() method.
        return $this->product->getProductDetailGroup($id);
    }
}