<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionAllocation extends Model
{
    protected $table = 'commission_allocation';
    protected $primaryKey = 'commission_allocation_id';
    protected $fillable = [
        'commission_allocation_id',
        'staff_id',
        'commission_id',
        'commission_coefficient',
        'priority'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;


    /**
     * Lưu phân bổ hoa hồng
     */
    public function saveCommissioAllocation($data)
    {
        return $this->insert($data);
    }

    /**
     * Lấy danh sách nhân viên theo hoa hồng
     */
    public function getStaffByCommission($id)
    {
        return $this
            ->select(
                's.full_name',
                'b.branch_name',
                'd.department_name',
                "{$this->table}.commission_coefficient"
            )
            ->where("{$this->table}.commission_id", $id)
            ->leftJoin('staffs as s', 's.staff_id', '=', "{$this->table}.staff_id")
            ->leftJoin('branches as b', 'b.branch_id', '=', 's.branch_id')
            ->leftJoin('departments as d', 'd.department_id', '=', 's.department_id')
            ->get()
            ->toArray();
    }

    /**
     * Xóa cứng phân bổ hoa hồng theo id hoa hồng
     */
    public function removeAllocation($id)
    {
        return $this->where("{$this->table}.commission_id", $id)
            ->delete();
    }

    /**
     * Lấy hoa hồng được phân bổ cho nhân viên
     *
     * @param $idStaff
     * @return mixed
     */
    public function getAllocationByStaff($idStaff)
    {
        return $this
            ->select(
                "{$this->table}.commission_allocation_id",
                "{$this->table}.commission_id",
                "{$this->table}.commission_coefficient",
                "c.commission_name",
                "c.commission_type",
                "c.start_effect_time",
                "c.apply_time"
            )
            ->join("commission as c", "c.commission_id", "=", "{$this->table}.commission_id")
            ->where("{$this->table}.staff_id", $idStaff)
            ->where("c.status", self::IS_ACTIVE)
            ->where("c.is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Xoá hoa hồng được phân bổ cho nhân viên
     *
     * @param $idStaff
     * @return mixed
     */
    public function removeAllocationByStaff($idStaff)
    {
        return $this->where("staff_id", $idStaff)->delete();
    }
}
