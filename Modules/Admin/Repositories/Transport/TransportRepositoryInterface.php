<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/27/2018
 * Time: 10:03 AM
 */

namespace Modules\Admin\Repositories\Transport;


interface TransportRepositoryInterface
{
    public function list(array $filters=[]);

    /**
     * Add transport
     *
     * @param array $data
     * @return number
     */
    public function add(array $data);
    /**

     * Delete transport
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Edit transport
     *
     * @param array $data,$id
     * @return number
     */
    public function edit(array $data,$id);
    public function getItem($id);
    public function testName($name,$id);
}