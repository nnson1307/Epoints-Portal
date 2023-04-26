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
use Carbon\Carbon;

class ReportSaleController extends Controller
{
    protected $reportSale;
    public function __construct(
        ReportSaleRepositoryInterface $reportSale
    ) {
        $this->reportSale = $reportSale;
    }

    public function index()
    {
        $dataOption = $this->reportSale->getOption();
        return view('report-sale::report-sale.index',$dataOption);
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

    public function getChartTotalByBranch(Request $request)
    {
        $dataReturn = $this->reportSale->getTotalAmountByBranch($request);
        return response()->json($dataReturn);
    }

    public function getTotalOrdersByBranch(Request $request)
    {
        $dataReturn = $this->reportSale->getTotalOrdersByBranch($request);
        return response()->json($dataReturn);
    }

    /**
     * Show modal add holiday
     *
     * @return mixed
     */
    public function showModalListOrders(Request $request)
    {
        // $data = $this->reportSale->getList($request);
        $html = \View::make('report-sale::report-sale.popup.popup-orders', [
            // 'LIST' =>  $data,
            'time' => $request['time'],
            'branch' => $request['branch'],
            'orderType' => $request['order_type']
        ])->render();
        return response()->json([
            'html' => $html
        ]);
    }

    
    public function listOrdersAction(Request $request){
        $filter = $request->only(['page', 'display', 'search', 'time_search' , 'branch_search', 'order_type']);
        $data = $this->reportSale->getList($filter);
        return view('report-sale::report-sale.popup.list-orders', [
            'LIST' => $data,
            'page' => $filter['page']
        ]);
    }

}