<?php

namespace Modules\ManagerWork\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class StaffTableNew extends Model
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
            ->leftJoin("manage_project_staff", "manage_project_staff.staff_id", "=", "{$this->table}.staff_id")
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.staff_id", "desc");

        if (isset($filter['list_staff'])) {
            $ds->whereIn("{$this->table}.staff_id", $filter['list_staff']);
        }

        if (isset($filter['manage_project_id'])) {
            $ds->where("manage_project_staff.manage_project_id", $filter['manage_project_id']);
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

        //Nhập thông tin tìm kiếm
        if (isset($filter['search']) && $filter['search'] != null) {
            $search = $filter['search'];

            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.full_name", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.email", 'like', '%' . $search . '%');
            });
        }


        unset($filter['staff_id'], $filter['list_staff'], $filter['staff_have_schedule'], $filter['department_id'], $filter['shift_id'], $filter['date_object'], $filter['branch_object'], $filter['branch_id'], $filter['years'],$filter['manage_project_id']);

        return $ds->groupBy($this->table.'.staff_id');
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
                "{$this->table}.full_name"
            )
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.staff_id", $staffId)
            ->first();
    }
}