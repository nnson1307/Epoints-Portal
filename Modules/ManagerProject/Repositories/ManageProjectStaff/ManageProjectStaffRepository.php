<?php


namespace Modules\ManagerProject\Repositories\ManageProjectStaff;


use Modules\ManagerProject\Models\ManageProjectStaffTable;

class ManageProjectStaffRepository implements ManageProjectStaffRepositoryInterface
{
    public function getListStaff($projectId){
        $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);
        return $mManageProjectStaff->getAllByProjectId($projectId);
    }
}