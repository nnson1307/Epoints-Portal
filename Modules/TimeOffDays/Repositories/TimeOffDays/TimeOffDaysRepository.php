<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDays;

use Modules\TimeOffDays\Models\TimeOffDaysTable;
use Modules\TimeOffDays\Models\StaffsTable;
use Modules\TimeOffDays\Models\TimeOffDaysTimeTable;
use Modules\TimeOffDays\Models\DepartmentTable;
use Illuminate\Support\Facades\Auth;
class TimeOffDaysRepository implements TimeOffDaysRepositoryInterface
{
    protected $repo;

    public function __construct(TimeOffDaysTable $repo)
    {
        $this->repo = $repo;
    }

    public function add(array $data)
    {
        return $this->repo->add($data);
    }

    public function getList($data){
        $staffs = new StaffsTable();
        if (!isset($filter["staff_id"])) {
            $staffInfo = $staffs->getDetail(Auth::id());
            $arrStaff = [];
            if(isset($staffInfo['is_manager']) && $staffInfo['is_manager'] == 1){
                $lstStaff = $staffs->getListStaffDepartment($staffInfo['department_id']);
                foreach ($lstStaff as $objStaff) {
                    $arrStaff[] = $objStaff['staff_id'];
                }
            }
            $data['arr_staff'] = $arrStaff;
            
        }
        $lst = $this->repo->getListTimeOffDay($data);
      
        foreach($lst as $item){
           if($item['direct_management_approve'] == 1){ 
                $staffInfo = $staffs->getDetailApproveLevel1($item['department_id']);
                if(isset($staffInfo)){
                    $item['staff_id_approve_level1'] = $staffInfo['staff_id'];
                }
                
           }
        }
        return $lst;
    }

    public function getDetail($id){
        return $this->repo->getDetail($id);
    }

    public function edit(array $data, $id)
    {
        return $this->repo->edit($data, $id);
    }
    
    public function total($id)
    {
        return $this->repo->total($id);
    }

    public function reportByType($params)
    {
        return $this->repo->reportByType($params);
    }
    
    public function reportByTopTen($params)
    {
        return $this->repo->reportByTopTen($params);
    }
    
    public function reportByPrecious($params)
    {
        return $this->repo->reportByPrecious($params);
    }

    public function getOptionDaysOffTime(){
        $timeOffDaysTime = new TimeOffDaysTimeTable();
        return $timeOffDaysTime->getOptionList();
    }

    public function getOptionDepartment(){
        $department = new DepartmentTable();
        return $department->getOptionList();
    }

}