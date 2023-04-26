<?php


namespace Modules\Admin\Repositories\OrderCommission;


interface OrderCommissionRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data);

    /**
     * @param $order_detail_id
     * @return mixed
     */
    public function getItemByOrderDetail($order_detail_id);

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id);

    /**
     * @param $customer_id
     * @return mixed
     */
    public function getCommissionByCustomer($customer_id);

    /**
     * @param $time
     * @return mixed
     */
    public function reportStaffCommission($time);
}