<?php


namespace Modules\FNB\Repositories\OrderCommission;


interface OrderCommissionRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data);

    public function getItemByOrderDetail($order_detail_id);
}