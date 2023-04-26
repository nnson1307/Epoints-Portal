<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/7/2019
 * Time: 9:35 AM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\Receipt\ReceiptRepositoryInterface;
use Modules\Admin\Repositories\ReceiptDetail\ReceiptDetailRepositoryInterface;

class ReportRevenueByBranchController extends Controller
{
    protected $branches;
    protected $order;
    protected $orderDetail;
    protected $receiptDetail;
    protected $receipt;

    public function __construct(
        BranchRepositoryInterface $branch,
        OrderRepositoryInterface $order,
        OrderDetailRepositoryInterface $orderDetail,
        ReceiptDetailRepositoryInterface $receiptDetail,
        ReceiptRepositoryInterface $receipt
    )
    {
        $this->branches = $branch;
        $this->order = $order;
        $this->orderDetail = $orderDetail;
        $this->receiptDetail = $receiptDetail;
        $this->receipt = $receipt;
    }

    public function indexAction()
    {
        $branch = $this->branches->getBranch();
        $customerGroup = $this->branches->customerGroup();
        return view('admin::report.report-revenue.report-revenue-by-branch', [
            'branch' => $branch,
            'customerGroup' => $customerGroup
        ]);
    }

    public function totalChart($value, $dataSelect, $filter)
    {
        $result = [];
        $result['totalOrder'] = 0;
        $result['totalMoney'] = 0;
        $result['totalOrderPaysuccess'] = 0;
        $result['totalMoneyOrderPaysuccess'] = 0;
        $result['totalOrderNew'] = 0;
        $result['totalMoneyOrderNew'] = 0;
        $result['totalOrderPayFail'] = 0;
        $result['totalMoneyOrderPayFail'] = 0;
//        foreach ($value as $key2 => $val2) {
            foreach ($dataSelect as $key3 => $val3) {
//                if ($dataSelect[$key3][$filter] == $key2) {
                if (isset($value[$dataSelect[$key3][$filter]])) {
                    $result['totalOrder'] += 1;
//                    $result['totalMoney'] += ($dataSelect[$key3]['total'] - $dataSelect[$key3]['discount']);
                    $result['totalMoney'] += ($dataSelect[$key3]['total_money'] - $dataSelect[$key3]['discount']);
//                    if (in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                    if (in_array($dataSelect[$key3]['status'], ["paid", "part-paid"])) {
//                        if ($dataSelect[$key3]['receipts_status'] == 'paid') {
                        if ($dataSelect[$key3]['status'] == 'paid') {
//                            $value[$key2] += $dataSelect[$key3]['amount'];
//                            $value[$key2] += $dataSelect[$key3]['amount_paid'];
                            $value[$dataSelect[$key3][$filter]] += $dataSelect[$key3]['amount_paid'];
                            $result['totalOrderPaysuccess'] += 1;
//                            $result['totalMoneyOrderPaysuccess'] += $dataSelect[$key3]['amount'];
                            $result['totalMoneyOrderPaysuccess'] += $dataSelect[$key3]['amount_paid'];
                        }
//                        bỏ
//                        $result['totalMoneyOrderNew'] += ($dataSelect[$key3]['total_amount'] - $dataSelect[$key3]['amount']);
                    }
//                    if ($dataSelect[$key3]['process_status'] == 'new') {
//                        $result['totalOrderNew'] += 1;
//                        $result['totalMoneyOrderNew'] += $dataSelect[$key3]['total'];
//                    }
//                    if ($dataSelect[$key3]['receipts_status'] == 'cancel') {
                    if ($dataSelect[$key3]['status'] == 'cancel') {
                        $result['totalOrderPayFail'] += 1;
//                        $result['totalMoneyOrderPayFail'] += $dataSelect[$key3]['amount'];
                        $result['totalMoneyOrderPayFail'] += $dataSelect[$key3]['amount_paid'];
                    }
                }
            }
//        }

