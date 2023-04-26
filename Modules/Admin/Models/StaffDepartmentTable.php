<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;


class StaffDepartmentTable extends Model
{
    use ListTableTrait;
    protected $table = 'staff_department';
    protected $primaryKey = 'staff_department_id';

    protected $fillable = ['staff_department_id', 'staff_department_name', 'staff_department_code', 'staff_department_description', 'is_active','is_delete', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    protected function _getList()
    {
        return $this->select('staff_department_id', 'staff_department_name', 'staff_department_code', 'is_active', 'created_at')->where('is_delete',0);
    }
    /**
     * Insert staff department to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $oStaffDepartment=$this->create($data);
        return $oStaffDepartment->id;
    }

    /**
     * Edit staff department to database
     *
     * @param array $data , $id
     * @return number
     */
    public function edit(array $data,$id)
    {
        return $this->where($this->primaryKey,$id)->update($data);
    }
    /**
     * Remove staff department to database
     *
     * @param number $id
     */
    public function remove($id)
    {
        $this->where($this->primaryKey,$id)->update(['is_delete'=>1]);
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey,$id)->first();
    }
    /*
     * function get
     */
    public function getStaffDepartmentOption(){
        return $this->select('staff_department_id','staff_department_name')->get()->toArray();
    }
}
