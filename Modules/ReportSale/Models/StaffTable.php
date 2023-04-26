<?php

namespace Modules\ReportSale\Models;

use Illuminate\Database\Eloquent\Model;

class StaffTable extends Model
{
    protected $table = "staffs";
    protected $primaryKey = "staff_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy option nhân viên
     *
     * @return mixed
     */
    public function getOption($idStaff = null)
    {
        $ds = $this
            ->select(
                "staff_id",
                "full_name as staff_name"
            )
            ->where("is_deleted", self::NOT_DELETED);

        if (Auth()->user()->is_admin != 1) {
            $ds->where('staff_id', Auth()->id());
        }

        if ($idStaff != null) {
            $ds->where("{$this->table}.staff_id", $idStaff);
        }

        return $ds->get();
    }
}