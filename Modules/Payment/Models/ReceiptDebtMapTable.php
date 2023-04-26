<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptDebtMapTable extends Model
{
    protected $table = "receipt_debt_maps";
    protected $primaryKey = "receipt_debt_map_id";
    protected $fillable = [
        "receipt_debt_map_id",
        "receipt_id",
        "customer_debt_id",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "amount_paid"
    ];

    /**
     * Lay cong no duoc thanh toan trong dot thu nay
     *
     * @param $receiptId
     * @return mixed
     */
    public function getDebtMapByReceipt($receiptId)
    {
        return $this
            ->select(
                "{$this->table}.customer_debt_id",
                "d.status",
                "d.amount",
                "d.amount_paid",
                "{$this->table}.amount_paid as amount_paid_turn"
            )
            ->join("customer_debt as d", "d.customer_debt_id", "=", "{$this->table}.customer_debt_id")
            ->where("{$this->table}.receipt_id", $receiptId)
            ->get();
    }
}