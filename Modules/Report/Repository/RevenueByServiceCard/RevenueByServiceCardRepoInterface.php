<?php


namespace Modules\Report\Repository\RevenueByServiceCard;


interface RevenueByServiceCardRepoInterface
{
    /**
     * Data cho View báo cáo doanh thu theo thẻ dịch vụ
     *
     * @return mixed
     */
    public function dataViewIndex();

    /**
     * filter thời gian, chi nhánh, số lượng thẻ dịch vụ
     *
     * @param $input
     * @return mixed
     */
    public function filterAction($input);
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