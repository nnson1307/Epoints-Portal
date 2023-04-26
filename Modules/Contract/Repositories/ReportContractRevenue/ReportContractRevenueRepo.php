<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/26/2021
 * Time: 3:08 PM
 * @author nhandt
 */


namespace Modules\Contract\Repositories\ReportContractRevenue;


use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Contract\Models\ContractCareTable;
use Modules\Contract\Models\ContractCategoriesTable;
use Modules\Contract\Models\ContractExpectedRevenueTable;
use Modules\Contract\Models\ContractMapOrderTable;
use Modules\Contract\Models\ContractReceiptTable;
use Modules\Contract\Models\ContractSpendTable;
use Modules\Contract\Models\ContractTable;
use Modules\Contract\Models\ReceiptTable;

class ReportContractRevenueRepo implements ReportContractRevenueRepoInterface
{
    public function getDataViewIndex($input)
    {
        $mContractCategory = new ContractCategoriesTable();
        $optionCategory = $mContractCategory->getOption();
        return [
            'optionCategory' => $optionCategory,
        ];
    }

    public function getChart($input)
    {
        $mContractExpectedRevenue = new ContractExpectedRevenueTable();
        $mContractReceipt = new ContractReceiptTable();
        $mContractSpend = new ContractSpendTable();

        // dữ liệu chi tiết thu
        $dataReceipt = $mContractReceipt->getReportReceiptDetail($input);
        // dữ liệu chi tiết chi
        $dataSpend = $mContractSpend->getReportSpendDetail($input);
        // dữ liệu dự kiến thu - chi
        $dataExpected = $mContractExpectedRevenue->getReportExpectedRevenueDetail($input);
        $startTime = Carbon::now()->startOfYear()->format('Y-m-d');
        $endTime = Carbon::now()->endOfYear()->format('Y-m-d');
        $dataChart = $this->processCategoriesChart($startTime, $endTime, $dataReceipt, $dataSpend, $dataExpected);
        return $dataChart;
    }
    private function processCategoriesChart($startTime, $endTime, $dataReceipt, $dataSpend, $dataExpected){
        $arrayCategories = $arrayDate = $dataCategories = [];
        $dateDiff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
        //28/10/2019 - 02/08/2021
        $startMonth = Carbon::parse($startTime)->format('m');
        $endMonth = Carbon::parse($endTime)->format('m');
        $startYear = Carbon::parse($startTime)->format('Y');
        $endYear = Carbon::parse($endTime)->format('Y');
        $start = Carbon::parse($startTime)->startOfMonth();
        $end   = Carbon::parse($endTime)->startOfMonth();
        $key = 0;
        do
        {
            $endOfMonthStart = Carbon::parse($start)->endOfMonth();
            $months[$key] = $start->format('d/m/Y') .
                ' - ' .
                $endOfMonthStart->format('d/m/Y');
            if($start->format('m-Y') == Carbon::parse($startTime)->format('m-Y')){
                $months[$key] = Carbon::parse($startTime)->format('d/m/Y').
                    ' - ' .
                    $endOfMonthStart->format('d/m/Y');
            }
            if($start->format('m-Y') == Carbon::parse($endTime)->format('m-Y')){
                $months[$key] = $start->format('d/m/Y') .
                    ' - ' .
                    Carbon::parse($endTime)->format('d/m/Y');
            }
            $key++;
        } while ($start->addMonth() <= $end);
        $arrayCategories = $arrayDate = $months;
        $dataCategories = $this->processDataMoreThan10Days($arrayDate, $dataReceipt, $dataSpend, $dataExpected);
        return [
            'dataCategories' => $dataCategories,
            'arrayCategories' => $arrayCategories,
        ];
    }

    private function processDataMoreThan10Days(array $arrayDate, $dataReceipt, $dataSpend, $dataExpected)
    {
        $data = [];

        foreach ($arrayDate as $key => $value) {
            $data['receipt_detail'][$value] = 0;
            $data['spend_detail'][$value] = 0;
            $data['receipt_expected'][$value] = 0;
            $data['spend_expected'][$value] = 0;
        }

        foreach ($dataReceipt as $key => $value) {
            $timeTemp = $value['created_group'];
            foreach ($arrayDate as $k => $v){
                $arr_filter = explode(" - ", $v);
                $t = Carbon::createFromFormat('d/m/Y', $timeTemp);
                $a0 = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
                $a1 = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
                if($t->gte($a0) && $t->lte($a1)){
                    $data['receipt_detail'][$v] += $value['total_receipt'];
                }
            }
        }

        foreach ($dataSpend as $key => $value) {
            $timeTemp = $value['created_group'];
            foreach ($arrayDate as $k => $v){
                $arr_filter = explode(" - ", $v);
                $t = Carbon::createFromFormat('d/m/Y', $timeTemp);
                $a0 = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
                $a1 = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
                if($t->gte($a0) && $t->lte($a1)){
                    $data['spend_detail'][$v] += $value['total_spend'];
                }
            }
        }

        foreach ($dataExpected as $key => $value) {
            $timeTemp = $value['created_group'];
            foreach ($arrayDate as $k => $v){
                $arr_filter = explode(" - ", $v);
                $t = Carbon::createFromFormat('d/m/Y', $timeTemp);
                $a0 = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
                $a1 = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
                if($t->gte($a0) && $t->lte($a1)){
                    $data['receipt_expected'][$v] += $value['total_expected_receipt'];
                    $data['spend_expected'][$v] += $value['total_expected_spend'];
                }
            }
        }

        $dataFinal = [];
        $dataFinal[] = [
            'name' => __('Dự thu'),
            'data' => array_values($data['receipt_expected'])
        ];
        $dataFinal[] = [
            'name' => __('Thực thu'),
            'data' => array_values($data['receipt_detail'])
        ];
        $dataFinal[] = [
            'name' => __('Dự chi'),
            'data' => array_values($data['spend_expected'])
        ];
        $dataFinal[] = [
            'name' => __('Thực chi'),
            'data' => array_values($data['spend_detail'])
        ];
        return $dataFinal;
    }

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

    public function getListDataExport($input)
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