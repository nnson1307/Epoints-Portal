<?php


namespace Modules\ManagerProject\Repositories\ManageProjectContact;


use Modules\ManagerProject\Models\ManageProjectContactTable;

class ManageProjectContactRepository implements ManageProjectContactRepositoryInterface
{
    protected $mManageProjectContact;

    public function __construct(ManageProjectContactTable $manageProjectContact)
    {
        $this->mManageProjectContact = $manageProjectContact;
    }

    public function getListByIdProject($projectId)
    {
        return $this->mManageProjectContact->getListByIdProject($projectId);
    }
}