<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/5/2018
 * Time: 11:32 AM
 */

namespace Modules\Admin\Repositories\ProductChild;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\ProductChildTable;
use Modules\Admin\Models\ProductConditionTable;
use Modules\Admin\Models\ProductSuggestConfigMapTable;
use Modules\Admin\Models\ProductSuggestConfigTable;
use Modules\Admin\Models\ProductTagTable;

class ProductChildRepository implements ProductChildRepositoryInterface
{
    /**
     * @var ProductChildTable
     */
    protected $productChild;
    protected $productCondition;
    protected $timestamps = true;

    public function __construct(ProductChildTable $productChild, ProductConditionTable $productCondition)
    {
        $this->productChild = $productChild;
        $this->productCondition = $productCondition;
    }

    /**
     *get list product child
     */
    public function list(array $filters = [])
    {
        return $this->productChild->getList($filters);
    }

    /**
     * delete product child
     */
    public function remove($id)
    {
        $this->productChild->remove($id);
    }

    /**
     * add product child
     */
    public function add(array $data)
    {

        return $this->productChild->add($data);
    }

    /*
     * edit product child
     */
    public function edit(array $data, $id)
    {
        return $this->productChild->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->productChild->getItem($id);
    }

    /*
     *  get item
     */
    public function testProductCode($code)
    {
        return $this->productChild->testProductCode($code);
    }

    /**
     * get product child by product id
     */
    public function getProductChildByProductId($id)
    {
        return $this->productChild->getProductChildByProductId($id);
    }

    /*
    *search product child
    */
    public function searchProductChild($name)
    {
        return $this->productChild->searchProductChild($name);
    }

    /*
     * get product child by id
     */
    public function getProductChildById($id)
    {
        return $this->productChild->getProductChildById($id);
    }

    /*
     * get product child by code
     */
    public function getProductChildByCode($code)
    {
        return $this->productChild->getProductChildByCode($code);
    }

    public function getProductChildByMatrix($code,$matrix = [])
    {
        return $this->productChild->getProductChildByMatrix($code,$matrix);
    }

    /*
    *search product child in inventory output
    */
    public function searchProductChildInventoryOutput($warehouseId, $name)
    {
        return $this->productChild->searchProductChildInventoryOutput($warehouseId, $name);
    }

    /*
     * get product child by warehouse and code.
     */
    public function getProductChildByWarehouseAndCode($warehouseId, $code)
    {
        return $this->productChild->getProductChildByWarehouseAndCode($warehouseId, $code);
    }

    /*
     * search product child by warehouse and code.
     */
    public function searchProductChildByWarehouseAndCode($warehouseId, $code)
    {
        return $this->productChild->searchProductChildByWarehouseAndCode($warehouseId, $code);
    }

    public function getProductChildByWarehouseAndProductCode($warehouseId, $code)
    {
        return $this->productChild->getProductChildByWarehouseAndProductCode($warehouseId, $code);
    }

    public function getProductChildOption()
    {
        $array = array();
        foreach ($this->productChild->getProductChildOption() as $item) {
            $array[$item['product_code']] = $item['product_child_name'];
        }
        return $array;
    }
    public function getOptionChildSonService()
    {
        // TODO: Implement getOptionChildSonService() method.
        $array = array();
        foreach ($this->productChild->getOptionChildSonService() as $item) {
            $array[$item['product_child_id']] = $item['product_child_name'];
        }
        return $array;
    }

    //search product by keyword
    public function searchProduct($keyword)
    {
        $array = array();
        foreach ($this->productChild->searchProduct($keyword) as $item) {
            $array[$item['product_code']] = $item['product_child_name'];
        }
        return $array;
    }

    /**
     * @param $id
     * @return mixed|void
     */
    public function getListChildOrder($productName = null, $productCategory = null)
    {
        return $this->productChild->getListChildOrder($productName, $productCategory);
    }

