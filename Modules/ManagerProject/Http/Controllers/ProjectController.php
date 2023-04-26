<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\ManagerProject\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\DealDetailTable;
use Modules\ManagerProject\Http\Requests\Project\ProjectStoreRequest;
use Modules\ManagerProject\Http\Requests\Project\ProjectUpdateRequest;
use Modules\ManagerProject\Models\Customers;
use Modules\ManagerProject\Models\DepartmentTable;
use Modules\ManagerProject\Models\ManageProjectStatusConfigMapTable;
use Modules\ManagerProject\Models\ManageStatusTable;
use Modules\ManagerProject\Models\ManageTagsTable;
use Modules\ManagerProject\Models\ProjectStatusTable;
use Modules\ManagerProject\Models\StaffsTable;
use Modules\ManagerProject\Repositories\Contract\ContractRepositoryInterface;
use Modules\ManagerProject\Repositories\ManageProjectContact\ManageProjectContactRepositoryInterface;
use Modules\ManagerProject\Repositories\ManageProjectPhare\ManageProjectPhareRepositoryInterface;
use Modules\ManagerProject\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportFile;
use Modules\ManagerWork\Models\BranchTable;
use Modules\ManagerWork\Models\ManageProjectStaffTable;
use phpDocumentor\Reflection\DocBlock\Description;
use Modules\ManagerProject\Repositories\ManagerWork\ManagerWorkRepositoryInterface;
use Modules\ManagerWork\Repositories\TypeWork\TypeWorkRepositoryInterface;
use Modules\ManagerWork\Models\ManagerConfigListTable;
use Modules\ManagerWork\Repositories\ManageTags\ManageTagsRepositoryInterface;
use Modules\ManagerWork\Repositories\ManageStatus\ManageStatusRepositoryInterface;
use Modules\ManagerWork\Repositories\ManageRedmind\ManageRedmindRepositoryInterface;
use Modules\ManagerWork\Models\StaffTable;
use Modules\ManagerWork\Models\ManageWorkSupportTable;
use Modules\ManagerWork\Models\ManageWorkTagTable;
use Modules\ManagerWork\Models\ManageRepeatTimeTable;
use Modules\ManagerWork\Models\ManagerHistoryTable;
use Modules\ManagerWork\Repositories\ManagerWorkSupport\ManagerWorkSupportInterface;
use Modules\ManagerWork\Repositories\ManagerWorkTag\ManagerWorkTagInterface;
use Modules\ManagerWork\Repositories\Departments\DepartmentsInterface;

class ProjectController extends Controller
{
    protected $project;
    protected $managerWork;
    protected $configList;
    protected $typeWork;
    protected $staff;
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

    public function indexAction(Request $request)
    {
        // Danh sách trạng thái dự án
        $mStatusProject = app()->get(ProjectStatusTable::class);
        $listStatus = $mStatusProject->getAll();
        // Danh nhân viên (người quản trị)
        $mStaffs = app()->get(StaffsTable::class);
        $listStaffs = $mStaffs->getAll();
        $listColumnConfig = $this->project->getConfigListProject();
        $listProject =  $this->project->list();

        return view('manager-project::project.index', [
            'list' => $listProject,
            'listStatus' => $listStatus,
            'listStaffs' => $listStaffs,
            'listColumnConfig' => $listColumnConfig
        ]);
    }

    public function listAction(Request $request)
    {
        $filters = $request->all();
        $listColumnConfig = $this->project->getConfigListProject();
        $view = view(
            'manager-project::project.list',
            [
                'list' => $this->project->list($filters),
                'listColumnConfig' => $listColumnConfig
            ]
        )->render();
        return [
            'view' => $view,
            'error' => false
        ];
    }
    /**
     * Hiển thị form thêm dự án
     * @return view
     */
    public function addAction()
    {
        $rContract = app()->get(ContractRepositoryInterface::class);
        // Danh nhân viên (người quản trị)
        $mStaffs = app()->get(StaffsTable::class);
        $listStaffs = $mStaffs->getAll();
        // Danh sách trạng thái dự án
        $mStatusProject = app()->get(ProjectStatusTable::class);
        $listStatus = $mStatusProject->getAll();
        // Danh sách phòng ban
        $mDepartment = app()->get(DepartmentTable::class);
        $listDepartment = $mDepartment->getAll();
        // Danh sách tags
        $mTags = app()->get(ManageTagsTable::class);
        $listTag = $mTags->getAll();

        $listContract = $rContract->getAllContractUsing();

        return view('manager-project::project.add', [
            'listStaffs' => $listStaffs,
            'listStatus' => $listStatus,
            'listDepartment' => $listDepartment,
            'listTag' => $listTag,
            'listContract' => $listContract
        ]);
    }

