<?php


namespace Modules\FNB\Repositories\ConfigColumn;


interface ConfigColumnRepositoryInterface
{
    /**
     * Hiển thị cấu hình
     * @param $data
     * @return mixed
     */
    public function showColumn($data);

    /**
     * Lưu cấu hình
     * @param $data
     * @return mixed
     */
    public function saveConfig($data);

    /**
     * Lấy cấu hình hiển thị cột và filter theo route và nhân viên
     * @param $staffId
     * @param $route
     * @return mixed
     */
    public function getConfigByStaffRoute($staffId,$route);

    /**
     * Lấy danh sách cấu hình theo từng nhân viên
     * @param $staffId
     * @param $route
     * @return mixed
     */
    public function getAllConfigStaff($staffId,$route);
}