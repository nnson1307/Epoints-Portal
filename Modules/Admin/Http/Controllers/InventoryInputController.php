<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/12/2018
 * Time: 9:41 AM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Http\Requests\InventoryInput\InventoryInputSerialStoreRequest;
use Modules\Admin\Http\Requests\InventoryInput\InventoryInputStoreRequest;
use Modules\Admin\Models\InventoryInputDetailSerialTable;
use Modules\Admin\Models\ProductInventorySerialTable;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\InventoryInput\InventoryInputRepositoryInterface;
use Modules\Admin\Repositories\InventoryInputDetail\InventoryInputDetailRepositoryInterface;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Admin\Repositories\ProductInventory\ProductInventoryRepositoryInterface;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;
use Modules\Admin\Repositories\Supplier\SupplierRepositoryInterface;
use Modules\Admin\Repositories\Unit\UnitRepositoryInterface;
use Modules\Admin\Repositories\Warehouse\WarehouseRepositoryInterface;

class InventoryInputController extends Controller
{
    /**
     * @var InventoryInputRepositoryInterface
     */

    protected $inventoryInput;
    protected $inventoryInputDetail;
    protected $wareHouse;
    protected $supplier;
    protected $code;
    protected $productChild;
    protected $unit;
    protected $productInventory;
    protected $staff;

    public function __construct(
        InventoryInputRepositoryInterface $inventoryInput,
        InventoryInputDetailRepositoryInterface $inventoryInputDetail,
        WarehouseRepositoryInterface $wareHouse,
        SupplierRepositoryInterface $supplier,
        CodeGeneratorRepositoryInterface $code,
        ProductChildRepositoryInterface $productChild,
        UnitRepositoryInterface $unit,
        ProductInventoryRepositoryInterface $productInventory,
        StaffRepositoryInterface $staff
    )
    {
        $this->inventoryInput = $inventoryInput;
        $this->inventoryInputDetail = $inventoryInputDetail;
        $this->wareHouse = $wareHouse;
        $this->supplier = $supplier;
        $this->code = $code;
        $this->productChild = $productChild;
        $this->unit = $unit;
        $this->productInventory = $productInventory;
        $this->staff = $staff;
    }

