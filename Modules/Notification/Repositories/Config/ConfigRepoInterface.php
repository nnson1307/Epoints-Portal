<?php


namespace Modules\Notification\Repositories\Config;


interface ConfigRepoInterface
{
    /**
     * Lấy dữ liệu view index
     *
     * @return mixed
     */
    public function dataIndex();

    /**
     * Lấy thông tin cấu hình thông báo
     *
     * @param $id
     * @return mixed
     */
    public function getInfo($id);

    /**
     * Chỉnh sửa cấu hình thông báo
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

    /**
     * submit contract notify config
     *
     * @param $data
     * @return mixed
     */
    public function submitNotifyContract($data);
}