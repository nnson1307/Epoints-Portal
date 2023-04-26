<?php

namespace Modules\FNB\Repositories\OrderApp;


interface OrderAppRepoInterface
{
    /**
     * Trừ quota_use của đơn hàng có promotion là quà tặng
     *
     * @param $orderId
     * @return mixed
     */
    public function subtractQuotaUsePromotion($orderId);

    /**
     * Group số lượng mua của các object, lấy ra CTKM áp dụng cho đơn hàng
     *
     * @param $arrObjectBuy
     * @return mixed
     */
    public function groupQuantityObjectBuy($arrObjectBuy);

    /**
     * Cộng quota_use khi mua hàng có promotion là quà tặng
     *
     * @param $arrQuota
     * @return mixed
     */
    public function plusQuotaUsePromotion($arrQuota);
}