<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Report\Repository\StatisticBranch\StatisticBranchRepoInterface;

class StatisticBranchController extends Controller
{
    protected $statisticBranch;
    public function __construct(StatisticBranchRepoInterface $statisticBranch)
    {
        $this->statisticBranch = $statisticBranch;
    }

    /**
     * view index
     */
    public function indexAction()
    {
        $data = $this->statisticBranch->dataViewIndex();
        return view('report::statistics.by-branch.index', [
            'branch' => $data['optionBranch']
        ]);
    }

    /**
     * filter theo thời gian, chi nhánh
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        $input = $request->all();
        return $this->statisticBranch->filterAction($input);
    }
    /**
 * Danh sách chi tiết của chart by statistics branch
 *
 * @param Request $request
 * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
 */
    public function listDetailAction(Request $request)
    {
        $data = $this->statisticBranch->listDetail($request->all());

        return view('report::statistics.by-branch.list-detail', [
            'LIST' => $data['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Export total list detail statistics branch
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotalAction(Request $request)
    {
        return $this->statisticBranch->exportExcelTotal($request->all());
    }

    /**
     * Export detail list statistics branch
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetailAction(Request $request)
    {
        return $this->statisticBranch->exportExcelDetail($request->all());
    }
}