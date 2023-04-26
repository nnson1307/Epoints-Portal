<?php

namespace Modules\Warranty\Repository\ReportRepairCost;

interface ReportRepairCostRepoInterface
{
    /**
     * Data cho View báo cáo chi phí bảo dưỡng
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
}