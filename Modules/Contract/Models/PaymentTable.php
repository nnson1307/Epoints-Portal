<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 09/09/2021
 * Time: 17:38
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class PaymentTable extends Model
{
    protected $table = "payments";
    protected $primaryKey = "payment_id";
    protected $fillable = [
        "payment_id",
        "payment_code",
        "branch_code",
        "staff_id",
        "total_amount",
        "approved_by",
        "status",
        "note",
        "payment_date",
        "object_accounting_type_code",
        "accounting_id",
        "accounting_name",
        "payment_type",
        "document_code",
        "payment_method",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "is_delete"
    ];

    const SPEND_CONTRACT_TYPE = "OAT_CONTRACT";

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
     * Chỉnh sửa phiếu chi
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function edit($data, $id)
    {
        return $this->where('payment_id', $id)->update($data);
    }

    /**
     * Chỉnh sửa phiếu chi bằng mã
     *
     * @param $data
     * @param $paymentCode
     * @return mixed
     */
    public function editByCode($data, $paymentCode)
    {
        return $this->where('payment_code', $paymentCode)->update($data);
    }

    /**
     * Chỉnh sửa phiếu chi bằng hợp đồng
     *
     * @param $data
     * @param $contractId
     * @return mixed
     */
    public function editByContract($data, $contractId)
    {
        return $this
            ->where("object_accounting_type_code", self::SPEND_CONTRACT_TYPE)
            ->where('accounting_id', $contractId)
            ->update($data);
    }
}