<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/07/2021
 * Time: 11:39
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class SmsDealDetailTable extends Model
{
    protected $table = "sms_deal_detail";
    protected $primaryKey = "sms_deal_detail_id";
    protected $fillable = [
        "sms_deal_detail_id",
        "sms_deal_id",
        "object_id",
        "object_name",
        "object_type",
        "object_code",
        "price",
        "quantity",
        "discount",
        "amount",
        "voucher_code",
        "updated_at",
        "created_at",
        "is_deleted",
        "created_by",
        "updated_by"
    ];

    const NOT_DELETED = 0;

    /**
     * Lấy thông tin deal được tạo khi chạy campaign
     *
     * @param $emailDealId
     * @return mixed
     */
    public function getDealDetail($emailDealId)
    {
        return $this
            ->select(
                "sms_deal_detail_id",
                "sms_deal_id",
                "object_name",
                "object_type",
                "object_code",
                "object_id",
                "price",
                "quantity",
                "discount",
                "amount",
                "voucher_code",
                "is_deleted"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->where("sms_deal_id", $emailDealId)
            ->get();
    }

    public function add(array $data)
    {
        return $this->create($data)->sms_deal_detail_id;
    }

    public function getList($smsDealId)
    {
        $list = $this
            ->select(
                "{$this->table}.sms_deal_detail_id",
                "{$this->table}.sms_deal_id",
                "{$this->table}.object_id",
                "{$this->table}.object_name",
                "{$this->table}.object_type",
                "{$this->table}.object_code",
                "{$this->table}.price",
                "{$this->table}.quantity",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.voucher_code"
            )
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.sms_deal_id", $smsDealId)
            ->orderBy("{$this->table}.sms_deal_detail_id", "desc")
            ->get();

        return $list;
    }


    public function removeItem($id)
    {
        return $this->where("sms_deal_id", $id)->delete();
    }
}