<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\Ticket\Repositories\TicketAction;


interface TicketActionRepositoryInterface
{
    /**
     * Get ticketAction list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Get option list
     *
     * @param $null
     */

    public function getName();

    /**
     * Delete ticketAction
     *
     * @param number $id
     */
    public function remove($id);

     /**
     * Get all
     *
     * @param array $all
     */
    public function getAll(array $filters = []);
    
    /**
     * Add TicketAction
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update TicketAction
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