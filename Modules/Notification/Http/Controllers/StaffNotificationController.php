<?php

namespace Modules\Notification\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Notification\Repositories\StaffNotification\StaffNotificationRepoInterface;

class StaffNotificationController extends Controller
{
    protected $staffNotification;
    public function __construct(StaffNotificationRepoInterface $staffNotification)
    {
        $this->staffNotification = $staffNotification;
    }

    /**
     * Lấy tất cả notify
     *
     * @param Request $request
     * @return mixed
     */
    public function getAll(Request $request)
    {
        $data = $request->all();
        return $this->staffNotification->getAllNotification($data);
    }

    /**
     * Lấy thông báo mới
     *
     * @return mixed
     */
    public function getNotificationNew()
    {
        return $this->staffNotification->getNotificationNew();
    }

    /**
     * Cập nhật trạng thái đã đọc khi click thông báo
     *
     * @param Request $request
     * @return mixed
     */
    public function updateStatus(Request $request)
    {
        $data = $request->all();
        return $this->staffNotification->updateStatus($data);
    }

    /**
     * Lấy số lượng thông báo mới
     *
     * @return mixed
     */
    public function getNumberOfNotificationNew()
    {
        return $this->staffNotification->getNumberOfNotificationNew();
    }

    /**
     * Clear những thông báo mới khi click vào chuông
     *
     * @return mixed
     */
    public function clearNotifyNewAction()
    {
        return $this->staffNotification->clearNotifyNew();
    }
}