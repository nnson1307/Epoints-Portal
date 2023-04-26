<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\Ticket\Repositories\TicketRoleActionMap;


interface TicketRoleActionMapRepositoryInterface
{
    /**
     * Get TicketRoleActionMap list
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
     * Delete TicketRoleActionMap
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add TicketRoleActionMap
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update TicketRoleActionMap
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
    * check exist
    */
    public function checkExistEmail($email);

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id, $startTime, $endTime);
    
    public function removeByRole($roleId);
}