<?php


namespace Modules\BookingWeb\Repositories\Brand;


use Modules\BookingWeb\Http\Api\BookingApi;

class BrandRepository implements BrandRepositoryInterface
{
    protected $booking;
    public function __construct(BookingApi $booking)
    {
        $this->booking = $booking;

    }

    public function getListBrand(array $data = [])
    {
        // TODO: Implement getListBrand() method.
        return $this->booking->getListBrand($data);
    }
}