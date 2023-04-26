<?php

namespace Modules\ChatHub\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;
use Carbon\Carbon;


class ChatHubCommentTable extends Model
{
    use ListTableTrait;
    protected $table = 'chathub_comment';
    protected $fillable = [
        'id', 'customer_id', 'message', 'image', 'video', 'post_id', 'comment_id', 'created_at', 'updated_at','date_comment'
    ];

    public function _getList(&$filter = []){
        $listComment = $this->select('chathub_comment.id as cmt_id','cpo_customer_registers.full_name as cus_name','chathub_channel.name as cha_name','chathub_post.message as mes', 'chathub_post.image as img' ,'chathub_comment.post_id', 'chathub_comment.message', 'chathub_comment.image','chathub_comment.message', 'chathub_comment.date_comment', 'chathub_comment.comment_id')
                    ->orderBy('chathub_comment.id', 'DESC')
                    ->join('chathub_post', 'chathub_post.id', '=', $this->table.'.post_id')
                    ->join('chathub_channel','chathub_channel.channel_id', '=','chathub_post.channel_id')
                    ->join('cpo_customer_registers', 'cpo_customer_registers.customer_register_id', '=', 'chathub_comment.customer_id');
        
        // if(isset($filter['data_value']) && !empty($filter['data_value'])){
        //     $listComment->where('chathub_comment.message', 'like', '%' . ['data_value'] . '%')
        //                 ->orwhere('chathub_post.message', 'like', '%' . ['data_value'] . '%');
        //     unset($filter['data_value']);
        // }
        // if(isset($filter['data_time']) && !empty($filter['data_time'])){
        //     $time=Carbon::createFromFormat('m/d/Y', $filter['data_time'])->format('Y-m-d');
        //     $listComment->where($this->table.'.date_comment', '>=', $time)->where($this->table.'.date_comment', '<=', $time . ' 23:59:59');
        //     unset($filter['data_time']);
        // }
        return $listComment;
    }
}
