<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class NotificationTypeTable extends Model
{
    use ListTableTrait;
    protected $table = 'notification_type';
    protected $primaryKey = 'id';
    protected $fillable=[
        'id','type_name', 'type_name_vi','type_name_en','is_detail', 'detail_type', 'action', 'action_params','is_banner','is_notify',
        'created_at', 'created_by', 'updated_at', 'updated_by', 'is_deleted', 'from'
    ];

    public function getList($filter)
    {
        $oSelect = $this->where('is_deleted', 0);

        if(isset($filter['is_banner'])){
            $oSelect->where('is_banner', 1);
        } else {
            $oSelect->where('is_notify', 1);
        }

        return $oSelect->get();
    }
}
