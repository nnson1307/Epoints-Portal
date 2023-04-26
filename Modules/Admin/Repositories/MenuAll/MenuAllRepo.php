<?php


namespace Modules\Admin\Repositories\MenuAll;


use Illuminate\Support\Facades\Cache;
use Modules\Admin\Models\AdminMenuCategoryTable;
use Modules\Admin\Models\AdminMenuTable;

class MenuAllRepo implements MenuAllRepoInterface
{
    protected $menuAll;

    public function __construct(AdminMenuCategoryTable $menuCategory)
    {
        $this->menuAll = $menuCategory;
    }

    /**
     * Danh sách tất cả menu theo group
     *
     * @param $search
     * @return array|mixed
     */
    public function getMenuByGroup($search = null)
    {
        $data = [];
        $idTenant = session()->get('idTenant');
        if (Cache::has('data_menu_'.$idTenant)) {
            //Đã có cache data
            $cacheData = Cache::get('data_menu_'.$idTenant);

            if (count($cacheData) > 0) {
                foreach ($cacheData as $v) {
                    //Parse thành collection laravel
                    $collection = collect($v['menu']);
                    //Xử lý filter
                    $collection = $collection->filter(function ($item) use ($search) {
                        return preg_match("/$search/", $item['admin_menu_name']);
                    });

                    $v['menu'] = $collection->whereIn('admin_menu_route', session('routeList'));

                    if (count($v['menu']) > 0) {
                        $data[] = [
                            'menu_category_name' => $v['menu_category_name'],
                            'menu_category_icon' => $v['menu_category_icon'],
                            'menu_category_id' => $v['menu_category_id'],
                            'menu' => $v['menu']
                        ];
                    }
                }
            }
        } else {
            $mAdminMenu = new AdminMenuTable();
            //Lấy nhóm quyền menu
            $listMenuCategory = $this->menuAll->getAll();

            if ($listMenuCategory != null && count($listMenuCategory) > 0) {
                foreach ($listMenuCategory as $item) {
                    //Đếm số lượng menu trong menu_cat, nếu = 0 thì không add vào data
                    $menu = $mAdminMenu->getListMenuByMenuCatId($item['menu_category_id'], [], $search);

                    if (count($menu) > 0) {
                        foreach ($menu as $k => $v) {
                            if (!in_array($v['admin_menu_route'], session('routeList'))) {
                                unset($menu[$k]);
                            }
                        }
                    }

                    if (count($menu) > 0) {
                        $data[] = [
                            'menu_category_name' => $item['menu_category_name'],
                            'menu_category_icon' => $item['menu_category_icon'],
                            'menu_category_id' => $item['menu_category_id'],
                            'menu' => $menu
                        ];
                    }
                }
            }
            //Gán cache cho nó
            Cache::forever('data_menu_'.$idTenant, $data);
        }
        
        return $data;
    }

    /**
     * Danh sách tất cả menu không theo group (category)
     *
     * @param $filter
     * @return mixed
     */
    public function getMenuNotByGroup()
    {
        $mMenu = new AdminMenuTable();
        $getAllMenu = $mMenu->getAllMenu()->toArray();
        return $getAllMenu;
    }

    /**
     * Search menu all
     *
     * @param $input
     * @return array|mixed
     */
    public function dataSearchMenuAll($input)
    {
        //Lấy menu category
        $dataGroup = $this->getMenuByGroup(isset($input['search']) ? $input['search'] : null);

        $view = \View::make('admin::menu-all.list', [
            'data' => $dataGroup,
        ])->render();

        return [
            'html' => $view
        ];
    }
}