<?php
/**
 * Created by PhpStorm.
 * User: nhu
 * Date: 26/03/2018
 * Time: 21:30
 */

namespace Modules\ChatHub\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;


class ChatHubChannelTable extends Model
{

    use ListTableTrait;

    /*
     * table service_package
     */
    protected $table = 'chathub_channel';
    protected $primaryKey = 'channel_id';

    /*
     * fill table
     * $var array
     */
    protected $fillable = [
        'channel_id',
        'channel_social_id', 
        'service_id', 
        'name', 
        'avatar', 
        'cover_image', 
        'link', 
        'user_id', 
        'channel_access_token', 
        'is_subscribed', 
        'is_dialogflow',
        'project_id_dialogflow',
        'private_key_dialogflow', 
        'client_email_dialogflow', 
        'is_deleted', 
        'show_option', 
        'created_at', 
        'updated_at'
    ];
    // thêm một channel
    public function createChannel($data){
        $insert = $this->create($data);
        return $insert->channel_id;
    }
    //lấy danh sách channel của user
    public function getList($id){
        return $this->get();
        // return $this->where('user_id','=',$id)->get();
    }

    //lấy danh sách channel subscribed của user
    public function getListSubcribed($id){
        return $this->where('is_subscribed',1)->orderBy('channel_id')->get();
    }
    
    //lấy một channel của user
    public function getChannel($id){
        return $this->where('channel_id',$id)->first();
    }
    //subcribe channel
    public function updateChannel($id, $data){
        return $this->where('channel_id',$id)->update($data);
    }
    //xem id social có tồn tại
    public function findSocialId($id){
        $find= $this->where('channel_social_id',$id)->first();
        return $find['channel_social_id'];
    }
    //lấy token
    public function getToken($channel_id){
        $item= $this->where('channel_id', $channel_id)->first();
        return $item['channel_access_token'];
    }
}
