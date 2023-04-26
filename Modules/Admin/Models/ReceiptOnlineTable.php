<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/12/2021
 * Time: 11:41
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class ReceiptOnlineTable extends Model
{
    protected $table = "receipt_online";
    protected $primaryKey = "receipt_online_id";
    protected $fillable = [
        "receipt_online_id",
        "receipt_id",
        "object_type",
        "object_code",
        "object_id",
        "payment_method_code",
        "amount_paid",
        "payment_transaction_code",
        "payment_transaction_uuid",
        "payment_time",
        "status",
        "performer_name",
        "performer_phone",
        "type",
        "note",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm đợt thanh toán online
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->receipt_online_id;
    }

    /**
     * Chỉnh sửa đợt thanh toán online
     *
     * @param array $data
     * @param $receiptOnlineId
     * @return mixed
     */
    public function edit(array $data, $receiptOnlineId)
    {
        return $this->where("receipt_online_id", $receiptOnlineId)->update($data);
    }

    public function createReceiptOnline($data)
    {
        return $this->create($data)->receipt_online_id;
    }

    public function removeReceiptOnlineMethod($receiptId, $methodCode)
    {
        return $this->where("receipt_id", $receiptId)->where("payment_method_code", $methodCode)
            ->delete();
    }
    public function updateReceiptOnlineByTypeAndOrderId($data, $objectType, $objectId, $methodCode)
    {
        return $this->where("object_type", $objectType)
            ->where("object_id", $objectId)
            ->where("payment_method_code", $methodCode)->update($data);
    }
    public function getReceiptOnlineByTypeAndOrderId($objectType, $objectId, $methodCode)
    {
        return $this->where("object_type", $objectType)
            ->where("object_id", $objectId)
            ->where("payment_method_code", $methodCode)
            ->first();
    }

}