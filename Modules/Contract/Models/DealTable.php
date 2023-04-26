<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/09/2021
 * Time: 14:59
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class DealTable extends Model
{
    protected $table = "cpo_deals";
    protected $primaryKey = "deal_id";
    protected $fillable = [
        "deal_id",
        "deal_code",
        "deal_name",
        "customer_code",
        "contract_code",
        "total",
        "discount",
        "amount",
        "probability",
        "owner",
        "sale_id",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "pipeline_code",
        "journey_code",
        "deal_description",
        "order_source_id",
        "voucher_code",
        "discount_member",
        "customer_contact_code",
        "is_deleted",
        "closing_date",
        "closing_due_date",
        "reason_lose_code",
        "tag",
        "type_customer",
        "branch_code",
        "deal_type_code",
        "deal_type_object_id",
        "phone",
        "date_revoke"
    ];

    /**
     * Chỉnh sửa deal
     *
     * @param array $data
     * @param $dealCode
     * @return mixed
     */
    public function editByCode(array $data, $dealCode)
    {
        return $this->where("deal_code", $dealCode)->update($data);
    }
    public function getDealByTypeAndId($objectType, $objectId)
    {
        $item = $this->where("deal_type_code", $objectType)
            ->where("deal_type_object_id", $objectId);
        return $item->first();
    }
    public function getDealByContractCode($contractCode)
    {
        $item = $this->where("contract_code", $contractCode);
        return $item->first();
    }
}