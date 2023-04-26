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

class StaffSalaryTable extends Model
{
    // use ListTableTrait;
    protected $table = "staff_salary";
    protected $primaryKey = "staff_salary_id";
    protected $fillable = [
        "staff_salary_id",
        "staff_salary_type_code",
        "staff_salary_pay_period_code",
        "staff_salary_days",
        "staff_salary_months",
        "staff_salary_years",
        "staff_salary_weeks",
        "start_date",
        "end_date",
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
        return $this->create($data)->staff_salary_id;
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
    public function getList($filters)
    {
        $ds = $this
            ->select(
                "{$this->table}.staff_salary_id",
                "{$this->table}.staff_salary_type_code",
                "{$this->table}.staff_salary_pay_period_code",
                "{$this->table}.staff_salary_days",
                "{$this->table}.staff_salary_months",
                "{$this->table}.staff_salary_years",
                "{$this->table}.staff_salary_weeks",
                "{$this->table}.staff_salary_status",
                "{$this->table}.start_date",
                "{$this->table}.end_date",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "staff_salary_type.staff_salary_type_name",
                "staff_salary_pay_period_name"
            )
        ->leftJoin("staff_salary_type", "staff_salary_type.staff_salary_type_code", "=", "{$this->table}.staff_salary_type_code")
        ->leftJoin("staff_salary_pay_period", "staff_salary_pay_period.staff_salary_pay_period_code", "=", "{$this->table}.staff_salary_pay_period_code");
        $ds->orderBy('created_at', 'DESC');
        $page    = (int) ($filters['page'] ?? 1);
        $display = (int) ($filters['perpage'] ?? 10);
        return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
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
                "staff_salary_id",
                "staff_salary_type_code",
                "staff_salary_pay_period_code",
                "staff_salary_days",
                "staff_salary_months",
                "staff_salary_years",
                "staff_salary_weeks",
                "staff_salary_status",
                "start_date",
                "end_date",
                "created_by",
                "updated_by",
                "created_at",
                "updated_at"
            )
            ->where('staff_salary_id','=', $id);
        return $ds->first();
    }

    /**
     * get detail
     *
     * @return mixed
     */
    public function getDetailByDate($start_date, $end_date)
    {
        $ds = $this
            ->select(
                "staff_salary_id",
                "staff_salary_type_code",
                "staff_salary_pay_period_code",
                "staff_salary_days",
                "staff_salary_months",
                "staff_salary_years",
                "staff_salary_weeks",
                "start_date",
                "end_date",
                "created_by",
                "updated_by",
                "created_at",
                "updated_at"
            )
            ->where('start_date','=', $start_date)
            ->where('end_date','=', $end_date);
        return $ds->first();
    }
}