<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 8/29/2019
 * Time: 11:43 AM
 */

namespace Modules\ChatHub\Repositories\Setting;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Http\Api\ChatHub;
use Modules\ChatHub\Models\ChatHubChannelTable;
use Modules\ChatHub\Models\BOBrandTable;
use Modules\ChatHub\Models\BOChannelTable;
use Modules\Admin\Models\StaffTable;
use Facebook\Facebook as Facebook;

class SettingRepository implements SettingRepositoryInterface
{
    protected $channel;
    protected $staff;
    protected $fb;
    protected $bo_brand;
    protected $bo_channel;
    public function __construct(
        ChatHubChannelTable $channel,
        StaffTable $staff,
        Facebook $fb,
        BOBrandTable $bo_brand,
        BOChannelTable $bo_channel
    )
    {
        $this->channel = $channel;
        $this->staff=$staff;
        $this->fb=$fb;
        $this->bo_brand=$bo_brand;
        $this->bo_channel=$bo_channel;
    }
    public function createChannel($data){

        $oApi = new ChatHub();
        $dataApi = [
            'tenant_id' => session('idTenant'),
            'channel_social_id' => $data['channel_social_id'],
            'channel_name' => $data['name'],
        ];
        $result = $oApi->storeChannel($dataApi);
        if($result){
            return $this->channel->createChannel($data);
        }

        return false;

    }
    public function getChannelList($id){
        return $this->channel->getList($id);
    }
    public function unsubscribeChannel($id){
        $channel = $this->channel->getChannel($id);

        $oApi = new ChatHub();

        $dataApi = [
            'tenant_id' => session('idTenant'),
            'channel_social_id' => $channel->channel_social_id,
            'is_subscribed' => 0
        ];

        $result = $oApi->subscribeChannel($dataApi);

        if(isset($result['ErrorCode']) && $result['ErrorCode']){
            return [
                'errors' => 1
            ];
        }

        if($channel['service_id'] == 1) {
            try {
                $response = $this->fb->delete("/" . $channel->channel_social_id . "/subscribed_apps", ['subscribed_fields' => 'messages', 'access_token' => $channel->channel_access_token]);
                $result = $response->getGraphNode();
                if ($result["success"] == true) {
                    $data = ['is_subscribed' => 0];
                    $this->channel->updateChannel($id, $data);
                }
            } catch (FacebookSDKException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }
            //delete persistent menu
            try {
                $this->fb->delete("/me/messenger_profile",
                    ["fields" => ['persistent_menu'], "access_token" => $channel->channel_access_token]);
            } catch (FacebookSDKException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }
        }
        else {
            $data = ['is_subscribed' => 0];
            $this->channel->updateChannel($id, $data);
        }
    }
    public function subscribeChannel($id){
        $channel = $this->channel->getChannel($id);

        $oApi = new ChatHub();

        $dataApi = [
            'tenant_id' => session('idTenant'),
            'channel_social_id' => $channel->channel_social_id,
            'is_subscribed' => 1
        ];

        $result = $oApi->subscribeChannel($dataApi);

        if(isset($result['ErrorCode']) && $result['ErrorCode']){
            return [
                'errors' => 1
            ];
        }

        if($channel['service_id'] == 1) {
            try {
                $response = $this->fb->post("/" . $channel->channel_social_id . "/subscribed_apps",
                        ['subscribed_fields' => ['messages', 'message_echoes', 'messaging_postbacks', 'feed'], 'access_token' => $channel->channel_access_token]);
                $result = $response->getGraphNode();
                if ($result["success"] == true) {
                    $data = ['is_subscribed' => 1];
                    $this->channel->updateChannel($id, $data);
                }
            } catch (FacebookSDKException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }
            
            if ($channel->show_option == 1) {
                // button start
                $btnstartData = json_encode([
                    "payload" => "USER_DEFINED_PAYLOAD"
                ]);

                $persistentData = '
                {
                    "persistent_menu": [
                        {
                            "locale": "default",
                            "composer_input_disabled": false,
                            "call_to_actions": [
                                {
                                    "type": "postback",
                                    "title": "Text 1",
                                    "payload": "Payload 1"
                                },
                                {
                                    "type": "postback",
                                    "title": "Text 2",
                                    "payload": "Payload 2"
                                }
                            ]
                        }
                    ]
                }
                ';

                $persistent = json_decode($persistentData, true);
                try {
                    $btnstart = $this->fb->post("/me/messenger_profile",
                        [
                            "persistent_menu" => $persistent['persistent_menu'],
                            "access_token" => $channel->channel_access_token,
                            "get_started" => [
                                'payload' => 'GET_STARTED_PAYLOAD'
                            ]
                        ]);
                } catch(FacebookSDKException $e) {
                    echo 'Graph returned an error: ' . $e->getMessage();
                    exit;
                }
            }

            return [
                'errors' => 0
            ];
        }

        return [
            'errors' => 1
        ];
    }

