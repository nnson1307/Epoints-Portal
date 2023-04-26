<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 15/06/2021
 * Time: 13:58
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;

class NotifyQueueTable extends Model
{
    protected $table = "notification_queue";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "notification_detail_id",
        "tenant_id",
        "send_type",
        "send_type_object",
        "notification_avatar",
        "notification_title",
        "notification_message",
        "send_at",
        "is_brand",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by",
        "is_actived",
        "is_send",
        "is_deleted"
    ];

    /**
     * Chỉnh sửa notify_queue
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where("id", $id)->update($data);
    }
}