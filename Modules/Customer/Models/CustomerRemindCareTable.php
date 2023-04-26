<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 15/06/2021
 * Time: 09:54
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerRemindCareTable extends Model
{
    protected $table = "customer_remind_care";
    protected $primaryKey = "customer_remind_care_id";
    protected $fillable = [
        "customer_remind_care_id",
        "customer_remind_use_id",
        "type",
        "type_name",
        "type_id",
        "content",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by"
    ];

    /**
     * Thêm lich sử chăm sóc KH
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->customer_remind_care_id;
    }

    /**
     * Lấy thông tin chăm sóc
     *
     * @param $remindUseId
     * @return mixed
     */
    public function getCare($remindUseId)
    {
        return $this
            ->select(
                "{$this->table}.customer_remind_care_id",
                "{$this->table}.type",
                "{$this->table}.type_name",
                "{$this->table}.type_id",
                "{$this->table}.content",
                "{$this->table}.created_at",
                "sms.sms_status",
                "sms.time_sent_done as sms_sent_at",
                "email.email_status",
                "email.time_sent_done as email_sent_at",
                "notify.is_send as notify_is_send",
                "notify.send_at as notify_sent_at",
                "sf.full_name as staff_name"
            )
            ->leftJoin("sms_log as sms", "sms.id", "=", "{$this->table}.type_id")
            ->leftJoin("email_log as email", "email.id", "=", "{$this->table}.type_id")
            ->leftJoin("notification_queue as notify", "notify.id", "=", "{$this->table}.type_id")
            ->leftJoin("staffs as sf", "sf.staff_id", "=", "{$this->table}.created_by")
            ->where("{$this->table}.customer_remind_use_id", $remindUseId)
            ->orderBy("{$this->table}.customer_remind_care_id", "desc")
            ->get();
    }
}