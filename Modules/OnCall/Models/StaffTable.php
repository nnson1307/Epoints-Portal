<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 28/07/2021
 * Time: 10:04
 */

namespace Modules\OnCall\Models;


use Illuminate\Database\Eloquent\Model;

class StaffTable extends Model
{
    protected $table = "staffs";
    protected $primaryKey = "staff_id";

    const IS_ACTIVED = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy tất cả nhân viên
     *
     * @return mixed
     */
    public function getStaff()
    {
        return $this
            ->select(
                "staff_id",
                "full_name"
            )
            ->where("is_actived", self::IS_ACTIVED)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}