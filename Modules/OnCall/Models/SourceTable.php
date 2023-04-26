<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 29/07/2021
 * Time: 14:01
 */

namespace Modules\OnCall\Models;


use Illuminate\Database\Eloquent\Model;

class SourceTable extends Model
{
    protected $table = "oc_sources";
    protected $primaryKey = "source_id";

    const IS_ACTIVED = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy option nguồn cuộc gọi
     *
     * @return mixed
     */
    public function getOption()
    {
        $lang = app()->getLocale();

        return $this
            ->select(
                "source_code",
                "source_name_$lang as source_name"
            )
            ->where("is_actived", self::IS_ACTIVED)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}