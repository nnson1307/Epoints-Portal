<?php

namespace Modules\Salary\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class SalaryTable
 * @package Modules\Salary\Models
 * @author VuND
 * @since 02/12/2021
 */
class SalaryStaffTable extends BaseModel
{

    protected $table = "salary_staff";
    protected $primaryKey = "salary_staff_id";

    public function getDetailByStaffId($salaryId, $staffId)
    {
        $oSelect = $this->where('salary_id', $salaryId)->where('staff_id', $staffId)->first();
        return $this->returntToArray($oSelect);
    }

    /**
     * Lấy tất cả danh sách
     * @param array $filter
     */
    public function getAll($filter = [])
    {
        $oSelect = $this
            ->join('staffs', 'staffs.staff_id', $this->table . '.staff_id')
            ->leftJoin('staff_title', 'staff_title.staff_title_id', 'staffs.staff_title_id')
            ->leftJoin('salary_commission_config', 'salary_commission_config.department_id', $this->table . '.department_id')
            ->select(
                $this->table . '.staff_code',
                $this->table . '.staff_name',
                $this->table . '.department_name',
                'staff_title.staff_title_name',
                $this->table . '.salary',
                $this->table . '.total_revenue',
                $this->table . '.total_commission',
                $this->table . '.total_kpi',
                $this->table . '.total_allowance',
                $this->table . '.plus',
                $this->table . '.minus',
                $this->table . '.total'
            );

//        Kì lương
        if (isset($filter['salary_id'])) {
            $oSelect = $oSelect->where($this->table . '.salary_id', $filter['salary_id']);
        }

//        Lọc theo phòng ban
        if (isset($filter['department_id'])) {
            $oSelect = $oSelect->where($this->table . '.department_id', $filter['department_id']);
        }

//        Lọc theo nhân viên
        if (isset($filter['staff_id'])) {
            $oSelect = $oSelect->where($this->table . '.staff_id', $filter['staff_id']);
        }
        // lọc theo loại phòng ban kỹ thuật hay kinh doanh
        if (isset($filter['type'])) {
            $oSelect = $oSelect->where('salary_commission_config.type_view', $filter['type']);
        }

        return $oSelect->orderBy($this->table . '.salary_staff_id', 'DESC')->get();

    }

    /**
     * Sum tổng
     * @param array $filter
     * @return mixed
     */
    public function getAllTotal($filter = [])
    {
        $oSelect = $this
            ->join('staffs', 'staffs.staff_id', $this->table . '.staff_id')
            ->leftJoin('staff_title', 'staff_title.staff_title_id', 'staffs.staff_title_id')
            ->leftJoin('salary_commission_config', 'salary_commission_config.department_id', $this->table . '.department_id')
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

//        Kì lương
        if (isset($filter['salary_id'])) {
            $oSelect = $oSelect->where($this->table . '.salary_id', $filter['salary_id']);
        }
//        Lọc theo phòng ban
        if (isset($filter['department_id'])) {
            $oSelect = $oSelect->where($this->table . '.department_id', $filter['department_id']);
        }

//        Lọc theo nhân viên
        if (isset($filter['staff_id'])) {
            $oSelect = $oSelect->where($this->table . '.staff_id', $filter['staff_id']);
        }
        // lọc theo loại phòng ban kỹ thuật hay kinh doanh
        if (isset($filter['type'])) {
            $oSelect = $oSelect->where('salary_commission_config.type_view', $filter['type']);
        }

        return $oSelect->first();

    }

    /**
     * Kiểm tra mã nhân viên
     */
    public function checkCode($data)
    {
        return $this
            ->select($this->table.'.salary_staff_id',$this->table.'.total_revenue',$this->table.'.total_commission')
            ->join('staffs', 'staffs.staff_id', $this->table . '.staff_id')
            ->where($this->table . '.staff_code', $data['staff_code'])
            ->where($this->table . '.salary_id', $data['salary_id'])
            ->first();
    }

    public function editSalaryStaff($data, $salaryStaffId)
    {
        return $this->where('salary_staff_id', $salaryStaffId)->update($data);
    }

    /**
     * Thêm thông tin lương nhân viên
     * @param $data
     * @return mixed
     */
    public function addSalaryStaff($data)
    {
        return $this->insertGetId($data);
    }

    /**
     * Lấy chi tiết lương
     * @param $salaryStaffId
     */
    public function getDetail($salaryStaffId)
    {
        return $this
            ->select(
                $this->table . '.salary_staff_id',
                $this->table . '.salary_id',
                $this->table . '.staff_code',
                $this->table . '.staff_name',
                $this->table . '.department_name',
                'staff_title.staff_title_name',
                $this->table . '.salary',
                $this->table . '.total_commission',
                $this->table . '.total_kpi',
                $this->table . '.total_allowance',
                $this->table . '.plus',
                $this->table . '.minus',
                $this->table . '.note',
                $this->table . '.total',
                'staffs.subsidize'
            )
            ->join('staffs', 'staffs.staff_id', $this->table . '.staff_id')
            ->leftJoin('staff_title', 'staff_title.staff_title_id', 'staffs.staff_title_id')
            ->where($this->table . '.salary_staff_id', $salaryStaffId)
            ->first();
    }

    /**
     * Lấy danh sách hoa hồng bán hàng
     * @param array $filter
     */
    public function getSalaryCommission($filter = [])
    {
        $oSelect = $this
            ->join("salary_staff_detail", "{$this->table}.salary_staff_id", "salary_staff.salary_staff_id")
            ->join('staffs', 'staffs.staff_id', $this->table . '.staff_id')
            ->join('contracts', 'contracts.contract_id', $this->table . '.contract_id')
            ->leftJoin('staff_title', 'staff_title.staff_title_id', 'staffs.staff_title_id')
            ->leftJoin('salary_commission_config', 'salary_commission_config.department_id', $this->table . '.department_id')
            ->leftJoin('ticket', 'ticket.ticket_code', $this->table . '.ticket_code')
            ->select(
                $this->table . '.staff_code',
                $this->table . '.staff_name',
                $this->table . '.department_name',
                'staff_title.staff_title_name',
                'contracts.staff_title_name',
                $this->table . '.salary',
                $this->table . '.total_revenue',
                $this->table . '.total_commission',
                $this->table . '.total_kpi',
                $this->table . '.total_allowance',
                $this->table . '.plus',
                $this->table . '.minus',
                $this->table . '.total'
            );

//        Kì lương
        if (isset($filter['salary_id'])) {
            $oSelect = $oSelect->where($this->table . '.salary_id', $filter['salary_id']);
        }

//        Lọc theo phòng ban
        if (isset($filter['department_id'])) {
            $oSelect = $oSelect->where($this->table . '.department_id', $filter['department_id']);
        }

//        Lọc theo nhân viên
        if (isset($filter['staff_id'])) {
            $oSelect = $oSelect->where($this->table . '.staff_id', $filter['staff_id']);
        }
        // lọc theo loại phòng ban kỹ thuật hay kinh doanh
        if (isset($filter['type'])) {
            $oSelect = $oSelect->where('salary_commission_config.type_view', $filter['type']);
        }

        return $oSelect->orderBy($this->table . '.salary_staff_id', 'DESC')->get();

    }
}