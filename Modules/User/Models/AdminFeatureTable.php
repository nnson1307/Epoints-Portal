<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 21/06/2021
 * Time: 15:40
 */

namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class AdminFeatureTable extends Model
{
    protected $table = "admin_feature";
    protected $primaryKey = "feature_id";
    protected $fillable = [
        "feature_id",
        "feature_group_id",
        "feature_name_vi",
        "feature_name_en",
        "feature_code",
        "service_type",
        "platform_type",
        "description",
        "brand_action_id",
        "is_actived",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by",
        "position"
    ];

    const IS_ACTIVE = 1;

    /**
     * Lấy feature theo group
     *
     * @param $groupId
     * @return mixed
     */
    public function getFeatureByGroup($groupId)
    {
        return $this
            ->select(
                "feature_id",
                "feature_group_id",
                "feature_name_vi",
                "feature_name_en",
                "feature_code",
                "service_type",
                "platform_type"
            )
            ->where("feature_group_id", $groupId)
            ->where("is_actived", self::IS_ACTIVE)
            ->get();
    }
}