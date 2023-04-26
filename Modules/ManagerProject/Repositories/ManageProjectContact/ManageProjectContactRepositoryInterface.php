<?php


namespace Modules\ManagerProject\Repositories\ManageProjectContact;


interface ManageProjectContactRepositoryInterface
{
    /**
     * lấy danh sách người liên hệ
     * @param $projectId
     * @return mixed
     */
    public function getListByIdProject($projectId);
}