        return $result;
    }

    public function chartIndexAction(Request $request)
    {
        $arrayBranch = [];
        $arrayValueBranch = [];
        $branch = $this->branches->getBranch();
        $seriesData = [];
        $time=$request->time;
        $startTime = $endTime = null;

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        foreach ($branch as $key => $value) {
            $arrayBranch[] = $value;
            $arrayValueBranch[$key] = 0;
        }
//        $dataSelect = $this->order->getValueByParameter3($startTime, $endTime, null, null);
        $dataSelectTmp = $this->order->getValueByParameter4($startTime, $endTime, null, null);
//
//        $arrOrderId = collect($dataSelectTmp)->pluck('order_id');
//        $dataReceipt = $this->receipt->getListReceipt($arrOrderId);
//        $arrIdReceipt = collect($dataReceipt)->groupBy('order_id');
//        $dataSelect = [];
//        $n = 0;
//        foreach ($dataSelectTmp as $key3 => $val3) {
//            if (isset($arrIdReceipt[$val3['order_id']])){
//                foreach ($arrIdReceipt[$val3['order_id']] as $item) {
//                    $dataSelect[$n] = $val3;
//                    $dataSelect[$n]['amount'] = $item['amount_paid'];
//                    $dataSelect[$n]['total_amount'] = $item['amount'];
//                    $dataSelect[$n]['receipts_status'] = $item['status'];
//                    $n++;
//                }
//            } else {
//                $dataSelect[$n] = $val3;
//                $dataSelect[$n]['amount'] = null;
//                $dataSelect[$n]['total_amount'] = null;
//                $dataSelect[$n]['receipts_status'] = null;
//                $n++;
//            }
//        }

        $dataSelect = $this->receipt->getListReceipt($startTime, $endTime, null, null,null);

        $groupStatus = [];
        $groupStatus = collect($dataSelect)->groupBy('status');
        if (isset($groupStatus['paid'])) {
            foreach ($groupStatus['paid'] as $key3 => $val3) {
//            if (in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"]) && isset($arrayValueBranch[$val3['branch_id']])) {
                if (isset($arrayValueBranch[$val3['branch_id']])) {
//                $arrayValueBranch[$val3['branch_id']] += $dataSelect[$key3]['amount'];
                    $arrayValueBranch[$val3['branch_id']] += $dataSelect[$key3]['amount_paid'];
                }
            }
        }
        if (isset($groupStatus['part-paid'])) {
            foreach ($groupStatus['part-paid'] as $key3 => $val3) {
//            if (in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"]) && isset($arrayValueBranch[$val3['branch_id']])) {
                if (isset($arrayValueBranch[$val3['branch_id']])) {
//                $arrayValueBranch[$val3['branch_id']] += $dataSelect[$key3]['amount'];
                    $arrayValueBranch[$val3['branch_id']] += $dataSelect[$key3]['amount_paid'];
                }
            }
        }


//        foreach ($arrayValueBranch as $key3 => $value3) {
//            $seriesData[] = $value3;
//        }
        $seriesData = array_values($arrayValueBranch);

        $totalChart = $this->totalChart($arrayValueBranch, $dataSelect, 'branch_id');

        // data phương thức thanh toán
        $listReceiptType = ['cash','transfer','visa','member_card','member_point','member_money'];
//        $dataChartReceiptType = $this->receiptDetail->getSumMoneyByReceiptType();
        $dataChartReceiptType = $this->receiptDetail->getSumMoneyByReceiptTypeOptimize();
        $dataTotalMoneyByReceiptType = [];
        // danh sách receipt type thực tế
        $listReceiptTypeReal = [];
        $sum = 0;

        // tính tổng và lấy danh sách receipt type thực tế
        foreach ($dataChartReceiptType as $k => $v) {
            $sum += $v['sum_type'];
            $listReceiptTypeReal[] = $v['receipt_type'];
        }

        foreach ($listReceiptType as $v) {
            if (!in_array($v, $listReceiptTypeReal)) {
                $dataTotalMoneyByReceiptType[] = [
                    'name' => $v,
                    'y' => 0,
                    'sum_type' => 0
                ];
//                $dataTotalMoneyByReceiptType[] = $data;
            }
        }

        foreach ($dataChartReceiptType as $k => $v) {
            $data = [
                'name' => $v['receipt_type'],
                'y' => (float)number_format($v['sum_type']*100 / $sum, 2),
                'sum_type' => number_format($v['sum_type'], 2) . __('đ')
            ];
            $dataTotalMoneyByReceiptType[] = $data;
        }
        $totalChart['totalOrderNew'] = $dataSelectTmp['count_order'];
        $totalChart['totalMoneyOrderNew'] = (double)$dataSelectTmp['total_order'];
        $totalChart['totalOrder'] += $dataSelectTmp['count_order'] ;
        $totalChart['totalMoney'] += (double)$dataSelectTmp['total_order'];
        return response()->json(['list' => $arrayBranch, 'seriesData' => $seriesData, 'totalChart' => $totalChart,
            'totalMoneyByReceiptType' => $dataTotalMoneyByReceiptType]);
    }

    public function filterAction(Request $request)
    {
        $time = $request->time;
        $branch = $request->branch;
        $customer_group = $request->customer_group;
        $arrayBranch = [];
        $arrayValueBranch = [];
        $seriesData = [];
        $startTime = $endTime = null;

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        // data phương thức thanh toán
        $listReceiptType = ['cash','transfer','visa','member_card','member_point','member_money'];
        $dataChartReceiptType = $this->receiptDetail->getSumMoneyByReceiptTypeFilter($startTime, $endTime, $branch);
        $dataTotalMoneyByReceiptType = [];
        // danh sách receipt type thực tế
        $listReceiptTypeReal = [];
        $sum = 0;
        // tính tổng và lấy danh sách receipt type thực tế
        foreach ($dataChartReceiptType as $k => $v) {
            $sum += $v['sum_type'];
            $listReceiptTypeReal[] = $v['receipt_type'];
        }

        foreach ($listReceiptType as $v) {
            if (!in_array($v, $listReceiptTypeReal)) {
                $data = [
                    'name' => $v,
                    'y' => 0,
                    'drilldown' => null
                ];
                $dataTotalMoneyByReceiptType[] = $data;
            }
        }
        foreach ($dataChartReceiptType as $k => $v) {
            $data = [
                'name' => $v['receipt_type'],
                'y' => (float)number_format($v['sum_type']*100 / $sum, 2),
                'drilldown' => null
            ];
            $dataTotalMoneyByReceiptType[] = $data;
        }

        if ($time == null && $branch != null) {
            $monthValueDefault = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0];
            foreach ($this->branches->getBranch() as $key => $value) {
                if ($key == $branch) {
                    $arrayBranch[] = $value;
                    $arrayValueBranch[$key] = 0;
                }
            }
            $dataSelect = $this->order->getValueByYear(date('Y'));

            foreach ($arrayValueBranch as $key2 => $val2) {
                foreach ($dataSelect as $key3 => $val3) {
                    if ($dataSelect[$key3]['branch_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                        $arrayValueBranch[$key2] += $dataSelect[$key3]['amount'];
                        $month = Carbon::createFromFormat('Y-m-d H:i:s', $val3['created_at'])->month;
                        foreach ($monthValueDefault as $mo => $total) {
                            if ($month == $mo) {
                                $monthValueDefault[$mo] += $val3['amount'];
                            }
                        }
                    }
                }
            }
            foreach ($monthValueDefault as $key3 => $value3) {
                $seriesData[] = $value3;
            }
            $totalChart = $this->totalChart($arrayValueBranch, $dataSelect, 'branch_id');
            return response()->json(['seriesData' => $seriesData, 'totalChart' => $totalChart,
                'totalMoneyByReceiptType' => $dataTotalMoneyByReceiptType]);
        } else if ($time != null && $branch == null) {
//            $dataSelect = $this->order->getValueByParameter3($startTime, $endTime, null, null);
            $dataSelectTmp = $this->order->getValueByParameter4($startTime, $endTime, null, null,$customer_group);
//
//            $arrOrderId = collect($dataSelectTmp)->pluck('order_id');
//            $dataReceipt = $this->receipt->getListReceipt($arrOrderId);
//            $arrIdReceipt = collect($dataReceipt)->groupBy('order_id');
//            $dataSelect = [];
//            $n = 0;
//            foreach ($dataSelectTmp as $key3 => $val3) {
//                if (isset($arrIdReceipt[$val3['order_id']])){
//                    foreach ($arrIdReceipt[$val3['order_id']] as $item) {
//                        $dataSelect[$n] = $val3;
//                        $dataSelect[$n]['amount'] = $item['amount_paid'];
//                        $dataSelect[$n]['total_amount'] = $item['amount'];
//                        $dataSelect[$n]['receipts_status'] = $item['status'];
//                        $n++;
//                    }
//                } else {
//                    $dataSelect[$n] = $val3;
//                    $dataSelect[$n]['amount'] = null;
//                    $dataSelect[$n]['total_amount'] = null;
//                    $dataSelect[$n]['receipts_status'] = null;
//                    $n++;
//                }
//            }

            $dataSelect = $this->receipt->getListReceipt($startTime, $endTime, null, null,$customer_group);

            foreach ($this->branches->getBranch() as $key => $value) {
                $arrayBranch[] = $value;
                $arrayValueBranch[$key] = 0;
            }

            $groupStatus = [];
            $groupStatus = collect($dataSelect)->groupBy('status');
            if (isset($groupStatus['paid'])) {
//            foreach ($arrayValueBranch as $key2 => $val2) {
//                foreach ($dataSelect as $key3 => $val3) {
                foreach ($groupStatus['paid'] as $key3 => $val3) {
//                    if ($dataSelect[$key3]['branch_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                    if (isset($arrayValueBranch[$val3['branch_id']]) && in_array($dataSelect[$key3]['status'], ["paid", "part-paid"])) {
//                        $arrayValueBranch[$key2] += $dataSelect[$key3]['amount'];
                        $arrayValueBranch[$val3['branch_id']] += $dataSelect[$key3]['amount_paid'];
                    }
                }
//            }
            }

            if (isset($groupStatus['part-paid'])) {
//            foreach ($arrayValueBranch as $key2 => $val2) {
//                foreach ($dataSelect as $key3 => $val3) {
                foreach ($groupStatus['part-paid'] as $key3 => $val3) {
//                    if ($dataSelect[$key3]['branch_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                    if (isset($arrayValueBranch[$val3['branch_id']])) {
//                        $arrayValueBranch[$key2] += $dataSelect[$key3]['amount'];
                        $arrayValueBranch[$val3['branch_id']] += $dataSelect[$key3]['amount_paid'];
                    }
                }
//            }
            }


            foreach ($arrayValueBranch as $key3 => $value3) {
                $seriesData[] = $value3;
            }
            $totalChart = $this->totalChart($arrayValueBranch, $dataSelect, 'branch_id');

            $totalChart['totalOrderNew'] = $dataSelectTmp['count_order'];
            $totalChart['totalMoneyOrderNew'] = (double)$dataSelectTmp['total_order'];

            $totalChart['totalOrder'] += $dataSelectTmp['count_order'] ;
            $totalChart['totalMoney'] += (double)$dataSelectTmp['total_order'];

            return response()->json(['list' => $arrayBranch, 'seriesData' => $seriesData, 'totalChart' => $totalChart,
                'totalMoneyByReceiptType' => $dataTotalMoneyByReceiptType]);
        } else if ($time != null && $branch != null) {
            //Số ngày.
            $datediff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
//            $dataSelect = $this->order->getValueByParameter3($startTime, $endTime, 'branch_id', $branch);

            $dataSelectTmp = $this->order->getValueByParameter4($startTime, $endTime, 'orders.branch_id', $branch,$customer_group);
//
//            $arrOrderId = collect($dataSelectTmp)->pluck('order_id');
//            $dataReceipt = $this->receipt->getListReceipt($arrOrderId);
//            $arrIdReceipt = collect($dataReceipt)->groupBy('order_id');
//            $dataSelect = [];
//            $n = 0;
//            foreach ($dataSelectTmp as $key3 => $val3) {
//                if (isset($arrIdReceipt[$val3['order_id']])){
//                    foreach ($arrIdReceipt[$val3['order_id']] as $item) {
//                        $dataSelect[$n] = $val3;
//                        $dataSelect[$n]['amount'] = $item['amount_paid'];
//                        $dataSelect[$n]['total_amount'] = $item['amount'];
//                        $dataSelect[$n]['receipts_status'] = $item['status'];
//                        $n++;
//                    }
//                } else {
//                    $dataSelect[$n] = $val3;
//                    $dataSelect[$n]['amount'] = null;
//                    $dataSelect[$n]['total_amount'] = null;
//                    $dataSelect[$n]['receipts_status'] = null;
//                    $n++;
//                }
//            }

            $dataSelect = $this->receipt->getListReceipt($startTime, $endTime, 'orders.branch_id', $branch,$customer_group);

            //Danh sách ngày có giá trị.
            $arrayTime = [];
            $groupByCreate = collect($dataSelect)->keyBy('created_at');
//            foreach ($dataSelect as $kk => $vv) {
//                $timee = date('Y-m-d', strtotime($vv['created_at']));
//                $arrayTime[date('d/m/Y', strtotime($timee))] = $this->order->getValueByDate($timee, 'branch_id', $branch);
//            }
            foreach ($groupByCreate as $kk => $vv) {
                $timee = date('Y-m-d', strtotime($kk));
                $valueByDate = $this->order->getValueByDate($timee, 'branch_id', $branch);
                $arrayTime[date('d/m/Y', strtotime($timee))] = $valueByDate;
                $seriesData[] = (double)$valueByDate;
            }
            $arrayDayValue = [];
            for ($i = 0; $i < $datediff; $i++) {
                $tomorrow = date('d/m/Y', strtotime($startTime . "+" . $i . " days"));
                $arrayDayValue[$tomorrow] = 0;
//                foreach ($arrayTime as $ii => $jj) {
//                    dd($ii);
//                    if ($ii == $tomorrow) {
//                        $result[] = $jj;
//                        $arrayDayValue[$tomorrow] = $jj;
//                    }
//                }
                if (isset($arrayTime[$tomorrow])){
                    $result[] = $tomorrow;
                    $arrayDayValue[$tomorrow] = $tomorrow;
                }
            }
            $arrayDay = [];
            $arrayValueDay = [];
            foreach ($arrayDayValue as $jjj => $jjjj) {
                $arrayDay[] = substr($jjj, 0, -5);
//                $arrayValueDay[] = intval($jjjj);
            }

//            foreach ($this->branches->getBranch() as $key => $value) {
//                if ($key == $branch) {
//                    $arrayBranch[] = $value;
//                    $arrayValueBranch[$key] = 0;
//                }
//            }


            $arrayBranch[] = $this->branches->getNameBranch($branch)['branch_name'];
            $arrayValueBranch[$branch] = 0;
            $groupStatus = [];
            $groupStatus = collect($dataSelect)->groupBy('status');
//            foreach ($arrayValueBranch as $key2 => $val2) {
                if (isset($groupStatus['paid'])) {
                    foreach ($groupStatus['paid'] as $key3 => $val3) {
//                    if ($dataSelect[$key3]['branch_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
//                        if (isset($arrayValueBranch[$val3['branch_id']]) &&  in_array($dataSelect[$key3]['status'], ["paid", "part-paid"])) {
//                        $arrayValueBranch[$key2] += $dataSelect[$key3]['amount'];
                            $arrayValueBranch[$val3['branch_id']] += $dataSelect[$key3]['amount_paid'];
//                        }
                    }
                }
                if (isset($groupStatus['pay-half'])) {
                    foreach ($groupStatus['pay-half'] as $key3 => $val3) {
    //                    if ($dataSelect[$key3]['branch_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
    //                        if (isset($arrayValueBranch[$val3['branch_id']]) &&  in_array($dataSelect[$key3]['status'], ["paid", "part-paid"])) {
    //                        $arrayValueBranch[$key2] += $dataSelect[$key3]['amount'];
                        $arrayValueBranch[$val3['branch_id']] += $dataSelect[$key3]['amount_paid'];
    //                        }
                    }
                }
//            }

//            foreach ($arrayValueBranch as $key3 => $value3) {
//                $seriesData[] = $value3;
//            }

            $result3 = [];
            $result3['totalOrder'] = 0;
            $result3['totalMoney'] = 0;
            $result3['totalOrderPaysuccess'] = 0;
            $result3['totalMoneyOrderPaysuccess'] = 0;
            $result3['totalOrderNew'] = 0;
            $result3['totalMoneyOrderNew'] = 0;
            $result3['totalOrderPayFail'] = 0;
            $result3['totalMoneyOrderPayFail'] = 0;
            foreach ($dataSelect as $key3 => $val3) {
                if ($val3['branch_id'] == $branch) {
                    $result3['totalOrder']++;
//                    $result3['totalMoney'] += ($dataSelect[$key3]['total'] - $dataSelect[$key3]['discount']);
                    $result3['totalMoney'] += ($dataSelect[$key3]['total_money'] - $dataSelect[$key3]['discount']);
//                    if (in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                    if (in_array($dataSelect[$key3]['status'], ["paid", "part-paid"])) {
//                        if ($dataSelect[$key3]['receipts_status'] == 'paid') {
                        if ($dataSelect[$key3]['status'] == 'paid') {
                            $result3['totalOrderPaysuccess'] += 1;
//                            $result3['totalMoneyOrderPaysuccess'] += $dataSelect[$key3]['amount'];
                            $result3['totalMoneyOrderPaysuccess'] += $dataSelect[$key3]['amount_paid'];
                        }
//                        $result3['totalMoneyOrderNew'] += ($dataSelect[$key3]['total_amount'] - $dataSelect[$key3]['amount']);
                    }
//                    if ($dataSelect[$key3]['process_status'] == 'new') {
//                        $result3['totalOrderNew'] += 1;
//                        $result3['totalMoneyOrderNew'] += $dataSelect[$key3]['total'];
//                    }
//                    if ($dataSelect[$key3]['receipts_status'] == 'cancel') {
                    if ($dataSelect[$key3]['status'] == 'cancel') {
                        $result3['totalOrderPayFail'] += 1;
//                        $result3['totalMoneyOrderPayFail'] += $dataSelect[$key3]['amount'];
                        $result3['totalMoneyOrderPayFail'] += $dataSelect[$key3]['amount_paid'];
                    }
                }
            }

            $result3['totalOrderNew'] = $dataSelectTmp['count_order'];
            $result3['totalMoneyOrderNew'] = (double)$dataSelectTmp['total_order'];
            $result3['totalOrder'] += $dataSelectTmp['count_order'] ;
            $result3['totalMoney'] += (double)$dataSelectTmp['total_order'];

            return response()->json(['list' => $arrayDay, 'seriesData' => $seriesData, 'totalChart' => $result3,
//            return response()->json(['list' => $arrayBranch, 'seriesData' => $seriesData, 'totalChart' => $result3,
                'totalMoneyByReceiptType' => $dataTotalMoneyByReceiptType]);
        }
    }
}