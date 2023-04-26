<?php

namespace Modules\Salary\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SalaryTable
 * @package Modules\Salary\Models
 * @author VuND
 * @since 02/12/2021
 */
class StaffTable extends Model
{

    protected $table = "staffs";
    protected $primaryKey = "staff_id";

    public function getAllForSalary(){
        return $this
            ->select("{$this->table}.*", 'department_name')
            ->join('departments', 'departments.department_id', '=', 'staffs.department_id')
            ->where("{$this->table}.is_actived", 1)
            ->where("{$this->table}.is_deleted", 0)
            ->get();
    }

    /**
     * Lấy thông tin nhân viên theo mã
     */
    public function getInfoStaffByCode($staffCode){
        return $this
            ->select(
                $this->table.'.staff_id',
                $this->table.'.full_name',
                $this->table.'.department_id',
                $this->table.'.staff_title_id',
                'departments.department_name',
                'staff_title.staff_title_name'
            )
            ->leftJoin('departments','departments.department_id',$this->table.'.department_id')
            ->leftJoin('staff_title','staff_title.staff_title_id',$this->table.'.staff_title_id')
            ->where($this->table.'.staff_code',$staffCode)
            ->first();

    }

    public function getName(){
        $oSelect= self::select("staff_id","full_name")->where('is_deleted',0)->where('is_actived', '=', 1)->orderBy('full_name', 'asc')->get();
        return ($oSelect->pluck("full_name","staff_id")->toArray());
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }
}