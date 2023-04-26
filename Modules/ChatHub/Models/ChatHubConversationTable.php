<?php

namespace Modules\ChatHub\Models;

use Illuminate\Database\Eloquent\Model;

class ChatHubConversationTable extends Model
{
    protected $table = 'chathub_conversation';

    protected $primaryKey = 'conversation_id';

    protected $fillable = [
        'conversation_id', 
        'conversation_social_id', 
        'channel_id', 
        'customer_id', 
        'agent_id', 
        'last_time', 
        'last_message', 
        'is_read', 
        'created_at', 
        'updated_at'
    ];

    public function seenMessage($filter){
        $s = $this->where('customer_id', $filter['customer_id']);
        if($filter['channel_id'] != "null"){
            $s->where('channel_id',$filter['channel_id']);
        }
        return $s->update(['is_read' => 0]);
    }
}
