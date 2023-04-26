<?php


namespace Modules\Report\Repository\RevenueByCustomer;


interface RevenueByCustomerRepoInterface
{
    /**
     * Data cho View báo cáo doanh thu theo khách hàng
     *
     * @return mixed
     */
    public function dataViewIndex();

    /**
     * filter thời gian, chi nhánh, số lượng khách hàng
     *
     * @param $data
     * @return mixed
     */
    public function filterAction($data);
    /**
     * Ds chi tiết của chart
     *
     * @param $input
     * @return mixed
     */
    public function listDetail($input);

    /**
     * Export excel tổng
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelTotal($input);

    /**
     * Export excel chi tiết
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelDetail($input);
}