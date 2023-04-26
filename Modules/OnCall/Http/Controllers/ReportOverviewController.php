<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/08/2021
 * Time: 09:22
 */

namespace Modules\OnCall\Http\Controllers;


use Illuminate\Http\Request;
use Modules\OnCall\Repositories\ReportOverview\ReportOverviewRepo;

class ReportOverviewController extends Controller
{
    protected $reportOverview;

    public function __construct(
        ReportOverviewRepo $reportOverview
    ) {
        $this->reportOverview = $reportOverview;
    }

    /**
     * View báo cáo tổng quan
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $getOption = $this->reportOverview->getOption();

        return view('on-call::report-overview.index', $getOption);
    }

    /**
     * Load chart báo cáo tổng quan
     *
     * @param Request $request
     * @return mixed|void
     */
    public function loadChartAction(Request $request)
    {
        return $this->reportOverview->loadChart($request->all());
    }

    /**
     * Load dữ liệu list 1
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function loadList1Action(Request $request)
    {
        return $this->reportOverview->loadList1($request->all());
    }
}