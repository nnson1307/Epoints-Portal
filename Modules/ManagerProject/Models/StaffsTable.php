<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerProject\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class StaffsTable extends Model
{
    use ListTableTrait;
    protected $table = 'staffs';
    protected $primaryKey = 'staff_id';

    protected $fillable = [
        'staff_id', 'department_id', 'branch_id', 'staff_title_id', 'user_name', 'password', 'salt', 'full_name',
        'birthday', 'gender', 'phone1', 'phone2', 'email', 'facebook', 'date_last_login', 'is_admin', 'is_actived',
        'is_deleted', 'staff_avatar', 'address', 'created_by', 'updated_by', 'created_at', 'updated_at',
        'remember_token', 'is_master', 'staff_code', 'salary', 'subsidize', 'commission_rate', 'password_reset', 'date_password_reset',
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * lấy tất cả nhân viên
     * @return mixed
     */
    public function getAll($filter = [])
    {
        $oSelect = $this
            ->select(
                'staff_id',
                'full_name'
            )
            ->where('is_actived', 1)
            ->where('is_deleted', 0);

        if (isset($filter['branch_id'])) {
            $oSelect = $oSelect->where('branch_id', $filter['branch_id']);
        }

        if (isset($filter['arr_staff'])){
            $oSelect = $oSelect->whereIn('staff_id', $filter['arr_staff']);
        }

        if (isset($filter['not_arr_staff'])){
            $oSelect = $oSelect->whereNotIn('staff_id', $filter['not_arr_staff']);
        }

        return $oSelect->orderBy('staff_id', 'DESC')->get();
    }
    /**
     * Lấy các option nhan vien
     *
     * @return mixed
     */
    public function getOption()
    {
        $select = $this->select(
            "staff_id as accounting_id",
            "full_name as accounting_name"
        )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE);
        return $select->get();
    }


    /**
     * lấy danh sách nhân viên jobOverview
     */
    public function staffNoJob($data)
    {
        $oSelect = $this
            ->select(
                $this->table . '.staff_id',
                $this->table . '.full_name as staff_name',
                $this->table . '.staff_avatar',
                'manage_work.manage_work_id',
                'departments.department_name as role_name'
            )
            ->join('manage_work', 'manage_work.processor_id', $this->table . '.staff_id')
            ->join('departments', 'departments.department_id', $this->table . '.department_id')
            ->where($this->table . '.is_actived', 1)
            ->where($this->table . '.is_deleted', 0);

        if (isset($data['list_staff_no_started_work'])) {
            $oSelect = $oSelect->where('manage_work.manage_status_id', 1);
        }


        if (isset($data['from_date']) && isset($data['to_date'])) {
            $start = $data['from_date'];
            $end = $data['to_date'];
            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql->whereBetween('manage_work.date_start', [$start, $end])
                    ->orWhereBetween('manage_work.date_end', [$start, $end])
                    ->orWhere(function ($sql1) use ($start) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $start)
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });
        } else {
//            $oSelect = $oSelect
//                ->where('manage_work.date_start','<=', Carbon::now())
//                ->where('manage_work.date_end','>=', Carbon::now());
            $oSelect = $oSelect
                ->where(function ($sql) {
                    $sql->whereNull('manage_work.date_start')
                        ->where('manage_work.date_end', '>=', Carbon::now());
                })
                ->orWhere(function ($sql) {
                    $sql->where('manage_work.date_start', '<=', Carbon::now())
                        ->where('manage_work.date_end', '>=', Carbon::now());
                });
        }

        $oSelect = $this->permission($oSelect);

        return $oSelect->groupBy($this->table . '.staff_id')->get();
    }

    /**
     * Danh sách nhân viên chưa có việc làm
     * @param $arrIdStaff
     */
    public function getListStaffNoJob($arrIdStaff)
    {
        $oSelect = $this
            ->select(
                $this->table . '.staff_id',
                $this->table . '.full_name as staff_name',
                $this->table . '.staff_avatar',
                'departments.department_name as role_name'
            )
            ->join('departments', 'departments.department_id', $this->table . '.department_id')
            ->where($this->table . '.is_actived', 1)
            ->where($this->table . '.is_deleted', 0)
            ->whereNotIn($this->table . '.staff_id', $arrIdStaff);

        $oSelect = $this->permission($oSelect);


        return $oSelect->groupBy($this->table . '.staff_id')->get();
    }

    public function permission($oSelect)
    {
        $user = Auth::user();
        $userId = $user->staff_id;

        $dataRole = DB::table('map_role_group_staff')
            ->select('manage_role.role_group_id', 'is_all', 'is_branch', 'is_department', 'is_own')
            ->join('manage_role', 'manage_role.role_group_id', 'map_role_group_staff.role_group_id')
            ->where('staff_id', $userId)
            ->get()->toArray();

        $isAll = $isBranch = $isDepartment = $isOwn = 0;

        foreach ($dataRole as $role) {
            $role = (array)$role;
            if ($role['is_all']) {
                $isAll = 1;
            }

            if ($role['is_branch']) {
                $isBranch = 1;
            }

            if ($role['is_department']) {
                $isDepartment = 1;
            }

            if ($role['is_own']) {
                $isOwn = 1;
            }
        }

        if ($isAll) {

        } else if ($isBranch) {
            $myBrand = $user->branch_id;

            $oSelect->where($this->table . '.branch_id', $myBrand);
        } else if ($isDepartment) {
            $myDep = $user->department_id;
            $oSelect->where($this->table . '.department_id', $myDep);
        } else {
            // where de khong ra
            $oSelect->where($this->table . '.department_id', 'vund');
        }

        return $oSelect;
    }

    /**
     * Lấy danh sách nhân viên theo id nhân viên
     * @param $arrStaff
     */
    public function getListStaffByStaff($arrStaff)
    {
        $oSelect = $this
            ->select(
                $this->table . '.staff_id',
                $this->table . '.full_name as staff_name',
                $this->table . '.staff_avatar'
            )
            ->where($this->table . '.is_actived', 1)
            ->where($this->table . '.is_deleted', 0)
            ->whereIn($this->table . '.staff_id', $arrStaff);

        return $oSelect->groupBy($this->table . '.staff_id')->get();
    }

    /**
     * Lấy nhân viên theo id
     */
    public function getStaffId($staffId)
    {
        $oSelect = $this
            ->select(
                $this->table . '.staff_id',
                $this->table . '.full_name as staff_name',
                $this->table . '.staff_avatar'
            )
            ->where($this->table . '.staff_id', $staffId);

        return $oSelect->first();
    }

    /**
     * Lấy chi nhánh của nhân viên
     *
     * @return mixed
     */
    public function getBranchByStaff()
    {
        return $this
            ->select(
                "{$this->table}.branch_id",
                "br.branch_name"
            )
            ->join("branches as br", "br.branch_id", "=", "{$this->table}.branch_id")
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->groupBy("{$this->table}.branch_id")
            ->get();
    }

    /**
     * Lấy ds nhân viên theo chi nhánh và phòng ban
     *
     * @param $branchId
     * @param $departmentId
     * @return mixed
     */
    public function getListStaffByBranchDepartment($branchId, $departmentId)
    {
        return $this
            ->select(
                $this->table . '.staff_id',
                $this->table . '.full_name as staff_name'
            )
            ->where($this->table . '.is_actived', 1)
            ->where($this->table . '.is_deleted', 0)
            ->where("{$this->table}.branch_id", $branchId)
            ->where("{$this->table}.department_id", $departmentId)
            ->get();
    }

    public function getListStaffByArrStaff($arrIdStaff){
        return $this
            ->select(
                $this->table . '.staff_id',
                $this->table . '.full_name as staff_name'
            )
            ->whereIn($this->table . '.staff_id', $arrIdStaff)
            ->where($this->table . '.is_actived', 1)
            ->where($this->table . '.is_deleted', 0)
            ->get();
    }

    public function getName(){
        $oSelect= self::select("staff_id","full_name")->where('is_deleted',0)->where('is_actived', '=', 1)->get();
        return ($oSelect->pluck("full_name","staff_id")->toArray());
    }

    /**
     * Lấy danh sách nhân viên chưa thêm vào dự án
     * @param $listStaff
     * @return mixed
     */

    public function getListStaffProject($listStaff = [])
    {
        return   $this
            ->where('is_actived', 1)
            ->where('is_deleted', 0)
            ->whereNotIn("{$this->table}.{$this->primaryKey}", $listStaff)
            ->orderBy('staff_id', 'DESC')->get();
    }

    public function getDetail($staffId){
        return $this
            ->where("{$this->table}.staff_id", $staffId)
            ->first();
    }

    /**
     * Lấy ds nhân viên theo chi nhánh và phòng ban
     *
     * @param $branchId
     * @param $departmentId
     * @return mixed
     */
    public function getListStaffByBranchDepartmentStaff($branchId, $departmentId,$arrStaffId)
    {
        return $this
            ->select(
                $this->table . '.staff_id',
                $this->table . '.full_name as staff_name'
            )
            ->where($this->table . '.is_actived', 1)
            ->where($this->table . '.is_deleted', 0)
            ->where("{$this->table}.branch_id", $branchId)
            ->where("{$this->table}.department_id", $departmentId)
            ->whereIn("{$this->table}.staff_id", $arrStaffId)
            ->get();
    }
    public function getInfoManager($filter = []){

        $mSelect = $this
            ->select(
                "{$this->table}.staff_id as manager_id",
                "{$this->table}.full_name as manager_name",
                "{$this->table}.staff_avatar as manager_avatar",
                "{$this->table}.staff_avatar as manager_avatar",
                "{$this->table}.phone1",
                "{$this->table}.email",
                "{$this->table}.staff_type"
            );
        if(isset($filter['arrIdManager']) && $filter['arrIdManager'] != '' && $filter['arrIdManager'] != null ){
            $mSelect = $mSelect->whereIn( "{$this->table}.staff_id", $filter['arrIdManager']);
        }
        if(isset($filter['created_by']) && $filter['created_by'] != '' && $filter['created_by'] != null ){
            $mSelect = $mSelect->where( "{$this->table}.staff_id", $filter['created_by']);
        }
        if(isset($filter['arrIdStaff']) && $filter['arrIdStaff'] != '' && $filter['arrIdStaff'] != null ){
            $mSelect = $mSelect->whereIn( "{$this->table}.staff_id", $filter['arrIdStaff']);
        }
        return $mSelect->get()->toArray();
    }

    /**
     * Lấy danh sách nhân viên theo phòng ban
     */
    public function listStaffDepartment($idProject){
        return $this
            ->join('departments','departments.department_id',$this->table.'.department_id')
            ->join('manage_project_staff','manage_project_staff.staff_id',$this->table.'.staff_id')
            ->where('manage_project_staff.manage_project_id',$idProject)
            ->get();
    }
}