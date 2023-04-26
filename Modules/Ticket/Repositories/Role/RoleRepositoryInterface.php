<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\Ticket\Repositories\Role;


interface RoleRepositoryInterface
{
    /**
     * Get Role list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

     /**
     * Get all
     *
     * @param array $all
     */
    public function getAll(array $filters = []);
    
    /**
     * Get getName
     *
     * @param array $null
     */
    public function getName();

    /**
     * Get getRoleGroupId
     *
     * @param array $null
     */
    public function getRoleGroupId();

    /**
     * Delete Role
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add Role
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update Role
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

 
}