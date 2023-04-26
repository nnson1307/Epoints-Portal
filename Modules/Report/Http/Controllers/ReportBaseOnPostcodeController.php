<?php


namespace Modules\Report\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Report\Repository\BaseOnPostcode\BaseOnPostcodeRepoInterface;

class ReportBaseOnPostcodeController extends Controller
{
    protected $reportPostcode;

    public function __construct(BaseOnPostcodeRepoInterface $reportPostcode)
    {
        $this->reportPostcode = $reportPostcode;
    }

    /**
     * View báo cáo dựa trên postcode
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->reportPostcode->dataView();
        return view('report::base-on-postcode.index', $data);
    }

    /**
     * Load chart báo cáo tỉ lệ mua hàng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadChartAction(Request $request)
    {
        $data = $this->reportPostcode->loadChart($request->all());
        return response()->json($data);
    }
}