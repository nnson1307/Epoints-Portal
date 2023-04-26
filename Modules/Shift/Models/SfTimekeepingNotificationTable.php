<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 10:52 AM
 */

namespace Modules\Shift\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class SfTimekeepingNotificationTable extends Model
{
    use ListTableTrait;
    protected $table = "sf_timekeeping_notification";
    protected $primaryKey = "sf_timekeeping_notification_id";
    protected $fillable = [
        "sf_timekeeping_notification_id",
        "sf_timekeeping_notification_title_show",
        "sf_timekeeping_notification_desc",
        "sf_timekeeping_notification_title",
        "sf_timekeeping_notification_content",
        "is_email",
        "is_noti",
        "avatar",
        "type",
        "type_send",
        "time_send",
        "is_active",
        "is_deleted",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",

    ];

    const IS_ACTIVE = 1;
    const IS_DELETED = 0;

    public function getAll(){
        return $this
            ->where($this->table.'.is_deleted', self::IS_DELETED)
            ->get();
    }

    /**
     * Lấy chi tiết thông báo
     * @param $sf_timekeeping_notification_id
     */
    public function getDetail($sf_timekeeping_notification_id){
        return $this
            ->where($this->table.'.sf_timekeeping_notification_id',$sf_timekeeping_notification_id)
            ->first();
    }

    /**
     * Cập nhật noti
     * @param $data
     * @param $id
     */
    public function updateNoti($data,$sf_timekeeping_notification_id){
        return $this
            ->where($this->table.'.sf_timekeeping_notification_id',$sf_timekeeping_notification_id)
            ->update($data);
    }
}