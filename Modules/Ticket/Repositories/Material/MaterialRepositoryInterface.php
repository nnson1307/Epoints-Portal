<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\Ticket\Repositories\Material;


interface MaterialRepositoryInterface
{
    /**
     * Get Material list
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
     * Delete Material
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add Material
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update Material
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
    
    public function getItemByTicketId($ticket_id);

    public function getMaterialDetailByTicketId($ticket_id);

    /**
     * @param $ticket_id
     * @return mixed
     */
    public function getListStaff($ticket_id);
}