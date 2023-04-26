<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigStaffNotificationTable extends Model
{
    protected $table = "config_staff_notification";
    protected $fillable = [
        "key",
        "name",
        "config_staff_notification_group_id",
        "is_active",
        "display_sort",
        "send_type",
        "schedule_unit",
        "value",
        "created_at",
        "updated_at",
        "updated_by"
    ];

    /**
     * Lấy danh sách cấu hình thông báo
     *
     * @return mixed
     */
    public function getConfig()
    {
        return $this
            ->select(
                "{$this->table}.key",
                "{$this->table}.name",
                "{$this->table}.config_staff_notification_group_id as config_notification_group_id",
                "{$this->table}.is_active",
                "{$this->table}.send_type",
                "{$this->table}.schedule_unit",
                "{$this->table}.value",
                "snta.title",
                "snta.message",
                "snta.avatar",
                "snta.detail_background",
                "snta.detail_content"
            )
            ->join("staff_notification_template_auto as snta", "snta.key", "=", "{$this->table}.key")
            ->get();
    }

    /**
     * Lấy thông tin cấu hình
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
                "{$this->table}.config_staff_notification_group_id as config_notification_group_id",
                "{$this->table}.is_active",
                "{$this->table}.send_type",
                "{$this->table}.schedule_unit",
                "{$this->table}.value",
                "snta.title",
                "snta.message",
                "snta.avatar",
                "snta.detail_background",
                "snta.detail_content"
            )
            ->join("staff_notification_template_auto as snta", "snta.key", "=", "{$this->table}.key")
            ->where("{$this->table}.key", $key)
            ->first();
    }

    /**
     * Chỉnh sửa cấu hình
     *
     * @param array $data
     * @param $key
     * @return mixed
     */
    public function edit(array $data, $key)
    {
        return $this->where("key", $key)->update($data);
    }
}