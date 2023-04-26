<?php

namespace Modules\Notification\Repositories\ConfigStaff;

interface ConfigStaffRepoInterface
{
    /**
     * Lấy dữ liệu view index
     *
     * @return mixed
     */
    public function dataIndex();

    /**
     * Lấy thông tin cấu hình thông báo nhân viên
     *
     * @param $key
     * @return mixed
     */
    public function dataEdit($key);

    /**
     * Chỉnh sửa cấu hình thông báo nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Thay đổi trạng thái
     *
     * @param $input
     * @return mixed
     */
    public function changeStatus($input);

    /**
     * Upload hình ảnh
     *
     * @param $input
     * @return mixed
     */
    public function uploadImage($input);
}