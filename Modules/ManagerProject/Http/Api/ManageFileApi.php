<?php


namespace Modules\Managerproject\Http\Api;


use GuzzleHttp\Client;
use MyCore\Api\ApiAbstract;

class ManageFileApi extends ApiAbstract
{

    /**
     * Login file manage
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function loginManageFIle($token = null)
    {
//        return $this->baseClientLoyaltyApi('/manage-work/send-noti-work', $data, false);
        $data['brand_code'] = session()->get('brand_code');

        if (session()->has('access_token')){
            $access_token = session()->get('access_token');
        } else {
            $access_token = '';
        }
//        $access_token = 'sondang';

        if ($token != null) {
            $access_token = $token;
        }

        $oClient = new Client([
            'base_uri' => getDomain(),
            'http_errors' => true, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'brand-code' => $data['brand_code']
            ]

        ]);

        $response = $oClient->post('/file/api/auth/login', [
            'json' => [
//                'token' => $access_token,
                'epoints_token' => $access_token,
                'brand-code' => $data['brand_code'],
                'decode' => 1
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Login file manage
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function moveFile(array $data = [])
    {
//        return $this->baseClientLoyaltyApi('/manage-work/send-noti-work', $data, false);
        $data['brand_code'] = session()->get('brand_code');

        if (session()->has('access_token')){
            $access_token = session()->get('access_token');
        } else {
            $access_token = '';
        }
        $jwt = session('authen_token');

        $oClient = new Client([
            'base_uri' => getDomain(),
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'Authorization' => 'Bearer ' . $data['token'],
                'brand-code' => $data['brand_code']
            ]
        ]);

        $data['access_token'] = $access_token;

        $response = $oClient->post('/file/api/files/move', [
            'json' => $data
        ]);
        return json_decode($response->getBody(), true);
    }
}