<?php

namespace Modules\Payment\Repositories\ReportSynthesis;

use Modules\Payment\Models\BranchTable;
use Modules\Payment\Models\PaymentMethodTable;
use Modules\Payment\Models\PaymentTable;
use Modules\Payment\Models\PaymentTypeTable;
use Modules\Payment\Models\ReceiptTable;
use Modules\Payment\Models\ReceiptTypeTable;
use PhpOffice\PhpSpreadsheet\Shared\OLE\PPS;

class ReportSynthesisRepo implements ReportSynthesisRepoInterface
{
    public function dataViewIndex()
    {
        $mBranch = new BranchTable();
        $mReceiptType = new ReceiptTypeTable();
        $mPaymentType = new PaymentTypeTable();
        $mPaymentMethod = new PaymentMethodTable();

        $optionBranch = $mBranch->getOption();
        $optionReceiptType = $mReceiptType->getOption();
        $optionPaymentType = $mPaymentType->getOption();
        $optionPaymentMethod = $mPaymentMethod->getOption();

        return [
            'optionBranch' => $optionBranch,
            'optionReceiptType' => $optionReceiptType,
            'optionPaymentType' => $optionPaymentType,
            'optionPaymentMethod' => $optionPaymentMethod,
        ];
    }

    /**
     * filter biểu đồ
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function filterAction($input)
    {
        $time = $input['time'];
        $branchCode = $input['branch'];
        $receiptTypeCode = $input['receiptType'];
        $paymentTypeId = $input['paymentType'];
        $paymentMethodId = $input['paymentMethod'];

        $mBranch = new BranchTable();
        $mReceipt = new ReceiptTable();
        $mPayment = new PaymentTable();
        $getBranch = $mBranch->getBranchByCode($branchCode); // lấy id branch
        $allRecordReceipt = $mReceipt->getAllReceiptByFilter(
            $time,
            $getBranch != null ? $getBranch['branch_id'] : null,
            $receiptTypeCode,
            $paymentMethodId);

        $allRecordPayment = $mPayment->getAllPaymentByFilter($time, $branchCode, $paymentTypeId, $paymentMethodId);
        // Tính tổng thu, tổng chi, tồn quỹ
        $calculate = $this->calculateReceiptPayment($allRecordReceipt, $allRecordPayment);
//        dd($time, $branchCode,$getBranch['branch_id'], $paymentTypeId,$receiptTypeCode, $paymentMethodId);
        // Xử lý data cho biểu đồ dòng tiền theo chi nhánh
        $branchDataTable = $this->processChartBranch(
            $time,
            $branchCode,
            $getBranch != null ? $getBranch['branch_id'] : '',
            $paymentTypeId,
            $receiptTypeCode,
            $paymentMethodId);

        // Xử lý data cho biểu đồ dòng tiền theo hình thức thanh toán
        $ChartPaymentMethod = $this->processChartPaymentMethod(
            $time,
            $branchCode,
            $getBranch != null ? $getBranch['branch_id'] : '',
            $paymentTypeId,
            $receiptTypeCode,
            $paymentMethodId);

        // Xử lý data cho biểu đồ dòng tiền theo loại phiếu thu
        $ChartReceiptType = $this->processChartReceiptType(
            $time,
            $getBranch != null ? $getBranch['branch_id'] : '',
            $receiptTypeCode,
            $paymentMethodId);

        // Xử lý data cho biểu đồ dòng tiền theo loại phiếu chi
        $ChartPaymentType = $this->processChartPaymentType($time, $branchCode, $paymentTypeId, $paymentMethodId);

        $dataReturn = [
            'calculate' => $calculate,
            'receipt_type' => $ChartReceiptType,
            'payment_type' => $ChartPaymentType,
            'payment_method' => $ChartPaymentMethod,
            'branch_datatable' => $branchDataTable,
        ];
        return response()->json($dataReturn);
    }

    // Tính tổng thu, tổng chi, tồn quỹ
    protected function calculateReceiptPayment($allRecordReceipt, $allRecordPayment)
    {
        $sumReceipt = 0;
        $sumPayment = 0;

        foreach ($allRecordReceipt as $receipt) {
            $sumReceipt += $receipt['amount_paid'];
        }
        foreach ($allRecordPayment as $payment) {
            $sumPayment += $payment['total_amount'];
        }
        return [
            'totalFund' => $sumReceipt - $sumPayment,
            'totalReceipt' => $sumReceipt,
            'totalPayment' => $sumPayment
        ];
    }

    protected function processChartBranch($time, $branchCode, $branchId, $paymentType, $receiptType, $paymentMethod)
    {
        $BranchTable = new BranchTable();
        $totalEachBranch = $BranchTable->getTotalPaymentEachBranch($time, $branchCode, $branchId, $paymentType, $receiptType, $paymentMethod)->toArray();
        $dataReturn = [];
        $totalPayment = $BranchTable->getTotalRecordPaymentEachBranch($time, $branchCode, $paymentType, $paymentMethod)->toArray();
        $totalReceipt = $BranchTable->getTotalRecordReceiptEachBranch($time, $branchId, $receiptType, $paymentMethod)->toArray();
        $totalBalance = $this->processGetTotalBalance($totalReceipt, $totalPayment);
//        $dataReturn = array_merge_recursive($totalPayment,$totalReceipt);
        foreach ($totalBalance as $value) {
            $branchName = $value["branch_name"];
            $balance = (float)$value['total_amount'];
            $payment = 0;
            $receipt = 0;
            foreach ($totalPayment as $k => $v) {
                if (in_array($v["branch_id"], $value)) {
                    $payment = (float)$v["total_amount"];
                    break;
                }
            }
            foreach ($totalReceipt as $k => $v) {
                if (in_array($v["branch_id"], $value)) {
                    $receipt = (float)$v["amount"];
                    break;
                }
            }
            $dataReturn [] = array("branch_name" => $branchName,
                "payment" => $payment,
                "receipt" => $receipt,
                "balance" => $balance);
        }
        // lấy dữ liệu tổng phiếu chi từng ngày theo từng chi nhánh
        $totalPaymentEachBranchByDay = $BranchTable->getDataChartPaymentEachBranch($time, $branchCode, $paymentType, $paymentMethod)->toArray();
        // lấy dữ liệu tổng phiếu thu từng ngày theo từng chi nhánh
        $totalReceiptEachBranchByDay = $BranchTable->getDataChartReceiptEachBranch($time, $branchId, $receiptType, $paymentMethod)->toArray();
        // chưa xử lý data balance
        $totalBalanceEachBranchByDay = $this->processGetTotalBalanceWithPaymentDate($totalReceiptEachBranchByDay, $totalPaymentEachBranchByDay);
//        dd($totalReceiptEachBranchByDay,$totalPaymentEachBranchByDay,$totalBalanceEachBranchByDay);
//        $totalBalanceEachBranchByDay =  $BranchTable->getDataChartBalanceEachBranch($time, $branchCode,$branchId, $paymentType,$receiptType, $paymentMethod)->toArray();
//        $this->getDataChartBalanceEachBranch($totalPaymentEachBranchByDay,$totalReceiptEachBranchByDay);
        // định dạng lại dữ liệu cho đúng với format của high chart
        $branchPaymentChart = $this->precessFormatDataPaymentReceipt($totalPaymentEachBranchByDay);
        $branchReceiptChart = $this->precessFormatDataPaymentReceipt($totalReceiptEachBranchByDay);
        $branchBalanceChart = $this->precessFormatDataPaymentReceipt($totalBalanceEachBranchByDay);
//        dd($branchBalanceChart);
        // lấy danh sách các ngày (category day của high chart)
        $branchPaymentCateDay = $this->processFormatCateDate($totalPaymentEachBranchByDay);
        $branchReceiptCateDay = $this->processFormatCateDate($totalReceiptEachBranchByDay);
        $branchBalanceCateDay = $this->processFormatCateDate($totalBalanceEachBranchByDay);
        return [
            'branch_datatable' => $dataReturn,
            'branch_payment_chart' => $branchPaymentChart,
            'branch_receipt_chart' => $branchReceiptChart,
            'branch_balance_chart' => $branchBalanceChart,
            'branch_payment_cate_day' => $branchPaymentCateDay,
            'branch_receipt_cate_day' => $branchReceiptCateDay,
            'branch_balance_cate_day' => $branchBalanceCateDay,
        ];
    }

    /**
     * Lấy thông tin tồn quỹ dựa vào tổng thu và tổng chi của từng chi nhánh
     *
     * @param $totalReceipt
     * @param $totalPayment
     * @return array
     */
    protected function processGetTotalBalance($totalReceipt, $totalPayment)
    {
        $totalBalance = [];
        $listBranchId = $listBranchName = $listTotalAmount = [];
        $n = 0;
        $checkId = false;
        if ($totalReceipt == null && $totalPayment == null) {
            return $totalBalance;
        } else if ($totalReceipt == null && $totalPayment != null) {
            foreach ($totalPayment as $k => $v) {
                foreach ($v as $key => $value) {
                    if ($key == "branch_id") {
                        array_push($listBranchId, $value);
                    }
                    if ($key == "branch_name") {
                        array_push($listBranchName, $value);
                    }
                    if ($key == "total_amount") {
                        array_push($listTotalAmount, (float)$value);
                    }
                }
            }
        } else {
            foreach ($totalReceipt as $k => $v) {
                foreach ($v as $key => $value) {
                    if ($key == "branch_id") {
                        array_push($listBranchId, $value);
                    }
                    if ($key == "branch_name") {
                        array_push($listBranchName, $value);
                    }
                    if ($key == "amount") {
                        array_push($listTotalAmount, (float)$value);
                    }
                }
            }
            foreach ($totalPayment as $k => $v) {
                foreach ($v as $key => $value) {
                    if ($key == "branch_id") {
                        if (in_array($value, $listBranchId)) {
                            $checkId = true;
                            $n = array_search($value, $listBranchId);
                        } else {
                            array_push($listBranchId, $value);
                        }
                    }
                    if ($key == "branch_name") {
                        if ($checkId) {
                        } else {
                            array_push($listBranchName, $value);
                        }
                    }
                    if ($key == "total_amount") {
                        if ($checkId) {
                            $listTotalAmount[$n] = $listTotalAmount[$n] - $value;
                        } else {
                            array_push($listTotalAmount, (float)-$value);
                        }
                    }
                }
                $checkId = false;
            }
        }
        for ($i = 0; $i < count($listBranchId); $i++) {
            $totalBalance [] = array("branch_id" => $listBranchId[$i],
                "branch_name" => $listBranchName[$i], "total_amount" => $listTotalAmount[$i]);
        }
        return $totalBalance;
    }

