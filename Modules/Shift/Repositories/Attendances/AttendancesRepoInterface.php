<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 10:50
 */

namespace Modules\Shift\Repositories\Attendances;


interface AttendancesRepoInterface
{

    /**
     * get data shift
     *
     * @return mixed
     */
    public function getDataFilter();

    /**
     * insert data checkin log
     *
     * @return mixed
     */
    public function checkin($input);

    /**
     * insert data checkout log
     *
     * @return mixed
     */
    public function checkout($input);
    
    /**
     * get data checkin log
     *
     * @return mixed
     */
    public function getListShift();

    /**
     * get data checkin 
     *
     * @return mixed
     */
    public function getListShiftCheckin($staffId, $working_day, $time_day);

     /**
     * insert data checkin log
     *
     * @return mixed
     */
    public function getListHistoryCheckIn(array $filters = []);

    /**
     * get list branch
     *
     * @return mixed
     */
    public function getListBranch();

    /**
     * get list department
     *
     * @return mixed
     */
    public function getListDepartment();

     /**
     * get list department
     *
     * @return mixed
     */
    public function updateWorkingTime(array $data = [], $id);

    /**
     * duyệt đi trễ về sớm
     */
    public function approveLateSoon(array $data = [], $id);

    public function getInfo($id);
}