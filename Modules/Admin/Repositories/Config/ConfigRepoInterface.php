<?php


namespace Modules\Admin\Repositories\Config;


interface ConfigRepoInterface
{
    /**
     * @return mixed
     */
    public function getAll();

    /**
     * @param $key
     * @return mixed
     */
    public function getInfoByKey($key);

    /**
     * Lấy thông tin theo id
     * @return mixed
     */
    public function getInfoById($id);

    /**
     * Cập nhật config
     * @return mixed
     */
    public function updatekey($data);

    /**
     * lấy danh sách cấu hình chi tiết
     * @return mixed
     */
    public function getConfigDetail($id);

    /**
     * Lấy danh sách timezone
     * @return mixed
     */
    public function getZone();

    /**
     * Lấy danh sách mã vùng
     * @return mixed
     */
    public function getCountryIso();

    /**
     * Lấy tên country
     * @param $country_iso
     * @return mixed
     */
    public function getNameCountryIso($country_iso);

    /**
     * Data view chỈnh sửa cấu hình chung
     *
     * @param $id
     * @return mixed
     */
    public function dataViewEdit($id);

    /**
     * Cập nhật cấu hình chung
     *
     * @param $input
     * @return mixed
     */
    public function updateConfigGeneral($input);
}