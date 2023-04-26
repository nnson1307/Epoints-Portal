<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 09/05/2022
 * Time: 16:46
 */

namespace Modules\Admin\Http\Api;

use MyCore\Api\ApiAbstract;
use GuzzleHttp\Client;

class StaffApi extends ApiAbstract
{
    /**
     * Đăng ký account chat
     *
     * @param array $data
     * @return mixed
     */
    public function registerStaffAccountChat(array $data = [])
    {
        $data['brand_code'] = session()->get('brand_code');

        $jwt = session('access_token');

        $oClient = new Client([
            'base_uri' => STAFF_API_URL,
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt,
                'staff-token' => $jwt,
                'brand-code' => session()->get('brand_code')
            ],
        ]);
        $response = $oClient->post('/chat/register', [
            'json' => $data
        ]);



        return json_decode($response->getBody(), true);
    }

    /**
     * update account chat
     *
     * @param array $data
     * @return mixed
     */
    public function updateStaffAccountChat(array $data = [])
    {
        $data['brand_code'] = session()->get('brand_code');

        $jwt = session('access_token');

        $oClient = new Client([
            'base_uri' => STAFF_API_URL,
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt,
                'staff-token' => $jwt,
                'brand-code' => session()->get('brand_code')
            ],
        ]);

        $response = $oClient->post('/chat/update-profile', [
            'json' => $data
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Đăng ký account chat
     *
     * @param array $data
     * @return mixed
     */
    public function deleteStaffAccountChat(array $data = [])
    {
        $data['brand_code'] = session()->get('brand_code');

        $jwt = session('access_token');

        $oClient = new Client([
            'base_uri' => STAFF_API_URL,
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt,
                'staff-token' => $jwt,
                'brand-code' => session()->get('brand_code')
            ],
        ]);

        $response = $oClient->post('/chat/remove-user', [
            'json' => $data
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Đăng ký account chat
     *
     * @param array $data
     * @return mixed
     */
    public function getProfileWeb(array $data = [])
    {
        $data['brand_code'] = session()->get('brand_code');

        $jwt = session('access_token');

        $oClient = new Client([
            'base_uri' => STAFF_API_URL,
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt,
                'staff-token' => $jwt,
                'brand-code' => session()->get('brand_code')
            ],
        ]);


        $response = $oClient->post('/chat/profile-web', [
            'json' => $data
        ]);

        $result = json_decode($response->getBody(), true);

        if(!$result['ErrorCode']){
            return $result['Data'];
        }

        return $result['ErrorDescription'];
    }

    public function refeshTokenStaff(array $data = []){
        $data['brand_code'] = session()->get('brand_code');

        $jwt = session('authen_token');
   
        $oClient = new Client([
            'base_uri' => STAFF_API_URL,
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt,
                'brand-code' => $data['brand_code']
            ]
        ]);

        $response = $oClient->post('/user/refresh-token', [
            'json' => $data
        ]);
      
        $result = json_decode($response->getBody(), true);
       
        if(!$result['ErrorCode']){
            return $result['Data'];
        }

        return $result['ErrorDescription'];
    }

    public function loginStaff(array $data = []){
        $data['brand_code'] = session()->get('brand_code');

        $jwt = session('authen_token');

        $oClient = new Client([
            'base_uri' => STAFF_API_URL,
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt
            ]
        ]);

        $response = $oClient->post('/user/login', [
            'json' => $data
        ]);

        return json_decode($response->getBody(), true);
    }
}
