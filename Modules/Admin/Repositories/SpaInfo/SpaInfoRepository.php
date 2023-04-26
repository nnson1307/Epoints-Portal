<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 21/3/2019
 * Time: 09:31
 */

namespace Modules\Admin\Repositories\SpaInfo;


use Modules\Admin\Models\SpaInfoTable;

class SpaInfoRepository implements SpaInfoRepositoryInterface
{
    protected $spa_info;
    protected $timestamps = true;

    public function __construct(SpaInfoTable $spa_info)
    {
        $this->spa_info = $spa_info;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = [])
    {
        // TODO: Implement list() method.
        return $this->spa_info->getList($filters);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        // TODO: Implement add() method.
        return $this->spa_info->add($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem()
    {
        // TODO: Implement getItem() method.
        return $this->spa_info->getItem();
    }

    /**
     * @param $name
     * @param $id
     * @return mixed
     */
    public function testName($name, $id)
    {
        // TODO: Implement testName() method.
        return $this->spa_info->testName($name, $id);
    }

    /**
     * @param $id
     */
    public function remove($id)
    {
        // TODO: Implement remove() method.
        return $this->spa_info->remove($id);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        // TODO: Implement edit() method.
        return $this->spa_info->edit($data, $id);
    }

    public function getInfoSpa(){
        return $this->spa_info->getInfoSpa();
    }

    public function getIntroduction()
    {
        return $this->spa_info->getIntroduction();
    }

    public function updateIntroduction(array $data)
    {
        return $this->spa_info->updateIntroduction($data);
    }
}