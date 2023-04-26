<?php

namespace Modules\ChatHub\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use MyCore\Models\Traits\ListTableTrait;

class ChatHubPostTable extends Model
{
    use ListTableTrait;
    protected $table = 'chathub_post';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'message', 'response_id', 'date_comment','video', 'channel_id', 'key', 'post_id', 'active', 'created_at', 'updated_at','date_comment'
    ];

    public function _getList(array &$filter = []){
        
        $postList= $this->orderBy('id', 'DESC')
                    ->join('chathub_channel','chathub_channel.channel_id', '=',$this->table.'.channel_id')
                    ->where('chathub_channel.is_subscribed', '=', 1);
        if(isset($filter['post_name'])){
            $postList->where('chathub_post.message', 'like', '%' . $filter['post_name'] . '%');
            unset($filter['post_name']);
        }
        if(isset($filter['data_time'])){
            $time=Carbon::createFromFormat('m/d/Y', $filter['data_time'])->format('Y-m-d');
            $postList->where($this->table.'.date_comment', '>=', $time)->where($this->table.'.date_comment', '<=', $time . ' 23:59:59');
            unset($filter['data_time']);
        }
        return $postList;
    }

    public function getPost($id){
        return $this->where('id', '=', $id)->first();
    }
    public function updateKey($id, $brand, $sku, $sub_brand, $attribute){
        return $this->where('id', '=', $id)
                    ->update(['brand'=>$brand, 'sku'=>$sku, 'sub_brand'=>$sub_brand, 'attribute'=>$attribute]);
    }
    public function subcribe($id){
        return $this->where('id', '=', $id)
                    ->update(['active'=>1]);
    }
    public function unsubcribe($id){
        return $this->where('id', '=', $id)
                    ->update(['active'=>0]);
    }
}
