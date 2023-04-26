<?php

namespace Modules\ReportSale\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ReportSale\Repositories\ReportStaff\ReportSaleStaffRepoInterface;

class ReportSaleStaffController extends Controller
{
    protected $reportSale;
    public function __construct(
        ReportSaleStaffRepoInterface $reportSale
    ) {
        $this->reportSale = $reportSale;
    }

    public function index()
    {
        $dataOption = $this->reportSale->getOption();
        return view('report-sale::report-sale-staff.index',$dataOption);
    }

    public function getTotal(Request $request)
    {
        $dataReturn = $this->reportSale->getTotal($request);
        return response()->json($dataReturn);
    }
    public function getChartTotal(Request $request)
    {
        $dataReturn = $this->reportSale->getChartTotal($request);
        return response()->json($dataReturn);
    }

    public function getChartTotalOrder(Request $request)
    {
        $dataReturn = $this->reportSale->getChartTotalCountOrder($request);
        return response()->json($dataReturn);
    }

    public function getChartTotalByStaff(Request $request)
    {
        $dataReturn = $this->reportSale->getTotalAmountByStaff($request);
        return response()->json($dataReturn);
    }

    public function getTotalOrdersByStaff(Request $request)
    {
        $dataReturn = $this->reportSale->getTotalOrdersByStaff($request);
        return response()->json($dataReturn);
    }
}