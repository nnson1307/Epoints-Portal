<?php


namespace Modules\BookingWeb\Repositories\Booking;


use Modules\BookingWeb\Http\Api\BookingApi;


class BookingRepository implements BookingRepositoryInterface
{

    protected $booking;


    public function __construct(BookingApi $booking)
    {
        $this->booking = $booking;
    }

    public function listBranch(array $data = [])
    {
        // TODO: Implement listBranch() method.
        return $this->booking->listBranch($data);
    }

    public function settingTimeBooking(array $data = [])
    {
        // TODO: Implement settingTimeBooking() method.
        return $this->booking->settingTimeBooking($data);
    }

    public function timeWorking(array $data = [])
    {
        // TODO: Implement timeWorking() method.
        return $this->booking->timeWorking($data);
    }

    public function listServiceBooking(array $data = [])
    {
        // TODO: Implement list() method.
        return $this->booking->listServiceBooking($data);
    }

    public function optionServiceBooking(array $data = [])
    {
        // TODO: Implement optionServiceBooking() method.
        return $this->booking->optionServiceBooking($data);
    }

    public function listStaffBooking(array $data = [])
    {
        // TODO: Implement listStaffBooking() method.
        return $this->booking->listStaffBooking($data);
    }

    public function submitBooking(array $data = [])
    {
        // TODO: Implement submitBooking() method.
        return $this->booking->submitBooking($data);
    }

    public function spaInfo(array $data = [])
    {
        // TODO: Implement spaInfo() method.
        return $this->booking->spaInfo($data);
    }

    public function getSliderHeader(array $data = [])
    {
        // TODO: Implement getSliderHeader() method.
        return $this->booking->getSliderHeader($data);
    }

}