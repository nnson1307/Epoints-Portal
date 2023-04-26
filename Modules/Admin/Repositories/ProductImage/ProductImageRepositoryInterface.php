<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/3/2018
 * Time: 2:34 PM
 */

namespace Modules\Admin\Repositories\ProductImage;


interface ProductImageRepositoryInterface
{
    /**
     * Get product image list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Delete product image
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add product image
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update product image
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

    /*
    * get image link by product.
    */
    public function getImageByProductId($productId);

    /*
     * delete all image by product
     */
    public function deleteByProductId($productId);
    /*
     * delete image by product id and link image
     */
    public function deleteImageByProductIdAndLink($productId,$link);

    /**
     * Lấy ảnh đại diện sản phẩm
     *
     * @param $productCode
     * @return mixed
     */
    public function getAvatar($productCode);

    /**
     * Lấy ảnh đại diện của sản phẩm cha
     *
     * @param $productId
     * @return mixed
     */
    public function getAvatarOfProductMaster($productId);
}