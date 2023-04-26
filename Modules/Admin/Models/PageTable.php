<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/8/2019
 * Time: 1:58 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PageTable extends Model
{
    protected $table = 'pages';
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'name', 'route', 'is_actived', 'created_at', 'updated_at'];

    const PORTAL = "portal";

    public function add(array $data)
    {
        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->id;
    }

    public function getList()
    {
        $select = $this
            ->select('pages.id', 'pages.name', 'pages.route')
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
            ->where('pages.is_actived', 1)->get()->toArray();
        return $select;
    }

    /**
     * Lấy tất cả quyền của page
     *
     * @param $arrFeature
     * @return array
     */
    public function getAllRoute($arrFeature = [])
    {
        $select = $this
            ->select(
                'pages.route'
            )
            ->join('action_group as ag', 'ag.action_group_id', '=', 'pages.action_group_id')
            ->where('pages.is_actived', 1)
            ->where('ag.is_actived', 1)
            ->where("ag.platform", self::PORTAL)
            ->whereIn("{$this->table}.route", $arrFeature)
            ->get();
        $data = [];
        if ($select != null) {
            foreach ($select->toArray() as $item) {
                $data[] = $item['route'];
            }
        }
        return $data;
    }
}
////