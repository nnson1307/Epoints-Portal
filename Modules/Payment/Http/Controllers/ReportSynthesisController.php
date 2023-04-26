<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Payment\Repositories\ReportSynthesis\ReportSynthesisRepoInterface;

class ReportSynthesisController extends Controller
{
    protected $reportSynthesis;
    public function __construct(ReportSynthesisRepoInterface $reportSynthesis)
    {
        $this->reportSynthesis = $reportSynthesis;
    }

    public function index()
    {
        $data = $this->reportSynthesis->dataViewIndex();
        return view('payment::report-synthesis.index', $data);
    }

    /**
     * Filter biểu đồ
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        return $this->reportSynthesis->filterAction($request->all());
    }
}