<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 20/09/2022
 * Time: 14:40
 */

namespace Modules\ManagerProject\Models;


use Illuminate\Database\Eloquent\Model;

class ProjectStatusTable extends Model
{
    protected $table = "manage_project_status";
    protected $primaryKey = "manage_project_status_id";

    const IS_ACTIVE = 1;

    public function getAll($filter = []){
        $oSelect = $this
            ->join('manage_project_status_config','manage_project_status_config.manage_project_status_id',$this->table.'.manage_project_status_id')
            ->where('manage_project_status_config.is_active',self::IS_ACTIVE)
            ->select($this->table.'.manage_project_status_id', 'manage_project_status_name')
                    ->where($this->table.".is_active", self::IS_ACTIVE);

        if (isset($filter['arr_status'])){
            $oSelect = $oSelect->whereIn($this->table.'.manage_project_status_id',$filter['arr_status']);
        }

        return $oSelect->get();
    }
}