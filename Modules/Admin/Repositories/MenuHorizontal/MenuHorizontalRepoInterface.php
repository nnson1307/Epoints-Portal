<?php


namespace Modules\Admin\Repositories\MenuHorizontal;


interface MenuHorizontalRepoInterface
{
    /**
     * Show popup thêm nhóm chức năng menu
     *
     * @return mixed
     */
    public function popupAdd();

    /**
     * Danh sách chức năng
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Lấy danh sách menu theo menu category id
     *
     * @param $input
     * @return mixed
     */
    public function getListMenuByMenuCategory($input);

    /**
     * Thêm chức năng cho menu ngang (thanh điều hướng)
     *
     * @param $input
     * @return mixed
     */
    public function saveMenuHorizontal($input);

    /**
     * Cập nhật trạng thái cho is_active
     *
     * @param $input
     * @return mixed
     */
    public function updateStatus($input);

    /**
     * Xoá chức năng menu ngang (thanh điều hướng)
     *
     * @param $input
     * @return mixed
     */
    public function remove($input);
}