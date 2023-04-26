<?php


namespace Modules\ManagerProject\Repositories\ManageProjectStaff;


interface ManageProjectStaffRepositoryInterface
{
    /**
     * lấy danh sách nhân viên theo dự án
     * @param $projectId
     * @return mixed
     */
    public function getListStaff($projectId);
}