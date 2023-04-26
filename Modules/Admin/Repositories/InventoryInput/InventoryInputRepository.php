<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/12/2018
 * Time: 9:44 AM
 */

namespace Modules\Admin\Repositories\InventoryInput;

use App\Exports\ExportFile;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\InventoryInputDetailSerialTable;
use Modules\Admin\Models\InventoryInputDetailTable;
use Modules\Admin\Models\InventoryInputTable;
use Modules\Admin\Models\ProductChildTable;
use Modules\Admin\Models\ProductInventorySerialTable;
use Modules\Admin\Models\UnitTable;
use Modules\Admin\Repositories\InventoryInputDetail\InventoryInputDetailRepositoryInterface;
use Modules\Admin\Repositories\Unit\UnitRepositoryInterface;

class InventoryInputRepository implements InventoryInputRepositoryInterface
{
    protected $inventoryInput;
    protected $timestamps = true;

    public function __construct(InventoryInputTable $inventoryInput)
    {
        $this->inventoryInput = $inventoryInput;
    }
    /**
     * add inventory input.
     */
    public function add(array $data)
    {
        return $this->inventoryInput->add($data);
    }
    /**
     *get list inventory input
     */
    public function list(array $filters = [])
    {
        return $this->inventoryInput->getList2($filters);
    }
    public function list2($filters)
    {
        return $this->inventoryInput->getList1($filters);
    }
    /**
     * delete inventory input
     */
    public function remove($id)
    {
//        Xoá phiếu
        $this->inventoryInput->remove($id);
    }
    /*
     * edit inventory input
     */
    public function edit(array $data, $id)
    {
        return $this->inventoryInput->edit($data, $id);
    }

    /**
     * Cập nhật theo id kiểm kho
     * @param array $data
     * @param $id
     * @return mixed|void
     */
    public function editByChecking(array $data, $id)
    {
        return $this->inventoryInput->editByCheckingId($data, $id);
    }

    /*
     *  get inventory input
     */
    public function getItem($id)
    {
        return $this->inventoryInput->getItem($id);
    }
    /*
     * detail inventory input
     */
    public function detail($id){
        return $this->inventoryInput->detail($id);
    }

