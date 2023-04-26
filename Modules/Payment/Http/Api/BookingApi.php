<?php


namespace Modules\Payment\Http\Api;


use MyCore\Api\ApiAbstract;
use MyCore\Models\Traits\ListTableTrait;

class BookingApi extends ApiAbstract
{
    use ListTableTrait;

    /**
     * Tích điểm khi thanh toán đủ tiền
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function loyalty(array $data=[])
    {
        return $this->baseClient('/loyalty/score-calculation',$data);
    }

    /**
     * Tích điểm khi thanh toán chưa đủ tiền
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function plusPointReceipt(array $data = [])
    {
        return $this->baseClient('/loyalty/plus-point-receipt', $data);
    }

    /**
     * Tích điểm khi thanh toán đủ tiền
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function plusPointReceiptFull(array $data = [])
    {
        return $this->baseClient('/loyalty/plus-point-receipt-full', $data);
    }
}