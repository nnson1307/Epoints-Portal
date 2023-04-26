<?php


namespace Modules\FNB\Http\Api;


use MyCore\Api\ApiAbstract;
use GuzzleHttp\Client;

class SendNotificationApi extends ApiAbstract
{
    /**
     * Send notification
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function sendNotification(array $data = [])
    {
        return $this->baseClientLoyaltyApi('/notification/send-notification', $data, false);
    }

    /**
     * Gửi thông báo nhân viên
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function sendStaffNotification(array $data = [])
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

        $response = $oClient->post('/notification/send-staff-notification', [
            'json' => $data
        ]);

        return json_decode($response->getBody(), true);
    }
}