<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/08/2021
 * Time: 11:50
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractAnnexFollowMapTable extends Model
{
    protected $table = "contract_annex_follow_map";
    protected $primaryKey = "contract_annex_follow_map_id";
    protected $fillable = [
        "contract_annex_follow_map_id",
        "contract_annex_id",
        "follow_by"
    ];

    /**
     * Lấy người theo dõi ăn theo HĐ
     *
     * @param $contractAnnexId
     * @return mixed
     */
    public function getFollowMapByContract($contractAnnexId)
    {
        return $this
            ->select(
                "contract_annex_follow_map_id",
                "contract_annex_id",
                "follow_by"
            )
            ->where("contract_annex_id", $contractAnnexId)
            ->get();
    }

    /**
     * Xoá người theo dõi ăn theo HĐ
     *
     * @param $contractAnnexId
     * @return mixed
     */
    public function removeFollowByContractAnnex($contractAnnexId)
    {
        return $this->where("contract_annex_id", $contractAnnexId)->delete();
    }
}