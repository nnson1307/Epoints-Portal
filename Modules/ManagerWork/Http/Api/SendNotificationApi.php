<?php


namespace Modules\ManagerWork\Http\Api;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use MyCore\Api\ApiAbstract;

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
//        return $this->baseClientLoyaltyApi('/manage-work/send-noti-work', $data, false);
        $data['brand_code'] = session()->get('brand_code');

        $jwt = session('authen_token');

        $oClient = new Client([
            'base_uri' => STAFF_API_URL,
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt
            ]
        ]);

        $response = $oClient->post('/manage-work/send-noti-work', [
            'json' => $data
        ]);

        return json_decode($response->getBody(), true);
    }
}