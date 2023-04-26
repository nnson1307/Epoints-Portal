<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/8/2018
 * Time: 11:00 AM
 */

namespace Modules\Admin\Repositories\ProductInventory;

use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\ConfigTable;
use Modules\Admin\Models\ProductChildTable;
use Modules\Admin\Models\ProductInventoryTable;
use Modules\Admin\Repositories\Warehouse\WarehouseRepositoryInterface;

class ProductInventoryRepository implements ProductInventoryRepositoryInterface
{
    /**
     * @var ProductInventoryTable
     */
    protected $productInventory;
    protected $rWarehouse;
    protected $mProductChild;
    protected $timestamps = true;

    public function __construct(
        ProductInventoryTable $productInventory,
        ProductChildTable $mProductChild)
    {
        $this->productInventory = $productInventory;
        $this->rWarehouse = app(WarehouseRepositoryInterface::class);
        $this->mProductChild = $mProductChild;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->productInventory->getList($filters);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->productInventory->getItem($id);
    }

    public function getListProductInventory()
    {
        return $this->productInventory->getListProductInventory();
    }

    public function add(array $data)
    {
        return $this->productInventory->add($data);
    }

    public function edit(array $data, $id)
    {
        return $this->productInventory->edit($data, $id);
    }

    public function checkProductInventory($productCode, $warehouseId)
    {
        return $this->productInventory->checkProductInventory($productCode, $warehouseId);
    }

    /*
     * get product inventory by warehouse id and product id.
     */
    public function getProductByWarehouseAndProductId($warehouseId, $productId)
    {
        return $this->productInventory->getProductByWarehouseAndProductId($warehouseId, $productId);
    }

    /*
     * get product inventory by warehouse id and product child code.
     */
    public function getProductByWarehouseAndProductCode($warehouseId, $code)
    {
        return $this->productInventory->getProductByWarehouseAndProductCode($warehouseId, $code);
    }

    public function getProductInventoryByWarehouse($productCode)
    {
        return $this->productInventory->getProductInventoryByWarehouse($productCode);
    }

    public function getProduct()
    {
        $array = [];
        foreach ($this->productInventory->getProduct() as $item) {
            $array[] = [$item['warehouse_id'], $item['product_code'], $item['quantity']];
        }
        return $array;
    }

    public function getProductInventory()
    {
        return $this->productInventory->getProductInventory();
    }

    public function getQuantityProductInventoryByCode($code)
    {
        return $this->productInventory->getQuantityProductInventoryByCode($code);

    }

    public function getProductWhereIn(array $warehouse)
    {
        $array = array();
        foreach ($this->productInventory->getProductWhereIn($warehouse) as $item) {
            $array[] = [$item['warehouse_id'], $item['product_code'], $item['quantity']];
        }
        return $array;
    }

    //Tìm kiểm sản phẩm tồn kho.
    public function getProductInventoryByCodeOrName($warehouse, $name, $code)
    {
        return $this->productInventory->getProductInventoryByCodeOrName($warehouse, $name, $code);
    }

    //Tìm kiểm sản phẩm tồn kho theo kho.
    public function getProductInventoryByWarehouseId($warehouse)
    {
        $array = array();
        foreach ($this->productInventory->getProductInventoryByWarehouseId($warehouse) as $item) {
            $array[$item['product_child_id']] = $item['product_child_name'];
        }
        return $array;
    }

    //Tìm kiểm sản phẩm tồn kho theo kho.
    public function getProductInventoryByWarehouseIdList($warehouse)
    {
        $array = array();
        foreach ($this->productInventory->getProductInventoryByWarehouseId($warehouse) as $item) {
            $array[$item['product_child_id']] = $item['product_code'].' - '.$item['product_child_name'];
        }
        return $array;
    }

    public function listProductInventory($params)
    {
        //Danh sách product child
        $page = (int)($params['page'] ?? 1);
        $display = (int)($params['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $params['page'] = $page;
        $params['perpage'] = $display;
        $productChild = $this->mProductChild->getList($params);
        //Danh sách kho
        $wareHouse = $this->rWarehouse->getWareHouseOption();
        //Array product child id
        $productChildId = $productChild->pluck('product_child_id')->toArray();
        //Tồn kho của "Array product child id" trên.
        $productInventory = $this->productInventory->productInventory($productChildId);

        //KQ
        $result = [];
        foreach ($productChild as $item) {
            $temp = [];
            $temp['product_child_id'] = $item['product_child_id'];
            $temp['product_child_name'] = $item['product_child_name'];
            $temp['product_code'] = $item['product_code'];
            $temp['created_at'] = $item['created_at'];
            $total = 0;
            //Tồn kho của sp trong kho
            foreach ($productInventory as $v) {

                if ($v['product_id'] == $item['product_child_id']) {
                    $temp[$v['warehouse_id']] = $v['quantity'];
                    //Tồn kho ở tất cả kho
                    $total += $v['quantity'];
                }
            }
            $temp['total'] = $total;
            $result[$item['product_child_id']] = $temp;
        }

        $data = [
            'wareHouse' => $wareHouse,
            'productChild' => $productChild,
            'result' => $result,
            'page' => $page,
            'display' => $display,
        ];
        $view = view('admin::product-inventory.list.product-inventory', $data)->render();
        return [
            'view' => $view,
            'data' => $data,
        ];
    }

    /**
     * Edit san pham ton kho theo code
     *
     * @param array $data
     * @param $productCode
     * @param $warehouseId
     * @return mixed
     */
    public function editQuantityByCode(array $data, $productCode, $warehouseId)
    {
        return $this->productInventory->editQuantityByCode($data, $productCode, $warehouseId);
    }

    /**
     * Lấy số lượng hiện tại trong kho
     *
     * @param $productCode
     * @param $warehouseId
     * @return mixed
     */
    public function getQuantityByProdCodeAndWarehouseId($productCode, $warehouseId)
    {
        return $this->productInventory->getQuantityByProdCodeAndWarehouseId($productCode, $warehouseId);
    }

    /**
     * Lấy data cho trang cấu hình
     *
     * @return mixed
     */
    public function getDataConfig()
    {
        $mBranch = new BranchTable();
        $mConfig = new ConfigTable();
        // get config id = 28 (key =branch_apply_order)
        $optionBranch = $mBranch->getBranchOption();
        $getConfig = $mConfig->getInfoByKey('branch_apply_order');
        return [
            'optionBranch' => $optionBranch,
            'getConfig' => $getConfig
        ];
    }

    /**
     * submit data config
     *
     * @param $input
     * @return mixed|void
     */
    public function saveInventoryConfig($input)
    {
        try {
            $branchId = $input['branchId'];
            $mConfig = new ConfigTable();
            $mConfig->editByKey(['value' => $branchId], 'branch_apply_order');

            return [
                'error' => false,
                'message' => __('Cập nhật thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Cập nhật thất bại')
            ];
        }
    }

    /**
     * Danh sách tồn kho dưới định mức
     *
     * @param array $filter
     * @return array|mixed
     */
    public function listBelowNorm($filter = [])
    {
        $mConfig = new ConfigTable();
        //Lấy giá trị tồn kho dưới định mức
        $getNumberInventory = $mConfig->getInfoByKey('number_report_inventory')['value'];
        //Filter theo giá trị định mức
        $filter['number_report_inventory'] = intval($getNumberInventory);
        //Danh sách tồn kho dưới định mức
        $list = $this->productInventory->getList($filter);

        return [
            'list' => $list
        ];
    }
}