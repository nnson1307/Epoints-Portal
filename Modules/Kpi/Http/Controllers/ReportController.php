<?php

namespace Modules\Kpi\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Kpi\Repositories\Report\ReportRepoInterface;

class ReportController extends Controller
{
    protected $report;

    public function __construct(
        ReportRepoInterface $report
    ) {
        $this->report = $report;
    }

    /**
     * Lấy dữ liệu view báo cáo kpi
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        //Lấy option chi nhánh
        $optionBranch = $this->report->getOptionBranch();

        return view('kpi::report.index', [
            'optionBranch' => $optionBranch
        ]);
    }

    /**
     * Thay đổi chi nhánh
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeBranchAction(Request $request)
    {
        //Lấy option phòng ban
        $optionDepartment = $this->report->getOptionDepartment($request->branch_id);

        return response()->json([
            'optionDepartment' => $optionDepartment
        ]);
    }

    /**
     * Thay đổi phòng ban
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeDepartmentAction(Request $request)
    {
        //Lấy option nhóm
        $optionTeam = $this->report->getOptionTeam($request->department_id);

        return response()->json([
            'optionTeam' => $optionTeam
        ]);
    }

    /**
     * Load dữ liệu báo cáo kpi
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadDataAction(Request $request)
    {
        $data = $this->report->loadData($request->all());

        return response()->json($data);
    }
}