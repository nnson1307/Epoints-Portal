<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/12/2021
 * Time: 15:13
 */

namespace Modules\Admin\Http\Api;

use GuzzleHttp\Client;
use Modules\Payment\Models\ConfigTable;
use MyCore\Api\ApiAbstract;

class PaymentOnline extends ApiAbstract
{
    /**
     * Thanh toán vn pay
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function paymentVnPay(array $data = [])
    {
        return $this->baseClientShareService('/payment/pay', $data);
    }

    /**
     * Lấy trạng thái thanh toán vn pay
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function returnPaymentVnPay(array $data = [])
    {
        return $this->baseClientShareService('/payment/return', $data);
    }


    public function settingPaymentVnPay(array $data = [])
    {
        return $this->baseClientShareService('/payment/setting', $data);
    }
}