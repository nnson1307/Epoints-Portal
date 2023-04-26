<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/5/2018
 * Time: 11:31 AM
 */

namespace Modules\Booking\Repositories\ProductChild;


interface ProductChildRepositoryInterface
{
    //Lấy danh sách sản phẩm.
    public function getProductChild(array $filter=[]);
    /*
     * get product child by id
     */
    public function getProductChildById($id);

}