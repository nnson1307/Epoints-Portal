<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 17/06/2021
 * Time: 11:31
 */

namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class AdminServiceBrandFeatureChildTable extends Model
{
    protected $table = "admin_service_brand_feature_child";
    protected $primaryKey = "service_brand_feature_child_id";

    const IS_ACTIVED = 1;

    /**
     * Lấy service được cấp phép sử dụng
     *
     * @return mixed
     */
    public function getAllService()
    {
        return $this
            ->select(
                "{$this->table}.feature_code"
            )
            ->join("admin_service_brand_feature as ft", "ft.service_brand_feature_id", "=", "{$this->table}.service_brand_feature_id")
            ->where("ft.is_actived", self::IS_ACTIVED)
            ->where("{$this->table}.is_actived", self::IS_ACTIVED)
            ->groupBy("{$this->table}.feature_code")
            ->get();
    }
}