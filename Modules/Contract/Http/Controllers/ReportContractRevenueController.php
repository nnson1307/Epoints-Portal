<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/23/2021
 * Time: 10:37 AM
 * @author nhandt
 */


namespace Modules\Contract\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Contract\Repositories\ReportContractCare\ReportContractCareRepoInterface;
use Modules\Contract\Repositories\ReportContractOverview\ReportContractOverViewRepoInterface;
use Modules\Contract\Repositories\ReportContractRevenue\ReportContractRevenueRepoInterface;

class ReportContractRevenueController extends Controller
{
    protected $repo;
    public function __construct(ReportContractRevenueRepoInterface $repo)
    {
        $this->repo = $repo;
    }

    public function indexAction(Request $request)
    {
        $data = $this->repo->getDataViewIndex($request->all());
        return view('contract::report.contract-revenue.index', $data);
    }

    /**
     * data chart theo filter
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        $data = $this->repo->getChart($request->all());
        return $data;
    }

    public function listAction(Request $request)
    {
        $filter = $request->all();
        $list = $this->repo->getListData($filter);
        return view('contract::report.contract-revenue.list', [
            'LIST' => $list,
            'page' => $filter['page']
        ]);
    }

    public function exportExcel(Request $request)
    {
        return $this->repo->getListDataExport($request->all());
    }

}