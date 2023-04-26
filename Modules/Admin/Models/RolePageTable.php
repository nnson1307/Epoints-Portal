<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/9/2019
 * Time: 11:40 AM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class RolePageTable extends Model
{
    protected $table = 'role_pages';
    protected $primaryKey = 'role_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'staff_title_id', 'page_id', 'created_at', 'updated_at', 'is_actived','group_id'
    ];


    public function add(array $data)
    {
        $oTitle = $this->create($data);
        return $oTitle->role_id;
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function checkIssetRole($staffId, $pageId)
    {
        $select = $this->where('group_id', $staffId)->where('page_id', $pageId)->first();
        return $select;
    }

    public function checkRolePage($idUser,$pageId)
    {
        $select=$this->select('role_pages.role_id', 'role_pages.staff_title_id', 'role_pages.page_id', 'role_pages.is_actived')
            ->leftJoin('staff_title','staff_title.staff_title_id','=','role_pages.staff_title_id')
            ->leftJoin('staffs','staffs.staff_title_id','=','role_pages.staff_title_id')
            ->where('staffs.staff_id',$idUser)
            ->where('role_pages.page_id',$pageId)->first();

            return $select;
    }

    public function getRolePage($staffTileId){
        $select=$this->leftJoin('pages','pages.id','=',$this->table.'.page_id')
            ->where($this->table.'.staff_title_id',$staffTileId)
            ->where($this->table.'.is_actived',1)
            ->where('pages.is_actived',1)
            ->select('pages.route as route')->get();
        $data=[];
        if ($select!=null)
        {
            foreach ($select->toArray() as $item){
                $data[]= $item['route'];
            }
        }

        return $data;
    }
    public function getAllRolePage($staffTileId){
        $select=$this->leftJoin('pages','pages.id','=',$this->table.'.page_id')
            ->where($this->table.'.staff_title_id',$staffTileId)
            ->where('pages.is_actived',1)
            ->select('pages.route as route')->get();
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