    /**
     * Show popup thêm phiếu nhập kho
     * @param $wareHouse
     * @param $supplier
     * @param $user
     * @param $code
     * @param $product
     * @return mixed|void
     */
    public function showPopupAddInventory($wareHouse, $supplier, $user, $code, $product)
    {
        try{
            $view = view('admin::inventory-input.popup.popup-inventory-add',[
                'wareHouse' => $wareHouse,
                'supplier' => $supplier,
                'user' => $user,
                'code' => $code,
                'product' => $product
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
     * lấy danh sách sản phẩm từ file excel
     * @param $file
     * @return mixed|void
     */
    public function getValueExcelInventoryInput($file)
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
                $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);

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

                                                $checkSerialInventoryProduct = $mProductInventorySerial->checkSeialInventoryProduct(array_map('trim', $tmpSerial));
                                                if (count($checkSerialInventoryProduct) != 0){
                                                    $checkSerialInventoryProduct = collect($checkSerialInventoryProduct)->pluck('serial')->toArray();
                                                    $resultArray = array_intersect(array_map('trim', $tmpSerial),$checkSerialInventoryProduct);
                                                    if(count($resultArray) != 0){
                                                        foreach($tmpSerial as $keySerialValue => $itemSerialValue){
                                                            if (in_array($itemSerialValue, $resultArray)) {
                                                                unset($tmpSerial[$keySerialValue]);
                                                            }
                                                        }
                                                        $numberError++;
                                                        $tmp['error_message'] = implode(',',$resultArray).__(' không thể thêm');
                                                        $arrError[] = $tmp;
                                                    }
                                                }

                                                $tmp['serial'] = implode(',',array_unique($tmpSerial));
                                                if (isset($arrSuccess[$tmp['product_code']])){

                                                    $tmp['serial'] = array_merge(explode(',',$tmp['serial']),explode(',',$arrSuccess[$tmp['product_code']]['serial']));
                                                    $tmp['serial'] = implode(',',array_unique($tmp['serial']));
                                                }
                                                $tmp['quantity'] = count($tmpSerial);
                                            }

                                            $tmp['price'] = isset($tmp['price']) ? $tmp['price'] : $testCodeProduct['quantity'];
                                            $numberSuccess++;
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

    /**
     * Hiển thị popup insert sản phẩm nhập kho
     * @param $data
     * @return mixed|void
     */
    public function showPopupAddProductAction($data)
    {
        try{
            $view = view('admin::inventory-input.popup.popup-inventory-add-product',[
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

    public function submitAddProductAction($data)
    {
        try {
            $idInventoryInput = $data['inventory_input_id'];
            $mInventoryInpurDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $rInventoryInputDetail = app()->get(InventoryInputDetailRepositoryInterface::class);
            $dataExcel = $this->getValueExcelInventoryInput($data['file']);

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
                    'created_at' => Carbon::now()
                ];
                $dataSerial = [];
                $checkIdInventoryInput = $rInventoryInputDetail->checkIdInventoryInput($idInventoryInput,$productCode);
                if($checkIdInventoryInput == null) {
                    $idInventoryInputDetail = $rInventoryInputDetail->add($dataInventoryProductDetail);
                } else {
                    $idInventoryInputDetail = $checkIdInventoryInput['inventory_input_detail_id'];
                }
                if ($inventory_management == 'serial'){
                    foreach($serial as $keySerial => $itemSerial){
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
            }

            return [
                'error'=> false,
                'message' => $messageExcel != '' ? $messageExcel : __('Nhập dữ liệu thành công'),
                'id' => $idInventoryInput,
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
     * Xoá sản phẩm + serial ở chỉnh sửa nhập kho
     * @param $data
     * @return mixed|void
     */
    public function deleteProduct($data)
    {
        try {

            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $mInventoryInputDetail = app()->get(InventoryInputDetailTable::class);

            $mInventoryInputDetailSerial->deleteSerialInput($data['inventory_input_detail_id']);
            $mInventoryInputDetail->deleteDetailInput($data['inventory_input_detail_id']);

            return [
                'error'=> false,
                'message'=> __('Xoá sản phẩm nhập kho thành công')
            ];
        }catch (\Exception $e){
            return [
                'error'=> true,
                'message'=> __('Xoá sản phẩm nhập kho thất bại')
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

            $mInventoryInputDetail = app()->get(InventoryInputDetailTable::class);

            $detailProduct = $mInventoryInputDetail->getDetail($data['inventory_input_detail_id']);

            $view = view('admin::inventory-input.popup.popup-list-serial',[
                'detailProduct' => $detailProduct,
                'inventory_input_detail_id' => $data['inventory_input_detail_id']
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

            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $type = $data['type'];
            unset($data['type']);
            $listSerial = $mInventoryInputDetailSerial->getListSerialPaging($data);

            $view = view('admin::inventory-input.append.list-serial',[
                'listSerial' => $listSerial,
                'inventory_input_detail_id' => $data['inventory_input_detail_id'],
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
     * Lưu sản phẩm ở chỉnh sửa sản phẩm nhập kho
     * @param $data
     * @return mixed|void
     */
    public function submitEditProduct($data)
    {
        try {
            $mInventoryInputDetail = app()->get(InventoryInputDetailTable::class);
            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);

            $arrayProducts = $data['arrayProducts'];
            $id = $data['id'];
            $inventoryInputDetailExists = [];
            $inventoryInputDetail = $mInventoryInputDetail->getInventoryInputDetailByParentId($id);
            foreach ($inventoryInputDetail as $key => $value) {
                $inventoryInputDetailExists[] = $value['code'];
            }
            $arrayProductsChilds = array_chunk($arrayProducts, 6, false);

            foreach ($arrayProductsChilds as $k => $v) {
                $productCode = $v[0];
                $unitId = $v[1];
                $quantity = $v[2];
                $currentPrice = str_replace(",", "", $v[3]);
                $quantityRecived = $v[4];
                $total = str_replace(",", "", $v[5]);
                $inventoryInputDetailAjax[] = $productCode;
                if (!in_array($v[0], $inventoryInputDetailExists)) {
                    $dataInventoryProductDetail = [
                        'inventory_input_id' => $id,
                        'product_code' => $productCode,
                        'unit_id' => $unitId,
                        'quantity' => $quantity,
                        'current_price' => $currentPrice,
                        'quantity_recived' => $quantityRecived,
                        'total' => $total,
                        'created_by' => Auth::id(),
                        'created_at' => Carbon::now()
                    ];
                    $mInventoryInputDetail->add($dataInventoryProductDetail);
                } else {
                    $mProductChild = app()->get(ProductChildTable::class);
                    $getInfoProductChild = $mProductChild->testProductCode($productCode);

                    if($getInfoProductChild['inventory_management'] == 'serial'){
                        $quantityRecived = $mInventoryInputDetailSerial->getTotalSerial($id,$productCode);
                        $total = $currentPrice * $quantityRecived;
                    }

                    $dataInventoryProductDetail = [
                        'inventory_input_id' => $id,
                        'product_code' => $productCode,
                        'unit_id' => $unitId,
                        'quantity' => $quantityRecived,
                        'current_price' => $currentPrice,
                        'quantity_recived' => $quantityRecived,
                        'total' => $total,
                        'updated_by' => Auth::id(),
                        'updated_at' => Carbon::now()
                    ];
                    $mInventoryInputDetail->editByInputIdAndProductCode($dataInventoryProductDetail, $id, $v[0]);
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
     * Xoá serial sản phẩm chi tiết
     * @param $data
     * @return mixed|void
     */
    public function removeSerial($data)
    {
        try{
            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $mInventoryInputDetailSerial->deleteSerialById($data['inventory_input_detail_serial_id']);

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
     * Lấy danh sách sản phẩm
     * @param $data
     * @return mixed|void
     */
    public function getListProductInput($data)
    {
        try{

            $inventoryInputDetail = app()->get(InventoryInputDetailTable::class);
            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $unit = app()->get(UnitRepositoryInterface::class);

            $listSerial = [];
            $groupInputDetail = [];

            $inventoryInputDetailData = $inventoryInputDetail->getInventoryInputDetailByParentId($data['inventory_input_id']);

            $groupInputDetail = collect($inventoryInputDetailData)->pluck('inventory_input_detail_id');
            $tmp = $mInventoryInputDetailSerial->getListSerialByDetail($groupInputDetail);
            $listSerial = collect($tmp)->groupBy('inventory_input_detail_id');

            $unit = $unit->getUnitOption();
            $arrayQuantity = [];
            $arrayTotal = [];
            foreach ($inventoryInputDetailData as $key => $value) {
                $arrayQuantity[] = $value['quantity'];
                $arrayTotal[] = $value['total'];
            }

            $view = view('admin::inventory-input.append.block-list-product-main', [
                'inventoryInputDetail' => $inventoryInputDetailData,
                'unit' => $unit,
                'sumQuantity' => array_sum($arrayQuantity),
                'sumTotal' => array_sum($arrayTotal),
                'listSerial' => $listSerial,
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];

        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Lấy danh sách thất bại')
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
            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);

            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();

            //todo: kiểm tra serial đã tồn tại hay chưa
            $data['serial'] = trim($data['serial']);
            $checkSerial = $mInventoryInputDetailSerial->checkSerial($data['product_code'],$data['serial'],$data['inventory_input_detail_id']);

            if ($checkSerial == null){

                $checkSerialInventory = $mProductInventorySerial->checkSeialInventoryProduct([$data['serial']]);
                if (count($checkSerialInventory) == 0){
                    $mInventoryInputDetailSerial->insertListSerial($data);
                } else {
                    return [
                        'error' => true,
                        'message' => __('Số serial không thể thêm vào kho')
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
            $mInventoryInputDetailSerial = app()->get(InventoryInputDetailSerialTable::class);
            $mInventoryInputDetail = app()->get(InventoryInputDetailTable::class);
            $listSerial = [];
            $groupInputDetail = [];

            $detail = $mInventoryInputDetail->getDetail($data['inventory_input_detail_id']);

            $tmp = $mInventoryInputDetailSerial->getListSerialByDetail([$data['inventory_input_detail_id']]);
            $listSerial = collect($tmp)->groupBy('inventory_input_detail_id');

            $view = view('admin::inventory-input.append.block_tr_detail', [
                'listSerial' => $listSerial,
                'code' => $detail['product_code'],
                'inventory_input_detail_id' => $data['inventory_input_detail_id']
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
}