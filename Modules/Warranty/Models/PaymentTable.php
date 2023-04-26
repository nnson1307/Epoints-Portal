<?php

namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class PaymentTable extends Model
{
    protected $table = "payments";
    protected $primaryKey = "payment_id";

    protected $fillable = [
        'payment_id', 'payment_code', 'branch_code','staff_id', 'created_by', 'updated_by', 'total_amount',
        'approved_by', 'status', 'note','payment_date', 'created_at', 'updated_at',
        'object_accounting_type_code', 'accounting_id', 'accounting_name',
        'payment_type','document_code','payment_method','is_delete'
    ];

    const NOT_DELETE = 0;

    public function getPayment($paymentType, $repairId)
    {
        return $this
            ->where("payment_type", $paymentType)
            ->where("accounting_id", $repairId)
            ->where("is_delete", self::NOT_DELETE)
            ->first();
    }

    /**
     * Thêm phiếu chi
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Cập nhật phiếu chi
     *
     * @param $data
     * @param $paymentId
     * @return mixed
     */
    public function edit($data, $paymentId)
    {
        return $this->where("payment_id", $paymentId)->update($data);
    }

    /**
     * Lấy thông tin thanh toán phiếu bảo dưỡng
     *
     * @param $repairCode
     * @return mixed
     */
    public function getPaymentByRepairCode($repairCode)
    {
        $lang = Config::get('app.locale');
        return $this
            ->select(
                "{$this->table}.payment_id",
                "{$this->table}.payment_code",
                "{$this->table}.branch_code",
                "{$this->table}.staff_id",
                "{$this->table}.total_amount",
                "{$this->table}.status",
                "{$this->table}.status",
                "{$this->table}.payment_date",
                "{$this->table}.object_accounting_type_code",
                "{$this->table}.accounting_id",
                "{$this->table}.accounting_name",
                "{$this->table}.payment_type",
                "{$this->table}.document_code",
                "{$this->table}.created_at",
                "payment_method.payment_method_name_$lang as payment_method_name"
            )
            ->leftJoin("payment_method", "payment_method.payment_method_code", "=", "{$this->table}.payment_method")
            ->where("{$this->table}.document_code", $repairCode)
            ->where("{$this->table}.is_delete", self::NOT_DELETE)
            ->first();
    }
}