<?php


namespace Modules\ManagerWork\Repositories\ManageConfig;


interface ManageConfigRepositoryInterface
{
    /**
     * lấy danh sách role
     * @return mixed
     */
    public function getListRole();

    /**
     * Cập nhật cấu hình quyền
     * @param $data
     * @return mixed
     */
    public function updateAction($data);

    /**
     * Lấy danh sách trạng thái được cấu hình
     * @return mixed
     */
    public function getListStatus();

    /**
     * Lấy danh sách trạng thái để chọn
     * @return mixed
     */
    public function getListStatusSelect();

    /**
     * Lấy danh sáhc trạng thái đang hoạt động để chọn
     * @return mixed
     */
    public function getListStatusSelectActive();

    /**
     * Lấy danh sách all để cấu hình
     * @return mixed
     */
    public function getAllConfig();

    /**
     * Thêm view cấu hình trạng thái
     * @return mixed
     */
    public function addStatusConfig($data);

    /**
     * Cập nhật cấu hình trạng thái
     * @param $data
     * @return mixed
     */
    public function updateConfigStatus($data);

    /**
     * Lấy danh sách noti cấu hình
     * @return mixed
     */
    public function getListNotiConfig();

    /**
     * Lấy giao diện popup
     * @param $data
     * @return mixed
     */
    public function showPopup($data);

    /**
     * Cập nhật cấu hình noti
     * @param $data
     * @return mixed
     */
    public function updateNotification($data);

    /**
     * Xoá cấu hình trạng thái
     * @param $data
     * @return mixed
     */
    public function removeStatusConfig($data);

    /**
     * Cập nhật trạng thái hoạt động theo cấu hình
     * @param $data
     * @return mixed
     */
    public function updateActive($data);
}