    /**
     * Tạo dự án
     * @param $request Request
     * @return mixed
     */

    public function storeAction(ProjectStoreRequest $request)
    {
        $params = $request->all();
        $result = $this->project->store($params);
        return response()->json($result);
    }

    /**
     * View hiển thị cập nhật dự án
     * @return view
     */
    public function editAction($id)
    {
        $rContract = app()->get(ContractRepositoryInterface::class);
        $mManageProjectStatusConfigMap = app()->get(ManageProjectStatusConfigMapTable::class);
        $rManageProjectContact = app()->get(ManageProjectContactRepositoryInterface::class);

        // Dự án
        $project = $this->project->getItemProject($id);
        // danh sách tags đã chọn
        $listTagSelected = $project->tags->pluck('manage_tag_id')->toArray();
        // Danh nhân viên (người quản trị)
        $mStaffs = app()->get(StaffsTable::class);
        $listStaffs = $mStaffs->getAll();
        // Danh sách trạng thái dự án (Lấy danh sách trạng thái kế tiếp)
        $mStatusProject = app()->get(ProjectStatusTable::class);

//        Lấy danh sách trạng thái kế tiếp
        $listNextStatus = $mManageProjectStatusConfigMap->getListStatusByConfig($project['manage_project_status_id']);

        $arrStatus = [];
        if (count($listNextStatus) != 0){
            $arrStatus = collect($listNextStatus)->pluck('manage_project_status_id')->toArray();
        }

        $arrStatus[] = $project['manage_project_status_id'];

        $listStatus = $mStatusProject->getAll(['arr_status'=> $arrStatus]);
        // Danh sách phòng ban
        $mDepartment = app()->get(DepartmentTable::class);
        $listDepartment = $mDepartment->getAll();
        // Danh sách tags
        $mTags = app()->get(ManageTagsTable::class);
        $listTag = $mTags->getAll();
        $listContract = $rContract->getAllContractUsing();

        $listContact = $rManageProjectContact->getListByIdProject($id);

        return view('manager-project::project.edit', [
            'listStaffs' => $listStaffs,
            'listStatus' => $listStatus,
            'listDepartment' => $listDepartment,
            'listTag' => $listTag,
            'project' => $project,
            'listTagSelected' => $listTagSelected,
            'listContract' => $listContract,
            'listContact' => $listContact
        ]);
    }

