<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerWork\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ManagerWorkSupportTable extends Model
{
    use ListTableTrait;
    protected $table = "manage_work_support";
    protected $primaryKey = "manage_work_support_id";

    /**
     * lấy danh sách nhân viên liên quan theo công việc
     * @param $manage_work_id
     */
    public function getListStaffByWork($manage_work_id){
        return $this
            ->select(
                'staffs.staff_id',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where('manage_work_id',$manage_work_id)
            ->get();
    }

}