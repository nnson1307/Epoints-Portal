<?php


namespace Modules\ManagerProject\Repositories\ManageConfig;


interface ManageConfigRepositoryInterface
{


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