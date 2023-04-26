<?php
/**
 * Created by PhpStorm.
 * LeDangSinh
 * Date: 3/31/2018
 */

namespace Modules\Services\Repositories\ServiceTime;

use Modules\Services\Models\ServiceTimeTable;

class ServiceTimeRepository implements ServiceTimeRepositoryInterface
{
    protected $serviceTime;
    protected $timestamps = true;

    public function __construct(ServiceTimeTable $serviceTime)
    {
        $this->serviceTime = $serviceTime;
    }

    public function list(array $filters = [])
    {
        return $this->serviceTime->getList($filters);
    }

    public function getItem($id)
    {
        return $this->serviceTime->getItem($id);
    }

    public function getOptionServiceTime()
    {
        $array = array();
        foreach ($this->serviceTime->getOptionServiceTime() as  $value) {
            $array[$value['service_time_id']] = $value['time'];
        }
        return $array;

    }
}