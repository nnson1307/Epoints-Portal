<?php


namespace Modules\ManagerWork\Repositories\StaffOverView;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\ManagerWork\Models\BranchTable;
use Modules\ManagerWork\Models\DepartmentTable;
use Modules\ManagerWork\Models\ManagerCommentTable;
use Modules\ManagerWork\Models\ManageRedmindTable;
use Modules\ManagerWork\Models\ManagerHistoryTable;
use Modules\ManagerWork\Models\ManagerWorkSupportTable;
use Modules\ManagerWork\Models\ManagerWorkTable;
use Modules\ManagerWork\Models\ManagerWorkTagTable;
use Modules\ManagerWork\Models\ManageStatusTable;
use Modules\ManagerWork\Models\MapRoleGroupStaffTable;
use Modules\ManagerWork\Models\ProjectTable;
use Modules\ManagerWork\Models\StaffsTable;

class StaffOverViewRepository implements StaffOverViewRepositoryInterface
{
    protected $mBranch;
    protected $mDepartment;
    protected $mProject;
    protected $mManageWork;

    public function __construct(BranchTable $mBranch,DepartmentTable $mDepartment,ProjectTable $mProject,ManagerWorkTable $mManageWork)
    {
        $this->mBranch = $mBranch;
        $this->mDepartment = $mDepartment;
        $this->mProject = $mProject;
        $this->mManageWork = $mManageWork;
    }

    /**
     * Lấy danh sách chi nhánh
     * @return mixed|void
     */
    public function getListBranch($branchId = null)
    {
        return $this->mBranch->getAll($branchId);
    }

    /**
     * Lấy danh sách phòng ban
     * @return mixed
     */
    public function getListDepartment($departmentId = null)
    {
        return $this->mDepartment->getAll($departmentId);
    }

    /**
     * Danh sách dự án
     * @return mixed|void
     */
    public function getListProject()
    {
        return $this->mProject->getAll();
    }

    /**
     * lấy danh sách dự án theo quyền user
     * @return mixed|void
     */
    public function getListProjectPermission($userId)
    {
        return $this->mProject->getAllPermission($userId);
    }

