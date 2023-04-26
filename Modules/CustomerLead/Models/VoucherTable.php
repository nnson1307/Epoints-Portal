<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherTable extends Model
{
    protected $table = "vouchers";
    protected $primaryKey = "voucher_id";
    protected $fillable = [
        "voucher_id",
        "code",
        "is_all",
        "type",
        "branch_id",
        "percent",
        "cash",
        "max_price",
        "required_price",
        "object_type",
        "object_type_id",
        "expire_date",
        "quota",
        "total_use",
        "is_actived",
        "sale_special",
        "voucher_img",
        "description",
        "detail_description",
        "member_level_apply",
        "type_using",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "slug",
        "voucher_title",
        "point",
        "customer_group_apply"
    ];

    public function getCodeItem($code)
    {
        $oSelect = $this
            ->select("voucher_id",
                "code", "is_all",
                "type", "percent",
                "cash", "max_price",
                "required_price", "object_type",
                "object_type_id", "expire_date",
                "quota", "total_use", "is_actived", "branch_id", "total_use")
            ->where("code", $code)
            ->where("is_deleted", 0);

        return $oSelect->first();
    }

    public function editVoucherOrder($data, $code)
    {
        return $this->where("code", $code)->update($data);
    }
}