<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/07/2022
 * Time: 15:03
 */

namespace Modules\Team\Repositories\Team;


interface TeamRepoInterface
{
    /**
     * Danh sách nhóm
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Lấy dữ liệu view tạo
     *
     * @return mixed
     */
    public function getDataCreate();

    /**
     * Đổi chức vụ load nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function changeTitle($input);

    /**
     * Thêm nhóm
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Lấy dữ liệu view chỉnh sửa
     *
     * @param $id
     * @return mixed
     */
    public function getDataEdit($id);

    /**
     * Chỉnh sửa nhóm
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xoá nhóm
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);
}