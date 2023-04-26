<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\ManagerWork\Repositories\ManageRedmind;

use Modules\ManagerWork\Models\ManageRedmindTable;

class ManageRedmindRepository implements ManageRedmindRepositoryInterface
{
    /**
     * @var manageRedmind ableTable
     */
    protected $manageRedmind;
    protected $timestamps = true;

    public function __construct(ManageRedmindTable $manageRedmind)
    {
        $this->manageRedmind = $manageRedmind;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->manageRedmind->getList($filters);
    }
    
    public function getAll(array $filters = [])
    {
        return $this->manageRedmind->getAll($filters);
    }
    public function getName()
    {
        return $this->manageRedmind->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->manageRedmind->remove($id);
    }
    
    /**
     * delete removeByWorkId
     */
    public function removeByWorkId($id)
    {
        $this->manageRedmind->removeByWorkId($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->manageRedmind->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->manageRedmind->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->manageRedmind->getItem($id);
    }


}