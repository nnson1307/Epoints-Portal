<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/13/2018
 * Time: 5:49 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Http\Requests\InventoryInput\InventoryInputSerialStoreRequest;
use Modules\Admin\Models\InventoryInputDetailSerialTable;
use Modules\Admin\Models\InventoryOutputDetailSerialTable;
use Modules\Admin\Models\InventoryOutputTable;
use Modules\Admin\Models\OrderDetailTable;
use Modules\Admin\Models\ProductInventorySerialTable;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\InventoryInput\InventoryInputRepositoryInterface;
use Modules\Admin\Repositories\InventoryInputDetail\InventoryInputDetailRepositoryInterface;
use Modules\Admin\Repositories\InventoryOutput\InventoryOutputRepositoryInterface;
use Modules\Admin\Repositories\InventoryOutputDetail\InventoryOutputDetailRepositoryInterface;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Admin\Repositories\ProductInventory\ProductInventoryRepositoryInterface;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;
use Modules\Admin\Repositories\Supplier\SupplierRepositoryInterface;
use Modules\Admin\Repositories\Unit\UnitRepositoryInterface;
use Modules\Admin\Repositories\Warehouse\WarehouseRepositoryInterface;

class InventoryOutputController extends Controller
{
    protected $inventoryOutput;
    protected $code;
    protected $productChild;
    protected $supplier;
    protected $unit;
    protected $wareHouse;
    protected $inventoryOutputDetail;
    protected $productInventory;
    protected $staff;

    public function __construct(
        InventoryOutputRepositoryInterface $inventoryOutput,
        CodeGeneratorRepositoryInterface $code,
        ProductChildRepositoryInterface $productChild,
        SupplierRepositoryInterface $supplier,
        UnitRepositoryInterface $unit,
        WarehouseRepositoryInterface $wareHouse,
        InventoryOutputDetailRepositoryInterface $inventoryOutputDetail,
        ProductInventoryRepositoryInterface $productInventory,
        StaffRepositoryInterface $staff
    )
    {
        $this->inventoryOutput = $inventoryOutput;
        $this->code = $code;
        $this->productChild = $productChild;
        $this->supplier = $supplier;
        $this->unit = $unit;
        $this->wareHouse = $wareHouse;
        $this->inventoryOutputDetail = $inventoryOutputDetail;
        $this->productInventory = $productInventory;
        $this->staff = $staff;
    }

    public function addAction()
    {
        $wareHouse = $this->wareHouse->getWareHouseOption();
        $supplier = $this->supplier->getAll();
        $user = DB::table('staffs')->where('staff_id', Auth::id())->first();
        $code = 'XK_' . date("Y") . date("m") . date("d") . $this->code->generateServiceCardCode("");

        $view = $this->inventoryOutput->showPopupAddInventory($wareHouse,$supplier,$user,$code);

        return response()->json($view);

//        return view('admin::inventory-output.add', [
//            'wareHouse' => $wareHouse, 'supplier' => $supplier, 'user' => $user, 'code' => $code
//        ]);
    }

    public function searchProductAction(Request $request)
    {
        $data = $request->all();
        $value = $this->productChild->searchProductChildInventoryOutput($data['warehouse'], $data['search']);
        $result = [];
        foreach ($value as $item) {
            $result['results'][] = [
                'id' => $item['product_child_id'],
                'text' => $item['product_child_name']
            ];
        }
        return response()->json($result);
    }

    // Get product child by id.
    public function getProductChildByIdAction(Request $request)
    {
        $productCode = $request->product_code;
        $warehouse = $request->warehouse;
        $result = [];
        $data = $this->productChild->getProductChildById($request->id);
        $listUnit = $this->unit->getUnitWhereNotIn($data['unit_id']);
        $productInventory = $this->productInventory->getProductByWarehouseAndProductCode($warehouse, $productCode);
        $unit = [];

        foreach ($listUnit as $item) {
            $unit[$item['unit_id']] = $item['name'];
        }
        $unitExists = $this->unit->getItem($data['unit_id']);
        $result['product'] = $data;
        $result['unit'] = $unit;
        $result['unitExists'] = $unitExists;
        if ($productInventory != null) {
            $result['productInventory'] = $productInventory->quantitys;
        } else {
            $result['productInventory'] = 0;
        }
        return response()->json($result);
    }

