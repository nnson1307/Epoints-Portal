<?php

namespace Modules\ChatHub\Models;

use Illuminate\Database\Eloquent\Model;

class ChatHubMessageTable extends Model
{
    protected $table = 'chathub_message';

    protected $primaryKey = 'message_id';

    protected $fillable = [
        'message_id', 
        'message_social_id', 
        'conversation_id', 
        'receiver_id', 
        'sender_id', 
        'content', 
        'time', 
        'type', 
        'created_at', 
        'updated_at'
    ];
    //lấy danh sách tin nhắn của customer cho 1 channel
    public function getList($filter){
        $oSelect = $this->select(
            'customer_register_id',
            'time',
            'type',
            'cpo_customer_registers.avatar',
            'full_name',
            'register_object_id',
            'chathub_message.content',
            'chathub_message.content_type',
            'chathub_message.message_id',
            'chathub_channel.avatar as ava',
            'chathub_channel.avatar as channel_avatar',
            'chathub_channel.name as channel_name'
        )
                    ->join('chathub_conversation',$this->table.'.conversation_id' , '=', 'chathub_conversation.conversation_id')
                    ->join('cpo_customer_registers','cpo_customer_registers.customer_register_id','=','chathub_conversation.customer_id')
                    ->join('chathub_channel', 'chathub_channel.channel_id', '=', 'cpo_customer_registers.channel_id')
                    ->where('chathub_conversation.customer_id','=',$filter['customer_id'])
        ;

        if($filter['channel_id'] != "null"){
            $this->where('chathub_conversation.channel_id','=',$filter['channel_id']);
        }
        if(isset($filter['order_by'])){
            return $oSelect->orderBy($this->table.'.message_id', $filter['order_by'])->limit(15)->get();
        } else {
            return $oSelect->orderBy($this->table.'.message_id','desc')->limit(15)->get();
        }
    }
    //thêm message khi scroll top
    public function addMessage($channel_id, $customer_id, $message_id){
        return $this->select('time','content','type', 'cpo_customer_registers.avatar', 'full_name', 'customer_register_id', 'chathub_message.content_type', 'chathub_message.message_id', 'chathub_channel.avatar as ava', 'chathub_channel.name as channel_name')
                    ->join('chathub_conversation',$this->table.'.conversation_id' , '=', 'chathub_conversation.conversation_id')
                    ->join('cpo_customer_registers','cpo_customer_registers.customer_register_id','=','chathub_conversation.customer_id')
                    ->join('chathub_channel', 'chathub_channel.channel_id', '=', 'cpo_customer_registers.channel_id')
                    ->where('chathub_conversation.channel_id','=',$channel_id)
                    ->where('chathub_conversation.customer_id','=',$customer_id)
                    ->where($this->table.'.message_id','<',$message_id)
                    ->orderBy($this->table.'.message_id','DESC')->limit(10)->get();
    }
}
