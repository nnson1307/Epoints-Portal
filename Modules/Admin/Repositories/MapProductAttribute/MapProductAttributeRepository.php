<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/12/2018
 * Time: 3:29 PM
 */

namespace Modules\Admin\Repositories\MapProductAttribute;

use Modules\Admin\Models\MapProductAttributeTable;

class MapProductAttributeRepository implements MapProductAttributeRepositoryInterface
{
    protected $mapProductAttribute;
    protected $timestamps = true;

    public function __construct(MapProductAttributeTable $mapProductAttribute)
    {
        $this->mapProductAttribute = $mapProductAttribute;
    }

    /**
     * get list department
     */
    public function list(array $filterts = [])
    {
        return $this->mapProductAttribute->getList($filterts);
    }

    /**
     * add department.
     */
    public function add(array $data)
    {
        return $this->mapProductAttribute->add($data);
    }

    /**
     * edit department
     */
    public function edit(array $data, $id)
    {
        return $this->mapProductAttribute->edit($data, $id);
    }

    /**
     * delete department
     */
    public function remove($id)
    {
        return $this->mapProductAttribute->remove($id);
    }

    /**
     * Get item
     */
    public function getItem($id)
    {
        return $this->mapProductAttribute->getItem($id);
    }

    /*
    * Get map product attribute group by product id
     */
    public function getMapProductAttributeGroupByProductId($idProduct)
    {
        return $this->mapProductAttribute->getMapProductAttributeGroupByProductId($idProduct);
    }

    /*
     * Get product attribute by product id
     */
    public function getProductAttributeByProductId($idProduct)
    {
        $data = $this->mapProductAttribute->getProductAttributeByProductId($idProduct);
//        $array = [];
//        foreach ($data as $item) {
//            $array[$item['productAttributeId']] = $item['productAttributeLabel'];
//        }
        return $data;
    }

    /*
     * test map product attribute
     */
    public function testMapProductAttributeIsset($idProduct,$attribute)
    {
        return $this->mapProductAttribute->testMapProductAttributeIsset($idProduct,$attribute);
    }

    /*
    * get all product attribute by product id
    */
    public function getAllAttrByProductId($product)
    {
        return $this->mapProductAttribute->getAllAttrByProductId($product);
    }

    public function deleteMapProductAttrByAttrId($idProduct, $attribute)
    {
        return $this->mapProductAttribute->deleteMapProductAttrByAttrId($idProduct, $attribute);
    }
    public function deleleAllByProductId($idProduct)
    {
        return $this->mapProductAttribute->deleleAllByProductId($idProduct);
    }

    /**
     * Thêm mảng dữ liệu
     * @param $data
     * @return mixed
     */
    public function insertArr($data){
        return $this->mapProductAttribute->insertArr($data);
    }

    /**
     * Lấy danh sách attribute theo productId
     * @param $productId
     * @return mixed|void
     */
    public function getListMapProductAttribute($productId)
    {
        return $this->mapProductAttribute->getListByProductId($productId);
    }
}