<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/24/2018
 * Time: 10:20 AM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class DepartmentTable extends Model
{
    use ListTableTrait;
    protected $table = 'departments';
    protected $primaryKey = 'department_id';

    protected $fillable = [
        'department_id',
        'department_name',
        'is_inactive',
        'is_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'slug',
        'branch_id',
        'staff_title_id',
        'staff_id'
    ];

    const NOT_DELETED = 0;

    /**
     * Danh sách phòng ban
     *
     * @return mixed
     */
    protected function _getList()
    {
        $ds = $this
            ->select(
                "{$this->table}.department_id",
                "{$this->table}.department_name",
                "{$this->table}.is_inactive",
                "{$this->table}.created_at",
                "br.branch_name",
                "tt.staff_title_name",
                "sf.full_name as staff_name"
            )
            ->leftJoin("branches as br", "br.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("staff_title as tt", "tt.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->leftJoin("staffs as sf", function ($join) {
                $join->on("sf.staff_id", "=", "{$this->table}.staff_id")
                    ->on("sf.staff_title_id", "=", "{$this->table}.staff_title_id");
            })
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy($this->primaryKey, "desc");

        return $ds;
    }

    public function getAll() {
        $ds = $this
            ->select(
                "{$this->table}.department_id",
                "{$this->table}.department_name",
                "{$this->table}.is_inactive",
                "{$this->table}.created_at"
            )
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy($this->primaryKey, "desc");

        return $ds;
    }

    /**
     * Insert department to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $oStaffDepartment = $this->create($data);
        return $oStaffDepartment->id;
    }

    /**
     * Edit department to database
     *
     * @param array $data , $id
     * @return number
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /**
     * Remove department to database
     *
     * @param number $id
     */
    public function remove($id)
    {
        $this->where($this->primaryKey, $id)->update(['is_deleted' => 1]);
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }
    /*
     * function get
     */
    public function getStaffDepartmentOption()
    {
        return $this->select('department_id', 'department_name')->where('is_deleted', 0)->get()->toArray();
    }
    /*
     * check unique department
     */
    public function check($name)
    {
        return $this->where('slug', str_slug($name))->where('is_deleted', 0)->first();
    }
    /*
    * check unique department edit
    */
    public function checkEdit($id, $name)
    {
        return $this->where('department_id', '<>', $id)->where('slug', str_slug($name))->where('is_deleted', 0)->first();
    }
    /*
     * test is deleted
     */
    public function testIsDeleted($name)
    {
        return $this->where('slug', str_slug($name))->where('is_deleted', 1)->first();
    }
    /*
     * edit by department name
     */
    public function editByName($name)
    {
        return $this->where('slug', str_slug($name))->update(['is_deleted' => 0]);
    }

    /**
     * lấy phòng ban theo điều kiện whereIn
     * @param array $listIdDepartment
     * @return mixed
     */

    public function getListCondition($listIdDepartment)
    {
        return $this->whereIn("department_id", $listIdDepartment)->get();
    }
}
