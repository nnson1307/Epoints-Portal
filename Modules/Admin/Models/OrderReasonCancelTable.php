<?php
/*
 * OrderReasonCacel
 * @author ledangsinh
 * @since March 20, 2018
*/

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class OrderReasonCancelTable extends Model
{
    use ListTableTrait;

    /*
     * table order_reason_cancel
     */
    protected $table = 'order_reason_cancel';
    protected $primaryKey = 'order_reason_cancel_id';


    protected $fillable = ['order_reason_cancel_id', 'order_reason_cancel_name', 'order_reason_cancel_description', 'created_at', 'updated_at', 'is_active','is_delete', 'created_by', 'updated_by'];

    /**
     * Build query table
     * @author ledangsinh
     * @return \Illuminate\Database\Eloquent\Builder
     */

    protected function _getList()
    {
        return $this->select(
            'order_reason_cancel_id', 'order_reason_cancel_name',
            'order_reason_cancel_description', 'created_at',
            'updated_at', 'is_active', 'created_by',
            'updated_by')
            ->where('is_delete',0);
    }

    /*
     * Function get item
     */
    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    /*
     * Function add order reason cancel
     */
    public function add(array $data)
    {
        $oOrderReasonCancel = $this->create($data);
        return $oOrderReasonCancel->order_reason_cancel_id;
    }

    /*
     * Function edit order reason cancel
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /*
     * Function remove order reason cancel
     */
    public function remove($id)
    {
        return $this->where($this->primaryKey,$id )->update(['is_delete'=> 1]);
    }
}
