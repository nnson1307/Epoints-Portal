<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Facebook\Facebook as Facebook;
use Zalo\Zalo;
use Facebook\Exceptions\FacebookSDKException;
use Modules\ChatHub\Repositories\Setting\SettingRepositoryInterface;
use Socialite;
use Auth;
use Modules\ChatHub\Models\ChatHubChannelTable;


class AuthSocialController extends Controller
{
    protected $setting;
    protected $fb;
    protected $channel;
    public function __construct(
        SettingRepositoryInterface $setting,
        Facebook $fb,
        ChatHubChannelTable $channel
    ) {
        $this->setting = $setting;
        $this->fb =$fb;
        $this->channel = $channel;
    }
    public function redirect($social)
    {
        $arrData = [
            'tenant_id' => session('idTenant'),
            'user_id' => Auth::id(),
            'redirect' => route('setting')
        ];
        return Socialite::driver($social)
        ->scopes(['manage_pages','pages_messaging', 'publish_pages', ])
        ->with(['state' => base64_encode(json_encode($arrData))])
        ->redirect();        
    }

    public function callback(Request $request, $social)
    {
        if($social == "zalo"){
            //lấy thong tin Official Account
            
            $infoOA = file_get_contents("https://openapi.zaloapp.com/v2.0/oa/getoa?access_token=".$request->access_token);
            $dataChannel = json_decode($infoOA);

            $this->newChannel($dataChannel->data->oa_id, 2, $dataChannel->data->name, $dataChannel->data->avatar, $dataChannel->data->cover, '', Auth::id(), $request->access_token, 0);
        }
        else {
            $socialUser = Socialite::driver($social)->stateless()->user();
            $user = [
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'avatar' => $socialUser->getAvatar(),
                'service' => 1,
                'social_id' => $socialUser->getId(),
                'access_token' => $socialUser->token,
            ];
            
            try {
                $pageIdListAPI = $this->fb->get($user['social_id'] . '/accounts', $user['access_token']);
            } catch (FacebookSDKException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }

            $pageIdList = $pageIdListAPI->getGraphEdge();

            foreach ($pageIdList as $pageId) {
                // get info a page
                try {
                    $pageProfileAPI = $this->fb->get(
                        $pageId['id'] . '?fields=name,cover,category,picture,link,access_token',
                        $socialUser->token
                    );
                } catch (FacebookSDKException $e) {
                    echo 'Graph returned an error: ' . $e->getMessage();
                    exit;
                }

                $pageProfile = $pageProfileAPI->getGraphNode();
                // check isset cover image, avatar, agent_id
                if (isset($pageProfile['cover']['source'])) {
                    $page_cover = $pageProfile['cover']['source'];
                } else {
                    $page_cover = '';
                }
                if (isset($pageProfile['picture']['url'])) {
                    $page_avatar = $pageProfile['picture']['url'];
                } else {
                    $page_avatar = '';
                }
                if($this->channel->findSocialId($pageProfile['id'])==null){
                    $this->newChannel($pageProfile['id'], 1, $pageProfile['name'], $page_avatar, $page_cover, $pageProfile['link'], Auth::id(), $pageProfile['access_token'], 0);
                }                
            }
        }
        return redirect()->route('setting');
    }

    public function loginZalo(){
        $config = array(
            'app_id' => '868365199196050151',
            'app_secret' => '2Hj3KL1VX74PHcfUOmF4',
            'callback_url' => 'https://516fe1e8384a.ngrok.io/chat-hub/callback/zalo'
        );
        $zalo = new Zalo($config);
        $helper = $zalo -> getRedirectLoginHelper();
        $callbackUrl = "https://516fe1e8384a.ngrok.io/chat-hub/callback/zalo";
        $loginUrl = $helper->getLoginUrlByPage($callbackUrl); // This is login url
        return $loginUrl;

    }

    private function newChannel($channel_social_id, $service, $name, $avatar, $cover_image, $link, $user_id, $channel_access_token, $is_subscribed){
        $dataChannel = [
            'channel_social_id' => $channel_social_id,
            'service_id' => $service,
            'name' => $name,
            'avatar' => $avatar,
            'cover_image' => $cover_image,
            'link' => $link,
            'user_id' => $user_id, //$user->id
            'channel_access_token' => $channel_access_token,
            'is_subscribed' => $is_subscribed
        ];
        $channel = $this->setting->createChannel($dataChannel);
    }
}
