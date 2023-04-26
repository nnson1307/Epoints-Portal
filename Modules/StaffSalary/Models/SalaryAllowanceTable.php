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

class SalaryAllowanceTable extends Model
{
    // use ListTableTrait;
    protected $table = "salary_allowance";
    protected $primaryKey = "salary_allowance_id";
    protected $fillable = [
        "salary_allowance_id",
        "salary_allowance_name",
        "salary_allowance_num",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];
    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * add salary allowance
     *
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->salary_allowance_id;
    }

    /**
     * edit salary allowance
     *
     * @return mixed
     */
    public function edit($data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /**
     * get list salary allowance
     *
     * @return mixed
     */
    public function getList()
    {
        $ds = $this
            ->select(
                "{$this->table}.salary_allowance_id",
                "{$this->table}.salary_allowance_name",
                "{$this->table}.salary_allowance_num",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "st.full_name as staff_name"
            )
            ->leftJoin("staffs as st", "st.staff_id", "=", "{$this->table}.created_by");
        return $ds->get();
    }

    /**
     * get salary allowance
     *
     * @return mixed
     */
    public function getDetail($id)
    {
        $ds = $this
            ->select(
                "salary_allowance_id",
                "salary_allowance_name",
                "salary_allowance_num"
            )
            ->where('salary_allowance_id', '=', $id);
        return $ds->first();
    }

    /**
     * get list salary allowance
     *
     * @return mixed
     */
    public function getListForSalary()
    {
        $ds = $this
            ->select(
                "{$this->table}.salary_allowance_id",
                "{$this->table}.salary_allowance_name",
                "{$this->table}.salary_allowance_num",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at"
            );
        return $ds->get();
    }

}