    /**
     * Cập nhật dự án
     * @param Request $request
     * @return mixed
     */
    public function updateAction(ProjectUpdateRequest $request)
    {
        $params = $request->all();
        $result = $this->project->update($params);
        return response()->json($result);
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->manage_project_id;
            $manage_project_name = $request->manage_project_name;
            $checkExist = $this->project->checkExist($manage_project_name, $id);
            if ($checkExist == null) {
                $data = [
                    'manage_project_name' => $request->manage_project_name,
                    'updated_by' => Auth::id(),
                ];

                if ($this->project->edit($data, $id)) {
                    return response()->json(['status' => 1]);
                }
                return response()->json(['status' => 2]);
            } else {
                return response()->json(['status' => 0]);
            }
        }
    }

    public function removeAction($id)
    {
        $result = $this->project->remove($id);
        return response()->json($result);
    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_active'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $data['updated_by'] =  Auth::id();
        $this->project->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }


    /**
     * Lấy danh sách khách hàng loại khách hàng
     * @return mixed
     */
    public function getListCustomerByType(Request $request)
    {
        $type = $request->type;
        $mCustomers = app()->get(Customers::class);
        $listCustomer = $mCustomers->getAllByType($type);
        $rs = [
            'error' => true,
            'data' => []
        ];
        if ($listCustomer->count() > 0) {
            $rs = [
                'error' => false,
                'data' => $listCustomer
            ];
        }
        return response()->json($rs);
    }

    /**
     * Lấy tên tiền tố dự án ngẫu nhiên
     * @return string
     */

    public function getNamePrefix(Request $request)
    {
        $param = $request->nameDefault;
        $result = $this->project->getNamePrefix($param);
        return response()->json($result);
    }

    /**
     * Cấu hình dự án
     * @param Request $request
     * @return mixed
     */

    public function configListProject(Request $request)
    {
        $params = $request->all();
        $result = $this->project->configListProject($params);
        return response()->json($result);
    }

    /**
     * Chi tiết dự án tab thông tin chung
     * @param $idProject
     * @return view
     */

    public function showAction($idProject)
    {
        $project = $this->project->getDetailFix($idProject);

        return view('manager-project::project.show', ['project' => $project, 'idProject' => $idProject]);
    }
    //danh sách phòng ban
    public function getDepartment()
    {
        $data = $this->project ->getDepartment();
        return $data;
    }
    //danh sách chi nhánh
    public function getBranch()
    {
        $data = $this->project ->getBranch();
        return $data;
    }

    public  function projectInfoOverview($id){
        $info = $this->getProjectInfo($id);
            return view('manager-project::project-info.index',['info' => $info]);
    }
    public function getProjectInfo($id){
        $data = $this->project->getProjectInfo($id);
        return $data;
    }
    public function projectInfoAllIssue($id){
        $data = $this->project->getAllIssueProject($id);
        $info = $this->getProjectInfo($id);
        return view('manager-project::project-info.all-issue',[
            'data' => $data,
            'id' => $id,
            'info' => $info
        ]);
    }
    public function popupAddRemind(Request $request){
        $id = $request->id;
        $view = view('manager-project::remind.add-remind',['id' => $id])->render();
        $data = [
            'view' => $view
        ];
        return \response()->json($data);
    }
    public function deleteRemind(Request $request){
        $data = $this->project->deleteRemind($request->all());
        return \response()->json($data);
    }
    public  function projectInfoReport($id){
        $info = $this->project->projectInfoReport($id);
        $info['department'] = $this->getDepartment();
        return view('manager-project::project-info.report',['info' => $info]);
    }
    public  function projectInfoWork($id , Request $request){
        $info = $this->project->projectInfoWork($id);
        // Danh sách trạng thái
        $listStatus = $this->project->getStatus();
        //danh sách kiểu công việc
        $listTypeWork = $this->project->getTypeWork();
        //danh sách nhân vien
        $listStaff= $this->project->listStaff();

        $param = $request->all();
        $param['manage_project_id'] = $id;
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

            $columnList = $this->showColumn();

            $columnList = collect($columnList)->where('active', 1);
            if (count($columnList) != 0) {
                $columnList = collect($columnList)->keys()->toArray();
            }

            $user_id = \Auth::id();
            $route_name = 'manager-project.work';
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
        if (isset($param['date_end']) && $param['date_end'] != null && !isset($param['none_time'])) {
//            $filter['date_end'] = Carbon::parse($param['date_end'])->format('d/m/Y') . ' - ' . Carbon::parse($param['date_end'])->format('d/m/Y');
            $filter['date_end'] = Carbon::parse($param['date_end'])->format('d/m/Y');
        }

        if (isset($param['date_start']) && $param['date_start'] != null && !isset($param['none_time'])) {
//            $filter['date_end'] = Carbon::parse($param['date_end'])->format('d/m/Y') . ' - ' . Carbon::parse($param['date_end'])->format('d/m/Y');
            $filter['date_start'] = Carbon::parse($param['date_start'])->format('d/m/Y');
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

        if (isset($param['customer_id']) && $param['customer_id'] != null) {
            $filter['customer_id'] = $param['customer_id'];
        }

        if (isset($param['manage_work_customer_type']) && $param['manage_work_customer_type'] != null) {
            $filter['manage_work_customer_type'] = $param['manage_work_customer_type'];
        }

        if (isset($param['manage_project_id']) && $param['manage_project_id'] != null) {
            $filter['manage_project_id'] = $param['manage_project_id'];
        }

        if (isset($param['manage_status_id'])){
            $filter['manage_status_id'] = $param['manage_status_id'];
            foreach ($filter['manage_status_id'] as $key => $item ){
                $filter['manage_status_id'][$key] = (int)$item;
            }
        }

        if (isset($param['type-search'])){
            $filter['type-search'] = $param['type-search'];
        }

        if (isset($param['type-page'])){
            $filter['type-page'] = $param['type-page'];
        }

        if (isset($param['support_id'])){
            $filter['manage_work_support_id'] = $param['support_id'];
        }

        $rManageWork=
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
        $project = null;
        $listStaffManage = [];
        $listStaffProject = [];
        if (isset($param['manage_project_id'])) {
            $rProject = app()->get(\Modules\ManagerWork\Repositories\Project\ProjectRepositoryInterface::class);
            $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);
            $project = $rProject->getDetailFix($param['manage_project_id']);
            $listStaffManage = $mManageProjectStaff->getListAdmin($param['manage_project_id'],'administration');
            $listStaffProject = $mManageProjectStaff->getListAdmin($param['manage_project_id']);
            if (count($listStaffManage) != 0){
                $listStaffManage = collect($listStaffManage)->pluck('staff_id')->toArray();
            }

            if (count($listStaffProject) != 0){
                $listStaffProject = collect($listStaffProject)->pluck('staff_id')->toArray();
            }
        }

        $rManageProjectPhare = app()->get(ManageProjectPhareRepositoryInterface::class);

//        Danh sách giai đoạn
        $listPhare = $rManageProjectPhare->getAllPhareByProject($param['manage_project_id']);

        return view('manager-project::project-info.work',[
            'info' => $info,
            'listStatus' => $listStatus,
            'department' => $this->getDepartment(),
            'listTypeWork' => $listTypeWork,
            'listStaff' => $listStaff,
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
            'params' => $filter,
            'project' => $project,
            'listStaffManage'=> $listStaffManage,
            'listStaffProject' => $listStaffProject,
            'listPhare' => $listPhare
        ]);
    }

    public function projectInfoWorkList(Request $request)
    {
        $filters = $request->only([
            'page', 'display','branch_id','created_at','work_overdue_search',
            'search', 'manage_status_id','assign_by', 'created_at',
            'date_end','date_overtime','manage_tag_id', 'processor_id',
            'manage_work_support_id','created_by', 'approve_id', 'updated_by',
            'type_card_work','manage_project_id', 'department_id', 'manage_type_work_id',
            'priority','date_finish', 'updated_at', 'customer_id','manage_work_customer_type','date_start','is_parent','type-search','type-page','manage_project_phase_id'
        ]);

        $param = $request->all();

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

        $project = null;
        $listStaffManage = [];
        $listStaffProject = [];
        if (isset($param['manage_project_id'])) {
            $rProject = app()->get(\Modules\ManagerWork\Repositories\Project\ProjectRepositoryInterface::class);
            $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);
            $project = $rProject->getDetailFix($param['manage_project_id']);
            $listStaffManage = $mManageProjectStaff->getListAdmin($param['manage_project_id'],'administration');
            $listStaffProject = $mManageProjectStaff->getListAdmin($param['manage_project_id']);
            if (count($listStaffManage) != 0){
                $listStaffManage = collect($listStaffManage)->pluck('staff_id')->toArray();
            }

            if (count($listStaffProject) != 0){
                $listStaffProject = collect($listStaffProject)->pluck('staff_id')->toArray();
            }
        }

        $rManageProjectPhare = app()->get(ManageProjectPhareRepositoryInterface::class);

