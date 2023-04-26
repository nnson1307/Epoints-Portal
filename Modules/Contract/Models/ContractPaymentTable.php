<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/08/2021
 * Time: 10:28
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractPaymentTable extends Model
{
    protected $table = "contract_payment";
    protected $primaryKey = "contract_payment_id";
    protected $fillable = [
        "contract_payment_id",
        "contract_id",
        "total_amount",
        "tax",
        "discount",
        "last_total_amount",
        "payment_method_id",
        "payment_unit_id",
        "promotion_code",
        "reason_discount",
        "custom_1",
        "custom_2",
        "custom_3",
        "custom_4",
        "custom_5",
        "custom_6",
        "custom_7",
        "custom_8",
        "custom_9",
        "custom_10",
        "custom_11",
        "custom_12",
        "custom_13",
        "custom_14",
        "custom_15",
        "custom_16",
        "custom_17",
        "custom_18",
        "custom_19",
        "custom_20",
        "created_at",
        "updated_at",
        "total_amount_after_discount",
        "vat_id"
    ];

    /**
     * Thêm thông tin thanh toán HĐ
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Chỉnh sửa thông tin thanh toán HĐ
     *
     * @param array $data
     * @param $contractId
     * @return mixed
     */
    public function edit(array $data, $contractId)
    {
        return $this->where("contract_id", $contractId)->update($data);
    }

    /**
     * Lấy thông tin thanh toán (theo HĐ)
     *
     * @param $contractId
     * @return mixed
     */
    public function getPaymentByContract($contractId)
    {
        return $this
            ->select(
                "{$this->table}.contract_payment_id",
                "{$this->table}.contract_id",
                "{$this->table}.total_amount",
                "{$this->table}.tax",
                "{$this->table}.discount",
                "{$this->table}.last_total_amount",
                "{$this->table}.payment_method_id",
                "{$this->table}.payment_unit_id",
                "{$this->table}.promotion_code",
                "{$this->table}.reason_discount",
                "{$this->table}.custom_1",
                "{$this->table}.custom_2",
                "{$this->table}.custom_3",
                "{$this->table}.custom_4",
                "{$this->table}.custom_5",
                "{$this->table}.custom_6",
                "{$this->table}.custom_7",
                "{$this->table}.custom_8",
                "{$this->table}.custom_9",
                "{$this->table}.custom_10",
                "{$this->table}.custom_11",
                "{$this->table}.custom_12",
                "{$this->table}.custom_13",
                "{$this->table}.custom_14",
                "{$this->table}.custom_15",
                "{$this->table}.custom_16",
                "{$this->table}.custom_17",
                "{$this->table}.custom_18",
                "{$this->table}.custom_19",
                "{$this->table}.custom_20",
                "{$this->table}.total_amount_after_discount",
                "{$this->table}.vat_id"
            )
            ->where("{$this->table}.contract_id", $contractId)
            ->first();
    }
}