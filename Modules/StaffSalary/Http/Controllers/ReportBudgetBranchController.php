<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/06/2022
 * Time: 10:01
 */

namespace Modules\StaffSalary\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\StaffSalary\Repositories\ReportBudgetBranch\ReportBudgetBranchRepoInterface;

class ReportBudgetBranchController extends Controller
{
    protected $budgetBranch;

    public function __construct(
        ReportBudgetBranchRepoInterface $budgetBranch
    ) {
        $this->budgetBranch = $budgetBranch;
    }

    /**
     * View danh sách ngân sách dự kiến (theo chi nhánh)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $now = Carbon::now();

        return view('staff-salary::report-budget-branch.index', [
            'FILTER' => $this->filters(),
            'week_in_year' =>  $now->weeksInYear
        ]);
    }

    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filters()
    {
        //Lấy data filter
        $data = $this->budgetBranch->getDataFilter();

        //Chi nhánh
        $groupBranch = (['' => __('Chọn chi nhánh')]) + $data['optionBranch'];

        return [
            'branch_id' => [
                'data' => $groupBranch
            ]
        ];
    }

    /**
     * Ajax filter, phân trang ngân sách dự kiến (theo chi nhánh)
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'date_type', 'date_object', 'branch_id']);

        //Lấy ds ngân sách dự kiến (theo chi nhánh)
        $getData = $this->budgetBranch->getList($filter);

        return view('staff-salary::report-budget-branch.list', [
            'LIST' => $getData['list'],
            'page' => $filter['page']
        ]);
    }


     /**
     * Ajax filter, chart report time
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listChartAction(Request $request)
    {
        
        $filter = $request->only(['date_type', 'date_object', 'branch_id']);

        //Lấy ds ngân sách dự kiến (theo chi nhánh)
        $dataReturn = $this->budgetBranch->getListChart($filter);

        return response()->json($dataReturn);
    }

    /**
     * tab report list
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTabReportListAction(Request $request)
    {
       
        $now = Carbon::now();
        $html = \View::make('staff-salary::report-budget-branch.append.report-budget-list-branch',[
            'FILTER' => $this->filters(),
            'week_in_year' =>  $now->weeksInYear
        ])->render();
        return response()->json([
            'html' => $html
        ]);
    }

     /**
     * tab report chart
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTabReportchartAction(Request $request)
    {
          //Chi nhánh
        $data = $this->budgetBranch->getDataFilter();
        $groupBranch = $data['optionBranch'];
        $now = Carbon::now();
        $html = \View::make('staff-salary::report-budget-branch.append.report-budget-chart-branch',[
            'FILTER' => $this->filters(),
            'week_in_year' =>  $now->weeksInYear,
            'optionBranch' => $groupBranch
        ])->render();
        return response()->json([
            'html' => $html
        ]);
    }
}