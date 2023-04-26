<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\ZNS\Repositories\Template;


interface TemplateRepositoryInterface
{
    /**
     * Get Template list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * Delete Template
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add Template
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update Template
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);

    /**
     * get item Template
     * @param array $data
     * @return $data
     */
    public function getItem($id);


}