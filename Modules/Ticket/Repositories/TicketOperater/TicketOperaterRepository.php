<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\TicketOperater;

use Modules\Ticket\Models\TicketOperaterTable;

class TicketOperaterRepository implements TicketOperaterRepositoryInterface
{
    /**
     * @var TicketOperaterTable
     */
    protected $TicketOperater;
    protected $timestamps = true;

    public function __construct(TicketOperaterTable $TicketOperater)
    {
        $this->TicketOperater = $TicketOperater;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->TicketOperater->getList($filters);
    }
    
    public function getAll(array $filters = [])
    {
        return $this->TicketOperater->getAll($filters);
    }
    public function getName()
    {
        return $this->TicketOperater->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->TicketOperater->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->TicketOperater->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->TicketOperater->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->TicketOperater->getItem($id);
    }

    /*
     *  test code
     */
    public function testCode($code, $id)
    {
        return $this->TicketOperater->testCode($code, $id);
    }

    /*
    * check exist
    */
    public function checkExistEmail($email = '')
    {
        return $this->TicketOperater->checkExistEmail($email);
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id, $startTime, $endTime)
    {
        return $this->TicketOperater->testEdit($id, $startTime, $endTime);
    }
    public function removeFile($ticketId)
    {
        return $this->TicketOperater->removeFile($ticketId);
    }
}