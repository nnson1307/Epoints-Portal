<?php


namespace Modules\Report\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Report\Repository\RevenueByStaff\RevenueByStaffRepoInterface;

class ReportRevenueByStaffController extends Controller
{
    protected $revenueByStaff;
    public function __construct(RevenueByStaffRepoInterface $revenueByStaff)
    {
        $this->revenueByStaff = $revenueByStaff;
    }

    /**
     * View index
     */
    public function indexAction()
    {
        $data = $this->revenueByStaff->dataViewIndex();
        return view('report::revenue-by-staff.index', [
            'branch' => $data['optionBranch'],
            'staff' => $data['optionStaff']
        ]);
    }

    /**
     * filter time, branch, number staff
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        return $this->revenueByStaff->filterAction($request->all());
    }
    /**
     * Danh sách chi tiết của chart by staff
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->revenueByStaff->listDetail($request->all());

        return view('report::revenue-by-staff.list-detail', [
            'LIST' => $data['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Export total list detail staff
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotalAction(Request $request)
    {
        return $this->revenueByStaff->exportExcelTotal($request->all());
    }

    /**
     * Export detail list staff
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetail(Request $request)
    {
        return $this->revenueByStaff->exportExcelDetail($request->all());
    }
}