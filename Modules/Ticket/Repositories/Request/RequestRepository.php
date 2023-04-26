<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\Request;

use Modules\Ticket\Models\RequestTable;

class RequestRepository implements RequestRepositoryInterface
{
    /**
     * @var RequestRepository
     */
    protected $requests;
    protected $timestamps = true;

    public function __construct(RequestTable $requests)
    {
        $this->requests = $requests;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->requests->getList($filters);
    }

    public function getAll(array $filters = [])
    {
        return $this->requests->getAll($filters);
    }

    public function getName()
    {
        return $this->requests->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->requests->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->requests->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->requests->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->requests->getItem($id);
    }

    /*
     *  test code
     */
    public function testCode($code, $id)
    {
        return $this->requests->testCode($code, $id);
    }

    /*
    * check exist
    */
    public function checkExistEmail($email = '')
    {
        return $this->requests->checkExistEmail($email);
    }

}