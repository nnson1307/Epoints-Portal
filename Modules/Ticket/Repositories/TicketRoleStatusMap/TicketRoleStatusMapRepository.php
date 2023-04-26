<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\TicketRoleStatusMap;

use Modules\Ticket\Models\TicketRoleStatusMapTable;

class TicketRoleStatusMapRepository implements TicketRoleStatusMapRepositoryInterface
{
    /**
     * @var TicketRoleStatusMapTable
     */
    protected $TicketRoleStatusMap;
    protected $timestamps = true;

    public function __construct(TicketRoleStatusMapTable $TicketRoleStatusMap)
    {
        $this->TicketRoleStatusMap = $TicketRoleStatusMap;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->TicketRoleStatusMap->getList($filters);
    }

    public function getAll(array $filters = [])
    {
        return $this->TicketRoleStatusMap->getAll($filters);
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->TicketRoleStatusMap->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->TicketRoleStatusMap->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->TicketRoleStatusMap->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->TicketRoleStatusMap->getItem($id);
    }
    
    /*
    * check exist
    */
    public function checkExistEmail($email = '')
    {
        return $this->TicketRoleStatusMap->checkExistEmail($email);
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id, $startTime, $endTime)
    {
        return $this->TicketRoleStatusMap->testEdit($id, $startTime, $endTime);
    }
    public function removeByRole($roleId)
    {
        $this->TicketRoleStatusMap->removeByRole($roleId);
    }
}