    /**
     * Search chart
     * @return mixed|void
     */
    public function searchChart($data)
    {
        try {

            $mManageStatus = new ManageStatusTable();

//            Xử lý tổng công việc theo trạng thái
            $searchStatus = $this->mManageWork->getTotalByStatus($data);
            $getTotalOverdue = $this->mManageWork->getTotalHome(['job_overview' => 1]);

            if (count($searchStatus) != 0){
                $searchStatus = collect($searchStatus)->keyBy('manage_status_id');
            }

            $listStatus = $mManageStatus->getAll();
            $label1 = [];
            $color1 = [];
            $data1 = [];
            $total1 = 0;
            $totalText1 = '';
            $overdue = 0;

            $statusId1 = [];
            $total = 0;

            foreach ($listStatus as $item){
                $label1[] = (isset($searchStatus[$item['manage_status_id']]) ? (int)$searchStatus[$item['manage_status_id']]['total_work'] : 0) .' '.$item['manage_status_name'];
//                $color1[] = isset($searchStatus[$item['manage_status_id']]) ? $item['manage_status_color'] : '#FFF';
                $color1[] = $item['manage_status_color'];
                $data1[] = isset($searchStatus[$item['manage_status_id']]) ? (int)$searchStatus[$item['manage_status_id']]['total_work'] : 0;
                $total1 += isset($searchStatus[$item['manage_status_id']]) ? (int)$searchStatus[$item['manage_status_id']]['total_work'] : 0;
//                $overdue += isset($searchStatus[$item['manage_status_id']]) ? (int)$searchStatus[$item['manage_status_id']]['overdue'] : 0;
                $statusId1[] = $item['manage_status_id'];
            }

            $totalWork = $total1;
            if ($total1 != 0){
                $total1 = $total1.'<br>'.__(' Công việc');
            } else {
                $totalText1 = __('Chưa có công việc nào');
            }

            if ($getTotalOverdue != null && $getTotalOverdue['total_overdue'] != 0){
                $label1[] = $getTotalOverdue['total_overdue'].'/'.$getTotalOverdue['total_work'].__(' công việc quá hạn');
                $color1[] = '#fff';
                $data1[] = 0;
            }

            $view1 = view('manager-work::report.append.append-list-status',[
                'color' => $color1,
                'label' => $label1,
                'statusId' => $statusId1,
                'type' => 'staff-overview1',
                'typePage' => 'not-list',
                'chart_department_id' => isset($data['chart_department_id']) ? $data['chart_department_id'] : '',
                'chart_manage_project_id' => isset($data['chart_manage_project_id']) ? $data['chart_manage_project_id'] : '',
            ])->render();

//            if ($searchStatus['overdue'] != 0){


//            Xử lý tổng công việc theo Mức độ

            $searchPriority = $this->mManageWork->getTotalByPriority($data);

            $label2 = [];
            $data2 = [];
            $total2 = 0;
            $totalText2 = '';

            if ($searchPriority != null) {
                $label2 = [
                    (int)$searchPriority['priority_1'].' '.__('Cao'),
                    (int)$searchPriority['priority_2'].' '.__('Bình thường'),
                    (int)$searchPriority['priority_3'].' '.__('Thấp')
                ];
                $data2[] = (int)$searchPriority['priority_1'];
                $data2[] = (int)$searchPriority['priority_2'];
                $data2[] = (int)$searchPriority['priority_3'];
                $total2 = (int)$searchPriority['priority_1'] + (int)$searchPriority['priority_2'] + (int)$searchPriority['priority_3'];


                $view2 = view('manager-work::report.append.append-list-status',[
                    'color' => ['#E94343', '#FFC000', '#92D050'],
                    'label' => $label2,
                    'statusId' => [0,1,2],
                    'link' => 'none'
                ])->render();


                if ($total2 != 0 ){
                    $total2 = $total2.'<br>'.__(' Công việc');
                } else {
                    $totalText2 = __('Chưa có công việc nào');
                }
            }


            return [
                'error' => false,
                'label1' => $label1,
                'color1' => $color1,
                'data1' => $data1,
                'total1' => $total1,
                'label2' => $label2,
                'data2' => $data2,
                'total2' => $total2,
                'view1' => $view1,
                'view2' => $view2,
                'totalText1' => $totalText1,
                'totalText2' => $totalText2,
            ];
        } catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Tìm kiếm bị lỗi')
            ];
        }
    }

    /**
     * Phát hiện điểm nóng
     * @param $data
     * @return mixed|void
     */
    public function hotSpotDetection($data)
    {
        try{

            $mStaff = new StaffsTable();

            $mManageComment = new ManagerCommentTable();
            $mManageWorkTag = new ManagerWorkTagTable();
            $mManageWorkSupport = new ManagerWorkSupportTable();

//            $listStaffJob = $mStaff->staffNoJob($data);
//
//            if (count($listStaffJob) != 0){
//                $listStaffJob = collect($listStaffJob)->pluck('staff_id');
//            }

//            Danh sách nhân viên chưa có việc làm
//            $list_staff_no_job = $mStaff->getListStaffNoJob($listStaffJob);
//
//            $viewStaffNoJob = view('manager-work::staff-overview.append.list_staff_not_work',[
//                'list_staff_no_job' => $list_staff_no_job
//            ])->render();


//            Danh sách nhân viên chưa bắt đầu công việc trong ngày
            $data['list_staff_no_started_work'] = 1;
            $list_staff_no_started_work = $mStaff->staffNoJob($data);
            $viewStaffNoStartedJob = view('manager-work::staff-overview.append.list_staff_not_work_start_yet',[
                'list_staff_no_started_work' => $list_staff_no_started_work
            ])->render();

//            Lấy danh sách công việc quá hạn

            $data['job_overview'] = 1;
            $data['status_overdue'] = [1,2,5];

            $list_overdue = $this->mManageWork->getListOverdue($data);

            foreach ($list_overdue as $key => $item){
                $list_overdue[$key]['total_message'] = $mManageComment->getTotalCommentByWork($item['manage_work_id']);
                $list_overdue[$key]['tags'] = $mManageWorkTag->getListTagByWork($item['manage_work_id']);
                $list_overdue[$key]['list_staff'] = $mManageWorkSupport->getListStaffByWork($item['manage_work_id']);
            }

            $viewListOverdue = view('manager-work::staff-overview.append.list_work_overdue',[
                'list_overdue' => $list_overdue
            ])->render();

            return [
                'error' => false,
//                'viewStaffNoJob' => $viewStaffNoJob,
                'viewStaffNoStartedJob' => $viewStaffNoStartedJob,
//                'viewListOverdue' => $viewListOverdue
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Tìm kiếm bị lỗi')
            ];
        }
    }

    /**
     * Tiến độ công việc
     * @param $data
     * @return mixed|void
     */
    public function priorityWork($data)
    {
        try{

            $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
            $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59');

            $start = null;
            $end = null;

            $n = 0;
            $check = 0; // Kiểm tra cuối tháng

//            Việc hôm nay

            $data['from_date'] = Carbon::now()->format('Y-m-d 00:00:00');
            $data['to_date'] = Carbon::now()->format('Y-m-d 23:59:59');

            $list[$n] = [
                'date_start' => $data['from_date'],
                'date_end' => $data['to_date'],
                'text_block' => __('Hôm nay'),
                'list' =>  $this->mManageWork->getMyWorkByDate($data),
                'type' => 'staff-overview1',
                'chart_department_id' => isset($data['list_department_id']) ? $data['list_department_id'] : '',
                'chart_manage_project_id' => isset($data['list_manage_project_id']) ? $data['list_manage_project_id'] : '',
                'type-page' => 'not-list',
                'type-search' => 'not_overdue',
            ];

            $list[$n]['list'] = $this->checkList($list[$n]['list']);


//            Việc tuần này
            $n++;
            $data['from_date'] = Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00') >= $startOfMonth ? Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00') : $startOfMonth;
            $data['to_date'] = Carbon::now()->endOfWeek()->format('Y-m-d 23:59:59')  <= $endOfMonth ? Carbon::now()->endOfWeek()->format('Y-m-d 23:59:59') : $endOfMonth;

            if (Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00') >= $startOfMonth){
                $start = $startOfMonth;
                $end = Carbon::now()->startOfWeek()->subDays(1)->format('Y-m-d 23:59:59');
            }

            if (Carbon::now()->endOfWeek()->format('Y-m-d 23:59:59') > $endOfMonth){
                $check = 1;
            }

            $list[$n] = [
                'date_start' => $data['from_date'],
                'date_end' => $data['to_date'],
                'text_block' => __('Tuần này'),
                'list' =>  $this->mManageWork->getMyWorkByDate($data),
                'type' => 'staff-overview1',
                'chart_department_id' => isset($data['list_department_id']) ? $data['list_department_id'] : '',
                'chart_manage_project_id' => isset($data['list_manage_project_id']) ? $data['list_manage_project_id'] : '',
                'type-page' => 'not-list',
                'type-search' => 'not_overdue',
            ];

            $list[$n]['list'] = $this->checkList($list[$n]['list']);


//            Việc tuần sau
            if ($check == 0){
                $n++;
                $data['from_date'] = Carbon::now()->addWeeks(1)->startOfWeek()->format('Y-m-d 00:00:00');
                $data['to_date'] = Carbon::now()->addWeeks(1)->endOfWeek()->format('Y-m-d 23:59:59') <= $endOfMonth ? Carbon::now()->addWeeks(1)->endOfWeek()->format('Y-m-d 23:59:59') : $endOfMonth;

                if (Carbon::now()->addWeeks(1)->endOfWeek()->format('Y-m-d 23:59:59') > $endOfMonth){
                    $check = 1;
                } else {
                    $start = Carbon::now()->addWeeks(1)->addDays(1)->startOfWeek()->format('Y-m-d 00:00:00');
                    $end = $endOfMonth;
                }

                $list[$n] = [
                    'date_start' => $data['from_date'],
                    'date_end' => $data['to_date'],
                    'text_block' => __('Tuần sau'),
                    'list' =>  $this->mManageWork->getMyWorkByDate($data),
                    'type' => 'staff-overview1',
                    'chart_department_id' => isset($data['list_department_id']) ? $data['list_department_id'] : '',
                    'chart_manage_project_id' => isset($data['list_manage_project_id']) ? $data['list_manage_project_id'] : '',
                    'type-page' => 'not-list',
                    'type-search' => 'not_overdue',
                ];

                $list[$n]['list'] = $this->checkList($list[$n]['list']);
            }

//            Việc tuần kế tiếp

//            if ($check == 0){
//                $n++;
//                $data['from_date'] = Carbon::now()->addWeeks(2)->startOfWeek()->format('Y-m-d 00:00:00');
//                $data['to_date'] = Carbon::now()->addWeeks(2)->endOfWeek()->format('Y-m-d 23:59:59') <= $endOfMonth ? Carbon::now()->addWeeks(2)->endOfWeek()->format('Y-m-d 23:59:59') : $endOfMonth;
//
//                if (Carbon::now()->addWeeks(1)->endOfWeek()->format('Y-m-d 23:59:59') > $endOfMonth){
//                    $check = 1;
//                } else {
//                    $start = Carbon::now()->addWeeks(2)->addDays(1)->startOfWeek()->format('Y-m-d 00:00:00');
//                    $end = $endOfMonth;
//                }
//
//                $list[$n] = [
//                    'date_start' => $data['from_date'],
//                    'date_end' => $data['to_date'],
//                    'text_block' => __('Tuần kế tiếp'),
//                    'list' =>  $this->mManageWork->getMyWorkByDate($data),
//                    'type' => 'staff-overview1',
//                    'chart_department_id' => isset($data['list_department_id']) ? $data['list_department_id'] : '',
//                    'chart_manage_project_id' => isset($data['list_manage_project_id']) ? $data['list_manage_project_id'] : '',
//                    'type-page' => 'not-list'
//                ];
//
//                $list[$n]['list'] = $this->checkList($list[$n]['list']);
//            }

//            Khác
//            $n++;
//            $data['from_date'] = $start;
//            $data['to_date'] = $end;
//
//            $list[$n] = [
//                'date_start' => $data['from_date'],
//                'date_end' => $data['to_date'],
//                'text_block' => __('Khác'),
//                'list' =>  $this->mManageWork->getMyWorkByDate($data),
//                'type' => 'staff-overview1',
//                'chart_department_id' => isset($data['list_department_id']) ? $data['list_department_id'] : '',
//                'chart_manage_project_id' => isset($data['list_manage_project_id']) ? $data['list_manage_project_id'] : '',
//                'type-page' => 'not-list'
//            ];
//
//            $list[$n]['list'] = $this->checkList($list[$n]['list']);
//
//            $view = view('manager-work::staff-overview.append.list_priority',[
//                'list' => $list
//            ])->render();

            $mManageStatus = app()->get(ManageStatusTable::class);

            $listStatus = $mManageStatus->getAllStatusNotOverdue();

            if (count($listStatus) != 0){
                $listStatus = collect($listStatus)->pluck('manage_status_id')->toArray();
            }

            $n++;
            $data['from_date'] = null;
            $data['to_date'] = null;
            $data['type'] = 'expired';
            $data['status_overdue'] = [1,2,5];

            $list[$n] = [
                'date_start' => $data['from_date'],
                'date_end' => Carbon::now()->format('Y-m-d H:i:s'),
                'text_block' => __('Quá hạn'),
                'list' =>  $this->mManageWork->getMyWorkByDate($data),
                'type' => 'staff-overview1',
                'chart_department_id' => isset($data['list_department_id']) ? $data['list_department_id'] : '',
                'chart_manage_project_id' => isset($data['list_manage_project_id']) ? $data['list_manage_project_id'] : '',
                'manage_status_id' => $listStatus,
                'type-search' => 'overdue',
                'type-page' => 'not-list'
            ];

            $list[$n]['list'] = $this->checkList($list[$n]['list']);

            $view = view('manager-work::staff-overview.append.list_priority',[
                'list' => $list
            ])->render();

            return [
                'error' => false,
                'view' => $view,
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Tìm kiếm bị lỗi')
            ];
        }
    }

    /**
     * Công việc của tôi chỉnh sửa danh sách công việc
     */
    public function checkList($list){
        try{
            $mManageComment = new ManagerCommentTable();
            $mManageWorkTag = new ManagerWorkTagTable();
            $mManageWorkSupport = new ManagerWorkSupportTable();
            $mManageWork = app()->get(ManagerWorkTable::class);

            foreach ($list as $key => $item) {
                $list[$key]['total_message'] = $mManageComment->getTotalCommentByWork($item['manage_work_id']);
                $list[$key]['tags'] = $mManageWorkTag->getListTagByWork($item['manage_work_id']);
                $list[$key]['list_staff'] = $mManageWorkSupport->getListStaffByWork($item['manage_work_id']);
                if ($item['parent_id'] == null){
                    $list[$key]['is_parent'] = ($mManageWork->getListChildTask($item['manage_work_id']) != 0 ? 1 : 0);
                } else {
                    $list[$key]['is_parent'] = 0;
                }
            }

            return $list;

        }  catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Tìm kiếm bị lỗi')
            ];
        }
    }

    /**
     * Hiển thị popup tạo nhắc nhở danh sách nhân viên chưa bắt đầu công việc
     * @param $data
     * @return mixed|void
     */
    public function popupListStaffNotStartWork($data)
    {
        try {

            if (count($data) == 0){
                return [
                    'error' => true,
                    'message' => __('Vui lòng chọn nhân viên để tạo nhắc nhở')
                ];
            }

            $mStaff = new StaffsTable();

            $staffList = $mStaff->getListStaffByStaff($data['staff_not_start_work']);
            $view = view('manager-work::staff-overview.popup.remind',['staffList' => $staffList,'staffSelect' => json_encode($data['staff_not_start_work'])])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị popup thất bại')
            ];
        }
    }

    /**
     * Tạo nhắc nhở cho danh sách nhân viên chưa bắt đầu công việc trong ngày
     * @param $data
     * @return mixed|void
     */
    public function addRemindListStaffNotStart($data)
    {
        try{
            if (isset($data['time_remind']) && $data['time_remind'] == 'selected'){
                unset($data['time_remind']);
            }
            $mManageRemind = new ManageRedmindTable();
            if (isset($data['time_remind'])){
                $data['time_remind'] = str_replace(',', '', $data['time_remind']);
                $messageError = $this->checkRemind($data);
                if ($messageError != null){
                    return [
                        'error' => true,
                        'message'=> $messageError
                    ];
                }
            }

            $mStaff = app()->get(StaffsTable::class);
            $mManageWork = app()->get(ManagerWorkTable::class);

            $dataRemind = [];

            foreach (json_decode($data['list_staff_not_start_work']) as $item){
                $created_by = $mStaff->getStaffId(Auth::id());
                $staff_id = $mStaff->getStaffId($item);
                if (isset($data['manage_work_id'])){
                    $detailWork = $mManageWork->getDetail($data['manage_work_id']);
                    $title = $created_by['staff_name'].' '.__('managerwork::managerwork.created_remind_work_for',['manage_work_title' => $detailWork['manage_work_title']]).' '.$staff_id['staff_name'];
                } else {
                    $title = $created_by['staff_name'].' '.__('managerwork::managerwork.created_remind_for').' '.$staff_id['staff_name'];
                }

                $dataRemind[] = [
                    'title' => isset($data['title']) ? $data['title'] : $title,
                    'staff_id' => $item,
                    'date_remind' => Carbon::createFromFormat('d/m/Y H:i',$data['date_remind'])->format('Y-m-d H:i:00'),
                    'time' => isset($data['time_remind']) ? $data['time_remind'] : null,
                    'time_type' => isset($data['time_remind']) ? $data['time_type_remind'] : null,
                    'description' => strip_tags($data['description_remind']),
                    'is_sent' => 0,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];
            }

            if (count($dataRemind) != 0){
                $mManageRemind->insertArrayRemind($dataRemind);
            }

            return [
                'error' => false,
                'message' => __('Tạo nhắc nhở thành công')
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Tạo nhắc nhở thất bại')
            ];
        }
    }

    /**
     * Popup tạo nhắc nhở cho công việc
     * @param $data
     * @return mixed|void
     */
    public function popupListWorkOverdue($data)
    {
        try {
            if (count($data) == 0){
                return [
                    'error' => true,
                    'message' => __('Vui lòng chọn công việc để tạo nhắc nhở')
                ];
            }

            $mStaff = new StaffsTable();

            $listWork = $this->mManageWork->getListWorkByWork($data['list_work_overdue']);

            $detailWork = null;
            if (isset($data['manage_work_id'])){
                $detailWork = $this->mManageWork->getDetail($data['manage_work_id']);
            }

            $view = view('manager-work::staff-overview.popup.remind-work',['listWork' => $listWork,'workSelect' => json_encode($listWork),'detailWork' => $detailWork,'data' => $data])->render();
            return [
                'error' => false,
                'view' => $view
            ];

        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị popup thất bại')
            ];
        }
    }

    /**
     * Tạo nhắc nhở cho danh sách công việc
     * @param $data
     * @return mixed|void
     */
    public function addRemindWorkOverdue($data)
    {
        try{
            if (isset($data['time_remind']) && $data['time_remind'] == 'selected'){
                unset($data['time_remind']);
            }

            $mManageRemind = new ManageRedmindTable();
            $mManageHistory = new ManagerHistoryTable();
            if (isset($data['time_remind'])){
                $data['time_remind'] = str_replace(',', '', $data['time_remind']);
                $messageError = $this->checkRemind($data);
                if ($messageError != null){
                    return [
                        'error' => true,
                        'message'=> $messageError
                    ];
                }
            }

            $listWork = collect(json_decode($data['list_work'],true))->pluck('manage_work_id');

            $listDetailWork = $this->mManageWork->getListWorkByWork($listWork);

            $mStaff = app()->get(StaffsTable::class);
            $mManageWork = app()->get(ManagerWorkTable::class);

            $dataRemind = [];
            $dataHistory = [];
            foreach ($listDetailWork as $item){
                $created_by = $mStaff->getStaffId(Auth::id());
                $staff_id = $mStaff->getStaffId($item['processor_id']);
                if (isset($data['title'])) {
                    $title = $data['title'];
                } else {
                    if (isset($data['manage_work_id'])){
                        $detailWork = $mManageWork->getDetail($data['manage_work_id']);
                        $title = $created_by['staff_name'].' '.__('managerwork::managerwork.created_remind_work_for',['manage_work_title' => $detailWork['manage_work_title']]).' '.$staff_id['staff_name'];
                    } else {
                        $title = $created_by['staff_name'].' '.__('managerwork::managerwork.created_remind_for').' '.$staff_id['staff_name'];
                    }
                }


                $dataRemind[] = [
                    'title' => $title,
                    'staff_id' => $item['processor_id'],
                    'date_remind' => Carbon::createFromFormat('d/m/Y H:i',$data['date_remind'])->format('Y-m-d H:i:00'),
                    'time' => isset($data['time_remind']) ? $data['time_remind'] : null,
                    'time_type' => isset($data['time_remind']) ? $data['time_type_remind'] : null,
                    'description' => strip_tags($data['description_remind']),
                    'manage_work_id' => $item['manage_work_id'],
                    'is_sent' => 0,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];
                $dataHistory[] = [
                    'manage_work_id' => $item['manage_work_id'],
                    'staff_id' => Auth::id(),
                    'message' => __(' đã tạo nhắc nhở cho ').$item['staff_name'],
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];
            }

            if (count($dataRemind) != 0){
                $mManageRemind->insertArrayRemind($dataRemind);
                $mManageHistory->createdHistory($dataHistory);
            }

            return [
                'error' => false,
                'message' => __('Tạo nhắc nhở thành công')
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Tạo nhắc nhở thất bại')
            ];
        }
    }

    public function checkRemind($data){
        $messageError = __('Thời gian trước nhắc nhở cho thời gian nhắc đã qua vui lòng chọn thời gian khác');
        if ($data['time_type_remind'] == 'm'){
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i',$data['date_remind'])->subMinutes($data['time_remind'])){
                return $messageError;
            }
        } else if($data['time_type_remind'] == 'h'){
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i',$data['date_remind'])->subHours($data['time_remind'])){
                return $messageError;
            }
        } else if($data['time_type_remind'] == 'd'){
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i',$data['date_remind'])->subDays($data['time_remind'])){
                return $messageError;
            }
        } else if($data['time_type_remind'] == 'w'){
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i',$data['date_remind'])->subWeeks($data['time_remind'])){
                return $messageError;
            }
        }

        return null;
    }

    /**
     * Danh sách công việc theo cấp độ
     * @param $data
     * @return mixed|void
     */
    public function tableWorkLevel($data)
    {
        $data['perpage'] = 10;
        $searchPriority = $this->mManageWork->getTotalByPriorityPagination($data);
        $view = view('manager-work::staff-overview.append.append-table-work-level',[
            'listWorkLevel' => $searchPriority
        ])->render();

        return [
            'error' => false,
            'view' => $view
        ];
    }

    /**
     * Danh sách công việc theo trạng thái
     * @param $data
     * @return mixed|void
     */
    public function tableWorkStatus($data)
    {
        $data['perpage'] = 10;
        $searchStatus = $this->mManageWork->getTotalByStatusPagination($data);
        $view = view('manager-work::staff-overview.append.append-table-work-status',[
            'listWorkStatus' => $searchStatus
        ])->render();

        return [
            'error' => false,
            'view' => $view
        ];
    }

    /**
     * Check quyền dữ liệu
     * @return mixed|void
     */
    public function checkPermission()
    {
        $mManageWork = app()->get(MapRoleGroupStaffTable::class);
        $checkRole = $mManageWork->checkRoleWork(Auth::id());
        $isAll = $isBranch = $isDepartment = $isOwn = 0;
        foreach ($checkRole as $role) {
            $role = (array)$role;
            if ($role['is_all']) {
                $isAll = 1;
            }

            if ($role['is_branch']) {
                $isBranch = 1;
            }

            if ($role['is_department']) {
                $isDepartment = 1;
            }

            if ($role['is_own']) {
                $isOwn = 1;
            }
        }

        if ($isAll == 1) {
            $isBranch = $isDepartment = $isOwn = 0;
        } else if ($isBranch == 1) {
            $isDepartment = $isOwn = 0;
        } else if ($isDepartment == 1) {
            $isOwn = 0;
        }
        return [
            'isAll' => $isAll,
            'isBranch' => $isBranch,
            'isDepartment' => $isDepartment,
            'isOwn' => $isOwn
        ];
    }
}