<?php

namespace Modules\Notification\Repositories\StaffNotification;

interface StaffNotificationRepoInterface
{
    /**
     * Lấy tất cả notify
     *
     * @param $input
     * @return mixed
     */
    public function getAllNotification($input);

    public function getNotificationNew();

    /**
     * Cập nhật trạng thái đã đọc khi click thông báo
     *
     * @param $input
     * @return mixed
     */
    public function updateStatus($input);

    public function getNumberOfNotificationNew();

    /**
     * Clear những thông báo mới khi click vào chuông
     *
     * @return mixed
     */
    public function clearNotifyNew();
}