<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffType;

use Illuminate\Support\Facades\Auth;
use Modules\TimeOffDays\Models\TimeOffTypeTable;
use Modules\TimeOffDays\Models\TimeOffTypeOptionTable;
use Modules\TimeOffDays\Models\StaffTitleTable;
use Modules\TimeOffDays\Models\StaffsTable;

class TimeOffTypeRepository implements TimeOffTypeRepositoryInterface
{
    protected $repo;
    protected $repoTimeOffTypeOption;
    protected $repoStaffTitle;

    public function __construct(TimeOffTypeTable $repo, TimeOffTypeOptionTable $repoTimeOffTypeOption, StaffTitleTable $repoStaffTitle)
    {
        $this->repo = $repo;
        $this->repoTimeOffTypeOption = $repoTimeOffTypeOption;
        $this->repoStaffTitle = $repoStaffTitle;
    }

    public function getList($data){
        $staffs = new StaffsTable();
        $data = $this->repo->getList($data);
        
        // foreach ($data as $item) {
        //     if(isset($item['staff_id_approve_level2'])){
        //         $staffInfo = $staffs->getDetailStaffApproveInfo($item['staff_id_approve_level2']);
        //         if(isset($staffInfo)){
        //             $item['approve_level_2_name'] = $staffInfo['full_name'];
        //         }
        //     }
        //     if(isset($item['staff_id_approve_level3'])){
        //         $staffInfo = $staffs->getDetailStaffApproveInfo($item['staff_id_approve_level3']);
        //         if(isset($staffInfo)){
        //             $item['approve_level_3_name'] = $staffInfo['full_name'];
        //         }
        //     }
        // }
        
        return $data;
    }

    public function getAll(){
        return $this->repo->getAll();
    }

    public function edit($data, $id){
        return $this->repo->edit($data, $id);
    }

    public function getDetail($id){
        return $this->repo->getDetail($id);
    }

    /**
     * Danh sách
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffDaysActivityApproveRepoException
     */
    public function getListsStaffApprove($timeOffTypeId)
    {
        $staffs = new StaffsTable();
        $item = $this->repo->getDetail($timeOffTypeId);
        $arrayData = [];
        if($item['direct_management_approve'] != 0){
            $staffInfo = $staffs->getDetailApproveLevel1(Auth::user()->department_id);
           
            if(isset($staffInfo)){
                $arrayData[] =
                    [
                        'staff_id' => $staffInfo['staff_id'],
                        'full_name' => $staffInfo['full_name'],
                        'staff_avatar' => $staffInfo['staff_avatar'],
                        'staff_title' => $staffInfo['staff_title'],
                        'staff_title_id' => $staffInfo['staff_title_id'],
                    ];
            }
        }
        if(isset($item['staff_id_approve_level2'])){
            $arrApproveLevel2 = json_decode($item['staff_id_approve_level2']);
            dd($arrApproveLevel2);
            $staffInfo = $staffs->getDetailStaffApproveInfo($item['staff_id_approve_level2']);
            if(isset($staffInfo)){
                $arrayData[] =
                    [
                        'staff_id' => $staffInfo['staff_id'],
                        'full_name' => $staffInfo['full_name'],
                        'staff_avatar' => $staffInfo['staff_avatar'],
                        'staff_title' => $staffInfo['staff_title'],
                        'staff_title_id' => $staffInfo['staff_title_id'],
                    ];
            }
        }
        if(isset($item['staff_id_approve_level3'])){
            $staffInfo = $staffs->getDetailStaffApproveInfo($item['staff_id_approve_level3']);
            if(isset($staffInfo)){
                $arrayData[] =
                [
                    'staff_id' => $staffInfo['staff_id'],
                    'full_name' => $staffInfo['full_name'],
                    'staff_avatar' => $staffInfo['staff_avatar'],
                    'staff_title' => $staffInfo['staff_title'],
                    'staff_title_id' => $staffInfo['staff_title_id'],
                ];
            }
        }
       
        return $arrayData;
    }
}