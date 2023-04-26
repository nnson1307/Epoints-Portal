<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptDetailTable extends Model
{
    protected $table = 'receipt_details';
    protected $primaryKey = 'receipt_detail_id';
    protected $fillable = [
        'receipt_detail_id', 'receipt_id', 'cashier_id', 'receipt_type', 'amount', 'note', 'created_by', 'updated_by',
        'created_at', 'updated_at','card_code', 'payment_method_code'
    ];

    /**
    * @param array $data
    * @return mixed
    */
    public function add(array $data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Chỉnh sửa chi tiết phiếu thu, trường hợp này (receipt : receipt_detail) - (1 : 1)
     *
     * @param $data
     * @param $receiptId
     * @return mixed
     */
    public function editByReceiptId($data, $receiptId)
    {
        return $this->where("receipt_id", $receiptId)->update($data);
    }

    /**
     * Lấy chi tiết phiếu thu
     *
     * @param $receiptId
     * @return mixed
     */
    public function getInfoDetail($receiptId)
    {
        $lang = \Config::get('app.locale');

        return $this
            ->select(
                "{$this->table}.receipt_detail_id",
                "{$this->table}.receipt_id",
                "{$this->table}.cashier_id",
                "{$this->table}.receipt_type",
                "{$this->table}.amount",
                "{$this->table}.card_code",
                "{$this->table}.created_at",
                "payment_method.payment_method_code",
                "payment_method.payment_method_name_$lang as payment_method_name"
            )
            ->leftJoin("payment_method", "payment_method.payment_method_code", "=", "{$this->table}.payment_method_code")
            ->where("{$this->table}.receipt_id", $receiptId)
            ->get();

    }
}