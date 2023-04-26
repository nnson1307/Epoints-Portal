<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\Role;

use Modules\Ticket\Models\RoleTable;

class RoleRepository implements RoleRepositoryInterface
{
    /**
     * @var Role
     */
    protected $role;
    protected $timestamps = true;

    public function __construct(RoleTable $role)
    {
        $this->role = $role;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->role->getList($filters);
    }

    public function getAll(array $filters = [])
    {
        return $this->role->getAll($filters);
    }

    public function getName()
    {
        return $this->role->getName();
    }
    public function getRoleGroupId()
    {
        return $this->role->getRoleGroupId();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->role->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->role->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->role->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->role->getItem($id);
    }

}