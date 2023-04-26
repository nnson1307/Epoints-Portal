<?php

namespace Modules\Loyalty\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;



class LoyaltyConfigNotificationTable extends Model
{
    protected $table = 'loyalty_template_notification';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'id',
        'key',
        'title',
        'message',
        'avatar',
        'has_detail',
        'detail_background',
        'detail_content',
        'detail_action_name',
        'detail_action',
        'detail_action_params',
        'created_at',
        'updated_at'
    ];

    public function getAllNotification()
    {
        $oSelect = $this->select('*')
            ->get();
        return $oSelect;
    }

    public function  getDetailByID($id)
    {
        $select = $this->select('*')
            ->where($this->table . '.id', $id)
            ->first();
        return $select;
    }

    public function updateData($data, $id)
    {
        return $this->where($this->table . '.id', $id)->update($data);
    }

    public function  getDetailByKey($key)
    {
        $select = $this->select('*')
            ->where($this->table . '.key', $key)
            ->first();
        return $select;
    }

    public function updateDataByID($data, $key)
    {
        return $this->where($this->table . '.key', $key)->update($data);
    }
}
