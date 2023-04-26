<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 02/08/2022
 * Time: 14:32
 */

namespace Modules\Notification\Models;


use Illuminate\Database\Eloquent\Model;

class RoleGroupTable extends Model
{
    protected $table = "role_group";
    protected $primaryKey = "id";

    const IS_ACTIVE = 1;

    /**
     * Lấy nhóm quyền
     *
     * @return mixed
     */
    public function getRoleGroup()
    {
        return $this
            ->select(
                "id",
                "name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->get();
    }
}