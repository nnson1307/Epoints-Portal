<?php

namespace Modules\Admin\Repositories\CustomerSource;

/**
 * customer Group  Repository interface
 */
interface CustomerSourceRepositoryInterface
{
    /**
     * Get customer Group list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Delete customer Group
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add customer Group
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update customer Group
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

    /**
     * @return mixed
     */
    public function getOption();
    /*
    * test customer source
    */
    public function testCustomerSourceName($customerSourceName);
    /*
     * test customer source edit
     */
    public function testCustomerSourceNameEdit($id,$customerSourceName);
    /*
     * add update customer source
     */
    public function testIsDeleted($customerSourceName);
    /*
    * edit by customer source name
    */
    public function editByName($customerSourceName);
} 