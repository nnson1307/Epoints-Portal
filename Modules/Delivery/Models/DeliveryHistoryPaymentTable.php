<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/24/2020
 * Time: 4:57 PM
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;

class DeliveryHistoryPaymentTable extends Model
{
    protected $table = "delivery_history_payment";
    protected $primaryKey = "delivery_history_payment_id";
    protected $fillable = [
        "delivery_payment_id",
        "delivery_history_id",
        "total",
        "is_verify",
        "note",
        "created_source",
        "created_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy thông tin thanh toán phiếu giao hàng
     *
     * @param $deliveryHistoryId
     * @return mixed
     */
    public function getPaymentByHistory($deliveryHistoryId)
    {
        return $this
            ->select(
                "delivery_payment_id",
                "delivery_history_id",
                "total",
                "is_verify",
                "note"
            )
            ->where("delivery_history_id", $deliveryHistoryId)
            ->first();
    }

    /**
     * Chỉnh sửa thanh toán phiếu giao hàng
     *
     * @param array $data
     * @param $paymentId
     * @return mixed
     */
    public function edit(array $data, $paymentId)
    {
        return $this->where("delivery_payment_id", $paymentId)->update($data);
    }

}