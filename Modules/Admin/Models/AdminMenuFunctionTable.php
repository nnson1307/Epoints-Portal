<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class AdminMenuFunctionTable extends Model
{
    use ListTableTrait;
    protected $table = 'admin_menu_function';
    protected $primaryKey = 'admin_menu_function_id';
    protected $fillable = [
        'admin_menu_function_id', 'admin_menu_category_id', 'admin_menu_id', 'position', 'type',
        'is_actived', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    public function _getList()
    {
        $lang = app()->getLocale();

        $res = $this
            ->select(
                "{$this->table}.admin_menu_function_id",
                "{$this->table}.admin_menu_category_id",
                "{$this->table}.admin_menu_id",
                "{$this->table}.position",
                "{$this->table}.type",
                "{$this->table}.is_actived",
                "am.admin_menu_name_$lang as admin_menu_name",
                "amc.menu_category_name"
            )
        ->leftJoin("admin_menu_category as amc", "amc.menu_category_id", "=", "{$this->table}.admin_menu_category_id")
        ->leftJoin("admin_menu as am","am.admin_menu_id","=","{$this->table}.admin_menu_id")
//        ->where("{$this->table}.type", "horizontal")
        ->orderBy("{$this->table}.admin_menu_category_id")
        ->orderBy("{$this->table}.position");
        if (isset($filter['type'])) {
            $res->where("{$this->table}.type", $filter['type']);
        }
        // ->orderBy("amc.position")
        return $res;
    }

    /**
     * Thêm chức năng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Cập nhật chức năng
     *
     * @param array $data
     * @param $menuFunctionId
     * @return mixed
     */
    public function edit(array $data, $menuFunctionId)
    {
        return $this->where('admin_menu_function_id', $menuFunctionId)
            ->update($data);
    }

    public function remove($menuFunctionId)
    {
        return $this->where('admin_menu_function_id', $menuFunctionId)->delete();
    }

    /**
     * Danh sách menu theo menu category id
     *
     * @param $menuCatId
     * @param $type
     * @return mixed
     */
    public function getMenuFunctionByMenuCat($menuCatId, $type)
    {
        return $this->select('admin_menu_id')
            ->where('admin_menu_category_id', $menuCatId)
            ->where('type', $type)
            ->get();
    }

    /**
     * Danh sách menu đã active, theo loại menu ngang và theo menu category id
     *
     * @param $menuCatId
     * @return mixed
     */
    public function getMenuFunctionActiveByMenuCat($menuCatId)
    {
        return $this->select('admin_menu_id')
            ->where('admin_menu_category_id', $menuCatId)
            ->where('type', 'horizontal')
            ->where('is_actived', 1)
            ->get();
    }

    public function getMenuFunctionActive($type)
    {
        return $this->select('admin_menu_id')
            ->where('type', $type)
            ->where('is_actived', 1)
            ->get();
    }
}