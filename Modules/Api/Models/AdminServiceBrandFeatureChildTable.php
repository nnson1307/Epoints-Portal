<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 17/06/2021
 * Time: 09:43
 */

namespace Modules\Api\Models;


use Illuminate\Database\Eloquent\Model;

class AdminServiceBrandFeatureChildTable extends Model
{
    protected $table = "admin_service_brand_feature_child";
    protected $primaryKey = "service_brand_feature_child_id";
    protected $fillable = [
        "service_brand_feature_child_id",
        "service_brand_feature_id",
        "brand_id",
        "service_id",
        "feature_group_id",
        "feature_id",
        "feature_code",
        "is_actived",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "brand_update_at",
        "brand_update_by"
    ];

    /**
     * Tạo feature child
     *
     * @param $data
     * @return mixed
     */
    public function createBrandFeatureChild($data)
    {
        $oSelect = $this->insert($data);
        return $oSelect;
    }

    /**
     * Xoá feature_child by brand_id
     *
     * @param $id
     * @return mixed
     */
    public function deleteByBrandId($id)
    {
        $oSelect = $this->delete();
        return $oSelect;
    }
}
