<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 15/10/2021
 * Time: 15:24
 */

namespace Modules\FNB\Models;


use Illuminate\Database\Eloquent\Model;

class ContractReceiptDetailTable extends Model
{
    protected $table = "contract_receipt_details";
    protected $primaryKey = "contract_receipt_detail_id";
    protected $fillable = [
        "contract_receipt_detail_id",
        "contract_receipt_id",
        "amount_receipt",
        "payment_method_id",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy chi tiết thanh toán của đợt thu
     *
     * @param $contractReceiptId
     * @return mixed
     */
    public function getDetail($contractReceiptId)
    {
        $lang = app()->getLocale();

        return $this
            ->select(
                "{$this->table}.contract_receipt_detail_id",
                "{$this->table}.contract_receipt_id",
                "{$this->table}.amount_receipt",
                "{$this->table}.payment_method_id",
                "pm.payment_method_name_$lang as payment_method_name"
            )
            ->join("payment_method as pm", "pm.payment_method_id", "=", "{$this->table}.payment_method_id")
            ->where("{$this->table}.contract_receipt_id", $contractReceiptId)
            ->get();
    }
}