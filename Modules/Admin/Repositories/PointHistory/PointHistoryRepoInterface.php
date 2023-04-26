<?php


namespace Modules\Admin\Repositories\PointHistory;


interface PointHistoryRepoInterface
{
    /**
     * @param $customer_id
     * @param $description
     * @return mixed
     */
    public function getHistoryByDescription($customer_id , $description);

    /**
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getPointGroupByCustomer($startTime, $endTime);

    public function getHistory($id);
}