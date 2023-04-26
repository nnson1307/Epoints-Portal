<?php

namespace Modules\Kpi\Models;

use Illuminate\Database\Eloquent\Model;

class TeamTable extends Model
{
    protected $table      = 'team';
    protected $primaryKey = 'team_id';
    protected $fillable   = [
        'team_id',
        'team_name'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy option nhóm
     *
     * @param $departmentId
     * @return mixed
     */
    public function getTeam($departmentId)
    {
        $oSelect = $this->select(
                        "{$this->table}.team_id",
                        "{$this->table}.team_name"
                    )
                    ->where("{$this->table}.is_actived", self::IS_ACTIVE)
                    ->where("{$this->table}.is_deleted", self::NOT_DELETED);
        if (! empty($departmentId)) {
//            $oSelect->where("{$this->table}.department_id", $departmentId);
        }
        
        return $oSelect->get()->toArray();
    }

    /**
     * Lấy thông tin nhóm
     *
     * @param $teamId
     * @return mixed
     */
    public function getInfoTeam($teamId)
    {
        return $this
            ->select(
                "{$this->table}.team_id",
                "{$this->table}.team_name"
            )
            ->where("{$this->table}.team_id", $teamId)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->first();
    }
}
