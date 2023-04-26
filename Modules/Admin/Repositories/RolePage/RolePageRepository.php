<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/9/2019
 * Time: 11:45 AM
 */

namespace Modules\Admin\Repositories\RolePage;

use Modules\Admin\Models\RolePageTable;

class RolePageRepository implements RolePageRepositoryInterface
{
    protected $rolePage;
    protected $timestamps = true;

    public function __construct(RolePageTable $rolePage)
    {
        $this->rolePage = $rolePage;
    }

    public function add(array $data)
    {
        return $this->rolePage->add($data);
    }

    public function edit(array $data, $id)
    {
        return $this->rolePage->edit($data, $id);
    }

    public function checkIssetRole($staffId, $pageId)
    {
        return $this->rolePage->checkIssetRole($staffId, $pageId);
    }
}