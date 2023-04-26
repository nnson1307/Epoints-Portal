<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/10/2018
 * Time: 12:33 PM
 */

namespace Modules\Admin\Repositories\Service;


interface ServiceRepositoryInterface
{
    /**
     * Get service list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

    /**
     * @param array $filters
     * @return mixed
     */
    public function listPriceService(array $filters = []);
    /**
     * Delete service
     *
     * @param number $id
     */
    public function remove($id);


    /**
     * Add service
     * @param array $data
     * @return number
     */
    public function add(array $data);
    /**
     * Update service
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);
    /**
     * Update OR ADD service
     * @param array $data
     * @return number
     */

    /**
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);

    /**
     * @param $name
     * @param $id
     * @return mixed
     */
    public function testName($name, $id);

    /**
     * @return mixed
     */
    public function getServiceOption();

    /**
     * @param $data
     * @return mixed
     */
    public function getServiceSearch($data);

    /**
     * @param $id
     * @return mixed
     */
    public function getItemImage($id);

    /**
     * @return mixed
     */
    public function getListAdd();

    /**
     * @return mixed
     */
    public function getService($name=null,$serviceCategory=null);

    /**
     * search name select2
     */
    public function getItemServiceSearch($name, $id);
}
