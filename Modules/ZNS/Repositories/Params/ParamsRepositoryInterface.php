<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\ZNS\Repositories\Params;


interface ParamsRepositoryInterface
{
    /**
     * Get Params list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Delete Params
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add Params
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update Params
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);

    /**
     * get item Params
     * @param array $data
     * @return $data
     */
    public function getItem($id);

}