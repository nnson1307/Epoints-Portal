<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 26/03/2018
 * Time: 2:18 CH
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;


class StaffTable  extends  Model
{
    use ListTableTrait;

    /*
     * table staffs
     */
    protected $table = 'staffs' ;
    protected $primaryKey = 'staff_id';

    /*
     * fill table
     * $var array
     */
    protected $fillable = ['staff_id', 'fullname', 'code', 'staff_department_id', 'staff_title_id', 'staff_account_id', 'phone', 'staff_avatar', 'is_active', 'is_delete', 'created_at', 'updated_at', 'created_by', 'updated_by'] ;


    /*
     * Build query table
     * @author Le viet Thach
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function _getList()
    {
        return $this->from($this->table.' as staff')
                    ->leftJoin('staff_title', 'staff_title.staff_title_id', '=', 'staff.staff_title_id')
                    ->leftJoin('staff_account', 'staff_account.staff_account_id', '=', 'staff.staff_account_id')
                    ->leftJoin('stores', 'stores.store_id', '=', 'staff_account.store_id')
                    ->leftJoin('staff_department', 'staff_department.staff_department_id', '=', 'staff.staff_department_id')
                    ->select(
                        'stores.store_name as store_name',
                        'staff_title.staff_title_name',
                        'staff_department.staff_department_name',
                        'staff_account.username',
                        'staff.staff_id',
                        'staff.fullname',
                        'staff.code',
                        'staff.staff_department_id',
                        'staff.staff_title_id',
                        'staff.staff_account_id',
                        'staff.phone',
                        'staff.staff_avatar',
                        'staff.is_active',
                        'staff.is_delete',
                        'staff.created_at',
                        'staff.updated_at',
                        'staff.created_by',
                        'staff.updated_by'
                    )
                ->where('staff.is_delete',0)
                ->orderBy($this->primaryKey,'desc');
    }
    public function add(array $data){
        $oStaff =  $this->create($data);
        return $oStaff->staff_id ;
    }

    public function getItemHasAccount($id){
        return $this->leftJoin('staff_title', 'staff_title.staff_title_id', '=', 'staffs.staff_title_id')
            ->leftJoin('staff_account', 'staff_account.staff_account_id', '=', 'staffs.staff_account_id')
            ->leftJoin('stores', 'stores.store_id', '=', 'staff_account.store_id')
            ->leftJoin('staff_department', 'staff_department.staff_department_id', '=', 'staffs.staff_department_id')
            ->select(
                'stores.store_id',
                'stores.store_name as store_name',
                'staff_title.staff_title_name',
                'staff_department.staff_department_name',
                'staff_account.username',
                'staffs.staff_id',
                'staffs.fullname',
                'staffs.code',
                'staffs.staff_department_id',
                'staffs.staff_title_id',
                'staffs.staff_account_id',
                'staffs.phone',
                'staffs.staff_avatar as avatar',
                'staffs.is_active',
                'staffs.is_delete',
                'staffs.created_at',
                'staffs.updated_at',
                'staffs.created_by',
                'staffs.updated_by'
            )
            ->where('staffs.'.$this->primaryKey, $id)->first();
    }


    public function convertNameToSelect2()
    {

        return $this->select('staff_id','fullname')->get();
    }

    public function convertCodeToSelect2()
    {
        return $this->select('staff_id','code')->get();
    }

    /**
     * Lấy tất cả option nhân viên
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this->select(
            'staff_id',
            'full_name'
        )
            ->where('is_actived', '=', 1)
            ->where('is_deleted', '=', 0)
            ->get();
    }

    /**
     * Lấy thông tin nhân viên
     *
     * @param $staffId
     * @return mixed
     */
    public function getDetail($staffId)
    {
        return $this
            ->where('staff_id', $staffId)
            ->where('is_actived', '=', 1)
            ->where('is_deleted', '=', 0)
            ->first();
    }

    /**
     * Lấy thông tin nhân viên - dùng làm history
     *
     * @param $staffId
     * @return mixed
     */
    public function getInfo($staffId)
    {
        return $this
            ->select(
                "staff_id",
                "full_name",
                "phone1 as phone"
            )
            ->where("staff_id", $staffId)
            ->first();
    }

    /**
     * Lấy tài khoản admin
     *
     * @return mixed
     */
    public function getStaffAdmin()
    {
        return $this
            ->where('is_admin', 1)
            ->where('is_actived', '=', 1)
            ->where('is_deleted', '=', 0)
            ->get();
    }
}