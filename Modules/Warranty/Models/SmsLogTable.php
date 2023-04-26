<?php

namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLogTable extends Model
{
    protected $table = 'sms_log';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'brandname', 'campaign_id', 'phone', 'customer_name', 'message', 'sms_status', 'sms_type', 'error_code', 'error_description', 'sms_guid', 'created_at', 'updated_at', 'time_sent', 'time_sent_done', 'sent_by', 'created_by', 'object_id', 'object_type'];

    public function add(array $data)
    {
        $data = $this->create($data);
        return $data->id;
    }
}