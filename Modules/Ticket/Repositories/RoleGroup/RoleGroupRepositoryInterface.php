<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\Ticket\Repositories\RoleGroup;


interface RoleGroupRepositoryInterface
{
    /**
     * Get queue list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

     /**
     * Get option
     *
     * @param array $null
     */
    public function getName();
    
    /**
     * Delete queue
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add queue
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update queue
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