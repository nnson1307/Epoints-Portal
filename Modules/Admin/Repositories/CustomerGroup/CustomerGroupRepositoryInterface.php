<?php
namespace Modules\Admin\Repositories\CustomerGroup;

/**
 * customer Group  Repository interface
 *
 * @author thach
 * @since   2018
 */
interface CustomerGroupRepositoryInterface
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
     * Update OR ADD customer Group
     * @param array $data
     * @return number
     */
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

    /**
     * @param $name
     * @param $id
     * @return mixed
     */
    public function testName($name, $id);
    /*
       * test group name
       */
    public function testGroupName($name);
    /*
     * test is delete.
     */
    public function testIsDeleted($name);
    /*
     * edit by name
     */
    public function editByName($name);
    /*
     * delete by name
     */
    public function deleteByName($name);
} 