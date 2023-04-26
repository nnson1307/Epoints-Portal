<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\TicketStatus;

use Modules\Ticket\Models\TicketStatusTable;

class TicketStatusRepository implements TicketStatusRepositoryInterface
{
    /**
     * @var TicketStatusTable
     */
    protected $ticketStatus;
    protected $timestamps = true;

    public function __construct(TicketStatusTable $ticketStatus)
    {
        $this->ticketStatus = $ticketStatus;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->ticketStatus->getList($filters);
    }

    public function getAll(array $filters = [])
    {
        return $this->ticketStatus->getAll($filters);
    }
    public function getName()
    {
        return $this->ticketStatus->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->ticketStatus->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->ticketStatus->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->ticketStatus->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->ticketStatus->getItem($id);
    }
    
    /*
    * check exist
    */
    public function checkExistEmail($email = '')
    {
        return $this->ticketStatus->checkExistEmail($email);
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id, $startTime, $endTime)
    {
        return $this->ticketStatus->testEdit($id, $startTime, $endTime);
    }
  
}