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

class StaffSalaryAttributeTable extends Model
{
    // use ListTableTrait;
    protected $table = "staff_salary_attribute";
    protected $primaryKey = "staff_salary_attribute_id";
    protected $fillable = [
        "staff_salary_attribute_id",
        "staff_salary_attribute_code",
        "staff_salary_attribute_value",
        "staff_salary_attribute_type",
        "staff_id",
        "branch_id",
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
        return $this->create($data)->staff_salary_attribute_id;
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
     * delete
     *
     * @return mixed
     */
    public function deleteByStaff($staffId){
        return $this->where('staff_id', $staffId)->delete();
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
                "staff_salary_attribute_id",
                "staff_salary_attribute_code",
                "staff_salary_attribute_value",
                "staff_salary_attribute_type",
                "staff_id",
                "branch_id",
                "created_by",
                "updated_by",
                "created_at",
                "updated_at"
            )
            ->where('staff_salary_attribute_id','=', $id);
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
                "staff_salary_attribute_id",
                "staff_salary_attribute_code",
                "staff_salary_attribute_value",
                "staff_salary_attribute_type",
                "staff_id",
                "branch_id",
                "created_by",
                "updated_by",
                "created_at",
                "updated_at"
            )
            ->where('staff_id','=', $staffId);
        return $ds->get();
    }
}