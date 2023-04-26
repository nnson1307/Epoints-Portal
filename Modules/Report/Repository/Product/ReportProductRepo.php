<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/8/2020
 * Time: 11:10 AM
 */

namespace Modules\Report\Repository\Product;


use App\Exports\ExportFile;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\CustomerAppointmentTable;
use Modules\Report\Models\CustomerPotentialLogTable;
use Modules\Report\Models\OrderDetailTable;

class ReportProductRepo implements ReportProductRepoInterface
{
    /**
     * Load chart báo cáo sản phẩm
     *
     * @param $input
     * @return mixed|void
     */
    public function loadChart($input)
    {
        try {
            $mOrderDetail = new OrderDetailTable();
            $mCustomerPotential = new CustomerPotentialLogTable();
            $data = [];
            $dataName = [];
            $dataTotal = [];

            if ($input['type'] == "most_order") {
                $data = $mOrderDetail->getProductBuyTheMost($input['time'],$input['productId']);
            } else if ($input['type'] == "most_view") {
                $data = $mCustomerPotential->getMostViewProduct($input['time'],$input['productId']);
            }
            if (count($data) > 0) {
                foreach ($data as $v) {
                    $dataName [] = $v['product_name'];
                    $dataTotal [] = (int)$v['total'];
                }
            }
            return [
                'error' => 0,
                'dataName' => $dataName,
                'dataTotal' => $dataTotal
            ];
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => $e->getMessage()
            ];
        }
    }
    /**
     * Export excel tổng
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelTotal($input)
    {
        $heading = [
            __('TÊN SẢN PHẨM'),
            __('HÌNH THỨC'),
            __('SỐ LẦN'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mOrderDetail = new OrderDetailTable();
        $mCustomerPotential = new CustomerPotentialLogTable();
        if ($input['export_type_total'] == "most_order") {
            $allData = $mOrderDetail->getProductBuyTheMost($input['export_time_total'],$input['export_product_id_total']);
        } else if ($input['export_type_total'] == "most_view") {
            $allData = $mCustomerPotential->getMostViewProduct($input['export_time_total'],$input['export_product_id_total']);
        }
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['product_name'],
                    $input['export_type_total'] == "most_view" ? __("Xem") : __("Mua"),
                    $item['total'] == '' ? '0' : $item['total']
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-total.xlsx');
    }
}