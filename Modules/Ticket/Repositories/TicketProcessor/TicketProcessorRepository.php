<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\TicketProcessor;

use Modules\Ticket\Models\TicketProcessorTable;

class TicketProcessorRepository implements TicketProcessorRepositoryInterface
{
    /**
     * @var TicketProcessorTable
     */
    protected $TicketProcessor;
    protected $timestamps = true;

    public function __construct(TicketProcessorTable $TicketProcessor)
    {
        $this->TicketProcessor = $TicketProcessor;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->TicketProcessor->getList($filters);
    }
    
    public function getAll(array $filters = [])
    {
        return $this->TicketProcessor->getAll($filters);
    }
    public function getName()
    {
        return $this->TicketProcessor->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->TicketProcessor->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->TicketProcessor->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->TicketProcessor->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->TicketProcessor->getItem($id);
    }

    /*
     *  test code
     */
    public function testCode($code, $id)
    {
        return $this->TicketProcessor->testCode($code, $id);
    }

    /*
    * check exist
    */
    public function checkExistEmail($email = '')
    {
        return $this->TicketProcessor->checkExistEmail($email);
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id, $startTime, $endTime)
    {
        return $this->TicketProcessor->testEdit($id, $startTime, $endTime);
    }
    public function removeFile($ticketId)
    {
        return $this->TicketProcessor->removeFile($ticketId);
    }
}