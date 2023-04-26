<?php


namespace Modules\BookingWeb\Repositories\Product;


use Modules\BookingWeb\Http\Api\BookingApi;

class ProductRepository implements ProductRepositoryInterface
{
    protected $booking;
    public function __construct(BookingApi $booking)
    {
        $this->booking = $booking;

    }

    public function list(array $data = [])
    {
        return $this->booking->getListService($data);
    }

    public function getProduct(array $data = [])
    {
        // TODO: Implement getService() method.
        return $this->booking->getProduct($data);
    }

    public function getProductDetailGroup(array $data = [])
    {
        // TODO: Implement getProductDetailGroup() method.
        return $this->booking->getProductDetailGroup($data);
    }
}