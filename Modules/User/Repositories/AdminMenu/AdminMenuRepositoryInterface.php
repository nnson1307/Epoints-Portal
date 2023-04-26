<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/4/2019
 * Time: 16:17
 */

namespace Modules\User\Repositories\AdminMenu;


interface AdminMenuRepositoryInterface
{
    public function groupCategory($menu_category_id);

    /**
     * Đồng bộ quyền feature
     *
     * @return mixed
     */
    public function syncFeature();
}