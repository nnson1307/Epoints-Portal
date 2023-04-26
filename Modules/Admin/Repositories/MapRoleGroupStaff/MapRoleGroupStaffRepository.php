<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/22/2019
 * Time: 4:14 PM
 */

namespace Modules\Admin\Repositories\MapRoleGroupStaff;

use Modules\Admin\Models\MapRoleGroupStaffTable;

class MapRoleGroupStaffRepository implements MapRoleGroupStaffRepositoryInterface
{
    protected $mapRoleGroupStaff;

    protected $timestamps = true;


    public function __construct(MapRoleGroupStaffTable $mapRoleGroupStaff)
    {
        $this->mapRoleGroupStaff = $mapRoleGroupStaff;
    }

    public function add(array $data)
    {
        return $this->mapRoleGroupStaff->add($data);
    }

    public function edit(array $data, $roleGroupId, $staffId)
    {
        return $this->mapRoleGroupStaff->edit($data, $roleGroupId, $staffId);
    }

    public function checkIssetMap($roleGroupId, $staffId)
    {
        return $this->mapRoleGroupStaff->checkIssetMap($roleGroupId, $staffId);
    }

    public function getRoleGroupByStaffId($staffId)
    {
        return $this->mapRoleGroupStaff->getRoleGroupByStaffId($staffId);
    }

    public function editById(array $data, $id)
    {
        return $this->mapRoleGroupStaff->editById($data, $id);
    }

    public function removeByUser($id)
    {
        return $this->mapRoleGroupStaff->removeByUser($id);
    }
}
//