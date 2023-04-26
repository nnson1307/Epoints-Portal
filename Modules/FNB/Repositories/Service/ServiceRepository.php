<?php


namespace Modules\FNB\Repositories\Service;


use Modules\FNB\Models\ServiceTable;

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
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->service->getItem($id);
    }
}