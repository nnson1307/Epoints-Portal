<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

/**
 * User Model
 *
 * @author isc-daidp
 * @since Feb 23, 2018
 */
class UserTable extends Model
{
    use ListTableTrait;

    protected $table = 'staffs';
    protected $primaryKey = 'staff_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['staff_id', 'department_id',
        'branch_id', 'staff_title_id', 'user_name',
        'password', 'salt', 'full_name',
        'birthday', 'gender', 'phone1',
        'phone2', 'email', 'facebook',
        'date_last_login', 'is_admin',
        'is_actived', 'is_deleted', 'staff_avatar',
        'address', 'created_by', 'updated_by', 'created_at',
        'updated_at', 'remember_token', 'is_master', 'staff_code',
        'salary', 'subsidize', 'commission_rate', 'password_reset', 'date_password_reset'] ;



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Build query table
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function _getList()
    {
        return $this->select('staff_id', 'full_name', 'email', 'is_inactive', 'date_last_login');
    }


    /**
     * Remove user
     *
     * @param number $id
     */
    public function remove($id)
    {
        $this->where($this->primaryKey, $id)->delete();
    }


    /**
     * Insert user to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $oUser = $this->create($data);
        return $oUser->id;
    }

    public function getItem($id)
    {
        $ds = $this->select('full_name', 'gender', 'phone1', 'staff_avatar')->where('staff_id', $id)->first();
        return $ds;
    }

    /**
     * Chi tiết tk theo email
     * @param $param
     * @return mixed
     */
    public function getItemByCondition($param)
    {
        $select = $this->select($this->fillable);
        if ($param != []) {
            $select->where($param);
        }
        return $select->first();
    }

    /**
     * Chỉnh sửa
     * @param $id
     * @param $data
     */
    public function edit($id, $data)
    {
        $this->where($this->primaryKey, $id)->update($data);
    }
}