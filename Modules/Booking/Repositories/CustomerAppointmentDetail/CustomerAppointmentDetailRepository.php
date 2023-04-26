<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 16/1/2019
 * Time: 17:40
 */

namespace Modules\Booking\Repositories\CustomerAppointmentDetail;


use Modules\Booking\Models\CustomerAppointmentDetailTable;

class CustomerAppointmentDetailRepository implements CustomerAppointmentDetailRepositoryInterface
{
    protected $customer_appointment_detail;
    protected $timestamps = true;

    public function __construct(CustomerAppointmentDetailTable $customer_appointment_details)
    {
        $this->customer_appointment_detail = $customer_appointment_details;
    }

    public function add(array $data)
    {
        // TODO: Implement add() method.
        return $this->customer_appointment_detail->add($data);
    }

    public function edit(array $data, $id)
    {
        // TODO: Implement edit() method.
        return $this->customer_appointment_detail->add($data, $id);
    }
    public function groupItem($customer_appointment_id)
    {
        // TODO: Implement groupItem() method.
        return $this->customer_appointment_detail->groupItem($customer_appointment_id);
    }

    public function getItem($customer_appointment_id)
    {
        // TODO: Implement getItem() method.
        return $this->customer_appointment_detail->getItem($customer_appointment_id);
    }
    public function remove($id)
    {
        return $this->customer_appointment_detail->remove($id);
    }
    public function groupItemDetail($customer_appointment_id)
    {
        // TODO: Implement groupItemDetail() method.
        return $this->customer_appointment_detail->groupItemDetail($customer_appointment_id);
    }
}