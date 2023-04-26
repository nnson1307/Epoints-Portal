<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/07/2022
 * Time: 16:10
 */

namespace Modules\Team\Models;


use Illuminate\Database\Eloquent\Model;

class DepartmentTable extends Model
{
    protected $table = "departments";
    protected $primaryKey = "department_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy option phòng ban
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "department_id",
                "department_name"
            )
            ->where("is_inactive", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}