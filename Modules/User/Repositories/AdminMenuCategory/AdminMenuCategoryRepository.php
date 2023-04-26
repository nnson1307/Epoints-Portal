<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/4/2019
 * Time: 16:18
 */

namespace Modules\User\Repositories\AdminMenuCategory;


use Modules\User\Models\AdminMenuCategoryTable;

class AdminMenuCategoryRepository implements AdminMenuCategoryRepositoryInterface
{
    protected $admin_menu_category;
    protected $timestamps = true;

    public function __construct(AdminMenuCategoryTable $admin_menu_category)
    {
        $this->admin_menu_category = $admin_menu_category;
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
        return $this->admin_menu_category->getAll();
    }

    public function getListGroupMenu()
    {
        // TODO: Implement getListGroupMenu() method.
        return $this->admin_menu_category->getListGroupMenu();
    }
}