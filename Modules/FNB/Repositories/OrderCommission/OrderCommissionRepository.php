<?php


namespace Modules\FNB\Repositories\OrderCommission;



use Modules\FNB\Models\OrderCommissionTable;

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
}