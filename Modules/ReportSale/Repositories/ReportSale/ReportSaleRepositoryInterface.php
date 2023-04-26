<?php

/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/24/2018
 * Time: 10:40 AM
 */

namespace Modules\ReportSale\Repositories\ReportSale;

use Illuminate\Http\Request;

interface ReportSaleRepositoryInterface
{
    public function getOption();
    public function getTotal($request);
    public function getChartTotal($request);
    public function getChartTotalCountOrder($request);
    public function getTotalAmountByBranch($request);
    public function getTotalOrdersByBranch($request);
    public function getList($filters);
}