<?php


namespace Modules\Admin\Repositories\MenuVertical;


interface MenuVerticalRepoInterface
{
    /**
     * Danh sách chức năng
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Show popup thêm nhóm chức năng menu
     *
     * @return mixed
     */
    public function popupAdd();

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
    public function saveMenuVertical($input);

    /**
     * Cập nhật trạng thái cho is_active
     *
     * @param $input
     * @return mixed
     */
    public function updateStatus($input);

    /**
     * Xoá chức năng menu doc (truy cập nhanh)
     *
     * @param $input
     * @return mixed
     */
    public function remove($input);
}