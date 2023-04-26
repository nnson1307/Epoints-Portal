<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-26
 * Time: 7:29 PM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;

class TransportTable extends Model
{
    protected $table = "transports";
    protected $primaryKey = "transport_id";

    const NOT_DELETED = 0;
    CONST IN_ACTIVE = 1;

    /**
     * Lấy option đơn vị vận chuyển
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "transport_id",
                "transport_name",
                "contact_phone"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}