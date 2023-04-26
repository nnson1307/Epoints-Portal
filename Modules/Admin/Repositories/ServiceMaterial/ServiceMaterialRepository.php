<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/16/2018
 * Time: 3:53 PM
 */

namespace Modules\Admin\Repositories\ServiceMaterial;


use Modules\Admin\Models\ServiceMaterialTable;

class ServiceMaterialRepository implements ServiceMaterialRepositoryInterface
{
    protected $service_material;
    protected $timestamps=true;
    public function __construct(ServiceMaterialTable $service_materials)
    {
        $this->service_material=$service_materials;
    }
    public function list(array $filters = [])
    {
        return $this->service_material->getList($filters);
    }
    public function remove($id)
    {
        $this->service_material->remove($id);
    }

    /**
     * add service_category Group
     */
    public function add(array $data)
    {
        return $this->service_material->add($data);
    }
    /*
     * edit service_category Group
     */
    public function edit(array $data ,$id)
    {

        return $this->service_material->edit($data,$id);
    }
    /*
     *  update or add
     */

    public function getItem($id)
    {
        return $this->service_material->getItem($id);
    }
    public function getSelect($id)
    {
        $get=$this->service_material->getSelectProduct($id);
        $array=[];
        foreach ($get as $item)
        {

            $array[]=$item['material_Id'];
        }
        return $array;
    }
    public function deleteWhenEdit(array $data, $id)
    {
        return $this->service_material->deleteWhenEdit($data,$id);
    }
    public function getListServiceDetail($id, array $filters = [])
    {
        // TODO: Implement getListServiceDetail() method.
        return $this->service_material->getListServiceDetail($id,$filters);
    }

    public function getSelectMaterialsService($id)
    {
        return $this->service_material->getSelectMaterialsService($id);
    }

    public function deleteItem($id)
    {
        return $this->service_material->deleteItem($id);
    }
}