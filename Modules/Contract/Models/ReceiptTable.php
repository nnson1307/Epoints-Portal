<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/09/2021
 * Time: 10:48
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReceiptTable extends Model
{
    protected $table = "receipts";
    protected $primaryKey = "receipt_id";
    protected $fillable = [
        "receipt_id",
        "receipt_code",
        "customer_id",
        "staff_id",
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
        "object_id",
        "object_type",
        "receipt_type_code",
        "object_accounting_type_code",
        "object_accounting_id",
        "object_accounting_name",
        "type_insert",
        "document_code",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm thanh toán
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Chỉnh sửa thanh toán
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function edit($data, $id)
    {
        return $this->where('receipt_id', $id)->update($data);
    }

    /**
     * Chỉnh sửa thanh toán bằng mã
     *
     * @param $data
     * @param $receiptCode
     * @return mixed
     */
    public function editByCode($data, $receiptCode)
    {
        return $this->where('receipt_code', $receiptCode)->update($data);
    }

    /**
     * Chỉnh sửa thanh toán bằng đơn hàng
     *
     * @param $data
     * @param $orderId
     * @return mixed
     */
    public function editByOrder($data, $orderId)
    {
        return $this->where('order_id', $orderId)->update($data);
    }

    /**
     * Lấy tất cả tiền đã thanh toán của đơn hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function getReceiptOrder($orderId)
    {
        return $this
            ->select(
                "order_id",
                DB::raw("SUM(amount_paid) as amount_paid"),
                "note"
            )
            ->whereNotIn("status", ["cancel", "fail"])
            ->where("order_id", $orderId)
            ->groupBy("order_id")
            ->first();
    }

    /**
     * Lấy tất cã những lần thanh toán của đơn hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function getTotalReceipt($orderId)
    {
        return $this
            ->select(
                "{$this->table}.receipt_id",
                "{$this->table}.receipt_code",
                "{$this->table}.customer_id",
                "{$this->table}.staff_id",
                "{$this->table}.order_id",
                "{$this->table}.total_money",
                "{$this->table}.voucher_code",
                "{$this->table}.status",
                "{$this->table}.discount",
                "{$this->table}.custom_discount",
                "{$this->table}.is_discount",
                "{$this->table}.amount",
                "{$this->table}.amount_paid",
                "{$this->table}.amount_return",
                "{$this->table}.note",
                "{$this->table}.object_id",
                "{$this->table}.object_type",
                "{$this->table}.receipt_type_code",
                "{$this->table}.object_accounting_type_code",
                "{$this->table}.object_accounting_id",
                "{$this->table}.object_accounting_name",
                "{$this->table}.created_by",
                "orders.order_code"
            )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.order_id", $orderId)
            ->whereNotIn("{$this->table}.status", ["cancel", "fail"])
            ->get();
    }
}