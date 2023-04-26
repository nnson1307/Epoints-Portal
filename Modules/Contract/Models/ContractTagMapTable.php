<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/08/2021
 * Time: 11:53
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractTagMapTable extends Model
{
    protected $table = "contract_tag_map";
    protected $primaryKey = "contract_tag_map_id";
    protected $fillable = [
        "contract_tag_map_id",
        "tag_id",
        "contract_id"
    ];

    /**
     * Lấy tag ăn theo HĐ
     *
     * @param $contractId
     * @return mixed
     */
    public function getTagMapByContract($contractId)
    {
        return $this
            ->select(
                "contract_tag_map_id",
                "tag_id",
                "contract_id"
            )
            ->where("contract_id", $contractId)
            ->get();
    }

    /**
     * Xoá tag ăn theo HĐ
     *
     * @param $contractId
     * @return mixed
     */
    public function removeTagByContract($contractId)
    {
        return $this->where("contract_id", $contractId)->delete();
    }
}