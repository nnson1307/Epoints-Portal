<?php

/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/7/22
 * Time: 5:48 PM
 */

namespace Modules\Shift\Models;

use Illuminate\Database\Eloquent\Model;
// use MyCore\Models\Traits\ListTableTrait;
use Carbon\Carbon;

class ShiftCheckInLogTable extends Model
{
    // use ListTableTrait;
    protected $table = "sf_check_in_log";
    protected $primaryKey = "check_in_log_id";
    protected $fillable = [
        "check_in_log_id",
        "time_working_staff_id",
        "staff_id",
        "branch_id",
        "shift_id",
        "check_in_day",
        "check_in_time",
        "status",
        "reason",
        "created_type",
        "created_by",
        "created_at",
        "updated_at",
    ];
    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Thêm mới checkin
     *
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->check_in_log_id;
    }
    public function _getList($filters)
    {
        $ds = $this
            ->select(
                "{$this->table}.check_in_day",
                "{$this->table}.check_in_time",
                "{$this->table}.branch_id",
                "{$this->table}.time_working_staff_id",
                "number_late_time",
                "number_time_back_soon",
                "is_check_in",
                "is_check_out",
                "sh.shift_name",
                "sf_time.working_day",
                "sf_time.working_end_time",
                "sf_time.working_time",
                "co.check_out_day",
                "co.check_out_time",
                "st.staff_id",
                "st.full_name as staff_name",
                "br.branch_name",
                'departments.department_name as department_name',
                "sf_time.is_approve_late",
                "sf_time.is_approve_soon",
                "sf_time.check_in_by",
                "sf_time.check_out_by",
                "st1.full_name as approve_late_name",
                "st2.full_name as approve_soon_name",
                "st3.full_name as check_in_name",
                "st4.full_name as check_out_name"

            )
            ->join("sf_time_working_staffs as sf_time", "sf_time.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->join("sf_shifts as sh", "sh.shift_id", "=", "{$this->table}.shift_id")
            ->leftJoin("sf_check_out_log as co", "co.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->leftJoin("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->join("branches as br", "br.branch_id", "=", "{$this->table}.branch_id")
            ->join('departments', 'departments.department_id', '=', 'st.department_id')
            ->leftJoin("staffs as st1", "st1.staff_id", "=", "sf_time.approve_late_by")
            ->leftJoin("staffs as st2", "st2.staff_id", "=", "sf_time.approve_soon_by")
            ->leftJoin("staffs as st3", "st3.staff_id", "=", "sf_time.check_in_by")
            ->leftJoin("staffs as st4", "st4.staff_id", "=", "sf_time.check_out_by");
        if (isset($filters['department_id']) != "") {
            $ds->where('st.department_id', $filters['department_id']);
        }
        if (isset($filters['branch_id']) != "") {
            $ds->where("{$this->table}.branch_id", $filters['branch_id']);
        }
        if (isset($filters['search']) != "") {
            $search = $filters['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('st.full_name', 'like', '%' . $search . '%')
                    ->orWhere('st.user_name', 'like', '%' . $search . '%')
                    ->orWhere('st.email', 'like', '%' . $search . '%')
                    ->where('st.is_deleted', 0);
            });
        }
        if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $ds->whereDate("check_in_day", ">=", $startTime);
            $ds->whereDate("check_in_day", "<=", $endTime);
        }
        $isValid = $filters['status'] ?? 0;

        if ($isValid == 1) {

            $ds->where(function ($query) use ($isValid) {
                $query->where(function ($query2) use ($isValid) {
                    $query2->where('number_late_time', '=', 0);
                });
                $query->orWhere(function ($query1) use ($isValid) {
                    $query1->where('is_approve_late', '=', 1)
                        ->where('number_late_time', '>', 0);
                });
            });
            $ds->where(function ($query) use ($isValid) {
                $query->where(function ($query2) use ($isValid) {
                    $query2->where('number_time_back_soon', '=', 0);
                });
                $query->orWhere(function ($query1) use ($isValid) {
                    $query1->where('is_approve_soon', '=', 1)
                        ->where('number_time_back_soon', '>', 0);
                });
            });
            $ds->orWhere(function ($query) use ($isValid) {
                $query->where('working_day', '=', Carbon::now()->format('y-m-d'))
                    ->where('check_out_time', '=', null);
            });
        } else if ($isValid == 2) {
            $ds->where(function ($query) use ($isValid) {
                $query->where(function ($query2) use ($isValid) {
                    $query2->where('number_late_time', '>', 0);
                });
                $query->where(function ($query1) use ($isValid) {
                    $query1->where('is_approve_late', '=', 0)
                        ->orWhere('is_approve_late', '=', null);
                });
            });
        } else if ($isValid == 3) {

            $ds->orWhere(function ($query) use ($isValid) {
                $query->where(function ($query2) use ($isValid) {
                    $query2->where('number_time_back_soon', '>', 0);
                });
            });
        } else if ($isValid == 4) {
            $ds->orWhere(function ($query) use ($isValid) {
                $query->where('working_day', '<', Carbon::now()->format('y-m-d'))
                    ->where('check_out_time', '=', null);
            });
        }
        $ds->where('sh.is_deleted', self::NOT_DELETED);
        $ds->orderBy('check_in_day', 'DESC');
        $ds->orderBy('check_in_time', 'DESC');
        $ds->orderBy('check_out_time', 'DESC');
        $page    = (int) ($filters['page'] ?? 1);
        $display = (int) ($filters['perpage'] ?? 10);
        return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Lấy thông tin vào ca của ca làm việc
     *
     * @param $timeWorkingStaffId
     * @return mixed
     */
    public function getInfoLog($timeWorkingStaffId)
    {
        return $this->where("time_working_staff_id", $timeWorkingStaffId)->first();
    }

    /**
     * Chỉnh sửa vào ca
     *
     * @param array $data
     * @param $checkInLogId
     * @return mixed
     */
    public function edit(array $data, $checkInLogId)
    {
        return $this->where("check_in_log_id", $checkInLogId)->update($data);
    }
}
