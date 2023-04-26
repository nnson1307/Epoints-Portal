<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\Ticket\Repositories\QueueStaff;


interface QueueStaffRepositoryInterface
{
    /**
     * Get queueStaff list
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
    
    // lấy id nv đã phân công
    public function getStaff();
    /**
     * Delete queueStaff
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add queueStaff
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update queueStaff
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
     * get by queue ticket id
     * @param array $data
     * @return $data
     */
    public function getQueueOption($ticket_queue_id,$ticket_role_queue_id);


}