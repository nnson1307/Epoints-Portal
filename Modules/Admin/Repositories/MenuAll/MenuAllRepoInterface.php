<?php


namespace Modules\Admin\Repositories\MenuAll;


interface MenuAllRepoInterface
{
    /**
     * Danh sách tất cả menu theo group
     *
     * @return mixed
     */
    public function getMenuByGroup();

    /**
     * Lấy danh sách mennu không theo group
     *
     * @param $filter
     * @return mixed
     */
    public function getMenuNotByGroup();

    /**
     * Search menu all
     *
     * @param $input
     * @return mixed
     */
    public function dataSearchMenuAll($input);
}