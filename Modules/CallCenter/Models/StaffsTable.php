<?php


namespace Modules\CallCenter\Models;


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

    /**
     * Danh sach nhan vien
     *
     * @return mixed
     */
    public function getStaffOption()
    {
        return $this->select('staff_id', 'full_name', 'address', 'phone1', 'phone2')->where('is_deleted', 0)->get()->toArray();
    }
}