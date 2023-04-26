<?php

/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/18/22
 * Time: 5:48 PM
 */

namespace Modules\StaffSalary\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TimekeepingStaffsTable extends Model
{
    // use ListTableTrait;
    protected $table = "sf_timekeeping_staffs";
    protected $primaryKey = "timekeeping_staff_id";
    protected $fillable = [
        "timekeeping_staff_id",
        "staff_salary_id",
        "staff_id",
        "total_working_day",
        "total_working_ot_day",
        "total_working_ot_saturday",
        "total_working_ot_sunday",
        "total_working_ot_holiday",
        "total_working_time",
        "total_time_ot_saturday",
        "total_time_ot_sunday",
        "total_time_ot_holiday",
        "total_working_ot_time",
        "total_day_late",
        "total_late_time",
        "total_day_back_soon",
        "total_time_back_soon",
        "total_shift_off",
        "total_day_not_check_in",
        "total_day_not_check_out",
        "total_day_paid_leave",
        "total_saturday_paid_leave",
        "total_sunday_paid_leave",
        "total_holiday_paid_leave",
        "total_day_unpaid_leave",
        "total_saturday_unpaid_leave",
        "total_sunday_unpaid_leave",
        "total_holiday_unpaid_leave",
        "total_day_saturday",
        "total_day_sunday",
        "total_day_holiday",
        "total_time_saturday",
        "total_time_sunday",
        "total_time_holiday",
        "total_time_paid_leave",
        "total_saturday_time_paid_leave",
        "total_sunday_time_paid_leave",
        "total_holiday_time_paid_leave",
        "total_time_unpaid_leave",
        "total_saturday_time_unpaid_leave",
        "total_sunday_time_unpaid_leave",
        "total_holiday_time_unpaid_leave",
        "total_timekeeping_coefficient",
        "total_timekeeping_coefficient_saturday",
        "total_timekeeping_coefficient_sunday",
        "total_timekeeping_coefficient_holiday",
        "total_timekeeping_coefficient_ot",
        "total_timekeeping_coefficient_saturday_ot",
        "total_timekeeping_coefficient_sunday_ot",
        "total_timekeeping_coefficient_holiday_ot",
        "start_date",
        "end_date",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];
    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;
    /**
     * add 
     *
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->staff_salary_id;
    }

    /**
     * delete 
     *
     * @return mixed
     */
    public function deleteBysalary($staff_salary_id)
    {

        return $this->where('staff_salary_id', '=', $staff_salary_id)->delete();
    }

    /**
     * Lấy ds lịch làm việc của nhân viên
     *
     * @param array $filter
     * @return mixed
     */
    public function getDetail($staffId, $staffSalaryId)
    {
        $ds = $this
            ->select(
                "{$this->table}.timekeeping_staff_id",
                "{$this->table}.staff_salary_id",
                "{$this->table}.staff_id",
                "{$this->table}.total_working_day",
                "{$this->table}.total_working_ot_day",
                "{$this->table}.total_working_ot_saturday",
                "{$this->table}.total_working_ot_sunday",
                "{$this->table}.total_working_ot_holiday",
                "{$this->table}.total_working_time",
                "{$this->table}.total_time_ot_saturday",
                "{$this->table}.total_time_ot_sunday",
                "{$this->table}.total_time_ot_holiday",
                "{$this->table}.total_working_ot_time",
                "{$this->table}.total_day_late",
                "{$this->table}.total_late_time",
                "{$this->table}.total_day_back_soon",
                "{$this->table}.total_time_back_soon",
                "{$this->table}.total_shift_off",
                "{$this->table}.total_day_not_check_in",
                "{$this->table}.total_day_not_check_out",
                "{$this->table}.total_day_paid_leave",
                "{$this->table}.total_saturday_paid_leave",
                "{$this->table}.total_sunday_paid_leave",
                "{$this->table}.total_holiday_paid_leave",
                "{$this->table}.total_day_unpaid_leave",
                "{$this->table}.total_saturday_unpaid_leave",
                "{$this->table}.total_sunday_unpaid_leave",
                "{$this->table}.total_holiday_unpaid_leave",
                "{$this->table}.total_day_saturday",
                "{$this->table}.total_day_sunday",
                "{$this->table}.total_day_holiday",
                "{$this->table}.total_time_saturday",
                "{$this->table}.total_time_sunday",
                "{$this->table}.total_time_holiday",
                "{$this->table}.total_time_paid_leave",
                "{$this->table}.total_saturday_time_paid_leave",
                "{$this->table}.total_sunday_time_paid_leave",
                "{$this->table}.total_holiday_time_paid_leave",
                "{$this->table}.total_time_unpaid_leave",
                "{$this->table}.total_saturday_time_unpaid_leave",
                "{$this->table}.total_sunday_time_unpaid_leave",
                "{$this->table}.total_holiday_time_unpaid_leave",
                "{$this->table}.total_timekeeping_coefficient",
                "{$this->table}.total_timekeeping_coefficient_saturday",
                "{$this->table}.total_timekeeping_coefficient_sunday",
                "{$this->table}.total_timekeeping_coefficient_holiday",
                "{$this->table}.total_timekeeping_coefficient_ot",
                "{$this->table}.total_timekeeping_coefficient_saturday_ot",
                "{$this->table}.total_timekeeping_coefficient_sunday_ot",
                "{$this->table}.total_timekeeping_coefficient_holiday_ot",
                "{$this->table}.start_date",
                "{$this->table}.end_date",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at"
            )
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.staff_salary_id", $staffSalaryId);
        return $ds->first();
    }

