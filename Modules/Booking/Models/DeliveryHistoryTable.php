<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/11/2020
 * Time: 4:24 PM
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class DeliveryHistoryTable extends Model
{
    protected $table = "delivery_history";
    protected $primaryKey = "delivery_history_id";
    protected $fillable = [
        "delivery_history_id",
        "delivery_id",
        "delivery_history_code",
        "transport_id",
        "transport_code",
        "delivery_staff",
        "delivery_start",
        "delivery_end",
        "contact_phone",
        "contact_name",
        "contact_address",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "amount",
        "verified_payment",
        "verified_by",
        "status",
        "note",
        "time_ship",
        "pick_up",
        "image_pick_up",
        "image_drop",
        "time_pick_up"
    ];

    /**
     * Chỉnh sửa phiếu giao hàng
     *
     * @param array $data
     * @param $deliveryHistoryId
     * @return mixed
     */
    public function edit(array $data, $deliveryHistoryId)
    {
        return $this->where("$this->primaryKey", $deliveryHistoryId)->update($data);
    }
}