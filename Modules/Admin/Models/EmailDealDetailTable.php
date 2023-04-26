<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 22/07/2021
 * Time: 14:16
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class EmailDealDetailTable extends Model
{
    protected $table = "email_deal_detail";
    protected $primaryKey = "email_deal_detail_id";
    protected $fillable = [
        "email_deal_detail_id",
        "email_deal_id",
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
                "email_deal_detail_id",
                "email_deal_id",
                "object_name",
                "object_type",
                "object_code",
                "object_id",
                "price",
                "quantity",
                "discount",
                "amount",
                "voucher_code",
                "is_deleted",
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->where("email_deal_id", $emailDealId)
            ->get();
    }

    public function add(array $data)
    {
        return $this->create($data)->email_deal_detail_id;
    }

    public function getList($emailDealId)
    {
        $list = $this
            ->select(
                "{$this->table}.email_deal_detail_id",
                "{$this->table}.email_deal_id",
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
            ->where("{$this->table}.email_deal_id", $emailDealId)
            ->orderBy("{$this->table}.email_deal_detail_id", "desc")
            ->get();

        return $list;
    }


    public function removeItem($id)
    {
        return $this->where("email_deal_id", $id)->delete();
    }
}