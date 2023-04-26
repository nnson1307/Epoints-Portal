<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 19/05/2021
 * Time: 11:29
 */

namespace Modules\Report\Repository\ProductInventory;


use App\Exports\ExportFile;
use App\Exports\ExportInventory;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\InventoryInputDetailTable;
use Modules\Report\Models\InventoryOutputDetailTable;
use Modules\Report\Models\ProductChildTable;
use Modules\Report\Models\ProductInventoryLogTable;
use Modules\Report\Models\ProductInventoryTable;
use Modules\Report\Models\WarehouseTable;

class ProductInventoryRepo implements ProductInventoryRepoInterface
{

    /**
     * Lấy data view báo cáo tồn kho
     *
     * @return mixed|void
     */
    public function dataViewIndex()
    {
        $mWarehouse = app()->get(WarehouseTable::class);

        //Lấy danh sách kho
        $getWarehouse = $mWarehouse->optionWarehouse();

        return [
            'optionWarehouse' => $getWarehouse
        ];
    }

    /**
     * Danh sách tồn kho
     *
     * @param array $filter
     * @return mixed|void
     */
    public function list(array $filter = [])
    {
        $mProductInventory = app()->get(ProductInventoryTable::class);
        $mWarehouse = app()->get(WarehouseTable::class);

        $startTime = null;
        $endTime = null;

        if (isset($filter["created_at"]) != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
        }

        //Lấy ds kho
        $getWarehouse = $mWarehouse->getWarehouse($filter['warehouse_id']);

        //Lấy ds sản phẩm tồn kho
        $getProduct = $mProductInventory->getList([
            'page' => isset($filter['page']) ? $filter['page'] : 1,
            'product_id' => $filter['product_id']
        ]);

        if (count($getProduct->items()) > 0) {
            $mInventoryLog = app()->get(ProductInventoryLogTable::class);
            $mInputDetail = app()->get(InventoryInputDetailTable::class);
            $mOutputDetail = app()->get(InventoryOutputDetailTable::class);

            foreach ($getProduct->items() as $v) {
                $dataWarehouse = [];

                $allBeginInventory = 0;
                $allBeginInventoryValue = 0;
                $allInput = 0;
                $allInputValue = 0;
                $allOutput = 0;
                $allOutputValue = 0;
                $allInventory = 0;
                $allInventoryValue = 0;
                //Lấy tồn đầu kỳ, nhập, xuất, tồn theo kho
                if (count($getWarehouse) > 0) {
                    foreach ($getWarehouse as $wh) {
                        //Lấy tồn đầu kỳ theo ngày bắt đầu
                        $getLog = $mInventoryLog->getInventoryLog($v['product_code'], $wh['warehouse_id'], $startTime);
                        //Lấy số lượng nhập kho (từ ngày -> ngày)
                        $getImport = $mInputDetail->getInputToDate(
                            $v['product_code'],
                            $wh['warehouse_id'],
                            $startTime. ' '. '00:00:00',
                            $endTime. ' '. '23:59:59'
                        );
                        //Lấy số lượng xuất kho (từ ngày -> ngày)
                        $getExport = $mOutputDetail->getOutputToDate(
                            $v['product_code'],
                            $wh['warehouse_id'],
                            $startTime. ' '. '00:00:00',
                            $endTime. ' '. '23:59:59'
                        );

                        $beginInventory = $getLog != null ? $getLog['inventory'] : 0;
                        $beginInventoryValue = $getLog != null ? $getLog['inventory_value'] : 0;
                        $totalInput = $getImport != null ? $getImport['quantity'] : 0;
                        $totalInputValue = $getImport != null ? $getImport['total'] : 0;
                        $totalOutput = $getExport != null ? $getExport['quantity'] : 0;
                        $totalOutputValue = $getExport != null ? $getExport['total'] : 0;
                        $inventory = $beginInventory + ($totalInput - $totalOutput);
                        $price = $inventory * $v['cost'];

                        //Lấy tổng tồn đầu kỳ + giá trị
                        $allBeginInventory += $beginInventory;
                        $allBeginInventoryValue += $beginInventoryValue;
                        //Lấy tổng nhập + giá trị
                        $allInput += $totalInput;
                        $allInputValue += $totalInputValue;
                        //Lấy tổng xuất + giá trị
                        $allOutput += $totalOutput;
                        $allOutputValue += $totalOutputValue;
                        //Lấy tổng tồn + giá trị
                        $allInventory += $inventory;
                        $allInventoryValue += $price;

                        $dataWarehouse [] = [
                            'begin_inventory' => $beginInventory,
                            'begin_inventory_value' => $beginInventoryValue,
                            'total_input' => $totalInput,
                            'total_input_value' => $totalInputValue,
                            'total_output' => $totalOutput,
                            'total_output_value' => $totalOutputValue,
                            'inventory' => $inventory,
                            'price' => $price
                        ];
                    }
                }

                $v['data_warehouse'] = $dataWarehouse;
                $v['allBeginInventory'] = $allBeginInventory;
                $v['allBeginInventoryValue'] = $allBeginInventoryValue;
                $v['allInput'] = $allInput;
                $v['allInputValue'] = $allInputValue;
                $v['allOutput'] = $allOutput;
                $v['allOutputValue'] = $allOutputValue;
                $v['allInventory'] = $allInventory;
                $v['allInventoryValue'] = $allInventoryValue;
            }
        }

        return [
            'optionWarehouse' => $getWarehouse,
            'list' => $getProduct
        ];
    }

