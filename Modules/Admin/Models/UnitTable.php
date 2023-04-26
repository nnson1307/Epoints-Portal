<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/25/2018
 * Time: 4:04 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;
class UnitTable extends Model
{
    use ListTableTrait;
    protected $table="units";
    protected $primaryKey="unit_id";

    protected $fillable=[
      'unit_id','name','is_standard','is_actived','is_deleted',
      'updated_by','created_by','created_at','updated_at','slug'
    ];

    //function lấy danh sách
    protected function _getList()
    {
        $ds=$this->select('unit_id','name','is_standard','is_actived','is_deleted',
            'updated_by','created_by','created_at','updated_at')->where('is_deleted',0);
        return $ds;
    }

//    function add
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->unit_id;
    }
    //function get item
    public function getItem($id)
    {
        $ds=$this->select('unit_id','name','is_standard','is_actived','is_deleted',
            'updated_by','created_by','created_at','updated_at')->where($this->primaryKey,$id)->first();
        return $ds;
    }
    //test name
    public function test($name)
    {
        $ds=$this->select('unit_id','name','is_standard','is_actived','is_deleted',
            'updated_by','created_by','created_at','updated_at')->where('name',$name)->first();
        return $ds;
    }
    //function edit
    public function edit(array $data,$id)
    {
        return $this->where($this->primaryKey,$id)->update($data);
    }
    //function remove
    public function remove($id)
    {
        $this->where($this->primaryKey,$id)->update(['is_deleted'=>1]);
    }
    //function lay gia tri
    public function getUnitOption()
    {
        return $this->select('unit_id','name','is_standard','is_actived')
            ->where('is_deleted',0)
            ->where('is_actived',1)
            ->get()->toArray();
    }
    //function test name
    public function testName($name,$id)
    {
        return $this->where('slug',$name)->where('unit_id','<>',$id)->where('is_deleted',0)->first();
    }
    //funtion get all
    public function getAll(){
        return $this->select('unit_id', 'name')
            ->where('is_actived',1)
            ->where('is_deleted',0)
            ->get();
    }
    /*
     * get option edit product
     */
    public function getOptionEditProduct($id){
        return $this->where($this->primaryKey,'<>',$id)
            ->where('is_actived',1)
            ->where('is_deleted',0)
            ->get();
    }
    /*
     * get where not in
     */
    public function getUnitWhereNotIn($id){
        return $this->where('unit_id','<>',$id)
            ->where('is_deleted',0)
            ->where('is_actived',1)
            ->get();
    }
}