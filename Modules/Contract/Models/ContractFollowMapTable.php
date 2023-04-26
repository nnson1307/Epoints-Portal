<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/08/2021
 * Time: 11:50
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContractFollowMapTable extends Model
{
    protected $table = "contract_follow_map";
    protected $primaryKey = "contract_follow_map_id";
    protected $fillable = [
        "contract_follow_map_id",
        "contract_id",
        "follow_by"
    ];

    /**
     * Lấy người theo dõi ăn theo HĐ
     *
     * @param $contractId
     * @return mixed
     */
    public function getFollowMapByContract($contractId)
    {
        return $this
            ->select(
                "{$this->table}.contract_follow_map_id",
                "{$this->table}.contract_id",
                "{$this->table}.follow_by",
                "staffs.staff_id",
                "staffs.full_name",
                "staffs.email"
            )
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.follow_by")
            ->where("{$this->table}.contract_id", $contractId)
            ->get();
    }

    public function getStaffNameFollowMap($contractId)
    {
        $ds = $this->select(
            DB::raw("group_concat(staffs.full_name) as list_name")
        )
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.follow_by")
            ->where("contract_id", $contractId)
            ->groupBy("contract_id");
        return $ds->first();
    }
    /**
     * Xoá người theo dõi ăn theo HĐ
     *
     * @param $contractId
     * @return mixed
     */
    public function removeFollowByContract($contractId)
    {
        return $this->where("contract_id", $contractId)->delete();
    }
}