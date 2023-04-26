<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/29/2018
 * Time: 12:07 PM
 */

namespace Modules\Admin\Repositories\ProductCategory;

use Modules\Admin\Models\ProductCategoryTable;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    /**
     * @var ProductCategoryTable
     */
    protected $productCategory;
    protected $timestamps = true;

    public function __construct(ProductCategoryTable $productCategory)
    {
        $this->productCategory = $productCategory;
    }

    /**
     *get list product category
     */
    public function list(array $filters = [])
    {
        return $this->productCategory->getList($filters);
    }

    /**
     * delete product category
     */
    public function remove($id)
    {
        $this->productCategory->remove($id);
    }

    /**
     * Show modal thêm loại sản phẩm
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function showModalAdd()
    {
        $html = \View::make('admin::product-category.add')->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * add product category
     */
    public function add(array $data)
    {

        return $this->productCategory->add($data);
    }

    /*
     * edit product category
     */
    public function edit(array $data, $id)
    {
        return $this->productCategory->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->productCategory->getItem($id);
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
    /*
     * test product category name
     */
    public function testProductCategoryName($id, $name)
    {
        return $this->productCategory->testProductCategoryName($id, $name);
    }
    /*
     * test product category name
     */
    public function checkProductCategoryCode($id, $code)
    {

        return $this->productCategory->checkProductCategoryCode($id, $code);
    }
    /*
     * get option edit product
     */
    public function getOptionEditProduct($id)
    {
        return $this->productCategory->getOptionEditProduct($id);
    }
    /*
     * test is deleted
     */
    public function testIsDeleted($name)
    {
        return $this->productCategory->testIsDeleted($name);
    }

    /*
     * edit by name
     */
    public function editByName($name)
    {
        return $this->productCategory->editByName($name);
    }
}