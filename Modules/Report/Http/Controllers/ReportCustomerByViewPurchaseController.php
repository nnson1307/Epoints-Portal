<?php


namespace Modules\Report\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Report\Repository\CustomerByViewPurchase\CustomerByViewPurchaseRepoInterface;

class ReportCustomerByViewPurchaseController extends Controller
{
    protected $reportCustomer;

    public function __construct(CustomerByViewPurchaseRepoInterface $reportCustomer)
    {
        $this->reportCustomer = $reportCustomer;
    }

    /**
     * View báo cáo khách hàng theo lượt mua hay lượt xem
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->reportCustomer->dataView();
        return view('report::customer-by-view-purchase.index', $data);
    }

    /**
     * Load chart báo cáo khách hàng theo lượt mua hay lượt xem
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadChartAction(Request $request)
    {
        $data = $this->reportCustomer->loadChart($request->all());
        return response()->json($data);
    }

    /**
     * Xuất dữ liệu đã filter ra excel
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcel(Request $request)
    {
        return $this->reportCustomer->exportExcel($request->all());
    }
}