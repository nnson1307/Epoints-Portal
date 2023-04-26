<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\ManagerWork\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\ManagerWork\Http\Api\SendNotificationApi;
use Modules\ManagerWork\Http\Requests\Remind\RemindStaffNotStartRequest;
use Modules\ManagerWork\Models\BrandTable;
use Modules\ManagerWork\Models\ManagerHistoryTable;
use Modules\ManagerWork\Models\ManageStatusTable;
use Modules\ManagerWork\Repositories\ManagerWork\ManagerWorkRepository;
use Modules\ManagerWork\Repositories\ManagerWork\ManagerWorkRepositoryInterface;
use Modules\ManagerWork\Repositories\ManageStatus\ManageStatusRepositoryInterface;
use Modules\ManagerWork\Repositories\Report\ReportRepository;
use Modules\ManagerWork\Repositories\StaffOverView\StaffOverViewRepositoryInterface;
use MyCore\Helper\OpensslCrypt;


class StaffOverviewController extends Controller
{
    protected $staffOverView;
    protected $managerWork;
    protected $manageStatus;


    public function __construct(
        StaffOverViewRepositoryInterface $staffOverView,
        ManagerWorkRepositoryInterface $managerWork,
        ManageStatusRepositoryInterface $manageStatus
    )
    {
        $this->staffOverView = $staffOverView;
        $this->managerWork = $managerWork;
        $this->manageStatus = $manageStatus;
    }

    /**
     * Trang tổng quan công việc
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function indexAction(Request $request)
    {
        $rReport= app()->get(ReportRepository::class);
        $filter = [
            'branch_id' => Auth::user()['branch_id'],
            'department_id' => Auth::user()['department_id'],
        ];

        $checkPermission = $this->staffOverView->checkPermission();

        $branchId = Auth::user()['branch_id'];
        if ($checkPermission['isAll'] == 1){
            $branchId = null;
        }

        $departmentId = Auth::user()['department_id'];

        if ($checkPermission['isAll'] == 1 || $checkPermission['isBranch'] == 1){
            $departmentId = null;
        }

        $listBranch = $this->staffOverView->getListBranch($branchId);
        $listDepartment = $this->staffOverView->getListDepartment($departmentId);
        $listProject = $this->staffOverView->getListProjectPermission(Auth::id());
        $routeName = Route::currentRouteName();
        $arrayBlock = ['hot-spot-detection','job-overview','work-progress'];
        $getListBlock = $rReport->getListBlock($routeName,$arrayBlock);
        return view('manager-work::staff-overview.index', [
            'listBranch' => $listBranch,
            'listDepartment' => $listDepartment,
            'listProject' => $listProject,
            'filter' => $filter,
            'routeName' => $routeName,
            'getListBlock' => $getListBlock
        ]);
    }

    /**
     * Search chart
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchChart(Request $request){
        $param = $request->all();
        $searchChart = $this->staffOverView->searchChart($param);
        return response()->json($searchChart);
    }

    /**
     * Phát hiện điểm nóng
     * @param Request $request
     */
    public function hotSpotDetection(Request $request){
        $param = $request->all();
        $hotSpotDetection = $this->staffOverView->hotSpotDetection($param);
        return response()->json($hotSpotDetection);
    }

    /**
     * Tiến độ công việc
     * @param Request $request
     */
    public function priorityWork(Request $request){
        $param = $request->all();
        $priorityWork = $this->staffOverView->priorityWork($param);
        return response()->json($priorityWork);
    }

    /**
     * Hiển thị popup tạo nhắc nhở danh sách nhân viên chưa bắt đầu công việc
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popupListStaffNotStartWork(Request $request){
        $param = $request->all();
        $popupListStaffNotStartWork = $this->staffOverView->popupListStaffNotStartWork($param);
        return response()->json($popupListStaffNotStartWork);
    }

    /**
     * Tạo nhắc nhở cho danh sách nhân viên chưa bắt đầu công việc trong ngày
     * @param Request $request
     */
    public function addRemindListStaffNotStart(RemindStaffNotStartRequest $request){
        $param = $request->all();
        $addRemindListStaffNotStart = $this->staffOverView->addRemindListStaffNotStart($param);
        return response()->json($addRemindListStaffNotStart);
    }

    /**
     * Popup tạo nhắc nhở công việc
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popupListWorkOverdue(Request $request){
        $param = $request->all();
        $popupListWorkOverdue = $this->staffOverView->popupListWorkOverdue($param);
        return response()->json($popupListWorkOverdue);
    }

    /**
     * Tạo nhắc nhở cho danh sách công việc
     * @param Request $request
     */
    public function addRemindWorkOverdue(RemindStaffNotStartRequest $request){
        $param = $request->all();
        $addRemindWorkOverdue = $this->staffOverView->addRemindWorkOverdue($param);
        return response()->json($addRemindWorkOverdue);
    }

