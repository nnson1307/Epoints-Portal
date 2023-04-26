<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\ManagerWork\Http\Controllers;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Modules\ManagerWork\Http\Requests\Remind\RemindStaffNotStartRequest;
use Modules\ManagerWork\Repositories\Report\ReportRepositoryInterface;


class ReportController extends Controller
{
    protected $report;


    public function __construct(
        ReportRepositoryInterface $report
    )
    {
        $this->report = $report;
    }

    /**
     * Trang báo cáo
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function indexAction(Request $request)
    {
        $listBranch = $this->report->getListBranch();
        $listDepartment = $this->report->getListDepartment();
        $listStaff = $this->report->getListStaff();

        $request->session()->forget('filter_report');

        $filter = [
            'dateSelect' => Carbon::now()->startOfMonth()->format('d/m/Y').' - '.Carbon::now()->endOfMonth()->format('d/m/Y'),
            'branch_id' => Auth::user()->branch_id,
            'department_id' => Auth::user()->department_id,
            'sort_key'=>'total_overdue',
            'sort_type'=>'DESC',
        ];

        $request->session()->put('filter_report', $filter);

        $list = $this->report->getListReport($filter);

        if (count($list) != 0) {
            $filter['list_staff_process_id'] = collect(collect($list)->toArray()['data'])->pluck('staff_id');
        }

        $listStatus = $this->report->getListReportStatus($filter);

        $filter['not_list_group'] = [3,4];
        $listStatusActive = $this->report->getListStatusActive($filter);

        return view('manager-work::report.index', [
            'listBranch' => $listBranch,
            'listDepartment' => $listDepartment,
            'listStaff' => $listStaff,
            'filter' => $filter,
            'list' => $list,
            'listStatus' => $listStatus,
            'listStatusActive' => $listStatusActive
        ]);
    }

    protected function filters()
    {
        return [
        ];
    }

    /**
     * Search báo cáo
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request){
        $filter = $request->all();
        $request->session()->put('filter_report', $filter);
        $list = $this->report->getListReport($filter);
        if (count($list) != 0) {
            $filter['list_staff_process_id'] = collect(collect($list)->toArray()['data'])->pluck('staff_id');
        }

        $listStatus = $this->report->getListReportStatus($filter);

        $filter['not_list_group'] = [3,4];
        $listStatusActive = $this->report->getListStatusActive($filter);
        return view('manager-work::report.list', [
                'filter' => $filter,
                'list' => $list,
                'listStatus' => $listStatus,
                'listStatusActive' => $listStatusActive
            ]
        );
    }

//    Export báo cáo
    public function exportAction(Request $request){
        $filter = $request->session()->get('filter_report');
        $list = $this->report->getListReportExport($filter);

        $heading = [
            __('Nhân viên'),
            __('Tổng công việc được giao'),
            __('Tổng thời gian làm việc'),
            __('Hoàn thành đúng tiến độ'),
            __('Hoàn thành quá hạn'),
            __('Chưa hoàn thành'),
            __('Quá hạn'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        foreach ($list as $v) {
            $data [] = [
                $v['full_name'],
                $v['total_process'] == 0 ? '0' : $v['total_process'],
                $v['total_time_work'] == 0 ? '0' : $v['total_time_work'],
                $v['total_completed_schedule'] == 0 ? '0' : $v['total_completed_schedule'],
                $v['total_completed_overdue'] == 0 ? '0' : $v['total_completed_overdue'],
                $v['total_not_completed'] == 0 ? '0' : $v['total_not_completed'],
                $v['total_overdue'] == 0 ? '0' : $v['total_overdue']
            ];
        }

        return Excel::download(new ExportFile($heading, $data), 'report-work.xlsx');
    }

    /**
     * Báo cáo công việc của tôi
     */
    public function myWork(){

        $total = $this->report->getTotalMyWork();
        $totalCreated = $this->report->getTotalCreated();
        $totalApprove = $this->report->getTotalApprove();
        $routeName = Route::currentRouteName();
        $arrayBlock = ['my-work-assign','my-work-list','my-work','my-work-support'];
        $getListBlock = $this->report->getListBlock($routeName,$arrayBlock);

        $listHistory = $this->report->getListHistory();

        return view('manager-work::report.my-work', [
            'mywork' => $total['totalWork'],
            'created' => $totalCreated,
            'support' => $total['totalWork1'],
            'approve'=> $totalApprove,
            'routeName' => $routeName,
            'getListBlock' => $getListBlock,
            'listHistory' => $listHistory['view']

        ]);
    }

