<?php


namespace Modules\Report\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Report\Repository\CampaignOverviewReport\CampaignOverviewReportRepo;
use Modules\Report\Repository\CampaignOverviewReport\CampaignOverviewReportRepoInterface;

class CampaignOverviewReportController extends Controller
{
    protected $campaignOverviewReport;
    public function __construct(CampaignOverviewReportRepoInterface $campaignOverviewReport)
    {
        $this->campaignOverviewReport = $campaignOverviewReport;
    }

    /**
     * View index
     */
    public function indexAction()
    {
        return view('report::campaign-overview-report.index', [
        ]);
    }

    /**
     * filter time, branch, customer group
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        return $this->campaignOverviewReport->filterAction($request->all());
    }
    public function filterIIAction(Request $request)
    {
        return $this->campaignOverviewReport->filterIIAction($request->all());
    }
}