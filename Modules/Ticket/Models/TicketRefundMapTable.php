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

class TicketRefundMapTable extends Model
{
    use ListTableTrait;
    protected $table = 'ticket_refund_map';
    protected $primaryKey = 'ticket_refund_map_id';

    protected $fillable = ['ticket_refund_map_id', 'ticket_refund_id','ticket_id','created_at','updated_by', 'created_by', 'updated_at'];

    public function add(array $data)
    {
        $odata = $this->create($data);
        return $odata->ticket_refund_map_id;
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

    public function removeByRefundId($id)
    {
        return $this->where('ticket_refund_id', $id)->delete();
    }
    public function getRefundMapByTicketRefundId($id)
    {
        return $this->where('ticket_refund_id', $id)->get()->pluck('ticket_refund_map_id')->toArray();
    }

}