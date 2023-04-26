<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentsTable extends Model
{
    protected $table = 'departments';
    protected $primaryKey = 'department_id';

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy danh sách phòng ban
     */
    public function getListDepartment()
    {
        return $this
            ->select(
                "{$this->table}.department_id",
                "{$this->table}.department_name"
            )
            ->where("{$this->table}.is_inactive", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->get();
    }
}
