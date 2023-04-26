<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 15-04-02020
 * Time: 3:23 PM
 */

namespace Modules\Notification\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigNotificationGroupTable extends Model
{
    protected $table = "config_notification_group";
    protected $primaryKey = "config_notification_group_id";
    protected $fillable = [
        "config_notification_group_id",
        "config_notification_group_name",
        "display_sort",
        "created_at",
        "updated_at",
        "updated_by"
    ];

    /**
     * Lấy danh sách nhóm cấu hình thông báo
     *
     * @return mixed
     */
    public function getGroup()
    {
        return $this
            ->select(
                "config_notification_group_id",
                "config_notification_group_name"
            )
            ->orderBy("display_sort", "desc")
            ->get();
    }
}