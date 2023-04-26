<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\Ticket\Repositories\Staff;


interface StaffRepositoryInterface
{
    /**
     * Get Staff list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    
    public function listStaff(array $filters = []);

     /**
     * Get all
     *
     * @param array $all
     */
    public function getAll(array $filters = []);
    
     /**
     * Get all
     *
     * @param array $all
     */
    public function getName();
    
    /**
     * Delete Staff
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add Staff
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update Staff
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

    public function testCode($code, $id);

    public function checkExistEmail($email);

    public function getDetail($id = '');
}