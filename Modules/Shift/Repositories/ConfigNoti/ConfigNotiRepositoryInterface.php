<?php


namespace Modules\Shift\Repositories\ConfigNoti;


interface ConfigNotiRepositoryInterface
{
    /**
     * lấy danh thông báo chấm công
     * @return mixed
     */
    public function getListNoti();

    /**
     * Hiển thị popup
     * @param $data
     * @return mixed
     */
    public function showPopup($data);

    /**
     * Cập nhật nội dung thông báo
     * @param $data
     * @return mixed
     */
    public function updateMessage($data);

    /**
     * Cập nhật cấu hình
     * @param $data
     * @return mixed
     */
    public function updateNoti($data);
}