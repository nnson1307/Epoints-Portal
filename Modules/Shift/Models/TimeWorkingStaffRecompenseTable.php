<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 11/10/2022
 * Time: 10:51
 */

namespace Modules\Shift\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class TimeWorkingStaffRecompenseTable extends Model
{
    use ListTableTrait;
    protected $table = "sf_time_working_staff_recompense";
    protected $primaryKey = "time_working_staff_recompense_id";
    protected $fillable = [
        "time_working_staff_recompense_id",
        "time_working_staff_id",
        "recompense_id",
        "money",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Danh sách thưởng phạt
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.time_working_staff_recompense_id",
                "r.recompense_name",
                "{$this->table}.money"
            )
            ->join("sf_recompense as r", "r.recompense_id", "=", "{$this->table}.recompense_id")
            ->orderBy("{$this->table}.time_working_staff_recompense_id", "desc");

        //Filter theo loại
        if (isset($filter["type"]) && $filter["type"] != "") {
            $ds->where("r.type", $filter['type']);
        }

        //Filter theo ngày làm việc
        if (isset($filter["time_working_staff_id"]) && $filter["time_working_staff_id"] != "") {
            $ds->where("{$this->table}.time_working_staff_id", $filter['time_working_staff_id']);
        }

        unset($filter['time_working_staff_id'], $filter['type']);

        return $ds;
    }

    /**
     * Xoá thưởng - phạt của ngày làm việc
     *
     * @param $timeWorkingRecompenseId
     * @return mixed
     */
    public function removeRecompense($timeWorkingRecompenseId)
    {
        return $this->where("time_working_staff_recompense_id", $timeWorkingRecompenseId)->delete();
    }

    /**
     * Thêm thưởng - phạt của ngày làm việc
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->time_working_staff_recompense_id;
    }
}