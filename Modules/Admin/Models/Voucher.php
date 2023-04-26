<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class Voucher extends Model
{
    use ListTableTrait;

    protected $table = "vouchers";
    protected $primaryKey = "voucher_id";
    protected $fillable = [
        "voucher_id", 
        "code",
        "voucher_type",
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
        "customer_group_apply",
        "background_color",
        "text_color",
        "content_color",
        "using_by_guest",
        "number_of_using"
    ];

    const ALL = 'all';

    protected function _getList(&$filter = [])
    {
        $oSelect = $this
            ->select("voucher_id",
                "code", "is_all",
                "type", "percent",
                "cash", "max_price",
                "required_price", "object_type",
                "object_type_id", "expire_date",
                "quota", "total_use", "is_actived", "total_use")
            ->where("is_deleted", 0)
            ->orderBy("voucher_id", "desc");

        return $oSelect;
    }

    public function add(array $data)
    {
        return self::create($data);
    }

    public function edit($id, array $data)
    {
        return self::where("voucher_id", $id)->update($data);
    }

    public function remove($id)
    {
        return self::where("voucher_id", $id)->update([
            "is_deleted" => 1
        ]);
    }

    public function getDetail($id)
    {
        $oSelect = $this
            ->select(
                "voucher_id",
                "code",
                "voucher_type",
                "is_all",
                "type",
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
                "branch_id",
                "total_use",
                "sale_special",
                "voucher_img",
                "description",
                "detail_description",
                "member_level_apply",
                "type_using",
                "voucher_title",
                "point",
                "customer_group_apply",
                "background_color",
                "text_color",
                "content_color",
                "using_by_guest",
                "number_of_using"
            )
            ->where("voucher_id", $id)
            ->where("is_deleted", 0);

        return $oSelect->first();
    }

    public function changeStatus($id)
    {
        $oSelect = $this
            ->select("voucher_id", "is_actived")
            ->where("voucher_id", $id)
            ->where("is_deleted", 0)->first();
        if ($oSelect->is_actived == 0) {
            self::where("voucher_id", $id)->update([
                "is_actived" => 1
            ]);

            return 1;
        }
        self::where("voucher_id", $id)->update([
            "is_actived" => 0
        ]);

        return 0;
    }

    public function getCodeOrder($code, $type)
    {
        $ds = $this
            ->select(
                "voucher_id",
                "voucher_type",
                "code",
                "is_all",
                "type",
                "percent",
                "cash",
                "max_price",
                "required_price",
                "object_type",
                "object_type_id",
                "expire_date",
                "branch_id",
                "quota",
                "total_use",
                "type_using",
                "using_by_guest",
                "number_of_using"
            )
            ->where("code", $code)
            ->whereIn("object_type", [$type, self::ALL])
            ->where("is_deleted", 0)->first();
        return $ds;
    }

    public function getCodeItem($code)
    {
        $oSelect = $this
            ->select(
                "voucher_id",
                "voucher_type",
                "code",
                "is_all",
                "type",
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
                "branch_id",
                "total_use",
                "using_by_guest",
                "number_of_using"
            )
            ->where("code", $code)
            ->where("is_deleted", 0);

        return $oSelect->first();
    }

    public function editVoucherOrder($data, $code)
    {
        return $this->where("code", $code)->update($data);
    }

    public function checkSlug($slug, $id)
    {
        $select = $this->where("slug", str_slug($slug));
        if ($id != 0) {
            $select->where("voucher_id", "<>", $id);
        }
        return $select->first();
    }
}
