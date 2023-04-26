<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */

namespace Modules\TimeOffDays\Repositories\Staffs;

use Modules\TimeOffDays\Models\StaffsTable;

class StaffsRepository implements StaffsRepositoryInterface
{
    protected $repo;

    public function __construct(StaffsTable $repo)
    {
        $this->repo = $repo;
    }

    public function getAll(){
        return $this->repo->getAll();
    }

    public function getListById($input){
        return $this->repo->getListById($input);
    }

    public function getListStaffApprove(){
        $data = array(
            [
                'staff_id' => 139, 
                'full_name' => 'Vũ Ngô', 
                'staff_avatar' => 'https://epoint-bucket.s3.ap-southeast-1.amazonaws.com/0f73a056d6c12b508a05eea29735e8a5/2022/05/18/qayd74165284687218052022_avatar.jpg', 
                'staff_title' => 'Trưởng Phòng', 
                'staff_title_id' => 1, 
            ],
            [
                'staff_id' => 144, 
                'full_name' => 'Nguyễn Phương Bình', 
                'staff_avatar' => 'https://epoint-bucket.s3.ap-southeast-1.amazonaws.com/0f73a056d6c12b508a05eea29735e8a5/2022/06/10/vjgLo1165482900510062022_avatar.jpg', 
                'staff_title' => 'P Giám Đốc', 
                'staff_title_id' => 2, 
            ],
            [
                'staff_id' => 94, 
                'full_name' => 'Dương Thanh Tâm', 
                'staff_avatar' => 'https://epoint-bucket.s3.ap-southeast-1.amazonaws.com/0f73a056d6c12b508a05eea29735e8a5/2022/05/10/knEIc3165215248710052022_avatar.png', 
                'staff_title' => 'Giám Đốc', 
                'staff_title_id' => 3, 
            ]
            
        );
        return $data;
    }

    public function getListStaffDepartment($departmentId){
        return $this->repo->getListStaffDepartment($departmentId);
    }

    public function getDetailStaffApproveInfo($staffId){
        return $this->repo->getDetailStaffApproveInfo($staffId);
    }

    public function getDetailApproveLevel1($departmentId){
        return $this->repo->getDetailApproveLevel1($departmentId);
    }
    
    public function getListStaffApproveInfo($arrStaffs){
        return $this->repo->getListStaffApproveInfo($arrStaffs);
    }
}