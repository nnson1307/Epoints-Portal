<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class TeamTable extends Model
{
    protected $table = "team";
    protected $primaryKey = "team_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy nhóm của phòng ban
     *
     * @param $departmentId
     * @return mixed
     */
    public function getTeamByDepartment($departmentId)
    {
        return $this
            ->select(
                "team_id",
                "team_name",
                "team_code"
            )
//            ->where("department_id", $departmentId)
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}