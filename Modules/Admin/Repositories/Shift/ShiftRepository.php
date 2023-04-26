<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Admin\Repositories\Shift;

use Modules\Admin\Models\ShiftTable;

class ShiftRepository implements ShiftRepositoryInterface
{
    /**
     * @var ShiftTable
     */
    protected $shift;
    protected $timestamps = true;

    public function __construct(ShiftTable $shift)
    {
        $this->shift = $shift;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->shift->getList($filters);
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->shift->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->shift->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->shift->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->shift->getItem($id);
    }

    /*
     *  test code
     */
    public function testCode($code, $id)
    {
        return $this->shift->testCode($code, $id);
    }

    /*
    * check exist
    */
    public function checkExist($startTime, $endTime, $isDelete)
    {
        return $this->shift->checkExist($startTime, $endTime, $isDelete);
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id, $startTime, $endTime)
    {
        return $this->shift->testEdit($id, $startTime, $endTime);
    }
}