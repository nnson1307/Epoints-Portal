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
use Modules\ManagerProject\Models\ManageProjectStatusConfigMapTable;
use Modules\ManagerWork\Models\Customers;
use Modules\ManagerWork\Models\ProjectTable;
use Modules\ManagerWork\Models\StaffsTable;
use Modules\ManagerWork\Models\DepartmentTable;
use Modules\ManagerWork\Models\ManageTagsTable;
use Modules\ManagerWork\Models\ProjectStatusTable;
use Modules\ManagerWork\Http\Requests\Project\ProjectStoreRequest;
use Modules\ManagerWork\Http\Requests\Project\ProjectUpdateRequest;
use Modules\ManagerWork\Repositories\Project\ProjectRepositoryInterface;
use Modules\ManagerWork\Repositories\ManagerWork\ManagerWorkRepositoryInterface;


class ProjectController extends Controller
{
    protected $project;
    protected $managerWork;


    public function __construct(
        ProjectRepositoryInterface $project,
        ManagerWorkRepositoryInterface $managerWorkRepository
    ) {
        $this->project = $project;
        $this->managerWork = $managerWorkRepository;
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

        return view('manager-work::project.index', [
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
            'manager-work::project.list',
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
        return view('manager-work::project.add', [
            'listStaffs' => $listStaffs,
            'listStatus' => $listStatus,
            'listDepartment' => $listDepartment,
            'listTag' => $listTag
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
        $mManageProjectStatusConfigMap = app()->get(ManageProjectStatusConfigMapTable::class);
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
        return view('manager-work::project.edit', [
            'listStaffs' => $listStaffs,
            'listStatus' => $listStatus,
            'listDepartment' => $listDepartment,
            'listTag' => $listTag,
            'project' => $project,
            'listTagSelected' => $listTagSelected,
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

        return view('manager-work::project.show', ['project' => $project, 'idProject' => $idProject]);
    }
}
