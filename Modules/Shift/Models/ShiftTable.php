<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 10:52 AM
 */

namespace Modules\Shift\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ShiftTable extends Model
{
    use ListTableTrait;
    protected $table = "sf_shifts";
    protected $primaryKey = "shift_id";
    protected $fillable = [
        "shift_id",
        "shift_name",
        "shift_type",
        "start_work_time",
        "end_work_time",
        "start_lunch_break",
        "end_lunch_break",
        "start_timekeeping_on",
        "end_timekeeping_on",
        "start_timekeeping_out",
        "end_timekeeping_out",
        "timekeeping_coefficient",
        "min_time_work",
        "note",
        "is_monday",
        "is_tuesday",
        "is_wednesday",
        "is_thursday",
        "is_friday",
        "is_saturday",
        "is_sunday",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "time_work"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Danh sách Ca
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.shift_id",
                "{$this->table}.shift_name",
                "{$this->table}.shift_type",
                "{$this->table}.start_work_time",
                "{$this->table}.end_work_time",
                "{$this->table}.start_lunch_break",
                "{$this->table}.end_lunch_break",
                "{$this->table}.start_timekeeping_on",
                "{$this->table}.end_timekeeping_on",
                "{$this->table}.start_timekeeping_out",
                "{$this->table}.end_timekeeping_out",
                "{$this->table}.timekeeping_coefficient",
                "{$this->table}.min_time_work",
                "{$this->table}.note",
                "{$this->table}.is_actived",
                "{$this->table}.is_deleted",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "{$this->table}.is_monday",
                "{$this->table}.is_tuesday",
                "{$this->table}.is_wednesday",
                "{$this->table}.is_thursday",
                "{$this->table}.is_friday",
                "{$this->table}.is_saturday",
                "{$this->table}.is_sunday",
                "{$this->table}.time_work"
            )
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.shift_name", "ASC");

        //filter theo ca
        if (isset($filter['shift_id']) && $filter['shift_id'] != null) {
            $ds->where("{$this->table}.shift_id", $filter['shift_id']);
        }

        // filter tên
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.shift_name", 'like', '%' . $search . '%');
            });
        }

        //filter shift_type
        if (isset($filter['shift_type']) && $filter['shift_type'] != null) {
            $ds->where("{$this->table}.shift_type", $filter['shift_type']);
        }

        //filter is_actived
        if (isset($filter['is_actived']) && $filter['is_actived'] != null) {
            $ds->where("{$this->table}.is_actived", $filter['is_actived']);
        }

        //filter theo thứ
        if (isset($filter['day_name']) && $filter['day_name'] != null) {
            switch ($filter['day_name']) {
                case 'Monday':
                    $ds->where("{$this->table}.is_monday", 1);
                    break;
                case 'Tuesday':
                    $ds->where("{$this->table}.is_tuesday", 1);
                    break;
                case 'Wednesday':
                    $ds->where("{$this->table}.is_wednesday", 1);
                    break;
                case 'Thursday':
                    $ds->where("{$this->table}.is_thursday", 1);
                    break;
                case 'Friday':
                    $ds->where("{$this->table}.is_friday", 1);
                    break;
                case 'Saturday':
                    $ds->where("{$this->table}.is_saturday", 1);
                    break;
                case 'Sunday':
                    $ds->where("{$this->table}.is_sunday", 1);
                    break;
            }
        }

        if (isset($filter['focus_shift_id']) && $filter['focus_shift_id'] != null) {
            $ds->where("{$this->table}.shift_id", $filter['focus_shift_id']);

            unset($filter['focus_shift_id']);
        }
        $ds->orderBy("{$this->table}.shift_id", 'ASC');

        unset($filter['shift_type'], $filter['is_actived'], $filter['day_name'], $filter['shift_id'], $filter['staff_salary_type_code']);
        return $ds;
    }

    /**
     * Thêm
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->shift_id;
    }

    /**
     * Lấy thông tin
     *
     * @param $shiftId
     * @return mixed
     */
    public function getInfo($shiftId)
    {
        return $this
            ->select(
                "{$this->table}.shift_id",
                "{$this->table}.shift_name",
                "{$this->table}.shift_type",
                "{$this->table}.start_work_time",
                "{$this->table}.end_work_time",
                "{$this->table}.start_lunch_break",
                "{$this->table}.end_lunch_break",
                "{$this->table}.start_timekeeping_on",
                "{$this->table}.end_timekeeping_on",
                "{$this->table}.start_timekeeping_out",
                "{$this->table}.end_timekeeping_out",
                "{$this->table}.timekeeping_coefficient",
                "{$this->table}.min_time_work",
                "{$this->table}.note",
                "{$this->table}.is_actived",
                "{$this->table}.is_deleted",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "{$this->table}.is_monday",
                "{$this->table}.is_tuesday",
                "{$this->table}.is_wednesday",
                "{$this->table}.is_thursday",
                "{$this->table}.is_friday",
                "{$this->table}.is_saturday",
                "{$this->table}.is_sunday",
                "{$this->table}.time_work"
            )
            ->where("shift_id", $shiftId)
            ->first();
    }

    /**
     * Chỉnh sửa
     *
     * @param array $data
     * @param $shiftId
     * @return mixed
     */
    public function edit(array $data, $shiftId)
    {
        return $this->where("shift_id", $shiftId)->update($data);
    }

    /**
     * Lấy option ca làm việc
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "shift_id",
                "shift_name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}