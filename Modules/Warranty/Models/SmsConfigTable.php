<?php

namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;

class SmsConfigTable extends Model
{
    protected $table = 'sms_config';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'key', 'value', 'time_sent', 'name', 'content', 'is_active',
        'created_by', 'updated_by', 'created_at', 'updated_at', 'actived_by', 'datetime_actived'];

    public function getItemByKey($key)
    {
        return $this->where('key', $key)->first();
    }
}