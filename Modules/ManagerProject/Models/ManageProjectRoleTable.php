<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerProject\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class ManageProjectRoleTable extends Model
{
    protected $table = "manage_project_role";
    protected $primaryKey = "manage_project_role_id";
    protected $fillable = [
        'manage_project_role_id',
        'manage_project_role_code',
        'manage_project_role_name',
        'manage_project_id',
        'is_active',
        'is_deleted',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
    const IS_DELETED = 0;
    const IS_ACTIVE = 1;

    /**
     * Lấy danh sách quyền
     *
     * @return void
     */
    public function getAll()
    {
        return $this
            ->select("manage_project_role_id", "manage_project_role_name")
            ->where("is_active", self::IS_ACTIVE)
            ->where("is_deleted", self::IS_DELETED)
            ->orderBy("{$this->table}.{$this->primaryKey}", 'DESC')
            ->get();
    }

    /**
     * Lấy danh sách thành viên của phòng ban
     * @param $projectId
     * @return void
     */
    public function getAllByProject($projectId)
    {
        return $this
            ->select("manage_project_role_id", "manage_project_role_name")
            ->where("is_active", self::IS_ACTIVE)
            ->where("is_deleted", self::IS_DELETED)
            ->where("manage_project_id", $projectId)
            ->orderBy("{$this->table}.{$this->primaryKey}", 'DESC')
            ->get();
    }

    public function getInfoAdmin($roleCode){
        return $this
            ->where('manage_project_role_code',$roleCode)
            ->first();
    }
}
