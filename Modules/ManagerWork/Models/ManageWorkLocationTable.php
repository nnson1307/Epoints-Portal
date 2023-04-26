<?php

namespace Modules\ManagerWork\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ManageWorkLocationTable extends Model
{
    protected $table = "manage_work_location";
    protected $primaryKey = "manage_work_location_id";

    const NOT_DELETED = 0;

    /**
     * Lấy vị trí của công việc
     *
     * @param $idWork
     * @return mixed
     */
    public function getLocationByWork($idWork)
    {
        $imageDefault = asset('/static/backend/images/image-user.png');

        return $this
            ->select(
                "{$this->table}.manage_work_location_id",
                "{$this->table}.manage_work_id",
                "{$this->table}.lat",
                "{$this->table}.lng",
                "{$this->table}.description",
                "{$this->table}.created_at",
                "s.full_name as staff_name",
                DB::raw("(CASE
                    WHEN  s.staff_avatar = '' THEN '$imageDefault'
                    WHEN  s.staff_avatar IS NULL THEN '$imageDefault'
                    ELSE  s.staff_avatar 
                    END
                ) as staff_avatar")
            )
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.staff_id")
            ->where("{$this->table}.manage_work_id", $idWork)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.manage_work_id", "desc")
            ->get();
    }
}