<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/08/2021
 * Time: 10:16
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractPartnerTable extends Model
{
    protected $table = "contract_partner";
    protected $primaryKey = "contract_partner_id";
    protected $fillable = [
        "contract_partner_id",
        "contract_id",
        "partner_object_form",
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
        "created_at",
        "updated_at",
        "partner_object_name"
    ];

    /**
     * Thêm thông tin đối tác HĐ
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Chỉnh sửa thông tin đối tác HĐ
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
     * Lấy thông tin đối tác (theo HĐ)
     *
     * @param $contractId
     * @return mixed
     */
    public function getPartnerByContract($contractId)
    {
        return $this
            ->select(
                "{$this->table}.contract_partner_id",
                "{$this->table}.contract_id",
                "{$this->table}.partner_object_form",
                "{$this->table}.partner_object_type",
                "{$this->table}.partner_object_id",
                "{$this->table}.partner_object_name",
                "{$this->table}.address",
                "{$this->table}.email",
                "{$this->table}.phone",
                "{$this->table}.tax_code",
                "{$this->table}.representative",
                "{$this->table}.hotline",
                "{$this->table}.staff_title",
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
                "cs.full_name as customer_name",
                "cs.phone1 as customer_phone",
                "suppliers.supplier_name",
                "suppliers.contact_phone as supplier_phone"
            )
            ->leftJoin("customers as cs", "cs.customer_id", "=", "{$this->table}.partner_object_id")
            ->leftJoin("suppliers", "suppliers.supplier_id", "=", "{$this->table}.partner_object_id")
            ->where("{$this->table}.contract_id", $contractId)
            ->first();
    }
}