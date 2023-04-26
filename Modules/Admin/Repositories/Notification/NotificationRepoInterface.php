<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 07-04-02020
 * Time: 2:39 PM
 */

namespace Modules\Admin\Repositories\Notification;


interface NotificationRepoInterface
{
    /**
     * Insert notification log
     *
     * @param $key
     * @param $objectId
     * @return mixed
     */
    public function insertLogNotification($key, $objectId);
}