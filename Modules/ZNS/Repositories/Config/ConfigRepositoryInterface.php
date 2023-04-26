<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\ZNS\Repositories\Config;


interface ConfigRepositoryInterface
{
    /**
     * Get Config list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Delete Config
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add Config
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update Config
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);

    /**
     * get item Config
     * @param $id
     * @return $data
     */
    public function getItem($id);

    /**
     * Lưu log CSKH ZNS
     *
     * @param $key
     * @param $userId
     * @param $objectId
     * @return mixed
     */
    public function sendNotification($key, $userId, $objectId);
}