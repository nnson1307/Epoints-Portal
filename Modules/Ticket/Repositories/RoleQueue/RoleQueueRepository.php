<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\RoleQueue;

use Modules\Ticket\Models\RoleQueueTable;

class RoleQueueRepository implements RoleQueueRepositoryInterface
{
    /**
     * @var RoleQueue
     */
    protected $roleQueue;
    protected $timestamps = true;

    public function __construct(RoleQueueTable $roleQueue)
    {
        $this->roleQueue = $roleQueue;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->roleQueue->getList($filters);
    }

    public function getAll(array $filters = [])
    {
        return $this->roleQueue->getAll($filters);
    }

    public function getName(array $filters = [])
    {
        return $this->roleQueue->getName($filters);
    }
    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->roleQueue->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->roleQueue->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->roleQueue->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->roleQueue->getItem($id);
    }

}