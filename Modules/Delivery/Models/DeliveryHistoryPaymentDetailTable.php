<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/24/2020
 * Time: 5:04 PM
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class DeliveryHistoryPaymentDetailTable extends Model
{
    protected $table = "delivery_history_payment_detail";
    protected $primaryKey = "delivery_history_payment_detail_id";
    protected $fillable = [
        "delivery_history_payment_detail_id",
        "delivery_history_payment_id",
        "payment_type",
        "amount",
        "payment_transaction_code",
        "created_at",
        "updated_at"
    ];

    /**
     * Chi tiết thanh toán phiếu giao hàng
     *
     * @param $paymentId
     * @return mixed
     */
    public function getPaymentDetail($paymentId)
    {
        $lang = Config::get('app.locale');

        return $this
            ->select(
                "{$this->table}.delivery_history_payment_detail_id",
                "{$this->table}.delivery_history_payment_id",
                "{$this->table}.payment_type",
                "{$this->table}.amount",
                "{$this->table}.created_at",
                "{$this->table}.payment_transaction_code",
                "payment_method.payment_method_name_{$lang} as payment_method_name"
            )
            ->leftJoin("payment_method", "payment_method.payment_method_code", "=", "{$this->table}.payment_type")
            ->where("{$this->table}.delivery_history_payment_id", $paymentId)
            ->get();
    }

    /**
     * Cập nhật chi tiết thanh toán phiếu giao hàng
     *
     * @param array $data
     * @param $paymentDetailId
     * @return mixed
     */
    public function edit(array $data, $paymentDetailId)
    {
        return $this->where("delivery_history_payment_detail_id", $paymentDetailId)->update($data);
    }
}