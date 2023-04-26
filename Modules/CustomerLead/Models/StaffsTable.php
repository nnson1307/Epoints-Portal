<?php


namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StaffsTable extends Model
{
    protected $table = 'staffs';
    protected $primaryKey = 'staff_id';
    protected $fillable = [
        'staff_id', 'department_id', 'branch_id', 'staff_title_id', 'user_name', 'password', 'salt', 'full_name',
        'birthday', 'gender', 'phone1', 'phone2', 'email', 'facebook', 'date_last_login', 'is_admin', 'is_actived',
        'is_deleted', 'staff_avatar', 'address', 'created_by', 'updated_by', 'created_at', 'updated_at', 'remember_token'
    ];

    public function getListStaffByFilter($filter)
    {
        $data = $this
            ->select(
                'staffs.staff_avatar',
                'staffs.staff_id',
                'staffs.full_name'
            )
            ->leftJoin('departments', 'departments.department_id', '=', 'staffs.department_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id')
            ->where("{$this->table}.is_actived", 1)
            ->where("{$this->table}.is_deleted", 0);
        if(isset($filter['department_id']) != ''){
            $data->where("staffs.department_id", $filter['department_id']);
        }
        if(isset($filter['branch_code']) != ''){
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        return $data->get()->toArray();
    }
    /**
     * Danh sach nhan vien
     *
     * @return mixed
     */
    public function getStaffOption()
    {
        return $this->select('staff_id', 'full_name', 'address', 'phone1', 'phone2')
        ->where('is_deleted', 0)
        ->where('is_actived', 1)
        ->get()->toArray();
    }
    public function getStaffOptionByFilter($filter)
    {
        $data = $this->select('staff_id', 'full_name', 'address', 'phone1', 'phone2')
        ->where('is_deleted', 0)
        ->where('is_actived', 1);
        if(isset($filter['staff_id']) != ''){
            $data->where("staff_id", $filter['staff_id']);
            unset($filter['staff_id']);
        }
        return $data->get()->toArray();
    }

    /**
     * Danh sách tất cả nhân viên
     *
     * @return mixed
     */
    public function getListStaff()
    {
        return $this
            ->select(
                "{$this->table}.staff_id",
                "{$this->table}.full_name",
                "{$this->table}.address",
                "{$this->table}.phone1",
                "{$this->table}.phone2"
            )
            ->where("{$this->table}.is_actived", 1)
            ->where("{$this->table}.is_deleted", 0)
            ->get()->toArray();
    }

    public function getOptionStaffByDepartment(array $arrDepartment)
    {
        return $this->select('staff_id', 'full_name', 'address', 'phone1', 'phone2')
            ->whereIn('department_id', $arrDepartment)
            ->where('is_deleted', 0)
            ->get()->toArray();
    }

    /**
     * Lấy option nhân viên
     *
     * @return mixed
     */
    public function getOption()
    {
        $select = $this->select(
            'staff_id',
            'full_name',
            'address',
            'phone1',
            'phone2'
        )
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->where('branch_id', Auth::user()->branch_id);
        return $select->get()->toArray();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this
            ->select(
                'staffs.*',
                'departments.department_name as department_name',
                'branches.branch_name as branch_name',
                'staff_title.staff_title_name as staff_title_name',
                'staffs.user_name as account',
                'staffs.salt as salt',
                'staffs.full_name as name',
                'staffs.birthday as birthday',
                'staffs.gender as gender',
                'staffs.phone1 as phone1',
                'staffs.phone2 as phone2',
                'staffs.email as email',
                'staffs.facebook as facebook',
                'staffs.date_last_login as date_last_login',
                'staffs.is_admin as is_admin',
                'staffs.is_actived as is_actived',
                'staffs.staff_avatar as staff_avatar',
                'staffs.address as address',
                'staffs.salary as salary',
                'staffs.subsidize as subsidize',
                'staffs.commission_rate as commission_rate'
            )
            ->leftJoin('departments', 'departments.department_id', '=', 'staffs.department_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id')
            ->leftJoin('staff_title', 'staff_title.staff_title_id', '=', 'staffs.staff_title_id')
            ->where("{$this->table}.staff_id", $id)
            ->first();
    }

    /**
     * Lấy thông tin hoa hồng của nhân viên
     *
     * @param $idStaff
     * @return mixed
     */
    public function getCommissionStaff($idStaff)
    {
        return $this
            ->select(
                "staff_id",
                "commission_rate"
            )
            ->where("staff_id", $idStaff)
            ->where('is_deleted', 0)
            ->first();
    }
}