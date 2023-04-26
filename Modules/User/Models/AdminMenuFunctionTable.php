<?php


namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class AdminMenuFunctionTable extends Model
{
    protected $table = 'admin_menu_function';
    protected $primaryKey = 'admin_menu_function_id';
    protected $fillable = [
        'admin_menu_function_id', 'admin_menu_category_id', 'admin_menu_id', 'position', 'type',
        'is_actived', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    /**
     * Lấy danh sách menu dọc (truy cập nhanh)
     *
     * @return mixed
     */
    public function getListMenuVertical()
    {
        $lang = app()->getLocale();

        $res = $this->select(
            "{$this->table}.admin_menu_id",
            "admin_menu.admin_menu_name_$lang as admin_menu_name",
            "admin_menu.admin_menu_route",
            "admin_menu.admin_menu_icon",
            "admin_menu.admin_menu_img"
        )
            ->join("admin_menu", "admin_menu.admin_menu_id", "=", "{$this->table}.admin_menu_id")
            ->where("{$this->table}.type", "vertical")
            ->where("{$this->table}.is_actived", 1)
            ->orderBy("{$this->table}.admin_menu_function_id");

        return $res->limit(10)->get();
    }

    /**
     * Danh sách menu chức năng của từng nhóm menu (menu category id)
     *
     * @param $menuCatId
     */
    public function getListMenuHorizontalByMenuCat($menuCatId)
    {
        $lang = app()->getLocale();

        $res = $this->select(
            "{$this->table}.admin_menu_id",
            "admin_menu.admin_menu_name_$lang as admin_menu_name",
            "admin_menu.admin_menu_route",
            "admin_menu.admin_menu_icon",
            "admin_menu.admin_menu_img"
        )
            ->join("admin_menu", "admin_menu.admin_menu_id", "=", "{$this->table}.admin_menu_id")
            ->where("{$this->table}.type", "horizontal")
            ->where("{$this->table}.is_actived", 1)
            ->where("{$this->table}.admin_menu_category_id", $menuCatId)
            ->orderBy("{$this->table}.admin_menu_function_id");
        return $res->get();
    }
}