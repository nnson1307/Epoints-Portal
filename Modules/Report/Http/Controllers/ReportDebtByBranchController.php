<?php


namespace Modules\Report\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Report\Repository\DebtByBranch\DebtByBranchRepoInterface;

class ReportDebtByBranchController extends Controller
{
    protected $debtByBranch;
    public function __construct(DebtByBranchRepoInterface $debtByBranch)
    {
        $this->debtByBranch = $debtByBranch;
    }

    /**
     * View index báo cáo công nợ theo chi nhánh
     */
    public function indexAction()
    {
        $data = $this->debtByBranch->dataViewIndex();
        return view('report::debt-by-branch.index', [
            'branch' => $data['optionBranch']
        ]);
    }

    /**
     * filter time, branch,
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        return $this->debtByBranch->filterAction($request->all());
    }
    /**
     * Danh sách chi tiết của chart debt By Branch
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->debtByBranch->listDetail($request->all());

        return view('report::debt-by-branch.list-detail', [
            'LIST' => $data['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Export total list detail debt By Branch
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotalAction(Request $request)
    {
        return $this->debtByBranch->exportExcelTotal($request->all());
    }

    /**
     * Export detail list debt By Branch
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetail(Request $request)
    {
        return $this->debtByBranch->exportExcelDetail($request->all());
    }
}