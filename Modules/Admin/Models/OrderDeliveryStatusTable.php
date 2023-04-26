<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/03/2018
 * Time: 2:45 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

/**
 * User Model
 *
 * @author isc-daidp
 * @since Feb 23, 2018
 */
class OrderDeliveryStatusTable extends Model
{

    use ListTableTrait;
    protected $table = 'order_delivery_status';
    protected $primaryKey="order_delivery_status_id";



    protected $fillable = [
        'order_delivery_status_id', 'order_delivery_status_name', 'order_delivery_status_description', 'is_active', 'is_delete', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    protected function _getList()
    {
//        return $this->select('id', 'name', 'email', 'is_active', 'created_at', 'updated_at');

        $oSelect  = $this->select('order_delivery_status_id','order_delivery_status_name', 'order_delivery_status_description','created_at','is_active')->where('is_delete','=',0);
        return $oSelect;
    }


    /**
     * Remove user
     *
     * @param number $id
     */
    public function remove($id)
    {
        return $this->where($this->primaryKey,$id )->update(['is_delete'=> 1]);   // $this-> tuc la select cai bang productGroupTable hien tai
    }


    /**
     * Insert user to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $oUser = $this->create($data);

        return $oUser->order_delivery_status_id;
    }

    public function edit(array $data,$id)
    {
        return $this->where($this->primaryKey,$id)->update($data);

    }

    public function getItem($id){
        return  $this->where($this->primaryKey,$id)->first();
    }
}