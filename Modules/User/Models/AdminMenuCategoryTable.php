<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/4/2019
 * Time: 16:19
 */

namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class AdminMenuCategoryTable extends Model
{
    use ListTableTrait;
    protected $table = 'admin_menu_category';
    protected $primaryKey = 'menu_category_id';
    protected $fillable = [
        'menu_category_id', 'menu_category_name', 'menu_category_icon', 'created_at', 'updated_at'
    ];

    CONST LIMIT = 7;

    public function getAll()
    {
        $ds = $this->select('menu_category_id', 'menu_category_name', 'menu_category_icon')->get();
        return $ds;
    }

    /**
     * lấy danh sách nhóm menu
     *
     * @return mixed
     */
    public function getListGroupMenu()
    {
        $res = $this->select('menu_category_id', 'menu_category_name', 'menu_category_icon')
            ->where('is_show', 1);
        return $res
//            ->limit(self::LIMIT)
            ->get();
    }
}