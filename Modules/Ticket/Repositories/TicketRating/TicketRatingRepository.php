<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\TicketRating;

use Modules\Ticket\Models\TicketRatingTable;

class TicketRatingRepository implements TicketRatingRepositoryInterface
{
    /**
     * @var TicketRatingTable
     */
    protected $ticketRating;
    protected $timestamps = true;

    public function __construct(TicketRatingTable $ticketRating)
    {
        $this->ticketRating = $ticketRating;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->ticketRating->getList($filters);
    }
    
    public function getAll(array $filters = [])
    {
        return $this->ticketRating->getAll($filters);
    }
    public function getName()
    {
        return $this->ticketRating->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->ticketRating->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {
        return $this->ticketRating->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->ticketRating->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->ticketRating->getItem($id);
    }

    /*
     *  test code
     */
    public function testCode($code, $id)
    {
        return $this->ticketRating->testCode($code, $id);
    }

    /*
    * check exist
    */
    public function checkExistEmail($email = '')
    {
        return $this->ticketRating->checkExistEmail($email);
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id, $startTime, $endTime)
    {
        return $this->ticketRating->testEdit($id, $startTime, $endTime);
    }
    public function removeFile($ticketId)
    {
        return $this->ticketRating->removeFile($ticketId);
    }
}