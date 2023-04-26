<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 * 
 */

namespace Modules\ManagerWork\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Modules\ManagerWork\Models\Customers;
use Modules\ManagerWork\Models\StaffTable;
use Modules\ManagerWork\Models\BranchTable;
use Modules\CustomerLead\Models\ManageWorkTable;
use Modules\ManagerWork\Models\ManageRedmindTable;
use Modules\ManagerWork\Models\ManageWorkTagTable;
use Modules\ManagerWork\Models\ManagerHistoryTable;
use Modules\ManagerWork\Http\Controllers\Controller;
use Modules\ManagerWork\Http\Api\SendNotificationApi;
use Modules\ManagerWork\Models\ManageRepeatTimeTable;
use Modules\ManagerWork\Models\ManagerConfigListTable;
use Modules\ManagerWork\Models\ManageWorkSupportTable;
use Modules\ManagerWork\Models\ManageStatusConfigMapTable;
use Modules\ManagerWork\Http\Requests\Document\MoveFileRequest;
use Modules\ManagerWork\Repositories\Departments\DepartmentsRepo;
use Modules\ManagerWork\Repositories\Departments\DepartmentsInterface;
use Modules\ManagerWork\Http\Requests\Remind\RemindStaffNotStartRequest;
use Modules\ManagerWork\Repositories\Project\ProjectRepositoryInterface;
use Modules\ManagerWork\Repositories\TypeWork\TypeWorkRepositoryInterface;
use Modules\ManagerWork\Repositories\ManagerWorkTag\ManagerWorkTagInterface;
use Modules\ManagerWork\Repositories\ManageTags\ManageTagsRepositoryInterface;
use Modules\ManagerWork\Repositories\ManagerWork\ManagerWorkRepositoryInterface;
use Modules\ManagerWork\Repositories\ManageStatus\ManageStatusRepositoryInterface;
use Modules\ManagerWork\Repositories\ManageRedmind\ManageRedmindRepositoryInterface;
use Modules\ManagerWork\Repositories\ManagerWorkSupport\ManagerWorkSupportInterface;

class ManagerWorkController extends Controller
{
    protected $managerWork;
    protected $configList;
    protected $manageStatus;
    protected $typeWork;
    protected $staff;
    protected $project;
    protected $customers;
    protected $manageTags;
    protected $manageWorkSupport;
    protected $manageWorkSupportRepo;
    protected $manageWorkTag;
    protected $manageRemind;
    protected $manageRepeatTime;
    protected $mManageHistory;
    protected $manageWorkTagRepo;
    protected $repoDepartments;

