<?php


namespace Modules\Admin\Repositories\CommissionLog;


use Modules\Admin\Models\CommissionLogTable;

class CommissionLogRepository implements CommissionLogRepositoryInterface
{
    protected $commission_log;

    public function __construct(
        CommissionLogTable $commission_log
    ) {
        $this->commission_log = $commission_log;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->commission_log->add($data);
    }

    /**
     * @param $customer_id
     * @return mixed
     */
    public function getLogByCustomer($customer_id)
    {
        return $this->commission_log->getLogByCustomer($customer_id);
    }
}