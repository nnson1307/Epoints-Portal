<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 26/03/2018
 * Time: 2:18 CH
 */

namespace Modules\ManagerWork\Models;


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
    protected function _getList($filters = [])
    {   
        return $this->from($this->table.' as staff')
                ->where('staff.is_deleted',0)
                ->where('staff.is_actived',1)
                ->orderBy($this->primaryKey,'desc');
    }

    public function listStaff($filters = [])
    {   
        return $this->from('staffs')
                ->select(
                    'staff_id',
                    'full_name as fullname',
                    'phone1',
                    'email',
                    'address'
                )
                ->where('staffs.is_deleted',0)
                ->where('staffs.is_actived',1)
                ->orderBy('staff_id','desc')->get();
    }

    public function getName(){
        $oSelect= self::select("staff_id","full_name")->where('is_deleted',0)->where('is_actived', '=', 1)->get();
        return ($oSelect->pluck("full_name","staff_id")->toArray());
    }

    public function add(array $data){
        $oStaff =  $this->create($data);
        return $oStaff->staff_id ;
    }
    /**
     * Lấy thông tin nhân viên
     *
     * @param $staffId
     * @return mixed
     */
    public function getDetail($staffId)
    {
        return $this->select(
            'staff_id',
            'full_name',
            'phone1',
            'email',
            'address'
        )
            ->where('staff_id', $staffId)
            ->where('is_actived', '=', 1)
            ->where('is_deleted', '=', 0)
            ->first();
    }

}