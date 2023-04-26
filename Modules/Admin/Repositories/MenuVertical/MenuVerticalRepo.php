<?php


namespace Modules\Admin\Repositories\MenuVertical;


use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\AdminMenuCategoryTable;
use Modules\Admin\Models\AdminMenuFunctionTable;
use Modules\Admin\Models\AdminMenuTable;

class MenuVerticalRepo implements MenuVerticalRepoInterface
{
    protected $menuVertical;
    public function __construct(AdminMenuFunctionTable $menuVertical)
    {
        $this->menuVertical = $menuVertical;
    }

    public function list(array $filters = [])
    {
        $list = $this->menuVertical->getList($filters);
        return [
            'list' => $list
        ];
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
        $html = \View::make('admin::menu-vertical.popup-add', [
            'menuCategory' => $optionMenuCat,
        ])->render();

        return [
            'html' => $html
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
        $menuFunctionExist = $this->menuVertical->getMenuFunctionByMenuCat($input['menu_category_id'], $type)->toArray();
        $optionMenu = $mMenu->getListMenuByMenuCatId($input['menu_category_id'], $menuFunctionExist);
        return [
            'optionMenu' => $optionMenu
        ];
    }

    /**
     * Thêm chức năng cho menu dọc (truy cập nhanh)
     *
     * @param $input
     * @return array
     */
    public function saveMenuVertical($input)
    {
        try {
            $menuFunction = $input['admin_menu'];
            // Maximum 10 function
            $check = $this->menuVertical->getMenuFunctionActive('vertical');
            if (count($check) >= 10) {
                return [
                    'error' => 1,
                    'message' => __('Tối đa 10 chức năng hoạt động.')
                ];
            }

            if ($menuFunction != null) {
                $data = [
                    'admin_menu_category_id' => $input['admin_menu_category'],
                    'admin_menu_id' => $menuFunction,
                    'position' => 0,
                    'type' => 'vertical',
                    'created_at' => date('Y-m-d'),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id()
                ];
                $this->menuVertical->add($data);
            }

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
            // Check tối đa 10 admin function được active
            if ($input['is_actived'] == 1) {
                $check = $this->menuVertical->getMenuFunctionActive('vertical');
                if (count($check) >= 10) {
                    return [
                        'error' => 1,
                        'message' => __('Tối đa 10 chức năng hoạt động.')
                    ];
                }
            }

            $this->menuVertical->edit(['is_actived' => $input['is_actived']], $input['admin_menu_function_id']);
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
            $this->menuVertical->remove($input['admin_menu_function_id']);
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