<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 1:10 PM
 */

namespace Modules\Admin\Repositories\Supplier;

use Modules\Admin\Models\SupplierTable;

class SupplierRepository implements SupplierRepositoryInterface
{
    protected $supplier;
    protected $timestamps = true;

    public function __construct(SupplierTable $supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     *get list supplier
     */
    public function list(array $filters = [])
    {
        return $this->supplier->getList($filters);
    }

    /**
     * delete supplier
     */
    public function remove($id)
    {
        $this->supplier->remove($id);
    }

    /**
     * add supplier
     */
    public function add(array $data)
    {

        return $this->supplier->add($data);
    }

    /*
     * edit supplier
     */
    public function edit(array $data, $id)
    {
        return $this->supplier->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->supplier->getItem($id);
    }

    /*
    * get all
    */
    public function getAll()
    {
        $array = [];
        $data = $this->supplier->getAll();
        foreach ($data as $item) {
            $array[$item['supplier_id']] = $item['supplier_name'];
        }
        return $array;
    }

    /*
     * get option edit product
     */
    public function getOptionEditProduct($id)
    {
        return $this->supplier->getOptionEditProduct($id);
    }

    /*
    * check supplier
    */
    public function check($id, $name)
    {
        return $this->supplier->check($id, $name);
    }

    /*
    * check exist
    */
    public function checkExist($name, $isDelete)
    {
        return $this->supplier->checkExist($name, $isDelete);
    }
}