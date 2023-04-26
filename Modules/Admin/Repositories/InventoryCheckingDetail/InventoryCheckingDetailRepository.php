<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/16/2018
 * Time: 4:46 PM
 */

namespace Modules\Admin\Repositories\InventoryCheckingDetail;

use Modules\Admin\Models\InventoryCheckingDetailSerialTable;
use Modules\Admin\Models\InventoryCheckingDetailTable;
use Modules\Admin\Models\InventoryInputDetailSerialTable;
use Modules\Admin\Models\InventoryOutputDetailSerialTable;
use Modules\Admin\Models\ProductInventorySerialTable;

class InventoryCheckingDetailRepository implements InventoryCheckingDetailRepositoryInterface
{
    protected $inventoryCheckingDetail;
    protected $timestamps = true;

    public function __construct(InventoryCheckingDetailTable $inventoryCheckingDetail)
    {
        $this->inventoryCheckingDetail = $inventoryCheckingDetail;
    }

    /**
     * add inventory input.
     */
    public function add(array $data)
    {
        return $this->inventoryCheckingDetail->add($data);
    }

    public function getDetailInventoryCheckingDetailView($parentId)
    {
        $list = $this->inventoryCheckingDetail->getDetailInventoryCheckingDetail($parentId);
        $mInventoryInputSerial = app()->get(InventoryInputDetailSerialTable::class);
        $mInventoryOutputSerial = app()->get(InventoryOutputDetailSerialTable::class);
//        Lấy tổng số serial import

        if (count($list) != 0){
            $list = collect($list)->toArray();

        }

        $arrList = [];
        foreach ($list as $key => $item){
            $arrList[$key] = $item;
            $arrList[$key]['total_import'] = $mInventoryInputSerial->getTotalSerialImport($parentId,$item['product_code']);
            $arrList[$key]['total_export'] = $mInventoryOutputSerial->getTotalSerialExport($parentId,$item['product_code']);
        }

        return $arrList;
    }

    /*
     * get detail inventory checking detail
     */
    public function getDetailInventoryCheckingDetail($parentId)
    {
        $list = $this->inventoryCheckingDetail->getDetailInventoryCheckingDetail($parentId);

        $list = $this->checkTotalSerial($list,$parentId);

        return $list;
    }

    /*
     * get detail inventory checking detail update
     */
    public function getDetailInventoryCheckingDetailUpdate($parentId)
    {
        $list = $this->inventoryCheckingDetail->getDetailInventoryCheckingDetail($parentId);

        $list = $this->checkTotalSerial($list,$parentId);

        return $list;
    }

    public function checkTotalSerial($list,$inventoryCheckingId,$type = 'edit'){
        $mproductInventorySerial = app()->get(ProductInventorySerialTable::class);
        $mInventoryCheckingSerial = app()->get(InventoryCheckingDetailSerialTable::class);

        foreach ($list as $key => $item){
            if ($item['inventory_management'] == 'serial'){
                //        Lấy danh sách serial tồn kho
                $listSerialProductInventory = $mproductInventorySerial->getListSerialByProductWarehouse($item['warehouse_id'],$item['product_code']);
                if (count($listSerialProductInventory) != 0){
                    $listSerialProductInventory = collect($listSerialProductInventory)->keyBy('serial');
                }

//            Lấy danh sách serial kiểm kho
                $listSerialChecking = $mInventoryCheckingSerial->getListSerialChecking($inventoryCheckingId,$item['product_code']);
                if (count($listSerialChecking) != 0){
                    $listSerialChecking = collect($listSerialChecking)->keyBy('serial');
                }

                $list[$key]['total_import'] = 0;
                $list[$key]['total_export'] = 0;

//            Số lượng serial trong tồn kho nhiều hơn
                if (count($listSerialProductInventory) > count($listSerialChecking)){
                    foreach ($listSerialProductInventory as $keyProductInventory => $itemProductInventory){
                        if (isset($listSerialChecking[$keyProductInventory])){
                            unset($listSerialProductInventory[$keyProductInventory]);
                            unset($listSerialChecking[$keyProductInventory]);
                        }
                    }
                } else {
//            Số lượng serial trong tồn kho ít hơn
                    foreach ($listSerialChecking as $keyChecking => $itemChecking){
                        if (isset($listSerialProductInventory[$keyChecking])){
                            unset($listSerialChecking[$keyChecking]);
                            unset($listSerialProductInventory[$keyChecking]);
                        }
                    }
                }

                $list[$key]['total_import'] = count($listSerialChecking);
                $list[$key]['total_export'] = count($listSerialProductInventory);
            }
        }
        return $list;
    }

    /*
     * edit by inventory checking id and product code
     */
    public function editByParentIdAndProductCode($parentId, $productCode, array $data)
    {
        return $this->inventoryCheckingDetail->editByParentIdAndProductCode($parentId, $productCode, $data);
    }

    /*
   * Xóa khỏi db với điều kiện inventory_checking_id và product_code.
   */
    public function removeByParentIdAndProductCode($parentId, $productCode)
    {
        return $this->inventoryCheckingDetail->removeByParentIdAndProductCode($parentId, $productCode);
    }
}