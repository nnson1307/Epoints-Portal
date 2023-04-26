<?php


namespace Modules\Report\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Report\Repository\RevenueByBranch\RevenueByBranchRepoInterface;

class ReportRevenueByBranchController extends Controller
{
    protected $revenueByBranch;
    public function __construct(RevenueByBranchRepoInterface $revenueByBranch)
    {
        $this->revenueByBranch = $revenueByBranch;
    }

    /**
     * View index
     */
    public function indexAction()
    {
        $data = $this->revenueByBranch->dataViewIndex();
        return view('report::revenue-by-branch.index', [
            'branch' => $data['optionBranch'],
            'customerGroup' => $data['optionCustomerGroup']
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
        return $this->revenueByBranch->filterAction($request->all());
    }

    /**
     * Danh sách chi tiết của chart by branch
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->revenueByBranch->listDetail($request->all());

        return view('report::revenue-by-branch.list-detail-branch', [
            'LIST' => $data['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Export total list detail branch
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotalAction(Request $request)
    {
        return $this->revenueByBranch->exportExcelTotal($request->all());
    }

    /**
     * Export detail list branch
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetail(Request $request)
    {
        return $this->revenueByBranch->exportExcelDetail($request->all());
    }
}