<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/12/2018
 * Time: 3:24 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class MapProductAttributeTable extends Model
{
    use ListTableTrait;
    protected $table = 'map_product_attributes';
    protected $primaryKey = 'map_product_attribute_id';

    protected $fillable = ['map_product_attribute_id', 'product_attribute_groupd_id', 'product_attribute_id', 'product_id', 'created_at', 'updated_at'];

    protected function _getList()
    {
        return $this->select('map_product_attribute_id', 'product_attribute_groupd_id', 'product_attribute_id', 'product_id', 'created_at', 'updated_at');
    }

    const is_actived = 1;
    const is_deleted = 0;

    /**
     * Insert map product attribute to database
     *
     * @param array $data
     * @return number
     */
    public function createMapProductAttribute(array $data)
    {
        return $this->create($data);
    }

    /**
     * Insert map product attribute to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $oStaffDepartment = $this->create($data);
        return $oStaffDepartment->id;
    }

    /**
     * Edit map product attribute to database
     *
     * @param array $data , $id
     * @return number
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /**
     * Remove map product attribute to database
     *
     * @param number $id
     */
    public function remove($id)
    {
        $this->where($this->primaryKey, $id)->delete();
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    /*
    * Get map product attribute group by product id
    */
    public function getMapProductAttributeGroupByProductId($idProduct)
    {
        $select = $this->leftJoin('product_attribute_groups', 'product_attribute_groups.product_attribute_group_id',
            '=', 'map_product_attributes.product_attribute_groupd_id')
            ->select(
                'map_product_attributes.product_attribute_groupd_id as productAttrGroupId',
                'product_attribute_groups.product_attribute_group_name as productAttrGroupName'
            )->distinct('map_product_attributes.product_attribute_groupd_id')->where('map_product_attributes.product_id', $idProduct)->get();
        return $select;
    }

    /*
     * Get product attribute by product id
     */
    public function getProductAttributeByProductId($idProduct)
    {
        $select = $this->leftJoin('product_attributes', 'product_attributes.product_attribute_id', 'map_product_attributes.product_attribute_id')
            ->select(
                'map_product_attributes.product_attribute_id as productAttributeId',
                'map_product_attributes.product_attribute_groupd_id as productAttributeGroupId',
                'product_attributes.product_attribute_label as productAttributeLabel'
            )
            ->where('map_product_attributes.product_id', $idProduct)->get();
        return $select;
    }

    /*
     * test map product attribute
     */
    public function testMapProductAttributeIsset($idProduct, $attribute)
    {
        return $this->where('product_attribute_id', $attribute)->where('product_id', $idProduct)->first();
    }

    /*
        * get all product attribute by product id
        */
    public function getAllAttrByProductId($product)
    {
        return $this->where('product_id', $product)->get();
    }

    public function deleteMapProductAttrByAttrId($idProduct, $attribute)
    {
        return $this->where('product_attribute_id', $attribute)->where('product_id', $idProduct)->delete();
    }

    public function deleleAllByProductId($idProduct)
    {
        return $this->where('product_id', $idProduct)->delete();
    }

    /**
     * Thêm mảng dữ liệu
     * @param $data
     */
    public function insertArr($data){
        return $this->insert($data);
    }

    /**
     * Lấy danh sách attribute theo productId
     * @param $productId
     */
    public function getListByProductId($productId){
        return $this
            ->select(
                $this->table.'.*',
                'product_attribute_groups.product_attribute_group_id',
                'product_attribute_groups.product_attribute_group_name as product_attribute_group_name_vi',
//                'product_attribute_groups.product_attribute_group_name_en',
                'product_attributes.product_attribute_label as product_attribute_label_vi',
//                'product_attributes.product_attribute_label_en',
                'product_childs.is_master',
                'product_childs.price'
            )
            ->join('product_attributes','product_attributes.product_attribute_id',$this->table.'.product_attribute_id')
            ->join('product_attribute_groups','product_attribute_groups.product_attribute_group_id',$this->table.'.product_attribute_groupd_id')
            ->join('product_childs','product_childs.product_child_id',$this->table.'.product_child_id')
            ->where($this->table.'.product_id',$productId)
            ->where('product_attribute_groups.is_actived',self::is_actived)
            ->where('product_attribute_groups.is_deleted',self::is_deleted)
            ->where('product_attributes.is_actived',self::is_actived)
            ->where('product_attributes.is_deleted',self::is_deleted)
            ->get();
    }

    public function getMapProductAttribute($productChildId, $attributeId){
        return  $this->select()
                     ->where("product_child_id", $productChildId)
                     ->where("product_attribute_id", $attributeId)
                     ->first();
    }

}