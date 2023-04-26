<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 03/08/2021
 * Time: 10:58
 */

namespace Modules\OnCall\Http\Controllers;


use Illuminate\Http\Request;
use Modules\OnCall\Repositories\ReportStaff\ReportStaffRepoInterface;

class ReportStaffController extends Controller
{
    protected $reportStaff;

    public function __construct(
        ReportStaffRepoInterface $reportStaff
    ) {
        $this->reportStaff = $reportStaff;
    }

    /**
     * View báo cáo nhân viên
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $getOption = $this->reportStaff->getOption();

        return view('on-call::report-staff.index', $getOption);
    }

    /**
     * Load dữ liệu báo cáo
     *
     * @param Request $request
     * @return mixed
     */
    public function loadChartAction(Request $request)
    {
        return $this->reportStaff->loadChart($request->all());
    }

    /**
     * Load dữ liệu list 1
     *
     * @param Request $request
     * @return mixed
     */
    public function loadList1Action(Request $request)
    {
        return $this->reportStaff->loadList1($request->all());
    }
}