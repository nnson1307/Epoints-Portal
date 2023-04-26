<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/4/2019
 * Time: 16:18
 */

namespace Modules\User\Repositories\AdminMenuCategory;


interface AdminMenuCategoryRepositoryInterface
{
    public function getAll();

    /**
     * Lấy danh sách nhóm menu cho menu ngang
     *
     * @return mixed
     */
    public function getListGroupMenu();
}