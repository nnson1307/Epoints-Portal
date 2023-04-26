<?php

namespace Modules\Admin\Repositories\OrderStatus;
interface OrderStatusRepositoryInterface
{
    /**
     * Get Order Status list
     *
     * @param array $filters
     */
    public function list(array $filters=[]);

    /**
     * Add Order Status
     *
     * @param array $data
     * @return number
     */
    public function add(array $data);
    /**

     * Delete Order Status
     *
     * @param number $id
     */
    public function remove($id);
    /**
     * Edit Order Status
     *
     * @param array $data,$id
     * @return number
     */
    public function edit(array $data,$id);
    public function getEdit($id);

    /**
     * Export Excel store
     *
     **/
    public function exportExcel(array $array,$title);
    public function import(array $data);
}