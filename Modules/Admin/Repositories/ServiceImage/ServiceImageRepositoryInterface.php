<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/6/2018
 * Time: 2:09 PM
 */

namespace Modules\Admin\Repositories\ServiceImage;


interface ServiceImageRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data);

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id);

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id);

    /**
     * @param $name
     * @return mixed
     */
    public function remove($name);
}