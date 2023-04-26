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

class ReportContractCareController extends Controller
{
    protected $repo;
    public function __construct(ReportContractCareRepoInterface $repo)
    {
        $this->repo = $repo;
    }

    public function indexAction(Request $request)
    {
        $data = $this->repo->getDataViewIndex($request->all());
        return view('contract::report.contract-care', $data);
    }

    /**
     * data chart
     *
     * @param Request $request
     * @return mixed
     */
    public function filterAction(Request $request)
    {
        $data = $this->repo->getChart($request->all());
        return $data;
    }

    /**
     * ds phòng ban tồn tại trong chi nhánh
     *
     * @param Request $request
     * @return mixed
     */
    public function getDepartment(Request $request)
    {
        return $this->repo->getDepartment($request->all());
    }

    /**
     * ds nhân viên thuộc chi nhánh phòng ban
     *
     * @param Request $request
     * @return mixed
     */
    public function getStaff(Request $request)
    {
        return $this->repo->getStaff($request->all());
    }
}