<?php

namespace Modules\ManagerProject\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ManagerProject\Models\ManageProjectStaffTable;
use Modules\ManagerProject\Models\StaffsTable;
use Modules\ManagerProject\Repositories\Project\ProjectRepositoryInterface;

class DocumentController extends Controller
{
    protected $staff;

    public function __construct(
        StaffsTable $staff
    )
    {
        $this->staff = $staff;
    }

    public function indexAction(Request $request)
    {
        $filters = $request->all();
        $docType = [
            'file' => __('managerproject::managerproject.file_type'),
            'image' => __('managerproject::managerproject.image_type')
        ];

        $project = null;
        $listStaffManage = [];
        $listStaffProject = [];
        $info = [];
        if (isset($filters['manage_project_id'])) {
            $rProject = app()->get(ProjectRepositoryInterface::class);
            $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);
            $project = $rProject->getDetail($filters['manage_project_id']);

            $listStaffManage = $mManageProjectStaff->getListAdmin($filters['manage_project_id'],'administration');
            $listStaffProject = $mManageProjectStaff->getListAdmin($filters['manage_project_id']);
            if (count($listStaffManage) != 0){
                $listStaffManage = collect($listStaffManage)->pluck('staff_id')->toArray();
            }

            if (count($listStaffProject) != 0){
                $listStaffProject = collect($listStaffProject)->pluck('staff_id')->toArray();
            }

            $rProject = app()->get(ProjectRepositoryInterface::class);
            $info = $rProject->projectInfoWork($filters['manage_project_id']);
        }

        return view('manager-project::document.index', [
            'staffList' => $this->staff->getName(),
            'docType' => $docType,
            'project' => $project,
            'listStaffManage' => $listStaffManage,
            'listStaffProject' => $listStaffProject,
            'info' => $info,
        ]);
    }
}