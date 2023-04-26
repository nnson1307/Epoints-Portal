<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/26/2021
 * Time: 9:21 AM
 * @author nhandt
 */


namespace Modules\Contract\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Contract\Repositories\ReportContractDetail\ReportContractDetailRepoInterface;

class ReportContractDetailController extends Controller
{
    protected $repo;
    public function __construct(ReportContractDetailRepoInterface $repo)
    {
        $this->repo = $repo;
    }

    public function indexAction(Request $request)
    {
        $data = $this->repo->getDataViewIndex($request->all());
        return view('contract::report.contract-detail.index', $data);
    }

    public function listAction(Request $request)
    {
        $filter = $request->all();
        $list = $this->repo->getListData($filter);
        return view('contract::report.contract-detail.list', [
            'LIST' => $list,
            'page' => $filter['page']
        ]);
    }

    public function exportExcel(Request $request)
    {
        return $this->repo->exportExcel($request->all());
    }
}