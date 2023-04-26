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

class AlertTable extends Model
{
    use ListTableTrait;
    protected $table = 'ticket_alert';
    protected $primaryKey = 'ticket_alert_id';

    protected $fillable = ['ticket_alert_id', 'time','ticket_role_queue_id', 'template', 'params',
        'is_noti', 'is_email', 'created_at','created_by','updated_at','updated_by'];

    protected function _getList($filters = [])
    {
        return $this->get();
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'asc')->get();
    }
    public function getName(){
        $oSelect= self::select("ticket_alert_id","queue_name")->get();
        return ($oSelect->pluck("queue_name","ticket_alert_id")->toArray());
    }
    public function testCode($code, $id)
    {
        return $this->where('queue_name', $code)->where('ticket_alert_id', '<>', $id)->first();
    }

    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->ticket_alert_id;
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