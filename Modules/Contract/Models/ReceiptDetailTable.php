<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/09/2021
 * Time: 10:48
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ReceiptDetailTable extends Model
{
    protected $table = 'receipt_details';
    protected $primaryKey = 'receipt_detail_id';
    protected $fillable = [
        'receipt_detail_id',
        'receipt_id',
        'cashier_id',
        'receipt_type',
        'amount',
        'note',
        'card_code',
        'payment_method_code',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
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
     * Lấy chi tiết thanh toán
     *
     * @param $receiptId
     * @return mixed
     */
    public function getReceiptDetail($receiptId)
    {
        return $this
            ->select(
                "{$this->table}.receipt_detail_id",
                "{$this->table}.receipt_id",
                "{$this->table}.cashier_id",
                "{$this->table}.receipt_type",
                "{$this->table}.amount",
                "{$this->table}.note",
                "{$this->table}.card_code",
                "{$this->table}.payment_method_code",
                "pm.payment_method_id"
            )
            ->join("payment_method as pm", "pm.payment_method_code", "=", "{$this->table}.payment_method_code")
            ->where("receipt_id", $receiptId)
            ->get();
    }

}