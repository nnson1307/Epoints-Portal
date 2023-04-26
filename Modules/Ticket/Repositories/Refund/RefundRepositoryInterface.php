<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\Ticket\Repositories\Refund;


interface RefundRepositoryInterface
{
    /**
     * Get Refund list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Get list name
     *
     * @param array get list name
     */
    public function getName();

    /**
     * Delete Refund
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add Refund
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update Refund
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