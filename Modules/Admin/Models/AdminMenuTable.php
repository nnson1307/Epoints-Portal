<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminMenuTable extends Model
{
    protected $table = 'admin_menu';
    protected $primaryKey = 'admin_menu_id';
    protected $fillable = [
        'admin_menu_id', 'admin_menu_name', 'admin_menu_category_id', 'admin_menu_route',
        'admin_menu_icon', 'admin_menu_img', 'admin_menu_position', 'created_at', 'updated_at'
    ];

    // Lấy danh sách menu theo menu category id
    public function getListMenuByMenuCatId($menuCatId, $listMenuExist, $search = null)
    {
        // $lang = app()->getLocale();
        $lang = 'vi';
        if(app()->getLocale() != null){
            $lang = app()->getLocale();
        }
        $ds = $this->select(
            'admin_menu.admin_menu_id',
            "admin_menu.admin_menu_name_$lang as admin_menu_name",
            "admin_menu.admin_menu_name_vi",
            "admin_menu.admin_menu_name_en",
            'admin_menu.admin_menu_category_id',
            'admin_menu.admin_menu_route',
            'admin_menu.admin_menu_icon'
        )
            ->join('pages', 'pages.route', '=', 'admin_menu.admin_menu_route')
            ->join('action_group as ag', 'ag.action_group_id', '=', 'pages.action_group_id')
            ->join('admin_service_brand_feature as asbf', function ($join) {
                $join->on('asbf.feature_group_id', '=', 'ag.action_group_id')
                    ->on('asbf.is_actived', '=', DB::raw(1));
            })
            ->join('admin_service_brand as asb', function ($join) {
                $join->on('asb.service_id', '=', 'asbf.service_id')
                    ->on('asb.is_actived', '=', DB::raw(1));
            })
            ->where('admin_menu_category_id', $menuCatId)
            ->where('ag.is_actived', 1)
            ->where('pages.is_actived', 1)
            ->whereNotIn('admin_menu.admin_menu_id', $listMenuExist)
            ->groupBy('admin_menu.admin_menu_route');

        //Tìm kiếm tên menu
        if(!empty($search)) {
            $ds->where("admin_menu.admin_menu_name_$lang", "LIKE", "%" . $search . "%");
        }

        return $ds->orderBy('admin_menu_position', 'asc')->get();
    }

    /**
     * Lấy danh sách tất cả menu
     *
     * @param array $filter
     * @return mixed
     */
    public function getAllMenu($filter = [])
    {
        $lang = app()->getLocale();

        $ds = $this
            ->select(
                'admin_menu.admin_menu_id',
                "admin_menu.admin_menu_name_$lang as admin_menu_name",
                "admin_menu_name_vi",
                "admin_menu_name_en",
                'admin_menu.admin_menu_category_id',
                'admin_menu.admin_menu_route',
                'admin_menu.admin_menu_icon'
            )
            ->join('pages', 'pages.route', '=', 'admin_menu.admin_menu_route')
            ->join('action_group as ag', 'ag.action_group_id', '=', 'pages.action_group_id')
            ->join('admin_service_brand_feature as asbf', function ($join) {
                $join->on('asbf.feature_group_id', '=', 'ag.action_group_id')
                    ->on('asbf.is_actived', '=', DB::raw(1));
            })
            ->join('admin_service_brand as asb', function ($join) {
                $join->on('asb.service_id', '=', 'asbf.service_id')
                    ->on('asb.is_actived', '=', DB::raw(1));
            })
            ->where('ag.is_actived', 1)
            ->where('pages.is_actived', 1)
            ->groupBy('admin_menu.admin_menu_route');

        //Tìm kiếm tên menu
        if(!empty($filter['search'])) {
            $$ds->where("admin_menu.admin_menu_name_$lang", "LIKE", "%" . $filter['search'] . "%");
        }

        return $ds->orderBy('admin_menu_name', 'asc')->get();
    }
}