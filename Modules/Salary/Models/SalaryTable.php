<?php

namespace Modules\Salary\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Class SalaryTable
 * @package Modules\Salary\Models
 * @author VuND
 * @since 02/12/2021
 */
class SalaryTable extends Model
{
    protected $table = "salary";
    protected $primaryKey = "salary_id";
    protected $fillable = [
        "salary_id",
        "name",
        "season_month",
        "season_year",
        "date_start",
        "date_end",
        "queue_status",
        "is_active",
        "is_deleted",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
    ];


    public function getDetail($id){
        return $this->where($this->primaryKey, $id)->first();
    }

    public function getDataList($filter = [])
    {
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $query = $this->select(
            "{$this->table}.salary_id",
            "{$this->table}.name",
            "{$this->table}.season_month",
            "{$this->table}.season_year",
            "{$this->table}.date_start",
            "{$this->table}.date_end",
            "{$this->table}.queue_status",
            "{$this->table}.is_active",
            "{$this->table}.is_deleted",
            "{$this->table}.created_at",
            "{$this->table}.created_by",
            "{$this->table}.updated_at",
            "{$this->table}.updated_by",
            "p1.full_name as created_by_full_name",
            "p3.full_name as updated_by_full_name"
        )
        ->leftJoin("staffs as p1","p1.staff_id","{$this->table}.created_by")
        ->leftJoin("staffs as p3","p3.staff_id","{$this->table}.updated_by")
            ->orderBy($this->primaryKey, 'DESC');
            if(isset($filter['salary_period'])) {
                $season_month = Carbon::createFromFormat("m/Y", $filter["salary_period"])->format("m");
                $season_year = Carbon::createFromFormat("m/Y", $filter["salary_period"])->format("Y");
                $query = $query->where($this->table.'.season_month',$season_month);
                $query = $query->where($this->table.'.season_year',$season_year);
            }
            return $query->orderBy($this->primaryKey, 'desc')->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function add(array $data)
    {
        $oData = $this->create($data);
        return $oData->salary_id;
    }
    /**
     * Lấy tất cả danh sách
     * @param array $filter
     */
    public function getAll($filter = []){
        $oSelect = $this
            ->join('staffs' ,'staffs.staff_id',$this->table.'.staff_id')
            ->leftJoin('staff_title' ,'staff_title.staff_title_id','staffs.staff_title_id')
            ->select(
                $this->table.'.staff_code',
                $this->table.'.staff_name',
                $this->table.'.department_name',
                'staff_title.staff_title_name',
                $this->table.'.salary',
                $this->table.'.total_revenue',
                $this->table.'.total_commission',
                $this->table.'.total_kpi',
                $this->table.'.total_allowance',
                $this->table.'.plus',
                $this->table.'.minus',
                $this->table.'.total'
            );

//        Lọc theo phòng ban
        if(isset($filter['department_id'])) {
            $oSelect = $oSelect->where($this->table.'.department_id',$filter['department_id']);
        }

//        Lọc theo nhân viên
        if(isset($filter['staff_id'])) {
            $oSelect = $oSelect->where($this->table.'.staff_id',$filter['staff_id']);
        }

        return $oSelect->get();

    }

    /**
     * Sum tổng
     * @param array $filter
     * @return mixed
     */
    public function getAllTotal($filter = []){
        $oSelect = $this
            ->join('staffs' ,'staffs.staff_id',$this->table.'.staff_id')
            ->leftJoin('staff_title' ,'staff_title.staff_title_id','staffs.staff_title_id')
            ->select(
                DB::raw("SUM({$this->table}.salary) as sum_salary"),
                DB::raw("SUM({$this->table}.total_revenue) as sum_total_revenue"),
                DB::raw("SUM({$this->table}.total_commission) as sum_total_commission"),
                DB::raw("SUM({$this->table}.total_kpi) as sum_total_kpi"),
                DB::raw("SUM({$this->table}.total_allowance) as sum_total_allowance"),
                DB::raw("SUM({$this->table}.plus) as sum_plus"),
                DB::raw("SUM({$this->table}.minus) as sum_minus"),
                DB::raw("SUM({$this->table}.total) as sum_total")
            );

//        Lọc theo phòng ban
        if(isset($filter['department_id'])) {
            $oSelect = $oSelect->where($this->table.'.department_id',$filter['department_id']);
        }

//        Lọc theo nhân viên
        if(isset($filter['staff_id'])) {
            $oSelect = $oSelect->where($this->table.'.staff_id',$filter['staff_id']);
        }

        return $oSelect->first();
    }


    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    public function salaryStaffList($id,$filter = [])
    {
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $query = $this->select(
            "p1.salary_staff_id",
            "p1.salary_id",
            "p1.staff_id",
            "p1.staff_code",
            "p1.staff_name",
            "p1.department_id",
            "p1.department_name",
            "p1.salary",
            "p1.total_revenue",
            "p1.total_commission",
            "p1.total_kpi",
            "p1.total_allowance",
            "p1.plus",
            "p1.minus",
            "p1.total",
            "p1.created_at",
            "p1.created_by",
            "p1.updated_at",
            "p1.updated_by"
        )
        ->join("salary_staff as p1","p1.salary_id","{$this->table}.salary_id")
        ->where("p1.salary_id", $id)
            ->orderBy($this->primaryKey, 'DESC');
        if (isset($filter['staff_id'])){
            $query = $query->where('p1.staff_id',$filter['staff_id']);
        }

        if (isset($filter['department_id'])){
            $query = $query->where('p1.department_id',$filter['department_id']);
        }

        return $query->orderBy('p1.salary_staff_id', 'desc')->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}
