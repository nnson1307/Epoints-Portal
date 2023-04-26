<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 13/03/2018
 * Time: 1:48 CH
 */

namespace Modules\Admin\Repositories\CustomerGroup;


use Modules\Admin\Models\CustomerGroupTable;


class CustomerGroupRepository implements CustomerGroupRepositoryInterface
{

    /**
     * @var CustomerGroupTable
     */
    protected $customerGroup;
    protected $timestamps = true;

    public function __construct(CustomerGroupTable $customerGroup)
    {
        $this->customerGroup = $customerGroup;
    }

    /**
     *get list customer Group
     */
    public function list(array $filters = [])
    {
        return $this->customerGroup->getList($filters);
    }

    /**
     * delete customer Group
     */
    public function remove($id)
    {
        $this->customerGroup->remove($id);
    }

    /**
     * add customer Group
     */
    public function add(array $data)
    {
        return $this->customerGroup->add($data);
    }

    /*
     * edit customer Group
     */
    public function edit(array $data, $id)
    {
        return $this->customerGroup->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->customerGroup->getItem($id);
    }

    /**
     * @return array
     */
    public function getOption()
    {
        $array = array();
        foreach ($this->customerGroup->getOption() as $item) {
            $array[$item['customer_group_id']] = $item['group_name'];

        }
        return $array;
    }

    /**
     * @param $name
     * @param $id
     * @return mixed
     */
    public function testName($name, $id)
    {
        return $this->customerGroup->testName($name, $id);
    }

    /*
    * test group name
    */
    public function testGroupName($name)
    {
        return $this->customerGroup->testGroupName($name);
    }

    /*
     * test is delete.
     */
    public function testIsDeleted($name){
        return $this->customerGroup->testIsDeleted($name);
    }
    /*
     * edit by name
     */
    public function editByName($name){
        return $this->customerGroup->editByName($name);
    }
    /*
     * delete by name
     */
    public function deleteByName($name){
        return $this->customerGroup->deleteByName($name);
    }
}