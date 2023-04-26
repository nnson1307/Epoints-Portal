<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CustomerDealDetailTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_deal_details";
    protected $primaryKey = "deal_detail_id";
    protected $fillable = [
        "deal_detail_id",
        "deal_code",
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

    const NOT_DELETE = 0;

    /**
     * Thêm chi tiết deal
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->deal_detail_id;
    }

    /**
     * Cập nhật chi tiết deal
     *
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function edit($id, array $data)
    {
        return $this->where("deal_detail_id", $id)->update($data);
    }

    public function getList($dealCode)
    {
        $list = $this
            ->select(
                "cpo_deal_details.deal_detail_id",
                "cpo_deal_details.deal_code",
                "cpo_deal_details.object_id",
                "cpo_deal_details.object_name",
                "cpo_deal_details.object_type",
                "cpo_deal_details.object_code",
                "cpo_deal_details.price",
                "cpo_deal_details.quantity",
                "cpo_deal_details.discount",
                "cpo_deal_details.amount",
                "cpo_deal_details.voucher_code"
            )
            ->where("cpo_deal_details.is_deleted", 0)
            ->where("cpo_deal_details.deal_code", $dealCode)
            ->orderBy("cpo_deal_details.deal_detail_id", "desc")
            ->get();

        return $list;
    }

    /**
     * Xoá list object trong deal detail
     *
     * @param $dealCode
     */
    public function deleteByDealCode($dealCode)
    {
        $this->where('deal_code', $dealCode)->delete();
    }

    /**
     * chi tiết deal theo mã deal
     *
     * @param $dealCode
     * @return mixed
     */
    public function getDetailByDealCode($dealCode)
    {
        $select = $this->select(
            "deal_detail_id",
            "deal_code",
            "object_id",
            "object_name",
            "object_type",
            "object_code",
            "price",
            "quantity",
            "discount",
            "amount",
            "voucher_code",
            "is_deleted"
        )
            ->where('deal_code', $dealCode)
            ->where('is_deleted', 0);
        return $select->get()->toArray();
    }
}