    /**
     * Lấy thông tin tồn quỹ dựa vào tổng thu và tổng chi theo từng ngày của từng chi nhánh
     *
     * @param $totalReceipt
     * @param $totalPayment
     * @return array
     */
    protected function processGetTotalBalanceWithPaymentDate($totalReceipt, $totalPayment)
    {
        $totalBalance = [];
        $listBranchId = $listBranchName = $listTotalAmount = $listPaymentDate = [];
        $nId = 0;
        $nDate = 0;
        $checkId = false;
        $checkDate = false;
        if ($totalReceipt == null && $totalPayment == null) {
            return $totalBalance;
        } else if ($totalReceipt == null && $totalPayment != null) {
            foreach ($totalPayment as $k => $v) {
                array_push($listBranchId, $v["branch_id"]);
                array_push($listBranchName, $v["branch_name"]);
                array_push($listTotalAmount, (float)$v["total_amount"]);
                array_push($listPaymentDate, $v["payment_date"]);
            }
        } else {
            foreach ($totalReceipt as $k => $v) {
                array_push($listBranchId, $v["branch_id"]);
                array_push($listBranchName, $v["branch_name"]);
                array_push($listTotalAmount, (float)$v["total_amount"]);
                array_push($listPaymentDate, $v["payment_date"]);
            }
            foreach ($totalPayment as $k => $v) {
                // nếu branch_id của payment đã có trong receipt thì bật checkId = true và ghi lại vị trí của branch_id
                if (in_array($v["branch_id"], $listBranchId)) {
                    $checkId = true;
                    $n = array_search($v["branch_id"], $listBranchId);
                } // nếu branch_id không có thì thêm branch_id vào listBranchId
                else {
                    $checkId = false;
                    array_push($listBranchId, $v["branch_id"]);
                }
                // nếu payment_date của payment đã có trong receipt thì bật checkDate = true và ghi lại vị trí của payment_date
                if (in_array($v["payment_date"], $listPaymentDate)) {
                    $checkDate = true;
                    if ($checkId) { // nếu payment date và branch id đã có thì không thêm gì cả
                        $nDate = array_search($v["payment_date"], $listPaymentDate);
                    } else { // nếu branch id chưa có mà payment_date có thì cũng thêm 2 cái vào
                        array_push($listBranchId, $v["branch_id"]);
                        array_push($listPaymentDate, $v["payment_date"]);
                    }
                } else {
                    if ($checkId) { // nếu payment date chưa có mà branch id đã có thì cũng thêm 2 cái vào
                        array_push($listBranchId, $v["branch_id"]);
                        array_push($listPaymentDate, $v["payment_date"]);
                    } else { // nếu payment date chưa có mà branch id cũng chưa có thì chỉ thêm payment date vì branch id đã thêm rồi
                        array_push($listPaymentDate, $v["payment_date"]);
                    }
                }
                // nếu đã có chi nhánh và đã có ngày thì tồn quỹ sẽ trừ ra (thu - chi)
                if ($checkId && $checkDate) {
                    $listTotalAmount[$n] = $listTotalAmount[$n] - (float)$v["total_amount"];
                } else { // còn lại: no branch no payment, no branch have payment, have branch no payment
                    array_push($listBranchName, $v["branch_name"]);
                    array_push($listTotalAmount, (float)-$v["total_amount"]);
                }
                $checkId = false;
                $checkDate = false;
            }
        }
        for ($i = 0; $i < count($listBranchId); $i++) {
            $totalBalance [] = array("branch_id" => $listBranchId[$i],
                "branch_name" => $listBranchName[$i], "total_amount" => $listTotalAmount[$i], "payment_date" => $listPaymentDate[$i]);
        }
        return $totalBalance;
    }

