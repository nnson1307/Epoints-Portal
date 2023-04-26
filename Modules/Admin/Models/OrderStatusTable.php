<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/20/2018
 * Time: 10:07 AM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class OrderStatusTable extends Model
{
    use ListTableTrait;
    protected $table='order_status';
    protected $primaryKey='order_status_id';

    //function filtable
    protected $fillable=[
      'order_status_id','order_status_name','order_status_description','created_at','updated_at','is_active','is_delete','created_by','updated_by'
    ];

    //function get list
    protected function _getList()
    {
        return $this->select(
            'order_status_id','order_status_name','order_status_description','created_at','updated_at','is_active','is_delete','created_by','updated_by'
        )->where('is_delete',0);
    }

    //function add
    public function add(array $data)
    {
        $oOderStatus=$this->create($data);
        return $oOderStatus->id;
    }

    //function remove
    public function remove($id)
    {
        $this->where($this->primaryKey,$id)->update(['is_delete'=>1]);
    }

    //function edit
    public function edit(array $data,$id)
    {
        return $this->where($this->primaryKey,$id)->update($data);
    }
    //function get edit lấy id
    public function getEdit($id)
    {
        return $this->where($this->primaryKey,$id)->first();
    }

    //function  export
    public function exportExecl(array $array)
    {
        $orderStatus = DB::table($this->table)->select($array)->get();
        return $orderStatus;

    }
}