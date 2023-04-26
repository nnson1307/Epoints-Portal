<?php

namespace Modules\Ticket\Models;

use Illuminate\Database\Eloquent\Model;

class TicketConfigTable extends Model
{
    protected $table = "ticket_config";
    protected $primaryKey = "ticket_config_id";

    /**
     * Lấy thông tin cấu hình ticket
     *
     * @param $configKey
     * @return mixed
     */
    public function getConfig($configKey)
    {
        return $this
            ->select(
                "ticket_config_id",
                "ticket_config_key",
                "ticket_config_value"
            )
            ->where("ticket_config_key", $configKey)
            ->first();
    }

    /**
     * Lấy tất cã cấu hình ticket
     *
     * @return mixed
     */
    public function getAllConfig()
    {
        return $this
            ->select(
                "ticket_config_id",
                "ticket_config_key",
                "ticket_config_value"
            )
            ->get();
    }
}