<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 21/06/2021
 * Time: 14:52
 */

namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class ActionTable extends Model
{
    protected $table = "actions";
    protected $primaryKey = "id";

    const IS_ACTIVE = 1;

    /**
     * Lấy quyền action theo group
     *
     * @param $groupId
     * @return mixed
     */
    public function getActionByGroup($groupId)
    {
        return $this
            ->select(
                "name as route",
                "title as name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("action_group_id", $groupId)
            ->get();
    }
}