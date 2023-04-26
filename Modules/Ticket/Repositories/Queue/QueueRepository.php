<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\Queue;

use Modules\Ticket\Models\QueueTable;

class QueueRepository implements QueueRepositoryInterface
{
    /**
     * @var QueueTable
     */
    protected $queue;
    protected $timestamps = true;

    public function __construct(QueueTable $queue)
    {
        $this->queue = $queue;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->queue->getList($filters);
    }
    
    public function getAll(array $filters = [])
    {
        return $this->queue->getAll($filters);
    }
    public function getName()
    {
        return $this->queue->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->queue->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->queue->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->queue->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->queue->getItem($id);
    }

    /*
     *  test code
     */
    public function testCode($code, $id)
    {
        return $this->queue->testCode($code, $id);
    }

    /*
    * check exist
    */
    public function checkExistEmail($email = '',$id = '')
    {
        return $this->queue->checkExistEmail($email,$id);
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id, $startTime, $endTime)
    {
        return $this->queue->testEdit($id, $startTime, $endTime);
    }
}