<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/09/2021
 * Time: 09:48
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class UnitTable extends Model
{
    protected $table = "units";
    protected $primaryKey = "unit_id";

    const NOT_DELETED = 0;
    const IS_ACTIVE = 1;

    /**
     * Lấy option đơn vị tính
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "unit_id",
                "name",
                "is_standard"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}