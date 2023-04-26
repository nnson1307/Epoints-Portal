<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/08/2021
 * Time: 11:53
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractAnnexTagMapTable extends Model
{
    protected $table = "contract_annex_tag_map";
    protected $primaryKey = "contract_annex_tag_map_id";
    protected $fillable = [
        "contract_annex_tag_map_id",
        "tag_id",
        "contract_annex_id"
    ];

    /**
     * Lấy tag ăn theo HĐ
     *
     * @param $contractAnnexId
     * @return mixed
     */
    public function getTagMapByContract($contractAnnexId)
    {
        return $this
            ->select(
                "contract_annex_tag_map_id",
                "tag_id",
                "contract_annex_id"
            )
            ->where("contract_annex_id", $contractAnnexId)
            ->get();
    }

    /**
     * Xoá tag ăn theo HĐ
     *
     * @param $contractAnnexId
     * @return mixed
     */
    public function removeTagByContractAnnex($contractAnnexId)
    {
        return $this->where("contract_annex_id", $contractAnnexId)->delete();
    }
}