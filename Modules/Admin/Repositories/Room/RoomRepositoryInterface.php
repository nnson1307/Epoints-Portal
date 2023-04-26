<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/27/2018
 * Time: 2:11 PM
 */

namespace Modules\Admin\Repositories\Room;


interface RoomRepositoryInterface
{
    public function list(array $filters=[]);

    /**
     * Add unit
     *
     * @param array $data
     * @return number
     */
    public function add(array $data);
    /**

     * Delete unit
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Edit unit
     *
     * @param array $data,$id
     * @return number
     */
    public function edit(array $data,$id);

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id);

    /**
     * @return mixed
     */
    public function getRoomOption() ;

    /**
     * @param $name
     * @param $id
     * @return mixed
     */
    public function testName($name, $id);
}