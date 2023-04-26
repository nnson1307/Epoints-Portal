<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\ManagerWork\Http\Controllers;

use App\Http\Middleware\S3UploadsRedirect;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\CustomerLead\Models\ManageWorkTable;
use Modules\ManagerWork\Http\Api\SendNotificationApi;
use Modules\ManagerWork\Http\Requests\Document\MoveFileRequest;
use Modules\ManagerWork\Http\Requests\Remind\RemindStaffNotStartRequest;
use Modules\ManagerWork\Models\BranchTable;
use Modules\ManagerWork\Models\ManageRedmindTable;
use Modules\ManagerWork\Models\ManagerHistoryTable;
use Modules\ManagerWork\Models\ManageStatusConfigMapTable;
use Modules\ManagerWork\Repositories\Departments\DepartmentsInterface;
use Modules\ManagerWork\Repositories\Departments\DepartmentsRepo;
use Modules\ManagerWork\Repositories\ManagerWork\ManagerWorkRepositoryInterface;
use Modules\ManagerWork\Repositories\ManagerWorkSupport\ManagerWorkSupportInterface;
use Modules\ManagerWork\Repositories\ManagerWorkTag\ManagerWorkTagInterface;
use Modules\ManagerWork\Repositories\TypeWork\TypeWorkRepositoryInterface;
use Modules\ManagerWork\Repositories\Project\ProjectRepositoryInterface;
use Modules\ManagerWork\Repositories\ManageTags\ManageTagsRepositoryInterface;
use Modules\ManagerWork\Repositories\ManageStatus\ManageStatusRepositoryInterface;
use Modules\ManagerWork\Repositories\ManageRedmind\ManageRedmindRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cookie;
use Modules\ManagerWork\Models\ManagerConfigListTable;
use Modules\ManagerWork\Models\StaffTable;
use Modules\ManagerWork\Models\ManageWorkSupportTable;
use Modules\ManagerWork\Models\ManageWorkTagTable;
use Modules\ManagerWork\Models\Customers;
use Modules\ManagerWork\Models\ManageRepeatTimeTable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ManagerWorkController extends Controller
{
    protected $managerWork;
    protected $configList;
    protected $typeWork;
    protected $staff;
    protected $project;
    protected $customers;
    protected $manageTags;
    protected $manageStatus;
    protected $manageWorkSupport;
    protected $manageWorkSupportRepo;
    protected $manageWorkTag;
    protected $manageRemind;
    protected $manageRepeatTime;
    protected $mManageHistory;
    protected $manageWorkTagRepo;
    protected $repoDepartments;


    public function __construct(
        ManagerWorkRepositoryInterface   $managerWork,
        TypeWorkRepositoryInterface      $typeWork,
        ManagerConfigListTable           $configList,
        ProjectRepositoryInterface       $project,
        ManageTagsRepositoryInterface    $manageTags,
        ManageStatusRepositoryInterface  $manageStatus,
        ManageRedmindRepositoryInterface $manageRemind,
        StaffTable                       $staff,
        ManageWorkSupportTable           $manageWorkSupport,
        ManageWorkTagTable               $manageWorkTag,
        Customers                        $customers,
        ManageRepeatTimeTable            $manageRepeatTime,
        ManagerHistoryTable              $mManageHistory,
        ManagerWorkSupportInterface      $managerWorkSupportRepository,
        ManagerWorkTagInterface          $managerWorkTagRepository,
        DepartmentsInterface             $infDepartments
    )
    {
        $this->managerWork = $managerWork;
        $this->configList = $configList;
        $this->typeWork = $typeWork;
        $this->staff = $staff;
        $this->project = $project;
        $this->customers = $customers;
        $this->manageTags = $manageTags;
        $this->manageStatus = $manageStatus;
        $this->manageWorkSupport = $manageWorkSupport;
        $this->manageWorkTag = $manageWorkTag;
        $this->manageRemind = $manageRemind;
        $this->manageRepeatTime = $manageRepeatTime;
        $this->mManageHistory = $mManageHistory;
        $this->manageWorkSupportRepo = $managerWorkSupportRepository;
        $this->manageWorkTagRepo = $managerWorkTagRepository;
        $this->repoDepartments = $infDepartments;
    }

    public function indexAction(Request $request)
    {

        $param = $request->all();
        $filter = [];
        if (isset($param['report_staff_id'])) {
            $filter['report_staff_id'] = $param['report_staff_id'];
            $filter['processor_id'] = $param['report_staff_id'];
            if ($request->session()->has('filter_report')) {
                $dataReport = $request->session()->get('filter_report');
                if (isset($dataReport['branch_id'])) {
                    $filter['branch_id'] = $dataReport['branch_id'];
                }

                if (isset($dataReport['department_id'])) {
                    $filter['department_id'] = $dataReport['department_id'];
                }

                if (isset($dataReport['dateSelect'])) {
                    $filter['created_at'] = $dataReport['dateSelect'];
                    $filter['date_end'] = $dataReport['dateSelect'];
                }
            }
        }

        if (isset($param['manage_tag_id'])) {
            $filter['manage_tag_id'] = $param['manage_tag_id'];
        }

        if (isset($param['manage_type_work_id'])) {
            $filter['manage_type_work_id'] = $param['manage_type_work_id'];
        }

        if (isset($param['manage_project_id']) && $param['manage_project_id'] != null) {
            $filter['manage_project_id'] = $param['manage_project_id'];
        }

        if (isset($param['repeat_type']) && $param['repeat_type'] != null) {
            $filter['repeat_type'] = $param['repeat_type'];
        }

        if (count($filter) != 0) {
            $searchList = $this->searchColumn($filter);
            $searchList = collect($searchList)->where('active', 1);
            if (count($searchList) != 0) {
                $searchList = collect($searchList)->keys()->toArray();
            }

            $searchList[] = 12;
            if (isset($filter['department_id'])) {
                $searchList[] = 19;
            }

            if (isset($filter['date_end'])) {
                $searchList[] = 4;
                $searchList[] = 5;
            }

            if (isset($filter['manage_tag_id'])) {
                $searchList[] = 11;
            }

            if (isset($filter['manage_type_work_id'])) {
                $searchList[] = 20;
            }

            if (isset($filter['manage_project_id'])) {
                $searchList[] = 18;
            }

            $columnList = $this->showColumn();

            $columnList = collect($columnList)->where('active', 1);
            if (count($columnList) != 0) {
                $columnList = collect($columnList)->keys()->toArray();
            }

            $user_id = \Auth::id();
            $route_name = 'manager-work';
            $name = __('danh sách quản lý công việc');
            $data = [
                'search' => $searchList,
                'column' => $columnList,
            ];
            $data = [
                'value' => serialize($data),
                'user_id' => $user_id,
                'route_name' => $route_name,
                'name' => $name,
            ];
            if ($this->configList->checkExist($user_id, $route_name)) {
                $this->configList->edit($data, $user_id, $route_name);
            } else {
                $this->configList->add($data, $user_id, $route_name);
            }
        }


        //Lấy filter từ param url
        if (isset($param['date_end']) && $param['date_end'] != null) {
//            $filter['date_end'] = Carbon::parse($param['date_end'])->format('d/m/Y') . ' - ' . Carbon::parse($param['date_end'])->format('d/m/Y');
            $filter['date_end'] = Carbon::parse($param['date_end'])->format('d/m/Y');
        } else {
            $filter['date_end'] = Carbon::now()->endOfMonth()->format('d/m/Y');
        }

        if (isset($param['date_start']) && $param['date_start'] != null) {
//            $filter['date_end'] = Carbon::parse($param['date_end'])->format('d/m/Y') . ' - ' . Carbon::parse($param['date_end'])->format('d/m/Y');
            $filter['date_start'] = Carbon::parse($param['date_start'])->format('d/m/Y');
        } else {
            $filter['date_start'] = Carbon::now()->firstOfMonth()->format('d/m/Y');
        }

        if (isset($param['branch_id']) && $param['branch_id'] != null) {
            $filter['branch_id'] = $param['branch_id'];
        }

        if (isset($param['processor_id']) && $param['processor_id'] != null) {
            $filter['processor_id'] = $param['processor_id'];
        }

        if (isset($param['department_id']) && $param['department_id'] != null) {
            $filter['department_id'] = $param['department_id'];
        }

        if (isset($param['manage_status_id'])) {
            $filter['manage_status_id'] = $param['manage_status_id'];
        }

        if (isset($param['type-search'])) {
            $filter['type-search'] = $param['type-search'];
        }

        if (isset($param['type-page'])) {
            $filter['type-page'] = $param['type-page'];
        }

        if (isset($param['support_id'])) {
            $filter['manage_work_support_id'] = $param['support_id'];
        }

        $list = $this->managerWork->list($filter);
        $arrSupport = $arrTag = [];
        if ($list) {
            $listWork = $list->getCollection()->pluck('manage_work_id')->toArray();
            $listSupport = $this->manageWorkSupportRepo->getListByWork($listWork);
            $listTag = $this->manageWorkTagRepo->getListByWork($listWork);
            foreach ($listSupport as $itemS) {
                $arrSupport[$itemS['manage_work_id']][] = $itemS['staff_name'];
            }
            foreach ($listTag as $itemT) {
                $arrTag[$itemT['manage_work_id']][] = $itemT['manage_tag_name'];
            }
        }

        return view('manager-work::managerWork.index', [
            'list' => $list,
            'listSupport' => $arrSupport,
            'listTag' => $arrTag,
            'searchConfig' => $this->searchColumn($filter),
            'showColumn' => $this->showColumn(),
            'typeWork' => $this->typeWork->getName(),
            'staffList' => $this->staff->getName(),
            'managerWorkList' => $this->managerWork->getName(),
            'projectList' => $this->project->getName(),
            'customersList' => $this->customers->getFullOption(),
            'manageTagsList' => $this->manageTags->getName(),
            'manageStatusList' => $this->manageStatus->getName(),
            'typeWorkTagsList' => $this->typeWorkTags(),
            'priorityWorkList' => $this->priorityWork(),
            'filter' => $filter,
            'filterLoad' => $filter,
            'param' => $param
        ]);
    }
    public function kanbanViewVueAction(Request $request)
    {
        return view('manager-work::managerWork.kanban-vue');
    }

    public function showAddAction()
    {
        return response()->json(
            [
                'html' => view('manager-work::managerWork.add', $this->loadDetail())->render(),
            ]
        );
    }

    public function kanbanViewAction(Request $request)
    {
//        $filters = $request->only([
//            'page', 'display',
//            'search', 'manage_status_id','assign_by', 'created_at',
//            'date_end','date_overtime','manage_tag_id', 'processor_id',
//            'manage_work_support_id','created_by', 'approve_id', 'updated_by',
//            'type_card_work','manage_project_id', 'department_id', 'manage_type_work_id',
//            'priority','date_finish', 'updated_at', 'customer_id','manage_work_customer_type',
//            'date_start'
//        ]);
        $filters = $request->only([
            'page', 'display', 'branch_id', 'created_at', 'work_overdue_search',
            'search', 'manage_status_id', 'assign_by', 'created_at',
            'date_end', 'date_overtime', 'manage_tag_id', 'processor_id',
            'manage_work_support_id', 'created_by', 'approve_id', 'updated_by',
            'type_card_work', 'manage_project_id', 'department_id', 'manage_type_work_id',
            'priority', 'date_finish', 'updated_at', 'customer_id', 'manage_work_customer_type', 'date_start', 'is_parent', 'type-search', 'type-page'
        ]);

        if (!isset($filters['date_start'])) {
            $filters['date_start'] = Carbon::now()->startOfMonth()->format('d/m/Y');
        }

        if (!isset($filters['date_end'])) {
            $filters['date_end'] = Carbon::now()->endOfMonth()->format('d/m/Y');
        }

        $filters['perpage'] = 1000;
        //        $colorStatus = [
        //            1 => '#BDD7EE',
        //            2 => '#5B9BD5',
        //            3 => '#77C144',
        //            4 => '#FFC000',
        //            5 => '#E94343',
        //            6 => '#C0C0C0',
        //            7 => '#808080',
        //            8 => '#64d4c5',
        //        ];
        $colorStatus = $this->manageStatus->getColorList();
        //        dd($colorStatus);
        return view('manager-work::managerWork.kanban', [
            'list' => $this->managerWork->list($filters),
            'searchConfig' => $this->searchColumn(),
            'showColumn' => $this->showColumn(),
            'typeWork' => $this->typeWork->getName(),
            'staffList' => $this->staff->getName(),
            'managerWorkList' => $this->managerWork->getName(),
            'projectList' => $this->project->getName(),
            'customersList' => $this->customers->getFullOption(),
            'manageTagsList' => $this->manageTags->getName(),
            'manageStatusList' => $this->manageStatus->getName(),
            'typeWorkTagsList' => $this->typeWorkTags(),
            'priorityWorkList' => $this->priorityWork(),
            'colorStatus' => $colorStatus,
            'filters' => $filters,
        ]);
    }

    // public function loadKanBan()
    // {
    //     $status = $this->manageStatus->getName();
    //     $kanbanStatus = [];
    //     foreach($status as $status_id => $status_name){
    //         $kanbanStatus[] = json_encode([
    //             "text" => $status_name,
    //             "dataField" => $status_id
    //         ]);
    //     }
    //     $data = [
    //         'kanbanStatus' => array_values($kanbanStatus)
    //     ];
    //     return response()->json(['status' => 1,'data'=> $data]);
    // }

    protected function filters()
    {
        return [
            '' => __('Chọn trạng thái'),
            1 => __('Hoạt động'),
            0 => __('Tạm ngưng')
        ];
    }

    // Return Loại thẻ công việc	
    protected function typeWorkTags()
    {
        return [
            'bonus' => __('Thường'),
            'kpi' => 'KPI',
        ];
    }

    // Return mức độ ưu tiên
    protected function priorityWork()
    {
        return [
            1 => __('Thấp'),
            2 => __('Bình thường'),
            3 => __('Cao'),
        ];
    }

    public function listAction(Request $request)
    {
        $filters = $request->only([
            'page', 'display', 'branch_id', 'created_at', 'work_overdue_search',
            'search', 'manage_status_id', 'assign_by', 'created_at',
            'date_end', 'date_overtime', 'manage_tag_id', 'processor_id',
            'manage_work_support_id', 'created_by', 'approve_id', 'updated_by',
            'type_card_work', 'manage_project_id', 'department_id', 'manage_type_work_id',
            'priority', 'date_finish', 'updated_at', 'customer_id', 'manage_work_customer_type', 'date_start', 'is_parent', 'type-search', 'type-page',
            'create_object_type', 'repeat_type'
        ]);

        $list = $this->managerWork->list($filters);
        $arrSupport = $arrTag = [];
        if ($list) {
            $listWork = $list->getCollection()->pluck('manage_work_id')->toArray();
            $listSupport = $this->manageWorkSupportRepo->getListByWork($listWork);
            $listTag = $this->manageWorkTagRepo->getListByWork($listWork);
            foreach ($listSupport as $itemS) {
                $arrSupport[$itemS['manage_work_id']][] = $itemS['staff_name'];
            }
            foreach ($listTag as $itemT) {
                $arrTag[$itemT['manage_work_id']][] = $itemT['manage_tag_name'];
            }
        }
        return view(
            'manager-work::managerWork.list',
            [
                'list' => $list,
                'listSupport' => $arrSupport,
                'listTag' => $arrTag,
                'showColumn' => $this->showColumn(),
                'page' => $filters['page']
            ]
        );
    }

    public function addAction(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'manage_work_title' => $request->manage_work_title,
                'manage_type_work_id' => $request->manage_type_work_id,
                'is_approve_id' => $request->is_approve_id,
                'time' => $request->time,
                'time_type' => $request->time_type,
                'processor_id' => $request->processor_id,
                'approve_id' => $request->approve_id,
                'parent_id' => $request->parent_id,
                'description' => $request->description,
                'manage_project_id' => $request->manage_project_id,
                'customer_id' => $request->customer_id,
                'type_card_work' => $request->type_card_work,
                'priority' => $request->priority,
                'progress' => $request->manage_status_id == 6 ? 100 : (($request->progress != '') ? $request->progress : 0),
                'manage_status_id' => $request->manage_status_id,
                'repeat_type' => $request->repeat_type,
                'repeat_end' => $request->repeat_end,
                'repeat_end_time' => $request->repeat_end_time,
                'repeat_end_type' => $request->repeat_end_type,
                'repeat_end_full_time' => $request->repeat_end_full_time,
                'repeat_time' => $request->repeat_time,
                'created_by' => Auth::id(),
            ];
            if ($request->check_start_date_check != null && $request->date_issue_single) {
                $date_end = Carbon::createFromFormat("d/m/Y H:i", $request->date_issue_single)->format("Y-m-d H:i:s");
                $data['date_end'] = $date_end;
            } else {
                if ($request->date_issue) {
                    $arr_filter = explode(" - ", $request->date_issue);
                    $date_start = Carbon::createFromFormat("d/m/Y H:i", $arr_filter[0])->format("Y-m-d H:i:s");
                    $date_end = Carbon::createFromFormat("d/m/Y H:i", $arr_filter[1])->format("Y-m-d H:i:s");
                    $data['date_start'] = $date_start;
                    $data['date_end'] = $date_end;
                }
            }
            $id = $this->managerWork->add($data);
            if ($id) {
                $processor = $request->processor;
                // $this->manageWorkSupport->remove($id);
                if ($processor && is_array($processor)) {
                    foreach ($processor as $staff_id) {
                        $list_processor = [
                            "manage_work_id" => $id,
                            "staff_id" => $staff_id,
                            "created_by" => \Auth::id()
                        ];
                        $this->manageWorkSupport->add($list_processor);
                    }
                }
                $tag = $request->manage_tag_id;
                // $this->manageWorkTag->remove($id);
                if ($tag && is_array($tag)) {
                    foreach ($tag as $staff_id) {
                        $list_tag = [
                            "manage_work_id" => $id,
                            "manage_tag_id" => $staff_id,
                            "created_by" => \Auth::id()
                        ];
                        $this->manageWorkTag->add($list_tag);
                    }
                }
                $processor_id = $request->processor_id_remind; # người nhắc
                $date_remind = $request->date_remind; # thời gian nhắc
                $time_remind = $request->time_remind; # thời gian trước khi nhắc
                $time_type_remind = $request->time_type_remind; # loại thời gian trước khi nhắc
                $description_remind = $request->description_remind; # mô tả nhắc
                // $this->manageRemind->remove($id);
                if ($processor_id && is_array($processor_id)) {
                    foreach ($processor_id as $key => $staff_id) {
                        $list_remind = [
                            "staff_id" => $staff_id,
                            "date_remind" => ($date_remind[$key] != null) ? Carbon::createFromFormat("d/m/Y H:i", $date_remind[$key])->format("Y-m-d H:i:s") : '',
                            "time" => $time_remind[$key],
                            "time_type" => $time_type_remind[$key],
                            "manage_work_id" => $id,
                            "description" => $description_remind[$key],
                            "created_by" => \Auth::id()
                        ];
                        $this->manageRemind->add($list_remind);
                    }
                }

                $repeat_type = $request->repeat_type;
                // - weekly
                // - monthly
                $array_time = [];
                if ($repeat_type == "weekly") {
                    $array_time = $request->day_of_week;
                } elseif ($repeat_type == 'monthly') {
                    $array_time = $request->day_of_month;
                }
                // $this->manageRepeatTime->remove($id);
                if ($array_time && is_array($array_time)) {
                    foreach ($array_time as $time) {
                        $time_array = [
                            "manage_work_id" => $id,
                            "time" => $time,
                            "created_by" => \Auth::id()
                        ];
                        $this->manageRepeatTime->add($time_array);
                    }
                }

                $sendNoti = new SendNotificationApi();

                $dataNoti = [
                    'key' => 'work_assign',
                    'object_id' => $id,
                ];

                $sendNoti->sendStaffNotification($dataNoti);

                $dataHistory = [
                    'manage_work_id' => $id,
                    'staff_id' => Auth::id(),
                    'message' => __(' đã tạo công việc'),
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];

                $this->mManageHistory->createdHistory($dataHistory);

                return response()->json(['status' => 1]);
            }
            return response()->json(['status' => 0]);
        }
    }

    // FUNCTION RETURN VIEW EDIT
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->manage_work_id;
            $item = $this->managerWork->getItem($id);
            $workSupport = [];
            if (!empty($item->workSupport)) {
                foreach ($item->workSupport as $staff) {
                    $workSupport[] = $staff->staff_id;
                }
            }
            $workTag = [];
            if (!empty($item->workTag)) {
                foreach ($item->workTag as $tag) {
                    $workTag[] = $tag->manage_tag_id;
                }
            }

            $repeatTime = [];
            if (!empty($item->repeatTime)) {
                foreach ($item->repeatTime as $time) {
                    $repeatTime[] = $time->time;
                }
            }
            $end_date = ($item->date_end != '') ? date('d/m/Y H:i', strtotime($item->date_end)) : '';
            $start_date = ($item->date_start != '') ? date('d/m/Y H:i', strtotime($item->date_start)) : '';
            $repeat_end_full_time = ($item->repeat_end_full_time != '') ? date('d/m/Y', strtotime($item->repeat_end_full_time)) : '';

            $processorList = [];
            foreach ($workSupport as $itemSupport) {
                $processorList[$itemSupport] = $itemSupport;
            }

            $tagList = [];
            foreach ($workTag as $itemTag) {
                $tagList[$itemTag] = $itemTag;
            }

            $jsonString['detail'] = [
                'manage_work_id' => $id,
                'manage_work_title' => $item->manage_work_title,
                'type_card_work' => $item->type_card_work,
                'manage_type_work_id' => $item->manage_type_work_id,
                'is_approve_id' => $item->is_approve_id,
                'time' => $item->time,
                'time_type' => $item->time_type,
                'processor_id' => $item->processor_id,
                'approve_id' => $item->approve_id,
                'processor' => $processorList,
                'date_start' => $start_date,
                'date_end' => $end_date,
                'parent_id' => $item->parent_id,
                'progress' => $item->manage_status_id == 6 ? 100 : $item->progress,
                'description' => $item->description,
                'manage_project_id' => $item->manage_project_id,
                'customer_id' => $item->customer_id,
                'manage_tag_id' => $tagList,
                'priority' => $item->priority,
                'manage_status_id' => $item->manage_status_id,
                'remind' => $item->remind,
                'repeat_type' => $item->repeat_type,
                'repeat_end' => $item->repeat_end,
                'repeat_end_time' => $item->repeat_end_time,
                'repeat_end_type' => $item->repeat_end_type,
                'repeat_end_full_time' => $repeat_end_full_time,
                'repeat_times' => $repeatTime,
                'repeat_time' => $item->repeat_time,
            ];

            if ($request->action_type == 'copy') {
                return $jsonString['detail'];
            }

            return response()->json(
                [
                    'html' => view('manager-work::managerWork.edit', $jsonString + $this->loadDetail())->render(),
                    'data' => $jsonString['detail']
                ]
            );
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->manage_work_id_hidden;
            $detailOld = $this->managerWork->getDetail($id);

            $data = [
                'manage_work_title' => $request->manage_work_title,
                'manage_work_id' => $id,
                'is_approve_id' => $request->is_approve_id,
                'manage_type_work_id' => $request->manage_type_work_id,
                'time' => $request->time,
                'time_type' => $request->time_type,
                'processor_id' => $request->processor_id,
                'approve_id' => $request->approve_id,
                'parent_id' => $request->parent_id,
                'description' => $request->description,
                'manage_project_id' => $request->manage_project_id,
                'customer_id' => $request->customer_id,
                'type_card_work' => $request->type_card_work,
                'priority' => $request->priority,
                'progress' => $request->manage_status_id == 6 ? 100 : (($request->progress != '') ? $request->progress : 0),
                'repeat_time' => $request->repeat_time,
                'manage_status_id' => $request->manage_status_id,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];

            if ($request->check_start_date_check) {
                $date_end = Carbon::createFromFormat("d/m/Y H:i", $request->date_issue_single)->format("Y-m-d H:i:s");
                $data['date_end'] = $date_end;
            } else {
                $arr_filter = explode(" - ", $request->date_issue);
                $date_start = Carbon::createFromFormat("d/m/Y H:i", $arr_filter[0])->format("Y-m-d H:i:s");
                $date_end = Carbon::createFromFormat("d/m/Y H:i", $arr_filter[1])->format("Y-m-d H:i:s");
                $data['date_start'] = $date_start;
                $data['date_end'] = $date_end;
            }

            if ($this->managerWork->edit($data, $id)) {
                $processor = $request->processor;
                $this->manageWorkSupport->remove($id);
                if ($processor && is_array($processor)) {
                    foreach ($processor as $staff_id) {
                        $list_processor = [
                            "manage_work_id" => $id,
                            "staff_id" => $staff_id,
                            "created_by" => \Auth::id()
                        ];
                        $this->manageWorkSupport->add($list_processor);
                    }
                }
                $tag = $request->manage_tag_id;
                $this->manageWorkTag->remove($id);
                if ($tag && is_array($tag)) {
                    foreach ($tag as $staff_id) {
                        $list_tag = [
                            "manage_work_id" => $id,
                            "manage_tag_id" => $staff_id,
                            "created_by" => \Auth::id()
                        ];
                        $this->manageWorkTag->add($list_tag);
                    }
                }
                // $processor_id = $request->processor_id_remind; # người nhắc
                // $date_remind = $request->date_remind; # thời gian nhắc
                // $time_remind = $request->time_remind; # thời gian trước khi nhắc
                // $time_type_remind = $request->time_type_remind; # loại thời gian trước khi nhắc
                // $description_remind = $request->description_remind; # mô tả nhắc
                // $this->manageRemind->removeByWorkId($id);
                // if($processor_id && is_array($processor_id)){
                //     foreach($processor_id as $key => $staff_id){
                //         $list_remind = [
                //             "staff_id" => $staff_id,
                //             "date_remind" => ($date_remind[$key] != null) ? Carbon::createFromFormat("d/m/Y H:i", $date_remind[$key])->format("Y-m-d H:i:s") :'',
                //             "time" => $time_remind[$key],
                //             "time_type" => $time_type_remind[$key],
                //             "manage_work_id" => $id,
                //             "description" => $description_remind[$key],
                //             "created_by" => \Auth::id()
                //         ];
                //         $this->manageRemind->add($list_remind);
                //     }
                // }
                $repeat_type = $request->repeat_type;
                // - weekly
                // - monthly
                $array_time = [];
                if ($repeat_type == "weekly") {
                    $array_time = $request->day_of_week;
                } elseif ($repeat_type == 'monthly') {
                    $array_time = $request->day_of_week;
                }
                $this->manageRepeatTime->remove($id);
                if ($array_time && is_array($array_time)) {
                    foreach ($array_time as $time) {
                        $time_array = [
                            "manage_work_id" => $id,
                            "time" => $time,
                            "created_by" => \Auth::id()
                        ];
                        $this->manageRepeatTime->add($time_array);
                    }
                }

                $detail = $this->managerWork->getDetail($id);

                $sendNoti = new SendNotificationApi();

                if (isset($data['manage_status_id']) && $detailOld['manage_status_id'] != $data['manage_status_id']) {
                    if ($data['manage_status_id'] == 3) {
                        $dataNoti = [
                            'key' => 'work_finish',
                            'object_id' => $id,
                        ];
                    } else {
                        $dataNoti = [
                            'key' => 'work_update_status',
                            'object_id' => $id,
                        ];
                    }

                    $sendNoti->sendStaffNotification($dataNoti);
                }

                if (isset($data['processor_id']) && $detailOld['processor_id'] != $data['processor_id']) {
                    $dataNoti = [
                        'key' => 'work_assign',
                        'object_id' => $id,
                    ];
                    $sendNoti->sendStaffNotification($dataNoti);
                }

                if (isset($data['description']) && $detailOld['description'] != $data['description']) {
                    $dataNoti = [
                        'key' => 'work_update_description',
                        'object_id' => $id,
                    ];
                    $sendNoti->sendStaffNotification($dataNoti);
                }


                $dataHistory = [
                    'manage_work_id' => $id,
                    'staff_id' => Auth::id(),
                    'message' => __(' cập nhật thành công công việc'),
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];

                $this->mManageHistory->createdHistory($dataHistory);

                return response()->json(['status' => 1]);
            }
            return response()->json(['status' => 0]);
        }
    }

    //    public function changeStatus(Request $request){
    //        return 1;
    //        if ($request->ajax()) {
    ////            $id = $request->manage_work_id;
    //            return 1;
    //        }
    //    }
    /**
     * Chi tiết công việc tab bình luận
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function detailAction($id)
    {
//        Xóa session kiểm tra nhân viên dự án
        if (session()->has('is_staff_work_project')){
            session()->forget('is_staff_work_project');
        }
//        Kiểm tra công việc có tồn tại
        $detail = $this->managerWork->getDetailWork($id);
        if ($detail == null) {
            return redirect()->route('manager-work');
        }

//        Lấy chi tiết công việc
        $detail = $this->managerWork->getDetail($id);

        $data['manage_work_id'] = $id;
        $listComment = $this->managerWork->getListComment($id);
        return view('manager-work::managerWork.detail', [
                'detail' => $detail,
                'listComment' => $listComment,

            ] + $this->loadDetail());
    }

    /**
     * Lấy giao diện
     * @param Request $request
     */
    public function changeTabDetailWork(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->changeTabDetailWork($param);
        return response()->json($data);
    }

    private function loadDetail()
    {
        return [
            'typeWork' => $this->typeWork->getName(),
            'staffList' => $this->staff->getName(),
            'managerWorkList' => $this->managerWork->getName(),
            'projectList' => $this->project->getName(),
            'customersList' => $this->customers->getFullOption(),
            'manageTagsList' => $this->manageTags->getName(),
            'manageStatusList' => $this->manageStatus->getName(),
            'typeWorkTagsList' => $this->typeWorkTags(),
            'priorityWorkList' => $this->priorityWork(),
        ];
    }

    /**
     * Chi tiết tab lịch sử
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function detailHistoryAction($id)
    {
        $detail = $this->managerWork->getDetail($id);
        $listStaff = $this->managerWork->getListStaff();
        return view('manager-work::managerWork.detail-history', [
                'detail' => $detail,
                'listStaff' => $listStaff
            ] + $this->loadDetail());
    }

    /**
     * Tab tài liệu
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function detailDocumentAction($id)
    {
        $detail = $this->managerWork->getDetail($id);
        $data['manage_work_id'] = $id;
        $listDocument = $this->managerWork->getListDocument($data);
        return view('manager-work::managerWork.detail-document', [
                'detail' => $detail,
                'listDocument' => $listDocument
            ] + $this->loadDetail());
    }

    public function searchDocument(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->searchDocument($param);
        return response()->json($data);
    }

    /**
     * Tab nhắc nhở
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function detailRemindAction($id)
    {
        $detail = $this->managerWork->getDetail($id);
        $data['manage_work_id'] = $id;
        $data['sort_date_remind'] = 'DESC';
        $data['date_remind'] = Carbon::now()->startOfMonth()->format('d/m/Y') . ' - ' . Carbon::now()->endOfMonth()->format('d/m/Y');
        $listRemind = $this->managerWork->getListRemindDetail($data);
        //        lấy danh sách nhân viên

        $listStaff = $this->getListStaff($detail);

        return view('manager-work::managerWork.detail-remind', [
                'detail' => $detail,
                'listRemind' => $listRemind,
                'listStaff' => $listStaff
            ] + $this->loadDetail());
    }

    /**
     * Tab tác vụ con
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function detailChildWorkAction($id)
    {
        $detail = $this->managerWork->getDetail($id);
        $data['manage_work_id'] = $id;
        $listWorkChild = $this->managerWork->getListWorkChild($data);
        $listStatus = $this->managerWork->getListStatus();
        return view('manager-work::managerWork.detail-child-work', [
                'detail' => $detail,
                'listWorkChild' => $listWorkChild,
                'listStatus' => $listStatus
            ] + $this->loadDetail());
    }

    public function getListStaff($detail)
    {
        return $this->managerWork->getListStaffId($detail);
    }

    public function removeAction($id)
    {
        try {
            $mRemind = app()->get(ManageRedmindTable::class);
            $mManageWork = app()->get(ManageWorkTable::class);
            $detail = $this->managerWork->getDetail($id);
            if (isset($detail)) {

                $result = $this->managerWork->remove($id);

//                xóa nhắc nhở
                $mRemind->removeByWork($id);
                $mRemind->removeListByParentTask($id);

//                Xóa công việc con
                $mManageWork->removeWorkByParent($id);
                return response()->json([
                    'error' => 0,
                ]);
            } else {
                return response()->json([
                    'error' => 1,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 1,
            ]);
        }
    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();

        $detailWork = $this->managerWork->getDetailWork($change['manage_work_id']);

        $mManageStatusConfigMap = app()->get(ManageStatusConfigMapTable::class);

        $nextStep = $mManageStatusConfigMap->getListStatusByConfig($detailWork['manage_status_id']);

        if (count($nextStep) != 0) {

            $nextStep = collect($nextStep)->pluck('manage_status_id')->toArray();

            if (!in_array($change['manage_status_id'], $nextStep)) {
                return response()->json([
                    'status' => 1,
                    'message' => __('Trạng thái được thay đổi không phải là trạng thái kế tiếp')
                ]);
            }
        } else {
            return response()->json([
                'status' => 1,
                'message' => __('Cập nhật trạng thái thất bại')
            ]);
        }

        $data['manage_status_id'] = $request->manage_status_id;
        if ($request->manage_status_id == 6) {
            $data['progress'] = 100;
        }
        $data['updated_by'] = Auth::id();
        $this->managerWork->edit($data, $change['manage_work_id']);
        return response()->json([
            'status' => 0,
            'message' => __('Cập nhật trạng thái thành công')
        ]);
    }

    public function editElementItem(Request $request)
    {
        $change = $request->all();
        if ($request->progress) {
            $validated = $request->validate(
                [
                    'progress' => 'required|integer|between:0,100'
                ],
                [
                    'progress.required' => __('Tiến độ không được để trống'),
                    'progress.integer' => __('Tiến độ không hợp lệ'),
                    'progress.between' => __('Tiến độ không hợp lệ'),
                ]
            );
            if (isset($validated->message)) {
                return $validated->message;
            }
            $data['progress'] = $request->progress;
        }
        if ($request->date_end) {
            $date_end = isset($change['time_end']) ? Carbon::createFromFormat('d/m/Y', $change['date_end'])->format('Y-m-d ' . $change['time_end'] . ':00') : Carbon::createFromFormat('d/m/Y', $change['date_end'])->format('Y-m-d 23:00:00');
            if ($request->date_start) {
                $date_start = isset($change['time_start']) ? Carbon::createFromFormat('d/m/Y', $change['date_start'])->format('Y-m-d ' . $change['time_start'] . ':00') : Carbon::createFromFormat('d/m/Y', $change['date_start'])->format('Y-m-d 00:00:00');
                if ($date_start > $date_end) {
                    return [
                        'error' => true,
                        'message' => __('Thời gian bắt đầu phải nhỏ hơn thời gian kết thúc')
                    ];
                }
            }
            $data['date_end'] = $date_end;
        }
        $data['manage_work_id'] = $request->manage_work_id;
        $data['updated_by'] = Auth::id();
        $this->managerWork->edit($data, $change['manage_work_id']);
        return response()->json([
            'error' => false,
            'message' => __("Cập nhật thành công")
        ]);
    }

    public function uploadAction(Request $request)
    {
        $this->validate($request, [
            "manager_work" => "mimes:jpg,jpeg,png,gif,svg|max:10000"
        ], [
            "manager_work.mimes" => __('File không đúng định dạng'),
            "manager_work.max" => __('File quá lớn')
        ]);
        if ($request->file('file') != null) {
            $file = $this->uploadImageTemp($request->file('file'));
            return response()->json(["file" => $file, "success" => "1"]);
        }
    }

    //Lưu file image vào folder temp
    private function uploadImageTemp($file)
    {
        $time = Carbon::now();
        $file_name = rand(0, 9) . time() . date_format($time, 'd') . date_format($time, 'm') . date_format($time, 'Y') . "_manager_work." . $file->getClientOriginalExtension();
        Storage::disk('public')->put(TEMP_PATH . "/" . $file_name, file_get_contents($file));
        return $file_name;
    }

    //Chuyển file từ folder temp sang folder chính
    private function transferTempfileToAdminfile($filename)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = WORK_UPLOADS_PATH . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory(WORK_UPLOADS_PATH . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    //function delete image
    public function deleteTempFileAction(Request $request)
    {
        Storage::disk("public")->delete(TEMP_PATH . '/' . $request->input('filename'));
        return response()->json(['success' => '1']);
    }

    // lưu cấu hình tìm kiếm
    public function saveConfig(Request $request)
    {
        $user_id = \Auth::id();
        $route_name = 'manager-work';
        $name = __('danh sách quản lý công việc');
        $data = [
            'search' => $request->search,
            'column' => $request->column,
        ];
        $data = [
            'value' => serialize($data),
            'user_id' => $user_id,
            'route_name' => $route_name,
            'name' => $name,
        ];
        if ($this->configList->checkExist($user_id, $route_name)) {
            $this->configList->edit($data, $user_id, $route_name);
        } else {
            $this->configList->add($data, $user_id, $route_name);
        }
        return response()->json(['status' => 1, 'data' => $data]);
    }

    // hiển thị cấu hình tìm kiếm
    public function searchColumn($filter = [])
    {
        $mBranch = app()->get(BranchTable::class);

        /*
         Có 3 loại:
            - text
            - datepicker
            - select2 
        */

        // return data search

        $data = [

            1 => [
                "active" => 1,
                "placeholder" => __("Nhập thông tin tìm kiếm"),
                "type" => "text",
                "class" => "form-control",
                "name" => "search",
                "id" => "search",
                "data" => "",
                "nameConfig" => __("Thông tin tìm kiếm"),
            ],
            2 => [
                "active" => 1,
                "placeholder" => __("Chọn trạng thái"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "manage_status_id",
                "id" => "manage_status_id",
                "data" => $this->manageStatus->getName(),
                "nameConfig" => __("Trạng thái"),
            ],
            3 => [
                "active" => 1,
                //                "placeholder" => __("Giao cho tôi"),
                "placeholder" => __("Tất cả công việc"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "assign_by",
                "id" => "assign_by",
                "data" => [
                    1 => __("Tôi hỗ trợ"),
                    2 => __("Tôi tạo"),
                    3 => __("Cần duyệt"),
                    4 => __("Giao cho tôi"),
                    6 => __("Tôi giao"),
                    5 => __("Tất cả công việc"),
                ],
                "nameConfig" => __("Yêu cầu"),
            ],
            4 => [
//                "active" => isset($filter['date_start']) ? 1 : 0,
                "active" => 1,
                "placeholder" => __("Ngày bắt đầu"),
                "type" => "date_picker",
                "class" => "form-control m-input daterange-picker",
                "name" => "date_start",
                "id" => "date_start",
                "data" => "",
                "nameConfig" => __("Ngày bắt đầu"),
            ],
            5 => [
//                "active" => isset($filter['date_end']) ? 1 : 0,
                "active" => 1,
                "placeholder" => __("Ngày hết hạn"),
                "type" => "date_picker",
                "class" => "form-control m-input date-picker",
                "name" => "date_end",
                "id" => "date_end",
                "data" => "",
                "nameConfig" => __("Ngày hết hạn"),
            ],
            //            6 =>[
            //                "active" => 1,
            //                "placeholder" => __("Ngày quá hạn"),
            //                "type" => "daterange_picker",
            //                "class" => "form-control m-input daterange-picker",
            //                "name" => "date_overtime",
            //                "id" => "date_overtime",
            //                "data" => "",
            //                "nameConfig" => __("Ngày quá hạn"),
            //            ],
            11 => [
                "active" => 0,
                "placeholder" => __("Chọn Tag"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "manage_tag_id",
                "id" => "manage_tag_id",
                "data" => $this->manageTags->getName(),
                "nameConfig" => __("Tags"),
            ],
            12 => [
//                "active" => isset($filter['report_staff_id']) ? 1 : 0,
                "active" => 1,
                "placeholder" => __("Chọn người thực hiện"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "processor_id",
                "id" => "processor_id",
                "data" => $this->staff->getName(),
                "nameConfig" => __("Người thực hiện"),
            ],
            13 => [
                "active" => 0,
                "placeholder" => __("Chọn người hỗ trợ"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "manage_work_support_id",
                "id" => "manage_work_support_id",
                "data" => $this->staff->getName(),
                "nameConfig" => __("Người hỗ trợ"),
            ],
            14 => [
                "active" => 0,
                "placeholder" => __("Chọn người tạo"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "created_by",
                "id" => "created_by",
                "data" => $this->staff->getName(),
                "nameConfig" => __("Người tạo"),
            ],
            15 => [
                "active" => 0,
                "placeholder" => __("Chọn người duyệt"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "approve_id",
                "id" => "approve_id",
                "data" => $this->staff->getName(),
                "nameConfig" => __("Người duyệt"),
            ],
            16 => [
                "active" => 0,
                "placeholder" => __("Chọn người cập nhật"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "updated_by",
                "id" => "updated_by",
                "data" => $this->staff->getName(),
                "nameConfig" => __("Người cập nhật"),
            ],
            17 => [
                "active" => 0,
                "placeholder" => __("Chọn loại thẻ"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "type_card_work",
                "id" => "type_card_work",
                "data" => $this->typeWorkTags(),
                "nameConfig" => __("Loại thẻ"),
            ],
            18 => [
                "active" => 1,
                "placeholder" => __("Chọn dự án"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "manage_project_id",
                "id" => "manage_project_id",
                "data" => $this->project->getName(),
                "nameConfig" => __("Dự án"),
            ],
            19 => [
//                "active" => isset($filter['department_id']) ? 1 : 0,
                "active" => 1,
                "placeholder" => __("Chọn phòng ban"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "department_id",
                "id" => "department_id",
                "data" => $this->repoDepartments->getName(),
                "nameConfig" => __("Phòng ban"),
            ],
            20 => [
                "active" => 0,
                "placeholder" => __("Chọn loại công việc"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "manage_type_work_id",
                "id" => "manage_type_work_id",
                "data" => $this->typeWork->getName(),
                "nameConfig" => __("Loại công việc"),
            ],

            21 => [
                "active" => 0,
                "placeholder" => __("Chọn mức độ ưu tiên"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "priority",
                "id" => "priority",
                "data" => $this->priorityWork(),
                "nameConfig" => __("Mức độ ưu tiên"),
            ],

            22 => [
                "active" => 0,
                "placeholder" => __("Ngày hoàn thành"),
                "type" => "daterange_picker",
                "class" => "form-control m-input daterange-picker",
                "name" => "date_finish",
                "id" => "date_finish",
                "data" => "",
                "nameConfig" => __("Ngày hoàn thành"),
            ],

            23 => [
                "active" => 0,
                "placeholder" => __("Ngày cập nhật"),
                "type" => "daterange_picker",
                "class" => "form-control m-input daterange-picker",
                "name" => "updated_at",
                "id" => "updated_at",
                "data" => "",
                "nameConfig" => __("Ngày cập nhật"),
            ],
            24 => [
                "active" => 0,
                "placeholder" => __("Chọn khách hàng"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "customer_id",
                "id" => "customer_id",
                "data" => $this->customers->getName(),
                "nameConfig" => __("Khách hàng"),
            ],

            25 => [
                "active" => 1,
                "placeholder" => __("Kiểu công việc"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "is_parent",
                "id" => "is_parent",
                "data" => [
                    0 => __("Tất cả"),
                    1 => __("Công việc con"),
                    2 => __("Công việc cha"),

                ],
                "nameConfig" => __("Kiểu công việc"),
            ],

            26 => [
                "active" => 0,
                "placeholder" => __("Chi nhánh"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "branch_id",
                "id" => "branch_id",
                "data" => $mBranch->getAll(),
                "nameConfig" => __("Chi nhánh"),
            ],

            27 => [
                "active" => 0,
                "placeholder" => __("Ngày tạo"),
                "type" => "daterange_picker",
                "class" => "form-control m-input daterange-picker",
                "name" => "created_at",
                "id" => "created_at",
                "data" => "",
                "nameConfig" => __("Ngày tạo"),
            ],

            28 => [
                "active" => 1,
                //                "placeholder" => __("Giao cho tôi"),
                "placeholder" => __("Công việc quá hạn"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "work_overdue_search",
                "id" => "work_overdue_search",
                "data" => [
                    1 => __("Tất cả"),
                    2 => __("Hoàn thành"),
                    3 => __("Chưa hoàn thành"),
                ],
                "nameConfig" => __("Công việc quá hạn"),
            ],

            29 => [
                "active" => 1,
                "placeholder" => __("Nguồn công việc"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "create_object_type",
                "id" => "create_object_type",
                "data" => [
                    'all' => __("Tất cả"),
                    'live' => __("Trực tiếp"),
                    'shift' => __("Ca làm việc"),
                    'ticket' => __('Ticket')

                ],
                "nameConfig" => __("Nguồn công việc"),
            ],
            30 => [
                "active" => 1,
                "placeholder" => __('Tần suất lặp lại'),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "repeat_type",
                "id" => "repeat_type",
                "data" => [
                    'all' => __("Tất cả"),
                    'none' => __("Không có"),
                    'daily' => __("Hàng ngày"),
                    'weekly' => __("Hàng tuần một lần mỗi"),
                    'monthly' => __('Hàng tháng')

                ],
                "nameConfig" => __("Tần suất lặp lại"),
            ],
        ];

        $user_id = \Auth::id();
        $route_name = 'manager-work';
        $config = $this->configList->checkExist($user_id, $route_name);
        if (isset($config->value)) {
            $config = unserialize($config->value);
            foreach ($data as $key => $value) {
                if (in_array($key, [2, 3, 4, 5, 12, 18, 19, 25, 29])) {
                    $data[$key]['active'] = 1;
                } else {
                    if (!in_array($key, $config['search'])) {
                        $data[$key]['active'] = 0;
                    } else {
                        $data[$key]['active'] = 1;
                    }
                }

            }
        }

        return $data;
    }

    // hiển thị cấu hình table
    public function showColumn()
    {
        $data = [
            0 => [
                "name" => "#",
                "class" => "",
                "active" => 1,
                "nameConfig" => __("ID"),
                "column_name" => "count",
                "type" => "label"
            ],
            1 => [
                "name" => __("Chức năng công việc"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Chức năng công việc"),
                "type" => "function",
                "attribute" => [
                    "style" => "width:40px"
                ],
            ],
            2 => [
                "name" => __("Nguồn công việc"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Nguồn công việc"),
                "type" => "label",
                "column_name" => "create_object_type",
            ],
            3 => [
                "name" => __("Loại công việc"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Loại công việc"),
                "type" => "image",
                "column_name" => "manage_type_work_icon",
                "attribute" => [
                    "style" => "width:20px;height:20px"
                ],
            ],
            4 => [
                "name" => __("Kiểu vấn đề"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Kiểu vấn đề"),
                "type" => "label",
                "column_name" => "manage_work_parent_name",
            ],
            5 => [
                "name" => __("Công việc cha"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Công việc cha"),
                "type" => "link",
                "column_name" => "manage_work_parent_code",
            ],
            6 => [
                "name" => __("Mã công việc"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Mã công việc"),
                "type" => "label",
                "column_name" => "manage_work_code",
            ],
            7 => [
                "name" => __("Tiêu đề"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Tiêu đề"),
                "column_name" => "manage_work_title",
                "view_detail" => 1,
                "type" => "link",
            ],
            8 => [
                "name" => __("Trạng thái"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Trạng thái"),
                "type" => "status_work",
                "column_name" => "manage_status_name",
            ],
            9 => [
                "name" => __("Tiến độ"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Tiến độ"),
                "type" => "process",
                "column_name" => "progress",
            ],
            10 => [
                "name" => __("Người thực hiện"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Người thực hiện"),
                "type" => "label",
                "column_name" => "processor_id",
            ],
            11 => [
                "name" => __("Ngày bắt đầu"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Ngày bắt đầu"),
                "type" => "label",
                "column_name" => "date_start",
            ],

            12 => [
                "name" => __("Ngày hết hạn"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Ngày hết hạn"),
                "type" => "label",
                "column_name" => "date_end",
            ],
            13 => [
                "name" => __("Tag"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Tag"),
                "type" => "label",
                "column_name" => "tag",
            ],
            14 => [
                "name" => __("Người hỗ trợ"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Người hỗ trợ"),
                "type" => "label",
                "column_name" => "manage_work_support_id",
            ],
            15 => [
                "name" => __("Người tạo"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Người tạo"),
                "type" => "label",
                "column_name" => "created_name",
            ],
            16 => [
                "name" => __("Người duyệt"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Người duyệt"),
                "type" => "label",
                "column_name" => "approve_name",
            ],
            17 => [
                "name" => __("Người cập nhật"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Người cập nhật"),
                "type" => "label",
                "column_name" => "updated_name",
            ],
            18 => [
                "name" => __("Loại thẻ"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Loại thẻ"),
                "type" => "label",
                "column_name" => "type_card_work",
            ],
            19 => [
                "name" => __("Mức độ ưu tiên"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Mức độ ưu tiên"),
                "type" => "label",
                "column_name" => "priority",
            ],
            20 => [
                "name" => __("Ngày cập nhật"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Ngày cập nhật"),
                "type" => "label",
                "column_name" => "updated_at",
            ],
            21 => [
                "name" => __("Ngày hoàn thành"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Ngày hoàn thành"),
                "type" => "label",
                "column_name" => "date_finish",
            ],
            22 => [
                "name" => __("Khách hàng"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Khách hàng"),
                "type" => "label",
                "column_name" => "customer_name",
            ],
            23 => [
                "name" => __("Dự án"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Dự án"),
                "type" => "label",
                "column_name" => "manage_project_name",
            ],

            24 => [
                "name" => __("Ngày tạo"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Ngày tạo"),
                "type" => "label",
                "column_name" => "created_at",
            ],

        ];
        $user_id = \Auth::id();
        $route_name = 'manager-work';
        $config = $this->configList->checkExist($user_id, $route_name);
        if (isset($config->value)) {
            $config = unserialize($config->value);
            foreach ($data as $key => $value) {
                if (!in_array($key, $config['column'])) {
                    $data[$key]['active'] = 0;
                } else {
                    $data[$key]['active'] = 1;
                }

            }
        }
        return $data;
    }

    /**
     * Upload files
     * @param Request $request
     */
    public function uploadFile(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->uploadFile($param);
        return response()->json($data);
    }

    /**
     * Thêm bình luận
     * @param Request $request
     */
    public function addComment(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->addComment($param);
        return response()->json($data);
    }

    /**
     * hiển thị form comment
     * @param Request $request
     */
    public function showFormComment(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->showFormComment($param);
        return response()->json($data);
    }

    /**
     * Search tab lịch sử
     * @param Request $request
     */
    public function searchListHistory(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->searchListHistory($param);
        return response()->json($data);
    }

    /**
     * Hiển thị popup upload file
     * @param Request $request
     */
    public function showPopupUploadFile(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->showPopupUploadFile($param);
        return response()->json($data);
    }

    /**
     * Hiển thị popup upload file work
     * @param Request $request
     */
    public function showPopupUploadFileWork(Request $request)
    {
        $param = $request->all();
        $param['view_popup'] = 'manager-work::managerWork.popup.popup-upload-file-work';
        $data = $this->managerWork->showPopupUploadFile($param);
        return response()->json($data);
    }

    /**
     * Thêm file hồ sơ
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFileDocument(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->addFileDocument($param);
        return response()->json($data);
    }

    public function removeFileDocument(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->removeFileDocument($param);
        return response()->json($data);
    }

    /**
     * Hiển thị popup nhắc nhở
     */
    public function showPopupRemindPopup(Request $request)
    {
        $param = $request->all();
        $detail = $this->managerWork->getDetail($param['manage_work_id']);

        $param['listStaff'] = $this->getListStaff($detail);
        $data = $this->managerWork->showPopupRemindPopup($param);
        return response()->json($data);
    }

    /**
     * Tạo / chỉnh sửa nhắc nhở
     */
    public function addRemindWork(RemindStaffNotStartRequest $request)
    {
        $param = $request->all();
        $detail = $this->managerWork->getDetail($param['popup_manage_work_id']);

        $param['listStaff'] = $this->getListStaff($detail);

        $data = $this->managerWork->addRemindWork($param);

        return response()->json($data);
    }

    /**
     * Xoá nhắc nhở
     * @param Request $request
     */
    public function removeRemind(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->removeRemind($param);
        return response()->json($data);
    }

    /**
     * Tìm kiếm nhắc nhở
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchRemind(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->searchRemind($param);
        return response()->json($data);
    }

    /**
     * Thay đổi trạng thái nhắc nhở
     * @param Request $request
     */
    public function changeStatusRemind(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->changeStatusRemind($param);
        return response()->json($data);
    }

    /**
     * Hiển thị popup công việc
     * @param Request $request
     */
    public function showPopupWorkChild(Request $request)
    {
        //Forget session temp
        session()->forget('staff_support_temp');
        session()->forget('staff_support');
        session()->forget('remove_staff_support');

        $param = $request->all();
        $data = $this->managerWork->showPopupWorkChild($param);
        return response()->json($data);
    }

    /**
     * Lưu công việc
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveChildWork(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->saveChildWork($param);
        return response()->json($data);
    }

    /**
     * Xoá công việc
     */
    public function removeWork(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->removeWork($param);
        return response()->json($data);
    }

    /**
     * Tìm kiếm công việc con
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchWork(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->searchWork($param);
        return response()->json($data);
    }

    public function copyAction(Request $request)
    {
        $detail = $this->managerWork->getDetail($request->id)->toArray();
        $detail = $this->convertObject($detail);
        $request->request->add($detail);
        $addNewWork = $this->saveChildWork($request);
        $addNewWorkChangeJson = json_decode($addNewWork->content(), true);
        if ($addNewWorkChangeJson['error'] == true) {
            return $addNewWork;
        }


        $getListWorkChild = $this->managerWork->getListWorkChildInsert($request->id);

        foreach ($getListWorkChild as $item) {
            $item['parent_id'] = $addNewWorkChangeJson['manage_work_id'];
            $item['type_copy_child'] = true;
            $item = $this->convertObject($item);
            $request->request->add(collect($item)->toArray());
            $this->saveChildWork($request);
        }

        return $addNewWork;
    }

    public function convertObject($data)
    {
        $listSupport = $data['list_support'];
        $listTag = $data['list_tag'];
        unset(
            $data['manage_work_id'],
            $data['manage_work_code'],
            $data['repeat_type'],
            $data['repeat_end'],
            $data['repeat_end_time'],
            $data['repeat_end_type'],
            $data['repeat_end_full_time'],
            $data['repeat_time'],
            $data['customer_name'],
            $data['manage_type_work_name'],
            $data['manage_project_name'],
            $data['parent_manage_work_code'],
            $data['parent_manage_work_title'],
            $data['createdStaff_name'],
            $data['approve_name'],
            $data['staff_name'],
            $data['manage_status_name'],
            $data['list_support'],
            $data['list_tag']
        );

        $data['date_start'] = Carbon::parse($data['date_start'])->format('d/m/Y H:i');
        $data['date_end'] = Carbon::parse($data['date_end'])->format('d/m/Y H:i');
        $data['support'] = collect($listSupport)->pluck('staff_id', 'staff_id');
        $data['progress'] = 0;
        $data['manage_status_id'] = 1;

        $data['manage_tag'] = collect($listTag)->pluck('manage_tag_id', 'manage_tag_id');
        $data['type_copy'] = true;
        return $data;
    }

    public function approveAction(Request $request)
    {

        $detail = $this->managerWork->edit([
            'manage_status_id' => 6,
            'progress' => 100,
            'updated_at' => Carbon::now(),
            'updated_by' => Auth::id()
        ], $request->id);
        $sendNoti = new SendNotificationApi();
        $dataNoti = [
            'key' => 'work_update_status',
            'object_id' => $request->id,
        ];
        $sendNoti->sendStaffNotification($dataNoti);

        return response()->json(1);
    }

    public function rejectAction(Request $request)
    {
        $detail = $this->managerWork->edit([
            'manage_status_id' => 5,
            'updated_at' => Carbon::now(),
            'updated_by' => Auth::id()
        ], $request->id);
        $sendNoti = new SendNotificationApi();
        $dataNoti = [
            'key' => 'work_update_status',
            'object_id' => $request->id,
        ];

        $sendNoti->sendStaffNotification($dataNoti);

        return response()->json(1);
    }

    public function exportAction(Request $request)
    {
        $filters = $request->only([
            'page', 'display',
            'search', 'manage_status_id', 'assign_by', 'created_at',
            'date_end', 'date_overtime', 'manage_tag_id', 'processor_id',
            'manage_work_support_id', 'created_by', 'approve_id', 'updated_by',
            'type_card_work', 'manage_project_id', 'department_id', 'manage_type_work_id',
            'priority', 'date_finish', 'updated_at', 'customer_id', 'manage_work_customer_type', 'date_start', 'is_parent', 'type-search', 'type-page'
        ]);

        $filters['page'] = 'all';
        $list = $this->managerWork->list($filters);
        $arrSupport = $arrTag = [];
        if ($list) {
            $listWork = collect($list)->pluck('manage_work_id')->toArray();
            $listSupport = $this->manageWorkSupportRepo->getListByWork($listWork);
            $listTag = $this->manageWorkTagRepo->getListByWork($listWork);
            foreach ($listSupport as $itemS) {
                $arrSupport[$itemS['manage_work_id']][] = $itemS['staff_name'];
            }
            foreach ($listTag as $itemT) {
                $arrTag[$itemT['manage_work_id']][] = $itemT['manage_tag_name'];
            }
        }

        $data = [
            'list' => $list,
            'arrSupport' => $arrSupport,
            'listTag' => $arrTag,
            'listColumn' => $this->showColumn()
        ];
        return $this->managerWork->export($data);
    }

    public function loadComment(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->manage_work_id;
            if ($id) {
                $item = $this->managerWork->getItem($id);
                $returnModal = view('manager-work::managerWork.popup.comment-popup', [
                    'comment' => $item->countComment,
                    'manage_work_id' => $id
                ])->render();
                return response()->json(['error' => 0, 'data' => $returnModal]);
            }
            return response()->json(['error' => 1, 'data' => []]);
        }
    }

    public function loadFormUpdateProcess(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->manage_work_id;
            if ($id) {
                $item = $this->managerWork->getItem($id);
                $returnModal = view('manager-work::managerWork.popup.update_process', [
                    'progress' => $item->progress,
                    'manage_work_id' => $id
                ])->render();
                return response()->json(['error' => 0, 'data' => $returnModal]);
            }
            return response()->json(['error' => 1, 'data' => []]);
        }
    }

    public function loadFormUpdateDateEnd(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->manage_work_id;
            if ($id) {
                $detail = $this->managerWork->getDetail($id);
                $returnModal = view('manager-work::managerWork.popup.update_date_end', [
                    'detail' => $detail,
                    'manage_work_id' => $id
                ])->render();
                return response()->json(['error' => 0, 'data' => $returnModal]);
            }
            return response()->json(['error' => 1, 'data' => []]);
        }
    }

    /**
     * Lấy danh sách khách hàng
     * @param Request $request
     */
    public function changeCustomer(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->changeCustomer($param);
        return response()->json($data);
    }

    /**
     * Hiển thị popup chuyển folder
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupChangeFolder(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->popupChangeFolder($param);
        return response()->json($data);
    }

    /**
     * Lưu di chuyển tài liệu
     * @param Request $request
     */
    public function submitChangeFolder(MoveFileRequest $request)
    {
        $param = $request->all();
        $data = $this->managerWork->submitChangeFolder($param);
        return response()->json($data);
    }

    /**
     * Hiển thị popup danh sách nhân viên hỗ trợ
     */
    public function showPopupStaff(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->showPopupStaff($param);
        return response()->json($data);
    }

    /**
     * Search
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchPagePopupStaff(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->searchPagePopupStaff($param);
        return response()->json($data);
    }

    /**
     * Đổi branch lấy danh sách nhân viên
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeBranchStaff(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->changeBranchStaff($param);
        return response()->json($data);
    }

    /**
     * Kiểm tra số lượng công việc con
     * @param Request $request
     */
    public function checkWorkChild(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->checkWorkChild($param);
        return response()->json($data);
    }

    /**
     * Kiểm tra ngày bắt đầu và ngày kết thúc của dự án và công việc
     * @param Request $request
     */
    public function checkDateWorkProject(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->checkDateWorkProject($param);
        return response()->json($data);
    }

    /**
     * Lấy danh sách tác vụ cha
     */
    public function getListParentTask(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->getListParentTask($param);
        return response()->json($data);
    }

    public function changeParentTask(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->changeParentTask($param);
        return response()->json($data);
    }

    /**
     * Lấy danh sách nhân viên dựa theo dự án
     * @param Request $request
     */
    public function changeListStaff(Request $request)
    {
        $param = $request->all();
        $data = $this->managerWork->changeListStaff($param);
        return response()->json($data);
    }

    /**
     * Show pop chọn nhân viên hỗ trợ
     *
     * @return JsonResponse
     */
    public function showPopStaffSupportAction(Request $request)
    {
        $data = $this->managerWork->showPopStaffSupport($request->all());

        return response()->json($data);
    }

    /**
     * Ajax load ds nhân viên hỗ trợ
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function listStaffSupportAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'staffs$branch_id',
            'staffs$department_id',
            'staffs$staff_id',
            'search'
        ]);

        //Danh sách nhân viên
        $list = $this->managerWork->listStaffSupport($filter);

        return view('manager-work::managerWork.popup.list-staff-support', $list);
    }

    /**
     * Chọn nhân viên hỗ trợ
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function chooseStaffSupportAction(Request $request)
    {
        $arrCheckTemp = [];
        $arrCheck = [];

        //Lấy session tạm
        if (session()->get('staff_support_temp')) {
            $arrCheckTemp = session()->get('staff_support_temp');
        }

        //Get session chính
        if (session()->get('staff_support')) {
            $arrCheck = session()->get('staff_support');
        }

        //Merge 2 mãng lại
        $arrTotal = array_replace_recursive($arrCheckTemp, $arrCheck);

        if (isset($request->arrChoose) && count($request->arrChoose) > 0) {
            foreach ($request->arrChoose as $v) {
                //Push nhân viên mới chọn vào
                $arrTotal[$v['staff_id']] = [
                    "staff_id" => $v['staff_id']
                ];
            }
        }

        //Forget session temp
        session()->forget('staff_support_temp');
        //Put session temp
        session()->put('staff_support_temp', $arrTotal);

        return response()->json([
            'number_staff' => count($arrTotal)
        ]);
    }

    /**
     * Bỏ chọn nhân viên hỗ trợ
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function unChooseStaffSupportAction(Request $request)
    {
        $arrCheckTemp = [];
        $arrCheck = [];
        $arrRemove = [];

        //Lấy session tạm
        if (session()->get('staff_support_temp')) {
            $arrCheckTemp = session()->get('staff_support_temp');
        }

        //Get session chính
        if (session()->get('staff_support')) {
            $arrCheck = session()->get('staff_support');
        }

        //Merge 2 mãng lại
        $arrTotal = array_replace_recursive($arrCheckTemp, $arrCheck);

        if (isset($request->arrUnChoose) && count($request->arrUnChoose)) {
            foreach ($request->arrUnChoose as $v) {
                //Unset các nhân viên bỏ chọn
                unset($arrTotal[$v['staff_id']]);

                $arrRemove [] = $v['staff_id'];
            }
        }

        //Forget session temp
        session()->forget('staff_support_temp');
        //Put session temp
        session()->put('staff_support_temp', $arrTotal);

        //Get session remove temp
        if (session()->get('remove_staff_support')) {
            $arrRemove = session()->get('remove_staff_support');
        }

        //Lưu session remove temp
        session()->forget('remove_staff_support');
        session()->put('remove_staff_support', $arrRemove);

        return response()->json([
            'number_staff' => count($arrTotal)
        ]);
    }

    /**
     * Submit chọn nhân viên hỗ trợ
     *
     * @return JsonResponse
     */
    public function submitChooseStaffSupportAction()
    {
        $data = $this->managerWork->submitChooseStaffSupport();

        return response()->json($data);
    }

    /**
     * Xoá nhân viên đã chọn
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function removeStaffSupportAction(Request $request)
    {
        $data = $this->managerWork->removeStaffSupport($request->all());

        return response()->json($data);
    }
}
