<?php


namespace Modules\Report\Repository\StatisticBranch;


interface StatisticBranchRepoInterface
{
    /**
     * Dữ liệu option chi nhánh
     *
     * @return mixed
     */
    public function dataViewIndex();

    /**
     * Filter thời gian, chi nhánh
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