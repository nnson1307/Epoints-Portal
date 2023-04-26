<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 21/06/2021
 * Time: 14:52
 */

namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class PageTable extends Model
{
    protected $table = "pages";
    protected $primaryKey = "id";

    const IS_ACTIVE = 1;

    /**
     * Lấy quyền page theo group
     *
     * @param $groupId
     * @return mixed
     */
    public function getPageByGroup($groupId)
    {
        return $this
            ->select(
                "route",
                "name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("action_group_id", $groupId)
            ->get();
    }
}