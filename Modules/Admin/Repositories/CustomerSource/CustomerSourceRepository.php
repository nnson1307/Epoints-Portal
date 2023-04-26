<?php
/**
 * Created by PhpStorm.
 * User: nhu
 * Date: 13/03/2018
 * Time: 1:48 CH
 */

namespace Modules\Admin\Repositories\CustomerSource;


use Modules\Admin\Models\CustomerSourceTable;


class CustomerSourceRepository implements CustomerSourceRepositoryInterface
{

    /**
     * @var CustomerSourceTable
     */
    protected $customerSource;
    protected $timestamps = true;

    public function __construct(CustomerSourceTable $customerSource)
    {
        $this->customerSource = $customerSource;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->customerSource->getList($filters);
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->customerSource->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->customerSource->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->customerSource->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->customerSource->getItem($id);
    }

    /**
     * @return array|mixed
     */
    public function getOption()
    {
        $array = array();
        foreach ($this->customerSource->getOptionCustomerSource() as $item) {
            $array[$item['customer_source_id']] = $item['customer_source_name'];

        }
        return $array;
    }

    /*
    * test customer source
    */
    public function testCustomerSourceName($customerSourceName)
    {
        return $this->customerSource->testCustomerSourceName($customerSourceName);
    }

    /*
     * test customer source edit
     */
    public function testCustomerSourceNameEdit($id, $customerSourceName)
    {
        return $this->customerSource->testCustomerSourceNameEdit($id, $customerSourceName);
    }

    /*
     * add update customer source
     */
    public function testIsDeleted($customerSourceName)
    {
        return $this->customerSource->testIsDeleted($customerSourceName);
    }

    /*
     * edit by customer source name
     */
    public function editByName($customerSourceName)
    {
        return $this->customerSource->editByName($customerSourceName);
    }
}