    protected function precessFormatDataPaymentReceipt($data)
    {
        $dataCateDays = $this->processFormatCateDate($data);
        $dataReturn = [];
        $serialName = "";
        $seriesData = [];
        if (!$data) {
            $dataReturn [] = array("name" => $serialName, "data" => $seriesData);
            return $dataReturn;
        }
        $countCateDate = count($dataCateDays);
//        for($k = 0; $k < $countCateDate;$k++){
//            array_push($seriesData,null);
//        }
        // đếm số chi nhánh có trong data
        $dataCountBranch = [];
        foreach ($data as $v) {
            $dataCountBranch[] = $v["branch_name"];
        }
        $dataCountBranch = array_unique($dataCountBranch);
//        $countBranch = count($dataCountBranch);
        foreach ($dataCountBranch as $v) {
            $serialName = $v;
            foreach ($dataCateDays as $u) {
                $elementCateDays = 0;
                foreach ($data as $d) {
                    $temp = date_format(new \DateTime($d["payment_date"]), "d/m");
                    if ($d["branch_name"] == $v && $temp == $u) {
                        $elementCateDays = (float)$d["total_amount"];
                        break;
                    }
                }
                array_push($seriesData, (float)$elementCateDays);
            }
            $dataReturn [] = array("name" => $serialName, "data" => $seriesData);
            $serialName = "";
            $seriesData = [];
        }
//        $serialName = $data[0]["branch_name"];
//        $seriesData[0] = (float)$data[0]["total_amount"];
//        for($i = 0 ;$i<$countCateDate-1;$i++){
//            if($data[$i]["branch_name"] == $data[$i+1]["branch_name"]){
//                $serialName = $data[$i]["branch_name"];
//                $seriesData[$i+1] = (float)$data[$i+1]["total_amount"];
//            }
//            else{
//                $dataReturn [] = array("name" => $serialName, "data"=>$seriesData);
//                $seriesData = [];
//                for($k = 0; $k < $countCateDate;$k++){
//                    array_push($seriesData,null);
//                }
//                $serialName = $data[$i+1]["branch_name"];
//                $seriesData[$i+1] = (float)$data[$i+1]["total_amount"];
//                if($i == count($data) - 2){
//                    $dataReturn [] = array("name" => $serialName, "data"=>$seriesData);
//                    break;
//                }
//            }
//            if($i == count($data) - 2){
//                $dataReturn [] = array("name" => $serialName, "data"=>$seriesData);
//                break;
//            }
//        }
        return $dataReturn;
    }

