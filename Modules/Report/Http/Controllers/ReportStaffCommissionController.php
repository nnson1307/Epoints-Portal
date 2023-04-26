<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Admin\Models\StaffTable;
use Modules\Report\Repository\StaffCommission\StaffCommissionRepoInterface;

class
ReportStaffCommissionController extends Controller
{
    protected $staffCommission;
    public function __construct(StaffCommissionRepoInterface $staffCommission)
    {
        $this->staffCommission = $staffCommission;
    }

    /**
     * View index
     */
    public function indexAction()
    {
        $mStaff = new StaffTable();
        $optionStaff = $mStaff->getOption();
        return view('report::staff-commission.index', [
            'staff' => $optionStaff
        ]);
    }

    /**
     * filter time, number staff cho biểu đồ
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        return $this->staffCommission->filterAction($request->all());
    }
    /**
     * Danh sách chi tiết của chart hoa hồng nhân viên
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->staffCommission->listDetail($request->all());

        return view('report::staff-commission.list-detail', [
            'LIST' => $data['list'],
            'page' => $request->page
        ]);
    }
    /**
     * Export excel chi tiết hoa hồng nhân viên
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetail(Request $request)
    {
        return $this->staffCommission->exportDetail($request->all());
    }

    /**
     * Export excel tổng hoa hồng nhân viên
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotal(Request $request)
    {
        return $this->staffCommission->exportTotal($request->all());
    }
}