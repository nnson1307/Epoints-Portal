<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\ZNS\Repositories\Params;
use Modules\ZNS\Models\ParamsTable;


class ParamsRepository implements ParamsRepositoryInterface
{
    /**
     * @var ParamsTable
     */
    protected $params;
    protected $timestamps = true;

    public function __construct(ParamsTable $params)
    {
        $this->params = $params;
    }

    /**
     *get list params
     */
    public function list(array $filters = [])
    {
        return [
            'list' => $this->params->getList($filters),
            'params' => $filters,
        ];
    }
    
    /**
     * delete params
     */
    public function remove($id)
    {
        $this->params->remove($id);
    }

    /**
     * add params
     */
    public function add(array $data)
    {

        return $this->params->add($data);
    }

    /*
     * edit params
     */
    public function edit(array $data, $id)
    {
        return $this->params->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->params->getItem($id);
    }
}