    public function getProductChildByCodeAction(Request $request)
    {
        $productCode = $request->code;
        $warehouse = $request->warehouse;
        $product = $this->productChild->getProductChildByCode($productCode);
        $data = $this->productChild->getProductChildByWarehouseAndCode($warehouse, $productCode);

        if ($product != null) {
            $unitExists = $this->unit->getItem($product['unit_id']);
            $result['product'] = $product;
            $listUnit = $this->unit->getUnitWhereNotIn($product['unit_id']);
            $unit = [];
            foreach ($listUnit as $item) {
                $unit[$item['unit_id']] = $item['name'];
            }
            $result['unit'] = $unit;
            $result['unitExists'] = $unitExists;
            if ($data != null) {
                $result['product_inventory'] = $data['quantity'];
            } else {
                $result['product_inventory'] = 0;
            }
            return response()->json($result);
        } else {
            return response()->json('');
        }
    }

    public function submitAddAction(Request $request)
    {
        try {
            $param = $request->all();
            $created_at = isset($request->created_at) ? Carbon::createFromFormat('d/m/Y',$request->created_at)->format('Y-m-d H:i:s') : Carbon::now()->format('Y-m-d H:i:s');
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $rInventoryInput = app()->get(InventoryInputRepositoryInterface::class);
            $rInventoryOutput = app()->get(InventoryOutputRepositoryInterface::class);
            $mInventoryInputDetiailSerial = app()->get(InventoryInputDetailSerialTable::class);
//        $arrayProducts = $request->arrayProducts;
            $arrayProducts = isset($request->arrayProducts) ? $request->arrayProducts : [];
            $dataError = [];
            $messageExcel = '';
            if (isset($param['file']) && $param['file'] != 'undefined'){
                $dataExcel = $rInventoryOutput->getValueExcelInventoryInput($request->file,$param);

                if ($dataExcel['success'] == 2){
                    return response()->json([
                        'error'=> true,
                        'message' => $dataExcel['message'],
                    ]);
                }

                $arrayProducts = $dataExcel['data_success'];
                $dataError = $dataExcel['data_error'];
                $messageExcel = $dataExcel['message'];
            }

            $dataInventoryOutput = [
                'warehouse_id' => $request->warehouse_id,
                'po_code' => $request->pi_code,
                'created_by' => Auth::id(),
                'created_at' => $created_at,
                'status' => $request->status,
                'note' => $request->note,
                'type' => $request->type,
            ];
            $idInventoryOutput = $this->inventoryOutput->add($dataInventoryOutput);
            $this->inventoryOutput->edit(['po_code' => $this->code->codeDMY('XK', $idInventoryOutput)], $idInventoryOutput);

//        $arrayProductChild = array_chunk($arrayProducts, 3, false);
            $arrayProductChild = $arrayProducts;

            if ($idInventoryOutput > 0) {
                foreach ($arrayProductChild as $key => $value) {
                    $productCode = $value['product_code'];
                    $unitId = $value['unit_id'];
                    $quantity = $value['quantity'];
                    $barcode = $value['barcode'];
                    $inventory_management = $value['inventory_management'];
//                    $currentPrice = str_replace(",", "", $value['cost']);
//                    $quantityRecived = str_replace(",", "", $value['price']);
//                    $currentPrice = str_replace(",", "", $value['price']);
//                    $quantityRecived = $value['quantity'];
////                    $total = str_replace(",", "", $value['total']);
//                    $total = str_replace(",", "", $currentPrice * $quantityRecived);
                    $currentPrice = str_replace(",", "", $value['price'] <= 0 ? $value['cost'] : $value['price']);
                    $quantityRecived = $value['quantity'];
                    $total = str_replace(",", "", $currentPrice*$quantityRecived);
                    $serial = isset($value['serial']) && $value['serial'] != '' && $value['serial'] != null ? explode(',',$value['serial']) : [];
//                $currentPrice = $this->productChild->getProductChildByCode($productCode)->cost;
                    $detail = [
                        'inventory_output_id' => $idInventoryOutput,
                        'product_code' => $productCode,
                        'unit_id' => $unitId,
                        'quantity' => $quantity,
                        'total' => $quantity * $currentPrice,
                        'created_by' => Auth::id(),
                        'created_at' => $created_at,
                        'current_price' => $currentPrice
                    ];

                    $idInventoryOutputDetail = $this->inventoryOutputDetail->add($detail);

                    $dataSerial = [];
                    if ($inventory_management == 'serial'){
                        foreach($serial as $itemSerial){

                            $checkSerial = $mInventoryOutputDetailSerial->checkSerial($productCode,trim(strip_tags($itemSerial)),$idInventoryOutputDetail);

                            if($checkSerial == null){
                                $checkSerialWarehouse = $mInventoryInputDetiailSerial->checkSerialWarehouse($productCode,trim(strip_tags($itemSerial)));
                                if ($checkSerialWarehouse != null){
                                    $dataSerial[] = [
                                        'inventory_output_detail_id' => $idInventoryOutputDetail,
                                        'product_code' => $productCode,
                                        'serial' => trim(strip_tags($itemSerial)),
                                        'barcode' => $barcode,
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now(),
                                    ];
                                }
                            }
                        }

                        if(count($dataSerial) != 0){
                            $mInventoryOutputDetailSerial->insertListSerial($dataSerial);
                        }
                    }
//                if ($idInventoryOutputDetail > 0 && $request->status == "success") {
//                    $getProductByCode = $this->productChild->getProductChildByCode($productCode);
//                    $productId = $getProductByCode->product_child_id;
//                    $checkProductInventory = $this->productInventory->checkProductInventory($productCode, $request->warehouse_id);
//                    if ($checkProductInventory != null) {
//                        $dataEditProductInventory = [
//                            'product_id' => $productId,
//                            'product_code' => $productCode,
//                            'warehouse_id' => $request->warehouse_id,
//                            'export' => $quantity + $checkProductInventory->export,
//                            'quantity' => $checkProductInventory->quantity - $quantity,
//                            'created_at' => $created_at,
//                            'updated_by' => Auth::id(),
//                        ];
//                        $this->productInventory->edit($dataEditProductInventory, $checkProductInventory->product_inventory_id);
//                    }
//                }
                }
                return response()->json([
                    'error'=> false,
                    'message' => $messageExcel != '' ? $messageExcel : __('Thêm phiếu xuất thành công'),
                    'id' => $idInventoryOutput,
                    'dataError' => $dataError,
                    'countError' => count($dataError)
                ]);
            }
        }catch (\Exception $e){
            return response()->json([
                'error'=> true,
                'message' => __('Thêm phiếu xuất thất bại'),
                '__message' => $e->getMessage(),
            ]);
        }
    }

