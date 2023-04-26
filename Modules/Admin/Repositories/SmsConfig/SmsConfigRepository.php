<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/18/2019
 * Time: 2:15 PM
 */

namespace Modules\Admin\Repositories\SmsConfig;

use Modules\Admin\Models\SmsConfigTable;

class SmsConfigRepository implements SmsConfigRepositoryInterface
{
    protected $smsConfig;
    protected $timestamps = true;

    public function __construct(SmsConfigTable $smsConfig)
    {
        $this->smsConfig = $smsConfig;
    }

    public function getItem($id)
    {
        return $this->smsConfig->getItem($id);
    }

    /*
     * edit sms config
     */
    public function edit(array $data, $id)
    {
        return $this->smsConfig->edit($data, $id);
    }

    /*
     * active
     */
    public function activeConfig($param, $data)
    {
        return $this->smsConfig->activeConfig($param, $data);
    }

    //Lấy tất cả loại tin nhắn.
    public function getAllKey()
    {
        return $this->smsConfig->getAllKey();
    }

    public function getItemByType($type){
        return $this->smsConfig->getItemByType($type);
    }
}