<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\Ticket\Repositories\MaterialDetail;


interface MaterialDetailRepositoryInterface
{
    /**
     * Delete MaterialDetail
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add MaterialDetail
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update MaterialDetail
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);

    /**
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);

    /**
     * get item
     * @param $id
     * @return $data
     */

    public function getItemByMaterialId($id);
    
    /**
     * reomve item
     * @param $id
     * @return $data
     */

    public function removeByMaterialId($id);
}