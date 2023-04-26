<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/16/2018
 * Time: 4:44 PM
 */

namespace Modules\Admin\Repositories\InventoryChecking;

use App\Exports\ExportFile;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\InventoryCheckingDetailSerialTable;
use Modules\Admin\Models\InventoryCheckingDetailTable;
use Modules\Admin\Models\InventoryCheckingLogTable;
use Modules\Admin\Models\InventoryCheckingStatusTable;
use Modules\Admin\Models\InventoryCheckingTable;
use Modules\Admin\Models\InventoryInputDetailSerialTable;
use Modules\Admin\Models\InventoryInputDetailTable;
use Modules\Admin\Models\InventoryInputTable;
use Modules\Admin\Models\InventoryOutputDetailSerialTable;
use Modules\Admin\Models\InventoryOutputDetailTable;
use Modules\Admin\Models\InventoryOutputTable;
use Modules\Admin\Models\ProductChildTable;
use Modules\Admin\Models\ProductInventorySerialTable;
use Modules\Admin\Models\ProductInventoryTable;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\InventoryCheckingDetail\InventoryCheckingDetailRepositoryInterface;
use Modules\Admin\Repositories\Unit\UnitRepositoryInterface;

class InventoryCheckingRepository implements InventoryCheckingRepositoryInterface
{
    protected $inventoryChecking;
    protected $timestamps = true;

    public function __construct(InventoryCheckingTable $inventoryChecking)
    {
        $this->inventoryChecking = $inventoryChecking;
    }

    /**
     * add inventory checking.
     */
    public function add(array $data)
    {
        return $this->inventoryChecking->add($data);
    }

    /**
     *get list inventory checking
     */
    public function list(array $filters = [])
    {
        return $this->inventoryChecking->getList2($filters);
    }

    /**
     * delete inventory checking
     */
    public function remove($id)
    {
        $this->inventoryChecking->remove($id);
    }

    /*
     * edit inventory checking
     */
    public function edit(array $data, $id)
    {
        return $this->inventoryChecking->edit($data, $id);
    }

    /*
     *  get inventory checking
     */
    public function getItem($id)
    {
        return $this->inventoryChecking->getItem($id);
    }

    /*
     * Detail inventory checking.
     */
    public function detail($id)
    {
        return $this->inventoryChecking->detail($id);
    }

    /*
   * get data edit
   */
    public function getDataEdit($id)
    {
        return $this->inventoryChecking->getDataEdit($id);
    }
    public function list2($filters)
    {
        return $this->inventoryChecking->getList1($filters);
    }

