<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\TicketQueueMap;

use Modules\Ticket\Models\TicketQueueMapTable;

class TicketQueueMapRepository implements TicketQueueMapRepositoryInterface
{
    /**
     * @var TicketQueueMapTable
     */
    protected $TicketQueueMap;
    protected $timestamps = true;

    public function __construct(TicketQueueMapTable $TicketQueueMap)
    {
        $this->TicketQueueMap = $TicketQueueMap;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->TicketQueueMap->getList($filters);
    }
    
    public function getAll(array $filters = [])
    {
        return $this->TicketQueueMap->getAll($filters);
    }
    public function getName()
    {
        return $this->TicketQueueMap->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->TicketQueueMap->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->TicketQueueMap->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->TicketQueueMap->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->TicketQueueMap->getItem($id);
    }

    /*
     *  test code
     */
    public function testCode($code, $id)
    {
        return $this->TicketQueueMap->testCode($code, $id);
    }

    /*
    * check exist
    */
    public function checkExistEmail($email = '')
    {
        return $this->TicketQueueMap->checkExistEmail($email);
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id, $startTime, $endTime)
    {
        return $this->TicketQueueMap->testEdit($id, $startTime, $endTime);
    }
    public function removeFile($ticketId)
    {
        return $this->TicketQueueMap->removeFile($ticketId);
    }
}