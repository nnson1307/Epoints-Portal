<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/08/2021
 * Time: 09:55
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class StaffTable extends Model
{
    protected $table = "staffs";
    protected $primaryKey = "staff_id";

    const IS_ACTIVED = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy option nhân viên
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "{$this->table}.staff_id",
                "{$this->table}.full_name as staff_name",
                "dpm.department_name",
                "st.staff_title_name",
                "{$this->table}.phone1 as phone"
            )
            ->leftJoin("departments as dpm", "dpm.department_id", "=", "{$this->table}.department_id")
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->where("{$this->table}.is_actived", self::IS_ACTIVED)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy thông tin nhân viên
     *
     * @param $staffId
     * @return mixed
     */
    public function getInfo($staffId)
    {
        return $this
            ->select(
                "{$this->table}.staff_id",
                "{$this->table}.full_name",
                "{$this->table}.phone1 as phone",
                "{$this->table}.email",
                "{$this->table}.address",
                "st.staff_title_name",
                "dp.department_name"
            )
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->leftJoin("departments as dp", "dp.department_id", "=", "{$this->table}.department_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->first();
    }

    public function getItem($id)
    {
        return $this
            ->select(
                'staffs.*',
                'departments.department_name as department_name',
                'branches.branch_name as branch_name',
                'staff_title.staff_title_name as staff_title_name',
                'staffs.user_name as account',
                'staffs.salt as salt',
                'staffs.full_name as name',
                'staffs.birthday as birthday',
                'staffs.gender as gender',
                'staffs.phone1 as phone1',
                'staffs.phone2 as phone2',
                'staffs.email as email',
                'staffs.facebook as facebook',
                'staffs.date_last_login as date_last_login',
                'staffs.is_admin as is_admin',
                'staffs.is_actived as is_actived',
                'staffs.staff_avatar as staff_avatar',
                'staffs.address as address',
                'staffs.salary as salary',
                'staffs.subsidize as subsidize',
                'staffs.commission_rate as commission_rate'
            )
            ->leftJoin('departments', 'departments.department_id', '=', 'staffs.department_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id')
            ->leftJoin('staff_title', 'staff_title.staff_title_id', '=', 'staffs.staff_title_id')
            ->where("{$this->table}.staff_id", $id)
            ->first();
    }

    /**
     * Lấy thông tin nhân viên bằng tên
     *
     * @param $staffName
     * @return mixed
     */
    public function getInfoByName($staffName)
    {
        return $this
            ->select(
                "{$this->table}.staff_id",
                "{$this->table}.full_name",
                "{$this->table}.phone1 as phone",
                "{$this->table}.email",
                "{$this->table}.address",
                "st.staff_title_name",
                "dp.department_name"
            )
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->leftJoin("departments as dp", "dp.department_id", "=", "{$this->table}.department_id")
            ->where("{$this->table}.full_name", $staffName)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->first();
    }
    public function getDepartmentByBranch($branchId = '')
    {
        $ds = $this->select(
            "departments.department_id",
            "departments.department_name"
        )
            ->leftJoin("departments", "departments.department_id", "staffs.department_id");
        if($branchId != ''){
            $ds->where("staffs.branch_id", $branchId);
        }
        $ds->groupBy("staffs.department_id");
        return $ds->get();
    }
    public function getStaffByDepartmentBranch($branchId = '', $departmentId = '')
    {
        $ds = $this->select(
            "staffs.staff_id",
            "staffs.full_name"
        )
            ->leftJoin("departments", "departments.department_id", "staffs.department_id");
        if($branchId != ''){
            $ds->where("staffs.branch_id", $branchId);
        }
        if($departmentId != ''){
            $ds->where("staffs.department_id", $departmentId);
        }
        return $ds->get();
    }
}