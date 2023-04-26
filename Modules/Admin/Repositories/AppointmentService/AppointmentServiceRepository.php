<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/12/2018
 * Time: 4:15 PM
 */

namespace Modules\Admin\Repositories\AppointmentService;


use Modules\Admin\Models\AppointmentServiceTable;

class AppointmentServiceRepository implements AppointmentServiceRepositoryInterface
{
    protected $appointment_service;
    protected $timestamps=true;

    /**
     * AppointmentServiceRepository constructor.
     * @param AppointmentServiceTable $appointment_services
     */
    public function __construct(AppointmentServiceTable $appointment_services)
    {
        $this->appointment_service=$appointment_services;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->appointment_service->add($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->appointment_service->edit($data,$id);
    }
    public function detailCustomer($id)
    {
        return $this->appointment_service->detailCustomer($id);
    }
}