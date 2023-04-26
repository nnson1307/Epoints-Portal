<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/3/2018
 * Time: 2:37 PM
 */

namespace Modules\Admin\Repositories\ProductImage;

use Modules\Admin\Models\ProductImageTable;

class ProductImageRepository implements ProductImageRepositoryInterface
{
    /**
     * @var ProductImageTable
     */
    protected $productImage;
    protected $timestamps = true;

    public function __construct(ProductImageTable $productImage)
    {
        $this->productImage = $productImage;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->productImage->getList($filters);
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->productImage->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->productImage->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->productImage->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->productImage->getItem($id);
    }

    /*
        * get image link by product.
        */
    public function getImageByProductId($productId)
    {
        return $this->productImage->getImageByProductId($productId);
    }

    /*
     * delete all image by product
     */
    public function deleteByProductId($productId)
    {
        return $this->productImage->deleteByProductId($productId);
    }

    /*
     * delete image by product id and link image
     */
    public function deleteImageByProductIdAndLink($productId, $link)
    {
        return $this->productImage->deleteImageByProductIdAndLink($productId, $link);
    }

    /**
     * Lấy ảnh đại diện sản phẩm
     *
     * @param $productCode
     * @return mixed
     */
    public function getAvatar($productCode)
    {
        return $this->productImage->getAvatar($productCode);
    }

    /**
     * Lấy ảnh đại diện của sản phẩm cha
     * @param $productId
     * @return mixed
     */
    public function getAvatarOfProductMaster($productId)
    {
        return $this->productImage->getAvatarOfProductMaster($productId);
    }
}