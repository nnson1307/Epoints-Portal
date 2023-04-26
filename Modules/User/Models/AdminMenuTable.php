<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/4/2019
 * Time: 16:18
 */

namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class AdminMenuTable extends Model
{
    use ListTableTrait;
    protected $table = 'admin_menu';
    protected $primaryKey = 'admin_menu_id';
    protected $fillable = [
        'admin_menu_id', 'admin_menu_name', 'admin_menu_category_id', 'admin_menu_route',
        'admin_menu_icon', 'admin_menu_img', 'admin_menu_position', 'created_at', 'updated_at'
    ];

    public function groupCategory($menu_category_id)
    {
        $lang = app()->getLocale();

        $ds = $this->select(
            'admin_menu_id',
            "admin_menu_name_$lang as admin_menu_name",
            'admin_menu_category_id',
            'admin_menu_route', 'admin_menu_icon', 'admin_menu_img')
            //  ->join('admin_feature as af','af.feature_code', '=', 'admin_menu.admin_menu_route')
            //  ->join('admin_feature_group as afg', 'afg.feature_group_id', '=', 'af.feature_group_id')
            //  ->join('admin_service_brand_feature as asbf', function ($join) {
            //      $join->on('asbf.feature_group_id', '=', 'afg.feature_group_id')
            //          ->on('asbf.is_actived', '=', DB::raw(1));
            //  })
            //  ->join('admin_service_brand as asb', function ($join) {
            //      $join->on('asb.service_id', '=', 'asbf.service_id')
            //          ->on('asb.is_actived', '=', DB::raw(1));
            //  })
            //  ->where('af.is_actived', 1)
            //  ->where('afg.is_actived', 1)
            ->where('admin_menu_category_id', $menu_category_id)
            ->orderBy('admin_menu_position','asc')
            ->get();
        return $ds;
    }
}