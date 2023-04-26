<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/22/2019
 * Time: 4:14 PM
 */

namespace Modules\Admin\Repositories\MapRoleGroupStaff;


interface MapRoleGroupStaffRepositoryInterface
{
    public function add(array $data);

    public function edit(array $data, $roleGroupId, $staffId);

    public function checkIssetMap($roleGroupId, $staffId);

    public function getRoleGroupByStaffId($staffId);

    public function editById(array $data, $id);

    public function removeByUser($id);
}
//