<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/08/2021
 * Time: 14:50
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContractSignMapTable extends Model
{
    protected $table = "contract_sign_map";
    protected $primaryKey = "contract_sign_id";
    protected $fillable = [
        "contract_sign_id",
        "contract_id",
        "sign_by"
    ];

    /**
     * Lấy người ký ăn theo HĐ
     *
     * @param $contractId
     * @return mixed
     */
    public function getSignMapByContract($contractId)
    {
        return $this
            ->select(
                "{$this->table}.contract_sign_id",
                "{$this->table}.contract_id",
                "{$this->table}.sign_by",
                "staffs.staff_id",
                "staffs.full_name",
                "staffs.email"
            )
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.sign_by")
            ->where("{$this->table}.contract_id", $contractId)
            ->get();
    }
    public function getStaffNameSignMap($contractId)
    {
        $ds = $this->select(
            DB::raw("group_concat(staffs.full_name) as list_name")
        )
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.sign_by")
            ->where("contract_id", $contractId)
            ->groupBy("contract_id");
        return $ds->first();
    }
    /**
     * Xoá người ký ăn theo HĐ
     *
     * @param $contractId
     * @return mixed
     */
    public function removeSignByContract($contractId)
    {
        return $this->where("contract_id", $contractId)->delete();
    }
}