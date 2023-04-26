<?php


namespace Modules\BookingWeb\Repositories\Booking;


interface BookingRepositoryInterface
{
    public function listBranch(array $data=[]);

    public function settingTimeBooking(array $data=[]);

    public function timeWorking(array $data=[]);

    public function listServiceBooking(array $data=[]);

    public function optionServiceBooking(array $data=[]);

    public function listStaffBooking(array $data=[]);

    public function submitBooking(array $data=[]);

    public function spaInfo(array $data=[]);

    public function getSliderHeader(array $data=[]);
}