    public function popupStatus(Request $request)
    {
        $detail = $this->managerWork->getDetail($request->id)->toArray();
        $manageStatusList = $this->manageStatus->getListStatus($detail);
        $html = view('manager-work::staff-overview.popup.status', [
            'detail' => $detail,
            'listStatus' => $manageStatusList
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function popupProcess(Request $request)
    {
        $detail = $this->managerWork->getDetail($request->id)->toArray();
        $html = view('manager-work::staff-overview.popup.process', [
            'detail' => $detail,
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function popupDate(Request $request)
    {
        $detail = $this->managerWork->getDetail($request->id)->toArray();
        $html = view('manager-work::staff-overview.popup.date', [
            'detail' => $detail,
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function changeStatus(Request $request){
        $sendNoti = new SendNotificationApi();
        $this->createdHistory($request->id,'status',$request->status);
        $detailOld = $this->managerWork->getDetail($request->id);

        if ($request->status == 3){
            $this->managerWork->edit([
                'manage_status_id' => $request->status,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ], $request->id);

            $dataNoti = [
                'key' => 'work_finish',
                'object_id' => $request->id,
            ];

        } else {
            if ($request->status == 6){
                $this->managerWork->edit([
                    'manage_status_id' => $request->status,
                    'progress' => 100,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ], $request->id);
            } else {
                $this->managerWork->edit([
                    'manage_status_id' => $request->status,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ], $request->id);
            }


            $dataNoti = [
                'key' => 'work_update_status',
                'object_id' => $request->id,
            ];
        }

        $rManageWork = app()->get(ManagerWorkRepository::class);
        $detailOld = $this->managerWork->getDetail($request->id);
        $rManageWork->updateProgressParentTask($detailOld);

        $sendNoti->sendStaffNotification($dataNoti);

        return response()->json(1);
    }

    public function changeProcess(Request $request){
        $this->createdHistory($request->id,'progress',$request->progress);
        $this->managerWork->edit([
            'progress' => $request->progress,
            'updated_at' => Carbon::now(),
            'updated_by' => Auth::id()
        ], $request->id);

        $rManageWork = app()->get(ManagerWorkRepository::class);
        $detailOld = $this->managerWork->getDetail($request->id);
        $rManageWork->updateProgressParentTask($detailOld);

        return response()->json(1);
    }

    public function changeDate(Request $request){
        $date = Carbon::createFromFormat('d/m/Y H:i', $request->date)->format('Y-m-d H:i:00');
        $this->createdHistory($request->id,'date_end',$date);
        $this->managerWork->edit([
            'date_end' => $date,
            'updated_at' => Carbon::now(),
            'updated_by' => Auth::id()
        ], $request->id);
        return response()->json(1);
    }

    public function createdHistory($manage_work_id,$type,$data){
        $detailWork = $this->managerWork->getItem($manage_work_id);
        $mManageHistory = app()->get(ManagerHistoryTable::class);

        $message = '';

        if ($type == 'status' && $data != $detailWork['manage_status_id']){
            $mManageStatus = app()->get(ManageStatusTable::class);
            $oldStatus = $mManageStatus->getItem($detailWork['manage_status_id']);
            $newStatus = $mManageStatus->getItem($data);

            $message = __(' đã cập nhật trạng thái công việc ').$detailWork['manage_work_title'].__(' từ ').$oldStatus['manage_status_name'].__(' sang ').$newStatus['manage_status_name'];
            if ($data == 6) {
                $this->createdHistory($manage_work_id,'progress',100);
            }
        } else if($type == 'progress' && $data != $detailWork['progress']){
            $message = __(' đã cập nhật tiến độ công việc ').$detailWork['manage_work_title'].__(' từ ').$detailWork['progress'].'%'.__(' sang ').$data.'%';
        } else if ($type == 'date_end' && $data != $detailWork['date_end']) {
            $message = __(' đã cập nhật ngày hết hạn công việc ').$detailWork['manage_work_title'].__(' từ ').Carbon::parse($detailWork['date_end'])->format('H:i:s d/m/Y').__(' sang ').Carbon::parse($data)->format('H:i:s d/m/Y');
        }

        if ($message != ''){
            $dataHistory = [
                'manage_work_id' => $manage_work_id,
                'staff_id' => Auth::id(),
                'message' => $message,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];
            $mManageHistory->createdHistory($dataHistory);
        }
    }

    public function iframe(){
        return view('manager-work::staff-overview.iframe', [
        ]);
    }

    /**
     * Hiển thị danh sách công việc theo trạng thái
     */
    public function tableWorkStatus(Request $request){
        $param = $request->all();
        $data = $this->staffOverView->tableWorkStatus($param);
        return response()->json($data);
    }

    /**
     * Hiển thị danh sách công việc theo cấp độ
     */
    public function tableWorkLevel(Request $request){
        $param = $request->all();
        $data = $this->staffOverView->tableWorkLevel($param);
        return response()->json($data);
    }
}
