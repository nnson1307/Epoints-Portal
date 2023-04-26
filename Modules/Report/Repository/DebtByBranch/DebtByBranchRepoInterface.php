<?php


namespace Modules\Report\Repository\DebtByBranch;


interface DebtByBranchRepoInterface
{
    /**
     * Data cho View báo cáo công nợ theo chi nhánh
     *
     * @return mixed
     */
    public function dataViewIndex();

    /**
     * filter báo cáo theo thời gian, chi nhánh
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