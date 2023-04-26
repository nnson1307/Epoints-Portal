<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/18/2019
 * Time: 2:15 PM
 */

namespace Modules\Admin\Repositories\SmsConfig;


interface SmsConfigRepositoryInterface
{
    /*
     * get item
     */
    public function getItem($id);

    /**
     * Update sms_config
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);

    /*
     * active
     */
    public function activeConfig($param, $data);

    //Lấy tất cả loại tin nhắn.
    public function getAllKey();

    public function getItemByType($type);


}