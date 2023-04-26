<?php
/**
 * Created by PhpStorm.
 * User: nhu
 * Date: 20/03/2018
 * Time: 10:17
 */

namespace Modules\Admin\Repositories\OrderDeliveryType;

interface OrderDeliveryTypeRepositoryInterface
{
    /**
     * Get Order Delivery Type
     *
     * @param array $filters
     */
    public function list(array $filters = []);
    /**
     * Delete Order Delivery Type
     *
     * @param number $id
     */
    public function remove($id);
    /**
     * Add Order Delivery Type
     * @param array $data
     * @return number
     */
    public function add(array $data);
    /**
     * Update Order Delivery Type
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);
    /**
     * Update OR ADD Order Delivery Type
     * @param array $data
     * @return number
     */
    public function save(array $data, $id);
    /**
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);

}