<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/14/2018
 * Time: 2:17 PM
 */

namespace Modules\Admin\Repositories\CustomerAppointmentTime;


interface CustomerAppointmentTimeRepositoryInterface
{
    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data);

    /**
     * @return mixed
     */
    public function getTimeOption();

    /**
     * @param $time
     * @param $id
     * @return mixed
     */
    public function testTime($time, $id);
}