    /**
     * Export chi tiết tồn kho
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelDetail($input)
    {
        $mProductInventory = app()->get(ProductInventoryTable::class);
        $mWarehouse = app()->get(WarehouseTable::class);

        $startTime = null;
        $endTime = null;

        if (isset($input["created_at"]) != "") {
            $arr_filter = explode(" - ", $input["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
        }

        $warehouseName = __('Tất cả');

        //Lấy ds kho
        $getWarehouse = $mWarehouse->getWarehouse($input['warehouse_id']);

        if ($input['warehouse_id'] != null) {
            $warehouseName = isset($getWarehouse[0]['name']) ? $getWarehouse[0]['name'] : '';
        }

        //Lấy ds sản phẩm tồn kho
        $getProduct = $mProductInventory->getListProductInventory();

        if (count($getProduct) > 0) {
            $mInventoryLog = app()->get(ProductInventoryLogTable::class);
            $mInputDetail = app()->get(InventoryInputDetailTable::class);
            $mOutputDetail = app()->get(InventoryOutputDetailTable::class);

            foreach ($getProduct as $v) {
                $dataWarehouse = [];

                $allBeginInventory = 0;
                $allBeginInventoryValue = 0;
                $allInput = 0;
                $allInputValue = 0;
                $allOutput = 0;
                $allOutputValue = 0;
                $allInventory = 0;
                $allInventoryValue = 0;

                //Lấy tồn đầu kỳ, nhập, xuất, tồn theo kho
                if (count($getWarehouse) > 0) {
                    foreach ($getWarehouse as $wh) {
                        //Lấy tồn đầu kỳ theo ngày bắt đầu
                        $getLog = $mInventoryLog->getInventoryLog($v['product_code'], $wh['warehouse_id'], $startTime);
                        //Lấy số lượng nhập kho (từ ngày -> ngày)
                        $getImport = $mInputDetail->getInputToDate(
                            $v['product_code'],
                            $wh['warehouse_id'],
                            $startTime. ' '. '00:00:00',
                            $endTime. ' '. '23:59:59'
                        );
                        //Lấy số lượng xuất kho (từ ngày -> ngày)
                        $getExport = $mOutputDetail->getOutputToDate(
                            $v['product_code'],
                            $wh['warehouse_id'],
                            $startTime. ' '. '00:00:00',
                            $endTime. ' '. '23:59:59'
                        );

                        $beginInventory = $getLog != null ? $getLog['inventory'] : 0;
                        $beginInventoryValue = $getLog != null ? $getLog['inventory_value'] : 0;
                        $totalInput = $getImport != null ? $getImport['quantity'] : 0;
                        $totalInputValue = $getImport != null ? $getImport['total'] : 0;
                        $totalOutput = $getExport != null ? $getExport['quantity'] : 0;
                        $totalOutputValue = $getExport != null ? $getExport['total'] : 0;
                        $inventory = $beginInventory + ($totalInput - $totalOutput);
                        $price = $inventory * $v['cost'];

                        //Lấy tổng tồn đầu kỳ + giá trị
                        $allBeginInventory += $beginInventory;
                        $allBeginInventoryValue += $beginInventoryValue;
                        //Lấy tổng nhập + giá trị
                        $allInput += $totalInput;
                        $allInputValue += $totalInputValue;
                        //Lấy tổng xuất + giá trị
                        $allOutput += $totalOutput;
                        $allOutputValue += $totalOutputValue;
                        //Lấy tổng tồn + giá trị
                        $allInventory += $inventory;
                        $allInventoryValue += $price;

                        $dataWarehouse [] = [
                            'begin_inventory' => $beginInventory,
                            'begin_inventory_value' => $beginInventoryValue,
                            'total_input' => $totalInput,
                            'total_input_value' => $totalInputValue,
                            'total_output' => $totalOutput,
                            'total_output_value' => $totalOutputValue,
                            'inventory' => $inventory,
                            'price' => $price
                        ];
                    }
                }

                $v['data_warehouse'] = $dataWarehouse;
                $v['allBeginInventory'] = $allBeginInventory;
                $v['allBeginInventoryValue'] = $allBeginInventoryValue;
                $v['allInput'] = $allInput;
                $v['allInputValue'] = $allInputValue;
                $v['allOutput'] = $allOutput;
                $v['allOutputValue'] = $allOutputValue;
                $v['allInventory'] = $allInventory;
                $v['allInventoryValue'] = $allInventoryValue;
            }
        }

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        //Lấy dữ liệu export
        $data = [
            'list' => $getProduct,
            'listWarehouse' => $getWarehouse,
            'created_at' => $input['created_at'],
            'warehouse_name' => $warehouseName
        ];

        return Excel::download(new ExportInventory($data), 'export-inventory.xlsx');
    }

    /**
     * Lấy option sản phẩm load more
     *
     * @param $input
     * @return mixed|void
     */
    public function getListChild($input)
    {
        $mProductChild = app()->get(ProductChildTable::class);

        $input['search_keyword'] = isset($input['search']) ? $input['search'] : '';

        unset($input['search']);

        //Lấy ds sản phẩm
        $data = $mProductChild->getListChild($input);

        return response()->json([
            'items' => $data->items(),
            'pagination' => range($data->currentPage(),
                $data->lastPage()) ? true : false
        ]);
    }
}