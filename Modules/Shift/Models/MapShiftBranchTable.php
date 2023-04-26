<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/04/2022
 * Time: 17:21
 */

namespace Modules\Shift\Models;


use Illuminate\Database\Eloquent\Model;

class MapShiftBranchTable extends Model
{
    protected $table = "sf_map_shift_branch";
    protected $primaryKey = "map_shift_branch_id";
    protected $fillable = [
        "map_shift_branch_id",
        "branch_id",
        "shift_id",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVED = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy thông tin chi nhánh theo ca
     *
     * @param $shiftId
     * @return mixed
     */
    public function getInfoByShift($shiftId)
    {
        return $this
            ->select(
                "{$this->table}.map_shift_branch_id",
                "{$this->table}.branch_id",
                "{$this->table}.shift_id",
                "br.branch_name"
            )
            ->join("branches as br", "br.branch_id", "=", "{$this->table}.branch_id")
            ->where("{$this->table}.shift_id", $shiftId)
            ->where("br.is_actived", self::IS_ACTIVED)
            ->where("br.is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Xoá chi nhánh của ca làm việc
     *
     * @param $shiftId
     * @return mixed
     */
    public function removeBranchByShift($shiftId)
    {
        return $this->where("shift_id", $shiftId)->delete();
    }
}