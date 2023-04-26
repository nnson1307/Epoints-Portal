<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/12/2018
 * Time: 4:16 PM
 */

namespace Modules\Admin\Repositories\AppointmentService;


interface AppointmentServiceRepositoryInterface
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
    public function detailCustomer($id);
}