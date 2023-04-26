<?php

namespace Modules\Report\Repository\RevenueBySurchargeService;

interface RevenueBySurchargeServiceRepoInterface
{
    /**
     * Data cho View báo cáo doanh thu theo dịch vụ phụ thu
     *
     * @return mixed
     */
    public function dataViewIndex();

    /**
     * filter thời gian, chi nhánh, số lượng dịch vụ phụ thu
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