    public function getListChildOrderPaginate(array $filters = [])
    {
        return $this->productChild->getListChildOrderPaginate($filters);
    }

    public function getListChildOrderSearch($search)
    {
        // TODO: Implement getListChildOrderSearch() method.
        return $this->productChild->getListChildOrderSearch($search);
    }

    public function removeByCode($code)
    {
        return $this->productChild->removeByCode($code);
    }

    public function removeByArrChildId($productId, $arrChildId)
    {
        return $this->productChild->removeByArrChildId($productId, $arrChildId);
    }

    public function updateOrCreates(array $condition, array $data)
    {
        return $this->productChild->updateOrCreates($condition, $data);
    }

    public function updateByCode(array $data, $code)
    {
        return $this->productChild->updateByCode($data, $code);
    }

    public function getProductChildOptionIdName()
    {
        $array = array();
        foreach ($this->productChild->getProductChildOptionIdName() as $item) {
            $array[$item['product_child_id']] = $item['product_child_name'];
        }
        return $array;
    }

    public function getListProductChild()
    {
        return $this->productChild->getProductChildOptionIdName();
    }

    public function getProductChildInventoryOutput($warehouseId)
    {
        $array = array();
        foreach ($this->productChild->getProductChildInventoryOutput($warehouseId) as $item) {
            $array[$item['product_child_id']] = $item['product_child_name'];
        }
        return $array;
    }

    public function getListProductChildInventoryOutput($warehouseId)
    {
        return $this->productChild->getProductChildInventoryOutput($warehouseId);
    }

    public function getProductChildByBranchesWarehouses($warehouseId)
    {
        $array = array();
        foreach ($this->productChild->getProductChildByBranchesWarehouses($warehouseId) as $item) {
            $array[$item['product_child_id']] = $item['product_child_name'];
        }
        return $array;
    }

    public function getProductChildByBranchesWarehousesList($warehouseId)
    {
        $array = array();
        foreach ($this->productChild->getProductChildByBranchesWarehouses($warehouseId) as $item) {
            $array[$item['product_child_id']] = $item['product_code'] . ' - ' . $item['product_child_name'];
        }
        return $array;
    }

    public function checkProductChildName($name)
    {
        return $this->productChild->checkProductChildName($name);
    }

    public function checkSlug($slug)
    {
        return $this->productChild->checkSlug($slug);
    }

    public function checkSlugEN($slug)
    {
        return $this->productChild->checkSlugEN($slug);
    }

    /**
     * Danh sách product child 3 tab.
     * @param array $filters
     *
     * @return mixed
     */
    public function listTab(array $filters = [])
    {
        if (isset($filters['created_at']) && $filters['created_at'] != '') {
            $arr_explode = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_explode[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_explode[1])->format('Y-m-d');
            $filters['created_at'] = [$startTime . ' 00:00:00', $endTime . ' 23:59:59'];
        }
        $filters['perpage'] = (int)($filters['display'] ?? PAGING_ITEM_PER_PAGE);
        unset($filters['display']);
        return $this->productChild->getListNew($filters);
    }

    /**
     * Option product child để thêm với vào 3 tab: Mới, giảm giá, bán chạy.
     * @param array $filters
     * @param $listNotIn
     *
     * @return mixed
     */
    public function getOptionAddTab($listNotIn, array $filters = [])
    {
        $arrayNotIn = [];
        foreach ($listNotIn as $item) {
            $arrayNotIn[] = $item['product_child_id'];
        }
        $filters['arrayNotIn'] = $arrayNotIn;
        $filters['perpage'] = 10000;
        unset($filters['display'], $filters['type_tab']);
        $result = $this->productChild->getListNew($filters);
        return $result;
    }

    public function selectedProductChild($id)
    {
        $detail = $this->productChild->getItem($id);
        return $detail;
    }

