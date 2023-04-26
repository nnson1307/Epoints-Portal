<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 20/09/2022
 * Time: 14:40
 */

namespace Modules\ManagerProject\Models;


use Illuminate\Database\Eloquent\Model;

class TicketTable extends Model
{
    protected $table = "ticket";
    protected $primaryKey = "ticket_id";

    /**
     * Lấy thông tin ticket
     *
     * @param $ticketId
     * @return mixed
     */
    public function getInfo($ticketId)
    {
        return $this
            ->select(
                "{$this->table}.ticket_id",
                "{$this->table}.ticket_code",
                "{$this->table}.title"
            )
            ->where("{$this->table}.ticket_id", $ticketId)
            ->first();
    }
}