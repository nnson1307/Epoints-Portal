<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\Ticket\Repositories\TicketQueueMap;


interface TicketQueueMapRepositoryInterface
{
    /**
     * Get TicketQueueMap list
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
     * Get all
     *
     * @param array $all
     */
    public function getName();

    /**
     * Delete TicketQueueMap
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add TicketQueueMap
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update TicketQueueMap
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

    /*
    * check exist
    */
    public function checkExistEmail($email);

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id, $startTime, $endTime);
    
    // remove file by ticket id
    public function removeFile($ticketId);
}