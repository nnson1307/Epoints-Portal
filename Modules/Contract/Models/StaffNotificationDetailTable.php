<?php

namespace Modules\Contract\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;
use Carbon\Carbon;

class StaffNotificationDetailTable extends Model
{
    use ListTableTrait;
    protected $table = 'staff_notification_detail';
    protected $primaryKey = 'staff_notification_detail_id';
    protected $fillable=[
        'tenant_id', 'background', 'content', 'action', 'action_params', 'is_brand', 'action_name',
        'created_at', 'created_by', 'updated_at', 'updated_by', 'staff_notification_detail_id'
    ];

    public function createNotiDetail($data)
    {
        return $this->create($data)->staff_notification_detail_id;
    }
}
