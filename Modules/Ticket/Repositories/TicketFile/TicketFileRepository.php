<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\TicketFile;

use Modules\Ticket\Models\TicketFileTable;

class TicketFileRepository implements TicketFileRepositoryInterface
{
    /**
     * @var TicketFileTable
     */
    protected $TicketFile;
    protected $timestamps = true;

    public function __construct(TicketFileTable $TicketFile)
    {
        $this->TicketFile = $TicketFile;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->TicketFile->getList($filters);
    }
    
    public function getAll(array $filters = [])
    {
        return $this->TicketFile->getAll($filters);
    }
    public function getName()
    {
        return $this->TicketFile->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->TicketFile->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->TicketFile->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->TicketFile->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->TicketFile->getItem($id);
    }

    /*
     *  test code
     */
    public function testCode($code, $id)
    {
        return $this->TicketFile->testCode($code, $id);
    }

    /*
    * check exist
    */
    public function checkExistEmail($email = '')
    {
        return $this->TicketFile->checkExistEmail($email);
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id, $startTime, $endTime)
    {
        return $this->TicketFile->testEdit($id, $startTime, $endTime);
    }
    public function removeFile($ticketId,$group = 'file')
    {
        return $this->TicketFile->removeFile($ticketId,$group);
    }
}