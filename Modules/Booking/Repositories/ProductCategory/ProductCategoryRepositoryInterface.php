<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/29/2018
 * Time: 12:05 PM
 */

namespace Modules\Booking\Repositories\ProductCategory;


interface ProductCategoryRepositoryInterface
{
    /**
     * get product category
     */
    public function getAll();

    public function getListProduct(array $filter = []);

    public function getProductDetailGroup($id);
}