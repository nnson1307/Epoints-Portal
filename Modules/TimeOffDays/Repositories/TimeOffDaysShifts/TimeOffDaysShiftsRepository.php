<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysShifts;

use Modules\TimeOffDays\Models\TimeOffDaysShiftsTable;

class TimeOffDaysShiftsRepository implements TimeOffDaysShiftsRepositoryInterface
{
    protected $repo;

    public function __construct(TimeOffDaysShiftsTable $repo)
    {
        $this->repo = $repo;
    }

    public function getLists($data){
        return $this->repo->getLists($data);
    }

    public function getListsByDaysOff($daysOffId){
        return $this->repo->getListsByDaysOff($daysOffId);
    }
    
    public function add($data){
        return $this->repo->add($data);
    }

    /**
     * Cập nhật loại thông tin kèm theo
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function edit($data, $id){
        return $this->repo->edit($data, $id);
    }
    public function remove($id){
        return $this->repo->remove($id);
    }

    /**
     * Lấy tổng số ngày phép đã nghĩ
     * @data filter: 
     *  staff_id
     *  time_off_type_id
     *  month
     *  years
     *  month_reset
     */
    public function getNumberDaysOff($data){
        return $this->repo->getNumberDaysOff($data);
    }

    public function checkExist($timeWorkingStaffId, $timeOffTypeId){
        return $this->repo->checkExist($timeWorkingStaffId, $timeOffTypeId);
    }
}