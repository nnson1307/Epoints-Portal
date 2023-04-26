<?php


namespace Modules\Report\Repository\CustomerByViewPurchase;


use App\Exports\ExportFile;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\CustomerPotentialLogTable;
use Modules\Report\Models\OrderTable;
use Modules\Report\Models\ProductCategoryTable;

class CustomerByViewPurchaseRepo implements CustomerByViewPurchaseRepoInterface
{
    public function dataView()
    {
        $mProductCategory = new ProductCategoryTable();
        $optionProductCat = $mProductCategory->getOption();
        return [
          'optionProductCategory' => $optionProductCat
        ];
    }

    /**
     * Biểu đồ những khách hàng mua sản phẩm (xem sản phẩm) thuộc nhóm sản phẩm nhiều nhất
     *
     * @param $input
     * @return array|mixed
     */
    public function loadChart($input)
    {
        try {
            $mOrder = new OrderTable();
            $mCustomerPotential = new CustomerPotentialLogTable();

            $data = [];
            $dataName = [];
            $dataTotal = [];
            if ($input['type'] == "most_order") {
                $data = $mOrder->getCustomerByPurchase($input['product_category'], $input['time'], true)->toArray();
            } else if ($input['type'] == "most_view") {
                $data = $mCustomerPotential->getCustomerByView($input['product_category'], $input['time'], true)->toArray();
            }

            if (count($data) > 0) {
                foreach ($data as $item) {
                    $name = '';
                    if ($item['full_name'] != null) {
                        $name = $item['full_name'];
                        if ($item['email'] != null) {
                            $name = $name.'<br>'.$item['email'];
                        }
                        if ($item['phone1'] != null) {
                            $name = $name.'<br>'.$item['phone1'];
                        }
                    }
                    $dataName[] = $name;
                    $dataTotal[] = (int)$item['total'];
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
     * Xuất dữ liệu ra file excel theo filter
     *
     * @param $input
     * @return array|mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel($input)
    {
        try {
            $mOrder = new OrderTable();
            $mCustomerPotential = new CustomerPotentialLogTable();

            $data = [];
            if ($input['type'] == "most_order") {
                $data = $mOrder->getCustomerByPurchase($input['product_category'], $input['time'], false);
            } else if ($input['type'] == "most_view") {
                $data = $mCustomerPotential->getCustomerByView($input['product_category'], $input['time'], false);
            }

            // Export data
            $dataExport = [];
            foreach ($data as $key => $item) {
                $dataExport [] = [
                    $key+1,
                    'name' => $item['full_name'],
                    'email' => $item['email'],
                    'phone' => $item['phone1'],
                    'type' => $input['type'] == "most_order" ? __('Lượt mua') : __('Lượt xem'),
//                    'product_category' => 'aa',
                    'total' => $item['total'],
                ];
            }
            $heading = [
                __('STT'),
                __('Họ & Tên'),
                __('Email'),
                __('Số điện thoại'),
                __('Loại'),
//                __('Danh mục sản phẩm'),
                $input['type'] == "most_order" ? __('Số lượng sản phẩm mua'): __('Số lượt xem sản phẩm'),
            ];
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            return Excel::download(new ExportFile($heading, $dataExport), 'report.xlsx');

        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => $e->getMessage()
            ];
        }
    }
}