<?php


namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;

class ReceiptTable extends Model
{
    protected $table = "receipts";
    protected $primaryKey = "receipt_id";
    protected $fillable = [
        "receipt_id",
        "receipt_code",
        "customer_id",
        "staff_id",
        "object_type",
        "object_id",
        "order_id",
        "total_money",
        "voucher_code",
        "status",
        "discount",
        "custom_discount",
        "is_discount",
        "amount",
        "amount_paid",
        "amount_return",
        "note",
        "updated_by",
        "created_at",
        "updated_at",
        "created_by",
        "discount_member",
        "receipt_source",
        "receipt_type_code",
        "object_accounting_type_code",
        "object_accounting_id",
        "object_accounting_name",
        "type_insert",
        "document_code",
        "is_deleted"
    ];

    /**
     * Thêm thanh toán
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->receipt_id;
    }

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
                "staff_id",
                "object_type",
                "order_id",
                "total_money",
                "voucher_code",
                "status",
                "discount",
                "custom_discount",
                "is_discount",
                "amount",
                "amount_paid",
                "amount_return",
                "discount_member",
                "note"
            )
            ->where("$this->primaryKey", $receiptId)
            ->first();
    }

    /**
     * Lấy thông tin thanh toán by order_id
     *
     * @param $orderId
     * @return mixed
     */
    public function getInfoByOrder($orderId)
    {
        return $this
            ->select(
                "receipt_id",
                "receipt_code",
                "customer_id",
                "staff_id",
                "object_type",
                "order_id",
                "total_money",
                "voucher_code",
                "status",
                "discount",
                "custom_discount",
                "is_discount",
                "amount",
                "amount_paid",
                "amount_return",
                "discount_member",
                "note"
            )
            ->where("order_id", $orderId)
            ->whereIn("status", ["paid", "part-paid"])
            ->get();
    }

    /**
     * Chỉnh sửa thanh toán
     *
     * @param array $data
     * @param $receiptId
     * @return mixed
     */
    public function edit(array $data, $receiptId)
    {
        return $this->where("receipt_id", $receiptId)->update($data);
    }
}