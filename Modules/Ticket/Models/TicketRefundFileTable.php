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

class TicketRefundFileTable extends Model
{
    use ListTableTrait;
    protected $table = 'ticket_refund_file';
    protected $primaryKey = 'ticket_refund_file_id';

    protected $fillable = ['ticket_refund_file_id','ticket_refund_map_id', 'ticket_id','path_file','type','created_at','updated_by', 'created_by', 'updated_at'];

    public function add(array $data)
    {
        $odata = $this->create($data);
        return $odata->ticket_refund_file_id;
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

    public function removeByRefundMapId($id)
    {
        return $this->where('ticket_refund_map_id', $id)->delete();
    }

    public function getFileRefundByTicketId($id,$refund_id = null)
    {
        if($refund_id == null){
            return [];
        }
        $query =  $this->select(
            "{$this->table}.path_file",
            "{$this->table}.type",
        )
        ->leftJoin("ticket_refund_map as p1","p1.ticket_refund_map_id","{$this->table}.ticket_refund_map_id")
        ->leftJoin("ticket_refund as p2","p2.ticket_refund_id","p1.ticket_refund_id")
        ->where("p2.ticket_refund_id",'=', $refund_id)
        ->where("{$this->table}.ticket_id", $id);
        // ->whereNotNull("{$this->table}.type")
        return $query->get()->toArray();
    }
}