<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\RequestGroup;

use Modules\Ticket\Models\RequestGroupTable;

class RequestGroupRepository implements RequestGroupRepositoryInterface
{
    /**
     * @var RequestGroupRepository
     */
    protected $requestGroup;
    protected $timestamps = true;

    public function __construct(RequestGroupTable $requestGroup)
    {
        $this->requestGroup = $requestGroup;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->requestGroup->getList($filters);
    }

    public function getAll(array $filters = [])
    {
        return $this->requestGroup->getAll($filters);
    }
    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->requestGroup->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->requestGroup->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->requestGroup->edit($data, $id);
    }
    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->requestGroup->getItem($id);
    }

    public function getName()
    {
        return $this->requestGroup->getName();
    }
}