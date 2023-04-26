<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/26/2019
 * Time: 1:39 PM
 */

namespace Modules\Admin\Repositories\SendSms;


interface SendSmsRepositoryInterface
{
    public function sendOneSms($idLog);
}