<?php


namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;

class DepartmentTable extends Model
{
    protected $table = 'departments';
    protected $primaryKey = 'department_id';

    protected $fillable = ['department_id', 'department_name', 'is_inactive', 'is_deleted', 'created_by', 'updated_by', 'created_at', 'updated_at','slug'];

    public function getOption()
    {
        return $this->select('department_id', 'department_name')
            ->where('is_deleted', 0)->get();
    }
}