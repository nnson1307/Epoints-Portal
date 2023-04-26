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

class RoleQueueTable extends Model
{
    use ListTableTrait;
    protected $table = 'ticket_role_queue';
    protected $primaryKey = 'ticket_role_queue_id';

    protected $fillable = ['ticket_role_queue_id', 'name','key','created_by','created_at'];

    protected function _getList($filters = [])
    {
        $query = $this->select('ticket_role_queue_id', 'name','key', 'created_by','created_at')
            ->orderBy($this->primaryKey, 'desc');
        return $query;
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }
    
    public function getName(){
        $oSelect= self::select("ticket_role_queue_id","name")->get();
        return ($oSelect->pluck("name","ticket_role_queue_id")->toArray());
    }

    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->ticket_role_queue_id;
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


}