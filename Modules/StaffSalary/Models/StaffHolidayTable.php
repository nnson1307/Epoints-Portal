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

class StaffHolidayTable extends Model
{
    // use ListTableTrait;
    protected $table = "staff_holiday";
    protected $primaryKey = "staff_holiday_id";
    protected $fillable = [
        "staff_holiday_id",
        "staff_holiday_title",
        "staff_holiday_start_date",
        "staff_holiday_end_date",
        "staff_holiday_number",
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
        return $this->create($data)->staff_holiday_id;
    }

    /**
     * edit holiday
     *
     * @return mixed
     */
    public function edit($data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /**
     * delete
     *
     * @return mixed
     */
    public function deleteById($id)
    {
        return $this->where('staff_holiday_id', $id)->delete();
    }

    /**
     * get list holiday
     *
     * @return mixed
     */
    public function _getList($filters)
    {
        $ds = $this
            ->select(
                "{$this->table}.staff_holiday_id",
                "{$this->table}.staff_holiday_title",
                "{$this->table}.staff_holiday_start_date",
                "{$this->table}.staff_holiday_end_date",
                "{$this->table}.staff_holiday_number",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "st.full_name as staff_name"
            )
            ->leftJoin("staffs as st", "st.staff_id", "=", "{$this->table}.created_by");
        if (isset($filters['search']) != "") {
            $search = $filters['search'];
            $ds->where("{$this->table}.staff_holiday_title", 'like', '%' . $search . '%');
        }
        $ds->orderBy('created_at', 'DESC');
        $page    = (int) ($filters['page'] ?? 1);
        $display = (int) ($filters['perpage'] ?? 10);
        return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
    /**
     * get detail holiday
     *
     * @return mixed
     */
    public function getDetail($id)
    {
        $ds = $this
            ->select(
                "{$this->table}.staff_holiday_id",
                "{$this->table}.staff_holiday_title",
                "{$this->table}.staff_holiday_start_date",
                "{$this->table}.staff_holiday_end_date",
                "{$this->table}.staff_holiday_number",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at"
            )
            ->where('staff_holiday_id', '=', $id);
        return $ds->first();
    }

    /**
     * get detail holiday
     *
     * @return mixed
     */
    public function getHolidayByDate($startDate)
    {
        $ds = $this
            ->select(
                "{$this->table}.staff_holiday_id",
                "{$this->table}.staff_holiday_title",
                "{$this->table}.staff_holiday_start_date",
                "{$this->table}.staff_holiday_end_date",
                "{$this->table}.staff_holiday_number",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.created_at",
                "{$this->table}.updated_at"
            )
            ->where('staff_holiday_start_date', '<=', $startDate)
            ->where('staff_holiday_end_date', '>=', $startDate);
        return $ds->get();
    }
}
