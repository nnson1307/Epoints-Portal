<?php

namespace Modules\Ticket\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TicketLocationTable extends Model
{
    protected $table = "ticket_location";
    protected $primaryKey = "ticket_location_id";

    const NOT_DELETED = 0;

    /**
     * Lấy vị trí của ticket
     *
     * @param $idTicket
     * @return mixed
     */
    public function getLocationByTicket($idTicket)
    {
        $imageDefault = asset('/static/backend/images/image-user.png');

        return $this
            ->select(
                "{$this->table}.ticket_location_id",
                "{$this->table}.ticket_id",
                "{$this->table}.lat",
                "{$this->table}.lng",
                "{$this->table}.description",
                "{$this->table}.created_at",
                "s.full_name as staff_name",
                DB::raw("(CASE
                    WHEN  s.staff_avatar = '' THEN '$imageDefault'
                    WHEN  s.staff_avatar IS NULL THEN '$imageDefault'
                    ELSE  s.staff_avatar 
                    END
                ) as staff_avatar"),
            )
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.staff_id")
            ->where("{$this->table}.ticket_id", $idTicket)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.ticket_id", "desc")
            ->get();
    }
}