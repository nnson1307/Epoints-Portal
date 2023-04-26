<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/16/2018
 * Time: 4:50 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Http\Requests\InventoryChecking\InventoryCheckingStoreRequest;
use Modules\Admin\Http\Requests\InventoryInput\InventoryInputSerialStoreRequest;
use Modules\Admin\Models\InventoryCheckingDetailSerialTable;
use Modules\Admin\Models\InventoryCheckingDetailTable;
use Modules\Admin\Models\InventoryCheckingLogTable;
use Modules\Admin\Models\InventoryCheckingStatusTable;
use Modules\Admin\Models\InventoryInputDetailSerialTable;
use Modules\Admin\Models\InventoryInputDetailTable;
use Modules\Admin\Models\InventoryInputTable;
use Modules\Admin\Models\InventoryOutputDetailSerialTable;
use Modules\Admin\Models\InventoryOutputDetailTable;
use Modules\Admin\Models\InventoryOutputTable;
use Modules\Admin\Models\ProductInventorySerialTable;
use Modules\Admin\Models\ProductInventoryTable;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\InventoryChecking\InventoryCheckingRepositoryInterface;
use Modules\Admin\Repositories\InventoryCheckingDetail\InventoryCheckingDetailRepositoryInterface;
use Modules\Admin\Repositories\InventoryInput\InventoryInputRepositoryInterface;
use Modules\Admin\Repositories\InventoryInputDetail\InventoryInputDetailRepositoryInterface;
use Modules\Admin\Repositories\InventoryOutput\InventoryOutputRepositoryInterface;
use Modules\Admin\Repositories\InventoryOutputDetail\InventoryOutputDetailRepositoryInterface;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Admin\Repositories\ProductInventory\ProductInventoryRepositoryInterface;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;
use Modules\Admin\Repositories\Unit\UnitRepositoryInterface;
use Modules\Admin\Repositories\Warehouse\WarehouseRepositoryInterface;

class InventoryCheckingController extends Controller
{
    protected $inventoryChecking;
    protected $inventoryCheckingDetail;
    protected $wareHouse;
    protected $code;
    protected $productChild;
    protected $unit;
    protected $productInventory;
    protected $inventoryInput;
    protected $inventoryInputDetail;
    protected $inventoryOutput;
    protected $inventoryOutputDetail;
    protected $staff;

    public function __construct(
        InventoryCheckingRepositoryInterface $inventoryChecking,
        InventoryCheckingDetailRepositoryInterface $inventoryCheckingDetail,
        WarehouseRepositoryInterface $wareHouse,
        CodeGeneratorRepositoryInterface $code,
        ProductChildRepositoryInterface $productChild,
        UnitRepositoryInterface $unit,
        ProductInventoryRepositoryInterface $productInventory,
        InventoryInputRepositoryInterface $inventoryInput,
        InventoryInputDetailRepositoryInterface $inventoryInputDetail,
        InventoryOutputRepositoryInterface $inventoryOutput,
        InventoryOutputDetailRepositoryInterface $inventoryOutputDetail,
        StaffRepositoryInterface $staff
    )
    {
        $this->inventoryChecking = $inventoryChecking;
        $this->inventoryCheckingDetail = $inventoryCheckingDetail;
        $this->wareHouse = $wareHouse;
        $this->code = $code;
        $this->productChild = $productChild;
        $this->unit = $unit;
        $this->productInventory = $productInventory;
        $this->inventoryInput = $inventoryInput;
        $this->inventoryInputDetail = $inventoryInputDetail;
        $this->inventoryOutput = $inventoryOutput;
        $this->inventoryOutputDetail = $inventoryOutputDetail;
        $this->staff = $staff;
    }

    public function addAction()
    {
        $wareHouse = $this->wareHouse->getWareHouseOption();
        $user = DB::table('staffs')->where('staff_id', Auth::id())->first();
        $code = 'KK' . date("Y") . date("m") . date("d") . $this->code->generateServiceCardCode("");

        $view = $this->inventoryChecking->showPopupAddChecking($wareHouse,$code);
        return response()->json($view);

//        return view('admin::inventory-checking.add', [
//            'user' => $user,
//            'wareHouse' => $wareHouse,
//            'code' => $code
//        ]);
    }

    public function searchProductAction(Request $request)
    {
        $data = $request->all();

        if (isset($data['search']) && $data['search'] != null) {
            $result = [];
            $getProductInventory = $this->productInventory->getProductInventoryByCodeOrName($data['warehouse'], $data['search'], $data['search'])->toArray();

            foreach ($getProductInventory as $item) {
                $result['results'][] = [
                    'id' => $item['product_child_id'],
                    'text' => $item['product_child_name']
                ];
            }
            $value = $this->productChild->searchProductChildByWarehouseAndCode($data['warehouse'], $data['search'])->toArray();

            foreach ($value as $item) {
                $result['results'][] = [
                    'id' => $item['product_child_id'],
                    'text' => $item['product_child_name']
                ];
            }
            return response()->json($result);
        }
    }

