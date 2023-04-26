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

class StaffSalaryConfigTable extends Model
{
    // use ListTableTrait;
    protected $table = "staff_salary_config";
    protected $primaryKey = "staff_salary_config_id";
    protected $fillable = [
        "staff_salary_config_id",
        "staff_id",
        "staff_salary_type_code",
        "staff_salary_pay_period_code",
        "staff_salary_template_id",
        "staff_salary_unit_code",
        "payment_type",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];
    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * add 
     *
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->staff_salary_config_id;
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
     * get list 
     *
     * @return mixed
     */
    public function getList()
    {
        $ds = $this
            ->select(
                "staff_salary_config_id",
                "staff_id",
                "staff_salary_type_code",
                "staff_salary_pay_period_code",
                "staff_salary_unit_code",
                "payment_type",
                "created_by",
                "updated_by",
                "created_at",
                "updated_at"
            );

        return $ds->get();
    }

    /**
     * get detail
     *
     * @return mixed
     */
    public function getDetail($id)
    {
        $ds = $this
            ->select(
                "staff_salary_config_id",
                "staff_id",
                "staff_salary_type_code",
                "staff_salary_pay_period_code",
                "staff_salary_unit_code",
                "payment_type",
                "created_by",
                "updated_by",
                "created_at",
                "updated_at"
            )
            ->where('staff_salary_config_id','=', $id);
        return $ds->first();
    }

    /**
     * get detail by staffid
     *
     * @return mixed
     */
    public function getDetailByStaff($staffId)
    {
        $ds = $this
            ->select(
                "staff_salary_config_id",
                "staff_id",
                "staff_salary_type_code",
                "staff_salary_pay_period_code",
                "staff_salary_unit_code",
                "payment_type",
                "created_by",
                "updated_by",
                "created_at",
                "updated_at"
            )
            ->where('staff_id','=', $staffId);
        return $ds->first();
    }

    /**
     * get detail by staffid
     *
     * @return mixed
     */
    public function getListByPayPeriod($periodCode)
    {
        $ds = $this
            ->select(
                "{$this->table}.staff_salary_config_id",
                "{$this->table}.staff_id",
                "{$this->table}.staff_salary_type_code",
                "{$this->table}.staff_salary_pay_period_code",
                "{$this->table}.staff_salary_unit_code",
                "{$this->table}.payment_type",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at"
            )
            ->join("staffs as st", "st.staff_id", "=", "{$this->table}.staff_id")
            ->where('staff_salary_pay_period_code','=', $periodCode)
            ->where('st.is_actived', self::IS_ACTIVE)
            ->where('st.is_deleted', self::NOT_DELETED);
        return $ds->get();
    }
}