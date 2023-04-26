<?php


namespace Modules\FNB\Repositories\ProductCategory;


use Modules\FNB\Models\ProductCategoryTable;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    private $productCategory;


    public function __construct(ProductCategoryTable $productCategory)
    {
        $this->productCategory = $productCategory;
    }

    public function getAll()
    {
        $productCategory = app()->get(ProductCategoryTable::class);
        return $productCategory->getAll();
    }
}