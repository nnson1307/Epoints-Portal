<?php

namespace Modules\Notification\Repositories\Notification;

interface NotificationRepositoryInterface
{
    /**
     * Lấy danh sách notification type
     *
     * @return mixed
     */
    public function getNotificationTypeList($filter = []);

    /**
     * Lấy danh sách chi tiết đích đến
     *
     * @param $data
     * @return mixed
     */
    public function getDetailEndPoint($data);

    /**
     * Lấy danh sách nhóm
     *
     * @param $data
     */
    public function getGroupList($data);

    /**
     * Lưu thông báo
     *
     * @param $data
     */
    public function store($data);

    /**
     * Lấy chi tiết thông báo
     *
     * @param $id
     * @return mixed
     */
    public function getNotiById($id);

    /**
     * Cập nhật thông báo
     *
     * @param $id
     * @param $data
     */
    public function update($id, $data);

    /**
     * Lấy dánh sách thông báo
     *
     * @param $filter
     * @return mixed
     */
    public function getNotiList($filter);

    /**
     * Cập nhật hoạt động is_actived
     *
     * @param $id
     * @param $check
     * @return mixed
     */
    public function updateIsActived($id, $data);

    /**
     * Xóa theo detail id
     *
     * @param $id
     */
    public function destroy($id);

    /**
     * Lấy thông tin theo end point
     *
     * @param $data
     * @return array
     */
    public function getEndPointJson($data);

    /**
     * @param $noti
     * @param $acParams
     * @return mixed
     */
    public function getObjectNameDetailEndPoint($noti, $acParams);

    /**
     * popup tạo deal
     *
     * @param $input
     * @return mixed
     */
    public function popupCreateDeal($input);

    /**
     * popup chỉnh sửa thông tin deal
     *
     * @param $input
     * @return mixed
     */
    public function popupEditDeal($input);
}
