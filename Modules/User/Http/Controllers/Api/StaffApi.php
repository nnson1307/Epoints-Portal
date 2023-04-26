<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 22/11/2021
 * Time: 15:05
 */

namespace Modules\User\Http\Controllers\Api;

use GuzzleHttp\Client;

class StaffApi
{
    /**
     * Đăng
     *
     * @param array $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function registerDeviceToken(array $data = [])
    {
        $data['brand_code'] = session()->get('brand_code');

        $jwt = session('authen_token');

        $oClient = new Client([
            'base_uri' => STAFF_API_URL,
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt
            ]
        ]);

        $response = $oClient->post('/user/register-device-token', [
            'json' => $data
        ]);

        return json_decode($response->getBody(), true);
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

    public function refeshTokenStaff(array $data = []){
//        $data['brand_code'] = session()->get('brand_code');
        $data['brand_code'] = session()->get('brand_code');

        $jwt = session('authen_token');

        $oClient = new Client([
            'base_uri' => STAFF_API_URL,
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt
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
}