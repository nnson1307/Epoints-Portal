<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 27/03/2018
 * Time: 12:41 CH
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class StaffAccountTable extends  Model
{
    use ListTableTrait;

    protected $table        = 'staff_account' ;
    protected $primaryKey   = 'staff_account_id';
    protected $fillable = ['staff_account_id', 'staff_id', 'username', 'password', 'store_id', 'is_admin', 'is_active', 'date_last_login', 'created_at', 'updated_at', 'created_by', 'updated_by'] ;
    public function saveAccount(array $data, $id){
        if($id){
            return $this->edit($data, $id);
        }else{

            return $this->add($data);
        }
    }
    // note function này dùng chung chỉ có tác dụng add
    public function add(array  $data){
        $object = $this->create($data);
        return  $object->staff_account_id;
    }
    public function edit(array $data ,$id){
        return $this->where($this->primaryKey,$id)->update($data);

    }
}