    public function indexAction()
    {
        $productAttributeList = $this->inventoryInput->list();
        $warehouse = (['' => __('Chọn kho')]) + $this->wareHouse->getWareHouseOption();
        return view('admin::inventory-input.index', [
            'LIST' => $productAttributeList,
            'FILTER' => $this->filters(),
            'WAREHOUSE' => $warehouse
        ]);
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
            'inventory_inputs$type' => [
                'data' => [
                    '' => __('Chọn loại phiếu'),
                    'normal' => __('Thường'),
                    'transfer' => __('Chuyển kho'),
                    'checking' => __('Kiểm kho'),
                    'return' => __('Hủy'),
                ]
            ],
            'inventory_inputs$status' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    'success' => __('Hoàn thành'),
                    'new' => __('Mới'),
                    'inprogress' => __('Đang xử lý'),
                    'draft' => __('Lưu nháp'),
                    'cancel' => __('Hủy'),
                ]
            ],
            'inventory_inputs$created_by' => [
                'data' => $createdBy
            ],
            'inventory_inputs$warehouse_id' => [
                'data' => $warehouse
            ]
        ];
    }

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword',
            'inventory_inputs$status', 'inventory_inputs$created_by', 'inventory_inputs$type',
            'inventory_inputs$warehouse_id', 'inventory_inputs$supplier_id',
            'created_at', 'warehouses']);
        $inventoryInputList = $this->inventoryInput->list($filters);

        return view('admin::inventory-input.list',
            [
                'LIST' => $inventoryInputList,
                'FILTER' => $this->filters(),
                'page' => $filters['page']
            ]);
    }

    public function addAction()
    {
        $wareHouse = $this->wareHouse->getWareHouseOption();
        $supplier = $this->supplier->getAll();
        $user = DB::table('staffs')->where('staff_id', Auth::id())->first();
        $code = $this->code->generateServiceCardCode("");
        $product = $this->productChild->getProductChildOptionIdName();

//        return view('admin::inventory-input.add', [
//            'wareHouse' => $wareHouse,
//            'supplier' => $supplier,
//            'user' => $user,
//            'code' => $code,
//            'product' => $product
//        ]);

        $view = $this->inventoryInput->showPopupAddInventory($wareHouse,$supplier,$user,$code,$product);

        return response()->json($view);
    }

    //Search product child.
    public function searchProductAction(Request $request)
    {
        $data = $request->all();
        $value = $this->productChild->searchProductChild($data['search']);
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
        $result = [];
        $data = $this->productChild->getProductChildById($request->id);
        $listUnit = $this->unit->getUnitWhereNotIn($data['unit_id']);
        $unit = [];
        foreach ($listUnit as $item) {
            $unit[$item['unit_id']] = $item['name'];
        }
        $unitExists = $this->unit->getItem($data['unit_id']);
        $result['product'] = $data;
        $result['unit'] = $unit;
        $result['unitExists'] = $unitExists;
        return response()->json($result);
    }

    public function submitAddAction(InventoryInputStoreRequest $request)
    {
        try {
            $param = $request->all();
            $mInventoryInpurDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $request->created_at = isset($request->created_at) ? $request->created_at : Carbon::now()->format('Y-m-d H:i:s');
            $arrayProducts = isset($request->arrayProducts) ? $request->arrayProducts : [];
            $dataError = [];
            $messageExcel = '';

            if (isset($param['file']) && $param['file'] != 'undefined'){
                $dataExcel = $this->inventoryInput->getValueExcelInventoryInput($request->file);

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
            $created_at = Carbon::now()->format('Y-m-d H:i:s');
            $status = $request->status;
            //add inventory product
            $dataInventoryProduct = [
                'warehouse_id' => $request->warehouse_id,
                'supplier_id' => $request->supplier_id,
                'pi_code' => $request->pi_code,
                'created_by' => Auth::id(),
                'status' => $status,
                'note' => $request->note,
                'created_at' => $created_at,
                'type' => $request->type
            ];
            $idInventoryInput = $this->inventoryInput->add($dataInventoryProduct);
            $this->inventoryInput->edit(['pi_code' => $this->code->codeDMY('NK', $idInventoryInput)], $idInventoryInput);
//            $arrayProductsChilds = array_chunk($arrayProducts, 6, false);
            $arrayProductsChilds = $arrayProducts;

            if ($idInventoryInput > 0) {
                foreach ($arrayProductsChilds as $key => $value) {
//                    $productCode = $value[0];
//                    $unitId = $value[1];
//                    $quantity = $value[2];
//                    $currentPrice = str_replace(",", "", $value[3]);
//                    $quantityRecived = str_replace(",", "", $value[4]);
//                    $total = str_replace(",", "", $value[5]);
                    $productCode = $value['product_code'];
                    $unitId = $value['unit_id'];
                    $quantity = $value['quantity'];
                    $barcode = $value['barcode'];
                    $inventory_management = $value['inventory_management'];
//                    $currentPrice = str_replace(",", "", $value['cost']);
//                    $currentPrice = str_replace(",", "", $value['price']);
//                    $quantityRecived = $value['quantity'];
//                    $total = str_replace(",", "", $value['total']);
                    $currentPrice = str_replace(",", "", $value['price'] <= 0 ? $value['cost'] : $value['price']);
                    $quantityRecived = $value['quantity'];
                    $total = str_replace(",", "", $currentPrice*$quantityRecived);
                    $serial = isset($value['serial']) && $value['serial'] != '' && $value['serial'] != null ? explode(',',$value['serial']) : [];
                    $dataInventoryProductDetail = [
                        'inventory_input_id' => $idInventoryInput,
                        'product_code' => $productCode,
                        'unit_id' => $unitId,
                        'quantity' => $quantity,
                        'current_price' => $currentPrice,
                        'quantity_recived' => $quantityRecived,
                        'total' => $total,
                        'created_by' => Auth::id(),
                        'created_at' => $created_at
                    ];
                    $idInventoryInputDetail = $this->inventoryInputDetail->add($dataInventoryProductDetail);
                    $dataSerial = [];
                    if ($inventory_management == 'serial'){
                        foreach($serial as $itemSerial){

                            $checkSerial = $mInventoryInpurDetailSerial->checkSerial($productCode,trim(strip_tags($itemSerial)),$idInventoryInputDetail);

                            if($checkSerial == null){
                                $dataSerial[] = [
                                    'inventory_input_detail_id' => $idInventoryInputDetail,
                                    'product_code' => $productCode,
                                    'serial' => trim(strip_tags($itemSerial)),
                                    'barcode' => $barcode,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ];
                            }
                        }

                        if(count($dataSerial) != 0){
                            $mInventoryInpurDetailSerial->insertListSerial($dataSerial);
                        }
                    }

                    if ($idInventoryInputDetail > 0 && $status == 'success') {
                        $getProductByCode = $this->productChild->getProductChildByCode($productCode);
                        $productId = $getProductByCode->product_child_id;
                        $checkProductInventory = $this->productInventory->checkProductInventory($productCode, $request->warehouse_id);
                        if ($checkProductInventory == null) {
                            $dataProductInventory = [
                                'product_id' => $productId,
                                'product_code' => $productCode,
                                'warehouse_id' => $request->warehouse_id,
                                'import' => $quantity,
                                'quantity' => $quantity,
                                'created_at' => $created_at,
                                'created_by' => Auth::id(),
                            ];
                            $this->productInventory->add($dataProductInventory);
                        } else {
                            $dataEditProductInventory = [
                                'product_id' => $productId,
                                'product_code' => $productCode,
                                'warehouse_id' => $request->warehouse_id,
                                'import' => $quantity + $checkProductInventory->import,
                                'quantity' => $quantity + $checkProductInventory->quantity,
                                'created_at' => $created_at,
                                'updated_by' => Auth::id(),
                            ];
                            $this->productInventory->edit($dataEditProductInventory, $checkProductInventory->product_inventory_id);
                        }

                    }
                }
                return response()->json([
                    'error'=> false,
                    'message' => $messageExcel != '' ? $messageExcel : __('Thêm phiếu nhập thành công'),
                    'id' => $idInventoryInput,
                    'dataError' => $dataError,
                    'countError' => count($dataError)
                ]);
            }
        }catch (\Exception $e) {
            return response()->json([
                'error'=> true,
                'message' => __('Thêm phiếu nhập thất bại'),
            ]);
        }
    }

    /**
     * Export dữ liệu bị lỗi khi tạo phiếu nhập kho
     */
    public function exportAddInventoryInputError(Request $request){
        $param = $request->all();
        $data = $this->inventoryInput->exportAddInventoryInputError($param);
        return $data;
    }

    public function getProductChildByCode(Request $request)
    {
        $data = $this->productChild->getProductChildByCode($request->code);
        if ($data != null) {
            $listUnit = $this->unit->getUnitWhereNotIn($data->unit_id);
            $unit = [];
            foreach ($listUnit as $item) {
                $unit[$item['unit_id']] = $item['name'];
            }
            $unitExists = $this->unit->getItem($data->unit_id);
            $result['product'] = $data;
            $result['unit'] = $unit;
            $result['unitExists'] = $unitExists;
            return response()->json($result);
        } else {
            return response()->json('');
        }
    }

    public function removeAction($id)
    {
        $this->inventoryInput->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function editAction($id)
    {
        $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
        $inventoryInput = $this->inventoryInput->getItem($id);

        if ($inventoryInput == null || $inventoryInput->status == "success") {
            return redirect()->route('admin.product-inventory');
        } else {
            $warehouse = $this->wareHouse->getWareHouseOption();
            $supplier = $this->supplier->getAll();

            $user = $this->staff->getItem($inventoryInput->created_by);

            $inventoryInputDetail = $this->inventoryInputDetail->getInventoryInputDetailByParentId($inventoryInput->inventory_input_id);

            $listSerial = [];
            $groupInputDetail = [];
            if (count($inventoryInputDetail) != 0){
                $groupInputDetail = collect($inventoryInputDetail)->pluck('inventory_input_detail_id');
                $tmp = $mInventoryInputDetailSerial->getListSerialByDetailLimit($groupInputDetail);
                $listSerial = collect($tmp)->groupBy('inventory_input_detail_id');
            }

            $unit = $this->unit->getUnitOption();
            $arrayQuantity = [];
            $arrayTotal = [];
            foreach ($inventoryInputDetail as $key => $value) {
                $arrayQuantity[] = $value['quantity'];
                $arrayTotal[] = $value['total'];
            }
//            $product = $this->productChild->getProductChildOptionIdName();
            $product = $this->productChild->getListProductChild();
            return view('admin::inventory-input.edit', [
                'inventoryInput' => $inventoryInput,
                'warehouse' => $warehouse,
                'supplier' => $supplier,
                'user' => $user,
                'inventoryInputDetail' => $inventoryInputDetail,
                'unit' => $unit,
                'sumQuantity' => array_sum($arrayQuantity),
                'sumTotal' => array_sum($arrayTotal),
                'product' => $product,
                'listSerial' => $listSerial,
            ]);
        }
    }

    public function submitEditAction(Request $request)
    {

        $warehouseId = $request->warehouse_id;
        $supplierId = $request->supplier_id;
        $piCode = $request->pi_code;
        $status = $request->status;
        $note = $request->note;
//        $arrayProducts = $request->arrayProducts;
        $type = $request->type;
        $id = $request->id;
        $time = new \DateTime();
        $created_at = $time->format("Y-m-d H:i:s");
        $inventoryInputDetailExists = [];
        $inventoryInputDetailAjax = [];
        try {
            DB::beginTransaction();
            //Edit inventory product
            $dataInventoryProduct = [
                'warehouse_id' => $warehouseId,
                'supplier_id' => $supplierId,
                'pi_code' => $piCode,
                'updated_by' => Auth::id(),
                'status' => $status,
                'note' => $note,
                'updated_at' => $created_at,
                'type' => $type
            ];
            $this->inventoryInput->edit($dataInventoryProduct, $id);
            $inventoryInputDetail = $this->inventoryInputDetail->getInventoryInputDetailByParentId($id);

            foreach ($inventoryInputDetail as $key => $value) {
                $inventoryInputDetailExists[] = $value['code'];
            }
//            $arrayProductsChilds = array_chunk($arrayProducts, 6, false);

            $arrayProductsChilds = $inventoryInputDetail;

            if ($status == 'success') {
                $messageError = '';
                foreach($inventoryInputDetail as $v){
                    if ($v['inventory_management'] == 'serial'){
                        if ($v['total_serial'] == 0){
                            $messageError = $messageError.__('Sản phẩm ').$v['code'].__(' chưa có số seri vui lòng kiểm tra lại').'<br>';
                        } else if($v['quantity'] != $v['total_serial']){
                            $messageError = $messageError.__('Sản phẩm ').$v['code'].__(' có số lượng khác với số lượng serial').'<br>';
                        }
                    }
                }

                if ($messageError != ''){
                    return response()->json(['status' => false, 'message' => $messageError]);
                }

                $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);

                $listSerial = $mInventoryInputDetailSerial->getListSerialByInputId($id);
                foreach($listSerial as $key => $item){
                    $listSerial[$key]['warehouse_id'] = $warehouseId;
                    $listSerial[$key]['status'] = 'new';
                    $listSerial[$key]['created_at'] = Carbon::now();
                    $listSerial[$key]['updated_at'] = Carbon::now();
                }

                if (count($listSerial) != 0){
                    $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
                    $listSerial = collect($listSerial)->toArray();
                    $mProductInventorySerial->insertListSerrial($listSerial);
                }

            }

            foreach ($arrayProductsChilds as $k => $v) {
                $productCode = $v['code'];
                $unitId = $v['unitId'];
                $quantity = $v['quantity'];
                $currentPrice = $v['currentPrice'];
                $quantityRecived = $v['quantityRecived'];
                $total = $v['total'];
                $inventoryInputDetailAjax[] = $productCode;
                if ($status == 'success') {
                    $getProductByCode = $this->productChild->getProductChildByCode($productCode);
                    $productId = $getProductByCode->product_child_id;
                    $checkProductInventory = $this->productInventory->checkProductInventory($productCode, $warehouseId);
                    if ($checkProductInventory == null) {
                        $dataProductInventory = [
                            'product_id' => $productId,
                            'product_code' => $productCode,
                            'warehouse_id' => $warehouseId,
                            'import' => $quantity,
                            'quantity' => $quantity,
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                        ];
                        $this->productInventory->add($dataProductInventory);
                    } else {
                        $dataEditProductInventory = [
                            'product_id' => $productId,
                            'product_code' => $productCode,
                            'warehouse_id' => $warehouseId,
                            'import' => $quantity + $checkProductInventory->import,
                            'quantity' => $quantity + $checkProductInventory->quantity,
                            'created_at' => Carbon::now(),
                            'updated_by' => Auth::id(),
                        ];
                        $this->productInventory->edit($dataEditProductInventory, $checkProductInventory->product_inventory_id);
                    }
                }
            }
            foreach ($inventoryInputDetailExists as $k => $v) {
                if (!in_array($v, $inventoryInputDetailAjax)) {
                    $this->inventoryInputDetail->removeByParentIdAndProductCode($id, $v);
                }
            }
            DB::commit();
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function detailInventoryInputAction($id)
    {
        $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
        $inventoryInput = $this->inventoryInput->detail($id);

        if ($inventoryInput != null) {
            $inventoryInputDetail = $this->inventoryInputDetail->getInventoryInputDetailByParentId($id);
            $listSerial = [];
            $groupInputDetail = [];
            if (count($inventoryInputDetail) != 0){
                $groupInputDetail = collect($inventoryInputDetail)->pluck('inventory_input_detail_id');
                $tmp = $mInventoryInputDetailSerial->getListSerialByDetailLimit($groupInputDetail);
                $listSerial = collect($tmp)->groupBy('inventory_input_detail_id');
            }

            $list = collect($inventoryInputDetail)->forPage(1, 10);

            return view('admin::inventory-input.detail', [
                'inventoryInput' => $inventoryInput,
                'listSerial' => $listSerial,
                'LIST' => $list,
                'page' => 1,
                'data' => $inventoryInputDetail,
                'id' => $id
            ]);
        } else {
            return redirect()->route('admin.product-inventory');
        }

    }

    public function pagingDetailAction(Request $request)
    {
        $id = $request->id;
        $page = $request->page;
        $inventoryInputDetail = $this->inventoryInputDetail->getInventoryInputDetailByParentId($id);
        $list = collect($inventoryInputDetail)->forPage($page, 10);
        $contents = view('admin::inventory-input.paging-detail', [
            'data' => $inventoryInputDetail,
            'LIST' => $list,
            'page' => $page
        ])->render();
        return $contents;
    }

    public function renderList(Request $request)
    {
        $warehouse = $request->searchWarehouse;
        $inventoryInputList = null;
        if ($warehouse != null) {
            $inventoryInputList = $this->inventoryInput->list2($warehouse);
        } else {
            $inventoryInputList = $this->inventoryInput->list();
        }

        $contents = view('admin::product-inventory.list-inventory-input', [
            'LIST' => $inventoryInputList,
            'FILTER' => $this->filters(),
        ])->render();
        return $contents;
    }

    /**
     * Danh sách option của product child load more theo trang
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductChildOptionPage(Request $request)
    {
        $params = $request->all();
        $result = $this->productChild->getProductChildOptionPage($params);
        return response()->json($result);
    }

    /**
     * Hiển thị popup import excel sản phẩm nhập kho
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupAddProductAction(Request $request){
        $data = $this->inventoryInput->showPopupAddProductAction($request->all());
        return response()->json($data);
    }

    /**
     * Lưu sản phẩm import excel trang chỉnh sửa
     * @param Request $request
     */
    public function submitAddProductAction(Request $request){
        $data = $this->inventoryInput->submitAddProductAction($request->all());
        return response()->json($data);
    }

    /**
     * Xoá sản phẩm + serial nếu có ở chỉnh sửa nhập kho
     */
    public function deleteProduct(Request $request){
        $data = $this->inventoryInput->deleteProduct($request->all());
        return response()->json($data);
    }

    /**
     * Hiển thị popup danh sách số serial
     * @param Request $request
     */
    public function showPopupListSerial(Request $request){
        $data = $this->inventoryInput->showPopupListSerial($request->all());
        return response()->json($data);
    }

    /**
     * Lấy danh sách phân trang serial
     * @param Request $request
     */
    public function getListSerial(Request $request){
        $data = $this->inventoryInput->getListSerial($request->all());
        return response()->json($data);
    }

    /**
     * Lưu cập nhật sản phẩm ở chỉnh sửa sản phẩm nhập kho
     * @param Request $request
     */
    public function submitEditProduct(Request $request){
        $data = $this->inventoryInput->submitEditProduct($request->all());
        return response()->json($data);
    }

    /**
     * Xoá serial sản phẩm chi tiết
     * @param Request $request
     */
    public function removeSerial(Request $request){
        $data = $this->inventoryInput->removeSerial($request->all());
        return response()->json($data);
    }

    /**
     * Lấy danh sách sản phẩm
     * @param Request $request
     */
    public function getListProductInput(Request $request){
        $data = $this->inventoryInput->getListProductInput($request->all());
        return response()->json($data);
    }

    /**
 * Thêm serial
 * @param Request $request
 */
    public function addSerialProduct(InventoryInputSerialStoreRequest $request){
        $data = $this->inventoryInput->addSerialProduct($request->all());
        return response()->json($data);
    }

    /**
     * Lấy danh sách serial theo product
     * @param Request $request
     */
    public function getListSerialDetail(Request $request){
        $data = $this->inventoryInput->getListSerialDetail($request->all());
        return response()->json($data);
    }
}