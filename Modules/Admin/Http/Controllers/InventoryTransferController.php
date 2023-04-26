<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/15/2018
 * Time: 2:00 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\InventoryInput\InventoryInputRepositoryInterface;
use Modules\Admin\Repositories\InventoryInputDetail\InventoryInputDetailRepositoryInterface;
use Modules\Admin\Repositories\InventoryOutput\InventoryOutputRepositoryInterface;
use Modules\Admin\Repositories\InventoryOutputDetail\InventoryOutputDetailRepositoryInterface;
use Modules\Admin\Repositories\InventoryTransfer\InventoryTransferRepositoryInterface;
use Modules\Admin\Repositories\InventoryTransferDetail\InventoryTransferDetailRepositoryInterface;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Admin\Repositories\ProductInventory\ProductInventoryRepositoryInterface;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;
use Modules\Admin\Repositories\Unit\UnitRepositoryInterface;
use Modules\Admin\Repositories\Warehouse\WarehouseRepositoryInterface;

class InventoryTransferController extends Controller
{
    protected $code;
    protected $productChild;
    protected $unit;
    protected $wareHouse;
    protected $inventoryTransfer;
    protected $inventoryTransferDetail;
    protected $productInventory;
    protected $staff;
    protected $inventoryOutput;
    protected $inventoryOutputDetail;
    protected $inventoryInput;
    protected $inventoryInputDetail;

    public function __construct(
        CodeGeneratorRepositoryInterface $code,
        ProductChildRepositoryInterface $productChild,
        UnitRepositoryInterface $unit,
        WarehouseRepositoryInterface $wareHouse,
        InventoryTransferRepositoryInterface $inventoryTransfer,
        InventoryTransferDetailRepositoryInterface $inventoryTransferDetail,
        ProductInventoryRepositoryInterface $productInventory,
        StaffRepositoryInterface $staff,
        InventoryOutputRepositoryInterface $inventoryOutput,
        InventoryOutputDetailRepositoryInterface $inventoryOutputDetail,
        InventoryInputRepositoryInterface $inventoryInput,
        InventoryInputDetailRepositoryInterface $inventoryInputDetail
    )
    {
        $this->code = $code;
        $this->productChild = $productChild;
        $this->unit = $unit;
        $this->wareHouse = $wareHouse;
        $this->inventoryTransfer = $inventoryTransfer;
        $this->inventoryTransferDetail = $inventoryTransferDetail;
        $this->productInventory = $productInventory;
        $this->staff = $staff;
        $this->inventoryOutput = $inventoryOutput;
        $this->inventoryOutputDetail = $inventoryOutputDetail;
        $this->inventoryInput = $inventoryInput;
        $this->inventoryInputDetail = $inventoryInputDetail;
    }

    public function addAction()
    {
        $user = DB::table('staffs')->where('staff_id', Auth::id())->first();
        $code = $this->code->generateServiceCardCode("");
        $wareHouse = $this->wareHouse->getWareHouseOption();
        return view('admin::inventory-transfer.add', [
            'code' => $code,
            'user' => $user,
            'wareHouse' => $wareHouse
        ]);
    }

    public function searchProductAction(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if (isset($data['search']) && $data['search'] != null) {
                $value = $this->productChild->searchProductChildInventoryOutput($data['warehouseOutput'], $data['search']);
                $result = [];
                foreach ($value as $item) {
                    $result['results'][] = [
                        'id' => $item['product_child_id'],
                        'text' => $item['product_child_name']
                    ];
                }
                return response()->json($result);
            }
        }
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
        $result['units'] = $this->unit->getUnitOption();
        $result['unitExists'] = $unitExists;
        $productInventory = $this->productInventory->getProductByWarehouseAndProductCode($request->warehouseOutput, $data->product_code);
        if ($productInventory != null) {
            $result['productInventory'] = $productInventory->quantitys;
        } else {
            $result['productInventory'] = 0;
        }

