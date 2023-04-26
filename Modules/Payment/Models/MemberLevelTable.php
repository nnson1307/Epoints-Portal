<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class MemberLevelTable extends Model
{
    protected $table = "member_levels";
    protected $primaryKey = "member_level_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy cấu hình hạng thành viên
     *
     * @return mixed
     */
    public function getMemberLevel()
    {
        return $this
            ->select(
                "member_level_id",
                "name",
                "code",
                "point"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}