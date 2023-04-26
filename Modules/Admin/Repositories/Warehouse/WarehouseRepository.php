<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/25/2018
 * Time: 10:16 AM
 */

namespace Modules\Admin\Repositories\Warehouse;

use Modules\Admin\Models\WarehouseTable;
use Modules\Delivery\Http\Api\DeliveryApi;

class WarehouseRepository implements WarehouseRepositoryInterface
{
    protected $warehouse;
    protected $timestamps = true;

    public function __construct(WarehouseTable $warehouses)
    {
        $this->warehouse = $warehouses;
    }

    //Hàm lấy danh sách
    public function list(array $filters = [])
    {
        return $this->warehouse->getList($filters);
    }

    //Hàm add
    public function add(array $data)
    {
        return $this->warehouse->add($data);
    }

    //Hàm lấy giá trị
    public function getWareHouseOption()
    {

        $array = array();
        foreach ($this->warehouse->getWareHouseOption() as $item) {
            $array[$item['warehouse_id']] = $item['name'];

        }
        return $array;
    }

    //Hàm xóa
    public function remove($id)
    {
        $this->warehouse->remove($id);
    }

    //Hàm sửa
    public function edit(array $data, $id)
    {
        return $this->warehouse->edit($data, $id);
    }

    //Hàm get dữ liệu khi edit
    public function getItem($id)
    {
        return $this->warehouse->getItem($id);
    }

    //Hàm kiem tra trung name
    public function testName($name, $id)
    {
        return $this->warehouse->testName($name, $id);
    }

    /*
     * get warehouse not id parameter
     */
    public function getWarehouseNotId($id)
    {
        $array = array();
        foreach ($this->warehouse->getWarehouseNotId($id) as $item) {
            $array[$item['warehouse_id']] = $item['name'];
        }
        return $array;
    }

    //search where in warehouse.
    public function searchWhereIn(array $warehouse)
    {
        return $this->warehouse->searchWhereIn($warehouse);
    }

    public function checkIsRetail($branchId, $id)
    {
        return $this->warehouse->checkIsRetail($branchId, $id);
    }

    public function getWarehouseByBranch($branchId)
    {
        return $this->warehouse->getWarehouseByBranch($branchId);
    }

    public function changeIsRetailAction($branchId)
    {
        return $this->warehouse->changeIsRetailAction($branchId);
    }

    //Kiểm tra kho đầu tiên của chi nhánh( để đặt kho đầu tiên là kho bán lẻ).
    public function checkIsFirstWarehouse($branchId)
    {
        return $this->warehouse->checkIsFirstWarehouse($branchId);
    }

    /**
     * Lấy thông tin kho bán lẻ theo branch id
     *
     * @param $branchId
     * @return mixed
     */
    public function getWarehouseRetailByBranchId($branchId)
    {
        return $this->warehouse->getWarehouseRetailByBranchId($branchId);
    }

    /**
     * Tạo cửa hàng ở giao hàng nhanh
     * @return mixed|void
     */
    public function createStoreGHN()
    {
        try {

//            Lấy danh sách cửa hàng chưa tạo
            $listWarehouse = $this->warehouse->getListWareHouseNoStore();
            $apiDelivery = app()->get(DeliveryApi::class);
            foreach($listWarehouse as $item){
                if ($item['ward_id'] != null){
                    $create = $apiDelivery->createStore([
                        'method' => 'ghn',
                        'branch_id' => $item['branch_id'],
                        'province_id' => $item['province_id'],
                        'district_id' => $item['district_id'],
                        'ward_id' => $item['ward_id'],
                        'name' => $item['name'],
                        'phone' => $item['phone'],
                        'address' => $item['address'],
                    ]);

                    if (isset($create['ErrorCode']) && $create['ErrorCode'] == 0){
                        $this->warehouse->edit([
                            'ghn_shop_id' => $create['Data']['shop_id']
                        ],$item['warehouse_id']);
                    }
                }
            }

            return [
                'error' => false,
                'message' => __('Tạo cửa hàng thành công')
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Tạo cửa hàng thất bại')
            ];
        }
    }
}