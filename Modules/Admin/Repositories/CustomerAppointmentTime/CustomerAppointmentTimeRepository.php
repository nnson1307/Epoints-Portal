<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/14/2018
 * Time: 2:17 PM
 */

namespace Modules\Admin\Repositories\CustomerAppointmentTime;


use Modules\Admin\Models\CustomerAppointmentTimeTable;

class CustomerAppointmentTimeRepository implements CustomerAppointmentTimeRepositoryInterface
{
    protected $customer_appointment_time;
    protected $timestamps=true;
    public function __construct(CustomerAppointmentTimeTable $customer_appointment_times)
    {
        $this->customer_appointment_time=$customer_appointment_times;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters=[])
    {
        return $this->customer_appointment_time->getList($filters);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->customer_appointment_time->add($data);
    }

    /**
     * @return array|mixed
     */
    public function getTimeOption()
    {

        $array=array();
        foreach ($this->customer_appointment_time->getTimeOption() as $item)
        {
            $array[$item['customer_appointment_time_id']]=date("H:i",strtotime($item['time']));

        }
        return $array;
    }
    public function testTime($time, $id)
    {
        return $this->customer_appointment_time->testTime($time,$id);
    }

}