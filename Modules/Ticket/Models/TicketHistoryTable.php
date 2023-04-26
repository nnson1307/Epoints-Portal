<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/04/2021
 * Time: 15:42
 */

namespace Modules\Ticket\Models;


use Illuminate\Database\Eloquent\Model;
// use MyCore\Models\Traits\ListTableTrait;

class TicketHistoryTable extends Model
{
    // use ListTableTrait;
    protected $table = "ticket_history";
    protected $primaryKey = "ticket_process_history_id";
    protected $fillable = ['ticket_process_history_id', 'ticket_id','note_vi','note_en','created_at', 'updated_at'];

    public function getTicketQueueMapTable($ticketId)
    {
        return $this
            ->where("ticket_id", $ticketId)
            ->get();
    }

    public function add(array $data)
    {
        $oData = $this->create($data);
        return $oData->ticket_process_history_id;
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

    /**
     * Xoá tất cả file của ticket
     *
     * @param $ticketId
     * @return mixed
     */
    public function removeHistory($ticketId)
    {
        return $this->where("ticket_id", $ticketId)->delete();
    }
}