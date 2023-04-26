<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/9/2019
 * Time: 11:50 AM
 */

namespace Modules\Admin\Repositories\RoleAction;

use Modules\Admin\Models\RoleActionTable;

class RoleActionRepository implements RoleActionRepositoryInterface
{
    protected $roleAction;
    protected $timestamps = true;

    public function __construct(RoleActionTable $roleAction)
    {
        $this->roleAction = $roleAction;
    }

    public function add(array $data)
    {
        return $this->roleAction->add($data);
    }

    public function edit(array $data, $id)
    {
        return $this->roleAction->edit($data, $id);
    }

    public function checkIssetRole($staffId, $actionId)
    {
        return $this->roleAction->checkIssetRole($staffId, $actionId);
    }
}