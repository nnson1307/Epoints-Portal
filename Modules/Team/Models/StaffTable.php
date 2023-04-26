<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/07/2022
 * Time: 16:05
 */

namespace Modules\Team\Models;


use Illuminate\Database\Eloquent\Model;

class StaffTable extends Model
{
    protected $table = "staffs";
    protected $primaryKey = "staff_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy option nhân viên theo chức vụ
     *
     * @param $titleId
     * @return mixed
     */
    public function getOptionByTitle($titleId)
    {
        return $this
            ->select(
                "staff_id",
                "full_name"
            )
            ->where("staff_title_id", $titleId)
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}