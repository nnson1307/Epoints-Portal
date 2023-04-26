<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/27/2018
 * Time: 10:03 AM
 */

namespace Modules\Admin\Models;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class TransportTable extends Model
{
    use ListTableTrait;
    protected $table="transports";
    protected $primaryKey="transport_id";
    protected $fillable=[
      'transport_id','transport_name','charge','created_at','updated_at','is_deleted','description',
        'address','contact_name','contact_phone','contact_title','slug','created_by','updated_by','is_system','transport_code','token'
    ];
    protected function _getList()
    {
        $ds=$this->select('transport_id','transport_name','charge','created_at','updated_at','is_deleted','description',
            'address','contact_name','contact_phone','contact_title','is_system','transport_code','token')->where('is_deleted',0);
        return $ds;
    }

//    function add
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }
    //function get item
    public function getItem($id)
    {
        $ds=$this->select('transport_id','transport_name','charge','created_at','updated_at','is_deleted',
            'address','contact_name','contact_phone','contact_title','description','is_system','transport_code','token')->where($this->primaryKey,$id)->first();
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
//    public function getUnitOption()
//    {
//        return $this->select('unit_id','name','is_standard','is_actived')->where('is_deleted',0)->get()->toArray();
//    }
    public function testName($name,$id)
    {
        return $this->where('slug',$name)->where('transport_id','<>',$id)->where('is_deleted',0)->first();
    }
}