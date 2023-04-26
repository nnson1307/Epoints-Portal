<?php


namespace Modules\FNB\Repositories\Staff;


use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\FNB\Models\StaffsTable;

class StaffRepository implements StaffRepositoryInterface
{
    private $staffs;

    public function __construct(StaffsTable $staffs)
    {
        $this->staffs = $staffs;
    }

    /**
     * Lấy danh sách nhân viên
     */
    public function getAll(){
        return $this->staffs->getAll();
    }

    public function getStaffTechnician(){
        $array=array();
        foreach ($this->staffs->getStaffTechnician() as $item)
        {
            $array[$item['staff_id']]=$item['full_name'];

        }
        return $array;
    }

    /**
     * Hiển thị popup chọn nhân viên phục vụ
     * @param $data
     */
    public function chooseWaiter($data)
    {
        try {
            $listStaff = $this->getAll();

            $view = view('fnb::orders.popup.choose-waiter',['listStaff' => $listStaff,'staff_id_select' => $data['staff_id']])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị popup thất bại')
            ];
        }
    }

    public function getItem($staffId)
    {
        return $this->staffs->getItem($staffId);
    }
}