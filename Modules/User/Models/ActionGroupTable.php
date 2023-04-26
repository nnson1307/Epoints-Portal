<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 21/06/2021
 * Time: 13:57
 */

namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class ActionGroupTable extends Model
{
    protected $table = "action_group";
    protected $primaryKey = "action_group_id";

    const IS_ACTIVE = 1;

    /**
     * Lấy tất cả nhóm quyền
     *
     * @return mixed
     */
    public function getGroup()
    {
        return $this
            ->select(
                "action_group_id",
                "action_group_name_vi",
                "action_group_name_en",
                "platform"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->get();
    }
}