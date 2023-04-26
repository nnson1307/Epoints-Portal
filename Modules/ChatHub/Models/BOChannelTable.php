<?php

namespace Modules\ChatHub\Models;

use Illuminate\Database\Eloquent\Model;

class BOChannelTable extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'piospa_channel_master';
    protected $primaryKey = 'channel_master_id';
    protected $fillable = [
        'channel_master_id','brand_id', 'channel_social_id','created_at', 'updated_at'];

    public function createChannel($insert){
        return $this->create($insert);
    }
    
}