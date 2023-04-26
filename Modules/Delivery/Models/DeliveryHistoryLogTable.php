<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/22/2020
 * Time: 2:00 PM
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;

class DeliveryHistoryLogTable extends Model
{
    protected $table = "delivery_history_log";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "delivery_history_id",
        "status",
        "created_by",
        "created_at",
        "updated_at",
        "created_type"
    ];

    /**
     * Thêm log giao hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Lấy thông tin log giao hàng
     *
     * @param $deliveryHistoryId
     * @return mixed
     */
    public function getLog($deliveryHistoryId)
    {
        return $this
            ->select(
                "id",
                "status",
                "created_at"
            )
            ->orderBy("created_at", "asc")
            ->where("delivery_history_id", $deliveryHistoryId)
            ->get();
    }

    /**
     * Lấy thông tin của log bằng status
     *
     * @param $deliveryHistoryId
     * @param $status
     * @return mixed
     */
    public function getLogByStatus($deliveryHistoryId, $status)
    {
        return $this
            ->select(
                "id",
                "status",
                "created_at"
            )
            ->where("delivery_history_id", $deliveryHistoryId)
            ->where("status", $status)
            ->first();
    }
}