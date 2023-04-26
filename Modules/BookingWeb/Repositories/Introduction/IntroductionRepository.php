<?php


namespace Modules\BookingWeb\Repositories\Introduction;


use Modules\BookingWeb\Http\Api\BookingApi;

class IntroductionRepository implements IntroductionRepositoryInterface
{
    protected $bookingApi;
    public function __construct(BookingApi $bookingApi)
    {
        $this->bookingApi = $bookingApi;
    }

    public function getInfo(array $data=[])
    {
        return $this->bookingApi->getIntroduction($data);
    }
}