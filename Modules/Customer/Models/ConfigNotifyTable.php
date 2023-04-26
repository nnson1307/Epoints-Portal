<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 21/06/2021
 * Time: 11:08
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigNotifyTable extends Model
{
    protected $table = "config_notification";

    const IS_ACTIVE = 1;

    /**
     * Lấy thông tin config notification
     *
     * @param $key
     * @return mixed
     */
    public function getInfo($key)
    {
        return $this
            ->select(
                "{$this->table}.key",
                "{$this->table}.name",
                "{$this->table}.config_notification_group_id",
                "{$this->table}.send_type",
                "{$this->table}.schedule_unit",
                "{$this->table}.value",
                "st_auto.title",
                "st_auto.message",
                "st_auto.avatar",
                "st_auto.has_detail",
                "st_auto.detail_background",
                "st_auto.detail_content",
                "st_auto.detail_action_name",
                "st_auto.detail_action",
                "st_auto.detail_action_params",
                "{$this->table}.is_active"
            )
            ->join("notification_template_auto as st_auto", "st_auto.key", "=", "{$this->table}.key")
            ->where("{$this->table}.key", $key)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->first();
    }
}