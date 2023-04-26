<?php

namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLogTable extends Model
{
    protected $table = 'email_log';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'campaign_id', 'email', 'customer_name', 'email_status', 'email_type', 'content_sent',
        'created_at', 'updated_at', 'time_sent', 'time_sent_done', 'provider', 'sent_by',
        'created_by', 'updated_by','object_id','object_type'
    ];

    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }
}