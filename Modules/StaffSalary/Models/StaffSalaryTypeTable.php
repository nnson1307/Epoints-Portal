<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/18/22
 * Time: 5:48 PM
 */

namespace Modules\StaffSalary\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StaffSalaryTypeTable extends Model
{
    // use ListTableTrait;
    protected $table = "staff_salary_type";
    protected $primaryKey = "staff_salary_type_id";
    protected $fillable = [
        "staff_salary_type_id",
        "staff_salary_type_name",
        "staff_salary_type_code"
    ];
    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * get list
     *
     * @return mixed
     */
    public function getList()
    {
        $ds = $this
            ->select(
                "staff_salary_type_id",
                "staff_salary_type_name",
                "staff_salary_type_code"
            );
        return $ds->get();
    }
}