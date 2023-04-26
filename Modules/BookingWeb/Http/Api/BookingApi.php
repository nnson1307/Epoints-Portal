<?php


namespace Modules\BookingWeb\Http\Api;


use MyCore\Api\ApiAbstract;
use MyCore\Models\Traits\ListTableTrait;

class BookingApi extends ApiAbstract
{
    use ListTableTrait;

    public function listBranch(array $data=[])
    {
        return $this->baseClient('/booking/get-branch',$data);
    }

    public function settingTimeBooking(array $data=[])
    {
        return $this->baseClient('/booking/booking-get-rule-setting-other',$data);
    }

    public function timeWorking(array $data=[])
    {
        return $this->baseClient('/booking/get-time-work',$data);
    }

    public function listServiceBooking(array $data=[])
    {
        return $this->baseClient('/booking/booking-get-service',$data);
    }

    public function optionServiceBooking(array $data=[])
    {
        return $this->baseClient('/booking/get-all-service',$data);
    }

    public function listStaffBooking(array $data=[])
    {
        return $this->baseClient('/booking/booking-get-technician',$data);
    }

    public function submitBooking(array $data=[])
    {
        return $this->baseClient('/booking/booking-submit',$data,false);
    }

    public function spaInfo(array $data=[])
    {
        return $this->baseClient('/booking/get-about-us',$data);
    }

    public function getSliderHeader(array $data=[])
    {
        return $this->baseClient('/booking/get-slider-header',$data);
    }
//    Lấy danh sách service làm menu
    public function getListService(array $data=[])
    {
        return $this->baseClient('/booking/options',$data);
    }
//    Lấy danh sách dịch vụ theo group
    public function getService(array $data =[])
    {
        return $this->baseClient('/booking/get-service-list',$data);
    }

    public function getServiceDetailGroup(array $data=[])
    {
        return $this->baseClient('/booking/get-service-detail-group',$data);
    }

    public function getProduct(array $data=[])
    {
        return $this->baseClient('/booking/get-product-list',$data);
    }

    public function getProductDetailGroup(array $data=[])
    {
        return $this->baseClient('/booking/get-product-detail-group',$data);
    }

    public function getListBrand(array $data=[])
    {
        return $this->baseClient('/booking/list-brand',$data);
    }

    public function getIntroduction(array $data=[])
    {
        return $this->baseClient('/booking/introduction',$data);
    }

}