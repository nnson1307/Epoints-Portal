<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/2/2018
 * Time: 12:13 PM
 */

namespace Modules\Admin\Repositories\Product;

use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\InventoryCheckingDetailSerialTable;
use Modules\Admin\Models\InventoryCheckingStatusTable;
use Modules\Admin\Models\InventoryInputDetailSerialTable;
use Modules\Admin\Models\InventoryOutputDetailSerialTable;
use Modules\Admin\Models\MapProductAttributeTable;
use Modules\Admin\Models\ProductAttributeTable;
use Modules\Admin\Models\ProductBranchPriceTable;
use Modules\Admin\Models\ProductCategoryTable;
use Modules\Admin\Models\ProductChildTable;
use Modules\Admin\Models\ProductImageTable;
use Modules\Admin\Models\ProductInventorySerialTable;
use Modules\Admin\Models\ProductInventoryTable;
use Modules\Admin\Models\ProductModelTable;
use Modules\Admin\Models\ProductTable;
use Modules\Admin\Models\WarehouseTable;
use Modules\Ticket\Models\WarehousesTable;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @var ProductTable
     */
    protected $product;
    protected $productChild;
    protected $timestamps = true;

    public function __construct(
        ProductTable $product,
        ProductChildTable $productChild
    ) {
        $this->product = $product;
        $this->productChild = $productChild;
    }

    /**
     *get list product
     */
    public function list(array $filters = [])
    {
        return $this->product->getList($filters);
    }

    /**
     * delete product
     */
    public function remove($id)
    {
        $this->product->remove($id);
        $this->productChild->removeByProductId($id);
    }

    /**
     * add product
     */
    public function add(array $data)
    {
        return $this->product->add($data);
    }

    /*
     * edit product
     */
    public function edit(array $data, $id)
    {
        return $this->product->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->product->getItem($id);
    }

    /*
     *  test code
     */
    public function testCode($code, $id)
    {
        return $this->product->testCode($code, $id);
    }

    /*
     *  get option
     */
    public function getOption()
    {
        $array = [];
        $data = $this->product->getOption();
        foreach ($data as $item) {
            $array[$item['product_id']] = $item['product_name'];
        }
        return $array;
    }

    /*
     * Get detail product
     */
    public function getDetailProduct($id)
    {
        return $this->product->getDetailProduct($id);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function searchProduct($data)
    {
        return $this->product->searchProduct($data);
    }

    public function searchProductChild($data)
    {

        return $this->productChild->searchProduct($data);
    }

    public function getListAdd()
    {
        // TODO: Implement getListAdd() method.
        return $this->product->getListAdd();
    }

    //Kiểm tra trùng tên sản phẩm.
    public function checkName($name, $id)
    {
        return $this->product->checkName($name, $id);
    }

    public function checkNameEN($name, $id)
    {
        return $this->product->checkNameEN($name, $id);
    }

    //Kiểm tra trùng tên sản phẩm.
    public function checkSku($sku, $id)
    {
        return $this->product->checkSku($sku, $id);
    }

    /**
     * Lấy toàn bộ danh sách sản phẩm
     *
     * @return mixed
     */
    public function getProduct()
    {
        $array = array();
        foreach ($this->product->getProduct() as $item) {
            $array[$item['product_id']] = $item['product_name'];
        }
        return $array;
    }

    public function list2(array $filters = [])
    {
        return $this->product->getListProduct($filters);
    }

    /**
     * Import excel file image
     *
     * @param $input
     * @return mixed|void
     */
    public function importFileImage($input)
    {
        try {
            $mProductChild = new ProductChildTable();
            $mProductImage = new ProductImageTable();

            $file = request()->file('file');
            dd($file);
            if (isset($file)) {
                $typeFileExcel = $file->getClientOriginalExtension();
                if ($typeFileExcel == "xlsx") {
                    $reader = ReaderFactory::create(Type::XLSX);
                    $reader->open($file);

                    foreach ($reader->getSheetIterator() as $sheet) {
                        foreach ($sheet->getRowIterator() as $key => $row) {
                            if ($key != 1) {
                                $sku = $row[0];
                                $linkImage = $row[4];

                                $getChild = $mProductChild->getChildByCode($sku);

                                if ($getChild != null) {
                                    $mProductImage->insert([
                                        'product_id' => $getChild['product_id'],
                                        'product_child_code' => $getChild['product_code'],
                                        'name' => $linkImage,
                                        'created_by' => Auth()->id()
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            return [
                'error' => false,
                'message' => __('Import thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Import thất bại')
            ];
        }
    }

    /**
     * Tắt hiển thị sp không có hình ảnh
     *
     * @return mixed|void
     */
    public function unDisplay()
    {
        try {
            $mProductChild = new ProductChildTable();
            $mProductImage = new ProductImageTable();

            $getAllChild = $mProductChild->getOptionChildSonService();

            foreach ($getAllChild as $v) {
                $getImage = $mProductImage->getImageByCode($v['product_code']);

                if (count($getImage) == 0) {
                    //Un display sản phẩm ko có hình
                    $mProductChild->edit([
                        'is_display' => 0
                    ], $v['product_child_id']);
                } else {
                    //is display sản phẩm ko có hình
                    $mProductChild->edit([
                        'is_display' => 1
                    ], $v['product_child_id']);
                }
            }

            echo 'Cập nhật thành công';
        } catch (\Exception $e) {
            echo 'Cập nhật thất bại';
        }
    }

    /**
     * Kiểm tra kho đã có số serial gắn cho sản phẩm chưa
     * @param $data
     * @return mixed|void
     */
    public function checkSerialEdit($data)
    {
        $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
        $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
        $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);

        $getListProductChildCode = $this->getListProductCode($data);

        $checkTotalSerial = $mProductInventorySerial->checkTotalSerial($getListProductChildCode);
        return [
            'total' => $checkTotalSerial
        ];
    }

    /**
     * Kiểm tra tồn kho
     * @param $data
     * @return mixed|void
     */
    public function checkBasicEdit($data)
    {
        $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
        $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
        $mProductInventory = app()->get(ProductInventoryTable::class);

        $getListProductChildCode = $this->getListProductCode($data);

        $checkTotalSerial = $mProductInventory->checkTotalSerial($getListProductChildCode);
        $checkTotalSerial = $checkTotalSerial != null ? ($checkTotalSerial['quantity'] == null ? 0 : $checkTotalSerial['quantity']) : 0;
        return [
            'total' => $checkTotalSerial
        ];
    }

    /**
     * lấy danh sách mã code sản phẩm con
     * @param $data
     * @return \Illuminate\Support\Collection
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getListProductCode($data)
    {
        $mProductChild = app()->get(ProductChildTable::class);

        $getListProductChildCode = $mProductChild->getListProductChildCode($data['id']);
        if (count($getListProductChildCode) != 0) {
            $getListProductChildCode = collect($getListProductChildCode)->pluck('product_code');
        }

        return $getListProductChildCode;
    }

    /**
     * Xoá số serial
     * @param $productId
     */
    public function removeSerial($productId)
    {
        try {
            $listProductCode = $this->getListProductCode(['id' => $productId]);

            $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $mInventoryCheckingDetailSerial = app()->get(InventoryCheckingDetailSerialTable::class);

            $mProductInventorySerial->removeSerial($listProductCode);
            $mInventoryInputDetailSerial->removeSerialByCode($listProductCode);
            $mInventoryOutputDetailSerial->removeSerialByCode($listProductCode);
            $mInventoryCheckingDetailSerial->removeSerialByCode($listProductCode);


            return [
                'error' => false
            ];
        } catch (\Exception $e) {
            return [
                'error' => false,
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Hiển thị popup serial
     * @param $data
     * @return mixed|void
     */
    public function showPopupSerial($data)
    {
        try {

            $mProductChild = app()->get(ProductChildTable::class);
            $mWarehouse = app()->get(WarehouseTable::class);
            $mInventoryCheckingStatus = app()->get(InventoryCheckingStatusTable::class);
            $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);

            $detailProduct = $mProductChild->getProductChildByCode($data['product_code']);
            $listWarehouse = $mWarehouse->getWareHouseOption();

            $listStatus = $mInventoryCheckingStatus->getAll();

            $listSerial = $mProductInventorySerial->getListSerialByCodeProduct($data);

            $view = view('admin::product.modal.popup-list-serial', [
                'detailProduct' => $detailProduct,
                'listWarehouse' => $listWarehouse,
                'listStatus' => $listStatus,
                'listSerial' => $listSerial
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Hiển thị popup serial thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách serial
     * @param $data
     * @return mixed|void
     */
    public function searchSerial($data)
    {
        try {

            $mWarehouse = app()->get(WarehouseTable::class);
            $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);

            $listWarehouse = $mWarehouse->getWareHouseOption();

            $listSerial = $mProductInventorySerial->getListSerialByCodeProduct($data);

            $view = view('admin::product.append.list-serial', [
                'listSerial' => $listSerial
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                '__message' => $e->getMessage()
            ];
        }
    }

    public function getProductTopId()
    {
        return $this->product->getProductTopId();
    }

    /**
     * Lấy danh sách sản phẩm cơn có phân trang
     * @param $data
     * @return mixed|void
     */
    public function getListProductChild(array $filters = [])
    {
        return $this->productChild->getListPagination($filters);
    }

    public function importProduct(array $input)
    {
        DB::beginTransaction();
        try {
            //Get product category
            $getCategory = $this->addProductCategory($input);
            //Get product brand
            $getProductModel = $this->addProductBrand($input);
            // add products
            $getProduct = $this->addProductImport($input, $getCategory, $getProductModel);

            $productAttribute = $this->addProductAttribute($input);

            $getProductChild = $this->addProductChild($input, $getProduct);

            $this->addProductImage($input, $getProductChild);

            $this->addMapProductAttribute($getProduct, $getProductChild, $productAttribute);

            $this->addProductBranchPrice($input, $getProductChild);

            $this->addProductInventories($getProductChild);

            DB::commit();
            return response()->json([
                'error' => false,
                'success' => 1,
                'message' => __('Import thông tin sản phẩm thành công')
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }

    }

    private function addProductInventories($getProductChild){
            $warehouseTable = app(WarehousesTable::class);
            $mProductInventoryTable = app()->get(ProductInventoryTable::class);
            $warehouses = $warehouseTable->getWarehouseList();
            $productInventories = [];

            foreach ($warehouses as $warehouse){
                $productInventory = [
                    'product_code' => $getProductChild['product_code'],
                    'product_id' => $getProductChild['product_child_id'],
                    'warehouse_id' => $warehouse['warehouse_id'],
                    'import' => 0,
                    'export' => 0,
                    'quantity' => 0,
                    'created_at' => Carbon::now()->format("Y-m-d H:i:s"),
                    'updated_at' => Carbon::now()->format("Y-m-d H:i:s"),
                    'created_by' => 0,
                    'updated_by' => 0,
                ];
                $mInventory = $mProductInventoryTable->checkProductInventoryByWarehouse($warehouse['warehouse_id'], $getProductChild['product_child_id']);
                if(empty($mInventory)){
                    $mInventory = $mProductInventoryTable->createProductInventory($productInventory);
                }
                $productInventories[] = $mInventory;
            }
            return $productInventories;
    }

    private function addProductBranchPrice($input, $getProductChild){
        $mBranch = app()->get(BranchTable::class);
        $mProductBranchPrice = app()->get(ProductBranchPriceTable::class);
        $getBranch = $mBranch->getBranch();
        $productPrices = [];
        if (count($getBranch) > 0) {
           foreach ($getBranch as $branch) {
                    $productBranchPrice = [
                        "product_id" => $getProductChild['product_child_id'],
                        "branch_id" => $branch['branch_id'],
                        "product_code" => $getProductChild['product_code'],
                        "old_price" => null,
                        "new_price" => $input['price'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        "updated_at" => Carbon::now()->format('Y-m-d H:i:s'),
                        "is_actived" => 1,
                        "is_deleted" => 0
                    ];
                   $mPrice = $mProductBranchPrice->getProductBranchPriceByPrice($branch['branch_id'], $getProductChild['product_code'], $input['price']);
                   if(empty($mPrice)){
                       $mPrice = $mProductBranchPrice->createProductBranchPrice($productBranchPrice);
                   }
               $productPrices[] = $mPrice;
           }
        }
        return $productPrices;
    }

    private function addMapProductAttribute($getProduct, $getProductChild, $productAttribute){

        $mProductMap = app()->get(MapProductAttributeTable::class);
        $mapAttribute = null;
        //Insert product map
        if(isset($productAttribute)){
            $mapProductAttribute = [
                'product_id' => $getProduct['product_id'],
                'product_child_id' => $getProductChild['product_child_id'],
                'product_attribute_group_id' => 1,
                'product_attribute_id' => $productAttribute["product_attribute_id"],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];

            $mapAttribute = $mProductMap->getMapProductAttribute($getProductChild['product_child_id'], $productAttribute["product_attribute_id"]);
            if(empty($mapAttribute)){
                $mapAttribute = $mProductMap->createMapProductAttribute($mapProductAttribute);
            }
        }
        return $mapAttribute;
    }

    private function addProductChild($input, $getProduct){
        $dataChild = [
            "product_id" => $getProduct['product_id'],
            "product_code" => $input['code'],
            "barcode" => $input['barcode'],
            "product_child_name" => $input['name'],
            "product_child_name_en" => $input['name'],
            "cost" => $input['price'],
            "price" => $input['price'],
            "unit_id" => 1,
            "is_display" => 0,
            "is_actived" => 1,
            'slug' => Str::slug($input['name'])
        ];
        $getProductChild = $this->productChild->getProductChildByCode($input['code']);
        if(empty($getProductChild)){
            $getProductChild = $this->productChild->createProductChild($dataChild);
        }
        return $getProductChild;
    }

    private function addProductImage($input, $getProductChild){
        if(isset($getProductChild)){
            $mProductImage = app()->get(ProductImageTable::class);
            $image = $mProductImage->getAvatar($getProductChild['product_code']);
            if(empty($image)){
                $mProductImage->add([
                    'product_id' => $getProductChild['product_id'],
                    'product_child_code' => $getProductChild['product_code'],
                    'name' => (isset($input['image']) && $input['image'] != "") ? $input['image'] : 'https://epoint-bucket.s3.ap-southeast-1.amazonaws.com/48e9581f8d31054fff4aecad441dd0e3/2023/02/27/3OcOz3167748180727022023_config-general.png',
                    'is_avatar' => 1
                ]);
            }
        }
    }

    private function addProductAttribute($input){
        $mProductAttribute = app()->get(ProductAttributeTable::class);
        $attribute = $mProductAttribute->getAttributeByCode($input['attribute']);
        if(empty($attribute) && $input['attribute'] != ""){
            $attribute = $mProductAttribute->createAttribute([
                'product_attribute_label' => $input['attribute'],
                'product_attribute_code' => $input['attribute'],
                'product_attribute_group_id' => 1,
                'slug' => Str::slug($input['attribute'])
            ]);
        }
        return $attribute;
    }

    private function addProductImport($input, $getCategory, $getProductModel){

        $product = $this->product->getProductByCode($input['code']);
        if(empty($product)){
            $dataMaster = [
                "product_category_id" => $getCategory != null ? $getCategory['product_category_id'] : 1,
                "product_model_id" => $getProductModel != null ? $getProductModel['product_model_id'] : null,
                "product_name" => $input['name'],
                "description" => $input['description'],
                "description_en" => $input['description'],
                "slug" => Str::slug($input['name']),
                "cost" => $input['price'],
                "price_standard" => $input['price'],
                "product_code" => $input['code'],
                "unit_id" => 1,
                "is_actived" => 1
            ];
            $product = $this->product->createProduct($dataMaster);
        }
        return $product;
    }

    private function addProductCategory($input){
        $mProductCategory = app()->get(ProductCategoryTable::class);
        $getCategory = null;
        if(isset($input['category'])){
            $getCategory = $mProductCategory->getCategoryByName($input['category']);
            if(empty($getCategory)){
                $getCategory = $mProductCategory->createCategory([
                    'category_name' => $input['category'],
                    'category_name_vi' => $input['category'],
                    'slug' => Str::slug($input['category'])
                ]);
            }
        }
        return $getCategory;
    }

    private function addProductBrand(array $input)
    {
        $mProductModel = app()->get(ProductModelTable::class);
        $brand = null;
        if(isset($input['brand'])){
            $brand = $mProductModel->getProductModelByName($input['brand']);
            if(empty($brand)){
                $brand = $mProductModel->createBrand([
                    'product_model_name' => $input['brand'],
                    'slug' => Str::slug($input['brand'])
                ]);
            }
        }

        return $brand;
    }
}