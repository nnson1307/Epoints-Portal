<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/26/2021
 * Time: 9:20 AM
 * @author nhandt
 */


namespace Modules\Contract\Repositories\ReportContractDetail;


use App\Exports\ExportFile;
use Illuminate\Support\Facades\Cookie;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\MapRoleGroupStaffTable;
use Modules\Contract\Models\ContractCategoriesTable;
use Modules\Contract\Models\ContractMapOrderTable;
use Modules\Contract\Models\ContractSpendTable;
use Modules\Contract\Models\ContractTable;
use Modules\Contract\Models\CustomerTable;
use Modules\Contract\Models\ReceiptTable;
use Modules\Contract\Models\SupplierTable;

class ReportContractDetailRepo implements ReportContractDetailRepoInterface
{

    /**
     * data view report, filter,...
     *
     * @param $input
     * @return array
     */
    public function getDataViewIndex($input)
    {
        $mContractCategory = new ContractCategoriesTable();
        $mCustomer = new CustomerTable();
        $mSupplier = new SupplierTable();
        $optionCategory = $mContractCategory->getOption();
        $optionCustomer = $mCustomer->getCustomerOption();
        $optionSupplier = $mSupplier->getOption();
        $optionPartner = [];
        foreach ($optionCustomer as $key => $value){
            $customerText = $value['customer_type'] == 'personal' ? __('Cá nhân') : __('Doanh nghiệp');
            $optionPartner[] = [
                'key' => $value['customer_type'] . '_' . $value['customer_id'],
                'value' =>  $customerText . '_' . $value['full_name']
            ];
        }
        foreach ($optionSupplier as $key => $value){
            $optionPartner[] = [
                'key' => 'supplier' . '_' . $value['id'],
                'value' => __('Nhà cung cấp') . '_' . $value['name']
            ];
        }
        return [
            'optionCategory' => $optionCategory,
            'optionPartner' => $optionPartner
        ];
    }

    /**
     * list data report
     *
     * @param $input
     * @return mixed
     */
    public function getListData($input)
    {
        $mContractMapOrder = app()->get(ContractMapOrderTable::class);
        $mReceipt = app()->get(ReceiptTable::class);
        $mContractSpend = app()->get(ContractSpendTable::class);
        $mContract = new ContractTable();
        $list = $mContract->getListDetail($input);
        foreach ($list->items() as $v) {
            //Lấy đơn hàng gần nhất map với hợp đồng
            $getOrder = $mContractMapOrder->getOrderMap($v['contract_code']);

            $orderCode = null;
            $totalReceipt = 0;
            $totalNotReceipt = 0;
            if ($v['type'] == 'sell' && $getOrder != null) {
                //Hợp đồng bán
                $orderCode = $getOrder['order_code'];

                //Lấy tiền đã thu của đơn hàng
                $getAmountPaid = $mReceipt->getReceiptOrder($getOrder['order_id']);

                $totalReceipt += $getAmountPaid != null ? $getAmountPaid['amount_paid'] : 0;

                $totalNotReceipt = floatval($getOrder['amount']) - floatval($totalReceipt);
            }
            else if ($v['type'] == 'buy') {
                //Hợp đồng mua

                //Lấy tiền đã thu của HĐ
                $getAmountPaid = $mContractSpend->getAmountSpend($v['contract_id']);

                $totalReceipt += $getAmountPaid != null ? $getAmountPaid['total_amount'] : 0;

                $totalNotReceipt = floatval($v['last_total_amount']) - floatval($totalReceipt);
            }
            $v['total_receipt'] = $totalReceipt;
            $v['total_not_receipt'] = $totalNotReceipt;
        }
        return $list;
    }


    /**
     * export excel report
     *
     * @param $input
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel($input)
    {
        $heading = [
            __('MÃ HỢP ĐỒNG'),
            __('TÊN HỢP ĐỒNG'),
            __('LOẠI HỢP ĐỒNG'),
            __('TRẠNG THÁI'),
            __('ĐỐI TÁC'),
            __('NHÂN VIÊN PHỤ TRÁCH'),
            __('NGÀY HIỆU LỰC'),
            __('NGÀY HẾT HẠN'),
            __('NGÀY BẮT ĐẦU BẢO HÀNH'),
            __('NGÀY KẾT THÚC BẢO HÀNH'),
            __('GIÁ TRỊ HỢP ĐỒNG'),
            __('GIÁ TRỊ ĐÃ THANH TOÁN'),
            __('GIÁ TRỊ CHƯA THANH TOÁN')
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mContractMapOrder = app()->get(ContractMapOrderTable::class);
        $mReceipt = app()->get(ReceiptTable::class);
        $mContractSpend = app()->get(ContractSpendTable::class);
        $mContract = new ContractTable();
        $lstContract = $mContract->getListDetailExport($input);
        foreach ($lstContract as $k => $v) {
            //Lấy đơn hàng gần nhất map với hợp đồng
            $getOrder = $mContractMapOrder->getOrderMap($v['contract_code']);
            $orderCode = null;
            $totalReceipt = 0;
            $totalNotReceipt = 0;
            if ($v['type'] == 'sell' && $getOrder != null) {
                //Hợp đồng bán
                $orderCode = $getOrder['order_code'];

                //Lấy tiền đã thu của đơn hàng
                $getAmountPaid = $mReceipt->getReceiptOrder($getOrder['order_id']);

                $totalReceipt += $getAmountPaid != null ? $getAmountPaid['amount_paid'] : 0;

                $totalNotReceipt = floatval($getOrder['amount']) - floatval($totalReceipt);
            }
            else if ($v['type'] == 'buy') {
                //Hợp đồng mua

                //Lấy tiền đã thu của HĐ
                $getAmountPaid = $mContractSpend->getAmountSpend($v['contract_id']);

                $totalReceipt += $getAmountPaid != null ? $getAmountPaid['total_amount'] : 0;

                $totalNotReceipt = floatval($v['last_total_amount']) - floatval($totalReceipt);
            }
            $v['total_receipt'] = $totalReceipt;
            $v['total_not_receipt'] = $totalNotReceipt;
        }
        if (count($lstContract) > 0) {
            foreach ($lstContract as $k => $v) {
                $itemData = [
                    $v['contract_code'],
                    $v['contract_name'],
                    $v['contract_category_name'],
                    $v['status_name'],
                    $v['partner_name'],
                    $v['staff_performer_name'],
                    $v['effective_date'] != '' ? \Carbon\Carbon::parse($v['effective_date'])->format('d/m/Y') : '',
                    $v['expired_date'] != '' ? \Carbon\Carbon::parse($v['expired_date'])->format('d/m/Y') : '',
                    $v['warranty_start_date'] != '' ? \Carbon\Carbon::parse($v['warranty_start_date'])->format('d/m/Y') : '',
                    $v['warranty_end_date'] != '' ? \Carbon\Carbon::parse($v['warranty_end_date'])->format('d/m/Y') : '',
                   number_format($v['last_total_amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                   number_format($v['total_receipt'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                   number_format($v['total_not_receipt'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                ];
                $data [] = $itemData;
            }
        }
        return Excel::download(new ExportFile($heading, $data), 'export-detail.xlsx');
    }
}