        return response()->json($result);
    }

    public function getWarehouseNotId(Request $request)
    {
        $optionWarehouse = $this->wareHouse->getWarehouseNotId($request->id);
        return response()->json($optionWarehouse);
    }

    public function getProductChildByCodeAction(Request $request)
    {
        $productCode = $request->code;
        $warehouseOutput = $request->warehouseOutput;
        $product = $this->productChild->getProductChildByCode($productCode);
        $data = $this->productChild->getProductChildByWarehouseAndCode($warehouseOutput, $productCode);

        if ($product != null) {
            $unitExists = $this->unit->getItem($product['unit_id']);
            $listUnit = $this->unit->getUnitWhereNotIn($product['unit_id']);
            $unit = [];
            foreach ($listUnit as $item) {
                $unit[$item['unit_id']] = $item['name'];
            }

            $result['unit'] = $unit;
            $result['unitExists'] = $unitExists;
            $result['product'] = $product;
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
        $approved_at = Carbon::createFromFormat('d/m/Y', $request->approved_at)->format('Y-m-d');
        $created_at = Carbon::createFromFormat('d/m/Y', $request->transfer_at)->format('Y-m-d');
        $transfer_at = Carbon::createFromFormat('d/m/Y', $request->transfer_at)->format('Y-m-d');
        $arrayProducts = $request->arrayProducts;
        try {
            DB::beginTransaction();
            //Thêm phiếu chuyển kho.
            $dataAdd = [
                'warehouse_to' => $request->warehouse_to,
                'warehouse_from' => $request->warehouse_from,
                'transfer_code' => $request->transfer_code,
                'created_by' => Auth::id(),
                'created_at' => $created_at,
                'transfer_at' => $transfer_at,
                'status' => $request->status,
                'note' => $request->note,
                'approved_at' => $approved_at
            ];
            $id = $this->inventoryTransfer->add($dataAdd);
            $this->inventoryTransfer->edit(['transfer_code' => $this->code->codeDMY('CK', $id)], $id);


            //Chia mảng sản phẩm chuyển kho.
            $arrayProductChild = array_chunk($arrayProducts, 3, false);

            if ($id > 0) {
                foreach ($arrayProductChild as $key => $value) {
                    $productCode = $value[0];
                    $unitId = $value[1];
                    $quantity = $value[2];
                    $quantityTranfer = $value[2];
                    $currentPrice = $this->productChild->getProductChildByCode($productCode)->cost;
                    $total = $quantity * $currentPrice;
                    //Thêm chi tiết phiếu chuyển kho.
                    $detail = [
                        'inventory_tranfer_id' => $id,
                        'product_code' => $productCode,
                        'quantity' => $quantity,
                        'unit_id' => $unitId,
                        'quantity_tranfer' => $quantityTranfer,
                        'total' => intval($total),
                        'created_at' => $created_at,
                        'created_by' => Auth::id(),
                        'current_price' => $currentPrice,
                    ];
                    $this->inventoryTransferDetail->add($detail);

                    //Lưu vào tồn kho nếu status là success.
                    if ($request->status == "success") {

                        //Thêm phiếu xuất kho cho kho xuất.
                        $dataOutput = [
                            'warehouse_id' => $request->warehouse_from,
                            'po_code' => $this->code->generateCodeRandom('XK'),
                            'created_by' => Auth::id(),
                            'created_at' => $created_at,
                            'status' => $request->status,
                            'note' => $request->note,
                            'type' => 'transfer',
                        ];
                        $idAddOutput = $this->inventoryOutput->add($dataOutput);
                        $this->inventoryOutput->edit(['po_code' => $this->code->codeDMY('XK', $idAddOutput)], $idAddOutput);
                        //Thêm chi tiết phiếu xuất kho.
                        $detailOuput = [
                            'inventory_output_id' => $idAddOutput,
                            'product_code' => $productCode,
                            'unit_id' => $unitId,
                            'quantity' => $quantityTranfer,
                            'total' => $quantityTranfer * $currentPrice,
                            'created_by' => Auth::id(),
                            'created_at' => $created_at,
                            'current_price' => $currentPrice
                        ];
                        $this->inventoryOutputDetail->add($detailOuput);

                        //Thêm phiếu nhập cho kho nhập.
                        $dataInput = [
                            'warehouse_id' => $request->warehouse_to,
                            'supplier_id' => '',
                            'pi_code' => $this->code->generateCodeRandom('NK'),
                            'created_by' => Auth::id(),
                            'status' => $request->status,
                            'note' => $request->note,
                            'created_at' => $created_at,
                            'type' => 'transfer'
                        ];
                        $idAddInput = $this->inventoryInput->add($dataInput);
                        $this->inventoryInput->edit(['pi_code' => $this->code->codeDMY('NK', $idAddInput)], $idAddInput);

                        //Thêm chi tiết phiếu nhập kho.
                        $detailInput = [
                            'inventory_input_id' => $idAddInput,
                            'product_code' => $productCode,
                            'unit_id' => $unitId,
                            'quantity' => $quantityTranfer,
                            'current_price' => $currentPrice,
                            'quantity_recived' => $quantityTranfer,
                            'total' => $total,
                            'created_by' => Auth::id(),
                            'created_at' => $created_at
                        ];
                        $this->inventoryInputDetail->add($detailInput);

                        $getProductByCode = $this->productChild->getProductChildByCode($productCode);
                        $productId = $getProductByCode->product_child_id;
                        $checkProductInventory = $this->productInventory->checkProductInventory($productCode, $request->warehouse_to);
                        if ($checkProductInventory == null) {
                            $dataProductInventory = [
                                'product_id' => $productId,
                                'product_code' => $productCode,
                                'warehouse_id' => $request->warehouse_to,
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
                                'warehouse_id' => $request->warehouse_to,
                                'import' => $quantity + $checkProductInventory->import,
                                'quantity' => $quantity + $checkProductInventory->quantity,
                                'created_at' => $created_at,
                                'updated_by' => Auth::id(),
                            ];
                            $this->productInventory->edit($dataEditProductInventory, $checkProductInventory->product_inventory_id);
                        }
                        $check = $this->productInventory->checkProductInventory($productCode, $request->warehouse_from);
                        $dataEditAfterTransfer = [
                            'product_id' => $productId,
                            'product_code' => $productCode,
                            'warehouse_id' => $request->warehouse_from,
                            'export' => $check->export + $quantity,
                            'quantity' => $check->quantity - $quantity,
                            'created_at' => $created_at,
                            'created_by' => Auth::id(),
                        ];
                        $this->productInventory->edit($dataEditAfterTransfer, $check->product_inventory_id);
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function checkQuantityProductInventory(Request $request)
    {
        $check = $this->productInventory->checkProductInventory($request->code, $request->warehouseOutPut);
        if ($request->quantity > $check->quantity) {
            return response()->json(['status' => 0]);
        } else {
            return response()->json(['status' => 1]);
        }
    }

    public function indexAction()
    {
        $productAttributeList = $this->inventoryTransfer->list();
        return view('admin::inventory-transfer.index', [
            'LIST' => $productAttributeList,
            'FILTER' => $this->filters(),
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
        return [
            'inventory_tranfers$status' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    'success' => __('Hoàn thành'),
                    'new' => __('Mới'),
                    'inprogress' => __('Đang xử lý'),
                    'draft' => __('Lưu nháp'),
                    'cancel' => __('Hủy'),
                ]
            ],
            'inventory_tranfers$created_by' => [
                'data' => $createdBy
            ]

        ];
    }

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword',
            'inventory_tranfers$status', 'inventory_tranfers$created_by',
            'inventory_tranfers$created_at', 'warehouses', 'created_at']);
        $inventoryInputList = $this->inventoryTransfer->list($filters);
        return view('admin::inventory-transfer.list',
            [
                'LIST' => $inventoryInputList,
                'FILTER' => $this->filters(),
                'page' => $filters['page']
            ]);
    }

    public function removeAction($id)
    {
        $this->inventoryTransfer->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function detailTransferController($id)
    {
        $inventoryTransfer = $this->inventoryTransfer->detail($id);
        if ($inventoryTransfer != null) {
            $inventoryTransferDetail = $this->inventoryTransferDetail->getInventoryTransfer($id);
            $list = collect($inventoryTransferDetail)->forPage(1, 10);
            return view('admin::inventory-transfer.detail', [
                'inventoryTransfer' => $inventoryTransfer,
                'data' => $inventoryTransferDetail,
                'id' => $id,
                'LIST' => $list,
                'page' => 1
            ]);
        } else {
            return redirect()->route('admin.product-inventory');
        }
    }

    public function pagingDetailAction(Request $request)
    {
        $id = $request->id;
        $page = $request->page;
        $inventoryTransferDetail = $this->inventoryTransferDetail->getInventoryTransfer($id);
        $list = collect($inventoryTransferDetail)->forPage($page, 10);
        $contents = view('admin::inventory-transfer.paging-detail', [
            'data' => $inventoryTransferDetail,
            'LIST' => $list,
            'page' => $page
        ])->render();
        return $contents;
    }

    public function editAction($id)
    {
        $inventoryTransfer = $this->inventoryTransfer->getInventoryTransferEdit($id);
        if ($inventoryTransfer != null) {
            $inventoryTransferDetail = $this->inventoryTransferDetail->getInventoryTransfer($id);
            $product = [];
            foreach ($inventoryTransferDetail as $value) {
                $productInventory = $this->productInventory->getProductByWarehouseAndProductCode($inventoryTransfer->warehouseFrom, $value['productCode']);
                $quantitys = 0;
                if ($productInventory != null) {
                    $quantitys = $productInventory->quantitys;
                }
                $product[] = [
                    'productName' => $value['productName'],
                    'productCode' => $value['productCode'],
                    'transferQuantity' => $value['quantity'],
                    'currentPrice' => $value['currentPrice'],
                    'unitId' => $value['unitId'],
                    'productInventory' => $quantitys
                ];
            }
            $wareHouse = $this->wareHouse->getWareHouseOption();
            $unit = $this->unit->getUnitOption();

            $productByWarehouse = $this->productChild->getProductChildInventoryOutput($inventoryTransfer->warehouseFrom);
            if ($inventoryTransfer->status == "success") {
                return redirect()->route('admin.product-inventory');
            } else {
                return view('admin::inventory-transfer.edit', [
                    'inventoryTransfer' => $inventoryTransfer,
                    'wareHouse' => $wareHouse,
                    'unit' => $unit,
                    'product' => $product,
                    'id' => $id,
                    'productByWarehouse' => $productByWarehouse
                ]);
            }
        } else {
            return redirect()->route('admin.product-inventory');
        }
    }

    public function submitEditAction(Request $request)
    {
        $id = $request->id;
        $arrayProducts = array_chunk($request->arrayProducts, 3, false);
        $transferCode = $request->transfer_code;
        $warehouseFrom = $request->warehouse_from;
        $warehouseTo = $request->warehouse_to;
        $status = $request->status;
        $note = $request->note;
        $time = new \DateTime();
        $createdAt = $time->format("Y-m-d");
        $inventoryTransferDetailExists = [];
        $inventoryTransferDetailAjax = [];
        try {
            DB::beginTransaction();
            $dataAdd = [
                'warehouse_to' => $warehouseTo,
                'warehouse_from' => $warehouseFrom,
                'transfer_code' => $transferCode,
                'updated_by' => Auth::id(),
                'updated_at' => $createdAt,
                'transfer_at' => $createdAt,
                'approved_at' => $createdAt,
                'status' => $status,
                'note' => $note,
//                'approved_at' => $approvedAt
            ];
            $this->inventoryTransfer->edit($dataAdd, $id);
            $inventoryTransferDetail = $this->inventoryTransferDetail->getInventoryTransfer($id);
            foreach ($inventoryTransferDetail as $key => $value) {
                $inventoryTransferDetailExists[] = $value['productCode'];
            }
            foreach ($arrayProducts as $key => $value) {
                $code = $value[0];
                $unit = $value[1];
                $quantity = $value[2];
                $currentPrice = $this->productChild->getProductChildByCode($code)->cost;
                $inventoryTransferDetailAjax[] = $code;
                if (!in_array($code, $inventoryTransferDetailExists)) {
                    $detail = [
                        'inventory_tranfer_id' => $id,
                        'product_code' => $code,
                        'quantity' => $quantity,
                        'unit_id' => $unit,
                        'quantity_tranfer' => $quantity,
                        'total' => $quantity * $currentPrice,
                        'created_at' => $createdAt,
                        'created_by' => Auth::id(),
                        'current_price' => $currentPrice,
                    ];
                    $this->inventoryTransferDetail->add($detail);
                } else {
                    $detailEdit = [
                        'inventory_tranfer_id' => $id,
                        'product_code' => $code,
                        'quantity' => $quantity,
                        'unit_id' => $unit,
                        'quantity_tranfer' => $quantity,
                        'total' => $quantity * $currentPrice,
                        'updated_at' => $createdAt,
                        'updated_by' => Auth::id(),
                        'current_price' => $currentPrice,
                    ];
                    $this->inventoryTransferDetail->editByParentIdAndProductCode($detailEdit, $id, $code);
                }
                if ($status == "success") {
                    //Thêm phiếu xuất kho cho kho xuất.
                    $dataOutput = [
                        'warehouse_id' => $request->warehouse_from,
                        'po_code' => $this->code->generateCodeRandom('XK'),
                        'created_by' => Auth::id(),
                        'created_at' => date('Y-m-d H:i:s'),
                        'status' => $request->status,
                        'note' => $request->note,
                        'type' => 'transfer',
                    ];
                    $idAddOutput = $this->inventoryOutput->add($dataOutput);
                    $this->inventoryOutput->edit(['po_code' => $this->code->codeDMY('XK', $idAddOutput)], $idAddOutput);
                    //Thêm chi tiết phiếu xuất kho.
                    $detailOuput = [
                        'inventory_output_id' => $idAddOutput,
                        'product_code' => $code,
                        'unit_id' => $unit,
                        'quantity' => $quantity,
                        'total' => $quantity * $currentPrice,
                        'created_by' => Auth::id(),
                        'created_at' => date('Y-m-d H:i:s'),
                        'current_price' => $currentPrice
                    ];
                    $this->inventoryOutputDetail->add($detailOuput);

                    //Thêm phiếu nhập cho kho nhập.
                    $dataInput = [
                        'warehouse_id' => $request->warehouse_to,
                        'supplier_id' => '',
                        'pi_code' => $this->code->generateCodeRandom('NK'),
                        'created_by' => Auth::id(),
                        'status' => $request->status,
                        'note' => $request->note,
                        'created_at' => date('Y-m-d H:i:s'),
                        'type' => 'transfer'
                    ];
                    $idAddInput = $this->inventoryInput->add($dataInput);
                    $this->inventoryInput->edit(['pi_code' => $this->code->codeDMY('NK', $idAddInput)], $idAddInput);

                    //Thêm chi tiết phiếu nhập kho.
                    $detailInput = [
                        'inventory_input_id' => $idAddInput,
                        'product_code' => $code,
                        'unit_id' => $unit,
                        'quantity' => $quantity,
                        'current_price' => $currentPrice,
                        'quantity_recived' => $quantity,
                        'total' => $quantity * $currentPrice,
                        'created_by' => Auth::id(),
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    $this->inventoryInputDetail->add($detailInput);

                    $getProductByCode = $this->productChild->getProductChildByCode($code);
                    $productId = $getProductByCode->product_child_id;
                    $checkProductInventory = $this->productInventory->checkProductInventory($code, $warehouseTo);
                    if ($checkProductInventory == null) {
                        $dataProductInventory = [
                            'product_id' => $productId,
                            'product_code' => $code,
                            'warehouse_id' => $warehouseTo,
                            'import' => $quantity,
                            'quantity' => $quantity,
                            'created_at' => $createdAt,
                            'created_by' => Auth::id(),
                        ];
                        $this->productInventory->add($dataProductInventory);
                    } else {
                        $dataEditProductInventory = [
                            'product_id' => $productId,
                            'product_code' => $code,
                            'warehouse_id' => $warehouseTo,
                            'import' => $quantity + $checkProductInventory->import,
                            'quantity' => $quantity + $checkProductInventory->quantity,
                            'created_at' => $createdAt,
                            'updated_by' => Auth::id(),
                        ];
                        $this->productInventory->edit($dataEditProductInventory, $checkProductInventory->product_inventory_id);
                    }
                    $check = $this->productInventory->checkProductInventory($code, $warehouseFrom);
                    $dataEditAfterTransfer = [
                        'product_id' => $productId,
                        'product_code' => $code,
                        'warehouse_id' => $warehouseFrom,
                        'export' => $check->export + $quantity,
                        'quantity' => $check->quantity - $quantity,
                        'created_at' => $createdAt,
                        'created_by' => Auth::id(),
                    ];
                    $this->productInventory->edit($dataEditAfterTransfer, $check->product_inventory_id);
                }
            }
            foreach ($inventoryTransferDetailExists as $k => $v) {
                if (!in_array($v, $inventoryTransferDetailAjax)) {
                    $this->inventoryTransferDetail->removeByParentIdAndProductCode($id, $v);
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
        $inventoryTransferList = null;
        if ($warehouse != null) {
            $inventoryTransferList = $this->inventoryTransfer->list2($warehouse);
        } else {
            $inventoryTransferList = $this->inventoryTransfer->list();
        }
        $contents = view('admin::product-inventory.list-inventory-transfer', [
            'LIST' => $inventoryTransferList,
            'FILTER' => $this->filters(),
        ])
            ->render();
        return $contents;
    }

    public function getProductChildInventoryByWarehouse(Request $request)
    {
        $warehouseId = $request->warehouse_id;
        $result = $this->productChild->getProductChildInventoryOutput($warehouseId);
//        var_dump($result);
        return response()->json($result);
    }
}
