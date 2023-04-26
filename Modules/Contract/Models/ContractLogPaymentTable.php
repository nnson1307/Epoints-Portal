<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/08/2021
 * Time: 10:23
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractLogPaymentTable extends Model
{
    protected $table = "contract_log_payment";
    protected $primaryKey = "contract_log_payment_id";
    protected $fillable = [
        "contract_log_payment_id",
        "contract_log_id",
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
        "total_amount_new",
        "tax_new",
        "discount_new",
        "last_total_amount_new",
        "payment_method_id_new",
        "payment_unit_id_new",
        "promotion_code_new",
        "reason_discount_new",
        "custom_1_new",
        "custom_2_new",
        "custom_3_new",
        "custom_4_new",
        "custom_5_new",
        "custom_6_new",
        "custom_7_new",
        "custom_8_new",
        "custom_9_new",
        "custom_10_new",
        "custom_11_new",
        "custom_12_new",
        "custom_13_new",
        "custom_14_new",
        "custom_15_new",
        "custom_16_new",
        "custom_17_new",
        "custom_18_new",
        "custom_19_new",
        "custom_20_new",
        "created_at",
        "updated_at",
        "total_amount_after_discount",
        "vat_id",
        "total_amount_after_discount_new",
        "vat_id_new"
    ];

    /**
     * Lưu log thanh toán HĐ
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }
}