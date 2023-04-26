<?php
/**
 * Created by PhpStorm.
 * User: nhu
 * Date: 20/03/2018
 * Time: 10:15
 */

namespace Modules\Admin\Models;
use MyCore\Models\Traits\ListTableTrait;
use Illuminate\Database\Eloquent\Model;
class OrderPaymentTypeTable extends Model
{
    use ListTableTrait;

    /*
     * table service_package
     */
    protected $table = 'order_payment_type' ;
    protected $primaryKey = 'order_payment_type_id';

    /*
     * fill table
     * $var array
     */
    protected $fillable = ['order_payment_type_id', 'order_payment_type_name', 'order_payment_type_description', 'is_active', 'is_delete', 'created_at', 'updated_at', 'created_by', 'updated_by'] ;

    /*
     * Build query table
     * @author doan thi huynh nhu
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function _getList()
    {
        return $this->select('order_payment_type_id', 'order_payment_type_name', 'order_payment_type_description', 'is_active', 'is_delete', 'created_at', 'updated_at', 'created_by', 'updated_by')->where('is_delete',0)->orderBy($this->primaryKey,'desc');
    }
    // function remove item
    public function remove($id)
    {
        return $this->where($this->primaryKey,$id )->update(['is_delete'=> 1]);
    }
    /*
     * function edit
     */
    public function edit(array $data ,$id){

        return $this->where($this->primaryKey,$id)->update($data) ;

    }
    /*
     * function save
     */

    /*
     * function add
     */
    public function add(array $data){

        $oOrderdeliveryType =  $this->create($data);
        return $oOrderdeliveryType->order_delivery_type_id ;
    }
    /*
     * function getItem
     */
    public function getItem($id){
        return $this->where($this->primaryKey,$id )->first();
    }
}