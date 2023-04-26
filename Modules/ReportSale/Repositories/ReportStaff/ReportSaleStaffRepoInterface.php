<?php

namespace Modules\ReportSale\Repositories\ReportStaff;

interface ReportSaleStaffRepoInterface
{
    public function getOption();
    public function getTotal($request);
    public function getChartTotal($request);
    public function getChartTotalCountOrder($request);
    public function getTotalAmountByStaff($request);
    public function getTotalOrdersByStaff($request);
}