//        Danh sách giai đoạn
        $listPhare = $rManageProjectPhare->getAllPhareByProject($param['manage_project_id']);

        return view(
            'manager-project::project-info.work-list',
            [
                'list' => $list,
                'listSupport' => $arrSupport,
                'listTag' => $arrTag,
                'showColumn' => $this->showColumn(),
                'page' => $filters['page'],
                'params' => $filters,
                'project' => $project,
                'listStaffManage'=> $listStaffManage,
                'listStaffProject'=> $listStaffProject,
                'listPhare' => $listPhare
            ]
        );
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
            4 =>[
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
            5 =>[
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
//            18 => [
//                "active" => 1,
//                "placeholder" => __("Chọn dự án"),
//                "type" => "select2",
//                "class" => "form-control select2 select2-active",
//                "name" => "manage_project_id",
//                "id" => "manage_project_id",
//                "data" => $this->project->getName(),
//                "nameConfig" => __("Dự án"),
//            ],
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

            25 =>[
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

            26 =>[
                "active" => 0,
                "placeholder" => __("Chi nhánh"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "name" => "branch_id",
                "id" => "branch_id",
                "data" => $mBranch->getAllSearch(),
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

        $user_id = Auth::id();
        $route_name = 'manager-project.work';
        $config = $this->configList->checkExist($user_id, $route_name);

        if (isset($config->value)) {
            $config = unserialize($config->value);
            foreach($data as $key => $value) {
                if(in_array($key,[2,3,4,5,12,18,19,25])){
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
                "name" => __("Tiêu đề"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Tiêu đề"),
                "column_name" => "manage_work_title",
                "view_detail" => 1,
                "type" => "link",
            ],
            4 => [
                "name" => __("Trạng thái"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Trạng thái"),
                "type" => "status_work",
                "column_name" => "manage_status_name",
            ],
            5 => [
                "name" => __("Tiến độ"),
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Tiến độ"),
                "type" => "process",
                "column_name" => "progress",
            ],
            6 => [
                "name" => __("Người thực hiện"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Người thực hiện"),
                "type" => "label",
                "column_name" => "processor_id",
            ],
            7 =>[
                "name" => __("Ngày bắt đầu"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Ngày bắt đầu"),
                "type" => "label",
                "column_name" => "date_start",
            ],

            8 => [
                "name" => __("Ngày hết hạn"),
                "class" => "text-center",
                "active" => 1,
                "nameConfig" => __("Ngày hết hạn"),
                "type" => "label",
                "column_name" => "date_end",
            ],
            9 => [
                "name" => __("Tag"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Tag"),
                "type" => "label",
                "column_name" => "tag",
            ],
            10 => [
                "name" => __("Người hỗ trợ"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Người hỗ trợ"),
                "type" => "label",
                "column_name" => "manage_work_support_id",
            ],
            11 => [
                "name" => __("Người tạo"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Người tạo"),
                "type" => "label",
                "column_name" => "created_name",
            ],
            12 => [
                "name" => __("Người duyệt"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Người duyệt"),
                "type" => "label",
                "column_name" => "approve_name",
            ],
            13 => [
                "name" => __("Người cập nhật"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Người cập nhật"),
                "type" => "label",
                "column_name" => "updated_name",
            ],


            14 => [
                "name" => __("Loại thẻ"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Loại thẻ"),
                "type" => "label",
                "column_name" => "type_card_work",
            ],
            15 => [
                "name" => __("Mức độ ưu tiên"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Mức độ ưu tiên"),
                "type" => "label",
                "column_name" => "priority",
            ],
            16 => [
                "name" => __("Ngày cập nhật"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Ngày cập nhật"),
                "type" => "label",
                "column_name" => "updated_at",
            ],

            17 => [
                "name" => __("Ngày hoàn thành"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Ngày hoàn thành"),
                "type" => "label",
                "column_name" => "date_finish",
            ],
            18 => [
                "name" => __("Khách hàng"),
                "class" => "text-center",
                "active" => 0,
                "nameConfig" => __("Khách hàng"),
                "type" => "label",
                "column_name" => "customer_name",
            ],
//            19 => [
//                "name" => __("Dự án"),
//                "class" => "text-center",
//                "active" => 0,
//                "nameConfig" => __("Dự án"),
//                "type" => "label",
//                "column_name" => "manage_project_name",
//            ],

        ];
        $user_id = Auth::id();
        $route_name = 'manager-project.work';
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

    public function exportPiospa(){
        $dataFeatureGroup = DB::table('piospa_admin_feature_group')
            ->select(
                "piospa_admin_feature_group.feature_group_name_vi"
            )
            ->get()->toArray();
        $a = [];
        foreach ($dataFeatureGroup as $item){
            $b = [
                'feature_group_name_vi' => ''
            ];
            foreach ($item as $k => $v){

                if($k == 'feature_group_name_vi'){$b['feature_group_name_vi'] = $v;}
            }
            $a[] = $b;
        }

        $dataFeature = DB::table('piospa_admin_feature')
            ->select(
                "piospa_admin_feature.feature_id",
                "piospa_admin_feature.feature_name_vi",
                "piospa_admin_feature.feature_group_id",
                'piospa_admin_feature_group.feature_group_name_vi'
            )
            ->leftJoin('piospa_admin_feature_group','piospa_admin_feature.feature_group_id','piospa_admin_feature_group.feature_group_id')
            ->get()->toArray();
        $c = [];
        foreach ($dataFeature as $item){
            $d = [
                'feature_id' => 0,
                'feature_name_vi' => '',
                'feature_group_id' => '',
                'feature_group_name_vi' => ''
            ];
            foreach ($item as $k => $v){
                if($k == 'feature_id'){$d['feature_id'] = $v;}
                if($k == 'feature_name_vi'){$d['feature_name_vi'] = $v;}
                if($k == 'feature_group_id'){$d['feature_group_id'] = $v;}
                if($k == 'feature_group_name_vi'){$d['feature_group_name_vi'] = $v;}
            }
            $c[] = $d;
        }
//        $e = collect($c)->groupBy('feature_group_name_vi')->toArray();
//        $heading = [];
//            foreach ($a as $item){
//                $heading[] = $item['feature_group_name_vi'];
//            }
            $ex =[];
        foreach ($c as $k => $v){
            $ex[] = [
                'feature_group_name_vi' => $v['feature_group_name_vi'],
                'feature_name_vi' => $v['feature_name_vi']
            ];
        }
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return Excel::download(new ExportFile([], $ex), 'result.xlsx');
    }
    public function projectInfoPhase($id,Request $request){
        $param = $request->all();
        $info = $this->project->getInfoPhase($id,$param);
        return view('manager-project::project-info.phase',[
            'param' => $param,
            'info' => $info,
        ]);
    }
    public function projectInfoIssue($id,Request $request){
        $param = $request->all();
        $info = $this->project->getInfoIssue($id,$param);
        return view('manager-project::project-info.issue',[
            'param' => $param,
            'info' => $info,
            'listStaff' => $this->project->listStaff()
        ]);

    }
    public function popupAddIssue(Request $request){
        $id = $request->all();
        if(isset($id['job']) && $id['job'] != '' && $id['job'] != null){
            //lay thong tin van de cu
            $data = $this->project->popupEditIssue($id);
            $view = view('manager-project::project-info.popup.add-exchange',['data' => $data[0]])->render();
        }else{
            $view = view('manager-project::project-info.popup.add-issue',['id' => $id['id']])->render();
        }
        $data = [
            'error' => false,
            'view' => $view,
        ];
        return \response()->json($data);
    }
    public function addIssue(Request $request){
        $data = $this->project->addIssue($request->all());
        return \response()->json($data);

    }
    public function deleteIssue(Request $request){
        $id = $request->all();
        $data = $this->project->deleteIssue($id);
        return \response()->json($data);

    }
    public function popupEditIssue(Request $request){
        $id = $request->all();
        $data = $this->project->popupEditIssue($id);
        return \response()->json($data);
    }
    public function editIssue(Request $request){
        $data = $this->project->editIssue($request->all());
        return \response()->json($data);

    }
    public function projectInfoExpenditure($id,Request $request){
        $param = $request->all();
        $info = $this->project->getInfoExpenditure($id,$param);
        return view('manager-project::project-info.expenditure',[
            'param' => $param,
            'info' => $info,
            'branch' => $this->project->getBranch(),
            'listStaff' => $this->project->listStaff()
        ]);
    }
    public function projectInfoExpenditureList(Request $request){
        $param = $request->all();
        $info = $this->project->getInfoExpenditure(null,$param);
        return view('manager-project::project-info.expenditure-list',[
            'param' => $param,
            'info' => $info,
            'branch' => $this->project->getBranch(),
            'listStaff' => $this->project->listStaff()
        ]);
    }
    public  function popupAddPayment(Request $request){
        $param = $request->all();
        $data = $this->project->popupAddPayment($param);
        return \response()->json($data);
    }
    public  function popupAddReceipt(Request $request){
        $param = $request->all();
        $data = $this->project->popupAddReceipt($param);
        return \response()->json($data);
    }
    public function addNewPayment(Request $request){
        $data = $this->project->addNewPayment($request->all());
        return \response()->json($data);

    }
    /**
     * Load option đối tượng theo loại
     *
     * @param Request $request
     * @return mixed
     */
    public function loadOptionObjectAccounting(Request $request)
    {
        $data = $request->all();
        return $this->receipt->loadOptionObjectAccounting($data);
    }
    public function addNewReceipt(Request $request){
        $data = $this->project->addNewReceipt($request->all());
        return \response()->json($data);

    }

}
