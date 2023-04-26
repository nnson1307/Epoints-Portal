<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DepartmentTable extends Model
{
    protected $table = "departments";
    protected $primaryKey = "shift_id";
    protected $fillable = [
        "department_id",
        "department_name"
    ];

    /**
     * Get danh sách ca làm việc
     *
     * @param array $data
     * @return mixed
     */

    public function getOptionList(){
       
        $oSelect = $this
            ->select(
                $this->table.'.department_id',
                $this->table.'.department_name',
            )
            ->where("{$this->table}.is_deleted" , '=', 0)
            ->where("{$this->table}.is_inactive" , '=', 1);
        return $oSelect->get();
    }

}