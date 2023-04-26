<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 12:04
 */

namespace Modules\Shift\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class StaffTable extends Model
{
    use ListTableTrait;
    protected $table = "staffs";
    protected $primaryKey = "staff_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy danh sách nhân viên
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.staff_id",
                "{$this->table}.full_name",
                "br.branch_name",
                "br.branch_id",
                "dp.department_name",
                "dp.department_id",
                "{$this->table}.staff_avatar"
            )
            ->leftJoin("branches as br", "br.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("departments as dp", "dp.department_id", "=", "{$this->table}.department_id")
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.staff_id", "desc");

        if (isset($filter['list_staff'])) {
            $ds->whereIn("{$this->table}.staff_id", $filter['list_staff']);
        }

        if (isset($filter['staff_have_schedule'])) {
            $ds->whereNotIn("{$this->table}.staff_id", $filter['staff_have_schedule']);
        }

        //Filter theo nhân viên
        if (isset($filter['staff_id']) && $filter['staff_id'] != null) {
            $ds->where("{$this->table}.staff_id", $filter['staff_id']);
        }

        //Filter theo phỏng ban
        if (isset($filter['department_id']) && $filter['department_id'] != null) {
            $ds->where("{$this->table}.department_id", $filter['department_id']);
        }

        //Filter theo chi nhánh
        if (isset($filter['branch_id']) && $filter['branch_id'] != null) {
            $ds->where("{$this->table}.branch_id", $filter['branch_id']);
        }

        unset($filter['staff_id'], $filter['list_staff'], $filter['staff_have_schedule'], $filter['department_id'], $filter['shift_id'], $filter['date_object'], $filter['branch_object'], $filter['branch_id'], $filter['years']);

        return $ds;
    }

    /**
     * Lấy option nhân viên
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "staff_id",
                "full_name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.staff_id", "desc")
            ->get();
    }

    public function getDetail($staffId)
    {
        return $this
            ->select(
                "{$this->table}.staff_id",
                "{$this->table}.full_name",
                "c.staff_salary_type_code"
            )
            ->leftJoin("staff_salary_config as c", "c.staff_id", "=", "{$this->table}.staff_id")
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.staff_id", $staffId)
            ->first();
    }
}