<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;

class StaffTypeTable extends Model
{
    protected $table      = 'staff_type';
    protected $primaryKey = 'staff_type_id';


    /**
     * Lấy danh sách loại nhân viên
     */
    public function getListType()
    {
        return $this->select("{$this->table}.staff_type_id",
                            "{$this->table}.type_name")
                    ->get();
    }
}
