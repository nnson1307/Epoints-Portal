<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 10:30 AM
 */

namespace Modules\Admin\Repositories\OrderSource;

use Modules\Admin\Models\OrderSourceTable;

class OrderSourceRepository implements OrderSourceRepositoryInterface
{
    /**
     * @var OrderSourceTable
     */
    protected $orderSource;
    protected $timestamps = true;

    public function __construct(OrderSourceTable $orderSource)
    {
        $this->orderSource = $orderSource;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->orderSource->getList($filters);
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->orderSource->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->orderSource->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->orderSource->edit($data, $id);
    }

    /*
     *  update or add
     */
    public function getItem($id)
    {
        return $this->orderSource->getItem($id);
    }

    /*
    * check oder source
    */
    public function check($name)
    {
        return $this->orderSource->check($name);
    }

    /*
   * check oder source edit
   */
    public function checkEdit($id, $name)
    {
        return $this->orderSource->checkEdit($id, $name);
    }

    /*
     * test is deleted
     */
    public function testIsDeleted($name)
    {
        return $this->orderSource->testIsDeleted($name);
    }

    /*
     * edit by name
     */
    public function editByName($name)
    {
        return $this->orderSource->editByName($name);
    }

    public function getOption()
    {
        $array = array();
        foreach ($this->orderSource->getOption() as $item) {
            $array[$item['order_source_id']] = $item['order_source_name'];
        }
        return $array;
    }
}