<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 12/5/2018
 * Time: 2:37 PM
 */

namespace Modules\FNB\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use MyCore\Models\Traits\ListTableTrait;
use Illuminate\Support\Facades\DB;

class ReceiptDetailTable extends Model
{
    use ListTableTrait;

    protected $table = 'receipt_details';
    protected $primaryKey = 'receipt_detail_id';
    protected $fillable = [
        'receipt_detail_id', 'receipt_id', 'cashier_id', 'receipt_type', 'amount', 'note', 'created_by', 'updated_by',
        'created_at', 'updated_at', 'card_code', 'payment_method_code'
    ];

    const NOT_DELETED = 0;

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->receipt_detail_id;
    }

    public function getItem($id)
    {
        $lang = Config::get('app.locale');

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
            ->where("{$this->table}.receipt_id", $id)
            ->get();
    }

    public function edit(array $data, $id)
    {
        return $this->where('receipt_detail_id', $id)->update($data);
    }

    public function sumAmmount($id)
    {
        $ds = $this
            ->select(
                "{$this->table}.receipt_detail_id",
                "{$this->table}.receipt_id",
                DB::raw("SUM(receipt_details.amount) as number")
            )
            ->join("receipts", "receipt_details.receipt_id", "=", "receipts.receipt_id")
            ->where("receipts.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.receipt_id", $id)
            ->groupBy("{$this->table}.receipt_id")
            ->get();
        return $ds;
    }

    /**
     * Lấy ds tiền đã thanh toán
     *
     * @return mixed
     */
    public function getAllReceiptDetail()
    {
        return $this
            ->select(
                "{$this->table}.receipt_id",
                DB::raw("SUM(receipt_details.amount) as number")
            )
            ->join("receipts", "receipt_details.receipt_id", "=", "receipts.receipt_id")
            ->where("receipts.is_deleted", self::NOT_DELETED)
            ->groupBy("{$this->table}.receipt_id")
            ->get();
    }

    public function getSumMoneyByReceiptType()
    {
        $res = $this
            ->select(
                'receipt_details.receipt_id',
                'receipt_details.receipt_type',
                'receipt_details.receipt_detail_id',
                'receipts.order_id',
                'receipt_details.amount',
                DB::raw("SUM(receipt_details.amount) as sum_type")
            )
            ->join("receipts", "receipt_details.receipt_id", "=", "receipts.receipt_id")
            ->where("receipts.is_deleted", self::NOT_DELETED)
            ->groupBy('receipt_details.receipt_type')
            ->get();
        return $res;
    }

    public function getSumMoneyByReceiptTypeOptimize()
    {
        $res = $this
            ->select(
                'receipt_details.receipt_id',
                'receipt_details.receipt_type',
                'receipt_details.receipt_detail_id',
                'receipt_details.amount',
                DB::raw("SUM(receipt_details.amount) as sum_type")
            )
            ->join("receipts", "receipt_details.receipt_id", "=", "receipts.receipt_id")
            ->groupBy('receipt_details.receipt_type')
            ->where("receipts.is_deleted", self::NOT_DELETED)
            ->get();
        return $res;
    }

    public function getSumMoneyByReceiptTypeFilter($startTime, $endTime, $branchId)
    {
        $res = $this
            ->select(
                'receipt_details.receipt_id',
                'receipt_details.receipt_type',
                'receipt_details.receipt_detail_id',
                'receipts.order_id',
                'receipt_details.amount',
                DB::raw("SUM(receipt_details.amount) as sum_type")
            )
            ->join("receipts", "receipt_details.receipt_id", "=", "receipts.receipt_id")
            ->leftJoin("orders", "orders.order_id", "=", "receipts.order_id")
            ->where("receipts.is_deleted", self::NOT_DELETED)
            ->groupBy('receipt_details.receipt_type');

        if ($startTime != null && $endTime != null) {
            $res->whereBetween("receipt_details.created_at", [$startTime, $endTime]);
        }
        if ($branchId != null) {
            $res->where("orders.branch_id", $branchId);
        }
        return $res->get();
    }

    /**
     * Lấy tất cả chi tiết thanh toán theo mã đơn hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function getListDetailByOrderId($orderId)
    {
        $lang = Config::get('app.locale');

        $res = $this
            ->select
            (
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
            ->join("receipts", "receipt_details.receipt_id", "=", "receipts.receipt_id")
            ->leftJoin("payment_method", "payment_method.payment_method_code", "=", "{$this->table}.payment_method_code")
            ->where("receipts.order_id", $orderId)
            ->where("receipts.is_deleted", self::NOT_DELETED);
        return $res->get();
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
    public function removeReceiptDetail($receiptId)
    {
        return $this->where("receipt_id", $receiptId)->delete();
    }
    public function removeReceiptDetailMethod($receiptId, $methodCode)
    {
        return $this->where("receipt_id", $receiptId)->where("payment_method_code", $methodCode)
            ->delete();
    }

    /**
     * Xoá chi tiết thanh toán bằng receipt_id
     *
     * @param $receiptId
     * @return mixed
     */
    public function removeByReceipt($receiptId)
    {
        return $this->where("receipt_id", $receiptId)->delete();
    }

    public function getItemPaymentByOrder($id)
    {
        $lang = Config::get('app.locale');

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
            ->leftJoin("receipts", "receipts.receipt_id", "=", "{$this->table}.receipt_id")
            ->leftJoin("payment_method", "payment_method.payment_method_code", "=", "{$this->table}.payment_method_code")
            ->where("receipts.order_id", $id)
            ->get();
    }
}