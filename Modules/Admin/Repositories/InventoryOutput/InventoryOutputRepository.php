<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/13/2018
 * Time: 5:44 PM
 */

namespace Modules\Admin\Repositories\InventoryOutput;

use App\Exports\ExportFile;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\InventoryInputDetailSerialTable;
use Modules\Admin\Models\InventoryOutputDetailSerialTable;
use Modules\Admin\Models\InventoryOutputDetailTable;
use Modules\Admin\Models\InventoryOutputTable;
use Modules\Admin\Models\ProductChildTable;
use Modules\Admin\Models\ProductInventorySerialTable;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Admin\Repositories\ProductInventory\ProductInventoryRepositoryInterface;
use Modules\Admin\Repositories\Unit\UnitRepositoryInterface;

class InventoryOutputRepository implements InventoryOutputRepositoryInterface
{
    protected $inventoryOutput;
    protected $inventoryOutputDetail;
    protected $timestamps = true;

    public function __construct(InventoryOutputTable $inventoryOutput, InventoryOutputDetailTable $inventoryOutputDetail)
    {
        $this->inventoryOutput = $inventoryOutput;
        $this->inventoryOutputDetail = $inventoryOutputDetail;
    }

    /**
     * add inventory output.
     */
    public function add(array $data)
    {
        return $this->inventoryOutput->add($data);
    }

    /**
     *get list inventory output
     */
    public function list(array $filters = [])
    {
        return $this->inventoryOutput->getList2($filters);
    }

    /**
     * delete inventory output
     */
    public function remove($id)
    {
        $this->inventoryOutput->remove($id);
    }

    /*
     * edit inventory output
     */
    public function edit(array $data, $id)
    {
        return $this->inventoryOutput->edit($data, $id);
    }

    /**
     * Cập nhật theo id kiểm kho
     * @param array $data
     * @param $id
     * @return mixed|void
     */
    public function editByChecking(array $data, $id)
    {
        return $this->inventoryOutput->editByChecking($data, $id);
    }

    /*
     *  get inventory output
     */
    public function getItem($id)
    {
        return $this->inventoryOutput->getItem($id);
    }

    /*
     * detail inventory output
     */
    public function detail($id)
    {
        return $this->inventoryOutput->detail($id);
    }
    public function list2($filters)
    {
        return $this->inventoryOutput->getList1($filters);
    }

    /**
     * Lấy warehouse_id từ phiếu xuất kho theo order_id
     *
     * @param $orderId
     * @param $type
     * @return mixed|void
     */
    public function getInfoByOrderId($orderId, $type)
    {
        return $this->inventoryOutput->getInfoByOrderId($orderId, $type);
    }

