<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/07/2022
 * Time: 16:00
 */

namespace Modules\Team\Models;


use Illuminate\Database\Eloquent\Model;

class StaffTitleTable extends Model
{
    protected $table = "staff_title";
    protected $primaryKey = "staff_title_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy option chức vụ
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "staff_title_id",
                "staff_title_name"
            )
            ->where("is_active", self::IS_ACTIVE)
            ->where("is_delete", self::NOT_DELETED)
            ->get();
    }
}