<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/9/2019
 * Time: 11:43 AM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class RoleActionTable extends Model
{
    protected $table = 'role_actions';
    protected $primaryKey = 'role_action_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_action_id', 'staff_title_id', 'action_id', 'is_actived', 'created_at', 'updated_at','group_id'
    ];


    public function add(array $data)
    {
        $oTitle = $this->create($data);
        return $oTitle->role_action_id;
    }

    public function edit(array $data,$id){
        return $this->where($this->primaryKey,$id)->update($data);
    }

    public function checkIssetRole($staffId, $actionId)
    {
        $select = $this->where('group_id', $staffId)->where('action_id', $actionId)->first();
        return $select;
    }

    public function getRoleAction($staffTileId){
        $select=$this->leftJoin('actions','actions.id','=',$this->table.'.action_id')
            ->where($this->table.'.staff_title_id',$staffTileId)
            ->where($this->table.'.is_actived',1)
            ->where('actions.is_actived',1)
            ->select('actions.name as route')->get();
        $data=[];
        if ($select!=null)
        {
            foreach ($select->toArray() as $item){
                $data[]= $item['route'];
            }
        }

        return $data;
    }
    public function getAllRoleAction($staffTileId){
        $select=$this->leftJoin('actions','actions.id','=',$this->table.'.action_id')
            ->where($this->table.'.staff_title_id',$staffTileId)
            ->where('actions.is_actived',1)
            ->select('actions.name as route')->get();
        $data=[];
        if ($select!=null)
        {
            foreach ($select->toArray() as $item){
                $data[]= $item['route'];
            }
        }

        return $data;
    }
}
//