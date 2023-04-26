<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/09/2021
 * Time: 16:03
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class DealDetailTable extends Model
{
    protected $table = "cpo_deal_details";
    protected $primaryKey = "deal_detail_id";

    const NOT_DELETED = 0;

    /**
     * Lấy thông tin sản phẩm deal
     *
     * @param $dealCode
     * @param $arrLastFilter
     * @return mixed
     */
    public function getDealDetail($dealCode, $arrLastFilter = [])
    {
        return $this
            ->select(
                "{$this->table}.deal_code",
                "{$this->table}.object_id",
                "{$this->table}.object_name",
                "{$this->table}.object_type",
                "{$this->table}.object_code",
                "{$this->table}.price",
                "{$this->table}.quantity",
                "{$this->table}.discount",
                "{$this->table}.amount"
            )
            ->join("cpo_deals as deal", "deal.deal_code", "=", "{$this->table}.deal_code")
            ->where("deal.deal_code", $dealCode)
            ->whereNull("deal.contract_code")
            ->whereNotIn("deal.deal_code", $arrLastFilter)
            ->where("deal.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->get();
    }
}