    public function __construct(
        ManagerWorkRepositoryInterface $managerWork,
        TypeWorkRepositoryInterface $typeWork,
        ManagerConfigListTable $configList,
        ProjectRepositoryInterface $project,
        ManageTagsRepositoryInterface $manageTags,
        ManageStatusRepositoryInterface $manageStatus,
        ManageRedmindRepositoryInterface $manageRemind,
        StaffTable $staff,
        ManageWorkSupportTable $manageWorkSupport,
        ManageWorkTagTable $manageWorkTag,
        Customers $customers,
        ManageRepeatTimeTable $manageRepeatTime,
        ManagerHistoryTable $mManageHistory,
        ManagerWorkSupportInterface $managerWorkSupportRepository,
        ManagerWorkTagInterface $managerWorkTagRepository,
        DepartmentsInterface $infDepartments
    ) {
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

    public function getSearchOption()
    {
        return [
            'searchConfig' => $this->searchColumn()
        ];
    }

    public function getListWork(Request $request)
    {

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
        } else {
            $filters['date_start'] = Carbon::parse(str_replace('/', '-', $filters['date_start']))->format('d/m/Y');
        }

        if (!isset($filters['date_end'])) {
            $filters['date_end'] = Carbon::now()->endOfMonth()->format('d/m/Y');
        } else {
            $filters['date_end'] = Carbon::parse(str_replace('/', '-', $filters['date_end']))->format('d/m/Y');
        }

        if (isset($filters['manage_status_id'])) {
            $filters['manage_status_id'] = (array)$filters['manage_status_id'];
        }

        $filters['perpage'] = 10000;

        $listWorks = $this->managerWork->list($filters)->load('workSupportListAvatar', 'countComment');
        $statusLists = $this->manageStatus->getName();
        $colorStatus = $this->manageStatus->getColorList();

        if ($listWorks) {
            $listWorks = $listWorks->groupBy('manage_status_id');
            $groupListWork = [];

            foreach ($listWorks as $key => $work) {
                $workFirst = $work->first();
                $data['manage_status_id'] = $key;
                $data['manage_status_name'] = $workFirst->manage_status_name ?? "";
                $data['manage_color_code'] = $workFirst->manage_color_code ?? "";
                $data['count'] = $work->count();
                $data['items'] = $work->toArray();

                $groupListWork[$key] = $data;
            }

            $dataAppend = [];
            foreach ($statusLists as $keyStatus => $valueStatus) {
                if (isset($groupListWork[$keyStatus])) {
                    $dataAppend[] = $groupListWork[$keyStatus];
                } else {
                    $temp['manage_status_id'] = $keyStatus;
                    $temp['manage_status_name'] = $valueStatus;
                    $temp['manage_color_code'] = $colorStatus[$keyStatus];
                    $temp['count'] = 0;
                    $temp['items'] = [];
                    $dataAppend[] = $temp;
                }
            }
        }

        return [
            'lists' => $dataAppend,
            'filters' => $filters,
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

        ];

        $user_id = \Auth::id();
        $route_name = 'manager-work';
        $config = $this->configList->checkExist($user_id, $route_name);
        if (isset($config->value)) {
            $config = unserialize($config->value);
            foreach ($data as $key => $value) {
                if (in_array($key, [2, 3, 4, 5, 12, 18, 19, 25])) {
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
            3 => [
                "name" => __("Kiểu vấn đề"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Kiểu vấn đề"),
                "type" => "label",
                "column_name" => "manage_work_parent_name",
            ],
            4 => [
                "name" => __("Công việc cha"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Công việc cha"),
                "type" => "link",
                "column_name" => "manage_work_parent_code",
            ],
            5 => [
                "name" => __("Mã công việc"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Mã công việc"),
                "type" => "label",
                "column_name" => "manage_work_code",
            ],
            6 => [
                "name" => __("Tiêu đề"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Tiêu đề"),
                "column_name" => "manage_work_title",
                "view_detail" => 1,
                "type" => "link",
            ],
            7 => [
                "name" => __("Trạng thái"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Trạng thái"),
                "type" => "status_work",
                "column_name" => "manage_status_name",
            ],
            8 => [
                "name" => __("Tiến độ"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Tiến độ"),
                "type" => "process",
                "column_name" => "progress",
            ],
            9 => [
                "name" => __("Người thực hiện"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Người thực hiện"),
                "type" => "label",
                "column_name" => "processor_id",
            ],
            10 => [
                "name" => __("Ngày bắt đầu"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Ngày bắt đầu"),
                "type" => "label",
                "column_name" => "date_start",
            ],

            11 => [
                "name" => __("Ngày hết hạn"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Ngày hết hạn"),
                "type" => "label",
                "column_name" => "date_end",
            ],
            12 => [
                "name" => __("Tag"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Tag"),
                "type" => "label",
                "column_name" => "tag",
            ],
            13 => [
                "name" => __("Người hỗ trợ"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Người hỗ trợ"),
                "type" => "label",
                "column_name" => "manage_work_support_id",
            ],
            14 => [
                "name" => __("Người tạo"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Người tạo"),
                "type" => "label",
                "column_name" => "created_name",
            ],
            15 => [
                "name" => __("Người duyệt"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Người duyệt"),
                "type" => "label",
                "column_name" => "approve_name",
            ],
            16 => [
                "name" => __("Người cập nhật"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Người cập nhật"),
                "type" => "label",
                "column_name" => "updated_name",
            ],


            17 => [
                "name" => __("Loại thẻ"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Loại thẻ"),
                "type" => "label",
                "column_name" => "type_card_work",
            ],
            18 => [
                "name" => __("Mức độ ưu tiên"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Mức độ ưu tiên"),
                "type" => "label",
                "column_name" => "priority",
            ],
            19 => [
                "name" => __("Ngày cập nhật"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Ngày cập nhật"),
                "type" => "label",
                "column_name" => "updated_at",
            ],

            20 => [
                "name" => __("Ngày hoàn thành"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Ngày hoàn thành"),
                "type" => "label",
                "column_name" => "date_finish",
            ],
            21 => [
                "name" => __("Khách hàng"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Khách hàng"),
                "type" => "label",
                "column_name" => "customer_name",
            ],
            22 => [
                "name" => __("Dự án"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Dự án"),
                "type" => "label",
                "column_name" => "manage_project_name",
            ],

            23 => [
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
}
