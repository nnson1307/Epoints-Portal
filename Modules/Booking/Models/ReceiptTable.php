<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 5/6/2020
 * Time: 2:28 PM
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class ReceiptTable extends Model
{
    protected $table = "receipts";
    protected $primaryKey = "receipt_id";

    /**
     * Lấy thông tin thanh toán
     *
     * @param $receiptId
     * @return mixed
     */
    public function getInfo($receiptId)
    {
        return $this
            ->select(
                "receipt_id",
                "receipt_code",
                "customer_id",
                "object_type",
                "object_id",
                "order_id",
                "amount",
                "amount_paid",
                "amount_return"
            )
            ->where("receipt_id", $receiptId)
            ->first();
    }
}