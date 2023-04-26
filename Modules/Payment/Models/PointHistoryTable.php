<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class PointHistoryTable extends Model
{
    protected $table = "point_history";
    protected $primaryKey = "point_history_id";
    protected $fillable = [
        "point_history_id",
        "customer_id",
        "order_id",
        "point",
        "type",
        "point_description",
        "object_id",
        "is_deleted",
        "accepted_ranking",
        "created_at",
        "updated_at",
        "created_by",
        "source"
    ];

    /**
     * Thêm lịch sử tích điểm của KH
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->point_history_id;
    }

    /**
     * Lấy điểm đã cộng từ phiếu thu
     *
     * @param $orderId
     * @param $receiptId
     * @return mixed
     */
    public function getPlusPointByReceipt($orderId, $receiptId)
    {
        return $this
            ->where("order_id", $orderId)
            ->where("object_id", $receiptId)
            ->first();
    }
}