<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 7/4/2019
 * Time: 4:24 PM
 */

namespace Modules\Booking\Repositories\Service;

use Modules\Booking\Models\ServiceTable;

class ServiceRepository implements ServiceRepositoryInterface
{
    protected $service;

    public function __construct(ServiceTable $service)
    {
        $this->service = $service;
    }

    public function getService(array $filter = [])
    {
        return $this->service->getService($filter);
    }

    public function getListService(array $filter = [])
    {
        return $this->service->getServiceList($filter);
    }

    public function getServiceDetail($id)
    {
        return $this->service->getServiceDetail($id);
    }

    public function getServiceDetailGroup($id)
    {
        return $this->service->getServiceDetailGroup($id);
    }

    public function bookingGetService(array $filter = [])
    {
        return $this->service->bookingGetService($filter);
    }

    public function bookingGetAllService(array $filter = [])
    {
        return $this->service->bookingGetAllService($filter);
    }
}