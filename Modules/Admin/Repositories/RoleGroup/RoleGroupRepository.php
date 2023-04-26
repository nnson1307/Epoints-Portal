<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/20/2019
 * Time: 5:04 PM
 */

namespace Modules\Admin\Repositories\RoleGroup;

use Modules\Admin\Models\RoleGroupTable;

class RoleGroupRepository implements RoleGroupRepositoryInterface
{
    protected $roleGroup;
    protected $timestamps = true;

    public function __construct(RoleGroupTable $roleGroup)
    {
        $this->roleGroup = $roleGroup;
    }

    public function list(array $filterts = [])
    {
        return $this->roleGroup->getList($filterts);
    }

    public function add(array $data)
    {
        return $this->roleGroup->add($data);
    }

    /**
     * Edit product origin
     */
    public function edit(array $data, $id)
    {
        return $this->roleGroup->edit($data, $id);
    }

    public function getList2()
    {
        return $this->roleGroup->getLists();
    }

    public function checkName($name, $id)
    {
        return $this->roleGroup->checkName($name, $id);
    }

    public function getItem($id)
    {
        return $this->roleGroup->getItem($id);
    }

    public function getOptionActive()
    {
        $array = [];
        foreach ($this->roleGroup->getOptionActive() as $item) {
            $array[$item['id']] = $item['name'];
        }
        return $array;
    }
}