<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/06/2022
 * Time: 17:18
 */

namespace Modules\FNB\Http\Api;

use MyCore\Api\ApiAbstract;

class ApiQueue extends ApiAbstract
{
    /**
     * Gọi function push notify
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function functionSendNotify(array $data = [])
    {
        return $this->baseClientPushNotification('/job-notify/trigger-send-notify', $data);
    }

    /**
     * Gọi function send notify staff (có qua bước replace data)
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function functionSendNotifyStaff(array $data = [])
    {
        return $this->baseClientStaffPushNotification('/job-notify/trigger-send-notify', $data);
    }

    /**
     * Gọi function push notify staff (push thẳng)
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function functionPushNotifyStaff(array $data = [])
    {
        return $this->baseClientStaffPushNotification('/notification/push', $data);
    }
}