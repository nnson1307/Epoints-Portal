<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/08/2021
 * Time: 14:50
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractAnnexSignMapTable extends Model
{
    protected $table = "contract_annex_sign_map";
    protected $primaryKey = "contract_annex_sign_id";
    protected $fillable = [
        "contract_annex_sign_id",
        "contract_annex_id",
        "sign_by"
    ];

    /**
     * Lấy người ký ăn theo HĐ
     *
     * @param $contractAnnexId
     * @return mixed
     */
    public function getSignMapByContractAnnex($contractAnnexId)
    {
        return $this
            ->select(
                "contract_annex_sign_id",
                "contract_annex_id",
                "sign_by"
            )
            ->where("contract_annex_id", $contractAnnexId)
            ->get();
    }

    /**
     * Xoá người ký ăn theo HĐ
     *
     * @param $contractAnnexId
     * @return mixed
     */
    public function removeSignByContractAnnex($contractAnnexId)
    {
        return $this->where("contract_annex_id", $contractAnnexId)->delete();
    }
}