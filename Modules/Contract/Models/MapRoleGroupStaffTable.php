<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/22/2019
 * Time: 4:02 PM
 */

namespace Modules\Contract\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MapRoleGroupStaffTable extends Model
{
    protected $table = 'map_role_group_staff';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'role_group_id', 'staff_id', 'is_actived', 'created_at', 'updated_at'
    ];

    const PORTAL = "portal";


    public function getListStaffByRoleGroup($roleGroupId)
    {
        $data = $this->select(
            "{$this->table}.role_group_id",
            "{$this->table}.staff_id",
            "staffs.full_name",
            "staffs.email"
        )
            ->join("staffs", "staffs.staff_id", "{$this->table}.staff_id")
            ->where("{$this->table}.role_group_id", $roleGroupId)
            ->where("{$this->table}.is_actived", 1);
        return $data->get();
    }


}