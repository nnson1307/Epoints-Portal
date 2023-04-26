<?php


namespace Modules\Delivery\Models;


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
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'payment_method_code'
    ];

    /**
     * Thêm chi tiết thanh toán
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Chỉnh sửa tất cả chi tiết thanh toán
     *
     * @param array $data
     * @param $receiptId
     * @return mixed
     */
    public function editAll(array $data, $receiptId)
    {
        return $this->where("receipt_id", $receiptId)->update($data);
    }
}