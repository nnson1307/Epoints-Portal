<?php


namespace Modules\Admin\Repositories\MenuHorizontal;


use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\AdminMenuCategoryTable;
use Modules\Admin\Models\AdminMenuFunctionTable;
use Modules\Admin\Models\AdminMenuTable;

class MenuHorizontalRepo implements MenuHorizontalRepoInterface
{
    protected $menuHorizontal;
    public function __construct(AdminMenuFunctionTable $menuHorizontal)
    {
        $this->menuHorizontal = $menuHorizontal;
    }

    /**
     * Show popup thêm nhóm chức năng menu
     *
     * @return array|mixed
     */
    public function popupAdd()
    {
        $mMenuCat = new AdminMenuCategoryTable();
        $optionMenuCat = $mMenuCat->getAll();
        $html = \View::make('admin::menu-horizontal.popup-add', [
            'menuCategory' => $optionMenuCat,
        ])->render();

        return [
            'html' => $html
        ];
    }

    public function list(array $filters = [])
    {
        $list = $this->menuHorizontal->getList($filters);
        return [
            'list' => $list
        ];
    }

    /**
     * Lấy danh sách menu theo menu category id trừ những menu đã được thêm vào admin_menu_function
     *
     * @param $input
     * @return mixed
     */
    public function getListMenuByMenuCategory($input)
    {
        $mMenu = new AdminMenuTable();
        $type = $input['type'];
        $menuFunctionExist = $this->menuHorizontal->getMenuFunctionByMenuCat($input['menu_category_id'], $type)->toArray();
        $optionMenu = $mMenu->getListMenuByMenuCatId($input['menu_category_id'], $menuFunctionExist);
        return [
            'optionMenu' => $optionMenu
        ];
    }

    /**
     * Thêm chức năng cho menu ngang (thanh điều hướng)
     *
     * @param $input
     * @return array
     */
    public function saveMenuHorizontal($input)
    {
        try {
            $arrMenuFunction = [];
            $mMenuCategory = new AdminMenuCategoryTable();
            $arrMenuFunction = $input['admin_menu'];

            // Each group maximum 4 function
//            $check = $this->menuHorizontal->getMenuFunctionActiveByMenuCat($input['admin_menu_category']);
//
//            if (count($check) >=4) {
//                return [
//                    'error' => 1,
//                    'message' => __('Một nhóm chức năng tối đa 4 chức năng.')
//                ];
//            }

            if ($arrMenuFunction != null && count($arrMenuFunction) > 0) {
                foreach ($arrMenuFunction as $k => $value) {
                    $data = [
                        'admin_menu_category_id' => $input['admin_menu_category'],
                        'admin_menu_id' => $value,
                        'position' => $k,
                        'type' => 'horizontal',
                        'created_at' => date('Y-m-d'),
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ];
                    $this->menuHorizontal->add($data);
                }
            }

            $mMenuCategory->edit(['is_show' => 1], $input['admin_menu_category']);

            return [
                'error' => 0,
                'message' => __('Thêm thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => __('Thêm thất bại')
            ];
        }
    }

    /**
     * Cập nhật trạng thái cho is_active
     *
     * @param $input
     * @return array|mixed
     */
    public function updateStatus($input)
    {
        try {
            // Check mỗi nhóm chức năng chỉ được 4 chức năng active
            if ($input['is_actived'] == 1) {
                $check = $this->menuHorizontal->getMenuFunctionActiveByMenuCat($input['admin_menu_category']);
                if (count($check) >= 4) {
                    return [
                        'error' => 1,
                        'message' => __('Một nhóm chức năng tối đa 4 chức năng.')
                    ];
                }
            }

            $this->menuHorizontal->edit(['is_actived' => $input['is_actived']], $input['admin_menu_function_id']);
            return [
                'error' => 0,
                'message' => __('Cập nhật thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => __('Cập nhật thất bại')
            ];
        }
    }

    /**
     * Xoá chức năng menu ngang (thanh điều hướng)
     *
     * @param $input
     * @return array|mixed
     */
    public function remove($input)
    {
        try {
            $this->menuHorizontal->remove($input['admin_menu_function_id']);
            return [
                'error' => 0,
                'message' => __('Xoá thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => __('Xoá thất bại')
            ];
        }
    }
}