    /**
     * Hiển thị popup tạo phiếu kiểm kho
     * @param $wareHouse
     * @param $code
     * @return mixed|void
     */
    public function showPopupAddChecking($wareHouse, $code)
    {
        try{
            $view = view('admin::inventory-checking.popup.popup-inventory-add',[
                'wareHouse' => $wareHouse,
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

            $mInventoryCheckingDetail = app()->get(InventoryCheckingDetailTable::class);
            $mInventoryCheckingStatus = app()->get(InventoryCheckingStatusTable::class);

            $detailProduct = $mInventoryCheckingDetail->getDetail($data['inventory_checking_detail_id']);

            $getListStatus = $mInventoryCheckingStatus->getAll();

            $view = view('admin::inventory-checking.popup.popup-list-serial',[
                'detailProduct' => $detailProduct,
                'listStatus' => $getListStatus,
                'inventory_checking_detail_id' => $data['inventory_checking_detail_id']
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

            $mInventoryCheckingDetailSerial = app()->get(InventoryCheckingDetailSerialTable::class);
            $mInventoryCheckingDetail = app()->get(InventoryCheckingDetailTable::class);
            $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
            $type = $data['type'];
            unset($data['type']);
            $detailCheckingDetail = $mInventoryCheckingDetail->getDetail($data['inventory_checking_detail_id']);

//            Lấy danh sách serial

            $listProductSerial = $mProductInventorySerial->getListSerialByProductWarehouse($detailCheckingDetail['warehouse_id'],$detailCheckingDetail['product_code']);

            if (count($listProductSerial) != 0){
                $listProductSerial = collect($listProductSerial)->keyBy(function ($item){
                    return $item['product_code'].'-'.$item['serial'];
                });

                $data['list_serial'] = collect($listProductSerial)->pluck('serial');
            }



            $listSerial = $mInventoryCheckingDetailSerial->getListSerialPaging($data);

            $view = view('admin::inventory-checking.append.list-serial',[
                'listSerial' => $listSerial,
//                'inventory_checking_detail_id' => $data['inventory_checking_detail_id'],
                'type' => $type,
                'listProductSerial' => $listProductSerial
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
     * lấy danh sách sản phẩm từ file excel
     * @param $file
     * @return mixed|void
     */
    public function getValueExcelInventoryInput($file,$data)
    {
        $arrSuccess = [];
        $arrError = [];

        try {
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
                $mProductInventory = app()->get(ProductInventoryTable::class);
                $listSerialProduct = [];
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
                                'serial' => isset($row[1]) ? $row[1] : '',
                                'quantity_old' => isset($row[2]) ? trim($row[2]) : '',
                                'quantity_new' => isset($row[3]) ? trim($row[3]) : '',
                                'status_name' => isset($row[4]) ? $row[4] : '',
                            ];

                            if(!isset($tmp['product_code']) || $tmp['product_code'] == ''){
                                $numberError++;
                                $tmp['error_message'] = __('Không có mã sản phẩm');
                                $arrError[] = $tmp;
                            } else {
                                if ($tmp['quantity_old'] != '' ){

                                    if (is_int((int)$tmp['quantity_old']) == false){
                                        $numberError++;
                                        $tmp['error_message'] = __('Số lượng không đúng định dạng');
                                        $arrError[] = $tmp;
                                    }

                                    if ((int)$tmp['quantity_old'] < 1){
                                        $numberError++;
                                        $tmp['error_message'] = __('Số lượng phải > 0');
                                        $arrError[] = $tmp;
                                    }
                                } else {
                                    if ($tmp['quantity_new'] != ''){
                                        if (is_int((int)$tmp['quantity_new']) == false){
                                            $numberError++;
                                            $tmp['error_message'] = __('Số lượng không đúng định dạng');
                                            $arrError[] = $tmp;
                                        }

                                        if ((int)$tmp['quantity_new'] < 1){
                                            $numberError++;
                                            $tmp['error_message'] = __('Số lượng phải > 0');
                                            $arrError[] = $tmp;
                                        }

                                    }
                                }
                            }
                            if (!isset($tmp['error_message'])){
                                $testCodeProduct = $mProductChils->testProductCode($tmp['product_code']);
                                $inventoryProduct = $mProductInventory->checkProductInventory($tmp['product_code'],$data['warehouse_id']);
                                if ($inventoryProduct == null){
                                    $inventoryProduct['quantity'] = 0;
                                }

                                if($testCodeProduct == null){
                                    $numberError++;
                                    $tmp['error_message'] = __('Mã sản phẩm không tồn tại');
                                    $arrError[] = $tmp;
                                } else {
                                    $numberSuccess++;
                                    if ($testCodeProduct['inventory_management'] == 'serial'){
                                        $quantity_difference = (int)$inventoryProduct['quantity'] - count(explode(',',$tmp['serial']));
                                        $tmp['quantity_new'] = count(explode(',',$tmp['serial']));
                                    } else {
                                        $quantity_difference = (int)$inventoryProduct['quantity'] - (int)$tmp['quantity_new'];
                                    }

                                    if(isset($arrSuccess[$tmp['product_code']])){
                                        $quantity_difference = $inventoryProduct['quantity'] - ((int)$arrSuccess[$tmp['product_code']]['quantity_new'] + $tmp['quantity_new']);
                                        $arrSuccess[$tmp['product_code']]['quantity_new'] = (int)$arrSuccess[$tmp['product_code']]['quantity_new'] + (int)$tmp['quantity_new'];
                                        $arrSuccess[$tmp['product_code']]['quantity_difference'] = $quantity_difference;
                                        $arrSuccess[$tmp['product_code']]['total'] = $quantity_difference*(int)$testCodeProduct['price'];
                                        $arrSuccess[$tmp['product_code']]['status_name'] = $tmp['status_name'];
                                        $arrSuccess[$tmp['product_code']]['type_resolve'] = $quantity_difference > 0 ? 'output' : ($quantity_difference < 0 ? 'input' : 'not');

                                        $listSerialProduct[$tmp['product_code']] = explode(',',$listSerialProduct[$tmp['product_code']]);
                                        $tmp['serial'] = explode(',',$tmp['serial']);
                                        foreach ($tmp['serial'] as $keySerial => $itemSerial){
                                            if (in_array($itemSerial,$listSerialProduct[$tmp['product_code']])){
                                                unset($tmp['serial'][$keySerial]);
                                            }
                                        }

                                        if (count($tmp['serial']) != 0){
                                            $tmp['serial'] = implode(',',$tmp['serial']);
                                            if (isset($arrSuccess[$tmp['product_code']]['serial'][$tmp['status_name']])){
                                                $listOld = explode(',',$arrSuccess[$tmp['product_code']]['serial'][$tmp['status_name']]['list']);
                                                $listNew = explode(',',$tmp['serial']);
                                                $listSerial = array_merge($listOld,$listNew);
                                                $arrSuccess[$tmp['product_code']]['serial'][$tmp['status_name']] = [
                                                    'status_name' => $tmp['status_name'],
                                                    'list' => implode(',',array_unique($listSerial)),
                                                ];
                                            } else {
                                                $arrSuccess[$tmp['product_code']]['serial'][$tmp['status_name']] = [
                                                    'status_name' => $tmp['status_name'],
                                                    'list' => $tmp['serial'],
                                                ];
                                            }
                                        }
                                    } else {
                                        $arrSuccess[$tmp['product_code']] = [
                                            'product_code' => $tmp['product_code'],
                                            'unit_id' => $testCodeProduct['unit_id'],
                                            'quantity_old' => (int)$inventoryProduct['quantity'],
                                            'quantity_new' => $tmp['quantity_new'],
                                            'quantity_difference' => $quantity_difference,
                                            'current_price' => $testCodeProduct['price'],
                                            'total' => $quantity_difference*(int)$testCodeProduct['price'],
                                            'status_name' => $tmp['status_name'],
                                            'type_resolve' => $quantity_difference > 0 ? 'output' : ($quantity_difference < 0 ? 'input' : 'not'),
                                            'inventory_management' => $testCodeProduct['inventory_management']
                                        ];

                                        $arrSuccess[$tmp['product_code']]['serial'][$tmp['status_name']] = [
                                            'status_name' => $tmp['status_name'],
                                            'list' => $tmp['serial'],
                                        ];

                                        $listSerialProduct[$tmp['product_code']] = $tmp['serial'];
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
     * Export đữ liệu bị lỗi khi tạo phiếu nhập kho bằng file excel
     * @param $data
     * @return mixed|void
     */
    public function exportAddInventoryCheckingError($data)
    {
        $header = [
            __('MÃ SẢN PHẨM'),
            __('SỐ SERIAL'),
            __('HỆ THỐNG'),
            __('THỰC TẾ'),
            __('TRẠNG THÁI'),
            __('LỖI'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        return Excel::download(new ExportFile($header, $data['export']), 'error-serial.xlsx');
    }


    public function submitEditProduct($data)
    {
        $id = $data['id'];
        $warehouseId = $data['warehouseId'];
        $checkingCode = $data['checkingCode'];
        $reason = $data['reason'];
        $arrayProducts = array_chunk($data['arrayProducts'], 6, false);
        if (count($arrayProducts) != 0){
            $arrayProducts = collect($arrayProducts)->keyBy(0);
        }
        $time = new \DateTime();
        $createdAt = $time->format("Y-m-d");
        $status = $data['status'];
        //Mảng inventory checking detail đã có trong db.
        $inventoryCheckingDetailExists = [];
        //Mảng inventory checking detail nhận được từ request.
        $inventoryCheckingDetailAjax = [];

        $rInventoryCheckingDetail = app()->get(InventoryCheckingDetailRepositoryInterface::class);
        $productChild = app()->get(ProductChildTable::class);
        $inventoryInput = app()->get(InventoryInputTable::class);
        $inventoryInputDetail = app()->get(InventoryInputDetailTable::class);
        $inventoryOutput = app()->get(InventoryOutputTable::class);
        $inventoryOutputDetail = app()->get(InventoryOutputDetailTable::class);
        $rCode = app()->get(CodeGeneratorRepositoryInterface::class);
        $mInventoryCheckingDetailSerial = app()->get(InventoryCheckingDetailSerialTable::class);
        $mInventoryCheckingDetail = app()->get(InventoryCheckingDetailTable::class);
        $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
        $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
        $mInventoryCheckingStatus = app()->get(InventoryCheckingStatusTable::class);
        $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
        $mProductInventory = app()->get(ProductInventoryTable::class);
        $arrSerialInput = [];
        $arrSerialOutput = [];
        try {
            DB::beginTransaction();

            $inventoryCheckingDetail = $rInventoryCheckingDetail->getDetailInventoryCheckingDetail($id);

            $firstStatusChecking = $mInventoryCheckingStatus->getFirstDefault();

            foreach ($inventoryCheckingDetail as $key => $value) {
                $inventoryCheckingDetailExists[] = $value['productCode'];
            }

            foreach ($arrayProducts as $key => $value) {

                $detailProductChild = $productChild->getProductChildByCode($value[0]);

                $code = $value[0];
                $unit = $value[1];
                $quantityOld = $value[2];
                $quantityNew = $value[3];
                $quantityDifference = $value[4];
                $note = $value[5];
                $currentPrice = $productChild->getProductChildByCode($code)->cost;
                $typeResolve = 'not';
                $inventoryCheckingDetailAjax[] = $code;
                if ($quantityDifference > 0) {
                    $typeResolve = 'output';
                }
                if ($quantityDifference < 0) {
                    $typeResolve = 'input';
                }


                $checkSerial = '';
                if (in_array($typeResolve,['output','input']) && isset($data['serial'])){
                    $checkSerial = $mProductInventorySerial->checkSerialChecking($warehouseId,$code,trim(strip_tags($data['serial'])));
                }

//                Cập nhật số lượng ở tổng kho
                if ($status == 'success'){
//                    lấy chi tiết kho
                    $detailProductInventory = $mProductInventory->checkProductInventory($code,$warehouseId);

                    if ($detailProductInventory != null){
                        $productIdGet = $productChild->getProductChildByCode($code);
                        $dataProductInventory = null;
                        if ($typeResolve == 'input'){
                            $dataProductInventory = [
                                'import' => $detailProductInventory->import + abs($quantityDifference),
                                'quantity' => $detailProductInventory->quantity + abs($quantityDifference),
                                'updated_by' => Auth::id(),
                            ];

                        }

                        if ($typeResolve == 'output'){
                            $dataProductInventory = [
                                'export' => $detailProductInventory->export + abs($quantityDifference),
                                'quantity' => $detailProductInventory->quantity - abs($quantityDifference),
                                'updated_by' => Auth::id(),
                            ];
                        }

                        if ($dataProductInventory != null){
                            $mProductInventory->edit($dataProductInventory, $detailProductInventory->product_inventory_id);
                        }
                    }
                }
                if (!in_array($code, $inventoryCheckingDetailExists)) {

                    if (isset($data['serial']) && $data['product_code'] == $code){
                        $quantityNew = $quantityNew + 1;
                        $quantityDifference = $quantityOld - $quantityNew;
                    }
                    //Thêm chi tiết phiếu kiểm mới.
                    $dataEditInventoryCheckingDetail = [
                        'inventory_checking_id' => $id,
                        'product_code' => $code,
                        'quantity_old' => $quantityOld,
                        'quantity_new' => $quantityNew,
                        'quantity_difference' => $quantityDifference,
                        'current_price' => $currentPrice,
                        'note' => $note,
                        'total' => abs($quantityDifference) * abs($currentPrice),
                        'type_resolve' => $typeResolve,
                        'created_by' => Auth::id(),
                        'created_at' => $createdAt,
                    ];
                    $idCheckingDetailAdd = $rInventoryCheckingDetail->add($dataEditInventoryCheckingDetail);

                    if (isset($data['serial']) && $data['product_code'] == $code){
                        $dataSerial[] = [
                            'inventory_checking_detail_id' => $idCheckingDetailAdd,
                            'product_code' => $code,
                            'serial' => $data['serial'],
                            'inventory_checking_status_id' => $firstStatusChecking['inventory_checking_status_id'],
                            'is_new' => $checkSerial == null ? 1 : 0,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                        $mInventoryCheckingDetailSerial->insertListSerial($dataSerial);

                        if ($checkSerial == null){
//                            if($typeResolve == 'input'){
                                $arrSerialInput[$code][] = [
                                    'product_code' => $code,
                                    'serial' => trim(strip_tags($data['serial'])),
                                ];
//                            }
//                        } else {
//                            if($typeResolve == 'output'){
                                $arrSerialOutput[$code][] = [
                                    'product_code' => $code,
                                    'serial' => trim(strip_tags($data['serial'])),
                                ];
//                            }
                        }
                    }
                } else {
                    //Sửa chi tiết phiếu kiểm đã tồn tại.
                    $dataEditInventoryCheckingDetail2 = [
                        'inventory_checking_id' => $id,
                        'product_code' => $code,
                        'quantity_old' => $quantityOld,
                        'quantity_new' => $quantityNew,
                        'quantity_difference' => $quantityDifference,
                        'current_price' => $currentPrice,
                        'note' => $note,
                        'total' => abs($quantityDifference) * abs($currentPrice),
                        'type_resolve' => $typeResolve,
                        'updated_by' => Auth::id(),
                        'updated_at' => $createdAt,
                    ];
                    $rInventoryCheckingDetail->editByParentIdAndProductCode($id, $code, $dataEditInventoryCheckingDetail2);

                    if (isset($data['serial']) && $data['product_code'] == $code){

                        $idCheckingDetail = $mInventoryCheckingDetail->getDetailChecking($id, $code);

                        $checkSerial = $mInventoryCheckingDetailSerial->checkSerial($code,$data['serial'],$idCheckingDetail['inventory_checking_detail_id']);
                        if ($checkSerial == null) {
                            $checkSerialInput = $mProductInventorySerial->checkSerialChecking($warehouseId,$code,trim(strip_tags($data['serial'])));
                            $dataSerial[] = [
                                'inventory_checking_detail_id' => $idCheckingDetail['inventory_checking_detail_id'],
                                'product_code' => $code,
                                'serial' => $data['serial'],
                                'inventory_checking_status_id' => $firstStatusChecking['inventory_checking_status_id'],
                                'is_new' => $checkSerialInput == null ? 1 : 0,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ];

                            $mInventoryCheckingDetailSerial->insertListSerial($dataSerial);

                            if ($checkSerial == null){
//                                if($typeResolve == 'input'){
                                    $arrSerialInput[$code][] = [
                                        'product_code' => $code,
                                        'serial' => trim(strip_tags($data['serial'])),
                                    ];
//                                }
                            } else {
//                                if($typeResolve == 'output'){
                                    $arrSerialOutput[$code][] = [
                                        'product_code' => $code,
                                        'serial' => trim(strip_tags($data['serial'])),
                                    ];
//                                }
                            }
                        }
                    }
                }
            }

            foreach ($inventoryCheckingDetailExists as $k => $v) {
                if (!in_array($v, $inventoryCheckingDetailAjax)) {
                    $rInventoryCheckingDetail->removeByParentIdAndProductCode($id, $v);
                }
            }

            $arrayInventoryCheckingDetail = $rInventoryCheckingDetail->getDetailInventoryCheckingDetail($id);
            $arrayInput = [];
            $arrayOutput = [];

            foreach ($arrayInventoryCheckingDetail as $item) {
                if ($item['inventory_management'] != 'serial'){
                    if ($item['typeResolve'] == "input") {
                        $arrayInput[] = [$item['productCode'], $item['currentPrice'], $item['unitId'], abs($item['quantityDifference'])];
                    }
                    if ($item['typeResolve'] == "output") {
                        $arrayOutput[] = [$item['productCode'], $item['currentPrice'], $item['unitId'], abs($item['quantityDifference'])];
                    }
                } else {
                    if ($item['total_import'] != 0) {
                        $arrayInput[] = [$item['productCode'], $item['currentPrice'], $item['unitId'], abs($item['total_import'])];
                    }
                    if ($item['total_export'] != 0) {
                        $arrayOutput[] = [$item['productCode'], $item['currentPrice'], $item['unitId'], abs($item['total_export'])];
                    }
                }

            }

            if (count($arrayInput) != 0) {
                //Thêm phiếu nhập
                $addInput = [
                    'warehouse_id' => $warehouseId,
                    'supplier_id' => '',
                    'pi_code' => "NK" . $rCode->generateServiceCardCode(""),
                    'created_by' => Auth::id(),
                    'status' => $status,
                    'note' => '',
                    'created_at' => date('Y-m-d'),
                    'type' => 'checking',
                    'inventory_checking_id' => $id
                ];
                $detailInput = $inventoryInput->getDetailByIdChecking($id);
                if ($detailInput == null){
                    $idInput = $inventoryInput->add($addInput);
                    $inventoryInput->edit(['pi_code' => $rCode->codeDMY('NK', $idInput)], $idInput);
                } else {
                    $idInput = $detailInput['inventory_input_id'];
                }

                //Thêm chi tiết phiếu nhập.
                foreach ($arrayInput as $it) {
                    $productCode = $it[0];
                    $currentPrices = $it[1];
                    $unitId = $it[2];
                    $quantity = $it[3];
                    $dataInventoryProductDetail = [
                        'inventory_input_id' => $idInput,
                        'product_code' => $productCode,
                        'unit_id' => $unitId,
                        'quantity' => $quantity,
                        'current_price' => $currentPrices,
                        'quantity_recived' => $quantity,
                        'total' => $quantity * $currentPrices,
                        'created_by' => Auth::id(),
                        'created_at' => date('Y-m-d')
                    ];
                    $checkInputDetail = $inventoryInputDetail->checkInventoryInput($idInput,$productCode);
                    if ($checkInputDetail == null){
                        $idInputDetail = $inventoryInputDetail->add($dataInventoryProductDetail);
                    } else {
                        unset($dataInventoryProductDetail['created_by']);
                        unset($dataInventoryProductDetail['created_at']);
                        $inventoryInputDetail->editDetail($dataInventoryProductDetail,$checkInputDetail['inventory_input_detail_id'],$productCode);
                        $idInputDetail = $checkInputDetail['inventory_input_detail_id'];
                    }

                    if(isset($arrSerialInput[$productCode])){
                        $tmpSerialInput = [];
                        foreach($arrSerialInput[$productCode] as $serialInput){
                            $tmpSerialInput[] = [
                                'inventory_input_detail_id' => $idInputDetail,
                                'product_code' => $productCode,
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
            if (count($arrayOutput) != 0) {
                $dataAddOutput = [
                    'warehouse_id' => $warehouseId,
                    'po_code' => 'XK' . date("Y") . date("m") . date("d") . $rCode->generateServiceCardCode(""),
                    'created_by' => Auth::id(),
                    'created_at' => date('Y-m-d'),
                    'status' => $status,
                    'note' => '',
                    'type' => 'checking',
                    'inventory_checking_id' => $id
                ];
                $detailOutput = $inventoryOutput->getDetailByIdChecking($id);
                if ($detailOutput == null){
                    $idOutput = $inventoryOutput->add($dataAddOutput);
                    $inventoryOutput->edit(['po_code' => $rCode->codeDMY('XK', $idOutput)], $idOutput);
                } else {
                    $idOutput = $detailOutput['inventory_output_id'];
                }

                if ($idOutput > 0) {
                    foreach ($arrayOutput as $items) {
                        $product_code = $items[0];
                        $current_price = $items[1];
                        $unit_id = $items[2];
                        $quantityss = $items[3];

                        $detail = [
                            'inventory_output_id' => $idOutput,
                            'product_code' => $product_code,
                            'unit_id' => $unit_id,
                            'quantity' => $quantityss,
                            'current_price' => $current_price,
                            'total' => $quantityss * $current_price,
                            'created_by' => Auth::id(),
                            'created_at' => date('Y-m-d'),
                        ];

                        $checkOutputDetail = $inventoryOutputDetail->checkInventoryOutput($idOutput,$product_code);

                        if ($checkOutputDetail == null){
                            $idOutputDetail = $inventoryOutputDetail->add($detail);
                        } else {
                            unset($detail['created_by']);
                            unset($detail['created_at']);
                            $inventoryOutputDetail->editByOutIdAndProductCode($detail,$checkOutputDetail['inventory_output_id'],$product_code);
                            $idOutputDetail = $checkOutputDetail['inventory_output_detail_id'];
                        }

                        $mInventoryOutputDetailSerial->deleteSerialInput($idOutputDetail);

//                        Lấy danh sách serial trong kiểm kho

                        $listSerialExport = $mInventoryCheckingDetailSerial->getListSerialByCodeDetail($id,$product_code);
                        $dataTmpOutput['arrSerial'] = [];
                        $dataTmpOutput['warehouse_id'] = $warehouseId;
                        $dataTmpOutput['product_code'] = $product_code;
                        if(count($listSerialExport) != 0){
                            $dataTmpOutput['arrSerial'] = collect($listSerialExport)->pluck('serial');
                        }

                        $getListSerialProductInventory = $mProductInventorySerial->getListSerialExportInsert($dataTmpOutput);

                        $dataMainSerialOutput = [];
                        foreach ($getListSerialProductInventory as $itemSerialOutput){
                            $dataMainSerialOutput[] = [
                                'inventory_output_detail_id' => $idOutputDetail,
                                'product_code' => $product_code,
                                'serial' => $itemSerialOutput['serial'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        }

                        if(count($dataMainSerialOutput) != 0){
                            $mInventoryOutputDetailSerial->insertListSerial($dataMainSerialOutput);
                        }

                    }
                }
            }
            DB::commit();
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Lấy danh sách trạng thái checking
     * @return mixed|void
     */
    public function getListCheckingStatus()
    {
        $mInventoryCheckingStatus = app()->get(InventoryCheckingStatusTable::class);
        return $mInventoryCheckingStatus->getAll();
    }

    /**
     * Lấy danh sách sản phẩm
     * @param $data
     * @return mixed|void
     */
    public function getListProductInput($data)
    {
        try{
            $product = $this->getProductInfo($data);

            $view = view('admin::inventory-checking.append.block-list-product-main', [
                'inventoryCheckingDetail' => $product['inventoryCheckingDetail'],
                'listSerial' => $product['listSerial'],
                'listCheckingStatus' => $product['listCheckingStatus'],
                'unit' => $product['unit'],
                'data' => $product['data']
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

    public function getProductInfo($data){
        $rInventoryCheckingDetail = app()->get(InventoryCheckingDetailRepositoryInterface::class);
        $inventoryCheckingDetail = $rInventoryCheckingDetail->getDetailInventoryCheckingDetail($data['inventory_checking_id']);
        $mInventoryCheckingDetailSerial = app()->get(InventoryCheckingDetailSerialTable::class);
        $mUnit = app()->get(UnitRepositoryInterface::class);

        $listCheckingStatus = $this->getListCheckingStatus();

        $listSerial = [];
        $groupInputDetail = [];
        if (count($inventoryCheckingDetail) != 0){
            $groupInputDetail = collect($inventoryCheckingDetail)->pluck('inventory_checking_detail_id');
            $tmp = $mInventoryCheckingDetailSerial->getListSerialByDetailLimit($groupInputDetail);
            $listSerial = collect($tmp)->groupBy('inventory_checking_detail_id');
        }
        $unit = $mUnit->getUnitOption();

        return [
            'inventoryCheckingDetail' => $inventoryCheckingDetail,
            'listSerial' => $listSerial,
            'listCheckingStatus' => $listCheckingStatus,
            'unit' => $unit,
            'data' => $data
        ];
    }

    /**
     * Thêm serial
     * @param $data
     * @return mixed|void
     */
    public function addSerialProduct($data)
    {
        try{

            $mInventoryCheckingDetailSerial = app()->get(InventoryCheckingDetailSerialTable::class);
            $mInventoryCheckingDetail = app()->get(InventoryCheckingDetailTable::class);
            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $mInventoryInputDetail = app()->get(InventoryInputDetailTable::class);
            $mInventoryInput = app()->get(InventoryInputTable::class);
            $mInventoryCheckingStatus = app()->get(InventoryCheckingStatusTable::class);
            $mProductInvetorySerial = app()->get(ProductInventorySerialTable::class);
            $rCode = app()->get(CodeGeneratorRepositoryInterface::class);
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();
            $warehouseId = $data['warehouseId'];
            //todo: kiểm tra serial đã tồn tại hay chưa

            $checkingDetail = $mInventoryCheckingDetail->getDetail($data['inventory_checking_detail_id']);
            $idInventoryChecking = $checkingDetail['inventory_checking_id'];
            $getStatusId = $mInventoryCheckingStatus->getStatusByName($data['inventory_checking_status_id']);

            if ($getStatusId != null){
                $data['inventory_checking_status_id'] = $getStatusId['inventory_checking_status_id'];
            } else {
                $data['inventory_checking_status_id'] = $mInventoryCheckingStatus->addStatus([
                    'name' => $data['inventory_checking_status_id'],
                    'is_delete' => 0,
                    'is_active' => 1,
                    'is_default' => 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }

            $checkSerial = $mInventoryCheckingDetailSerial->checkSerial($data['product_code'],$data['serial'],$data['inventory_checking_detail_id']);

            if ($checkSerial == null){
                $checkSerial = $mInventoryInputDetailSerial->checkSerialChecking($data['warehouseId'],$data['product_code'],$data['serial']);
                unset($data['warehouseId']);
                $data['is_new'] = $checkSerial == null ? 1 : 0;
                $mInventoryCheckingDetailSerial->insertSerial($data);

                if ($checkSerial == null){
                    $detailInventoryInputDetail =  $mInventoryInputDetail->getDetailByCheckingId($checkingDetail['inventory_checking_id'],$data['product_code']);

                    if ($detailInventoryInputDetail != null){
                        $dataInsertInputSerial = [
                            'inventory_input_detail_id' => $detailInventoryInputDetail['inventory_input_detail_id'],
                            'product_code' => $data['product_code'],
                            'serial' => $data['serial'],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'is_export' => 0
                        ];
                        $mInventoryInputDetailSerial->insertListSerial($dataInsertInputSerial);

                        $detailInventoryInput = $mInventoryInputDetail->getDetail($detailInventoryInputDetail['inventory_input_detail_id']);
                        $updateInventoryInputDetail = [
                            'quantity' => (int)$detailInventoryInput['quantity'] + 1,
                            'quantity_recived' => (int)$detailInventoryInput['quantity_recived'] + 1,
                            'total' => (int)$detailInventoryInput['total'] * ((int)$detailInventoryInput['quantity'] + 1),
                        ];

                        $mInventoryInputDetail->editDetailById($updateInventoryInputDetail,$detailInventoryInputDetail['inventory_input_detail_id']);

                    } else {
                        $code = $code = 'NK' . date("Y") . date("m") . date("d") . $rCode->generateServiceCardCode("");
//                        Kiểm tra phiếu nhập kho đã được tạo hay chưa
                        $detailInventoryInputDetailCheck = $mInventoryInput->getDetailByIdChecking($idInventoryChecking);
                        if ($detailInventoryInputDetailCheck == null){
                            $dataInventoryProduct = [
                                'warehouse_id' => $warehouseId,
                                'supplier_id' => '',
                                'pi_code' => $code,
                                'created_by' => Auth::id(),
                                'status' => 'draft',
                                'created_at' => Carbon::now(),
                                'type' => 'checking',
                                'inventory_checking_id' => $idInventoryChecking
                            ];
                            $idInventoryInput = $mInventoryInput->add($dataInventoryProduct);
                            $mInventoryInput->edit(['pi_code' => $rCode->codeDMY('NK', $idInventoryInput)], $idInventoryInput);
                        } else {
                            $idInventoryInput = $detailInventoryInputDetailCheck['inventory_input_id'];
                        }

                        $mProductChild = app()->get(ProductChildTable::class);

//                        kiểm tra chi tiết nhập kho theo mã sản phẩm đã được tạo
                        $dataInventoryProductDetailCheck = $mInventoryInputDetail->checkInventoryInput($idInventoryInput,$data['product_code']);
                        if ($dataInventoryProductDetailCheck == null){
                            $detailProductChild = $mProductChild->getProductChildByCode($data['product_code']);
                            $dataInventoryProductDetail = [
                                'inventory_input_id' => $idInventoryInput,
                                'product_code' => $data['product_code'],
                                'unit_id' => $detailProductChild['unit_id'],
                                'quantity' => 1,
                                'current_price' => $detailProductChild['price'],
                                'quantity_recived' => 1,
                                'total' => $detailProductChild['price'],
                                'created_by' => Auth::id(),
                                'created_at' => Carbon::now()
                            ];

                            $idInventoryInputDetail = $mInventoryInputDetail->add($dataInventoryProductDetail);
                        } else {
                            $dataInventoryProductDetail = [
                                'quantity' => (int)$dataInventoryProductDetailCheck['quantity'] + 1,
                                'quantity_recived' => (int)$dataInventoryProductDetailCheck['quantity_recived'] + 1,
                                'total' => (double)$dataInventoryProductDetailCheck['current_price'] * ($dataInventoryProductDetailCheck['quantity'] + 1),
                                'created_by' => Auth::id(),
                                'created_at' => Carbon::now()
                            ];

                            $mInventoryInputDetail->editDetailById($dataInventoryProductDetail,$dataInventoryProductDetailCheck['inventory_input_detail_id']);
                            $idInventoryInputDetail = $dataInventoryProductDetailCheck['inventory_input_detail_id'];
                        }


                        $dataInsertInputSerial = [
                            'inventory_input_detail_id' => $idInventoryInputDetail,
                            'product_code' => $data['product_code'],
                            'serial' => $data['serial'],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'is_export' => 0
                        ];
                        $mInventoryInputDetailSerial->insertListSerial($dataInsertInputSerial);
                    }
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
                'message' => __('Thêm serial thất bại'),
                '__message' => $e->getMessage()
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
            $mInventoryCheckingDetailSerial = app()->get(InventoryCheckingDetailSerialTable::class);
            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $mInventoryCheckingDetailSerial->deleteSerialById($data['inventory_checking_detail_serial_id']);

            $mInventoryInputDetailSerial->removeSerialByChecking($data['productCode'],$data['inventory_checking_id'],$data['serial']);
            $mInventoryOutputDetailSerial->removeSerialByChecking($data['productCode'],$data['inventory_checking_id'],$data['serial']);

            return [
                'error' => false,
                'message' => __('Xoá serial thành công')
            ];

        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Xoá serial thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách serial theo kho
     * @param $data
     * @return mixed|void
     */
    public function getListProductByWarehouse($warehouseId)
    {
//        $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
//        return $mInventoryInputDetailSerial->getProductChildInventoryOutputNotPage(['warehouse_id' => $warehouseId]);

        $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
        return $mProductInventorySerial->getProductChildInventoryOutputNotPage(['warehouse_id' => $warehouseId]);
    }

    /**
     * Xuất file dữ liệu
     */
    public function exportCheckingList($data){
        $productInfo = $this->getProductInfo($data);
        $inventoryCheckingDetail = $productInfo['inventoryCheckingDetail'];
        $listSerial = $productInfo['listSerial'];
        $dataSerial = [];
        $tmpKey = 0;
        foreach($inventoryCheckingDetail as $item){
            if($item['inventory_management'] == 'serial'){
                foreach($listSerial as $keyListStatus => $itemListStatus){
                    foreach(collect($itemListStatus)->groupBy('inventory_checking_status_name') as $key => $itemStatus){
                        if($item['inventory_checking_detail_id'] == $keyListStatus){
                            $serial = collect($itemStatus)->pluck('serial')->toArray();
                            $serial = implode(',',$serial);
                            $tmpKey++;
                            $dataSerial[$tmpKey] = [
                                $item['productCode'],
                                $serial,
                                $item['quantityOld'],
                                count($itemStatus),
                                $itemStatus[0]['inventory_checking_status_name']
                            ];
                        }
                    }

                }
            } else {
                $dataSerial[$tmpKey] = [
                    $item['productCode'],
                    '',
                    $item['quantityOld'],
                    $item['quantityNew'],
                    ''
                ];
            }
        }
        $dataSerial = array_values($dataSerial);

        $header = [
            __('MÃ SẢN PHẨM'),
            __('SỐ SERIAL'),
            __('HỆ THỐNG'),
            __('THỰC TẾ'),
            __('TRẠNG THÁI'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return Excel::download(new ExportFile($header, $dataSerial), 'Kiểm kho.xlsx');
    }

    /**
     * Thêm sản phẩm kiểm kho
     * @param $data
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function submitAddProductAction($param)
    {
        $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
        $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
        $mInventoryCheckingStatus = app()->get(InventoryCheckingStatusTable::class);
        $mInventoryCheckingDetailSerial = app()->get(InventoryCheckingDetailSerialTable::class);
        $mInventoryCheckingDetail = app()->get(InventoryCheckingDetailTable::class);
        $mInventoryInput = app()->get(InventoryInputTable::class);
        $mInventoryInputDetail = app()->get(InventoryInputDetailTable::class);
        $mInventoryOutput = app()->get(InventoryOutputTable::class);
        $rCode = app()->get(CodeGeneratorRepositoryInterface::class);
        $mInventoryOutputDetail = app()->get(InventoryOutputDetailTable::class);
        $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
        $createdAt = Carbon::now();
        $warehouseId = $param['warehouse_id'];
        $status = $param['status_detail'];
//        $arrayProducts = array_chunk($request->arrayProducts, 6, false);
        $arrayProducts = [];
        $code = 'NK' . date("Y") . date("m") . date("d") . $rCode->generateServiceCardCode("");
        $codeInventoryOutput = 'XK' . date("Y") . date("m") . date("d") . $rCode->generateServiceCardCode("");
        $arrSerialInput = [];
        $arrSerialOutput = [];
        try {
            DB::beginTransaction();

            $dataError = [];
            $messageExcel = '';

            if (isset($param['file']) && $param['file'] != 'undefined'){
                $dataExcel = $this->getValueExcelInventoryInput($param['file'],$param);

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
            $idInventoryChecking = $param['inventory_checking_id'];

            $arrayInventoryInputDetail = [];
            $arrayInventoryOutputDetail = [];
            //Add inventory checking detail.

            $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);

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

                $checkCodeDetail = $mInventoryCheckingDetail->getDetailChecking($idInventoryChecking, $productCode);
                if ($checkCodeDetail == null) {
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

                    $idCheckingDetail = $mInventoryCheckingDetail->add($dataAddInventoryCheckingDetail);

                    if ($value['inventory_management'] == 'serial') {
                        foreach ($value['serial'] as $valueSerial) {
                            $checkStatus = $mInventoryCheckingStatus->getStatusByName($valueSerial['status_name']);

                            if ($checkStatus == null) {
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

                            $serial = isset($valueSerial['list']) && $valueSerial['list'] != '' && $valueSerial['list'] != null ? explode(',', $valueSerial['list']) : [];

                            $dataSerial = [];
                            foreach ($serial as $itemSerial) {
                                $checkSerial = '';
                                //                            if($typeResolve == 'input' || $typeResolve == 'output'){
                                $checkSerial = $mInventoryInputDetailSerial->checkSerialCheckingImport($warehouseId, $productCode, trim(strip_tags($itemSerial)));
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

                                if ($checkSerial == null) {
                                    if ($typeResolve1 == 'input') {
                                        $arrSerialInput[$productCode][] = [
                                            'product_code' => $productCode,
                                            'serial' => trim(strip_tags($itemSerial)),
                                        ];
                                    }

                                    if ($typeResolve2 == 'output') {
                                        $arrSerialOutput[$productCode][] = [
                                            'product_code' => $productCode,
                                            'serial' => trim(strip_tags($itemSerial)),
                                        ];
                                    }
                                }
                            }

                            if (count($serial) != 0) {
                                $listSerialExport = $mProductInventorySerial->getListSerialByProductWarehouse($warehouseId, $productCode, $serial);
                                foreach ($listSerialExport as $itemSerialExport) {
                                    $arrSerialOutput[$productCode][] = [
                                        'product_code' => $productCode,
                                        'serial' => trim(strip_tags($itemSerialExport['serial'])),
                                    ];
                                }

                                if (count($listSerialExport) != 0) {
                                    $arrayInventoryOutputDetail[] = [$productCode, $cost, $unit, abs(count($listSerialExport))];
                                }
                            }

                            if (count($dataSerial) != 0) {
                                $mInventoryCheckingDetailSerial->addSerial($dataSerial);
                            }
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
                $idInventoryInput = $mInventoryInput->add($dataInventoryProduct);
                $mInventoryInput->edit(['pi_code' => $rCode->codeDMY('NK', $idInventoryInput)], $idInventoryInput);

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
                    $idInventoryInputDetail = $mInventoryInputDetail->add($dataInventoryProductDetail);
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
                $idInventoryOutput = $mInventoryOutput->add($dataInventoryOutput);
                $mInventoryOutput->edit(['po_code' => $rCode->codeDMY('XK', $idInventoryOutput)], $idInventoryOutput);

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
                        $idInventoryOutputDetail = $mInventoryOutputDetail->add($detail);

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
            DB::commit();
            return [
                'error'=> false,
                'message' => $messageExcel != '' ? $messageExcel : __('Thêm phiếu nhập thành công'),
                'id' => $idInventoryChecking,
                'dataError' => $dataError,
                'countError' => count($dataError)
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return ['error'=> true, 'message' => $e->getMessage()];
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
            $view = view('admin::inventory-checking.popup.popup-inventory-add-product',[
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
     * Xoá sản phẩm
     * @param $data
     * @return mixed|void
     */
    public function removeProductInline($data)
    {
        try {

            $mInventoryCheckingDetailSerial = app()->get(InventoryCheckingDetailSerialTable::class);
            $mInventoryCheckingDetail = app()->get(InventoryCheckingDetailTable::class);
            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);
            $mInventoryInputDetail = app()->get(InventoryInputDetailTable::class);
            $mInventoryOutputDetail = app()->get(InventoryOutputDetailTable::class);

            $mInventoryCheckingDetailSerial->removeSerialByDetail($data['inventory_checking_detail_id']);
            $mInventoryCheckingDetail->removeDetail($data['inventory_checking_detail_id']);

            $mInventoryInputDetailSerial->removeProductByChecking($data['productCode'],$data['inventory_checking_id']);
            $mInventoryInputDetail->removeProductByChecking($data['productCode'],$data['inventory_checking_id']);

            $mInventoryOutputDetailSerial->removeProductByChecking($data['productCode'],$data['inventory_checking_id']);
            $mInventoryOutputDetail->removeProductByChecking($data['productCode'],$data['inventory_checking_id']);


            return [
                'error' => false,
                'message' => __('Xoá sản phẩm thành công')
            ];
        } catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Xoá sản phẩm thất bại')
            ];
        }
    }

    /**
     * Hiển thị popup serial theo sản phẩm xuất hoặc nhập kho
     * @param $data
     * @return mixed|void
     */
    public function showPopupSerialProduct($data)
    {
        try {

            $mInventoryCheckingDetail = app()->get(InventoryCheckingDetailTable::class);
            $mInventoryCheckingStatus = app()->get(InventoryCheckingStatusTable::class);
            $mInventoryCheckingDetailSerial = app()->get(InventoryCheckingDetailSerialTable::class);
            $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);

            $detailProduct = $mInventoryCheckingDetail->getDetail($data['inventory_checking_detail_id']);

            $getListStatus = $mInventoryCheckingStatus->getAll();

            if ($data['type_list'] == 'export'){
                if ($data['type'] == 'edit'){
                    $listSerialExport = $mInventoryCheckingDetailSerial->getListSerialExport($data['inventory_checking_detail_id']);
                    $data['arrSerial'] = [];
                    if (count($listSerialExport) != 0){
                        $data['arrSerial'] = collect($listSerialExport)->pluck('serial');
                    }
                    $listSerial = $mProductInventorySerial->getListSerialExport($data);
                } else {
                    $listSerial = $mInventoryOutputDetailSerial->getListSerialExport($data);
                }

            } else {
                $listSerial = $mInventoryCheckingDetailSerial->getListSerialImport($data);
            }

            $view = view('admin::inventory-checking.popup.popup-list-serial-product',[
                'detailProduct' => $detailProduct,
                'listStatus' => $getListStatus,
                'listSerial' => $listSerial,
                'type' => $data['type'],
                'data' => $data,
                'inventory_checking_detail_id' => $data['inventory_checking_detail_id']
            ])->render();

            return [
                'error'=> false,
                'view'=> $view
            ];
        } catch (\Exception $e){
            return [
                'error'=> true,
                'message'=> __('Thất bại')
            ];
        }
    }

    /**
     * Lấy danh sách serial
     * @param $data
     * @return mixed|void
     */
    public function getListSerialProduct($data)
    {
        try {

            $mInventoryCheckingDetail = app()->get(InventoryCheckingDetailTable::class);
            $mInventoryCheckingStatus = app()->get(InventoryCheckingStatusTable::class);
            $mInventoryCheckingDetailSerial = app()->get(InventoryCheckingDetailSerialTable::class);
            $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
            $mInventoryOutputDetailSerial = app()->get(InventoryOutputDetailSerialTable::class);

            if ($data['type_list'] == 'export'){
                if ($data['type'] == 'edit'){
                    $listSerialExport = $mInventoryCheckingDetailSerial->getListSerialExport($data['inventory_checking_detail_id']);
                    $data['arrSerial'] = [];
                    if (count($listSerialExport) != 0){
                        $data['arrSerial'] = collect($listSerialExport)->pluck('serial');
                    }
                    $listSerial = $mProductInventorySerial->getListSerialExport($data);
                } else {
                    $listSerial = $mInventoryOutputDetailSerial->getListSerialExport($data);
                }
            } else {
                $listSerial = $mInventoryCheckingDetailSerial->getListSerialImport($data);
            }

            $view = view('admin::inventory-checking.append.list-serial-product',[
                'listSerial' => $listSerial,
                'type' => $data['type'],
                'data' => $data,
                'inventory_checking_detail_id' => $data['inventory_checking_detail_id']
            ])->render();

            return [
                'error'=> false,
                'view'=> $view
            ];
        } catch (\Exception $e){
            return [
                'error'=> true,
                'message'=> __('Thất bại')
            ];
        }
    }

    /**
     * Kiểm tra trước khi submit kiểm kho
     * @param $data
     * @return mixed|void
     */
    public function submitEditCheck($data)
    {
        $mInventoryCheckingDetail = app()->get(InventoryCheckingDetailTable::class);

        $listProductChecking = $mInventoryCheckingDetail->getListProductChecking($data['id']);
        $messageError = '';
        foreach($listProductChecking as $item){
            if ($item['quantity_old'] != 0 && abs($item['quantity_new'])/abs($item['quantity_old'])*100 < 40) {
                $messageError = $messageError.__('Số lượng sản phẩm :product_name chênh lệch 40% so với hệ thống.',['product_name' => $item['product_child_name']]).'<br>';
            }
        }

        if ($messageError == ''){
            return [
                'error' => false
            ];
        } else {
            return [
                'error' => true,
                'message' => $messageError,
                'message_confirm' => __('Bạn có muốn tiếp tục lưu thông tin')
            ];
        }

    }

    /**
     * Insert log checking
     * @param $data
     * @return mixed|void
     */
    public function insertLogChecking($data,$id,$reason)
    {
        try {
            $totalAdd = 0;
            $totalMinus = 0;
            foreach ($data as $value){
                if ($value['inventory_management'] != 'serial'){
                    if ($value['quantityOld']-$value['quantityNew']>0){
                        $totalMinus = $totalMinus + abs($value['quantityOld'] - $value['quantityNew']);
                    } elseif($value['typeResolve']=="input") {
                        $totalAdd = $totalAdd + abs($value['quantityOld'] - $value['quantityNew']);
                    }
                } else {
                    if ($value['quantityOld'] != $value['quantityNew'] || $value['total_import'] != 0){
//                        if ($value['total_export'] != 0 || $value['quantityOld'] - $value['total_export'] > 0){
                        if ($value['total_export'] != 0){
//                            $totalMinus = $totalMinus + abs($value['quantityOld'] - $value['total_export']);
                            $totalMinus = $totalMinus + abs($value['total_export']);
                        }
                        if ($value['total_import'] != 0){
                            $totalAdd = $totalAdd + $value['total_import'];
                        }
                    }
                }
            }

            $mInventoryCheckingLog = app()->get(InventoryCheckingLogTable::class);

            $message = __('Thực hiện kiểm kho').'<br>';
            if ($totalAdd != 0){
                $message = $message.__('Nhập kho: ').$totalAdd.'<br>';
            }
            if ($totalMinus != 0){
                $message = $message.__('Xuất kho: ').$totalMinus;
            }

            $mInventoryCheckingLog->insertLog([
                'inventory_checking_id' => $id,
                'staff_id' => Auth::id(),
                'content' => $message,
                'reason' => $reason,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id()
            ]);


        }catch(\Exception $e){

        }
    }

    /**
     * lấy danh sách log có phân trang
     * @param array $filter
     * @return mixed|void
     */
    public function getListLog($filter = [])
    {
        $mInventoryCheckingLog = app()->get(InventoryCheckingLogTable::class);
        $data = $mInventoryCheckingLog->getListLog($filter);
        if (!isset($filter['page'])){
            return $data;
        } else {
            $view = view('admin::inventory-checking.append.table-log',['listLog' => $data])->render();
            return [
                'error' => false,
                'view' => $view
            ];
        }
    }

}