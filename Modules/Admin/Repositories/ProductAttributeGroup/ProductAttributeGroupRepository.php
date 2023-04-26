<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/27/2018
 * Time: 3:55 PM
 */

namespace Modules\Admin\Repositories\ProductAttributeGroup;

use Modules\Admin\Models\ProductAttributeGroupTable;

class ProductAttributeGroupRepository implements ProductAttributeGroupRepositoryInterface
{
    /**
     * @var ProductAttributeGroupTable
     */
    protected $productAttributeGroup;
    protected $timestamps = true;

    public function __construct(ProductAttributeGroupTable $productAttributeGroup)
    {
        $this->productAttributeGroup = $productAttributeGroup;
    }

    /**
     *get list product attribute group
     */
    public function list(array $filters = [])
    {
        return $this->productAttributeGroup->getList($filters);
    }

    /**
     * delete product attribute group
     */
    public function remove($id)
    {
        $this->productAttributeGroup->remove($id);
    }

    /**
     * add product attribute group
     */
    public function add(array $data)
    {

        return $this->productAttributeGroup->add($data);
    }

    /*
     * edit product attribute group
     */
    public function edit(array $data, $id)
    {
        return $this->productAttributeGroup->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->productAttributeGroup->getItem($id);
    }

    /**
     * Get option
     */
    public function getOption()
    {
        $array = array();
        $dt = $this->productAttributeGroup->getOption();
        foreach ($dt as $item) {
            $array[$item['product_attribute_group_id']] = $item['product_attribute_group_name'];
        }
        return $array;
    }

    /**
     * Get option add attribute
     */
    public function getOptionAddAttribute($id)
    {
        $array = array();
        foreach ($this->productAttributeGroup->getOptionAddAttribute($id) as $item) {
            $array[$item['product_attribute_group_id']] = $item['product_attribute_group_name'];
        }
        return $array;
    }

    /**
     * test product attribute group name
     */
    public function testProductAttGroupName($name, $id)
    {
        return $this->productAttributeGroup->testProductAttGroupName($name, $id);
    }
    //get option attribute group have array parameter
    public function getOptionAttributeGroup(array $productAttributeGroupId){

        $array = array();
        foreach ($this->productAttributeGroup->getOptionAttributeGroup($productAttributeGroupId) as $item) {
            $array[$item['product_attribute_group_id']] = $item['product_attribute_group_name'];
        }
        return $array;
    }
    /*
     * test is deleted
     */
    public function testIsDeleted($name)
    {
        return $this->productAttributeGroup->testIsDeleted($name);
    }

    /*
     * edit by name
     */
    public function editByName($name)
    {
        return $this->productAttributeGroup->editByName($name);
    }
}