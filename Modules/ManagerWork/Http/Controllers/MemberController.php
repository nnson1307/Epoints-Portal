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
use Modules\ManagerWork\Models\ManageProjectStaffTable;
use Modules\ManagerWork\Models\StaffsTable;
use Modules\ManagerWork\Models\ProjectTable;
use Modules\ManagerWork\Models\DepartmentTable;
use Modules\ManagerWork\Models\ManagerWorkTable;
use Modules\ManagerWork\Models\ManageProjectRoleTable;
use Modules\ManagerWork\Http\Requests\Member\StoreRequest;
use Modules\ManagerWork\Http\Requests\Member\UpdateRequest;
use Modules\ManagerWork\Repositories\Member\MemberRepositoryInterface;
use Modules\ManagerWork\Repositories\Project\ProjectRepositoryInterface;


class MemberController extends Controller
{
    protected $memberProject;

    public function __construct(MemberRepositoryInterface $memberProject)
    {
        $this->memberProject = $memberProject;
    }

    public function indexAction($id, Request $request)
    {
        $param = $request->all();
        $mProject = app()->get(ProjectTable::class);
        $project = $mProject->find($id);
        // danh sách nhân viên
        $mStaffs = app()->get(StaffsTable::class);
        // danh sách nhân viên của dự án
        $listStaffProject = $project->staffs->pluck('staff_id')->toArray();
        // danh sách vai trò
        $manageProjectRole = app()->get(ManageProjectRoleTable::class);
        $listRole = $manageProjectRole->getAll();
        // danh sách nhân viên 
        $listStaff = $mStaffs->getAll();
        // danh sách phòng ban
        $mDepartment = app()->get(DepartmentTable::class);
        $departments = $mDepartment->getAll();
        // danh sách nhân viên popup
        $listStaffPopup = $mStaffs->getListStaffProject($listStaffProject);

        if (!$project) return abort(404);

        $listStaffManage = [];
        $listStaffProject = [];
        $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);

        $listStaffManage = $mManageProjectStaff->getListAdmin($id,'administration');
        $listStaffProject = $mManageProjectStaff->getListAdmin($id,null,$param);
        if (count($listStaffManage) != 0){
            $listStaffManage = collect($listStaffManage)->pluck('staff_id')->toArray();
        }

        if (count($listStaffProject) != 0){
            $listStaffProject = collect($listStaffProject)->pluck('staff_id')->toArray();
        }

        return view('manager-work::project.member.index', [
            'project' => $project,
            'listStaffPopup' => $listStaffPopup,
            'listRole' => $listRole,
            'listStaff' => $listStaff,
            'departments' => $departments,
            'listStaffManage' => $listStaffManage,
            'listStaffProject' => $listStaffProject,
            'param' => $param
        ]);
    }
    /**
     * Danh sách thành viên dự án
     * @param Request $request
     * @return Response 
     */

    public function listAction(Request $request)
    {
        $params = $request->all();
        $result = $this->memberProject->getList($params);
        return response()->json($result);
    }

    /**
     * Thêm thành viên 
     * @param Request $request
     * @return mixed
     */

    public function storeAction(StoreRequest $request)
    {
        $params = $request->all();
        $result = $this->memberProject->store($params);
        return response()->json($result);
    }

    /**
     * Hiển thị thông tin chi tiết thành viên
     * @param Request $request
     * @return Response
     */
    public function showAction(Request $request)
    {
        $idMemberProject = $request->projectStaffId;
        $result = $this->memberProject->show($idMemberProject);
        return response()->json($result);
    }

    /**
     * Hiển thị thông tin cập nhật thành viên
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request)
    {
        $idMemberProject = $request->projectStaffId;
        $result = $this->memberProject->edit($idMemberProject);
        return response()->json($result);
    }

    /**
     * cập nhật thành viên
     * @param UpdateRequest $request
     * @return Response
     */
    public function updateAction(UpdateRequest $request)
    {
        $params = $request->all();
        $result = $this->memberProject->update($params);
        return response()->json($result);
    }

    /**
     * xoá thành viên
     * @param Request $request
     * @return Response
     */
    public function removeAction(Request $request)
    {
        $params = $request->all();
        $result = $this->memberProject->remove($params);
        return response()->json($result);
    }

    /**
     * Hiển thị popup chọn nhân viên
     */
    public function showPopupAddStaff(Request $request){
        $params = $request->all();
        $result = $this->memberProject->showPopupAddStaff($params);
        return response()->json($result);
    }
}
