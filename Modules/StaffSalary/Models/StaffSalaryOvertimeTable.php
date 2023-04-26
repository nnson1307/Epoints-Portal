<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/18/22
 * Time: 5:48 PM
 */

namespace Modules\StaffSalary\Models;

use Illuminate\Database\Eloquent\Model;
// use MyCore\Models\Traits\ListTableTrait;
use Carbon\Carbon;

class StaffSalaryOvertimeTable extends Model
{
    // use ListTableTrait;
    protected $table = "staff_salary_overtime";
    protected $primaryKey = "staff_salary_overtime_id";
    protected $fillable = [
        "staff_salary_overtime_id",
        "staff_id",
        "branch_id",
        "staff_salary_overtime_weekday",
        "staff_salary_overtime_holiday",
        "staff_salary_overtime_holiday_type",
        "staff_salary_overtime_saturday",
        "staff_salary_overtime_saturday_type",
        "staff_salary_overtime_sunday",
        "staff_salary_overtime_sunday_type",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];
    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * add holiday
     *
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->staff_salary_overtime_id;
    }

    /**
     * edit holiday
     *
     * @return mixed
     */
    public function edit($data, $id){
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /**
     * delete by staffid
     *
     * @return mixed
     */
    public function deleteByStaff($staffid){
        return $this->where('staff_id','=', $staffid)->delete();
    }
    /**
     * get detail salary detail
     *
     * @return mixed
     */
    public function getDetail($id)
    {
        $ds = $this
            ->select(
                "staff_salary_overtime_id",
                "staff_id",
                "branch_id",
                "staff_salary_overtime_weekday",
                "staff_salary_overtime_holiday",
                "staff_salary_overtime_holiday_type",
                "staff_salary_overtime_saturday",
                "staff_salary_overtime_saturday_type",
                "staff_salary_overtime_sunday",
                "staff_salary_overtime_sunday_type",
                "created_by",
                "updated_by",
                "created_at",
                "updated_at"
            )
            ->where('staff_salary_detail_id','=', $id);
        return $ds->first();
    }

    /**
     * get detail salary detail
     *
     * @return mixed
     */
    public function getDetailByStaff($staffId)
    {
        $ds = $this
            ->select(
                "staff_salary_overtime_id",
                "staff_id",
                "branch_id",
                "staff_salary_overtime_weekday",
                "staff_salary_overtime_holiday",
                "staff_salary_overtime_holiday_type",
                "staff_salary_overtime_saturday",
                "staff_salary_overtime_saturday_type",
                "staff_salary_overtime_sunday",
                "staff_salary_overtime_sunday_type",
                "created_by",
                "updated_by",
                "created_at",
                "updated_at"
            )
            ->where('staff_id','=', $staffId);
        return $ds->first();
    }
}