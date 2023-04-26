<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/9/2019
 * Time: 11:49 AM
 */

namespace Modules\Admin\Repositories\RoleAction;


interface RoleActionRepositoryInterface
{
    public function add(array $data);

    public function edit(array $data, $id);

    public function checkIssetRole($staffId, $actionId);
}