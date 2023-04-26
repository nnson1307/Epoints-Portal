<?php


namespace Modules\Admin\Http\Api;


use MyCore\Api\ApiAbstract;

class LoyaltyApi extends ApiAbstract
{
    /**
     * Cộng điểm cho khách hàng khi có hoạt động
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function plusPointEvent(array $data = [])
    {
        return $this->baseClient('/loyalty/plus-point-event',$data);
    }
}