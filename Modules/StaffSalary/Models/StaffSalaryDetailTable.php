<?php

/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/18/22
 * Time: 5:48 PM
 */

namespace Modules\StaffSalary\Models;

use Illuminate\Database\Eloquent\Model;
// use MyCore\Models\Traits\ListTableTrait;
use Carbon\Carbon;

class StaffSalaryDetailTable extends Model
{
    // use ListTableTrait;
    protected $table = "staff_salary_detail";
    protected $primaryKey = "staff_salary_detail_id";
    protected $fillable = [
        "staff_salary_detail_id",
        "staff_salary_id",
        "staff_id",
        "staff_salary_type_code",
        "staff_salary_pay_period_code",
        "staff_salary_overtime",
        "staff_salary_bonus",
        "staff_salary_allowance",
        "staff_salary_main",
        "staff_salary_received",
        "staff_salary_minus",
        "staff_salary_status",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];
    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * add holiday
     *
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->staff_salary_detail_id;
    }

    /**
     * edit holiday
     *
     * @return mixed
     */
    public function edit($data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /**
     * delete 
     *
     * @return mixed
     */
    public function detele($staff_salary_id)
    {

        return $this->where('staff_salary_id', $staff_salary_id)->delete();
    }

    /**
     * get detail salary detail
     *
     * @return mixed
     */
    public function getListByStaffSalary($staffSalaryid)
    {
        $ds = $this
            ->select(
                "{$this->table}.staff_salary_detail_id",
                "{$this->table}.staff_salary_id",
                "{$this->table}.staff_id",
                "{$this->table}.staff_salary_type_code",
                "{$this->table}.staff_salary_pay_period_code",
                "{$this->table}.staff_salary_overtime",
                "{$this->table}.staff_salary_bonus",
                "{$this->table}.staff_salary_allowance",
                "{$this->table}.staff_salary_main",
                "{$this->table}.staff_salary_received",
                "{$this->table}.staff_salary_minus",
                "{$this->table}.staff_salary_status",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "st.full_name as staff_name",
                "br.branch_name",
                "departments.department_name as department_name"
            )
            ->leftJoin("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("branches as br", "br.branch_id", "=", "st.branch_id")
            ->leftJoin('departments', 'departments.department_id', '=', 'st.department_id')
            ->where('staff_salary_id', '=', $staffSalaryid);
        return $ds->get();
    }


    /**
     * get detail salary detail
     *
     * @return mixed
     */
    public function getDetail($id)
    {
        $ds = $this
            ->select(
                "staff_salary_detail_id",
                "staff_salary_id",
                "staff_id",
                "staff_salary_type_code",
                "staff_salary_pay_period_code",
                "staff_salary_overtime",
                "staff_salary_bonus",
                "staff_salary_allowance",
                "staff_salary_main",
                "staff_salary_received",
                "staff_salary_minus",
                "staff_salary_status",
                "created_by",
                "updated_by",
                "created_at",
                "updated_at"
            )
            ->where('staff_salary_detail_id', '=', $id);
        return $ds->first();
    }

    /**
     * get detail salary detail
     *
     * @return mixed
     */
    public function getDetailBySalary($id)
    {
        $ds = $this
            ->select(
                "{$this->table}.staff_salary_detail_id",
                "{$this->table}.staff_salary_id",
                "{$this->table}.staff_id",
                "{$this->table}.staff_salary_type_code",
                "{$this->table}.staff_salary_pay_period_code",
                "{$this->table}.staff_salary_overtime",
                "{$this->table}.staff_salary_bonus",
                "{$this->table}.staff_salary_allowance",
                "{$this->table}.staff_salary_main",
                "{$this->table}.staff_salary_received",
                "{$this->table}.staff_salary_minus",
                "{$this->table}.staff_salary_status",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at"
            )
            ->join("staff_salary as ss", "ss.staff_salary_id", "=", "{$this->table}.staff_salary_id")
            ->where("{$this->table}.staff_salary_id", '=', $id);
        return $ds->get();
    }

    /**
     * get detail salary detail
     *
     * @return mixed
     */
    public function getDetailByStaff($staffId, $staffSalaryId)
    {
        $ds = $this
            ->select(
                "{$this->table}.staff_salary_detail_id",
                "{$this->table}.staff_salary_id",
                "{$this->table}.staff_id",
                "{$this->table}.staff_salary_type_code",
                "{$this->table}.staff_salary_pay_period_code",
                "{$this->table}.staff_salary_overtime",
                "{$this->table}.staff_salary_bonus",
                "{$this->table}.staff_salary_allowance",
                "{$this->table}.staff_salary_main",
                "{$this->table}.staff_salary_received",
                "{$this->table}.staff_salary_minus",
                "{$this->table}.staff_salary_status",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "st.full_name as staff_name",
                "staff_avatar",
                "br.branch_name",
                "departments.department_name as department_name",
                "ss.start_date",
                "ss.end_date",
                "staff_salary_type.staff_salary_type_name",
                "staff_salary_pay_period_name",
                "ss.staff_salary_status",
                "staff_salary_unit_name",
            )
            ->join("staff_salary as ss", "ss.staff_salary_id", "=", "{$this->table}.staff_salary_id")
            ->leftJoin("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->join("branches as br", "br.branch_id", "=", "st.branch_id")
            ->join('departments', 'departments.department_id', '=', 'st.department_id')
            ->leftJoin("staff_salary_type", "staff_salary_type.staff_salary_type_code", "=", "ss.staff_salary_type_code")
            ->leftJoin("staff_salary_pay_period", "staff_salary_pay_period.staff_salary_pay_period_code", "=", "ss.staff_salary_pay_period_code")
            ->leftJoin("staff_salary_config", "{$this->table}.staff_id", "=", "staff_salary_config.staff_id")
            ->leftJoin("staff_salary_units", "staff_salary_units.staff_salary_unit_code", "=", "staff_salary_config.staff_salary_unit_code")
            ->where("{$this->table}.staff_id", '=', $staffId)
            ->where("{$this->table}.staff_salary_id", '=', $staffSalaryId);
        return $ds->first();
    }

    /**
     * get detail salary detail
     *
     * @return mixed
     */
    public function getListSalaryByStaff($staffId)
    {
        $ds = $this
            ->select(
                "{$this->table}.staff_salary_detail_id",
                "{$this->table}.staff_salary_id",
                "{$this->table}.staff_id",
                "{$this->table}.staff_salary_type_code",
                "{$this->table}.staff_salary_pay_period_code",
                "{$this->table}.staff_salary_overtime",
                "{$this->table}.staff_salary_bonus",
                "{$this->table}.staff_salary_allowance",
                "{$this->table}.staff_salary_main",
                "{$this->table}.staff_salary_received",
                "{$this->table}.staff_salary_minus",
                "{$this->table}.staff_salary_status",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "st.full_name as staff_name",
                "br.branch_name",
                "departments.department_name as department_name",
                "ss.start_date",
                "ss.end_date",
                "staff_salary_type.staff_salary_type_name",
                "staff_salary_pay_period_name",
                "ss.staff_salary_status"
            )
            ->join("staff_salary as ss", "ss.staff_salary_id", "=", "{$this->table}.staff_salary_id")
            ->leftJoin("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("branches as br", "br.branch_id", "=", "st.branch_id")
            ->leftJoin('departments', 'departments.department_id', '=', 'st.department_id')
            ->leftJoin("staff_salary_type", "staff_salary_type.staff_salary_type_code", "=", "ss.staff_salary_type_code")
            ->leftJoin("staff_salary_pay_period", "staff_salary_pay_period.staff_salary_pay_period_code", "=", "ss.staff_salary_pay_period_code")
            ->where("{$this->table}.staff_id", '=', $staffId);
        $ds->orderBy('created_at', 'DESC');
        // var_dump($ds->toSql());
        // die;
        return $ds->get();
    }

    /**
     * get detail salary detail
     *
     * @return mixed
     */
    public function getListReportBranch($staffId)
    {
        $ds = $this
            ->select(
                "{$this->table}.staff_salary_detail_id",
                "{$this->table}.staff_salary_id",
                "{$this->table}.staff_id",
                "{$this->table}.staff_salary_type_code",
                "{$this->table}.staff_salary_pay_period_code",
                "{$this->table}.staff_salary_overtime",
                "{$this->table}.staff_salary_bonus",
                "{$this->table}.staff_salary_allowance",
                "{$this->table}.staff_salary_main",
                "{$this->table}.staff_salary_received",
                "{$this->table}.staff_salary_minus",
                "{$this->table}.staff_salary_status",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "st.full_name as staff_name",
                "br.branch_name",
                "departments.department_name as department_name",
                "ss.start_date",
                "ss.end_date",
                "staff_salary_type.staff_salary_type_name",
                "staff_salary_pay_period_name",
                "ss.staff_salary_status"
            )
            ->join("staff_salary as ss", "ss.staff_salary_id", "=", "{$this->table}.staff_salary_id")
            ->leftJoin("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("branches as br", "br.branch_id", "=", "st.branch_id")
            ->leftJoin('departments', 'departments.department_id', '=', 'st.department_id')
            ->leftJoin("staff_salary_type", "staff_salary_type.staff_salary_type_code", "=", "ss.staff_salary_type_code")
            ->leftJoin("staff_salary_pay_period", "staff_salary_pay_period.staff_salary_pay_period_code", "=", "ss.staff_salary_pay_period_code")
            ->where("{$this->table}.staff_id", '=', $staffId);
        $ds->orderBy('created_at', 'DESC');
        // var_dump($ds->toSql());
        // die;
        return $ds->get();
    }
}