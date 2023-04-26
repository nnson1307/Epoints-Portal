<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\Admin\Repositories\Shift;


interface ShiftRepositoryInterface
{
    /**
     * Get shift list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Delete shift
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add shift
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update shift
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);

    /**
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);

    public function testCode($code, $id);

    /*
    * check exist
    */
    public function checkExist($startTime, $endTime, $isDelete);

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id, $startTime, $endTime);
}