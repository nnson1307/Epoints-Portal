<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/8/2020
 * Time: 10:40 AM
 */

namespace Modules\Delivery\Repositories\UserCarrier;


interface UserCarrierRepoInterface
{
    /**
     * Danh sách nv giao hàng
     *
     * @param array $filters = []
     * @return mixed
     */
    public function getList(array $filters = []);

    /**
     * Thêm nv giao hàng
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Lấy thông tin nv giao hàng
     *
     * @param $userCarrierId
     * @return mixed
     */
    public function getInfo($userCarrierId);

    /**
     * Chỉnh sửa nv giao hàng
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
     * Xóa nv giao hàng
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);
}