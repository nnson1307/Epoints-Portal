<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class AdminMenuCategoryTable extends Model
{
    protected $table = 'admin_menu_category';
    protected $primaryKey = 'menu_category_id';
    protected $fillable = [
        'menu_category_id', 'menu_category_name', 'menu_category_icon', 'created_at', 'updated_at'
    ];

    public function getAll()
    {
        $ds = $this->select('menu_category_id', 'menu_category_name', 'menu_category_icon')->get();
        return $ds;
    }

    public function edit(array $data, $menuCatId)
    {
        return $this->where('menu_category_id', $menuCatId)
            ->update($data);
    }
}