    /**
     * Thêm cấu hình sản phẩm thương mại.
     * @param $params
     *
     * @return array|mixed
     */
    public function submitAddProductChild($params)
    {
        try {
            if (isset($params['productChildId']) && count($params['productChildId']) > 0) {
                if ($params['type_tab'] == 'new' || $params['type_tab'] == 'best_seller') {
                    $temp = [];
                    //Danh sách product child where in $params['productChildId'].
                    $productChild = $this->productChild->getWhereIn($params['productChildId']);
                    foreach ($productChild as $item) {
                        $temp[$item['product_child_id']] = $item['type_app'];
                    }
                    foreach ($temp as $id => $type_app) {
                        //Nối type app
                        $typeApp = $type_app != '' ? $type_app . ','
                            . $params['type_tab'] : $params['type_tab'];
                        $dataUpdate = [
                            'type_app' => $typeApp
                        ];
                        $this->productChild->edit($dataUpdate, $id);
                    }
                } else if ($params['type_tab'] == 'sale') {
                    foreach ($params['productChildId'] as $item) {
                        $dataUpdate = [
                            'is_sales' => 1,
                            'percent_sale' => intval($item['percentSale']),
                        ];
                        $this->productChild->edit($dataUpdate, $item['id']);
                    }
                }
            }
            return [
                'error' => false,
                'message' => 'Thêm thành công',
            ];
        } catch (\Exception $ex) {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            die;
        }
    }

    public function removeList($params)
    {
        try {
            $productChildId = $params['product_child_id'];
            if ($params['type_tab'] == 'new' || $params['type_tab'] == 'best_seller') {
                $productChild = $this->productChild->getItem($productChildId);
                if ($productChild != null) {
                    $typeApp1 = str_replace($params['type_tab'], '', $productChild['type_app']);
                    $typeApp2 = rtrim($typeApp1, ",");
                    $typeApp3 = ltrim($typeApp2, ",");
                    $dataUpdate = [
                        'type_app' => $typeApp3
                    ];
                    $this->productChild->edit($dataUpdate, $productChildId);
                }
            } else if ($params['type_tab'] == 'sale') {
                $dataUpdate = [
                    'is_sales' => 0,
                    'percent_sale' => 0,
                ];
                $this->productChild->edit($dataUpdate, $productChildId);
            }
            return [
                'error' => false,
                'message' => __('Xóa thành công'),
            ];
        } catch (\Exception $ex) {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            die;
        }
    }

    /**
     * Danh sách option của product child load more theo trang
     * @param array $filter
     *
     * @return mixed
     */
    public function getProductChildOptionPage($filter = [])
    {
        $filter['perpage'] = @$filter['perpage'] ?? PAGING_ITEM_PER_PAGE;
        $filter['page'] = @$filter['page'] ?? 1;
        $result = $this->productChild->getProductChildOptionPage($filter);
        return $result;
    }

    /**
     * Inventory output
     * Danh sách option của product child load more theo trang
     * @param array $filter
     *
     * @return mixed
     */
    public function getProductChildInventoryOutputOptionPage($filter = [])
    {
        $filter['perpage'] = @$filter['perpage'] ?? PAGING_ITEM_PER_PAGE;
        $filter['page'] = @$filter['page'] ?? 1;
        $result = $this->productChild->getProductChildInventoryOutputOptionPage($filter);
        return $result;
    }

