<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/15/2018
 * Time: 4:17 PM
 */

namespace Modules\Admin\Repositories\InventoryTransfer;

use Modules\Admin\Models\InventoryTransferTable;

class InventoryTransferRepository implements InventoryTransferRepositoryInterface
{
    protected $inventoryTransfer;
    protected $timestamps = true;

    public function __construct(InventoryTransferTable $inventoryTransfer)
    {
        $this->inventoryTransfer = $inventoryTransfer;
    }

    /**
     * add inventory transfer.
     */
    public function add(array $data)
    {
        return $this->inventoryTransfer->add($data);
    }

    /**
     *get list inventory transfer
     */
    public function list(array $filters = [])
    {
        return $this->inventoryTransfer->getList2($filters);
    }

    /**
     * delete inventory transfer
     */
    public function remove($id)
    {
        $this->inventoryTransfer->remove($id);
    }

    /*
     * edit inventory transfer
     */
    public function edit(array $data, $id)
    {
        return $this->inventoryTransfer->edit($data, $id);
    }

    /*
     *  get inventory transfer
     */
    public function getItem($id)
    {
        return $this->inventoryTransfer->getItem($id);
    }

    /*
     * detail inventory transfer
     */
    public function detail($id)
    {
        return $this->inventoryTransfer->detail($id);
    }
    /*
    * get inventory transfer edit
    */
    public function getInventoryTransferEdit($id){
        return $this->inventoryTransfer->getInventoryTransferEdit($id);
    }
    public function list2($filters)
    {
        return $this->inventoryTransfer->getList1($filters);
    }
}