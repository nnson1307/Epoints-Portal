<?php

namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;

class SmsProviderTable extends Model
{
    protected $table = 'sms_setting_brandname';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'provider', 'value', 'type', 'account', 'password', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_actived'];

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }
}