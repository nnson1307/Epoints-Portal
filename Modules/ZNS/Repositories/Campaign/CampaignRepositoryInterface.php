<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\ZNS\Repositories\Campaign;


interface CampaignRepositoryInterface
{
    /**
     * Get Campaign list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Delete Campaign
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add Campaign
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update Campaign
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);

    /**
     * get item Campaign
     * @param array $data
     * @return $data
     */
    public function getItem($id);


}