<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\ManagerWork\Repositories\Member;

use Illuminate\Support\Facades\Log;
use Modules\ManagerWork\Models\ProjectTable;
use Modules\ManagerWork\Models\ManageProjectRoleTable;
use Modules\ManagerWork\Models\ManageProjectStaffTable;
use Modules\ManagerWork\Models\StaffsTable;
use Modules\ManagerWork\Repositories\Project\ProjectRepositoryInterface;


class MemberRepository implements MemberRepositoryInterface
{
    /**
     * Thêm thành Viên
     * @param $params
     * @return mixed
     */

    protected $project;
    protected $projectStaff;

    public function __construct(
        ProjectTable $project,
        ManageProjectStaffTable $projectStaff
    ) {
        $this->project = $project;
        $this->projectStaff = $projectStaff;
    }

    public function store($params)
    {
        try {
            $listStaff = $params['listUser'];
            $role = $params['role'];
            $projectId = $params['idProject'];
            $project = $this->project->find($projectId);
            if (!$project)
                return [
                    'error' => false,
                    'message' => __('Thêm nhân viên thất bại')
                ];
            $project->staffs()->attach($listStaff, ['manage_project_role_id' => $role]);
            $rProjectRepo = app()->get(ProjectRepositoryInterface::class);
            $rProjectRepo->createHistoryProject([
                'key' => 'staff',
                'manage_project_id' => $projectId
            ]);
            return [
                'error' => false,
                'message' => __('Thêm nhân viên thành công')
            ];
        } catch (\Exception $e) {
            Log::info('create member error :' . $e->getMessage());
            return [
                'error' => false,
                'message' => __('Thêm nhân viên thất bại')
            ];
        }
    }


    /**
     * Danh sách thành viên 
     * @param $params
     * @return mixed
     */

    public function getList($params)
    {
        $list = $this->projectStaff->getListNew($params);

        $listStaffManage = [];
        $listStaffProject = [];
        $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);

        $listStaffManage = $mManageProjectStaff->getListAdmin($params['project'],'administration');
        $listStaffProject = $mManageProjectStaff->getListAdmin($params['project']);
        if (count($listStaffManage) != 0){
            $listStaffManage = collect($listStaffManage)->pluck('staff_id')->toArray();
        }

        if (count($listStaffProject) != 0){
            $listStaffProject = collect($listStaffProject)->pluck('staff_id')->toArray();
        }

        $view = view('manager-work::project.member.list', ['list' => $list,'listStaffManage'=> $listStaffManage,'listStaffProject' => $listStaffProject])->render();
        return [
            'error' => false,
            'view' => $view
        ];
    }
    /**
     * Hiển thị thông tin chi tiết thành viên
     * @param $idMemberProject
     * @return mixed
     */

    public function show($idMemberProject)
    {
        // danh sách vai trò
        $manageProjectRole = app()->get(ManageProjectRoleTable::class);
        $listRole = $manageProjectRole->getAll();
        $memberProject = $this->projectStaff->detail($idMemberProject);
        $view = view('manager-work::project.member.show', ['memberProject' => $memberProject, 'listRole' => $listRole])->render();
        return [
            'error' => false,
            'view' => $view
        ];
    }

    /**
     * Hiển thị thông tin cập nhật thành viên
     * @param $idMemberProject
     * @return mixed
     */

    public function edit($idMemberProject)
    {

        $manageProjectRole = app()->get(ManageProjectRoleTable::class);
        $listRole = $manageProjectRole->getAll();
        $memberProject = $this->projectStaff->detail($idMemberProject);
        $view = view('manager-work::project.member.edit', ['memberProject' => $memberProject, 'listRole' => $listRole])->render();
        return [
            'error' => false,
            'view' => $view
        ];
    }

    /**
     * cập nhật thành viên
     * @param $params
     * @return mixed
     */

    public function update($params)
    {
        try {
            $projectStaff = $this->projectStaff->find($params['projectStaffId']);
            if (!$projectStaff)
                return [
                    'error' => true,
                    'message' => __('Cập nhật thành viên thất bại')
                ];
            $projectStaff->update([
                'staff_id' => $params['user'],
                'manage_project_role_id' => $params['role']
            ]);

            $mStaff = app()->get(StaffsTable::class);

            $detailStaff = $mStaff->getDetail($params['user']);

            $rProjectRepo = app()->get(ProjectRepositoryInterface::class);
            $rProjectRepo->createHistoryProject([
                'key' => 'staff_edit',
                'old' => $detailStaff['full_name'],
                'manage_project_id' => $projectStaff->manage_project_id
            ]);

            return [
                'error' => false,
                'message' => __('Cập nhật thành viên thành công')
            ];
        } catch (\Exception $ex) {
            Log::info("Update member error : " . $ex->getMessage());
            return [
                'error' => true,
                'message' => __('Cập nhật thành viên thất bại')
            ];
        }
    }

    /**
     * xoá thành viên
     * @param $params
     * @return mixed
     */

    public function remove($params)
    {
        try {
//            $projectStaff = $this->projectStaff->find($idMemberProject);
            $projectStaff = $this->projectStaff->findStaff($params['id']);

            if (!$projectStaff)
                return [
                    'error' => true,
                    'message' => __('Xoá thành viên thất bại')
                ];

            if ($projectStaff['manage_project_role_code'] == 'administration'){
                $totalAdmin = $this->projectStaff->getListAdmin($params['manage_project_id'],$projectStaff['manage_project_role_code']);

                if (count($totalAdmin) <= 1){
                    return [
                        'error' => true,
                        'message' => __('Dự án tối thiểu phải có một người quản trị. Bạn không thể thực hiện thao tác này')
                    ];
                }
            }

            $mStaff = app()->get(StaffsTable::class);

            $detailStaff = $mStaff->getDetail($projectStaff['staff_id']);

            $rProjectRepo = app()->get(ProjectRepositoryInterface::class);
            $rProjectRepo->createHistoryProject([
                'key' => 'staff_delete',
                'old' => $detailStaff['full_name'],
                'manage_project_id' => $projectStaff->manage_project_id
            ]);

                $projectStaff->delete();
            return [
                'error' => false,
                'message' => __('Xoá thành viên thành công')
            ];
        } catch (\Exception $ex) {
            Log::info("remove member error : " . $ex->getMessage());
            return [
                'error' => true,
                'message' => __('Xoá thành viên thất bại')
            ];
        }
    }

    /**
     * Hiển thị popup thêm thành viên
     * @param $data
     * @return mixed|void
     */
    public function showPopupAddStaff($data)
    {
        $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);

        $listStaffProject = $mManageProjectStaff->getListStaffByProject($data['manage_project_id']);

        if (count($listStaffProject) != 0){
            $listStaffProject = collect($listStaffProject)->pluck('staff_id')->toArray();
        }

        $mStaffs = app()->get(StaffsTable::class);

        $listStaffPopup = $mStaffs->getAll(['not_arr_staff' => $listStaffProject]);

        // danh sách vai trò
        $manageProjectRole = app()->get(ManageProjectRoleTable::class);

        $listRole = $manageProjectRole->getAll();


        $view = view('manager-work::project.member.append.form-add-staff', [
            'listRole' => $listRole,
            'listStaffPopup' => $listStaffPopup
        ])->render();
        return [
            'error' => false,
            'view' => $view
        ];
    }
}
