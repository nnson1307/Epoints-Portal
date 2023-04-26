<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\QueueStaff;

use Modules\Ticket\Models\QueueStaffTable;

class QueueStaffRepository implements QueueStaffRepositoryInterface
{
    /**
     * @var QueueStaffTable
     */
    protected $staff;
    protected $timestamps = true;

    public function __construct(QueueStaffTable $staff)
    {
        $this->staff = $staff;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->staff->getList($filters);
    }

    public function getAll(array $filters = [])
    {
        return $this->staff->getAll($filters);
    }
    // lấy id nv đã phân công
    public function getStaff()
    {
        return $this->staff->getStaff();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->staff->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->staff->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->staff->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->staff->getItem($id);
    }
    /*
     *  get OPTION by queue id
     */
    public function getQueueOption($ticket_queue_id,$ticket_role_queue_id)
    {
        return $this->staff->getQueueOption($ticket_queue_id,$ticket_role_queue_id);
    }

}