    /**
     * Thêm điều kiện cấu hình sản phẩm gợi ý
     * @param $data
     * @return mixed|void
     */
    public function addConditionSuggest($data)
    {
        try {
            $mTags = new ProductTagTable();
            $getListTags = $mTags->getOption();
            $getListCondition = $this->productCondition->getListCondition();
            $view = view('admin::product-child.tab.product-suggest-append', [
                'data' => $data,
                'getListTags' => $getListTags,
                'getListCondition' => $getListCondition
            ])->render();
            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm điều kiện thất bại')
            ];
        }
    }

    /**
     * Lấy danh sách điều kiện cấu hình sản phẩm gợi ý
     * @return mixed
     */
    public function getListCondition()
    {
        return $this->productCondition->getListCondition();
    }

    //    Lấy danh sách tags cho cấu hình sản phẩm gợi ý
    public function getListTags()
    {
        $mTags = new ProductTagTable();
        return $mTags->getOption();
    }

    /**
     * Insert cấu hình sản phẩm gợi ý
     * @param $data
     * @return mixed|void
     */
    public function insertConditionSuggest($data)
    {
        try {
            DB::beginTransaction();
            $arrData = [];
            $mProductSuggestConfig = new ProductSuggestConfigTable();
            $mProductSuggestConfigMap = new ProductSuggestConfigMapTable();

            //            Xoá tất cả cấu hình trước đó
            $mProductSuggestConfig->deleteProductConfig();
            $mProductSuggestConfigMap->deleteProductConfig();

            foreach ($data as $item) {
                $arrData = [
                    'key' => $item['suggest_key'],
                    'type' => $item['suggest_type'],
                    'is_condition' => $item['suggest_is_condition'],
                    'product_condition_id' => $item['suggest_product_condition_id'],
                    'type_condition' => $item['suggest_type_condition'],
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];

                if (in_array($item['suggest_type_condition'], ['number_date', 'number'])) {
                    $arrData['quantity'] = str_replace(',', '', $item['suggest_quantity']);
                    $arrData['start_date'] = null;
                    $arrData['end_date'] = null;
                    if ($item['suggest_type_condition'] == 'number_date') {

                        $arr_filter = explode(" - ", $item['suggest_date_range']);
                        $from = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d 00:00:00');
                        $to = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d 23:59:59');
                        $arrData['start_date'] = $from;
                        $arrData['end_date'] = $to;
                    }

                    $mProductSuggestConfig->insertProductSuggestConfig($arrData);
                } else {
                    $idProductSuggestConfig = $mProductSuggestConfig->insertProductSuggestConfig($arrData);
                    if ($item['suggest_type_condition'] == 'tags') {
                        $arrTag = [];
                        foreach ($item['suggest_tags'] as $itemTag) {
                            $arrTag[] = [
                                'product_suggest_config_id' => $idProductSuggestConfig,
                                'type' => $item['suggest_type_condition'],
                                'object_id' => $itemTag,
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id()
                            ];
                        }
                        $mProductSuggestConfigMap->insertProductSuggestConfigMap($arrTag);
                    }
                }
            }

            DB::commit();
            return [
                'error' => false,
                'message' => 'Lưu điều kiện sản phẩm gợi ý thành công'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => 'Lưu điều kiện sản phẩm gợi ý thất bại'
            ];
        }
    }

    /**
     * Lấy thông tin cấu hình sản phẩm gợi ý
     * @return mixed|void
     */
    public function getListProductSuggestConfig()
    {
        $mProductSuggestConfig = new ProductSuggestConfigTable();
        $mProductSuggestConfigMap = new ProductSuggestConfigMapTable();

        $listConfig = $mProductSuggestConfig->getAll();

        $keyNumber = '#suggest_quantity_0';
        foreach ($listConfig as $key => $item) {
            $keyNumber = $keyNumber . ',#suggest_quantity_' . ($key + 1);
        }

        $listConfigMap = $mProductSuggestConfigMap->getAll();
        if (count($listConfigMap) != 0) {
            $listConfigMap = collect($listConfigMap)->groupBy('product_suggest_config_id');
            foreach ($listConfigMap as $key => $item) {
                $listConfigMap[$key] = collect($item)->pluck('object_id')->toArray();
            }
        }
        return [
            'keyNumber' => $keyNumber,
            'listConfig' => $listConfig,
            'listConfigMap' => $listConfigMap
        ];
    }

    public function getProductChildTopId()
    {
        return $this->productChild->getProductChildTopId();
    }
    //Kiểm tra trùng tên sản phẩm.
    public function checkSku($sku, $id)
    {
        return $this->productChild->checkSku($sku, $id);
    }
}