    // Get product child by warehouse and product child id.
    public function getProductChildByIdAction(Request $request)
    {
        $result = [];
        $param = $request->all();
        $data = $this->productInventory->getProductByWarehouseAndProductId($request->warehouse, $request->id);
        if ($data == null) {
            $productChild = $this->productChild->getProductChildById($request->id);
            $listUnit = $this->unit->getUnitOption();
            $unit = [];
            foreach ($listUnit as $key => $value) {
                $unit[$key] = $value;
            }
            $unitExists = $this->unit->getItem($productChild->unit_id);
            $result['product'] = $productChild;
            $result['unit'] = $unit;
            $result['units'] = $this->unit->getUnitOption();
            $result['unitExists'] = $unitExists;
            $result['totalSerial'] = 0;
            return response()->json(['productInventoryNull' => $result]);
        } else {
            $mInventoryCheckingDetailSerial = app()->get(InventoryCheckingDetailSerialTable::class);
//            $listUnit = $this->unit->getUnitWhereNotIn($data['unitId']);
            $listUnit = $this->unit->getUnitOption();
            $unit = [];
//            foreach ($listUnit as $item) {
//                $unit[$item['unit_id']] = $item['name'];
//            }
            foreach ($listUnit as $key => $value) {
                $unit[$key] = $value;
            }
            $unitExists = $this->unit->getItem($data['unitId']);
            $result['product'] = $data;
            $result['unit'] = $unit;
            $result['units'] = $this->unit->getUnitOption();
            $result['unitExists'] = $unitExists;
            if (isset($param['inventory_checking_id'])){
                $result['totalSerial'] = $mInventoryCheckingDetailSerial->getTotalSerial($param['inventory_checking_id'],$param['productCode']) == 0 ? 0 : $mInventoryCheckingDetailSerial->getTotalSerial($param['inventory_checking_id'],$param['productCode']) + 1;
            } else {
                $result['totalSerial'] = $data['quantitys'];
            }
            return response()->json(['productInventory' => $result]);
        }
    }

    // Get product child by warehouse and product child code.
    public function getProductChildByCodeAction(Request $request)
    {

        $data = $this->productInventory->getProductByWarehouseAndProductCode($request->warehouse, $request->code);

        if ($data != null) {
            $listUnit = $this->unit->getUnitWhereNotIn($data->unitId);
            $unit = [];
            foreach ($listUnit as $item) {
                $unit[$item['unit_id']] = $item['name'];
            }
            $unitExists = $this->unit->getItem($data->unitId);
            $result['product'] = $data;
            $result['unit'] = $unit;
            $result['unitExists'] = $unitExists;
            return response()->json(['productInventory' => $result]);
        } else {
            $productChilds = $this->productChild->getProductChildByWarehouseAndProductCode($request->warehouse, $request->code);

            if ($productChilds != null) {
                $listUnit = $this->unit->getUnitOption();
                $unit = [];
                foreach ($listUnit as $key => $value) {
                    $unit[$key] = $value;
                }
                $unitExists = $this->unit->getItem($productChilds->unit_id);
                $result['product']['product_child_name'] = $productChilds->product_child_name;
                $result['product']['product_code'] = $productChilds->product_code;
                $result['product']['cost'] = $productChilds->cost;
                $result['unit'] = $unit;
                $result['unitExists']['unit_id'] = $unitExists;

                return response()->json(['productInventoryNull' => $result]);
            } else {
                return response()->json(['null' => 1]);
            }

        }
    }

