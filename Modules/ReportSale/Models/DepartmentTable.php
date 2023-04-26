<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/24/2018
 * Time: 10:20 AM
 */

namespace Modules\ReportSale\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class DepartmentTable extends Model
{
    use ListTableTrait;
    protected $table = 'departments';
    protected $primaryKey = 'department_id';

    protected $fillable = ['department_id', 'department_name', 'is_inactive', 'is_deleted', 'created_by', 'updated_by', 'created_at', 'updated_at','slug'];

    public function getOption()
    {
        $select = $this->select('department_id', 'department_name');
        return $select->get()->toArray();
    }
}