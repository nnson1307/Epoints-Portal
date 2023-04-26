<?php


namespace Modules\BookingWeb\Repositories\Service;


use Illuminate\Http\Request;
use Modules\BookingWeb\Http\Api\BookingApi;

class ServiceRepository implements ServiceRepositoryInterface
{
    protected $booking;
    public function __construct(BookingApi $booking)
    {
        $this->booking = $booking;

    }

    public function list(array $data = [])
    {
        // TODO: Implement list() method.
        return $this->booking->getListService($data);
    }

    public function getService(array $data = [])
    {
        // TODO: Implement getService() method.
        return $this->booking->getService($data);
    }

    public function getServiceDetailGroup(array $data = [])
    {
        // TODO: Implement getServiceDetail() method.
        return $this->booking->getServiceDetailGroup($data);
    }

}