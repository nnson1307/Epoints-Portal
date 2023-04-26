<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\Alert;

use Modules\Ticket\Models\AlertTable;

class AlertRepository implements AlertRepositoryInterface
{
    /**
     * @var AlertTable
     */
    protected $Alert;
    protected $timestamps = true;

    public function __construct(AlertTable $Alert)
    {
        $this->Alert = $Alert;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->Alert->getList($filters);
    }
    
    public function getAll(array $filters = [])
    {
        return $this->Alert->getAll($filters);
    }
    public function getName()
    {
        return $this->Alert->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->Alert->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->Alert->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->Alert->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->Alert->getItem($id);
    }

}