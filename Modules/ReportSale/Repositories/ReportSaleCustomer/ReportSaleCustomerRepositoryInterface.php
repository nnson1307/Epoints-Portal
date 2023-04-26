<?php

/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/24/2018
 * Time: 10:40 AM
 */

namespace Modules\ReportSale\Repositories\ReportSaleCustomer;

use Illuminate\Http\Request;

interface ReportSaleCustomerRepositoryInterface
{
    public function getOption();
    public function getTotal($request);
    public function getChartTotal($request);
    public function getChartTotalCountOrder($request);
    public function getTotalAmountByCustomerGroup($request);
    public function getTotalOrdersByCustomerGroup($request);
    public function getCustomer($request);
}