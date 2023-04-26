<?php

namespace Modules\ManagerProject\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProjectStaffTable extends Model
{
    protected $table = "manage_project_staff";
    protected $primaryKey = "manage_project_staff_id";
    protected $fillable = [];

    public function getManager($filter = []){
        $oSelect = $this
            ->select(
                "{$this->table}.staff_id as manager_id",
                "{$this->table}.manage_project_role_id as role_id",
                "staffs.full_name as manager_name",
//                "staffs.department_id",
//                "staffs.staff_title_id",
//                "staffs.phone1 as phone",
//                "staffs.email",
//                "staffs.staff_avatar",
//                "staffs.address",
//                "staffs.staff_type",
                "{$this->table}.manage_project_id as project_id",
                "manage_project.manage_project_status_id as project_status_id",
                "staffs.department_id",
                "departments.department_name"
            )
            ->where("{$this->table}.manage_project_role_id", 1)
            ->leftJoin("staffs","manage_project_staff.staff_id","staffs.staff_id")
            ->leftJoin("departments","staffs.department_id","departments.department_id")
            ->leftJoin("manage_project","manage_project_staff.manage_project_id","manage_project.manage_project_id");

        if(isset($filter['arrManagerId']) && $filter['arrManagerId'] != null && $filter['arrManagerId'] != []){
            $oSelect->whereIn( "{$this->table}.staff_id" , $filter['arrManagerId']);
        }
        if(isset($filter['arrProjectId']) && $filter['arrProjectId'] != null && $filter['arrProjectId'] != []){
            $oSelect->whereIn( "{$this->table}.manage_project_id" , $filter['arrProjectId']);
        }
        return $oSelect->get();
    }
    public function getStaffProject($filter = []){
        $oSelect = $this
            ->select(
                "{$this->table}.staff_id",
                "{$this->table}.manage_project_role_id as role_id",
                "staffs.full_name",
                "{$this->table}.manage_project_id as project_id",
                "manage_project.manage_project_status_id as project_status_id",
                "staffs.department_id",
                "departments.department_name"
            )
            ->leftJoin("staffs","manage_project_staff.staff_id","staffs.staff_id")
            ->leftJoin("departments","staffs.department_id","departments.department_id")
            ->leftJoin("manage_project","manage_project_staff.manage_project_id","manage_project.manage_project_id");
        if(isset($filter['arrIdProject']) && $filter['arrIdProject'] != null){
            $oSelect->whereIn( "{$this->table}.manage_project_id" , $filter['arrIdProject']);
        }
        if(isset($filter['manage_project_id']) && $filter['manage_project_id'] != null){
            $oSelect->where( "{$this->table}.manage_project_id" , $filter['manage_project_id']);
        }
        return $oSelect->get()->toArray();
    }
    public function getMemberProject($filter = []){
        $mSelect = $this
            ->select('manage_project_id', DB::raw('count(*) as total'))
            ->groupBy('manage_project_id');
        if(isset($filter['arrIdProject']) && $filter['arrIdProject'] != '' && $filter['arrIdProject']!= null ){
            $mSelect = $mSelect->whereIn("{$this->table}.manage_project_id",$filter['arrIdProject']);
        }
        if(isset($filter['manage_project_id']) && $filter['manage_project_id'] != '' && $filter['manage_project_id']!= null ){
            $mSelect = $mSelect->where("{$this->table}.manage_project_id",$filter['manage_project_id']);
        }
        return $mSelect->get()->toArray();
    }
}