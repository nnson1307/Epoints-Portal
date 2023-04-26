<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/06/2021
 * Time: 14:47
 */

namespace Modules\Customer\Repositories\CustomerRemindUse;


interface CustomerRemindUseRepoInterface
{
    /**
     * Danh sách dự kiến nhắc sử dụng
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Dữ liệu view chỉnh sửa dự kiến nhắc sử dụng
     *
     * @param $remindId
     * @return mixed
     */
    public function dataViewEdit($remindId);

    /**
     * Chỉnh sửa dự kiến nhắc sử dụng
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Chăm sóc khách hàng
     *
     * @param $input
     * @return mixed
     */
    public function submitCare($input);
}