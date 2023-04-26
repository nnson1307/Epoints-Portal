<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/10/2022
 * Time: 14:32
 */

namespace Modules\StaffSalary\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class StaffSalaryTemplateTable extends Model
{
    use ListTableTrait;
    protected $table = "staff_salary_templates";
    protected $primaryKey = "staff_salary_template_id";
    protected $fillable = [
        "staff_salary_template_id",
        "staff_salary_template_code",
        "staff_salary_template_name",
        "staff_salary_type_code",
        "staff_salary_pay_period_code",
        "staff_salary_unit_code",
        "payment_type",
        "salary_default",
        "salary_saturday_default",
        "salary_saturday_default_type",
        "salary_sunday_default",
        "salary_sunday_default_type",
        "salary_holiday_default",
        "salary_holiday_default_type",
        "is_overtime",
        "salary_overtime",
        "salary_saturday_overtime",
        "salary_saturday_overtime_type",
        "salary_sunday_overtime",
        "salary_sunday_overtime_type",
        "salary_holiday_overtime",
        "salary_holiday_overtime_type",
        "is_allowance",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETED = 0;

    /**
     * Danh sách mẫu áp dụng lương
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.staff_salary_template_id",
                "{$this->table}.staff_salary_template_code",
                "{$this->table}.staff_salary_template_name",
                "t.staff_salary_type_name",
                "p.staff_salary_pay_period_name",
                "u.staff_salary_unit_name",
                "{$this->table}.payment_type",
                "{$this->table}.salary_default",
                "{$this->table}.is_actived",
                "{$this->table}.created_at"
            )
            ->join("staff_salary_type as t", "t.staff_salary_type_code", "=", "{$this->table}.staff_salary_type_code")
            ->join("staff_salary_pay_period as p", "p.staff_salary_pay_period_code", "=", "{$this->table}.staff_salary_pay_period_code")
            ->join("staff_salary_units as u", "u.staff_salary_unit_code", "=", "{$this->table}.staff_salary_unit_code")
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.staff_salary_template_id", "desc");

        // filter tên
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.staff_salary_template_name", 'like', '%' . $search . '%');
            });
        }

        return $ds;
    }

    /**
     * Thêm mẫu lương
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->staff_salary_template_id;
    }

    /**
     * Chỉnh sửa mẫu lương
     *
     * @param array $data
     * @param $templateId
     * @return mixed
     */
    public function edit(array $data, $templateId)
    {
        return $this->where("staff_salary_template_id", $templateId)->update($data);
    }

    /**
     * Lấy thông tin mẫu lương
     *
     * @param $templateId
     * @return mixed
     */
    public function getInfo($templateId)
    {
        return $this
            ->select(
                "{$this->table}.staff_salary_template_id",
                "{$this->table}.staff_salary_template_code",
                "{$this->table}.staff_salary_template_name",
                "{$this->table}.staff_salary_type_code",
                "{$this->table}.staff_salary_pay_period_code",
                "{$this->table}.staff_salary_unit_code",
                "{$this->table}.payment_type",
                "{$this->table}.salary_default",
                "{$this->table}.salary_saturday_default",
                "{$this->table}.salary_saturday_default_type",
                "{$this->table}.salary_sunday_default",
                "{$this->table}.salary_sunday_default_type",
                "{$this->table}.salary_holiday_default",
                "{$this->table}.salary_holiday_default_type",
                "{$this->table}.is_overtime",
                "{$this->table}.salary_overtime",
                "{$this->table}.salary_saturday_overtime",
                "{$this->table}.salary_saturday_overtime_type",
                "{$this->table}.salary_sunday_overtime",
                "{$this->table}.salary_sunday_overtime_type",
                "{$this->table}.salary_holiday_overtime",
                "{$this->table}.salary_holiday_overtime_type",
                "{$this->table}.is_allowance",
                "{$this->table}.is_actived"
            )
            ->where("{$this->table}.staff_salary_template_id", $templateId)
            ->first();
    }
}