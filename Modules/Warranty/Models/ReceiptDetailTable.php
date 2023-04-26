<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 3/5/2021
 * Time: 1:43 PM
 */

namespace Modules\Warranty\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class ReceiptDetailTable extends Model
{
    protected $table = "receipt_details";
    protected $primaryKey = "receipt_detail_id";
    protected $fillable = [
        "receipt_detail_id",
        "receipt_id",
        "cashier_id",
        "receipt_type",
        "amount",
        "note",
        "card_code",
        "payment_method_code",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const MAINTENANCE = "maintenance";
    const TYPE_MAINTENANCE = "RTC_MAINTENANCE";

    /**
     * Lấy chi tiết thanh toán phiếu bảo trì
     *
     * @param $maintenanceId
     * @return mixed
     */
    public function getDetailMaintenance($maintenanceId)
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
            ->join("receipts", "receipt_details.receipt_id", "=", "receipts.receipt_id")
            ->leftJoin("payment_method", "payment_method.payment_method_code", "=", "{$this->table}.payment_method_code")
            ->where(function ($query) use ($maintenanceId) {
                $query->where("object_type", self::MAINTENANCE)
                    ->where("object_id", $maintenanceId);
            })
            ->orWhere(function ($query) use ($maintenanceId) {
                $query->where("receipt_type_code", self::TYPE_MAINTENANCE)
                    ->where("object_accounting_id", $maintenanceId);
            })
            ->get();
    }

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
     * Xoá chi tiết thanh toán bằng receipt_id
     *
     * @param $receiptId
     * @return mixed
     */
    public function removeByReceipt($receiptId)
    {
        return $this->where("receipt_id", $receiptId)->delete();
    }
}