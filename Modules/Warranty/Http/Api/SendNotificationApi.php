<?php

namespace Modules\Warranty\Http\Api;

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
}