    /**
     * Popup tạo phiếu xuất kho
     * @param $wareHouse
     * @param $supplier
     * @param $user
     * @param $code
     * @return mixed|void
     */
    public function showPopupAddInventory($wareHouse, $supplier, $user, $code)
    {
        try{
            $view = view('admin::inventory-output.popup.popup-inventory-add',[
                'wareHouse' => $wareHouse,
                'supplier' => $supplier,
                'user' => $user,
                'code' => $code,
            ])->render();

            return[
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e){
            return[
                'error' => true,
                '__message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Hiển thị popup show danh sách serial
     * @param $data
     * @return mixed|void
     */
    public function showPopupListSerial($data)
    {
        try {

            $mInventoryOutputDetail = app()->get(InventoryOutputDetailTable::class);

            $detailProduct = $mInventoryOutputDetail->getDetail($data['inventory_output_detail_id']);

            $view = view('admin::inventory-output.popup.popup-list-serial',[
                'detailProduct' => $detailProduct,
                'inventory_output_detail_id' => $data['inventory_output_detail_id']
            ])->render();

            return [
                'error'=> false,
                'view'=> $view
            ];
        } catch (\Exception $e){
            return [
                'error'=> true,
                'message'=> __('Xem thêm thất bại')
            ];
        }
    }

    /**
     * lấy danh sách phân trang serial
     * @param $data
     * @return mixed|void
     */
    public function getListSerial($data)
    {
        try {

            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $type = $data['type'];
            unset($data['type']);
            $listSerial = $mInventoryOutputDetailSerial->getListSerialPaging($data);

            $view = view('admin::inventory-output.append.list-serial',[
                'listSerial' => $listSerial,
                'inventory_output_detail_id' => $data['inventory_output_detail_id'],
                'type' => $type
            ])->render();

            return [
                'error'=> false,
                'view'=> $view
            ];
        } catch (\Exception $e){
            return [
                'error'=> true,
            ];
        }
    }

    /**
     * Hiển thị popup insert sản phẩm nhập kho
     * @param $data
     * @return mixed|void
     */
    public function showPopupAddProductAction($data)
    {
        try{
            $view = view('admin::inventory-output.popup.popup-inventory-add-product',[
            ])->render();

            return[
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e){
            return[
                'error' => true,
                '__message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Export đữ liệu bị lỗi khi tạo phiếu nhập kho bằng file excel
     * @param $data
     * @return mixed|void
     */
    public function exportAddInventoryInputError($data)
    {
        $header = [
            __('MÃ SẢN PHẨM'),
            __('SỐ LƯỢNG'),
            __('GIÁ NHẬP'),
            __('MÃ VẠCH'),
            __('SỐ SERIAL'),
            __('LỖI'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        return Excel::download(new ExportFile($header, $data['export']), 'error-serial.xlsx');
    }

    public function submitAddProductAction($data)
    {
        try {
            $idInventoryOutput = $data['inventory_output_id'];
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $rInventoryOutputDetail = app()->get(InventoryOutputRepositoryInterface::class);
            $mInventoryOutputDetail = app()->get(InventoryOutputDetailTable::class);
            $mInventoryInputDetiailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $dataExcel = $this->getValueExcelInventoryInput($data['file'],$data);

            if ($dataExcel['success'] == 2){
                return response()->json([
                    'error'=> true,
                    'message' => $dataExcel['message'],
                ]);
            }

            $arrayProducts = $dataExcel['data_success'];
            $dataError = $dataExcel['data_error'];
            $messageExcel = $dataExcel['message'];

            $arrayProductsChilds = $arrayProducts;

            foreach ($arrayProductsChilds as $key => $value) {
                $productCode = $value['product_code'];
                $unitId = $value['unit_id'];
                $quantity = $value['quantity'];
                $barcode = $value['barcode'];
                $inventory_management = $value['inventory_management'];
//                $currentPrice = str_replace(",", "", $value['cost']);
//                $quantityRecived = str_replace(",", "", $value['price']);
//                $currentPrice = str_replace(",", "", $value['price']);
//                $quantityRecived = $value['quantity'];
//                $total = str_replace(",", "", $currentPrice * $quantityRecived);
                $currentPrice = str_replace(",", "", $value['price'] <= 0 ? $value['cost'] : $value['price']);
                $quantityRecived = $value['quantity'];
                $total = str_replace(",", "", $currentPrice*$quantityRecived);
                $serial = isset($value['serial']) && $value['serial'] != '' && $value['serial'] != null ? explode(',',$value['serial']) : [];
                $dataInventoryProductDetail = [
                    'inventory_output_id' => $idInventoryOutput,
                    'product_code' => $productCode,
                    'unit_id' => $unitId,
                    'quantity' => $quantity,
                    'current_price' => $currentPrice,
                    'quantity_recived' => $quantityRecived,
                    'total' => $total,
                    'created_by' => Auth::id(),
                    'created_at' => Carbon::now()
                ];
                $dataSerial = [];
                $checkIdInventoryInput = $mInventoryOutputDetail->checkInventoryOutput($idInventoryOutput,$productCode);
                if($checkIdInventoryInput == null) {
                    $idInventoryOutputDetail = $mInventoryOutputDetail->add($dataInventoryProductDetail);
                } else {
                    $idInventoryOutputDetail = $checkIdInventoryInput['inventory_output_detail_id'];
                }
                if ($inventory_management == 'serial'){
                    foreach($serial as $keySerial => $itemSerial){
                        $checkSerial = $mInventoryOutputDetailSerial->checkSerial($productCode,trim(strip_tags($itemSerial)),$idInventoryOutputDetail);

                        if($checkSerial == null){
//                            Kiểm tra số serial có trong kho hay không
                            $checkSerialWarehouse = $mInventoryInputDetiailSerial->checkSerialWarehouse($productCode,trim(strip_tags($itemSerial)));
                            if($checkSerialWarehouse != null){
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
            }

            return [
                'error'=> false,
                'message' => $messageExcel != '' ? $messageExcel : __('Nhập dữ liệu thành công'),
                'id' => $idInventoryOutput,
                'dataError' => $dataError,
                'countError' => count($dataError)
            ];
        }catch (\Exception $e){
            return [
                'error'=> true,
                'message' => $messageExcel != '' ? $messageExcel : __('Nhập dữ liệu thất bại'),
            ];
        }
    }

    /**
     * lấy danh sách sản phẩm từ file excel
     * @param $file
     * @return mixed|void
     */
    public function getValueExcelInventoryInput($file,$data)
    {
        $arrSuccess = [];
        $arrError = [];
        $mInventoryOutput = app()->get(InventoryOutputTable::class);
        $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
        try {
            if (isset($data['inventory_output_id'])){
                $infoInventoryOutput = $mInventoryOutput->getItem($data['inventory_output_id']);
            } else {
                $infoInventoryOutput['warehouse_id'] = $data['warehouse_id'];
            }

            $typeFileExcel = $file->getClientOriginalExtension();

            if ($typeFileExcel == "xlsx" || $typeFileExcel == "csv") {
                if($typeFileExcel == "xlsx"){
                    $reader = ReaderFactory::create(Type::XLSX);
                } else {
                    $reader = ReaderFactory::create(Type::CSV);
                }

                $reader->open($file);

                $numberSuccess = 0;
                $numberError = 0;

                $mProductChils = app()->get(ProductChildTable::class);

                // sẽ trả về các object gồm các sheet
                foreach ($reader->getSheetIterator() as $sheet) {
                    // đọc từng dòng

                    foreach ($sheet->getRowIterator() as $key => $row) {
                        $tmp = [];

                        if($key > 499){
                            return [
                                'success' => 2,
                                'message' => __('Không được quá 500 bản ghi'),
                                'number_error' => $numberError,
                                'data_error' => $arrError,
                                'data_success' => $arrSuccess
                            ];
                        }
                        if($key >= 2 && ($row[0] != '' || $row[1] != '' || $row[2] != '' || $row[3] != '' || $row[4] != '')){
                            $tmp = [
                                'product_code' => isset($row[0]) ? $row[0] : '',
                                'quantity' => isset($row[1]) ? $row[1] : '',
                                'price' => isset($row[2]) ? $row[2] : '',
                                'barcode' => isset($row[3]) ? $row[3] : '',
                                'serial' => isset($row[4]) ? $row[4] : '',
                            ];
                            if(!isset($tmp['product_code']) || $tmp['product_code'] == ''){
                                $numberError++;
                                $tmp['error_message'] = __('Không có mã sản phẩm');
                                $arrError[] = $tmp;
                            } else {
//                                if ($tmp['quantity'] == '' || $tmp['quantity'] == 0 ){
                                if (is_int($tmp['quantity']) == false){
                                    $numberError++;
                                    if (is_int($tmp['quantity']) == false){
                                        $tmp['error_message'] = __('Số lượng không đúng định dạng');
                                    } else {
                                        $tmp['error_message'] = __('Số lượng phải > 0');
                                    }

                                    $arrError[] = $tmp;
                                } else {
                                    if ($tmp['price'] != '' && is_int($tmp['price']) == false){
                                        $numberError++;
                                        $tmp['error_message'] = __('Giá tiền không hợp lệ');
                                        $arrError[] = $tmp;
                                    } else {
                                        $testCodeProduct = $mProductChils->testProductCode($tmp['product_code']);

                                        if($testCodeProduct == null){
                                            $numberError++;
                                            $tmp['error_message'] = __('Mã sản phẩm không tồn tại');
                                            $arrError[] = $tmp;
                                        } else {

                                            if($testCodeProduct['inventory_management'] == 'serial'){
                                                $tmpSerial  =  explode(',',str_replace(" ","",$tmp['serial']));
                                                $messageErrorSerial = '';
                                                foreach($tmpSerial as $keySerialList => $valueSerial){
                                                    $checkSerialWarehouse = $mInventoryInputDetailSerial->checkSerialWarehouseUse($infoInventoryOutput['warehouse_id'],trim(strip_tags($valueSerial)));
                                                    if ($checkSerialWarehouse == null){
                                                        $messageErrorSerial = $messageErrorSerial.'Số serial '.trim(strip_tags($valueSerial)).' không hợp lệ';
                                                        if(count($tmpSerial) - 1 != $keySerialList){
                                                            $messageErrorSerial = $messageErrorSerial.',';
                                                        }
                                                    }
                                                }

                                                if($messageErrorSerial != ''){
                                                    $numberError++;
                                                    $tmp['error_message'] = $messageErrorSerial;
                                                    $arrError[] = $tmp;
                                                } else {
                                                    $numberSuccess++;
                                                }
                                            } else {
                                                $numberSuccess++;
                                            }

                                            if($testCodeProduct['inventory_management'] == 'serial'){
                                                $tmp['serial'] = implode(',',array_unique($tmpSerial));
                                                if (isset($arrSuccess[$tmp['product_code']])){
                                                    $tmp['serial'] = array_merge(explode(',',$tmp['serial']),explode(',',$arrSuccess[$tmp['product_code']]['serial']));
                                                    $tmp['serial'] = implode(',',array_unique($tmp['serial']));
                                                }
                                                $tmp['quantity'] = count($tmpSerial);
                                            }

                                            $tmp['price'] = isset($tmp['price']) ? $tmp['price'] : $testCodeProduct['quantity'];
                                            $arrSuccess[$tmp['product_code']] = [
                                                'product_code' => $tmp['product_code'],
                                                'unit_id' => $testCodeProduct['unit_id'],
                                                'cost' => $testCodeProduct['price'],
                                                'quantity' => $tmp['quantity'],
                                                'price' => $tmp['price'],
                                                'total' => (int)$tmp['quantity']*(int)$tmp['price'],
                                                'barcode' => $tmp['barcode'],
                                                'serial' => $tmp['serial'],
                                                'inventory_management' => $testCodeProduct['inventory_management']
                                            ];

                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $reader->close();

                return [
                    'success' => 1,
                    'message' => __('Số dòng thành công') . ':' . $numberSuccess . '<br/>' . __('Số dòng thất bại') . ':' . $numberError,
                    'number_error' => $numberError,
                    'data_error' => $arrError,
                    'data_success' => $arrSuccess
                ];
            } else {
                return [
                    'success' => 0,
                    'message' => __('File không đúng định dạng'),
                    'data_error' => $arrError,
                    'data_success' => $arrSuccess
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => 0,
                'message' => __('Import file thất bại'),
                '_message' => $e->getMessage() . ' ' . $e->getLine() . $e->getFile(),
                'data_error' => $arrError,
                'data_success' => $arrSuccess
            ];
        }
    }

    /**
     * Kiểm tra id inventory
     * @param $idInventoryInput
     * @return mixed|void
     */
    public function checkIdInventoryInput($idInventoryOutput,$productCode)
    {
        return $this->inventoryOutputDetail->checkInventoryOutput($idInventoryOutput,$productCode);
    }

    /**
     * Lấy danh sách sản phẩm
     * @param $data
     * @return mixed|void
     */
    public function getListProductInput($data)
    {
        try{

            $rUnit = app()->get(UnitRepositoryInterface::class);
            $rProductChild = app()->get(ProductChildRepositoryInterface::class);
            $rProductInventory = app()->get(ProductInventoryRepositoryInterface::class);

            $unit = $rUnit->getUnitOption();

            $inventoryOutput = $this->inventoryOutput->getItem($data['inventory_output_id']);

            $inventoryOutputDetail = $this->inventoryOutputDetail->getInventoryInputDetailByParentId($data['inventory_output_id']);
            $product = [];
            $totalQuantity = 0;
            $totalMoney = 0;

            $listSerial = [];
            $groupOutputDetail = [];
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            if (count($inventoryOutputDetail) != 0){
                $groupOutputDetail = collect($inventoryOutputDetail)->pluck('inventory_output_detail_id');
                $tmp = $mInventoryOutputDetailSerial->getListSerialByDetail($groupOutputDetail);
                $listSerial = collect($tmp)->groupBy('inventory_output_detail_id');
            }

            foreach ($inventoryOutputDetail as $item) {
                $currentPrice = $rProductChild->getProductChildByCode($item['code']);
                $price = $rProductChild->getProductChildByCode($item['code']);
                $productInventory = $rProductInventory->getProductByWarehouseAndProductCode($inventoryOutput->warehouse_id, $item['code']);
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
//                        'currentPrice' => $currentPrice->cost,
//                        'cost' => $currentPrice->cost,
                        'cost' => $item['currentPrice'],
                        'price' => $price->price,
                        'inventory_management' => $item['inventory_management'],
                        'inventory_output_detail_id' => $item['inventory_output_detail_id'],
                    ];

                    if ($item['inventory_management'] == 'serial'){
                        $totalQuantity +=  isset($listSerial[$item['inventory_output_detail_id']]) ? count($listSerial[$item['inventory_output_detail_id']]) : 0;
//                        $totalMoney += (isset($listSerial[$item['inventory_output_detail_id']]) ? count($listSerial[$item['inventory_output_detail_id']]) : 0) * $currentPrice->cost;
                        $totalMoney += (isset($listSerial[$item['inventory_output_detail_id']]) ? count($listSerial[$item['inventory_output_detail_id']]) : 0) * $item['currentPrice'];
                    } else {
                        $totalQuantity +=  $item['quantity'];
//                        $totalMoney += $item['quantity'] * $currentPrice->cost;
                        $totalMoney += $item['quantity'] * $item['currentPrice'];
                    }

                }
            }

            $view = view('admin::inventory-output.append.block-list-product-main', [
                'inventoryOutput' => $inventoryOutput,
                'unit' => $unit,
                'product' => $product,
                'listSerial' => $listSerial,
                'totalQuantity' => $totalQuantity,
                'totalMoney' => $totalMoney
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];

        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Lấy danh sách thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lưu sản phẩm ở chỉnh sửa sản phẩm nhập kho
     * @param $data
     * @return mixed|void
     */
    public function submitEditProduct($data)
    {
        try {
            $mInventoryOutputDetail = app()->get(InventoryOutputDetailTable::class);
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $mProductChild = app()->get(ProductChildTable::class);

            $arrayProducts = $data['arrayProducts'];
            $id = $data['id'];
            $inventoryInputDetailExists = [];
            $inventoryInputDetail = $mInventoryOutputDetail->getInventoryInputDetailByParentId($id);
            foreach ($inventoryInputDetail as $key => $value) {
                $inventoryInputDetailExists[] = $value['code'];
            }
            $arrayProductsChilds = array_chunk($arrayProducts, 5, false);

            foreach ($arrayProductsChilds as $k => $v) {

                $productCode = $v[0];
                $unitId = $v[1];
                $quantity = $v[2];
                $currentPrice = str_replace(",", "", $v[3]);
                $total = str_replace(",", "", $v[4]);

                $detailCode = $mProductChild->getProductChildByCode($productCode);
                if ($detailCode['inventory_management'] == 'serial'){
                    $quantity = $mInventoryOutputDetailSerial->getTotalSerialOutput($id,$productCode);
                    $total = $currentPrice * $quantity;
                }

                $inventoryInputDetailAjax[] = $productCode;

                if (!in_array($v[0], $inventoryInputDetailExists)) {
                    if (isset($data['serial']) && $data['product_code'] == $productCode){
                        $quantity = $quantity + 1;
                        $total = $quantity * $total;
                    }


                    $dataInventoryProductDetail = [
                        'inventory_output_id' => $id,
                        'product_code' => $productCode,
                        'unit_id' => $unitId,
                        'quantity' => $quantity,
                        'current_price' => $currentPrice,
                        'total' => $total,
                        'created_by' => Auth::id(),
                        'created_at' => Carbon::now()
                    ];
                    $id = $mInventoryOutputDetail->add($dataInventoryProductDetail);
                    if (isset($data['serial']) && $data['product_code'] == $productCode){
                        $dataSerial[] = [
                            'inventory_output_detail_id' => $id,
                            'product_code' => $productCode,
                            'serial' => $data['serial'],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                        $mInventoryOutputDetailSerial->insertListSerial($dataSerial);
                    }
                } else {
                    $dataInventoryProductDetail = [
                        'inventory_output_id' => $id,
                        'product_code' => $productCode,
                        'unit_id' => $unitId,
                        'quantity' => $quantity,
//                        'current_price' => $currentPrice,
                        'total' => $total,
                        'updated_by' => Auth::id(),
                        'updated_at' => Carbon::now()
                    ];
                    $mInventoryOutputDetail->editByInputIdAndProductCode($dataInventoryProductDetail, $id, $v[0]);

                    if (isset($data['serial']) && $data['product_code'] == $productCode){
                        $idOutputDetail = $mInventoryOutputDetail->getDetailOuput($id, $productCode);

                        $checkSerial = $mInventoryOutputDetailSerial->checkSerial($productCode,$data['serial'],$idOutputDetail['inventory_output_detail_id']);
                        if ($checkSerial == null){
                            $dataSerial[] = [
                                'inventory_output_detail_id' => $idOutputDetail['inventory_output_detail_id'],
                                'product_code' => $productCode,
                                'serial' => $data['serial'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ];
                            $mInventoryOutputDetailSerial->insertListSerial($dataSerial);
                        }
                    }
                }
            }

            return [
                'error' => false
            ];

        } catch (\Exception $e){
            return [
                'error' => true
            ];
        }
    }

    /**
     * Thêm serial
     * @param $data
     * @return mixed|void
     */
    public function addSerialProduct($data)
    {
        try{
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);

            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();

            //todo: kiểm tra serial đã tồn tại hay chưa

            $checkSerial = $mInventoryOutputDetailSerial->checkSerial($data['product_code'],$data['serial'],$data['inventory_output_detail_id']);

            if ($checkSerial == null){
                $checkSerialInWarehouse = $mInventoryInputDetailSerial->checkSerialInWarehouse($data['product_code'],$data['serial'],$data['warehouse_id']);

                if (count($checkSerialInWarehouse) != 0){
                    unset($data['warehouse_id']);
                    $mInventoryOutputDetailSerial->insertListSerial($data);
                } else {
                    return [
                        'error' => true,
                        'message' => __('Không tìm thấy dữ liệu')
                    ];
                }


            } else {
                return [
                    'error' => true,
                    'message' => __('Thêm serial bị trùng')
                ];
            }

            return [
                'error' => false,
                'message' => __('Thêm serial thành công')
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Thêm serial thất bại')
            ];
        }
    }

    /**
     * Lấy danh sách serial theo product
     * @param $data
     * @return mixed|void
     */
    public function getListSerialDetail($data)
    {
        try{
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $mInventoryOutputDetail = app()->get(InventoryOutputDetailTable::class);
            $listSerial = [];
            $groupInputDetail = [];

            $detail = $mInventoryOutputDetail->getDetail($data['inventory_output_detail_id']);

            $tmp = $mInventoryOutputDetailSerial->getListSerialByDetail([$data['inventory_output_detail_id']]);
            $listSerial = collect($tmp)->groupBy('inventory_output_detail_id');

            $view = view('admin::inventory-output.append.block_tr_detail', [
                'listSerial' => $listSerial,
                'code' => $detail['product_code'],
                'inventory_output_detail_id' => $data['inventory_output_detail_id']
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Lấy danh sách serial thất bại')
            ];
        }
    }

    /**
     * Xoá serial sản phẩm chi tiết
     * @param $data
     * @return mixed|void
     */
    public function removeSerial($data)
    {
        try{
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $mInventoryOutputDetailSerial->deleteSerialById($data['inventory_output_detail_serial_id']);

            return [
                'error' => false,
                'message' => __('Xoá serial thành công')
            ];

        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Xoá serial thất bại')
            ];
        }
    }

    /**
     * Xoá sản phẩm + serial ở chỉnh sửa nhập kho
     * @param $data
     * @return mixed|void
     */
    public function deleteProduct($data)
    {
        try {

            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $mInventoryOutputDetail = app()->get(InventoryOutputDetailTable::class);

            $mInventoryOutputDetailSerial->deleteSerialInput($data['inventory_output_detail_id']);
            $mInventoryOutputDetail->deleteDetailInput($data['inventory_output_detail_id']);

            return [
                'error'=> false,
                'message'=> __('Xoá sản phẩm xuất kho thành công')
            ];
        }catch (\Exception $e){
            return [
                'error'=> true,
                'message'=> __('Xoá sản phẩm xuất kho thất bại')
            ];
        }
    }

    /**
     * Lấy danh sách serial theo sản phẩm
     * @param $warehouse_id
     * @return mixed|void
     */
    public function getListProductSerial($warehouse_id = null)
    {
        $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
        return $mInventoryInputDetailSerial->getProductChildInventoryOutput($warehouse_id);

    }

    /**
     * Lấy danh sách serial theo sản phẩm có phân trang
     * @param $warehouse_id
     * @return mixed|void
     */
    public function getProductChildSerialOptionPage($filter)
    {
//        $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
//        return $mInventoryInputDetailSerial->getProductChildInventoryOutputPage($filter);

        $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
        return $mProductInventorySerial->getProductChildInventoryOutputPage($filter);

    }

    /**
     * Xoá tất cả sản phẩm và serial
     * @param $data
     * @return mixed|void
     */
    public function removeAllProduct($data)
    {
        try {

            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $mInventoryOutputDetail = app()->get(InventoryOutputDetailTable::class);

            $mInventoryOutputDetailSerial->removeSerialByDetail($data['inventory_output_id']);
            $mInventoryOutputDetail->removeProductByDetail($data['inventory_output_id']);

            return [
                'error'=> false,
            ];
        }catch (\Exception $e){
            return [
                'error'=> true,
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Kiểm tra tồn kho
     * @param $warehouse
     * @param $id
     * @return mixed|void
     */
    public function checkWarehouse($warehouse, $id)
    {
        try {
            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $messageError = '';
//            Lấy danh sách tồn kho
//            $warehouseSerial = $mInventoryInputDetailSerial->getListSerialWarehouse($warehouse);
            $warehouseSerial = $mProductInventorySerial->getListSerialWarehouse($warehouse);

            if (count($warehouseSerial) != 0){
                $warehouseSerial = collect($warehouseSerial)->keyBy('key_group');
            }


//            Lấy danh sách serial của phiếu xuất
            $listSerial = $mInventoryOutputDetailSerial->getListSerial($id);
            if (count($listSerial) != 0){
                $listSerial = collect($listSerial)->keyBy('key_group');
            }

            $arrUpdate = [];
            foreach($listSerial as $key => $item){
                if (!isset($warehouseSerial[$key])){
                    $messageError = $messageError.'Serial '.$item['serial'].' không có trong kho<br>';
                } else {
//                    $arrUpdate[] = $warehouseSerial[$key]['inventory_input_detail_serial_id'];
                    $arrUpdate[] = $warehouseSerial[$key]['serial'];
                }
            }

            if ($messageError == ''){
                return [
                    'error' => false,
                    'arrUpdateSerial' => $arrUpdate
                ];
            } else {
                return [
                    'error' => true,
                    'message' => $messageError,
                    'arrUpdateSerial' => []
                ];
            }


        }catch (\Exception $e){
            return [
                'error' => true,
                '__message'=> $e->getMessage()
            ];
        }
    }

    /**
     * Cập nhật xuất kho
     * @param $arrIdDetailSerial
     * @return mixed|void
     */
    public function updateExport($arrIdDetailSerial)
    {
        $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);

        $mInventoryInputDetailSerial->updateExport($arrIdDetailSerial);

        return true;
    }
}