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

class TimeWorkingStaffsTable extends Model
{
    use ListTableTrait;
    protected $table = "sf_time_working_staffs";
    protected $primaryKey = "time_working_staff_id";
    protected $fillable = [
        "time_working_staff_id",
        "work_schedule_id",
        "shift_id",
        "branch_id",
        "staff_id",
        "working_day",
        "working_time",
        "start_working_format_day",
        "start_working_format_week",
        "start_working_format_month",
        "start_working_format_year",
        "working_end_day",
        "working_end_time",
        "is_check_in",
        "is_check_out",
        "is_deducted",
        "is_close",
        "is_ot",
        "is_deleted",
        "note",
        "created_at",
        "updated_at",
        "updated_by",
        "is_approve_late",
        "is_approve_soon",
        "approve_late_by",
        "approve_soon_by",
        "check_in_by",
        "check_out_by",
        "time_work",
        "min_time_work",
        "actual_time_work"
    ];

    const IS_OT = 1;

    public function queryBuild($param = [])
    {
        $query = $this->select(
            "{$this->table}.{$this->primaryKey}",
            "{$this->table}.work_schedule_id",
            "{$this->table}.shift_id",
            "{$this->table}.branch_id",
            "{$this->table}.staff_id",
            "{$this->table}.working_day",
            "{$this->table}.working_time",
            "{$this->table}.working_end_day",
            "{$this->table}.working_end_time",
            "{$this->table}.number_working_day",
            "{$this->table}.number_working_ot_day",
            "{$this->table}.number_working_time",
            "{$this->table}.number_working_ot_time",
            "{$this->table}.number_late_time",
            "{$this->table}.number_time_back_soon",
            "{$this->table}.is_check_in",
            "{$this->table}.is_check_out",
            "{$this->table}.is_deducted",
            "{$this->table}.is_close",
            "{$this->table}.is_ot",
            "{$this->table}.is_off",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "{$this->table}.is_approve_late",
            "{$this->table}.is_approve_soon"
        );
        // relationship
        $query = $query->leftJoin('sf_shifts as s', 's.shift_id', "{$this->table}.shift_id");
        $query = $query->leftJoin('sf_check_in_log as ci', "ci.{$this->primaryKey}", "{$this->table}.{$this->primaryKey}");
        $query = $query->leftJoin('sf_check_out_log as co', "co.{$this->primaryKey}", "{$this->table}.{$this->primaryKey}");
        $query = $query->leftJoin('staffs', "staffs.staff_id", "{$this->table}.staff_id");
        $query = $query->leftJoin('branches', "branches.branch_id", "{$this->table}.branch_id");
        $query = $query->leftJoin('staff_title', "staff_title.staff_title_id", "staffs.staff_title_id");
        $query = $query->leftJoin("branches as wb", "{$this->table}.branch_id", "wb.branch_id")->addSelect("wb.branch_name as work_branch_name");

        // filter branch_id
        if (isset($param['branch_id']) && $param['branch_id'] != "") {
            $branch_id = $param['branch_id'];
            $query = $query->where(function ($where) use ($branch_id) {
                $where->where("{$this->table}.branch_id", $branch_id);
            });
            unset($param['branch_id']);
        }

        // filter department_id
        if (isset($param['department_id']) && $param['department_id'] != "") {
            $department_id = $param['department_id'];
            $query = $query->where(function ($where) use ($department_id) {
                $where->where("staffs.department_id", $department_id);
            });
            unset($param['department_id']);
        }

        // filter staff_id
        if (isset($param['staff_id']) && $param['staff_id'] != "") {
            $staff_id = $param['staff_id'];
            $query = $query->where(function ($where) use ($staff_id) {
                $where->where("{$this->table}.staff_id", $staff_id);
            });
            unset($param['staff_id']);
        }
        //filter type


        // filter time
        if (isset($param['time']) && $param['time'] != "") {
            $time = explode(" - ", $param['time']);
            $from = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');

            $query = $query->where(function ($where) use ($from, $to) {
                $where->where("{$this->table}.working_day", ">=", $from);
                $where->where("{$this->table}.working_day", "<=", $to);
            });

            unset($param['time']);
        }

        $query = $query->addSelect([
            "staffs.staff_id",
            "staffs.full_name as staff_name",
            "staffs.staff_avatar",
            "branches.branch_name",
            "staff_title.staff_title_name",
            DB::raw("GROUP_CONCAT( IF( ({$this->table}.is_check_in = 1) AND ({$this->table}.is_check_out = 1) , {$this->table}.working_day, NULL) ) as list_day"),
            DB::raw("SUM( IF( ({$this->table}.is_check_in = 1) AND ({$this->table}.is_check_out = 1) , 1, 0) ) as total_shift"),
            DB::raw("SUM( IF( ({$this->table}.is_check_in = 1) AND ({$this->table}.is_check_out = 1) AND (`{$this->table}`.`is_ot` = 1) , 1, 0) ) as total_ot"),
            DB::raw("SUM( UNIX_TIMESTAMP( STR_TO_DATE( CONCAT({$this->table}.working_end_day,' ',{$this->table}.working_end_time), '%Y-%m-%d %H:%i:%s')) - UNIX_TIMESTAMP( STR_TO_DATE( CONCAT({$this->table}.working_day,' ',{$this->table}.working_time), '%Y-%m-%d %H:%i:%s'))) as total_time"),
            DB::raw(
                "
            SUM(
                IF(
                    {$this->table}.is_off=0 AND {$this->table}.is_close=0
                    ,UNIX_TIMESTAMP( STR_TO_DATE( CONCAT({$this->table}.working_end_day,' ',{$this->table}.working_end_time),'%Y-%m-%d %H:%i:%s'))
                        -UNIX_TIMESTAMP( STR_TO_DATE( CONCAT({$this->table}.working_day,' ',{$this->table}.working_time), '%Y-%m-%d %H:%i:%s'))
                    ,0
                )
            ) as total_time_work"
            ),
            DB::raw("
            SUM(
                IF(
                    {$this->table}.is_off=0 AND {$this->table}.is_ot=1 AND {$this->table}.is_close=0
                    ,UNIX_TIMESTAMP( STR_TO_DATE( CONCAT({$this->table}.working_end_day,' ',{$this->table}.working_end_time), '%Y-%m-%d %H:%i:%s'))
                        -UNIX_TIMESTAMP( STR_TO_DATE( CONCAT({$this->table}.working_day,' ',{$this->table}.working_time), '%Y-%m-%d %H:%i:%s'))
                    ,0
                )
            ) as total_time_ot
            "),
            DB::raw("SUM( IF( UNIX_TIMESTAMP( STR_TO_DATE( CONCAT(ci.check_in_day,' ',ci.check_in_time), '%Y-%m-%d %H:%i:%s')) - UNIX_TIMESTAMP( STR_TO_DATE( CONCAT(ci.check_in_day,' ',{$this->table}.working_time), '%Y-%m-%d %H:%i:%s')) <= 0 ,1,0) ) as total_in_late"),
            DB::raw("SUM( IF( UNIX_TIMESTAMP( STR_TO_DATE( CONCAT(co.check_out_day,' ',co.check_out_time), '%Y-%m-%d %H:%i:%s')) - UNIX_TIMESTAMP( STR_TO_DATE( CONCAT(co.check_out_day,' ',{$this->table}.working_end_time), '%Y-%m-%d %H:%i:%s')) >= 0 ,1,0) ) as total_out_early"),
            DB::raw("SUM( IF( {$this->table}.is_close=1 AND {$this->table}.is_off=1 AND {$this->table}.is_deducted=1 ,1,0)) as total_deducted_shift"),
            DB::raw("SUM( IF( {$this->table}.is_close=1 AND {$this->table}.is_off=1 AND {$this->table}.is_deducted=0 ,1,0)) as total_non_deducted_shift"),
            DB::raw("SUM( IF( {$this->table}.is_close=1 AND {$this->table}.is_off=0 AND {$this->table}.is_check_in=0 ,1,0)) as total_non_check_in"),
            DB::raw("SUM( IF( {$this->table}.is_close=1 AND {$this->table}.is_off=0 AND {$this->table}.is_check_out=0 ,1,0)) as total_non_check_out"),
        ]);

        return $query;
    }

    /**
     * Danh sách
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this->select(
            "{$this->table}.{$this->primaryKey}",
            "{$this->table}.work_schedule_id",
            "{$this->table}.shift_id",
            "{$this->table}.branch_id",
            "{$this->table}.staff_id",
            "{$this->table}.working_day",
            "{$this->table}.working_time",
            "{$this->table}.working_end_day",
            "{$this->table}.working_end_time",
            "{$this->table}.number_working_day",
            "{$this->table}.number_working_ot_day",
            "{$this->table}.number_working_time",
            "{$this->table}.number_working_ot_time",
            "{$this->table}.number_late_time",
            "{$this->table}.number_time_back_soon",
            "{$this->table}.is_check_in",
            "{$this->table}.is_check_out",
            "{$this->table}.is_deducted",
            "{$this->table}.is_close",
            "{$this->table}.is_ot",
            "{$this->table}.is_off",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "{$this->table}.is_approve_late",
            "{$this->table}.is_approve_soon"
        );
        // relationship
        $ds = $ds->leftJoin('sf_shifts as s', 's.shift_id', "{$this->table}.shift_id");
        $ds = $ds->leftJoin('sf_check_in_log as ci', "ci.{$this->primaryKey}", "{$this->table}.{$this->primaryKey}");
        $ds = $ds->leftJoin('sf_check_out_log as co', "co.{$this->primaryKey}", "{$this->table}.{$this->primaryKey}");
        $ds = $ds->leftJoin('staffs', "staffs.staff_id", "{$this->table}.staff_id");
        $ds = $ds->leftJoin('branches', "branches.branch_id", "{$this->table}.branch_id");
        $ds = $ds->leftJoin('staff_title', "staff_title.staff_title_id", "staffs.staff_title_id");

        // filter branch_id
        if (isset($filter['branch_id']) && $filter['branch_id'] != "") {
            $branch_id = $filter['branch_id'];
            $ds = $ds->where(function ($query) use ($branch_id) {
                $query->where("{$this->table}.branch_id", $branch_id);
            });
            unset($filter['branch_id']);
        }

        // filter department_id
        if (isset($filter['department_id']) && $filter['department_id'] != "") {
            $department_id = $filter['department_id'];
            $ds = $ds->where(function ($query) use ($department_id) {
                $query->where("staffs.department_id", $department_id);
            });
            unset($filter['department_id']);
        }

        // filter staff_id
        if (isset($filter['staff_id']) && $filter['staff_id'] != "") {
            $staff_id = $filter['staff_id'];
            $ds = $ds->where(function ($query) use ($staff_id) {
                $query->where("{$this->table}.staff_id", $staff_id);
            });
            unset($filter['staff_id']);
        }
        //filter type




        // filter time
        if (isset($filter['time']) && $filter['time'] != "") {
            $time = explode(" - ", $filter['time']);
            $from = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');

            $ds = $ds->where(function ($query) use ($from, $to) {
                $query->where("{$this->table}.working_day", ">=", $from);
                $query->where("{$this->table}.working_day", "<=", $to);
            });

            unset($filter['time']);
        }



        $ds = $ds->groupBy("{$this->table}.staff_id");
        $ds = $ds->addSelect([
            "staffs.staff_id",
            "staffs.full_name as staff_name",
            "staffs.staff_avatar",
            "branches.branch_name",
            "staff_title.staff_title_name",
            DB::raw("GROUP_CONCAT( IF( ({$this->table}.is_check_in = 1) AND ({$this->table}.is_check_out = 1) , {$this->table}.working_day, NULL) ) as list_day"),
            DB::raw("SUM( IF( ({$this->table}.is_check_in = 1) AND ({$this->table}.is_check_out = 1) , 1, 0) ) as total_shift"),
            DB::raw("SUM( IF( ({$this->table}.is_check_in = 1) AND ({$this->table}.is_check_out = 1) AND (`{$this->table}`.`is_ot` = 1) , 1, 0) ) as total_ot"),
            DB::raw("SUM( UNIX_TIMESTAMP( STR_TO_DATE( CONCAT({$this->table}.working_end_day,' ',{$this->table}.working_end_time), '%Y-%m-%d %H:%i:%s')) - UNIX_TIMESTAMP( STR_TO_DATE( CONCAT({$this->table}.working_day,' ',{$this->table}.working_time), '%Y-%m-%d %H:%i:%s'))) as total_time"),
            DB::raw(
                "
            SUM(
                IF(
                    {$this->table}.is_off=0 AND {$this->table}.is_close=0
                    ,UNIX_TIMESTAMP( STR_TO_DATE( CONCAT({$this->table}.working_end_day,' ',{$this->table}.working_end_time),'%Y-%m-%d %H:%i:%s'))
                        -UNIX_TIMESTAMP( STR_TO_DATE( CONCAT({$this->table}.working_day,' ',{$this->table}.working_time), '%Y-%m-%d %H:%i:%s'))
                    ,0
                )
            ) as total_time_work"
            ),
            DB::raw("
            SUM(
                IF(
                    {$this->table}.is_off=0 AND {$this->table}.is_ot=1 AND {$this->table}.is_close=0
                    ,UNIX_TIMESTAMP( STR_TO_DATE( CONCAT({$this->table}.working_end_day,' ',{$this->table}.working_end_time), '%Y-%m-%d %H:%i:%s'))
                        -UNIX_TIMESTAMP( STR_TO_DATE( CONCAT({$this->table}.working_day,' ',{$this->table}.working_time), '%Y-%m-%d %H:%i:%s'))
                    ,0
                )
            ) as total_time_ot
            "),
            DB::raw("SUM( IF( UNIX_TIMESTAMP( STR_TO_DATE( CONCAT(ci.check_in_day,' ',ci.check_in_time), '%Y-%m-%d %H:%i:%s')) - UNIX_TIMESTAMP( STR_TO_DATE( CONCAT(ci.check_in_day,' ',{$this->table}.working_time), '%Y-%m-%d %H:%i:%s')) <= 0 ,1,0) ) as total_in_late"),
            DB::raw("SUM( IF( UNIX_TIMESTAMP( STR_TO_DATE( CONCAT(co.check_out_day,' ',co.check_out_time), '%Y-%m-%d %H:%i:%s')) - UNIX_TIMESTAMP( STR_TO_DATE( CONCAT(co.check_out_day,' ',{$this->table}.working_end_time), '%Y-%m-%d %H:%i:%s')) >= 0 ,1,0) ) as total_out_early"),
            DB::raw("SUM( IF( {$this->table}.is_close=1 AND {$this->table}.is_off=1 AND {$this->table}.is_deducted=1 ,1,0)) as total_deducted_shift"),
            DB::raw("SUM( IF( {$this->table}.is_close=1 AND {$this->table}.is_off=1 AND {$this->table}.is_deducted=0 ,1,0)) as total_non_deducted_shift"),
            DB::raw("SUM( IF( {$this->table}.is_close=1 AND {$this->table}.is_off=0 AND {$this->table}.is_check_in=0 ,1,0)) as total_non_check_in"),
            DB::raw("SUM( IF( {$this->table}.is_close=1 AND {$this->table}.is_off=0 AND {$this->table}.is_check_out=0 ,1,0)) as total_non_check_out"),
        ]);

        // sort
        if (isset($filter['sort']) && $filter['sort']) {
            $sort = $filter['sort'];
            $ds = $ds->orderBy($sort[0], $sort['1']);
            unset($filter['sort']);
        }


        return $ds;
    }
    /**
     * Danh sách
     *
     * @param array $filter
     * @return mixed
     */
    public function detailStaff($filter = [])
    {
        $ds = $this->queryBuild($filter);
        $ds = $ds->groupBy("{$this->table}.branch_id");

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
        return $this->create($data)->{$this->primaryKey};
    }



    /**
     * Lấy thông tin
     *
     * @param $timeKeepingId
     * @return mixed
     */
    public function getInfo($timeKeepingId)
    {
        return $this
            ->select(
                "{$this->table}.{$this->primaryKey}",
                "{$this->table}.wifi_name",
                "{$this->table}.bssid",
                "{$this->table}.note",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at"
            )
            ->where("{$this->primaryKey}", $timeKeepingId)
            ->first();
    }

    /**
     * Chỉnh sửa
     *
     * @param array $data
     * @param $timeKeepingId
     * @return mixed
     */
    public function edit(array $data, $timeKeepingId)
    {
        return $this->where("{$this->primaryKey}", $timeKeepingId)->update($data);
    }
}
