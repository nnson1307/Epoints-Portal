<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\TicketRoleActionMap;

use Modules\Ticket\Models\TicketRoleActionMapTable;

class TicketRoleActionMapRepository implements TicketRoleActionMapRepositoryInterface
{
    /**
     * @var TicketRoleActionMapTable
     */
    protected $TicketRoleActionMap;
    protected $timestamps = true;

    public function __construct(TicketRoleActionMapTable $TicketRoleActionMap)
    {
        $this->TicketRoleActionMap = $TicketRoleActionMap;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->TicketRoleActionMap->getList($filters);
    }

    public function getAll(array $filters = [])
    {
        return $this->TicketRoleActionMap->getAll($filters);
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->TicketRoleActionMap->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->TicketRoleActionMap->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->TicketRoleActionMap->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->TicketRoleActionMap->getItem($id);
    }
    
    /*
    * check exist
    */
    public function checkExistEmail($email = '')
    {
        return $this->TicketRoleActionMap->checkExistEmail($email);
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id, $startTime, $endTime)
    {
        return $this->TicketRoleActionMap->testEdit($id, $startTime, $endTime);
    }
    public function removeByRole($roleId)
    {
        $this->TicketRoleActionMap->removeByRole($roleId);
    }
}