<?php

namespace Modules\Kpi\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DepartmentsTable
 * @author HaoNMN
 * @since Jul 2022
 */
class DepartmentsTable extends Model
{
    protected $table    = 'departments';
    protected $primaryKey = 'department_id';
    protected $fillable = [
        'department_id',
        'department_name'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy danh sách phòng ban
     * @param $branchId
     * @return array
     */
    public function getDepartment($branchId)
    {
        $oSelect = $this->select(
                        "{$this->table}.department_id",
                        "{$this->table}.department_name"
                    )
                    ->where("{$this->table}.is_inactive", self::IS_ACTIVE)
                    ->where("{$this->table}.is_deleted", self::NOT_DELETED);

        if ($branchId != null) {
//            $oSelect->where("{$this->table}.branch_id", $branchId);
        }

        return $oSelect->get()->toArray();
    }

    /**
     * lấy danh sách phòng ban theo id Phòng ban nếu có
     * @param $departmentId
     */
    public function getListDepartment($departmentId = null){
        $oSelect = $this->select(
            "{$this->table}.department_id",
            "{$this->table}.department_name"
        )
            ->where("{$this->table}.is_inactive", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED);

        if ($departmentId != null) {
//            $oSelect->where("{$this->table}.department_id", $departmentId);
        }

        return $oSelect->get();
    }
}
