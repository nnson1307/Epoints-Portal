<?php

namespace Modules\Report\Repository\StatisticCustomer;

interface StatisticCustomerRepoInterface
{
    /**
     * Data cho view index (danh sách chi nhánh)
     *
     * @return mixed
     */
    public function dataViewIndex();

    /**
     * filter
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