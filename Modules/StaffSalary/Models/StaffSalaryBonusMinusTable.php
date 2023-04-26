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

class StaffSalaryBonusMinusTable extends Model
{
    // use ListTableTrait;
    protected $table = "staff_salary_bonus_minus";
    protected $primaryKey = "staff_salary_bonus_minus_id";
    protected $fillable = [
        "staff_salary_bonus_minus_id",
        "staff_id",
        "salary_bonus_minus_id",
        "staff_salary_bonus_minus_num",
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
        return $this->create($data)->staff_salary_bonus_minus_id;
    }

    /**
     * edit
     *
     * @return mixed
     */
    public function edit($data, $id){
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /**
     * delete by staff
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
                "staff_salary_bonus_minus_id",
                "staff_id",
                "salary_bonus_minus_id",
                "staff_salary_bonus_minus_num",
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
                "{$this->table}.staff_salary_bonus_minus_id",
                "{$this->table}.staff_id",
                "{$this->table}.salary_bonus_minus_id",
                "{$this->table}.staff_salary_bonus_minus_num",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "sb.salary_bonus_minus_type",
                "sb.salary_bonus_minus_name"
            )
            ->leftJoin("salary_bonus_minus as sb", "sb.salary_bonus_minus_id", "=", "{$this->table}.salary_bonus_minus_id")
            ->where('staff_id','=', $staffId);
        return $ds->get();
    }
}