    /**
     * Lấy thời gian làm việc theo chi nhánh
     *
     * @param array $filter
     * @return mixed
     */
    public function getTimeKeepingByBranch($filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.staff_salary_id",
                DB::raw("SUM({$this->table}.total_working_time) as total_working_time"),
                DB::raw("SUM({$this->table}.total_time_saturday) as total_time_saturday"),
                DB::raw("SUM({$this->table}.total_time_sunday) as total_time_sunday"),
                DB::raw("SUM({$this->table}.total_time_holiday) as total_time_holiday"),
                DB::raw("SUM({$this->table}.total_working_ot_time) as total_working_ot_time"),
                DB::raw("SUM({$this->table}.total_time_ot_saturday) as total_time_ot_saturday"),
                DB::raw("SUM({$this->table}.total_time_ot_sunday) as total_time_ot_sunday"),
                DB::raw("SUM({$this->table}.total_time_ot_holiday) as total_time_ot_holiday"),
                DB::raw("SUM({$this->table}.total_timekeeping_coefficient) as total_timekeeping_coefficient"),
                DB::raw("SUM({$this->table}.total_timekeeping_coefficient_saturday) as total_timekeeping_coefficient_saturday"),
                DB::raw("SUM({$this->table}.total_timekeeping_coefficient_sunday) as total_timekeeping_coefficient_sunday"),
                DB::raw("SUM({$this->table}.total_timekeeping_coefficient_holiday) as total_timekeeping_coefficient_holiday"),
                DB::raw("SUM({$this->table}.total_timekeeping_coefficient_ot) as total_timekeeping_coefficient_ot"),
                DB::raw("SUM({$this->table}.total_timekeeping_coefficient_saturday_ot) as total_timekeeping_coefficient_saturday_ot"),
                DB::raw("SUM({$this->table}.total_timekeeping_coefficient_sunday_ot) as total_timekeeping_coefficient_sunday_ot"),
                DB::raw("SUM({$this->table}.total_timekeeping_coefficient_holiday_ot) as total_timekeeping_coefficient_holiday_ot"),
                DB::raw("SUM(sld.staff_salary_received) as total_salary")
            )
            ->join("staffs as sf", "sf.staff_id", "=", "{$this->table}.staff_id")
            ->join("branches as br", "br.branch_id", "=", "sf.branch_id")
            ->join("staff_salary as sl", "sl.staff_salary_id", "=", "{$this->table}.staff_salary_id")
            ->join("staff_salary_detail as sld", function ($join) {
                $join->on("sld.staff_salary_id", "=", "{$this->table}.staff_salary_id")
                    ->whereRaw("sld.staff_id = {$this->table}.staff_id");
            })
            ->where("sf.is_actived", self::IS_ACTIVE)
            ->where("sf.is_deleted", self::NOT_DELETED)
            ->where("br.is_actived", self::IS_ACTIVE)
            ->where("br.is_deleted", self::NOT_DELETED)
            ->groupBy("{$this->table}.staff_salary_id");

        //Filter chi nhánh
        if (isset($filter['branch_id']) && $filter['branch_id'] != null) {
            $ds->where("br.branch_id", $filter['branch_id']);
        }

        //Filter theo loại (tuần/ tháng)
        if (
            isset($filter['date_type']) && $filter['date_type'] != null
            && isset($filter['date_object']) && $filter['date_object'] != null
        ) {

            switch ($filter['date_type']) {
                case 'by_week':
                    $ds->where("sl.staff_salary_weeks", $filter['date_object'])
                        ->where("sl.staff_salary_years", Carbon::now()->format('Y'));
                    break;
                case 'by_month':
                    $ds->where("sl.staff_salary_months", $filter['date_object'])
                        ->where("sl.staff_salary_years", Carbon::now()->format('Y'));
                    break;
            }
        }
        // if ($filter['branch_id'] == 5) {
        //     var_dump($ds->toSql());
        //     die;
        // }

        return $ds->first();
    }
}
