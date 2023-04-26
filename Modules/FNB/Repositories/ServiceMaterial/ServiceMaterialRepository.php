<?php


namespace Modules\FNB\Repositories\ServiceMaterial;


use Modules\FNB\Models\ServiceMaterialTable;

class ServiceMaterialRepository implements ServiceMaterialRepositoryInterface
{
    protected $service_material;
    protected $timestamps=true;
    public function __construct(ServiceMaterialTable $service_materials)
    {
        $this->service_material=$service_materials;
    }
    public function getItem($id)
    {
        return $this->service_material->getItem($id);
    }
}