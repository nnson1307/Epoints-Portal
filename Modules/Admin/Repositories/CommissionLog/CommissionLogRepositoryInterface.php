<?php


namespace Modules\Admin\Repositories\CommissionLog;


interface CommissionLogRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data);

    /**
     * @param $customer_id
     * @return mixed
     */
    public function getLogByCustomer($customer_id);
}