    public function submitAddAction(InventoryCheckingStoreRequest $request)
    {
        $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
        $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
        $mInventoryCheckingStatus = app()->get(InventoryCheckingStatusTable::class);
        $mInventoryCheckingDetailSerial = app()->get(InventoryCheckingDetailSerialTable::class);
        $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
        $param = $request->all();
        $warehouseId = $request->warehouse_id;
        $checkingCode = $request->checkingCode;
        $status = $request->status;
        $reason = $request->description;
        $createdAt = Carbon::now();
//        $arrayProducts = array_chunk($request->arrayProducts, 6, false);
        $arrayProducts = [];
        $code = 'NK' . date("Y") . date("m") . date("d") . $this->code->generateServiceCardCode("");
        $codeInventoryOutput = 'XK' . date("Y") . date("m") . date("d") . $this->code->generateServiceCardCode("");
        $arrSerialInput = [];
        $arrSerialOutput = [];
        $arrSerialOutputInventory = [];
        try {
            DB::beginTransaction();

            $dataError = [];
            $messageExcel = '';

            if (isset($param['file']) && $param['file'] != 'undefined'){
                $dataExcel = $this->inventoryChecking->getValueExcelInventoryInput($request->file,$param);

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

            //Add inventory checking.
            $dataAddInventoryChecking = [
                'warehouse_id' => $warehouseId,
                'checking_code' => $checkingCode,
                'created_by' => Auth::id(),
                'status' => $status,
                'reason' => $reason,
                'created_at' => $createdAt,
            ];
            $idInventoryChecking = $this->inventoryChecking->add($dataAddInventoryChecking);
            $this->inventoryChecking->edit(['checking_code' => $this->code->codeDMY('KK', $idInventoryChecking)], $idInventoryChecking);

            $arrayInventoryInputDetail = [];
            $arrayInventoryOutputDetail = [];
            //Add inventory checking detail.

            foreach ($arrayProducts as $key => $value) {
                $productCode = $value['product_code'];
                $cost = $value['current_price'];
                $unit = $value['unit_id'];
                $quantityOld = $value['quantity_old'];
                $quantityNew = $value['quantity_new'];
                $quantityDifference = $value['quantity_difference'];
//                $typeResolve = 'not';
                $typeResolve1 = 'not';
                $typeResolve2 = 'not';
                $typeResolve = $value['type_resolve'];
                $currentPrice = $cost;
//                if ($quantityDifference > 0) {
//                    $typeResolve = 'output';
//                    $arrayInventoryOutputDetail[] = [$productCode,$cost, $unit, abs($quantityDifference)];
//                }
//
//
//                if ($quantityDifference < 0) {
//                    $typeResolve = 'input';
//                    $arrayInventoryInputDetail[] = [$productCode, $cost, $unit, abs($quantityDifference)];
//                }

                $totalImportSerial = 0;
                $totalExportSerial = 0;


                if ($value['inventory_management'] == 'serial'){
                    foreach ($value['serial'] as $itemSerialList){
                        $itemSerialList = explode(',',$itemSerialList['list']);
                        $listImport = $mProductInventorySerial->getListSerialByProductWarehouse($warehouseId,$value['product_code']);

                        if (count($listImport) != 0){
                            $listImport = collect($listImport)->keyBy('serial');
                        }
                        foreach ($itemSerialList as $keySerialCheck => $itemSerialCheck){
                            if (isset($listImport[$itemSerialCheck])){
                                unset($itemSerialList[$keySerialCheck]);
                                unset($listImport[$itemSerialCheck]);
                            }
                        }

                        if (count($itemSerialList) != 0){
                            $typeResolve1 = 'input';
                            $arrayInventoryInputDetail[] = [$productCode, $cost, $unit, abs(count($itemSerialList))];
                        }

                        if (count($listImport) != 0){
                            $typeResolve2 = 'output';
                            $arrayInventoryOutputDetail[] = [$productCode, $cost, $unit, abs(count($listImport))];
                        }
                    }

                } else {
                    if ($quantityDifference > 0) {
                        $typeResolve = 'output';
                        $arrayInventoryOutputDetail[] = [$productCode, $cost, $unit, abs($quantityDifference)];
                    }


                    if ($quantityDifference < 0) {
                        $typeResolve = 'input';
                        $arrayInventoryInputDetail[] = [$productCode, $cost, $unit, abs($quantityDifference)];
                    }
                }

                $dataAddInventoryCheckingDetail = [
                    'inventory_checking_id' => $idInventoryChecking,
                    'product_code' => $productCode,
                    'quantity_old' => $quantityOld,
                    'quantity_new' => $quantityNew,
                    'quantity_difference' => $quantityDifference,
                    'current_price' => $currentPrice,
                    'total' => abs($quantityDifference) * abs($currentPrice),
                    'type_resolve' => $typeResolve,
                    'created_by' => Auth::id(),
                    'created_at' => $createdAt,
                ];
                $idCheckingDetail = $this->inventoryCheckingDetail->add($dataAddInventoryCheckingDetail);

                if($value['inventory_management'] == 'serial'){
                    foreach ($value['serial'] as $valueSerial){
                        $checkStatus = $mInventoryCheckingStatus->getStatusByName($valueSerial['status_name']);

                        if($checkStatus == null){
                            $checkStatus = $mInventoryCheckingStatus->addStatus([
                                'name' => $valueSerial['status_name'],
                                'is_delete' => 0,
                                'is_active' => 1,
                                'is_default' => 0,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id()
                            ]);
                        }
                        $checkStatus = $checkStatus['inventory_checking_status_id'];

                        $serial = isset($valueSerial['list']) && $valueSerial['list'] != '' && $valueSerial['list'] != null ? explode(',',$valueSerial['list']) : [];

                        $dataSerial = [];
                        foreach($serial as $itemSerial){
                            $checkSerial = '';
//                            if($typeResolve == 'input' || $typeResolve == 'output'){
                                $checkSerial = $mInventoryInputDetailSerial->checkSerialCheckingImport($warehouseId,$productCode,trim(strip_tags($itemSerial)));
//                            }
                            $dataSerial[] = [
                                'inventory_checking_detail_id' => $idCheckingDetail,
                                'product_code' => $productCode,
                                'serial' => trim(strip_tags($itemSerial)),
                                'inventory_checking_status_id' => $checkStatus,
                                'is_new' => $checkSerial == null ? 1 : 0,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ];

                            if ($checkSerial == null){
                                if($typeResolve1 == 'input'){
                                    $arrSerialInput[$productCode][] = [
                                        'product_code' => $productCode,
                                        'serial' => trim(strip_tags($itemSerial)),
                                    ];
                                }

                                if($typeResolve2 == 'output'){
                                    $arrSerialOutput[$productCode][] = [
                                        'product_code' => $productCode,
                                        'serial' => trim(strip_tags($itemSerial)),
                                    ];
                                }
                            }
                        }
                        if (count($serial) != 0){
                            $listSerialExport = $mProductInventorySerial->getListSerialByProductWarehouse($warehouseId,$productCode,$serial);
                            foreach ($listSerialExport as $itemSerialExport){
                                $arrSerialOutput[$productCode][] = [
                                    'product_code' => $productCode,
                                    'serial' => trim(strip_tags($itemSerialExport['serial'])),
                                ];
                            }

                            if (count($listSerialExport) != 0){
                                $arrayInventoryOutputDetail[] = [$productCode,$cost, $unit, abs(count($listSerialExport))];
                            }
                        }

                        if(count($dataSerial) != 0){
                            $mInventoryCheckingDetailSerial->addSerial($dataSerial);
                        }
                    }
                }
            }

            if (count($arrayInventoryInputDetail) != 0) {
                $dataInventoryProduct = [
                    'warehouse_id' => $warehouseId,
                    'supplier_id' => '',
                    'pi_code' => $code,
                    'created_by' => Auth::id(),
                    'status' => $status,
                    'created_at' => $createdAt,
                    'type' => 'checking',
                    'inventory_checking_id' => $idInventoryChecking
                ];
                $idInventoryInput = $this->inventoryInput->add($dataInventoryProduct);
                $this->inventoryInput->edit(['pi_code' => $this->code->codeDMY('NK', $idInventoryInput)], $idInventoryInput);

                foreach ($arrayInventoryInputDetail as $key => $value) {
                    $productCode2 = $value[0];
//                    $currentPrice = $this->productChild->getProductChildByCode($productCode2)->cost;
                    $currentPrice = $value[1];
                    $unitId1 = $value[2];
                    $quantity1 = $value[3];
                    $dataInventoryProductDetail = [
                        'inventory_input_id' => $idInventoryInput,
                        'product_code' => $productCode2,
                        'unit_id' => $unitId1,
                        'quantity' => $quantity1,
                        'current_price' => $currentPrice,
                        'quantity_recived' => $quantity1,
                        'total' => $currentPrice * $quantity1,
                        'created_by' => Auth::id(),
                        'created_at' => $createdAt
                    ];

                    $idInventoryInputDetail = $this->inventoryInputDetail->add($dataInventoryProductDetail);
                    if(isset($arrSerialInput[$productCode2])){
                        $tmpSerialInput = [];
                        foreach($arrSerialInput[$productCode2] as $serialInput){
                            $tmpSerialInput[] = [
                                'inventory_input_detail_id' => $idInventoryInputDetail,
                                'product_code' => $productCode2,
                                'serial' => $serialInput['serial'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        }

                        if(count($tmpSerialInput) != 0){
                            $mInventoryInputDetailSerial->insertListSerial($tmpSerialInput);
                        }

                    }
                }
            }

            if (count($arrayInventoryOutputDetail) != 0) {
                $dataInventoryOutput = [
                    'warehouse_id' => $warehouseId,
                    'po_code' => $codeInventoryOutput,
                    'created_by' => Auth::id(),
                    'created_at' => $createdAt,
                    'status' => $status,
                    'type' => 'checking',
                    'inventory_checking_id' => $idInventoryChecking
                ];
                $idInventoryOutput = $this->inventoryOutput->add($dataInventoryOutput);
                $this->inventoryOutput->edit(['po_code' => $this->code->codeDMY('XK', $idInventoryOutput)], $idInventoryOutput);

                if ($idInventoryOutput > 0) {
                    foreach ($arrayInventoryOutputDetail as $key => $value) {
                        $productCode3 = $value[0];
                        $currentPrice = $value[1];
                        $unitId2 = $value[2];
                        $quantity2 = $value[3];
                        $detail = [
                            'inventory_output_id' => $idInventoryOutput,
                            'product_code' => $productCode3,
                            'unit_id' => $unitId2,
                            'quantity' => $quantity2,
                            'current_price' => $currentPrice,
                            'total' => $currentPrice * $quantity2,
                            'created_by' => Auth::id(),
                            'created_at' => $createdAt,
                        ];
                        $idInventoryOutputDetail = $this->inventoryOutputDetail->add($detail);

                        if(isset($arrSerialOutput[$productCode3])){
                            $tmpSerialOutput = [];
                            foreach($arrSerialOutput[$productCode3] as $serialOutput){
                                $tmpSerialOutput[] = [
                                    'inventory_output_detail_id' => $idInventoryOutputDetail,
                                    'product_code' => $productCode3,
                                    'serial' => $serialOutput['serial'],
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ];
                            }

                            if(count($tmpSerialOutput) != 0){
                                $mInventoryOutputDetailSerial->insertListSerial($tmpSerialOutput);
                            }
                        }
                    }
                }
            }

//                Tạo log kiểm kho

            $mInventoryCheckingLog = app()->get(InventoryCheckingLogTable::class);

            $mInventoryCheckingLog->insertLog([
                'inventory_checking_id' => $idInventoryChecking,
                'staff_id' => Auth::id(),
                'content' => __('Tạo phiếu kiểm kho'),
                'reason' => $reason,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id()
            ]);

            DB::commit();
            return response()->json([
                'error'=> false,
                'message' => $messageExcel != '' ? $messageExcel : __('Thêm phiếu nhập thành công'),
                'id' => $idInventoryChecking,
                'dataError' => $dataError,
                'countError' => count($dataError)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error'=> true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Hiển thị popup import excel sản phẩm kiểm kho
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupAddProductAction(Request $request){
        $data = $this->inventoryChecking->showPopupAddProductAction($request->all());
        return response()->json($data);
    }

    public function submitAddProductAction(Request $request){
        $data = $this->inventoryChecking->submitAddProductAction($request->all());
        return response()->json($data);
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
            'inventory_checkings$status' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    'success' => __('Hoàn thành'),
                    'draft' => __('Lưu nháp'),
                ]
            ],
            'inventory_checkings$created_by' => [
                'data' => $createdBy
            ],
            'inventory_checkings$warehouse_id' => [
                'data' => $warehouse
            ],
        ];
    }

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword',
            'inventory_checkings$warehouse_id', 'inventory_checkings$created_by',
            'inventory_checkings$status', 'created_at', 'warehouses']);
        $inventoryCheckingList = $this->inventoryChecking->list($filters);
        return view('admin::inventory-checking.list',
            [
                'LIST' => $inventoryCheckingList,
                'FILTER' => $this->filters(),
                'page' => $filters['page']
            ]);
    }

    public function indexAction()
    {
        $inventoryCheckingList = $this->inventoryChecking->list();
        return view('admin::inventory-checking.index', [
            'LIST' => $inventoryCheckingList,
            'FILTER' => $this->filters(),
        ]);
    }

    public function detail($id)
    {
        $mInventoryCheckingDetailSerial = app()->get(InventoryCheckingDetailSerialTable::class);
        $inventoryChecking = $this->inventoryChecking->detail($id);
        if ($inventoryChecking != null) {
            $inventoryCheckingDetail = $this->inventoryCheckingDetail->getDetailInventoryCheckingDetailView($id);
            $list = collect($inventoryCheckingDetail)->forPage(1, 10);

            $listSerial = [];
            $groupInputDetail = [];
            if (count($inventoryCheckingDetail) != 0){
                $groupInputDetail = collect($inventoryCheckingDetail)->pluck('inventory_checking_detail_id');
                $tmp = $mInventoryCheckingDetailSerial->getListSerialByDetailLimit($groupInputDetail);
                $listSerial = collect($tmp)->groupBy('inventory_checking_detail_id');
            }

            $listLog = $this->inventoryChecking->getListLog(['inventory_checking_id' => $id]);

            return view('admin::inventory-checking.detail', [
                'inventoryChecking' => $inventoryChecking,
                'LIST' => $list,
                'page' => 1,
                'data' => $inventoryCheckingDetail,
                'id' => $id,
                'listSerial' => $listSerial,
                'listLog' => $listLog
            ]);
        } else {
            return redirect()->route('admin.product-inventory');
        }

    }

    public function pagingDetailAction(Request $request)
    {
        $id=$request->id;
        $page=$request->page;
        $inventoryCheckingDetail = $this->inventoryCheckingDetail->getDetailInventoryCheckingDetail($id);
        $list = collect($inventoryCheckingDetail)->forPage($page, 10);
        $contents = view('admin::inventory-checking.paging-detail', [
            'data' => $inventoryCheckingDetail,
            'LIST' => $list,
            'page' => $page
        ])->render();
        return $contents;
    }

    public function editAction($id)
    {
        $mInventoryCheckingDetailSerial = app()->get(InventoryCheckingDetailSerialTable::class);
        $inventoryChecking = $this->inventoryChecking->getDataEdit($id);
        if ($inventoryChecking != null) {
            if ($inventoryChecking->status == "success") {
                return redirect()->route('admin.product-inventory');
            } else {
                $warehouse = $this->wareHouse->getWareHouseOption();
                $inventoryCheckingDetail = $this->inventoryCheckingDetail->getDetailInventoryCheckingDetailUpdate($id);

                $unit = $this->unit->getUnitOption();

                $getProductInventoryByWarehouseId = $this->productInventory->getProductInventoryByWarehouseIdList($inventoryChecking->warehouseId);

                $getProductChildByBranchesWarehouses = $this->productChild->getProductChildByBranchesWarehousesList($inventoryChecking->warehouseId);

                $arrayIdProductChild = [];
                $productList = [];
                foreach ($getProductInventoryByWarehouseId as $key => $value) {
                    if ($key != null && $value != null) {
                        $productList[$key] = $value;
                        $arrayIdProductChild[] = $key;
                    }
                }

                foreach ($getProductChildByBranchesWarehouses as $key => $value) {
                    if ($key != null && $value != null && !in_array($key, $arrayIdProductChild)) {
                        $productList[$key] = $value;
                    }
                }
                ksort($productList);

                $listSerial = [];
                $groupInputDetail = [];
                if (count($inventoryCheckingDetail) != 0){
                    $groupInputDetail = collect($inventoryCheckingDetail)->pluck('inventory_checking_detail_id');
                    $tmp = $mInventoryCheckingDetailSerial->getListSerialByDetailLimit($groupInputDetail);
                    $listSerial = collect($tmp)->groupBy('inventory_checking_detail_id');
                }

                $listCheckingStatus = $this->inventoryChecking->getListCheckingStatus();

                $listProductByWarehouse = $this->inventoryChecking->getListProductByWarehouse($inventoryChecking->warehouseId);

                return view('admin::inventory-checking.edit', [
                    'warehouse' => $warehouse,
                    'inventoryChecking' => $inventoryChecking,
                    'inventoryCheckingDetail' => $inventoryCheckingDetail,
                    'unit' => $unit,
                    'productList' => $productList,
                    'listSerial' => $listSerial,
                    'listCheckingStatus' => $listCheckingStatus,
                    'listProductByWarehouse' => $listProductByWarehouse
                ]);
            }
        } else {
            return redirect()->route('admin.inventory-checking');
        }
    }

    public function submitEditAction(Request $request)
    {
        $id = $request->id;
        $warehouseId = $request->warehouseId;
        $checkingCode = $request->checkingCode;
        $reason = $request->reason;
        $time = new \DateTime();
        $createdAt = $time->format("Y-m-d");
        $status = $request->status;
        $param = $request->all();

        //Mảng inventory checking detail đã có trong db.
        $inventoryCheckingDetailExists = [];
        //Mảng inventory checking detail nhận được từ request.
        $inventoryCheckingDetailAjax = [];

        $listProduct = $this->inventoryCheckingDetail->getDetailInventoryCheckingDetail($id);

        $this->inventoryChecking->insertLogChecking($listProduct,$id,$reason);

        if ($status == "success") {

            if (isset($param['arrayProducts'])){
                $listProductCheck = array_chunk($param['arrayProducts'], 7, false);
                foreach ($listProductCheck as $itemProductCheck){
                    $product_code = $itemProductCheck[0];
                    $quantity = explode('_',$itemProductCheck[4]);
                    $quantityImport = $quantity[0];
                    $quantityExport = $quantity[1];
                    $inventory_management = $itemProductCheck[6];
                    if ($inventory_management == 'serial'){
                        if ($quantityImport == 0){
//                            Xóa danh sách serial import
                            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
                            $mInventoryInputDetail = app()->get(InventoryInputDetailTable::class);
                            $mInventoryInput = app()->get(InventoryInputTable::class);

                            $mInventoryInputDetailSerial->deleteSerialByChecking($id);
                            $mInventoryInputDetail->removeDetailByCheckingId($id);
                            $mInventoryInput->removeByCheckingId($id);
                        }

                        if ($quantityExport == 0){
//                            Xóa danh sách serial xuất kho
                            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
                            $mInventoryOutputDetail = app()->get(InventoryOutputDetailTable::class);
                            $mInventoryOutput = app()->get(InventoryOutputTable::class);

                            $mInventoryOutputDetailSerial->removeDetailSerialByCheckingId($id);
                            $mInventoryOutputDetail->removeDetailByCheckingId($id);
                            $mInventoryOutput->removeByCheckingId($id);
                        }
                    }
                }
            }

            $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);

            $mInventoryCheckingDetailSerial = app()->get(InventoryCheckingDetailSerialTable::class);
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $mInventoryInputDetail = app()->get(InventoryInputDetailTable::class);
            $mInventoryOutputDetail = app()->get(InventoryOutputDetailTable::class);
            $mProductInventory = app()->get(ProductInventoryTable::class);
            try {

                DB::beginTransaction();
                $dataEditInventoryChecking = [
                    'warehouse_id' => $warehouseId,
                    'checking_code' => $checkingCode,
                    'updated_by' => Auth::id(),
                    'status' => $status,
                    'reason' => $reason,
                    'updated_at' => $createdAt,
                ];
                $this->inventoryChecking->edit($dataEditInventoryChecking, $id);

                $listSerial = $mInventoryCheckingDetailSerial->getListSerial($id);

//                Lấy danh sách nhập kho

                $listProductInputInventory = $mInventoryInputDetail->getListProductInventoryInput($id);

                foreach ($listProductInputInventory as $itemProductInventoryInput){
                    $detailProductInventory = $mProductInventory->checkProductInventory($itemProductInventoryInput['product_code'],$itemProductInventoryInput['warehouse_id']);
                    $dataProductInventory = [
                        'import' => ($detailProductInventory != null ? $detailProductInventory->import : 0) + abs($itemProductInventoryInput['quantity_recived']),
                        'quantity' => ($detailProductInventory != null ? $detailProductInventory->quantity : 0) + abs($itemProductInventoryInput['quantity_recived']),
                        'updated_by' => Auth::id(),
                    ];
                    if ($detailProductInventory == null){
                        $dataProductInventory['product_id'] = $itemProductInventoryInput['product_child_id'];
                        $dataProductInventory['warehouse_id'] = $warehouseId;
                        $dataProductInventory['export'] = 0;
                        $dataProductInventory['created_at'] = Carbon::now();
                        $dataProductInventory['udpated_at'] = Carbon::now();
                        $dataProductInventory['created_by'] = Auth::id();
                        $mProductInventory->add($dataProductInventory);
                    } else {
                        $mProductInventory->edit($dataProductInventory, $detailProductInventory->product_inventory_id);
                    }
                }

//                Lấy danh sách xuất kho

                $listProductOutputInventory = $mInventoryOutputDetail->getListProductInventoryOutput($id);

                foreach ($listProductOutputInventory as $itemProductInventoryOutput){
                    $detailProductInventory = $mProductInventory->checkProductInventory($itemProductInventoryOutput['product_code'],$itemProductInventoryOutput['warehouse_id']);
                    $dataProductInventory = [
                        'import' => $detailProductInventory->import + abs($itemProductInventoryOutput['quantity']),
                        'quantity' => $detailProductInventory->quantity - abs($itemProductInventoryOutput['quantity']),
                        'updated_by' => Auth::id(),
                    ];
                    $mProductInventory->edit($dataProductInventory, $detailProductInventory->product_inventory_id);
                }

                $this->inventoryInput->editByChecking(['status' => $status],$id);
                $this->inventoryOutput->editByChecking(['status' => $status],$id);
//                Nhập kho
                if (count($listSerial) != 0){
//                    $listSerial = collect($listSerial)->groupBy('type_resolve');
                    $tmp = [];

                    foreach($listSerial as $key => $item){
//                        foreach($itemSerial as $item){
                            $tmp[] = [
                                'warehouse_id' => $warehouseId,
                                'product_code' => $item['product_code'],
                                'serial' => $item['serial'],
                                'status' => 'new',
                                'inventory_checking_status_id' => $item['inventory_checking_status_id'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ];
                    }

                    if (count($tmp) != 0){
                        $mProductInventorySerial->insertListSerrial($tmp);
                    }
                }

//                Xuất kho

                $getListSerialOutput = $mInventoryOutputDetailSerial->getListSerialOutputByChecking($id);

                if (count($getListSerialOutput) != 0){
                    $getListSerialOutput = collect($getListSerialOutput)->pluck('serial')->toArray();
                    $mInventoryInputDetailSerial->updateSerialOrder($getListSerialOutput,['is_export' => 1]);
                    $mProductInventorySerial->updateByArrSerial($getListSerialOutput,['status' => 'export']);
                }

                DB::commit();
                return response()->json(['status' => true]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }
        if ($status == "draft") {
            try {
                DB::beginTransaction();
                $dataEditInventoryChecking = [
                    'warehouse_id' => $warehouseId,
                    'checking_code' => $checkingCode,
                    'updated_by' => Auth::id(),
                    'status' => $status,
                    'reason' => $reason,
                    'updated_at' => $createdAt,
                ];
                $this->inventoryChecking->edit($dataEditInventoryChecking, $id);
                $inventoryCheckingDetail = $this->inventoryCheckingDetail->getDetailInventoryCheckingDetail($id);


                DB::commit();
                return response()->json(['status' => true]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    /**
     * Kiểm tra submit hoàn thành
     * @param Request $request
     */
    public function submitEditCheck(Request $request){
        $data = $this->inventoryChecking->submitEditCheck($request->all());
        return response()->json($data);
    }

    public function renderList(Request $request)
    {
        $warehouse = $request->searchWarehouse;
        $inventoryCheckingList = null;
        if ($warehouse != null) {
            $inventoryCheckingList = $this->inventoryChecking->list2($warehouse);
        } else {
            $inventoryCheckingList = $this->inventoryChecking->list();
        }
        $contents = view('admin::product-inventory.list-inventory-checking', [
            'LIST' => $inventoryCheckingList,
            'FILTER' => $this->filters(),
        ])
            ->render();
        return $contents;
    }

    public function getProductChilByWarehouse(Request $request)
    {
        $warehouse = $request->warehouse;
        $getProductInventoryByWarehouseId = $this->productInventory->getProductInventoryByWarehouseId($warehouse);

        $getProductChildByBranchesWarehouses = $this->productChild->getProductChildByBranchesWarehouses($warehouse);

        $arrayIdProductChild = [];
        $result = [];
        foreach ($getProductInventoryByWarehouseId as $key => $value) {
            if ($key != null && $value != null) {
                $result[$key] = $value;
                $arrayIdProductChild[] = $key;
            }
        }

        foreach ($getProductChildByBranchesWarehouses as $key => $value) {
            if ($key != null && $value != null && !in_array($key, $arrayIdProductChild)) {
                $result[$key] = $value;
            }
        }
        ksort($result);
        return response()->json($result);
    }

    public function removeAction($id)
    {
        $this->inventoryChecking->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    /**
     * Hiển thị popup danh sách số serial
     * @param Request $request
     */
    public function showPopupListSerial(Request $request){
        $data = $this->inventoryChecking->showPopupListSerial($request->all());
        return response()->json($data);
    }

    /**
     * Lấy danh sách phân trang serial
     * @param Request $request
     */
    public function getListSerial(Request $request){
        $data = $this->inventoryChecking->getListSerial($request->all());
        return response()->json($data);
    }

    /**
     * Export dữ liệu bị lỗi khi tạo phiếu nhập kho
     */
    public function exportAddInventoryCheckingError(Request $request){
        $param = $request->all();
        $data = $this->inventoryChecking->exportAddInventoryCheckingError($param);
        return $data;
    }

    /**
     * Lưu cập nhật sản phẩm ở chỉnh sửa sản phẩm nhập kho
     * @param Request $request
     */
    public function submitEditProduct(Request $request){
        $data = $this->inventoryChecking->submitEditProduct($request->all());
        return response()->json($data);
    }

    /**
     * Lấy danh sách sản phẩm
     * @param Request $request
     */
    public function getListProductInput(Request $request){
        $data = $this->inventoryChecking->getListProductInput($request->all());
        return response()->json($data);
    }

    /**
     * Thêm serial
     * @param Request $request
     */
    public function addSerialProduct(InventoryInputSerialStoreRequest $request){
        $data = $this->inventoryChecking->addSerialProduct($request->all());
        return response()->json($data);
    }

    /**
     * Xoá serial sản phẩm chi tiết
     * @param Request $request
     */
    public function removeSerial(Request $request){
        $data = $this->inventoryChecking->removeSerial($request->all());
        return response()->json($data);
    }

    /**
     * Xuất file dữ liệu
     */
    public function exportCheckingList(Request $request){
        $data = $this->inventoryChecking->exportCheckingList($request->all());
        return $data;
    }

    /**
     * Xoá sản phẩm
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeProductInline(Request $request){
        $data = $this->inventoryChecking->removeProductInline($request->all());
        return response()->json($data);
    }

    /**
     * Hiển thị popup serial theo sản phẩm xuất hoặc nhập kho
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupSerialProduct(Request $request){
        $data = $this->inventoryChecking->showPopupSerialProduct($request->all());
        return response()->json($data);
    }

    /**
     * Lấy danh sách serial
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListSerialProduct(Request $request){
        $data = $this->inventoryChecking->getListSerialProduct($request->all());
        return response()->json($data);
    }

    /**
     * Lấy danh sách log
     */
    public function getListLog(Request $request){
        $data = $this->inventoryChecking->getListLog($request->all());
        return response()->json($data);
    }

}