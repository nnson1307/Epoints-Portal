<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/08/2021
 * Time: 10:15
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractLogPartnerTable extends Model
{
    protected $table = "contract_log_partner";
    protected $primaryKey = "contract_log_partner_id";
    protected $fillable  = [
        "contract_log_partner_id",
        "contract_log_id",
        "partner_object_type",
        "partner_object_id",
        "address",
        "email",
        "phone",
        "tax_code",
        "representative",
        "hotline",
        "staff_title",
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
        "partner_object_type_new",
        "partner_object_id_new",
        "address_new",
        "email_new",
        "phone_new",
        "tax_code_new",
        "representative_new",
        "hotline_new",
        "staff_title_new",
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
        "partner_object_name",
        "partner_object_name_new"
    ];

    /**
     * Lưu log thông tin đối tác HĐ
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }
}