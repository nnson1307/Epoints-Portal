<?php

namespace Modules\TimeOffDays\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class StaffsTable extends Model
{
    use ListTableTrait;
    protected $table = 'staffs';
    protected $primaryKey = 'staff_id';
    protected $fillable = [
        'staff_id',
        'full_name',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Danh sách hoa hồng nhân viên
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $select = $this
            ->select(
                "{$this->table}.staff_id",
                "{$this->table}.full_name",
                "{$this->table}.is_deleted",
                "{$this->table}.created_at"
            )
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.staff_id", "desc");

        

        // filter ngày tạo
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        return $select;
    }

    /**
     * Thêm loại thông tin kèm theo
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Cập nhật loại thông tin kèm theo
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function edit($data, $id)
    {
        return $this->where("{$this->primaryKey}", $id)->update($data);
    }

    /**
     * Chi tiết
     *
     * @param $id
     * @return mixed
     */
    public function getDetail($id)
    {
        $oSelect = $this
            ->select(
                $this->table.'.staff_id',
                $this->table.'.full_name',
                $this->table.'.staff_avatar',
                $this->table.'.staff_title_id',
                'st.staff_title_name as staff_title',
                'dep.department_name',
                'st.is_manager',
                "{$this->table}.department_id"
            )
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->leftJoin("departments as dep", "dep.department_id", "=", "{$this->table}.department_id");
            $oSelect->where("{$this->table}.staff_id", $id);
        return $oSelect->first();
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }

    /**
     * List
     *
     * @param $input array id
     * @return mixed
     */
    public function getListById($input)
    {
        return $this->select(
            "{$this->table}.staff_id",
            "{$this->table}.full_name",
            "{$this->table}.staff_avatar",
            "{$this->table}.is_actived",
            "{$this->table}.is_deleted",
            "st.staff_title_name as staff_title",
        )
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->whereIn("{$this->primaryKey}", $input)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Get danh sách người duyệt
     *
     * @param array $data
     * @return mixed
     */

     public function getStaffApproveInfo($staffTitleId)
     {
         $oSelect = $this
             ->select(
                 $this->table . '.staff_id',
                 $this->table . '.full_name',
                 $this->table . '.staff_avatar',
                 $this->table . '.staff_title_id',
                 'st.staff_title_name as staff_title',
                 'dep.department_name',
             )
             ->leftJoin("staff_title as st", "st.staff_title_id", "=", "{$this->table}.staff_title_id")
             ->leftJoin("departments as dep", "dep.department_id", "=", "{$this->table}.department_id")
             // ->where("s.department_id", "=",  Auth()->user()->department_id ?? 1)
             ->where("st.staff_title_id", "=",  $staffTitleId);
 
         return $oSelect->first();
     }

     /**
     * Chi tiết
     *
     * @param $id
     * @return mixed
     */
    public function getListStaffDepartment($departmentId)
    {
        $oSelect = $this
            ->select(
                $this->table.'.staff_id',
                $this->table.'.full_name',
                $this->table.'.staff_avatar',
                $this->table.'.staff_title_id'
            )
            ->where("{$this->table}.department_id", $departmentId)
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.is_actived", 1);

        return $oSelect->get();
    }

    public function getDetailStaffApproveInfo($staffId)
     {
         $oSelect = $this
             ->select(
                 $this->table . '.staff_id',
                 $this->table . '.full_name',
                 $this->table . '.staff_avatar',
                 $this->table . '.staff_title_id',
                 $this->table . '.department_id',
                 'st.staff_title_name as staff_title',
                 'dep.department_name',
             )
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "{$this->table}.staff_title_id")
             ->leftJoin("departments as dep", "dep.department_id", "=", "{$this->table}.department_id")
             ->where("{$this->table}.staff_id", "=",  $staffId);
 
         return $oSelect->first();
     }

     public function getListStaffApproveInfo($arrStaffs)
     {
         $oSelect = $this
             ->select(
                 $this->table . '.staff_id',
                 $this->table . '.full_name',
                 $this->table . '.staff_avatar',
                 $this->table . '.staff_title_id',
                 $this->table . '.department_id',
                 'st.staff_title_name as staff_title',
                 'dep.department_name',
             )
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "{$this->table}.staff_title_id")
             ->leftJoin("departments as dep", "dep.department_id", "=", "{$this->table}.department_id")
             ->whereIn("{$this->table}.staff_id",  $arrStaffs);
 
         return $oSelect->get();
     }

     /**
     * Chi tiết
     *
     * @param $id
     * @return mixed
     */
    public function getDetailApproveLevel1($departmentId)
    {
        $oSelect = $this
            ->select(
                $this->table.'.staff_id',
                $this->table.'.full_name',
                $this->table.'.staff_avatar',
                $this->table.'.staff_title_id',
                'st.staff_title_name as staff_title',
                'dep.department_name',
                'st.is_manager'
            )
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->leftJoin("departments as dep", "dep.department_id", "=", "{$this->table}.department_id");
            $oSelect->where("{$this->table}.department_id", $departmentId);
            $oSelect->where("st.is_manager", 1);
        return $oSelect->first();
    }

}