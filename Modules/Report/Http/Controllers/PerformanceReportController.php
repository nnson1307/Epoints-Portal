<?php


namespace Modules\Report\Http\Controllers;


use Illuminate\Http\Request;
use Modules\CustomerLead\Models\BranchTable;
use Modules\CustomerLead\Models\DepartmentTable;
use Modules\CustomerLead\Models\StaffsTable;
use Modules\Report\Repository\CampaignOverviewReport\CampaignOverviewReportRepo;
use Modules\Report\Repository\CampaignOverviewReport\CampaignOverviewReportRepoInterface;
use Modules\Report\Repository\PerformanceReport\PerformanceReportRepoInterface;

class PerformanceReportController extends Controller
{
    protected $performanceReportReport;
    public function __construct(PerformanceReportRepoInterface $performanceReportReport)
    {
        $this->performanceReportReport = $performanceReportReport;
    }

    /**
     * View index
     */
    public function indexAction()
    {
        $mDepartment = new DepartmentTable();
        $mBranch = new BranchTable();
        $mStaff = new StaffsTable();
        $optionDepartment = $mDepartment->getOption();
        $optionBranches = $mBranch->getBranchOption();
        $optionStaffs = $mStaff->getListStaff();
        return view('report::performance-report.index', [
            'optionDepartment' => $optionDepartment,
            'optionBranches' => $optionBranches,
            'optionStaffs' => $optionStaffs,
        ]);
    }
    public function filterStaff(Request $request)
    {
        $mStaff = new StaffsTable();
        $optionStaffs = $mStaff->getListStaffByFilter($request->all());
        return response()->json([
            'optionStaffs' => $optionStaffs
        ]);
    }

    public function filterAction(Request $request)
    {
        return $this->performanceReportReport->filterAction($request->all());
    }
}