    public function checkQuantityProductInventory(Request $request)
    {
        $check = $this->productInventory->checkProductInventory($request->code, $request->warehouse);
        if ($check != null) {
            if ($request->quantity > $check->quantity) {
                return response()->json(['status' => 0]);
            } else {
                return response()->json(['status' => 1]);
            }
        } else {
            return response()->json(['status' => 0]);
        }
    }

    /**
     * Khai báo filter
     *
     * @return array
     */
    protected function filters()
    {
        $createdBy = (['' => __('Chọn người tạo')]) + $this->staff->getStaffOption();
        $warehouse = (['' => __('Chọn kho')]) + $this->wareHouse->getWareHouseOption();
        return [
            'inventory_outputs$type' => [
                'data' => [
                    '' => __('Chọn loại phiếu'),
                    'normal' => __('Thường'),
                    'transfer' => __('Chuyển kho'),
                    'checking' => __('Kiểm kho'),
                    'return' => __('Trả hàng'),
                ]
            ],
            'inventory_outputs$status' => [

                'data' => [
                    '' => __('Chọn trạng thái'),
                    'success' => __('Hoàn thành'),
                    'new' => __('Mới'),
                    'inprogress' => __('Đang xử lý'),
                    'draft' => __('Lưu nháp'),
                    'cancel' => __('Hủy'),
                ]
            ],
            'inventory_outputs$created_by' => [
                'data' => $createdBy
            ],
            'inventory_outputs$warehouse_id' => [
                'data' => $warehouse
            ]
        ];
    }

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword',
            'inventory_outputs$status', 'inventory_outputs$created_by', 'inventory_outputs$type',
            'inventory_outputs$warehouse_id', 'inventory_outputs$created_at', 'warehouses', 'created_at']);
        $inventoryInputList = $this->inventoryOutput->list($filters);
        return view('admin::inventory-output.list',
            [
                'LIST' => $inventoryInputList,
                'FILTER' => $this->filters(),
                'page' => $filters['page']
            ]);
    }

    public function indexAction()
    {
        $warehouse = (['' => 'Chọn kho']) + $this->wareHouse->getWareHouseOption();
        $inventoryOutputList = $this->inventoryOutput->list();
        return view('admin::inventory-output.index', [
            'LIST' => $inventoryOutputList,
            'FILTER' => $this->filters(),
            'WAREHOUSE' => $warehouse
        ]);
    }

    public function removeAction($id)
    {
        $this->inventoryOutput->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function detailInventoryOutAction($id)
    {
        $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
        $inventoryOutput = $this->inventoryOutput->detail($id);
        $inventoryOutputDetail = $this->inventoryOutputDetail->getInventoryInputDetailByParentId($id);
        $list = collect($inventoryOutputDetail)->forPage(1, 10);

        $listSerial = [];
        $groupInputDetail = [];

        if (count($inventoryOutputDetail) != 0){
            $groupInputDetail = collect($inventoryOutputDetail)->pluck('inventory_output_detail_id');
            $tmp = $mInventoryOutputDetailSerial->getListSerialByDetailLimit($groupInputDetail);
            $listSerial = collect($tmp)->groupBy('inventory_output_detail_id');
        }
        if ($inventoryOutput != null) {
            return view('admin::inventory-output.detail', [
                'inventoryOutput' => $inventoryOutput,
                'listSerial' => $listSerial,
                'data' => $inventoryOutputDetail,
                'LIST' => $list,
                'page' => 1,
                'id'=>$id
            ]);
        }else {
            return redirect()->route('admin.product-inventory');
        }
    }

    /**
     * Chuyển sang trang chỉnh sửa phiếu xuất kho
     * @param $id
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|mixed
     */
    public function editAction($id)
    {
        $inventoryOutput = $this->inventoryOutput->getItem($id);
        if ($inventoryOutput != null || $inventoryOutput->status == "success") {
            $inventoryOutputDetail = $this->inventoryOutputDetail->getInventoryInputDetailByParentId($id);
            $product = [];
            foreach ($inventoryOutputDetail as $item) {
                $currentPrice = $this->productChild->getProductChildByCode($item['code']);
                $price = $this->productChild->getProductChildByCode($item['code']);
                $productInventory = $this->productInventory->getProductByWarehouseAndProductCode($inventoryOutput->warehouse_id, $item['code']);
                if ($productInventory != null && $currentPrice != null && $price != null) {
                    $product[] = [
                        'productName' => $productInventory->name,
                        'productCode' => $item['code'],
                        'quantity' => $item['quantity'],
                        'code' => $item['code'],
                        'unitId' => $productInventory->unitId,
                        'productInventory' => $productInventory->quantitys,
                        'outputQuantity' => $item['quantity'],
                        'currentPrice' => $currentPrice->cost,
//                        'cost' => $currentPrice->cost,
                        'cost' => $item['currentPrice'],
                        'price' => $price->price,
                        'inventory_management' => $item['inventory_management'],
                        'inventory_output_detail_id' => $item['inventory_output_detail_id'],
                    ];
                } else {
                    return redirect()->route('admin.product-inventory');
                }

            }

            $warehouse = $this->wareHouse->getWareHouseOption();
            $productByWarehouse = $this->productChild->getListProductChildInventoryOutput($inventoryOutput->warehouse_id);

            $user = $this->staff->getItem($inventoryOutput->created_by);
            $unit = $this->unit->getUnitOption();


            $listSerial = [];
            $groupOutputDetail = [];
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            if (count($inventoryOutputDetail) != 0){
                $groupOutputDetail = collect($inventoryOutputDetail)->pluck('inventory_output_detail_id');
                $tmp = $mInventoryOutputDetailSerial->getListSerialByDetailLimit($groupOutputDetail);
                $listSerial = collect($tmp)->groupBy('inventory_output_detail_id');
            }

            $getListProductSerial = $this->inventoryOutput->getListProductSerial($inventoryOutput->warehouse_id);


            return view('admin::inventory-output.edit', [
                'inventoryOutput' => $inventoryOutput,
                'warehouse' => $warehouse,
                'user' => $user,
                'unit' => $unit,
                'product' => $product,
                'productByWarehouse' => $productByWarehouse,
                'listSerial' => $listSerial,
                'getListProductSerial' => $getListProductSerial
            ]);
        } else {
            return redirect()->route('admin.product-inventory');
        }
    }

    public function submitEditAction(Request $request)
    {
        $id = $request->id;
        $warehouseId = $request->warehouse_id;
        $poCode = $request->po_code;
        $status = $request->status;
        $note = $request->note;
        $createdAt = Carbon::createFromFormat('d/m/Y H:i:s', $request->created_at)->format('Y-m-d H:i:s');
        $type = $request->type;
        $arrayProduct = $request->arrayProducts;
        $inventoryOutputDetailExists = [];
        $productInventoryOutput = [];

        $mInventoryOutput = app()->get(InventoryOutputTable::class);
        $mOrderDetail = app()->get(OrderDetailTable::class);

        try {
            DB::beginTransaction();
            $dataInventoryOutput = [
                'warehouse_id' => $warehouseId,
                'po_code' => $poCode,
                'updated_by' => Auth::id(),
                'status' => $status,
                'note' => $note,
                'type' => $type,
                'updated_at' => $createdAt
            ];
            $this->inventoryOutput->edit($dataInventoryOutput, $id);

            $detailInventoryOutput = $mInventoryOutput->getItem($id);

            $inventoryOutputDetail = $this->inventoryOutputDetail->getInventoryInputDetailByParentId($id);
            foreach ($inventoryOutputDetail as $key => $value) {
                $inventoryOutputDetailExists[] = $value['code'];
            }

            if ($status == 'success') {

                $messageError = '';
                foreach($inventoryOutputDetail as $v){
                    if ($v['inventory_management'] == 'serial'){
                        if ($v['total_serial'] == 0){
                            $messageError = $messageError.__('Sản phẩm ').$v['code'].__(' chưa có số seri vui lòng kiểm tra lại').'<br>';
                        } else if($v['quantity'] != $v['total_serial']){
                            $messageError = $messageError.__('Sản phẩm ').$v['code'].__(' có số lượng khác với số lượng serial').'<br>';
                        }
                    }

                    if ($detailInventoryOutput['object_id'] != null){
                        $getTotalValueOrder = $mOrderDetail->getTotalQuantity($detailInventoryOutput['object_id'],$v['code']);
                        if ($getTotalValueOrder != $v['quantity']){
                            $messageError = $messageError.__('Sản phẩm ').$v['code'].__(' không trùng với số lượng ở đơn hàng').'<br>';
                        }
                    }

                }

                if ($messageError != ''){
                    return response()->json(['status' => false, 'message' => $messageError]);
                }

                $checkWarehouse = $this->inventoryOutput->checkWarehouse($warehouseId,$id);
                if ($checkWarehouse['error'] == true){
                    return response()->json(['status' => false, 'message' => $checkWarehouse['message']]);
                }

                $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
                $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
                $listSerial = $mInventoryOutputDetailSerial->getListSerialByOutputId($id);
                $dataInventorySerial['status'] = 'export';
                foreach($listSerial as $key => $itemProductSerial){
                    $mProductInventorySerial->updateSerial($dataInventorySerial,$warehouseId,$itemProductSerial['product_code'],$itemProductSerial['serial']);
                }
            }

//            $chunkArrayProduct = array_chunk($arrayProduct, 3, false);
            $chunkArrayProduct = $inventoryOutputDetail;
            foreach ($chunkArrayProduct as $key => $v) {

                $code = $v['code'];
                $unit = $v['unitId'];
                $currentPrice = $v['currentPrice'];
                $outputQuantity = $v['quantity'];
                $total = $v['total'];
                $productInventoryOutput[] = $code;

                if ($status == "success") {
                    $getProductByCode = $this->productChild->getProductChildByCode($code);
                    $productId = $getProductByCode->product_child_id;
                    $checkProductInventory = $this->productInventory->checkProductInventory($code, $warehouseId);
                    if ($checkProductInventory != null) {
                        $dataEditProductInventory = [
                            'product_id' => $productId,
                            'product_code' => $code,
                            'warehouse_id' => $warehouseId,
                            'export' => $outputQuantity + $checkProductInventory->export,
//                            'quantity' => $checkProductInventory->quantity - $outputQuantity,
                            'quantity' => $checkProductInventory->import - ($outputQuantity + $checkProductInventory->export),
                            'created_at' => $createdAt,
                            'updated_by' => Auth::id(),
                        ];
                        $this->productInventory->edit($dataEditProductInventory, $checkProductInventory->product_inventory_id);
                    }

                    $this->inventoryOutput->updateExport($checkWarehouse['arrUpdateSerial']);
                }
            };
            foreach ($inventoryOutputDetailExists as $k => $v) {
                if (!in_array($v, $productInventoryOutput)) {
                    $this->inventoryOutputDetail->removeByParentIdAndProductCode($id, $v);
                }
            }

            DB::commit();
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function renderList(Request $request)
    {
        $warehouse = $request->searchWarehouse;
        $inventoryOutputList = null;
        if ($warehouse != null) {
            $inventoryOutputList = $this->inventoryOutput->list2($warehouse);
        } else {
            $inventoryOutputList = $this->inventoryOutput->list();
        }
        $contents = view('admin::product-inventory.list-inventory-output', [
            'LIST' => $inventoryOutputList,
            'FILTER' => $this->filters(),
        ])
            ->render();
        return $contents;
    }

    public function getProductChildInventoryByWarehouse(Request $request)
    {
        $warehouseId = $request->warehouse_id;
        $result = $this->productChild->getProductChildInventoryOutput($warehouseId);

        return response()->json($result);
    }

    public function pagingDetailAction(Request $request)
    {
        $id=$request->id;
        $page=$request->page;
        $inventoryInputDetail = $this->inventoryOutputDetail->getInventoryInputDetailByParentId($id);
        $list = collect($inventoryInputDetail)->forPage($page, 10);
        $contents = view('admin::inventory-output.paging-detail', [
            'data' => $inventoryInputDetail,
            'LIST' => $list,
            'page' => $page
        ])->render();
        return $contents;
    }

    /**
     * Inventory output
     * Danh sách option của product child load more theo trang
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductChildInventoryOutputOptionPage(Request $request)
    {
        $params = $request->all();
        $result = $this->productChild->getProductChildInventoryOutputOptionPage($params);
        return response()->json($result);
    }

    /**
     * Hiển thị popup danh sách số serial
     * @param Request $request
     */
    public function showPopupListSerial(Request $request){
        $data = $this->inventoryOutput->showPopupListSerial($request->all());
        return response()->json($data);
    }

    /**
     * Lấy danh sách phân trang serial
     * @param Request $request
     */
    public function getListSerial(Request $request){
        $data = $this->inventoryOutput->getListSerial($request->all());
        return response()->json($data);
    }


    /**
     * Hiển thị popup import excel sản phẩm nhập kho
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupAddProductAction(Request $request){
        $data = $this->inventoryOutput->showPopupAddProductAction($request->all());
        return response()->json($data);
    }

    /**
     * Export dữ liệu bị lỗi khi tạo phiếu nhập kho
     */
    public function exportAddInventoryInputError(Request $request){
        $param = $request->all();
        $data = $this->inventoryOutput->exportAddInventoryInputError($param);
        return $data;
    }

    /**
     * Lưu sản phẩm import excel trang chỉnh sửa
     * @param Request $request
     */
    public function submitAddProductAction(Request $request){
        $data = $this->inventoryOutput->submitAddProductAction($request->all());
        return response()->json($data);
    }

    /**
     * Lấy danh sách sản phẩm
     * @param Request $request
     */
    public function getListProductInput(Request $request){
        $data = $this->inventoryOutput->getListProductInput($request->all());
        return response()->json($data);
    }

    /**
     * Lưu cập nhật sản phẩm ở chỉnh sửa sản phẩm xuất kho
     * @param Request $request
     */
    public function submitEditProduct(Request $request){
        $data = $this->inventoryOutput->submitEditProduct($request->all());
        return response()->json($data);
    }

    /**
     * Thêm serial
     * @param Request $request
     */
    public function addSerialProduct(InventoryInputSerialStoreRequest $request){
        $data = $this->inventoryOutput->addSerialProduct($request->all());
        return response()->json($data);
    }

    /**
     * Lấy danh sách serial theo product
     * @param Request $request
     */
    public function getListSerialDetail(Request $request){
        $data = $this->inventoryOutput->getListSerialDetail($request->all());
        return response()->json($data);
    }

    /**
     * Xoá serial sản phẩm chi tiết
     * @param Request $request
     */
    public function removeSerial(Request $request){
        $data = $this->inventoryOutput->removeSerial($request->all());
        return response()->json($data);
    }

    /**
     * Xoá sản phẩm + serial nếu có ở chỉnh sửa nhập kho
     */
    public function deleteProduct(Request $request){
        $data = $this->inventoryOutput->deleteProduct($request->all());
        return response()->json($data);
    }

    /**
     *
     */
    public function getProductChildSerialOptionPage(Request $request){
        $filter = $request->all();
        $filter['perpage'] = @$filter['perpage'] ?? PAGING_ITEM_PER_PAGE;
        $filter['page'] = @$filter['page'] ?? 1;
        $data = $this->inventoryOutput->getProductChildSerialOptionPage($filter);

        return response()->json($data);
    }

    /**
     * Xoá tất cả sản phẩm và serial
     * @param Request $request
     */
    public function removeAllProduct(Request $request){
        $data = $this->inventoryOutput->removeAllProduct($request->all());
        return response()->json($data);
    }
}