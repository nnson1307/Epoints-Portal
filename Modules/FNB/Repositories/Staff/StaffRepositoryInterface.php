<?php


namespace Modules\FNB\Repositories\Staff;


interface StaffRepositoryInterface
{
    /**
     * lấy danh sách nhân viên
     * @return mixed
     */
    public function getAll();

    public function getStaffTechnician();

    /**
     * Chọn nhân viên phục vụ
     * @param $data
     * @return mixed
     */
    public function chooseWaiter($data);

    public function getItem($staffId);
}