<?php


namespace Modules\Report\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Report\Repository\PurchaseByHour\PurchaseByHourRepoInterface;

class ReportPurchaseByHourController extends Controller
{
    protected $reportPurchase;

    public function __construct(PurchaseByHourRepoInterface $reportPurchase)
    {
        $this->reportPurchase = $reportPurchase;
    }

    /**
     * View báo cáo tỉ lệ mua hàng theo sản phẩm
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        return view('report::purchase-by-hour.index');
    }

    /**
     * Load chart báo cáo tỉ lệ mua hàng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadChartAction(Request $request)
    {
        $data = $this->reportPurchase->loadChart($request->all());
        return response()->json($data);
    }
}