    public function showOption($id, $check){
        $channel = $this->channel->getChannel($id);
        //facebook
        if($channel->service_id == 1) {
            // thêm show_option
            if ($check == 1) {
                $btnstartData = json_encode([
                    "payload" => "USER_DEFINED_PAYLOAD"
                ]);

                $persistentData = '
                {
                    "persistent_menu": [
                        {
                            "locale": "default",
                            "composer_input_disabled": false,
                            "call_to_actions": [
                                {
                                    "type": "postback",
                                    "title": "Text 1",
                                    "payload": "Payload 1"
                                },
                                {
                                    "type": "postback",
                                    "title": "Text 2",
                                    "payload": "Payload 2"
                                }
                            ]
                        }
                    ]
                }
                ';

                $persistent = json_decode($persistentData, true);
                try {
                    $btnstart = $this->fb->post("/me/messenger_profile",
                        [
                            "persistent_menu" => $persistent['persistent_menu'],
                            "access_token" => $channel->channel_access_token,
                            "get_started" => [
                                'payload' => 'GET_STARTED_PAYLOAD'
                            ]
                        ]);
                } catch(FacebookSDKException $e) {
                    echo 'Graph returned an error: ' . $e->getMessage();
                    exit;
                }    
            }
            else{
                //xóa show_option
                $btnstartData = json_encode([
                    "payload" => "USER_DEFINED_PAYLOAD"
                ]);
    
                $persistentData = '
                {
                    "persistent_menu": [
                        {
                            "locale": "default",
                            "composer_input_disabled": false,
                            "call_to_actions": [
                            ]
                        }
                    ]
                }
                ';
    
                $persistent = json_decode($persistentData, true);
                try {
                    $btnstart = $this->fb->post("/me/messenger_profile",
                        [
                            "persistent_menu" => $persistent['persistent_menu'],
                            "access_token" => $channel->channel_access_token,
                            "get_started" => [
                                'payload' => 'GET_STARTED_PAYLOAD'
                            ]
                        ]);
                } catch(FacebookSDKException $e) {
                    echo 'Graph returned an error: ' . $e->getMessage();
                    exit;
                }
                $data = ['show_option' => $check];
                $this->channel->updateChannel($id, $data);
            }
            $data = ['show_option' => $check];
            $this->channel->updateChannel($id, $data);
        } else{
            $data = ['show_option' => $check];
            $this->channel->updateChannel($id, $data);
        }
            
    }

    public function getChannel($id)
    {
        return $this->channel->getChannel($id);
    }
    public function saveChannel(array $data,$id){
        try{
            $dataUpdate = [];
            if($data['is_dialogflow'] == 0){
                $dataUpdate['is_dialogflow'] = $data['is_dialogflow'];
            }
            else{
                $dataUpdate = $data;
            }
            $this->channel->updateChannel($id, $dataUpdate);
            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ]);
        }catch (\Exception $ex){
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa thất bại')
            ]);
        }
    }
}
