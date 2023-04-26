<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/3/2018
 * Time: 2:26 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ProductImageTable extends Model
{
    use ListTableTrait;
    protected $table = 'product_images';
    protected $primaryKey = 'product_image_id';
    public $timestamps = true;

    protected $fillable = [
        'product_image_id',
        'product_id',
        'product_child_code',
        'name',
        'type',
        'created_at',
        'created_by',
        'updated_at',
        'is_avatar'
    ];

    protected function _getList()
    {
        $select = $this->leftJoin('products', 'products.product_id', '=', 'product_images.product_id')
            ->select(
                'product_images.product_image_id as productImageId',
                'product_images.name as productImageName',
                'product_images.type as productImageType',
                'product_images.created_at as productImageCreateAt',
                'product_images.product_id as productId',
                'products.product_name as productName'
            )->orderBy($this->primaryKey, 'desc');
        return $select;
    }
    public function add(array $data)
    {
        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->product_image_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function edit(array $data, $id)
    {

        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }
    /*
     * get image link by product.
     */
    public function getImageByProductId($productId){
        return $this
            ->where('product_id',$productId)
            ->where('is_avatar', 0)
            ->groupBy('name')
            ->get();
    }
    /*
     * delete all image by product
     */
    public function deleteByProductId($productId){
        return $this->where('product_id',$productId)->delete();
    }
    /*
     * delete image by product id and link image
     */
    public function deleteImageByProductIdAndLink($productId,$link){
        return $this->where('product_id',$productId)->where('name',$link)->delete();
    }

    /**
     * Lấy hình ảnh sản phẩm con
     *
     * @param $productChildCode
     * @return mixed
     */
    public function getImageByCode($productChildCode)
    {
        return $this
            ->select(
                "product_image_id",
                "product_child_code",
                "name"
            )
            ->where("product_child_code", $productChildCode)
            ->get();
    }

    /**
     * Lấy ảnh đại diện sản phẩm
     *
     * @param $productCode
     * @return mixed
     */
    public function getAvatar($productCode)
    {
        return $this
            ->select(
                "product_image_id",
                "product_child_code",
                "name"
            )
            ->where("product_child_code", $productCode)
            ->where("is_avatar", 1)
            ->first();
    }

    /**
     * Xoá tất cả ảnh của sản phẩm con trừ avatar
     *
     * @param $prodChildCode
     * @return mixed
     */
    public function removeImageByProdChildCode($prodChildCode)
    {
        return $this->where('product_child_code',$prodChildCode)
            ->where('is_avatar', 0)
            ->delete();
    }

    /**
     * Danh sách ảnh sản phẩm trừ avatar
     *
     * @param $prodChildCode
     * @return mixed
     */
    public function getImageExceptAvatar($prodChildCode)
    {
        return $this
            ->select(
                "product_image_id",
                "product_child_code",
                "name"
            )
            ->where("product_child_code", $prodChildCode)
            ->where("is_avatar", 0)
            ->get();
    }

    public function editAvatar(array $data, $prodChildCode)
    {
        return $this->where("product_child_code", $prodChildCode)
            ->where("is_avatar", 1)
            ->update($data);
    }

    /**
     * xoá tất cả avatar của product theo product id
     *
     * @param $productId
     * @return mixed
     */
    public function deleteAllAvatarByProductId($productId)
    {
        return $this->where('product_id',$productId)
            ->where('is_avatar', 1)
            ->delete();
    }

    /**
     * Lấy ảnh đại diện của sản phẩm cha
     *
     * @param $productId
     * @return mixed
     */
    public function getAvatarOfProductMaster($productId)
    {
        return $this
            ->where('product_id', $productId)
            ->whereNotNull('name')
            ->where('is_avatar', 1)
            ->first();
    }
}