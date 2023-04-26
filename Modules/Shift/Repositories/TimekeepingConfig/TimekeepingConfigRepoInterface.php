<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 2:24 PM
 */

namespace Modules\Shift\Repositories\TimekeepingConfig;


interface TimekeepingConfigRepoInterface
{
    /**
     * Lấy dữ liệu filter ds
     *
     * @return mixed
     */
    public function getDataFilter();

    /**
     * Danh sách
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Data view thêm
     *
     * @param $input
     * @return mixed
     */
    public function dataViewCreate($input);

    /**
     * Thêm
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Data view chỉnh sửa
     *
     * @param $input
     * @return mixed
     */
    public function dataViewEdit($input);

    /**
     * Chỉnh sửa
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xóa
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);

    /**
     * Lấy ip hiện tại
     *
     * @return mixed
     */
    public function currentIp();

    /**
     * Cập nhật trạng thái
     *
     * @param $input
     * @return mixed
     */
    public function changeStatus($input);
}