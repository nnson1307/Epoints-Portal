<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 15-04-02020
 * Time: 3:24 PM
 */

namespace Modules\Notification\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigNotificationTable extends Model
{
    protected $table = "config_notification";
//    protected $primaryKey = "key";
    protected $fillable = [
        "key",
        "name",
        "config_notification_group_id",
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
                "{$this->table}.config_notification_group_id",
                "{$this->table}.is_active",
                "{$this->table}.send_type",
                "{$this->table}.schedule_unit",
                "{$this->table}.value",
                "notification_template_auto.title",
                "notification_template_auto.message",
                "notification_template_auto.avatar",
                "notification_template_auto.detail_background",
                "notification_template_auto.detail_content"
            )
            ->join("notification_template_auto", "notification_template_auto.key", "=", "{$this->table}.key")
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
                "{$this->table}.config_notification_group_id",
                "{$this->table}.is_active",
                "{$this->table}.send_type",
                "{$this->table}.schedule_unit",
                "{$this->table}.value",
                "notification_template_auto.title",
                "notification_template_auto.message",
                "notification_template_auto.avatar",
                "notification_template_auto.detail_background",
                "notification_template_auto.detail_content"
            )
            ->join("notification_template_auto", "notification_template_auto.key", "=", "{$this->table}.key")
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