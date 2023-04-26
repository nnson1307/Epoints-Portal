<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 10/12/2018
 * Time: 10:29 AM
 */

namespace Modules\Admin\Repositories\ServiceCardGroup;


use Modules\Admin\Models\ServiceCardGroup;

class ServiceCardGroupRepository implements ServiceCardGroupRepositoryInterface
{
    private $serviceCardGroup;

    public function __construct(ServiceCardGroup $cardGroup)
    {
        $this->serviceCardGroup = $cardGroup;
    }

    public function list(array $filters = [])
    {
        return $this->serviceCardGroup->getList($filters);
    }

    public function add(array $data)
    {
        return $this->serviceCardGroup->add($data);
    }

    public function getItem($id)
    {
        return $this->serviceCardGroup->getItem($id);
    }

    public function getAllName()
    {
        return $this->serviceCardGroup->getAllName();
    }

    public function getOption()
    {
        $select = $this->serviceCardGroup->getOption();
        $array = [];
        foreach ($select as $key => $value) {
            $array[$value['service_card_group_id']] = $value['name'];
        }
        return $array;
    }

    public function checkName($id, $name)
    {
        return $this->serviceCardGroup->checkName($id, $name);
    }

    public function edit(array $data, $id)
    {
        return $this->serviceCardGroup->edit($data, $id);
    }

    public function checkSlug($name, $id)
    {
        return $this->serviceCardGroup->checkSlug($name, $id);
    }

    public function remove($id)
    {
        return $this->serviceCardGroup->remove($id);
    }
}