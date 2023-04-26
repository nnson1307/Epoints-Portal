<?php


namespace Modules\Report\Repository\PurchaseByHour;


interface PurchaseByHourRepoInterface
{
    /**
     * Load chart báo cáo tỉ lệ mua hàng theo khung giờ
     *
     * @param $input
     * @return mixed
     */
    public function loadChart($input);
}