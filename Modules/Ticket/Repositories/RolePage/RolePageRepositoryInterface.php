<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/9/2019
 * Time: 11:45 AM
 */

namespace Modules\Ticket\Repositories\RolePage;


interface RolePageRepositoryInterface
{
    public function add(array $data);

    public function edit(array $data, $id);

    public function checkIssetRole($staffId, $pageId);

}