<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/05/2021
 * Time: 15:39
 */

namespace Modules\Customer\Repositories\CustomerInfoTemp;


interface CustomerInfoTempRepoInterface
{
    /**
     * Danh sách thông tin cần cập nhật
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Lấy data view xác nhận thông tin cần cập nhật
     *
     * @param $input
     * @return mixed
     */
    public function dataViewConfirm($input);

    /**
     * Xác nhận thông tin cần cập nhật
     *
     * @param $input
     * @return mixed
     */
    public function confirm($input);
}