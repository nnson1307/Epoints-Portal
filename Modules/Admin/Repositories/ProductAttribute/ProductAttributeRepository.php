<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/27/2018
 * Time: 5:52 PM
 */

namespace Modules\Admin\Repositories\ProductAttribute;

use Modules\Admin\Models\ProductAttributeTable;

class ProductAttributeRepository implements ProductAttributeRepositoryInterface
{
    /**
     * @var ProductAttributeTable
     */
    protected $productAttribute;
    protected $timestamps = true;

    public function __construct(ProductAttributeTable $productAttribute)
    {
        $this->productAttribute = $productAttribute;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->productAttribute->getList($filters);
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->productAttribute->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->productAttribute->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->productAttribute->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->productAttribute->getItem($id);
    }

    /*
    *  test unique product_attribute_code
    */
    public function testCode($code, $id)
    {
        return $this->productAttribute->testCode($code, $id);
    }

    /*
     * get product attribute by group
     */
    public function getProductAttributeByGroup($idGroup)
    {
        $array = [];
        $data = $this->productAttribute->getProductAttributeByGroup($idGroup);
        foreach ($data as $item) {
            $array[$item['product_attribute_id']] = $item['product_attribute_label'];
        }
        return $array;
    }

    public function getProductAttributeGroup($attributeId)
    {
        return $this->productAttribute->getProductAttributeGroup($attributeId);
    }

    /*
     * Get option product attribute
     */
    public function getOption()
    {
        $data = $this->productAttribute->getOption();
        return $data;
    }

    /*
     * get product attribute id
     */
    public function getProductAttributeId()
    {
        return $this->productAttribute->getProductAttributeId();
    }

    /*
     * Get product attribute where not in
     */
    public function getProductAttributeWhereNotIn(array $data)
    {
        $result = $this->productAttribute->getProductAttributeWhereNotIn($data);
        return $result;
    }

    // test unique product attribute label
    public function testLabel($label, $id)
    {
        return $this->productAttribute->testLabel($label, $id);
    }

    /*
     * check exist
     */
    public function checkExist($group, $label, $isDelete)
    {
        return $this->productAttribute->checkExist($group, $label, $isDelete);
    }

    // Kiểm tra thuộc tính sản phẩm theo id,  id nhóm và thuộc tính (is_deleted=0).
    public function testEdit($id, $groupId, $label)
    {
        return $this->productAttribute->testEdit($id, $groupId, $label);
    }
}