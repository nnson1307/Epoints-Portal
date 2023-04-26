<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/26/2021
 * Time: 9:41 AM
 */

namespace Modules\Report\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Report\Repository\ServiceStaff\ServiceStaffRepoInterface;

class ReportServiceStaffController extends Controller
{
    protected $serviceStaff;

    public function __construct(
        ServiceStaffRepoInterface $serviceStaff
    ) {
        $this->serviceStaff = $serviceStaff;
    }

    /**
     * View báo cáo doanh thu nhân viên phục vụ
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->serviceStaff->dataViewIndex();

        return view('report::service-staff.index', [
            'branch' => $data['optionBranch'],
            'staff' => $data['optionStaff'],
            'FILTER' => []
        ]);
    }

    /**
     * Load data chart + table chi tiết
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadChartAction(Request $request)
    {
        $data = $this->serviceStaff->dataChart($request->all());

        return response()->json($data);
    }

    /**
     * Table chi tiết đơn hàng
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDetailAction(Request $request)
    {
        $data = $this->serviceStaff->listDetail($request->all());

        return view('report::service-staff.list-detail', [
            'LIST' => $data['list'],
            'page' => $request->page
        ]);
    }

    /**
     * Export excel total
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelTotalAction(Request $request)
    {
        return $this->serviceStaff->exportExcelTotal($request->all());
    }

    /**
     * Export excel chi tiết
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelDetail(Request $request)
    {
        return $this->serviceStaff->exportExcelDetail($request->all());
    }
}