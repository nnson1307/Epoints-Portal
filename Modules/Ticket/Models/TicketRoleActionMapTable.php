<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\Ticket\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class TicketRoleActionMapTable extends Model
{
    use ListTableTrait;
    protected $table = 'ticket_role_action_map';
    protected $primaryKey = 'ticket_role_action_map_id';

    protected $fillable = ['ticket_role_action_map_id', 'ticket_role_id','ticket_action_value','allow','created_by',
        'updated_by', 'created_at', 'updated_at'];

    protected function _getList($filters = [])
    {
        $query = $this->select('ticket_role_action_map_id', 'ticket_role_id','ticket_action_value','allow','created_by',
            'updated_by', 'created_at', 'updated_at')
            ->orderBy($this->primaryKey, 'ASC');
        return $query;
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }
    
    public function add(array $data)
    {
        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->ticket_role_action_map_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function edit(array $data, $id)
    {

        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    /*
    * check exist
    */
    public function checkExiststatus_name($status_name = '', $id = '')
    {
        $select = $this->where('status_name', $status_name)
        ->where('ticket_role_action_map_id','<>', $id)
        ->first();
        return $select;
    }

    //Kiểm tra ca đã tồn tại(is_deleted=0) by id, start_time , end_time.
    public function testEdit($id,$startTime, $endTime)
    {
        $select = $this->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->where('ticket_role_action_map_id','<>', $id)
            ->where('is_deleted', 0)->first();
        return $select;
    }
    public function removeByRole($roleId)
    {
        return $this->where('ticket_role_id', $roleId)->delete();
    }
}