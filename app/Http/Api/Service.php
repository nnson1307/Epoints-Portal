<?php


/**
 * @Author : VuND
 */

namespace App\Http\Api;


use MyCore\Api\ApiAbstract;

class Service extends ApiAbstract
{
    public function getAllBrand($filter = []){
        return $this->baseClientPushNotification('admin/brand/get-all', $filter, false);
    }
}
