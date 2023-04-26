<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\Ticket\Repositories\RequestGroup;


interface RequestGroupRepositoryInterface
{
    /**
     * Get RequestGroup list
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
     * Delete RequestGroup
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add RequestGroup
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update RequestGroup
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
    
    /*
    * get list option
    */
    public function getName();
}