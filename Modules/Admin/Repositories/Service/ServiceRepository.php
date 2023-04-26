<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/10/2018
 * Time: 2:01 PM
 */

namespace Modules\Admin\Repositories\Service;


use Modules\Admin\Models\ServiceTable;

class ServiceRepository implements ServiceRepositoryInterface
{
    protected $service;
    protected $timestamps = true;

    /**
     * ServiceRepository constructor.
     * @param ServiceTable $services
     */
    public function __construct(ServiceTable $services)
    {
        $this->service = $services;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        return $this->service->getList($filters);
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function listPriceService(array $filters = [])
    {
        return $this->service->getListPriceService($filters);
    }

    /**
     * @param number $id
     */
    public function remove($id)
    {
        $this->service->remove($id);
    }

    /**
     * add service Group
     */
    public function add(array $data)
    {
        return $this->service->add($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed|number
     */
    public function edit(array $data, $id)
    {

        return $this->service->edit($data, $id);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->service->getItem($id);
    }

    /**
     * @param $name
     * @param $id
     * @return mixed
     */
    public function testName($name, $id)
    {
        return $this->service->testName($name, $id);
    }

    /**
     * @return mixed|void
     */
    public function getServiceOption()
    {
        $array = array();
        foreach ($this->service->getServiceOption() as $item) {
            $array[$item['service_id']] = $item['service_name'];
        }
        return $array;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getServiceSearch($data)
    {
        return $this->service->getServiceSearch($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItemImage($id)
    {
        return $this->service->getItemImage($id);
    }

    public function getListAdd()
    {
        return $this->service->getListAdd();
    }

    public function getService($name = null, $serviceCategory = null)
    {
        return $this->service->getService($name, $serviceCategory);
    }

    public function getItemServiceSearch($name, $id)
    {

        return $this->service->getItemSearch($name, $id);
    }
}