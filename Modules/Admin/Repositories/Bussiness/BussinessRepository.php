<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 20/3/2019
 * Time: 15:27
 */

namespace Modules\Admin\Repositories\Bussiness;


use Modules\Admin\Models\BussinessTable;

class BussinessRepository implements BussinessRepositoryInterface
{
    protected $bussiness;
    protected $timestamps = true;

    public function __construct(BussinessTable $bussiness)
    {
        $this->bussiness = $bussiness;
    }

    public function list(array $filters = [])
    {
        // TODO: Implement list() method.
        return $this->bussiness->getList($filters);
    }

    public function add(array $data)
    {
        return $this->bussiness->add($data);
    }

    public function getItem($id)
    {
        // TODO: Implement getItem() method.
        return $this->bussiness->getItem($id);
    }

    public function edit(array $data, $id)
    {
        return $this->bussiness->edit($data, $id);
    }

    public function remove($id)
    {
        // TODO: Implement remove() method.
        return $this->bussiness->remove($id);
    }

    public function testName($name, $id)
    {
        // TODO: Implement testName() method.
        return $this->bussiness->testName($name, $id);
    }

    public function getBussinessOption()
    {
        // TODO: Implement getBussinessOption() method.
        $array = array();
        foreach ($this->bussiness->getBussinessOption() as $item) {
            $array[$item['id']] = $item['name'];
        }
        return $array;
    }

}