    /**
     * Xử lý thông tin thu chi theo từng PTTT
     *
     * @param $time
     * @param $branchCode
     * @param $branchId
     * @param $paymentType
     * @param $receiptType
     * @param $paymentMethod
     * @return array
     */
    protected function processChartPaymentMethod($time, $branchCode, $branchId, $paymentType, $receiptType, $paymentMethod)
    {
        $PaymentMethod = new PaymentMethodTable();
        $totalPaymentMethod = $PaymentMethod->getTotalAmountByPaymentMethod($time, $branchCode, $branchId, $paymentType, $receiptType, $paymentMethod)->toArray();
        $dataBoth = $dataPayment = $dataReceipt = $dataBalance = [];
        foreach ($totalPaymentMethod as $value) {
            $dataBoth [] = array("name" => $value['payment_method_name'], "payment" => (double)$value['total_amount'], "receipt" => (double)$value['amount']);
            $dataPayment [] = array("name" => $value['payment_method_name'], "y" => (double)$value['total_amount']);
            $dataReceipt [] = array("name" => $value['payment_method_name'], "y" => (double)$value['amount']);
            $dataBalance [] = array("name" => $value['payment_method_name'], "y" => (double)$value['balance']);
        }
        $dataChart = $dataChartCate = $dataArrPayment = $dataArrReceipt = $dataArrBalance = [];
        foreach ($totalPaymentMethod as $value) {
            $dataChartCate [] = array($value['payment_method_name']);
            $dataArrPayment [] = array((double)$value['total_amount']);
            $dataArrReceipt [] = array((double)$value['amount']);
            $dataArrBalance [] = array((double)$value['balance']);
        }

        $dataChart [] = array("name" => __('Phiếu thu'), "data" => $dataArrReceipt);
        $dataChart [] = array("name" => __('Phiếu chi'), "data" => $dataArrPayment);
        $dataChart [] = array("name" => __('Tồn quỹ'), "data" => $dataArrBalance);
        return [
            'dataBoth' => $dataBoth,
            'dataPayment' => $dataPayment,
            'dataReceipt' => $dataReceipt,
            'dataBalance' => $dataBalance,
            'dataChart' => $dataChart,
            'dataChartCate' => $dataChartCate
        ];
    }

