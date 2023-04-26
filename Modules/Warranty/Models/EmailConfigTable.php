<?php

namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;

class EmailConfigTable extends Model
{
    protected $table = 'email_config';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'key', 'value', 'title', 'content', 'is_actived', 'created_by', 'updated_by', 'created_at',
        'updated_at', 'actived_by', 'datetime_actived', 'time_sent'
    ];

    public function getItemByKey($key)
    {
        return $this->where('key', $key)->first();
    }
}