<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */
namespace Modules\TimeOffDays\Repositories\TimeOffDaysShifts;


interface TimeOffDaysShiftsRepositoryInterface
{
    public function getLists($data);

    public function getListsByDaysOff($daysOffId);

    public function add($data);

    /**
     * Cập nhật loại thông tin kèm theo
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function edit($data, $id);

    public function remove($id);

    /**
     * Lấy tổng số ngày phép đã nghĩ
     * @data filter: 
     *  staff_id'
     *  time_off_type_id']
     *  month
     *  years
     *  month_reset
     */
    public function getNumberDaysOff($data);

    public function checkExist($timeWorkingStaffId, $timeOffTypeId);
}