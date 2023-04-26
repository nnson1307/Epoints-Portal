<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/10/2022
 * Time: 14:32
 */

namespace Modules\StaffSalary\Models;


use Illuminate\Database\Eloquent\Model;

class StaffSalaryTemplateAllowanceTable extends Model
{
    protected $table = "staff_salary_template_allowance";
    protected $primaryKey = "staff_salary_template_allowance_id";

    /**
     * Lấy phụ cấp của mẫu lương
     *
     * @param $templateId
     * @return mixed
     */
    public function getTemplateAllowance($templateId)
    {
        return $this
            ->select(
                "{$this->table}.staff_salary_template_allowance_id",
                "{$this->table}.staff_salary_template_id",
                "{$this->table}.staff_salary_allowance_type",
                "{$this->table}.salary_allowance_id",
                "{$this->table}.staff_salary_allowance_num",
                "{$this->table}.staff_salary_allowance_tax",
                "a.salary_allowance_name"
            )
            ->join("salary_allowance as a", "a.salary_allowance_id", "=", "{$this->table}.salary_allowance_id")
            ->where("{$this->table}.staff_salary_template_id", $templateId)
            ->get();
    }

    /**
     * Xoá phụ cấp của mẫu lương
     *
     * @param $templateId
     * @return mixed
     */
    public function removeTemplateAllowance($templateId)
    {
        return $this->where("staff_salary_template_id", $templateId)->delete();
    }
}