    public function getChartMyWork(){
        $dataChart = $this->report->getTotalMyWork();
        return response()->json($dataChart);
    }

    /**
     * Lấy danh sách việc của tôi
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListMyWork(Request $request){
        $param = $request->all();
        $data = $this->report->getListMyWork($param);
        return response()->json($data);
    }

    /**
     * Lấy danh sách công việc tôi giao
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListMyWorkAssign(Request $request){
        $param = $request->all();
        $data = $this->report->getListMyWorkAssign($param);
        return response()->json($data);
    }

    /**
     * Huỷ / Duyệt công việc
     * @param Request $request
     */
    public function workApprove(Request $request){
        $param = $request->all();
        $data = $this->report->workApprove($param);
        return response()->json($data);
    }

    /**
     * Lấy danh sách nhắc nhở
     */
    public function searchRemind(Request $request){
        $param = $request->all();
        $data = $this->report->searchRemind($param);
        return response()->json($data);
    }

    /**
     * Xoá nhắc nhở
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeRemind(Request $request){
        $param = $request->all();
        $data = $this->report->removeRemind($param);
        return response()->json($data);
    }

    /**
     * Hiển thị popup nhắc nhở
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupRemindPopup(Request $request){
        $param = $request->all();
        $data = $this->report->showPopupRemindPopup($param);
        return response()->json($data);
    }

    /**
     * Tạo nhắc nhở
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addRemindWork(RemindStaffNotStartRequest $request){
        $param = $request->all();
        $data = $this->report->addRemindWork($param);
        return response()->json($data);
    }

    public function getListWork(Request $request){

        $listBranch = $this->report->getListBranch();
        $listDepartment = $this->report->getListDepartment();
        $listStaff = $this->report->getListStaff();
        $param = $request->all();
        $filter = $request->session()->get('filter_report');
        if (isset($param['staff_id'])) {
            $filter['staff_id'] = $param['staff_id'];
        }
        if (isset($param['type_work'])) {
            $filter['type_work'] = $param['type_work'];
        }

        if (isset($param['manage_status_id'])) {
            $filter['manage_status_id'] = $param['manage_status_id'];
        }

        $list = $this->report->getListWorkReport($filter);
        $request->session()->forget('staff_id_report');
        $request->session()->push('staff_id_report',$filter['staff_id']);
        $filter['not_list_group'] = [3,4];
        $listStatusActive = $this->report->getListStatusActive($filter);

        return view('manager-work::report.index-work', [
            'listBranch' => $listBranch,
            'listDepartment' => $listDepartment,
            'listStaff' => $listStaff,
            'filter' => $filter,
            'list' => $list,
            'listStatusActive' => $listStatusActive
        ]);
    }

    public function listWork(Request $request){
        $param = $request->all();

        $filter = $request->session()->get('filter_report');
        $staffId = $request->session()->get('staff_id_report');
        $filter = array_merge($filter,$param);
        $filter['staff_id'] = $staffId;
        $list = $this->report->getListWorkReport($filter);

        return view('manager-work::report.list-work', [
                'filter' => $filter,
                'list' => $list
            ]
        );
    }

    /**
     * Cập nhật vị trí block
     */
    public function myWorkUpdateBlock(Request $request){
        $param = $request->all();
        $data = $this->report->myWorkUpdateBlock($param);
        return response()->json($data);
    }

    /**
     * Hiển thị danh sách công việc của tôi ở view Công việc của tôi
     */
    public function tableMyWork(Request $request){
        $param = $request->all();
        $data = $this->report->viewReportTableMyWork($param);
        return response()->json($data);
    }

    /**
     * Hiển thị danh sách công việc của tôi ở view Công việc của tôi
     */
    public function tableWorkSupport(Request $request){
        $param = $request->all();
        $data = $this->report->viewReportTableWorkSupport($param);
        return response()->json($data);
    }
}