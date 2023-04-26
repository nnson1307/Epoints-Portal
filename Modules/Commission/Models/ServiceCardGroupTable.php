<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 20/10/2022
 * Time: 11:40
 */

namespace Modules\Commission\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceCardGroupTable extends Model
{
    protected $table = "service_card_groups";
    protected $primaryKey = "service_card_group_id";

    const NOT_DELETED = 0;

    /**
     * Lấy option nhóm thẻ dịch vụ
     *
     * @return mixed
     */
    public function getOptionGroup()
    {
        return $this
            ->select(
                "service_card_group_id",
                "name"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy thông tin nhóm thẻ dịch vụ
     *
     * @param $idCardGroup
     * @return mixed
     */
    public function getInfoGroup($idCardGroup)
    {
        return $this
            ->select(
                "service_card_group_id",
                "name"
            )
            ->where("service_card_group_id", $idCardGroup)
            ->first();
    }
}