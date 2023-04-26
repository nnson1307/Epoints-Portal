<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class NotificationQueueTable extends Model
{
    use ListTableTrait;
    protected $table = 'notification_queue';
    protected $primaryKey = 'id';
    protected $fillable=[
        'notification_detail_id', 'tenant_id', 'send_type', 'send_type_object', 'send_at', 'is_brand',
        'created_at', 'created_by', 'updated_at', 'updated_by', 'is_send', 'is_deleted', 'is_actived',
        'notification_avatar', 'notification_title', 'notification_message'
    ];

    /**
     * Insert thông báo
     *
     * @param $data
     * @return mixed
     */
    public function createNotiQueue($data)
    {
        return $this->create($data);
    }

    /**
     * Cập nhật thông báo
     *
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateNotiQueue($id, $data)
    {
        return $this->where('notification_detail_id', $id)->update($data);
    }

    /**
     * Xóa
     *
     * @param $id
     * @return mixed
     */
    public function deleteNotiQueue($id)
    {
        return $this->where('notification_detail_id', $id)->delete();
    }
}
