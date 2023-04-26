<?php


namespace Modules\Admin\Repositories\PointHistory;


use Modules\Admin\Models\PointHistoryTable;

class PointHistoryRepo implements PointHistoryRepoInterface
{
    protected $pointHistory;

    public function __construct(
        PointHistoryTable $pointHistory
    ) {
        $this->pointHistory = $pointHistory;
    }

    /**
     * @param $customer_id
     * @param $description
     * @return mixed
     */
    public function getHistoryByDescription($customer_id, $description)
    {
        return $this->pointHistory->getHistoryByDescription($customer_id, $description);
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getPointGroupByCustomer($startTime, $endTime)
    {
        return $this->pointHistory->getPointGroupByCustomer($startTime, $endTime);
    }

    public function getHistory($id)
    {
        return $this->pointHistory->getHistory($id);
    }
}