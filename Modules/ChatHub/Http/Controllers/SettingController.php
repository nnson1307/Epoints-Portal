<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Facebook\Facebook as Facebook;
use Modules\ChatHub\Http\Requests\Setting\UpdateRequest;
use Modules\ChatHub\Repositories\Setting\SettingRepositoryInterface;
use Auth;

class SettingController extends Controller
{
    protected $setting;
    public function __construct(
        SettingRepositoryInterface $setting
    ) {
        $this->setting = $setting;
    }
    //màn hình chính của setting
    public function indexAction(Request $request){
        try{
            $channelList = $this->setting->getChannelList(Auth::id());
            return view('chathub::setting.index',[
                'channelList'=>$channelList
            ]);
        }catch (\Exception $e) {
            return $e->getMessage();

        }
    }
    //hiện popup thêm channel
    public function addChannel(Request $request){
        try{
            return view('chathub::setting.popup.channel');
        }catch (\Exception $e) {
            return $e->getMessage();
        }

    }
    //hiện popup chỉnh sửa channel
    public function showPopupEdit(Request $request){
        try{
            $item = $this->setting->getChannel($request->id);
            return view('chathub::setting.popup.edit',[
                'item' => $item
            ]);
        }catch (\Exception $e) {
            return $e->getMessage();
        }

    }
    public function saveChannel(UpdateRequest $request){
        $id = $request->channel_id;
        $data = $request->all();
        return $this->setting->saveChannel($data,$id);
    }
    //subcribe một channel
    public function subscribeChannel(Request $request){
        try{
            $data=$request->all();

            $result=$this->setting->subscribeChannel($data['id']);

            return response()->json($result);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function unsubscribeChannel(Request $request){
        try{
            $data=$request->all();
            $result = $subscribe=$this->setting->unsubscribeChannel($data['id']);

            return response()->json($result);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function showOption(Request $request){
        try{
            $data=$request->all();
            $option=$this->setting->showOption($data['id'], $data['check']);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    
}
