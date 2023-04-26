<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM.
 */

namespace Modules\Ticket\Repositories\RoleGroup;

use Modules\Ticket\Models\RoleGroupTable;

class RoleGroupRepository implements RoleGroupRepositoryInterface
{
    /**
     * @var RoleGroupRepository
     */
    protected $roleGroup;
    protected $timestamps = true;

    public function __construct(RoleGroupTable $roleGroup)
    {
        $this->roleGroup = $roleGroup;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->roleGroup->getList($filters);
    }

    public function getName()
    {
        return $this->roleGroup->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->roleGroup->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->roleGroup->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->roleGroup->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->roleGroup->getItem($id);
    }

}