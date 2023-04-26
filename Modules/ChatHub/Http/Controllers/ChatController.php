<?php

namespace Modules\ChatHub\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Http\Api\StaffApi;
use Modules\Admin\Models\ConfigTable;

/**
 * Class ChatController
 * @package Modules\ChatHub\Http\Controllers
 * @author VuND
 * @since 31/05/2022
 */
class ChatController extends Controller
{
//    public function __invoke()
//    {
//        return view('chathub::chat.new');
//    }

    public function indexAction(){

//        $data = DB::table('staffs')->where('is_deleted',0)
//            ->whereNotIn('staff_id', [1,13])->get()->toArray();
//        $mStaffApi = app()->get(StaffApi::class);
//        foreach ($data as $item){
//            //Call api đăng ký tài khoản chat
//            $resStaffApi = $mStaffApi->registerStaffAccountChat([
//                'staff_id' => $item->staff_id,
//                'password' => '123456'
//            ]);
//        }
//
//        dd(123);

        $mConfig = new ConfigTable();

        $arrConfigKey = $mConfig->getAllKey();

        $domainChat = $arrConfigKey['domain_chat'];
        $domainMain = $arrConfigKey['domain_main'];

        $mStaffApi = app()->get(StaffApi::class);

        $staffLogin = $mStaffApi->refeshTokenStaff([
            'refresh_token' => session()->get('access_token'),
            'platform' => 'android',
            'device_token' => 'portal123',
            'imei' => 'portal123',
            'brand_code' => session()->get('brand_code')
        ]);


        setcookie('EPOINTS_SSO', $staffLogin['access_token'], 0, "/", $domainMain);

        return view('chathub::chat.index', ['domain' => $domainChat]);
    }

    public function newAction(){
        return view('chathub::chat.new');
    }

    public function getNotificationCount(){
        // if (session()->has('brand_code') && in_array(session()->get('brand_code'), ['matthewsliquor', 'matthews', 'qc'])) {
        //     $mStaffApi = app()->get(StaffApi::class);
        //     //Call api đăng ký tài khoản chat
        //     $res = $mStaffApi->getProfileWeb();
        //     $total = 0;
        //     if($res != null && isset($res['user'])){
        //         $total = $res['user']['roomAlert'];
        //     }
        //     return response()->json(['total' => $total]);
        // }
        if (in_array('chathub.chat', session('routeList'))) {
            $mStaffApi = app()->get(StaffApi::class);
            //Call api đăng ký tài khoản chat
            $res = $mStaffApi->getProfileWeb();
            $total = 0;
            if($res != null && isset($res['user'])){
                $total = $res['user']['roomAlert'];
            }
            return response()->json(['total' => $total]);
        }else {
            return response()->json(['total' => 0]);
        }

    }
}
