<?php


/**
 * @Author : VuND
 */

namespace Modules\ChatHub\Http\Api;


use MyCore\Api\ApiAbstract;

class ChatHub extends ApiAbstract
{
    public function storeChannel($filter = []){
        return $this->baseClientPushNotification('chathub/channel/store', $filter, false);
    }

    public function subscribeChannel($filter = []){
        return $this->baseClientPushNotification('chathub/channel/subscribed', $filter, false);
    }
}
