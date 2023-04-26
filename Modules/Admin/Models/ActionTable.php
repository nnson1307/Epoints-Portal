<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/17/2018
 * Time: 1:26 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActionTable extends Model
{
    protected $table = 'actions';
    protected $primaryKey = 'id';

    const PORTAL = "portal";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'title', 'is_actived', 'created_at', 'updated_at'
    ];


    public function add(array $data)
    {
        $oTitle = $this->create($data);
        return $oTitle->id;
    }

    public function getList()
    {
        $select = $this->select('actions.id', 'actions.name', 'actions.title')
            ->join('action_group as ag', 'ag.action_group_id', '=', 'actions.action_group_id')
            ->join('admin_service_brand_feature as asbf', function ($join) {
                $join->on('asbf.feature_group_id', '=', 'ag.action_group_id')
                    ->on('asbf.is_actived', '=', DB::raw(1));
            })
            ->join('admin_service_brand as asb', function ($join) {
                $join->on('asb.service_id', '=', 'asbf.service_id')
                    ->on('asb.is_actived', '=', DB::raw(1));
            })
            ->where('ag.is_actived', 1)
            ->where('actions.is_actived', 1)->get()->toArray();
        return $select;
    }

//    public function getAllRoute()
//    {
//        $select=$this->select('name')->where('is_actived',1)->get();
//        $data=[];
//        if ($select!=null)
//        {
//            foreach ($select->toArray() as $item){
//                $data[]= $item['name'];
//            }
//        }
//        return $data;
//    }

    /**
     * Lấy tất cả quyền của action
     *
     * @param $arrFeature
     * @return array
     */
    public function getAllRoute($arrFeature = [])
    {
        $select=$this
            ->select(
                'actions.name'
            )
            ->join('action_group as ag', 'ag.action_group_id', '=', 'actions.action_group_id')
            ->where('ag.is_actived', 1)
            ->where('actions.is_actived',1)
            ->where("ag.platform", self::PORTAL)
            ->whereIn("{$this->table}.name", $arrFeature)
            ->get();
        $data=[];
        if ($select!=null)
        {
            foreach ($select->toArray() as $item){
                $data[]= $item['name'];
            }
        }
        return $data;
    }
}
//