<?php

namespace Modules\Kpi\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * class StaffsTable
 * @author HaoNMN
 * @since Jun 2022
 */
class StaffsTable extends Model
{
    protected $table    = 'staffs';
    protected $fillable = [];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy danh sách nhân viên
     * @return array
     */
    public function getStaff($param)
    {
        $oSelect = $this->select(
                        "{$this->table}.staff_id",
                        "{$this->table}.full_name"
                    )
                    ->where("is_actived", self::IS_ACTIVE)
                    ->where("is_deleted", self::NOT_DELETED);
        if (isset($param['branch_id'])) {
            $oSelect->where("{$this->table}.branch_id", $param['branch_id']);
        }

        if (isset($param['department_id'])) {
            $oSelect->where("{$this->table}.department_id", $param['department_id']);
        }

        if (isset($param['team_id'])) {
            $oSelect->where("{$this->table}.team_id", $param['team_id']);
        }

        if (isset($param['staff_title_id'])) {
            $oSelect->where("{$this->table}.staff_title_id", $param['staff_title_id']);
        }

        if (isset($param['array_department_id'])) {
            $oSelect->whereIn("{$this->table}.department_id", $param['array_department_id']);
        }
        
        return $oSelect->get()->toArray();
    }

    /**
     * Lấy thông tin nhân viên
     *
     * @param $staffId
     * @return mixed
     */
    public function getInfo($staffId)
    {
        return $this
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->where("staff_id", $staffId)
            ->first();
    }

    /**
     * Lấy danh sách nhân viên trong nhóm
     *
     * @param $teamId
     * @return mixed
     */
    public function getStaffByTeam($teamId)
    {
        $ds = $this
            ->select(
                "staff_id",
                "full_name as staff_name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED);

        if ($teamId != null) {
            $ds->where("team_id", $teamId);
        }

        return $ds->get();
    }
}
