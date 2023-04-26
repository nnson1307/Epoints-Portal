<?php

namespace Modules\Kpi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kpi\Repositories\Criteria\KpiCriteriaRepoInterface;
use Modules\Kpi\Repositories\Report\_ReportRepoInterface;

/**
 * class KpiController
 * @author HaoNMN
 * @since Jun 2022
 */
class _ReportController extends Controller
{
    protected $reportRepo;


    public function __construct(_ReportRepoInterface $reportRepo)
    {
        $this->reportRepo = $reportRepo;
    }

    /**
     * Giao diện báo cáo chi tiết
     */
    public function index(){
        $listBranch = $this->reportRepo->getlistBranch();
//        $listDepartment = $this->reportRepo->getListDepartment();
//        $listDepart = [];
//        if (isset($listDepartment['list'])){
//            $listDepart = collect($listDepartment['list'])->pluck('department_id');
//        }
        return view('kpi::report.index', [
            'listBranch' => $listBranch,
            'listDepartment' => [],
            'listStaff' => []
        ]);
    }

    /*
     * Thay đổi chi nhánh
     */
    public function changeBranch(Request $request){
        $param = $request->all();
        $data = $this->reportRepo->changeBranch($param);
        return \response()->json($data);
    }

    /*
     * Thay đổi phòng ban
     */
    public function changeDepartment(Request $request){
        $param = $request->all();
        $data = $this->reportRepo->changeDepartment($param);
        return \response()->json($data);
    }

    /**
     * Lấy data chart và table
     * @param Request $request
     */
    public function showChartTable(Request $request){
        $param = $request->all();
        $data = $this->reportRepo->showChartTable($param);
        return \response()->json($data);
    }

    /**
     * Báo cáo hiệu quả ngân sách theo tháng
     * @param Request $request
     */
    public function budgetEfficiency(){
        $listDepartment = $this->reportRepo->getDepartment();

        return view('kpi::report.budget-efficiency.index', [
            'listDepartment' => $listDepartment,
        ]);
    }

    /**
     * Tìm kiếm theo tháng
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchMonth(Request $request){
        $param = $request->all();
        $data = $this->reportRepo->searchMonth($param);
        return \response()->json($data);
    }

    /**
     * Tìm kiếm theo tuần
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchWeek(Request $request){
        $param = $request->all();
        $data = $this->reportRepo->searchWeek($param);
        return \response()->json($data);
    }

    /**
     * Tìm kiếm theo ngày
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchDay(Request $request){
        $param = $request->all();
        $data = $this->reportRepo->searchDay($param);
        return \response()->json($data);
    }

}
