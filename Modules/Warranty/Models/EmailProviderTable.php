<?php

namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;

class EmailProviderTable extends Model
{
    protected $table = 'email_provider';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'type', 'name_email', 'email', 'password', 'is_actived', 'email_template_id',
        'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    public function getItem($id)
    {
        return $this->select('id','type', 'name_email', 'email', 'password', 'is_actived','email_template_id')
            ->where('id', $id)->first();
    }
}