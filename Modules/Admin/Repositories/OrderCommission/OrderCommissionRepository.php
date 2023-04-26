<?php


namespace Modules\Admin\Repositories\OrderCommission;


use Modules\Admin\Models\OrderCommissionTable;

class OrderCommissionRepository implements OrderCommissionRepositoryInterface
{
    protected $order_commission;

    public function __construct(
        OrderCommissionTable $order_commission
    ) {
        $this->order_commission = $order_commission;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->order_commission->add($data);
    }

    /**
     * @param $order_detail_id
     * @return mixed
     */
    public function getItemByOrderDetail($order_detail_id)
    {
        return $this->order_commission->getItemByOrderDetail($order_detail_id);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->order_commission->edit($data, $id);
    }

    /**
     * @param $customer_id
     * @return mixed
     */
    public function getCommissionByCustomer($customer_id)
    {
        return $this->order_commission->getCommissionByCustomer($customer_id);
    }

    /**
     * @param $time
     * @return mixed
     */
    public function reportStaffCommission($time)
    {
        return $this->order_commission->reportStaffCommission($time);
    }
}