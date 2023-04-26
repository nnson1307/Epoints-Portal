<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\Staff;

use Modules\Ticket\Models\StaffTable;

class StaffRepository implements StaffRepositoryInterface
{
    /**
     * @var StaffTable
     */
    protected $staff;
    protected $timestamps = true;

    public function __construct(StaffTable $staff)
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

    public function listStaff(array $filters = [])
    {
        return $this->staff->listStaff($filters);
    }

    public function getName()
    {
        return $this->staff->getName();
    }

    public function getAll(array $filters = [])
    {
        return $this->staff->getAll($filters);
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
     *  test code
     */
    public function testCode($code, $id)
    {
        return $this->staff->testCode($code, $id);
    }

    /*
    * check exist
    */
    public function checkExistEmail($email = '')
    {
        return $this->staff->checkExistEmail($email);
    }

    /*
    * get detail
    */
    public function getDetail($id = '')
    {
        return $this->staff->getDetail($id);
    }
    
}