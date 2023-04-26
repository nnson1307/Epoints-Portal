<?php

namespace Modules\ChatHub\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRegisterTable extends Model
{
    protected $table = 'cpo_customer_registers';

    protected $primaryKey = 'customer_register_id';

    protected $fillable = [
        'customer_register_id', 
        'register_source', 
        'register_object_id', 
        'channel_id', 
        'customer_lead_id', 
        'gender', 
        'full_name', 
        'email', 
        'phone', 
        'avatar',
        'created_at', 
        'updated_at'
    ];
    // lấy danh sách customer theo id channel
    public function getList($channel_id, $filter){
        $oSelect = $this->join('chathub_conversation', $this->table.'.customer_register_id' , '=', 'chathub_conversation.customer_id')
            ->join('chathub_channel', 'chathub_channel.channel_id' , '=', 'chathub_conversation.channel_id')
                    ->join('chathub_customer', 'chathub_customer.customer_id', '=', 'cpo_customer_registers.customer_register_id')
            ->where('is_subscribed',1);

        if($channel_id != "null"){
            $oSelect->where('chathub_conversation.channel_id','=',$channel_id);
        }
        if(isset($filter['search_message']) && $filter['search_message'] != ''){
            $search = $filter['search_message'];
            $oSelect->where(function($query)use($search){
                $query ->where('full_name', 'like', '%'.$search.'%')
                    ->orWhere('last_message', 'like', '%'.$search.'%')
                    ->orWhere('last_message_send', 'like', '%'.$search.'%');
            });
        }
        if(isset($filter['type_reading']) && $filter['type_reading'] != ''){
            if($filter['type_reading'] == 'read'){
                $oSelect->whereRaw("chathub_conversation.last_message_send = chathub_conversation.last_message");
            } elseif($filter['type_reading'] == 'unread'){
                $oSelect->where("chathub_conversation.is_read", '>', 0);
            }
            elseif($filter['type_reading'] == 'sent'){
                $oSelect->whereRaw("chathub_conversation.last_message_send != chathub_conversation.last_message");
            }
        }
        $oSelect->orderBy('last_time','DESC');
        $oSelect->select(
            "{$this->table}.customer_register_id",
            "{$this->table}.register_source",
            "{$this->table}.register_object_id",
            "{$this->table}.channel_id",
            "{$this->table}.customer_lead_id",
            "{$this->table}.gender",
            "{$this->table}.full_name",
            "{$this->table}.email",
            "{$this->table}.phone",
            "{$this->table}.avatar",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "chathub_conversation.conversation_id",
            "chathub_conversation.channel_id",
            "chathub_conversation.customer_id",
            "chathub_conversation.last_time",
            "chathub_conversation.last_message_send",
            "chathub_conversation.last_message",
            "chathub_conversation.is_read",
            "chathub_conversation.created_at",
            "chathub_conversation.updated_at",
            "chathub_channel.name as channel_name"
        );
        return $oSelect->limit(10)->get();

    }
    //thêm customer khi scroll tới cuối
    public function addCustomer($customer, $channel_id, $filter){
        $search = isset($filter['search']) ? $filter['search'] : '';
        $oSelect = $this->join('chathub_conversation',$this->table.'.customer_register_id' , '=', 'chathub_conversation.customer_id')
            ->join('chathub_channel', 'chathub_channel.channel_id' , '=', 'chathub_conversation.channel_id')->where('is_subscribed',1)
                    ->where(function($query)use($search){
                        $query ->where('full_name', 'like', '%'.$search.'%')
                            ->orWhere('last_message', 'like', '%'.$search.'%')
                            ->orWhere('last_message_send', 'like', '%'.$search.'%');
                    });

        if($channel_id != "null"){
            $oSelect->where('chathub_conversation.channel_id','=',$channel_id);
        }

        if(isset($filter['type_reading']) && $filter['type_reading'] != ''){
            if($filter['type_reading'] == 'read'){
                $oSelect->whereRaw("chathub_conversation.last_message_send = chathub_conversation.last_message");
            } elseif($filter['type_reading'] == 'unread'){
                $oSelect->where("chathub_conversation.is_read", '>', 0);
            }
            elseif($filter['type_reading'] == 'sent'){
                $oSelect->whereRaw("chathub_conversation.last_message_send != chathub_conversation.last_message");
            }
        }
        $oSelect->select(
            "{$this->table}.customer_register_id",
            "{$this->table}.register_source",
            "{$this->table}.register_object_id",
            "{$this->table}.channel_id",
            "{$this->table}.customer_lead_id",
            "{$this->table}.gender",
            "{$this->table}.full_name",
            "{$this->table}.email",
            "{$this->table}.phone",
            "{$this->table}.avatar",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "chathub_conversation.conversation_id",
            "chathub_conversation.channel_id",
            "chathub_conversation.customer_id",
            "chathub_conversation.last_time",
            "chathub_conversation.last_message_send",
            "chathub_conversation.last_message",
            "chathub_conversation.is_read",
            "chathub_conversation.created_at",
            "chathub_conversation.updated_at",
            "chathub_channel.name as channel_name"
        );
        return $oSelect->orderBy('last_time','DESC')->offset($customer)->limit(5)->get();
    }
    //lấy thông tin customer
    public function getCustomer($customer_id, $channel_id = "null"){
        $oSelect = $this
                    ->where('customer_register_id','=',$customer_id);
        if($channel_id != "null"){
            $oSelect->where('cpo_customer_registers.channel_id','=',$channel_id);
        }
        return $oSelect->first();
    }

    //update customer
    public function updateCustomer($id, $update){
        return $this->where('customer_register_id','=',$id)
            ->update($update);
    }
    public function getSocialId($customer_id){
        $customer = $this->where('customer_register_id', '=', $customer_id)->first();
        return $customer['register_object_id'];
    }
}
