<?php


namespace Modules\Report\Repository\CustomerByViewPurchase;


interface CustomerByViewPurchaseRepoInterface
{
    /**
     * data view report
     *
     * @return mixed
     */
    public function dataView();

    /**
     * Biểu đồ những khách hàng mua sản phẩm (xem sản phẩm) thuộc nhóm sản phẩm nhiều nhất
     *
     * @param $input
     * @return mixed
     */
    public function loadChart($input);

    /**
     * Xuất dữ liệu ra excel theo filter
     *
     * @param $input
     * @return mixed
     */
    public function exportExcel($input);
}