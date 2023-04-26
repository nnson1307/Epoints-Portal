<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/27/2018
 * Time: 2:11 PM
 */

namespace Modules\Admin\Models;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class RoomTable extends Model
{
    use ListTableTrait;
    protected $table="rooms";
    protected $primaryKey="room_id";
    protected $fillable=[
      'room_id','name','seat','seat_using','created_by','updated_by',
        'created_at','updated_at','is_actived','is_deleted','slug'
    ];

    /**
     * @return mixed
     */
    protected function _getList()
    {
        $ds=$this->select('room_id','name','seat','seat_using','created_by','updated_by',
            'created_at','updated_at','is_actived','is_deleted')->where('is_deleted',0);
        return $ds;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        $ds=$this->select('room_id','name','seat','seat_using','created_by','updated_by',
            'created_at','updated_at','is_actived','is_deleted')->where($this->primaryKey,$id)->first();
        return $ds;
    }


    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey,$id)->update($data);
    }

    /**
     * @param $id
     */
    public function remove($id)
    {
        $this->where($this->primaryKey,$id)->update(['is_deleted'=>1]);
    }

    /**
     * @param $name
     * @param $id
     * @return mixed
     */
    public function testName($name, $id)
    {
        return $this->where('slug',$name)->where('room_id','<>',$id)->where('is_deleted',0)->first();
    }

    /**
     * @return mixed
     */
    public function getRoomOption()
    {
        return $this->select('room_id','name','seat')->where('is_deleted',0)->get()->toArray();
    }
}