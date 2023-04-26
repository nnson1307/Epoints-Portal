<?php


namespace Modules\Report\Repository\RevenueByBranch;


interface RevenueByBranchRepoInterface
{
    /**
     * View báo cáo doanh thu theo chi nhánh
     *
     * @return mixed
     */
    public function dataViewIndex();

    /**
     * filter time, branch, customer group
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