    /**
     * Xử lý thông tin thu chi theo từng loại phiếu thu
     *
     * @param $time
     * @param $branchId
     * @param $receiptTypeCode
     * @param $paymentMethodId
     * @return array
     */
    protected function processChartReceiptType($time, $branchId, $receiptTypeCode, $paymentMethodId)
    {
        $ReceiptType = new ReceiptTypeTable();
        $totalReceiptType = $ReceiptType->getTotalReceiptByReceiptType($time, $branchId, $receiptTypeCode, $paymentMethodId)->toArray();

        $dataReturn = [];

        foreach ($totalReceiptType as $value) {
            $dataReturn [$value['receipt_type_name']] = [
                'name' => $value['receipt_type_name'],
                'y' => isset($dataReturn [$value['receipt_type_name']]['y']) ? $dataReturn [$value['receipt_type_name']]['y'] + (double)$value['amount_paid'] : (double)$value['amount_paid']
            ];
        }

        return array_values($dataReturn);
    }

    /**
     * Xử lý thông tin thu chi theo từng loại phiếu chi
     *
     * @param $time
     * @param $branchCode
     * @param $paymentTypeId
     * @param $paymentMethodId
     * @return array
     */
    protected function processChartPaymentType($time, $branchCode, $paymentTypeId, $paymentMethodId)
    {
        $PaymentType = new PaymentTypeTable();
        $totalPaymentType = $PaymentType->getTotalPaymentByPaymentType($time, $branchCode, $paymentTypeId, $paymentMethodId)->toArray();
        $dataReturn = [];
        foreach ($totalPaymentType as $value) {
            $dataReturn [] = array("name" => $value['payment_type_name'], "y" => (double)$value['total_amount']);
        }
        return $dataReturn;
    }

    protected function getDataChartBalanceEachBranch($dataPayment, $dataReceipt)
    {
        $dataBalanceMerge = array_merge_recursive($dataPayment, $dataReceipt);
        // array_search('2021-03-17', array_column($dataBalanceMerge, 'payment_date'))
        $dataBalance = [];
        foreach ($dataBalanceMerge as $value) {
            // nếu branch id và payment date của $value đã có trong $dataBalance thì xử lý sau đó push vào $dataBalance
            if (
                ((bool)array_search($value["branch_id"], array_column($dataBalance, 'branch_id')) &&
                    (bool)array_search($value["payment_date"], array_column($dataBalance, 'payment_date')))
                ||
                (array_search($value["branch_id"], array_column($dataBalance, 'branch_id')) == 0 &&
                    array_search($value["payment_date"], array_column($dataBalance, 'payment_date') == 0))
            ) {
                // tính toán số tiền (tổng thu - tổng chi)
                $total_amount = 0;
                //
                $dataBalance [] = array("branch_id" => $value["branch_id"],
                    "branch_name" => $value["branch_name"], "total_amount" => $total_amount,
                    "payment_date" => $value["payment_date"]);
            } else array_push($dataBalance, $value);
//            dd($dataBalanceMerge,$dataBalance);
        }
//        foreach ($dataPayment as $balance){
//            foreach($dataReceipt as $value){
//                // đúng recore (trùng branch và ngày tháng năm)
//                if($balance["branch_id"] == $value["branch_id"] && $balance["payment_date"] == $value["payment_date"])
//                {
//                }
//            }
//        }
    }


    protected function processFormatCateDate($data)
    {
        $dataReturn = [];
        foreach ($data as $value) {
            $date = date_format(new \DateTime($value["payment_date"]), "d/m");
            array_push($dataReturn, $date);
        }
        return array_unique($dataReturn);
    }
}