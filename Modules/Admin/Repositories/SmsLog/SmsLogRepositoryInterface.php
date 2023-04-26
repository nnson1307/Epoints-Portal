<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/19/2019
 * Time: 3:40 PM
 */

namespace Modules\Admin\Repositories\SmsLog;


interface SmsLogRepositoryInterface
{
    public function add(array $data);

    public function getLogCampaign($id);

    public function remove($id);

    //Cancel sms log
    public function cancelLog($type);

    public function saveSmsLog($type, array $fields);

    public function getList($type, $id = null);

    public function getAll();

    public function getAllLogNew($timeSent);

    public function getAllLogNewNoTimeSend($timeSent);

    public function edit(array $data, $id);

    public function getItem($id);

    public function getLogDetailCampaign($id, array $filter = []);

    public function cancelLogCampaign($id);

    public function getSmsSend($timeSend);
}