<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 5/8/2020
 * Time: 4:20 PM
 */

namespace Modules\Notification\Http\Api;


use MyCore\Api\ApiAbstract;

class PushNotification extends ApiAbstract
{
    /**
     * Gọi api push thông báo gửi tất cả
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function pushAllMyStore(array $data = [])
    {
        return $this->baseClientPushNotification('/push-noti/broadcast', $data);
    }

    /**
     * Gọi api push thông báo gửi nhóm
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function pushGroupMyStore(array $data = [])
    {
        return $this->baseClientPushNotification('/push-noti/group', $data);
    }
}