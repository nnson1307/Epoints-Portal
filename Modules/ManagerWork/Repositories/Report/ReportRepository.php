<?php


namespace Modules\ManagerWork\Repositories\Report;


use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\ManagerProject\Repositories\ManageHistory\ManageHistoryRepoInterface;
use Modules\ManagerWork\Http\Api\SendNotificationApi;
use Modules\ManagerWork\Models\BranchTable;
use Modules\ManagerWork\Models\DepartmentTable;
use Modules\ManagerWork\Models\ManageBlockPageTable;
use Modules\ManagerWork\Models\ManagerCommentTable;
use Modules\ManagerWork\Models\ManageRedmindTable;
use Modules\ManagerWork\Models\ManagerHistoryTable;
use Modules\ManagerWork\Models\ManagerWorkSupportTable;
use Modules\ManagerWork\Models\ManagerWorkTable;
use Modules\ManagerWork\Models\ManagerWorkTagTable;
use Modules\ManagerWork\Models\ManageStatusConfigTable;
use Modules\ManagerWork\Models\ManageStatusTable;
use Modules\ManagerWork\Models\StaffsTable;
use Modules\ManagerWork\Repositories\ManagerWork\ManagerWorkRepository;

class ReportRepository implements ReportRepositoryInterface
{

    protected $mBranch;
    protected $mManageWork;
    protected $mDepartment;
    protected $mManageHistory;
    protected $mStaff;

    const incomplete = 5;
    const complete = 6;

    public function __construct(BranchTable $mBranch,DepartmentTable $mDepartment,StaffsTable $mStaff,ManagerWorkTable $mManageWork,ManagerHistoryTable $mManageHistory)
    {
        $this->mBranch = $mBranch;
        $this->mDepartment = $mDepartment;
        $this->mStaff = $mStaff;
        $this->mManageWork = $mManageWork;
        $this->mManageHistory = $mManageHistory;
    }

    /**
     * Lấy danh sách chi nhánh
     * @return mixed|void
     */
    public function getListBranch()
    {
        return $this->mBranch->getAll();
    }

    /**
     * Danh sách phòng ban
     * @return mixed|void
     */
    public function getListDepartment()
    {
        return $this->mDepartment->getAll();
    }

    /**
     * lấy danh sách nhân viên
     * @return mixed|void
     */
    public function getListStaff()
    {
        return $this->mStaff->getAll();
    }

    public function getListReport($data)
    {
        $data['report_view'] = 1;
        return $this->mManageWork->getList($data);
    }

    /**
     * Lấy danh sách báo cáo theo trạng thái hoạt động
     * @param $data
     * @return mixed|void
     */
    public function getListReportStatus($data)
    {
        $data['report_view_status'] = 1;
        $data['array_not_group_status'] = [3,4];
        $list = $this->mManageWork->getTotalStatusOfStaff($data);
        if (count($list) != 0){
            $list = collect($list)->groupBy('processor_id');
            foreach ($list as $key => $item){
                $list[$key] = collect($item)->groupBy('manage_status_id');
            }
        }
        return $list;
    }

    public function getListWorkReport($data)
    {
        return $this->mManageWork->getWorkByReport($data);
    }

    public function getListReportExport($data)
    {
        return $this->mManageWork->getListExport($data);
    }


