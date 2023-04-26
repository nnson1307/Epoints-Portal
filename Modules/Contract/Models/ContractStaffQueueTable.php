<?php

namespace Modules\Contract\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContractStaffQueueTable extends Model
{
    protected $table = 'contract_staff_queue';
    protected $primaryKey = 'contract_staff_queue_id';
    protected $fillable=[
        "contract_staff_queue_id",
        "staff_notification_detail_id",
        "tenant_id",
        "contract_id",
        "staff_id",
        "staff_notification_avatar",
        "staff_notification_title",
        "staff_notification_message",
        "send_at",
        "is_actived",
        "is_send",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
    ];

    public function createContractStaffQueue($data)
    {
        return $this->create($data)->contract_staff_queue_id;
    }
}
