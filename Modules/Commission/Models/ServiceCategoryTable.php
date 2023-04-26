<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 20/10/2022
 * Time: 11:30
 */

namespace Modules\Commission\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceCategoryTable extends Model
{
    protected $table = "service_categories";
    protected $primaryKey = "service_category_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy option nhóm dịch vụ
     *
     * @return mixed
     */
    public function getOptionCategory()
    {
        return $this
            ->select(
                "service_category_id",
                "name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy thông tin nhóm dịch vụ
     *
     * @param $idCategory
     * @return mixed
     */
    public function getInfoCategory($idCategory)
    {
        return $this
            ->select(
                "service_category_id",
                "name"
            )
            ->where("service_category_id", $idCategory)
            ->first();
    }
}