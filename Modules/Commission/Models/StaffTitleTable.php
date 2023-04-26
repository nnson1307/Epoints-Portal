<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;

class StaffTitleTable extends Model
{
    protected $table      = 'staff_title';
    protected $primaryKey = 'staff_title_id';

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;


    /**
     * Lấy danh sách chức vụ
     */
    public function getListTitle()
    {
        return $this->select("{$this->table}.staff_title_id",
                            "{$this->table}.staff_title_name")
                    ->where("{$this->table}.is_active", self::IS_ACTIVE)
                    ->where("{$this->table}.is_delete", self::NOT_DELETED)
                    ->get();
    }
}
