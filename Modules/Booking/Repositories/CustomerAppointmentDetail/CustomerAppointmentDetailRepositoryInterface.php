<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 16/1/2019
 * Time: 17:40
 */

namespace Modules\Booking\Repositories\CustomerAppointmentDetail;


interface CustomerAppointmentDetailRepositoryInterface
{
    public function add(array $data);

    public function edit(array $data, $id);
    public function groupItem($customer_appointment_id);
    public function getItem($customer_appointment_id);
    public function remove($id);
    public function groupItemDetail($customer_appointment_id);
}