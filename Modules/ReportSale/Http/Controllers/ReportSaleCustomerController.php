<?php

/**
 * ServicesController
 * LeDangSinh
 * Date: 3/28/2018
 */

namespace Modules\ReportSale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;
use Modules\ReportSale\Repositories\ReportSale\ReportSaleRepositoryInterface;
use Modules\ReportSale\Repositories\ReportSaleCustomer\ReportSaleCustomerRepositoryInterface;
use Carbon\Carbon;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Http\Request as HttpRequest;

class ReportSaleCustomerController extends Controller
{
    protected $reportSale;
    protected $reportSaleCustomer;
    public function __construct(
        ReportSaleRepositoryInterface $reportSale,
        ReportSaleCustomerRepositoryInterface $reportSaleCustomer
    ) {
        $this->reportSale = $reportSale;
        $this->reportSaleCustomer = $reportSaleCustomer;
    }

    public function index()
    {
        $dataOption = $this->reportSaleCustomer->getOption();
        return view('report-sale::report-sale-customer.index', $dataOption);
    }

    public function getTotal(Request $request)
    {
        $dataReturn = $this->reportSaleCustomer->getTotal($request);
        return response()->json($dataReturn);
    }

    public function getChartTotal(Request $request)
    {
        $dataReturn = $this->reportSaleCustomer->getChartTotal($request);
        return response()->json($dataReturn);
    }

    public function getChartTotalOrder(Request $request)
    {
        $dataReturn = $this->reportSaleCustomer->getChartTotalCountOrder($request);
        return response()->json($dataReturn);
    }

    public function getChartTotalByCustomer(Request $request)
    {
        $dataReturn = $this->reportSaleCustomer->getTotalAmountByCustomerGroup($request);
        return response()->json($dataReturn);
    }

    public function getTotalOrdersByCustomer(Request $request)
    {
        $dataReturn = $this->reportSaleCustomer->getTotalOrdersByCustomerGroup($request);
        return response()->json($dataReturn);
    }

    public function getCustomer(Request $request){
        $dataReturn = $this->reportSaleCustomer->getCustomer($request);
        return response()->json($dataReturn);
    }
}