<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 02/08/2022
 * Time: 14:42
 */

namespace Modules\Notification\Models;


use Illuminate\Database\Eloquent\Model;

class StaffNotificationReceiverTable extends Model
{
    protected $table = "staff_notification_receiver";
    protected $primaryKey = "staff_notification_receiver_id";
    protected $fillable = [
        "staff_notification_receiver_id",
        "staff_notification_key",
        "role_group_id",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy thông tin người nhận
     *
     * @param $notificationKey
     * @return mixed
     */
    public function getReceiverByKey($notificationKey)
    {
        return $this->where("staff_notification_key", $notificationKey)->get();
    }

    /**
     * Xoá người nhận
     *
     * @param $notificationKey
     * @return mixed
     */
    public function removeReceiverByKey($notificationKey)
    {
        return $this->where("staff_notification_key", $notificationKey)->delete();
    }
}