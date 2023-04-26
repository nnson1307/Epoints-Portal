<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/10/2022
 * Time: 17:07
 */

namespace Modules\StaffSalary\Models;


use Illuminate\Database\Eloquent\Model;

class StaffSalaryUnitTable extends Model
{
    protected $table = "staff_salary_units";
    protected $primaryKey = "staff_salary_unit_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy option đơn vị tiền tệ
     *
     * @return mixed
     */
    public function getUnit()
    {
        return $this
            ->select(
                "staff_salary_unit_id",
                "staff_salary_unit_code",
                "staff_salary_unit_name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->orderBy("staff_salary_unit_id", "desc")
            ->get();
    }
}