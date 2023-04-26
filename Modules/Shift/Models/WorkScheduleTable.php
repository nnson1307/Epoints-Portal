<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/04/2022
 * Time: 09:41
 */

namespace Modules\Shift\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class WorkScheduleTable extends Model
{
    use ListTableTrait;
    protected $table = "sf_work_schedules";
    protected $primaryKey = "work_schedule_id";
    protected $fillable = [
        "work_schedule_id",
        "work_schedule_name",
        "start_day_shift",
        "end_day_shift",
        "repeat",
        "note",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Danh sách lịch làm việc
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "work_schedule_id",
                "work_schedule_name",
                "start_day_shift",
                "end_day_shift",
                "repeat",
                "note",
                "is_actived"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->orderBy("work_schedule_id", "desc");

        if (isset($filter['search']) && $filter['search'] != null) {
            $search = $filter['search'];

            $ds->where(function ($query) use ($search) {
                $query->where("work_schedule_name", 'like', '%' . $search . '%');
            });

            unset($filter['search']);
        }

        return $ds;
    }

    /**
     * Thêm lịch làm việc
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->work_schedule_id;
    }

    /**
     * Lấy thông tin lịch làm việc
     *
     * @param $workScheduleId
     * @return mixed
     */
    public function getInfo($workScheduleId)
    {
        return $this
            ->select(
                "work_schedule_id",
                "work_schedule_name",
                "start_day_shift",
                "end_day_shift",
                "repeat",
                "note",
                "is_actived"
            )
            ->where("work_schedule_id", $workScheduleId)
            ->first();
    }

    /**
     * Chỉnh sửa lịch làm việc
     *
     * @param array $data
     * @param $workScheduleId
     * @return mixed
     */
    public function edit(array $data, $workScheduleId)
    {
        return $this->where("work_schedule_id", $workScheduleId)->update($data);
    }
}