    public function checkList($list){
        try{
            $mManageComment = new ManagerCommentTable();
            $mManageWorkTag = new ManagerWorkTagTable();
            $mManageWorkSupport = new ManagerWorkSupportTable();

            foreach ($list as $key => $item) {
                $list[$key]['total_message'] = $mManageComment->getTotalCommentByWork($item['manage_work_id']);
                $list[$key]['tags'] = $mManageWorkTag->getListTagByWork($item['manage_work_id']);
                $list[$key]['list_staff'] = $mManageWorkSupport->getListStaffByWork($item['manage_work_id']);
            }

            return $list;

        }  catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Tìm kiếm bị lỗi')
            ];
        }
    }

    public function getListMyWork($data)
    {
        try{

            $mManageStatus = app()->get(ManageStatusTable::class);

            $listStatus = $mManageStatus->getAllStatusNotOverdue();

            if (count($listStatus) != 0){
                $listStatus = collect($listStatus)->pluck('manage_status_id')->toArray();
            }

            $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
            $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59');

            $start = null;
            $end = null;

            $n = 0;
            $check = 0; // Kiểm tra cuối tháng

//            Việc hôm nay

            $data['from_date'] = Carbon::now()->format('Y-m-d 00:00:00');
            $data['to_date'] = Carbon::now()->format('Y-m-d 23:59:59');
            if (isset($data['sort']) && isset($data['sort'][$n])){
                if ($data['sort'][$n]['manage_work_title'] != null){
                    $data['sort_manage_work_title'] = $data['sort'][$n]['manage_work_title'];
                }

                if ($data['sort'][$n]['progress'] != null){
                    $data['sort_manage_work_progress'] = $data['sort'][$n]['progress'];
                }

                if ($data['sort'][$n]['date_end'] != null){
                    $data['sort_manage_work_date_end'] = $data['sort'][$n]['date_end'];
                }

            }
            $list[$n] = [
                'text_block' => __('Hôm nay'),
                'list' =>  $this->mManageWork->getMyWorkReportByDate($data),
                'date_start' => $data['from_date'],
                'date_end' => $data['to_date'],
                'manage_status_id' => $listStatus,
                'assign_by' => 4,
                'type-page' => 'not-list'
            ];

            unset($data['sort_manage_work_title']);
            unset($data['sort_manage_work_progress']);
            unset($data['sort_manage_work_date_end']);

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

            if (isset($data['sort']) && isset($data['sort'][$n])){
                if ($data['sort'][$n]['manage_work_title'] != null){
                    $data['sort_manage_work_title'] = $data['sort'][$n]['manage_work_title'];
                }

                if ($data['sort'][$n]['progress'] != null){
                    $data['sort_manage_work_progress'] = $data['sort'][$n]['progress'];
                }

                if ($data['sort'][$n]['date_end'] != null){
                    $data['sort_manage_work_date_end'] = $data['sort'][$n]['date_end'];
                }

            }

            $list[$n] = [
                'text_block' => __('Tuần này'),
                'list' =>  $this->mManageWork->getMyWorkReportByDate($data),
                'date_start' => $data['from_date'],
                'date_end' => $data['to_date'],
                'manage_status_id' => $listStatus,
                'assign_by' => 4,
                'type-page' => 'not-list'
            ];

            $list[$n]['list'] = $this->checkList($list[$n]['list']);

            unset($data['sort_manage_work_title']);
            unset($data['sort_manage_work_progress']);
            unset($data['sort_manage_work_date_end']);


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

                if (isset($data['sort']) && isset($data['sort'][$n])){
                    if ($data['sort'][$n]['manage_work_title'] != null){
                        $data['sort_manage_work_title'] = $data['sort'][$n]['manage_work_title'];
                    }

                    if ($data['sort'][$n]['progress'] != null){
                        $data['sort_manage_work_progress'] = $data['sort'][$n]['progress'];
                    }

                    if ($data['sort'][$n]['date_end'] != null){
                        $data['sort_manage_work_date_end'] = $data['sort'][$n]['date_end'];
                    }

                }

                $list[$n] = [
                    'text_block' => __('Tuần sau'),
                    'list' =>  $this->mManageWork->getMyWorkReportByDate($data),
                    'date_start' => $data['from_date'],
                    'date_end' => $data['to_date'],
                    'manage_status_id' => $listStatus,
                    'assign_by' => 4,
                    'type-page' => 'not-list'
                ];


                $list[$n]['list'] = $this->checkList($list[$n]['list']);

                unset($data['sort_manage_work_title']);
                unset($data['sort_manage_work_progress']);
                unset($data['sort_manage_work_date_end']);
            }

//            Tuần kế tiếp

//            if ($check == 0){
//                $n++;
//
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
//                if (isset($data['sort']) && isset($data['sort'][$n])){
//                    if ($data['sort'][$n]['manage_work_title'] != null){
//                        $data['sort_manage_work_title'] = $data['sort'][$n]['manage_work_title'];
//                    }
//
//                    if ($data['sort'][$n]['progress'] != null){
//                        $data['sort_manage_work_progress'] = $data['sort'][$n]['progress'];
//                    }
//
//                    if ($data['sort'][$n]['date_end'] != null){
//                        $data['sort_manage_work_date_end'] = $data['sort'][$n]['date_end'];
//                    }
//
//                }
//
//                $list[$n] = [
//                    'text_block' => __('Tuần kế tiếp'),
//                    'list' =>  $this->mManageWork->getMyWorkReportByDate($data),
//                    'date_start' => $data['from_date'],
//                    'date_end' => $data['to_date'],
//                    'manage_status_id' => $listStatus,
//                    'assign_by' => 4,
//                    'type-page' => 'not-list'
//                ];
//
//
//                $list[$n]['list'] = $this->checkList($list[$n]['list']);
//
//                unset($data['sort_manage_work_title']);
//                unset($data['sort_manage_work_progress']);
//                unset($data['sort_manage_work_date_end']);
//            }

//            Khác

//            $n++;
//
//            $data['from_date'] = $start;
//            $data['to_date'] = $end;
//
//            if (isset($data['sort']) && isset($data['sort'][$n])){
//                if ($data['sort'][$n]['manage_work_title'] != null){
//                    $data['sort_manage_work_title'] = $data['sort'][$n]['manage_work_title'];
//                }
//
//                if ($data['sort'][$n]['progress'] != null){
//                    $data['sort_manage_work_progress'] = $data['sort'][$n]['progress'];
//                }
//
//                if ($data['sort'][$n]['date_end'] != null){
//                    $data['sort_manage_work_date_end'] = $data['sort'][$n]['date_end'];
//                }
//
//            }
//
//            $list[$n] = [
//                'text_block' => __('Khác'),
//                'list' =>  $this->mManageWork->getMyWorkReportByDate($data),
//                'date_start' => $data['from_date'],
//                'date_end' => $data['to_date'],
//                'manage_status_id' => $listStatus,
//                'assign_by' => 4,
//                'type-page' => 'not-list'
//            ];
//
//
//            $list[$n]['list'] = $this->checkList($list[$n]['list']);
//
//            unset($data['sort_manage_work_title']);
//            unset($data['sort_manage_work_progress']);
//            unset($data['sort_manage_work_date_end']);

//            $view = view('manager-work::report.append.append-my-work',[
//                'list' => $list,
//                'data' => $data
//            ])->render();


            $n++;
//            $data['from_date'] = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
            $data['from_date'] = null;
//            $data['to_date'] = Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59');
            $data['to_date'] = null;
            $data['type'] = 'expired';
            $data['status_overdue'] = [1,2,5];

            $list[$n] = [
                'text_block' => __('Quá hạn'),
                'list' =>  $this->mManageWork->getMyWorkReportByDate($data),
                'date_start' => $data['from_date'],
                'date_end' => Carbon::now()->format('Y-m-d H:i:s'),
                'assign_by' => 4,
                'manage_status_id' => $listStatus,
                'type-search' => 'overdue',
                'type' => 'overdue',
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
                'message' => '',
                '__message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Tổng công việc của tôi
     * @return mixed|void
     */
    public function getTotalMyWork()
    {
        try {

//            $totalMyWorkProcessor = $this->mManageWork->getTotalMyWorkProcessor();
//            $totalMyWorkUnfinished = $this->mManageWork->getTotalMyWorkUnfinished();
//            $totalMyWorkOverDue = $this->mManageWork->getTotalMyWorkOverdue();

            $mManageStatus = app()->get(ManageStatusTable::class);
            $listWork = $this->mManageWork->getListMyWork();
            $getTotalOverdue = $this->mManageWork->getTotalHome();
            $listStatus = $mManageStatus->getAll();

            if (count($listWork) != 0){
                $listWork = collect($listWork)->groupBy('manage_status_id');
            }

            $label = [];
            $data = [];
            $statusId = [];
            $color = [];
            $total = 0;
            $overdue = 0;

            foreach ($listStatus as $key => $item){
//                $listStatus[$key]['manage_status_total'] = isset($listWork[$item['manage_status_id']]) ? count($listWork[$item['manage_status_id']]) : 0;
                $label[] = (isset($listWork[$item['manage_status_id']]) ? count($listWork[$item['manage_status_id']]) : 0).' '.$item['manage_status_name'];
                $data[] = isset($listWork[$item['manage_status_id']]) ? count($listWork[$item['manage_status_id']]) : 0;
                $color[] = $item['manage_status_color'];
                $total = $total + (isset($listWork[$item['manage_status_id']]) ? count($listWork[$item['manage_status_id']]) : 0);
                $statusId[] = $item['manage_status_id'];
            }

            if ($getTotalOverdue != null && $getTotalOverdue['total_overdue'] != 0){
                $label[] = $getTotalOverdue['total_overdue'].'/'.$getTotalOverdue['total_work'].__(' công việc quá hạn');
                $color[] = '#fff';
            }

            $view = view('manager-work::report.append.append-list-status',[
                'color' => $color,
                'label' => $label,
                'statusId' => $statusId,
                'processor_id' => Auth::id()
            ])->render();

//            Công việc support
            $listWorkSuppport = $this->mManageWork->getListMyWorkSupport();
            $getTotalOverdueSupport = $this->mManageWork->getTotalHomeSupport();

            $listStatus = $mManageStatus->getAll();
            if (count($listWorkSuppport) != 0){
                $listWorkSuppport = collect($listWorkSuppport)->groupBy('manage_status_id');
            }

            $label1 = [];
            $data1 = [];
            $statusId1 = [];
            $color1 = [];
            $total1 = 0;
            $overdue1 = 0;
            foreach ($listStatus as $key => $item){
//                $listStatus[$key]['manage_status_total'] = isset($listWork[$item['manage_status_id']]) ? count($listWork[$item['manage_status_id']]) : 0;
                $label1[] = (isset($listWorkSuppport[$item['manage_status_id']]) ? count($listWorkSuppport[$item['manage_status_id']]) : 0).' '.$item['manage_status_name'];
                $data1[] = isset($listWorkSuppport[$item['manage_status_id']]) ? count($listWorkSuppport[$item['manage_status_id']]) : 0;
                $color1[] = $item['manage_status_color'];
                $total1 = $total1 + (isset($listWorkSuppport[$item['manage_status_id']]) ? count($listWorkSuppport[$item['manage_status_id']]) : 0);
                $statusId1[] = $item['manage_status_id'];
            }

            if ($getTotalOverdueSupport != null && $getTotalOverdueSupport['total_overdue'] != 0){
                $label1[] = $getTotalOverdueSupport['total_overdue'].'/'.$getTotalOverdueSupport['total_work'].__(' công việc quá hạn');
                $color1[] = '#fff';
            }

            $view1 = view('manager-work::report.append.append-list-status',[
                'color' => $color1,
                'label' => $label1,
                'statusId' => $statusId1,
                'support_id' => Auth::id(),
                'type' => 'support'
            ])->render();

            $viewMyWork = $this->viewReportTableMyWork();
            $viewSupport = $this->viewReportTableWorkSupport();

            return [
                'error' => false,
                'label' => $label,
                'label1' => $label1,
                'data' => $data,
                'data1' => $data1,
                'color' => $color,
                'color1' => $color1,
                'total' => $total == 0 ? __('Chưa có công việc nào') :  $total."<br>".__('Công việc'),
                'total1' => $total1 == 0 ? __('Chưa có công việc nào') :  $total1."<br>".__('Công việc'),
                'view' => $view,
                'view1' => $view1,
                'totalWork' => $total,
                'totalWork1' => $total1,
                'viewMyWork' => $viewMyWork['view'],
                'viewSupport' => $viewSupport['view']
            ];
        }catch (\Exception $e){

        }
    }

    public function viewReportTableMyWork($filter = []){
//        Số item trên 1 page
        $filter['perpage'] = 10;
        $listWork = $this->mManageWork->getListMyWorkPagination($filter);
//            View danh sách công việc block việc của tôi
        $viewMyWork = view('manager-work::report.append.append-table-work',['listWork' => $listWork])->render();
        return [
            'error' => false,
            'view' => $viewMyWork
        ];
    }

    public function viewReportTableWorkSupport($filter = []){
//        Số item trên 1 page
        $filter['perpage'] = 10;
        $listWorkSuppport = $this->mManageWork->getListMyWorkSupportPagination($filter);
//            View danh sách công việc của block công việc tôi hỗ trợ
        $viewSupport = view('manager-work::report.append.append-table-work-support',['listWorkSuppport' => $listWorkSuppport])->render();
        return [
            'error' => false,
            'view' => $viewSupport
        ];
    }

    /**
     * Lấy danh sách công việc tôi giao
     * @param $data
     * @return mixed|void
     */
    public function getListMyWorkAssign($data)
    {
        try {

            $mManageStatus = app()->get(ManageStatusTable::class);

            $listStatus = $mManageStatus->getAllStatusNotOverdue();

            if (count($listStatus) != 0){
                $listStatus = collect($listStatus)->pluck('manage_status_id')->toArray();
            }

//            Việc hôm nay
            $data['type_my_work'] = 'pending';

            if (isset($data['sort_assign'])){
                if ($data['sort_assign'][0]['manage_work_title'] != null){
                    $data['sort_assign_assign'] = $data['sort_assign'][0]['manage_work_title'];
                }

                if ($data['sort_assign'][0]['progress'] != null){
                    $data['sort_assign_manage_work_progress'] = $data['sort_assign'][0]['progress'];
                }

                if ($data['sort_assign'][0]['date_end'] != null){
                    $data['sort_assign_manage_work_date_end'] = $data['sort_assign'][0]['date_end'];
                }

            }

            $list[0] = [
                'text_block' => __('Chờ duyệt'),
                'list' =>  $this->mManageWork->getListMyWorkAssignPending($data),
                'date_start' => Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00'),
                'date_end' => Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59'),
                'manage_status_id' => $listStatus,
                'assign_by' => 3,
                'type-page' => 'not-list'
            ];

            $list[0]['list'] = $this->checkList($list[0]['list']);

            unset($data['sort_assign_manage_work_title']);
            unset($data['sort_assign_manage_work_progress']);
            unset($data['sort_assign_manage_work_date_end']);

            $data['type_my_work'] = 'assign';

            if (isset($data['sort_assign'])){
                if ($data['sort_assign'][1]['manage_work_title'] != null){
                    $data['sort_assign_manage_work_title'] = $data['sort_assign'][1]['manage_work_title'];
                }

                if ($data['sort_assign'][1]['progress'] != null){
                    $data['sort_assign_manage_work_progress'] = $data['sort_assign'][1]['progress'];
                }

                if ($data['sort_assign'][1]['date_end'] != null){
                    $data['sort_assign_manage_work_date_end'] = $data['sort_assign'][1]['date_end'];
                }

            }

            $list[1] = [
                'text_block' => __('Đã giao công việc'),
                'list' =>  $this->mManageWork->getListMyWorkAssignPending($data),
                'date_start' => Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00'),
                'date_end' => Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59'),
                'manage_status_id' => $listStatus,
                'assign_by' => 6,
                'type-page' => 'not-list'
            ];

            $list[1]['list'] = $this->checkList($list[1]['list']);

            unset($data['sort_assign_manage_work_title']);
            unset($data['sort_assign_manage_work_progress']);
            unset($data['sort_assign_manage_work_date_end']);

            $view = view('manager-work::report.append.append-my-work-assign',[
                'list' => $list,
                'data' => $data
            ])->render();
            return [
                'error' => false,
                'view' => $view,
            ];
        }catch (\Exception $e){
        }
    }

    /**
     * Huỷ / Duyệt công việc
     * @param $data
     * @return mixed|void
     */
    public function workApprove($data)
    {
        try{
            $status = null ;

            if ($data['type'] == 'approve') {
                $status = self::complete;
            } else if($data['type'] == 'reject') {
                $status = self::incomplete;
            }

            if ($status != null){
                $this->mManageWork->editWork(['manage_status_id' => $status],$data['manage_work_id']);
            }

            $dataHistory = [
                'manage_work_id' => $data['manage_work_id'],
                'staff_id' => Auth::id(),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            if ($status == self::complete){
                $dataHistory['message'] = __(' đã duyệt công việc');
            } else {
                $dataHistory['message'] = __(' đã từ chối công việc');
            }

            $this->mManageHistory->createdHistory($dataHistory);

            $sendNoti = new SendNotificationApi();

            $dataNoti = [
                'key' => $status == self::complete ? 'work_finish' : 'work_update_status',
                'object_id' => $data['manage_work_id'],
            ];
            $sendNoti->sendStaffNotification($dataNoti);


            return [
                'error' => false,
                'message' => $data['type'] == 'approve' ? __('Phê duyệt thành công') : __('Từ chối thành công')
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Phê duyệt thất bại')
            ];
        }
    }

    /**
     * Lấy danh sashc nhắc nhở của tôi
     * @param $data
     * @return mixed|void
     */
    public function searchRemind($data)
    {
        try{

            $mManageRemind = new ManageRedmindTable();

            $listRemind = $mManageRemind->getListRemindMyWork($data);

            $view = view('manager-work::report.append.append-list-remind',['listRemind' => $listRemind])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Lấy danh sách nhắc nhở thất bại')
            ];
        }
    }

    /**
     * Xoá nhắc nhở
     * @param $data
     * @return mixed|void
     */
    public function removeRemind($data)
    {
        try {
            if (!isset($data['remind']) || count($data['remind']) == 0){
                return [
                    'error' => true,
                    'message' => __('Vui lòng chọn nhắc nhở cần xoá')
                ];
            }

            $mManageRemind = new ManageRedmindTable();

            $mManageRemind->removeArrRemind($data['remind']);

            return [
                'error' => false,
                'message' => __('Xoá nhắc nhở thành công')
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Xoá nhắc nhở thất bại')
            ];
        }
    }

    /**
     * hiển thị popup nhắc nhở
     * @param $data
     * @return mixed|void
     */
    public function showPopupRemindPopup($data)
    {
        try{

            $mManageRemind = new ManageRedmindTable();
            $detail = null;
            $listWork = $this->mManageWork->getAll();

            $view = view('manager-work::report.popup.remind-work',['data' => $data,'listWork' => $listWork])->render();

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
     * Tạo nhắc nhở
     * @param $data
     * @return mixed|void
     */
    public function addRemindWork($data)
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

            foreach ($data['staff'] as $item){
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
                    'manage_work_id' => isset($data['manage_work_id']) ? $data['manage_work_id'] : null,
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

            $idRemind = $mManageRemind->insertRemind($dataRemind[0]);

            $sendNoti = new SendNotificationApi();

            $dataNoti = [
                'key' => 'work_remind',
                'object_id' => $idRemind,
            ];
            $sendNoti->sendStaffNotification($dataNoti);

            if (isset($data['manage_work_id'])){
                $dataHistory = [
                    'manage_work_id' => $data['manage_work_id'],
                    'staff_id' => Auth::id(),
                    'message' => __(' đã tạo nhắc nhở thành công'),
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];

                $this->mManageHistory->createdHistory($dataHistory);
            }
            return [
                'error' => false,
                'message' => __('Lưu nhắc nhở thành công'),
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Lưu nhắc nhở thất bại')
            ];
        }
    }

    /**
     * Kiểm tra nhắc trước
     * @param $data
     * @return string|null
     */
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
     * Tổng số công việc user hỗ trợ
     * @return mixed|void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getTotalCreated()
    {
        $mManageWork = app()->get(ManagerWorkTable::class);

        return $mManageWork->getTotalCreated();
    }

    /**
     * Tổng số công việc tôi duyệt trong tháng
     * @return mixed|void
     */
    public function getTotalApprove(){
        $mManageWork = app()->get(ManagerWorkTable::class);

        return $mManageWork->getTotalApprove();
    }

    /**
     * Cập nhật vị trí block
     * @param $data
     * @return mixed|void
     */
    public function myWorkUpdateBlock($data)
    {
        try {
            $mManageBlockPage = app()->get(ManageBlockPageTable::class);
            foreach ($data['block'] as $key => $item){
                $data['block'][$key]['staff_id'] = Auth::id();
                $data['block'][$key]['route_page'] = $data['route'];
                $data['block'][$key]['created_at'] = Carbon::now();
                $data['block'][$key]['updated_at'] = Carbon::now();
                $data['block'][$key]['created_by'] = Auth::id();
                $data['block'][$key]['updated_by'] = Auth::id();
            }
//        Xóa block
            $mManageBlockPage->removeBlock(Auth::id(),$data['route']);
//            Thêm danh sách block mới
            $mManageBlockPage->addBlock($data['block']);
            return [
                'error' => false,
                'message' => __('Cập nhật vị trí thành công')
            ];
        }catch (Exception $exception){
            return [
                'error' => true,
                'message' => __('Cập nhật vị trí thất bại')
            ];
        }

    }

    /**
     * Lấy danh sách vị trí block được cấu hình
     * @param $data
     */
    public function getListBlock($routeName,$arrayBlock)
    {
        $mManageBlockPage = app()->get(ManageBlockPageTable::class);
        $list = $mManageBlockPage->getListBlock($routeName,Auth::id());
        if (count($list) != 0){
            $arrayBlock = collect($list)->pluck('key_block');
        }

        return $arrayBlock;
    }

    /**
     * Lấy danh sách trạng thái đang hoạt động
     * @return mixed|void
     */
    public function getListStatusActive($data = [])
    {
        $mManageStatus = app()->get(ManageStatusTable::class);
        return $mManageStatus->getListStatusActive($data);
    }

    /**
     * Lấy danh sách hoạt động
     * @return mixed|void
     */
    public function getListHistory()
    {
        $rManageWork = app()->get(ManagerWorkRepository::class);
        $rManageHistory = app()->get(ManageHistoryRepoInterface::class);
        $data['staff_id'] = Auth::id();
        $data['my_report_created_at'] = Carbon::now()->subHours(24)->format('Y-m-d H:i:00').' - '.Carbon::now()->format('Y-m-d H:i:59');

//        return $rManageWork->getListHistory($data);
        return $rManageHistory->searchHistory($data);
    }

}