<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/7/2019
 * Time: 3:20 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Customer\CustomerRepository;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\Receipt\ReceiptRepositoryInterface;

class ReportRevenueByCustomerController extends Controller
{
    protected $branches;
    protected $order;
    protected $customer;
    protected $receipt;

    public function __construct(
        CustomerRepository $customer,
        BranchRepositoryInterface $branch,
        OrderRepositoryInterface $order,
        ReceiptRepositoryInterface $receipt
    )
    {
        $this->branches = $branch;
        $this->order = $order;
        $this->customer = $customer;
        $this->receipt = $receipt;
    }

    public function indexAction()
    {
        $branch = $this->branches->getBranch();
        $customer = $this->customer->getCustomerIdName();
        return view('admin::report.report-revenue.report-revenue-by-customer', [
            'branch' => $branch,
            'customer' => $customer
        ]);
    }

    //Hàm tính tổng doanh thu theo dữ liệu truyền vào.

    public function chartIndexAction(Request $request)
    {
//        $customer = $this->customer->getCustomerOption();
        $arrayCustomer = [];
        $arrayNameCustomer = [];
        $seriesData = [];
        $time = $request->time;
        $startTime = $endTime = null;

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
//        $dataSelect = $this->order->getValueByYear2(null, $startTime, $endTime);
        $dataSelectTmp = $this->order->getValueByParameter4($startTime, $endTime, null, null);
        $dataSelect = $this->receipt->getListReceipt($startTime, $endTime, null, null);
        $listCustomerId = collect($dataSelect)->pluck('customer_id');
        $customer = $this->customer->getCustomerOptionOptimize($listCustomerId);
//        foreach ($customer as $key => $value) {
//            $arrayCustomer[$key] = 0;
//        }

//        foreach ($arrayCustomer as $key2 => $val2) {
//            foreach ($dataSelect as $key3 => $val3) {
////                if ($dataSelect[$key3]['customer_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
//                if ($dataSelect[$key3]['customer_id'] == $key2 && in_array($dataSelect[$key3]['status'], ["paid", "part-paid"])) {
////                    $arrayCustomer[$key2] += $dataSelect[$key3]['amount'];
//                    $arrayCustomer[$key2] += $dataSelect[$key3]['amount_paid'];
//                }
//            }
//        }

        $groupStatus = [];
        $groupStatus = collect($dataSelect)->groupBy('status');
        if (isset($groupStatus['paid'])){
//            foreach ($dataSelect as $key3 => $val3) {
            foreach ($groupStatus['paid'] as $key3 => $val3) {

//                if ($dataSelect[$key3]['customer_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                if (isset($arrayCustomer[$val3['customer_id']])) {
//                    $arrayCustomer[$key2] += $dataSelect[$key3]['amount'];
                    $arrayCustomer[$val3['customer_id']] += $dataSelect[$key3]['amount_paid'];
                } else {
                    $arrayCustomer[$val3['customer_id']] = $dataSelect[$key3]['amount_paid'];
                }
            }
        }

        if (isset($groupStatus['part-paid'])){
//            foreach ($dataSelect as $key3 => $val3) {
            foreach ($groupStatus['part-paid'] as $key3 => $val3) {

//                if ($dataSelect[$key3]['customer_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                if (isset($arrayCustomer[$val3['customer_id']])) {
//                    $arrayCustomer[$key2] += $dataSelect[$key3]['amount'];
                    $arrayCustomer[$val3['customer_id']] += $dataSelect[$key3]['amount_paid'];
                } else {
                    $arrayCustomer[$val3['customer_id']] = $dataSelect[$key3]['amount_paid'];
                }
            }
        }

//        foreach ($dataSelect as $key3 => $val3) {
//
////                if ($dataSelect[$key3]['customer_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
//            if (isset($arrayCustomer[$val3['customer_id']]) && in_array($dataSelect[$key3]['status'], ["paid", "part-paid"])) {
////                    $arrayCustomer[$key2] += $dataSelect[$key3]['amount'];
//                $arrayCustomer[$val3['customer_id']] += $dataSelect[$key3]['amount_paid'];
//            }
//        }

        //Sắp xếp theo số tiền mua giảm dần.
        arsort($arrayCustomer);
        foreach ($arrayCustomer as $key4 => $value4) {
            $arrayNameCustomer[] = $this->customer->getItem($key4)->full_name;
            $seriesData[] = $value4;
        }

        $totalChart = $this->totalChart($arrayCustomer, $dataSelect, 'customer_id');

        $totalChart['totalOrderNew'] = $dataSelectTmp['count_order'];
        $totalChart['totalMoneyOrderNew'] = (double)$dataSelectTmp['total_order'];
        $totalChart['totalOrder'] += $dataSelectTmp['count_order'] ;
        $totalChart['totalMoney'] += (double)$dataSelectTmp['total_order'];

        return response()->json(['list' => $arrayNameCustomer, 'seriesData' => $seriesData, 'totalChart' => $totalChart, 'countListCustomer' => count($arrayNameCustomer)]);
    }

    //Biểu đồ tại trang chủ.

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
//                    bỏ
//                    $result['totalMoney'] += ($dataSelect[$key3]['amount'] + ($dataSelect[$key3]['total_amount']-$dataSelect[$key3]['amount']));
//                    $result['totalMoney'] += ($dataSelect[$key3]['total'] - $dataSelect[$key3]['discount']);
                    $result['totalMoney'] += ($dataSelect[$key3]['total_money'] - $dataSelect[$key3]['discount']);
//                    if (in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
                    if (in_array($dataSelect[$key3]['status'], ["paid", "part-paid"])) {
//                        if ($dataSelect[$key3]['receipts_status'] == 'paid') {
                        if ($dataSelect[$key3]['status'] == 'paid') {
//                            $value[$key2] += $dataSelect[$key3]['amount'];
                            $value[$dataSelect[$key3][$filter]] += $dataSelect[$key3]['amount_paid'];
                            $result['totalOrderPaysuccess'] += 1;
//                            $result['totalMoneyOrderPaysuccess'] += $dataSelect[$key3]['amount'];
                            $result['totalMoneyOrderPaysuccess'] += $dataSelect[$key3]['amount_paid'];
//                            $result['totalMoneyOrderNew'] += ($dataSelect[$key3]['total_amount']-$dataSelect[$key3]['amount']);
                        }
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

    //Hàm lọc kết quả.

    public function filterAction(Request $request)
    {
        $time = $request->time;
        $branch = $request->branch;
        $customer = null;
        $numberCustomer = $request->numberCustomer;

        $startTime = $endTime = null;
        $customerOption = $this->customer->getCustomerIdName();
        $arrayCustomer = [];
        $arrayNameCustomer = [];
        $seriesData = [];
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
//        var_dump($request->all());
        if ($time != null && $branch == null && $numberCustomer == null) {
            //3. Từ ngày đến ngày.
//            $dataSelect = $this->order->getValueByYear2(null, $startTime, $endTime);
            $dataSelectTmp = $this->order->getValueByParameter4($startTime, $endTime, null, null);
            $dataSelect = $this->receipt->getListReceipt($startTime, $endTime, null, null);

            //Mảng có key là id nhân viên và giá trị mặc định là 0;
//            foreach ($customerOption as $key => $value) {
//                $arrayCustomer[$key] = 0;
//            }

//            foreach ($arrayCustomer as $key2 => $val2) {
//                foreach ($dataSelect as $key3 => $val3) {
//                    //Gán giá trị cho nhân viên.
//                    if ($dataSelect[$key3]['customer_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
//                        $arrayCustomer[$key2] += $dataSelect[$key3]['amount'];
//                    }
//                }
//            }
            $groupStatus = [];
            $groupStatus = collect($dataSelect)->groupBy('status');
            if (isset($groupStatus['part-paid'])){
                foreach($groupStatus['part-paid'] as $key3 => $val3) {
                    //Gán giá trị cho nhân viên.
                    if (isset($arrayCustomer[$val3['customer_id']])) {
                        $arrayCustomer[$val3['customer_id']] += $dataSelect[$key3]['amount'];
                    } else {
                        $arrayCustomer[$val3['customer_id']] = $dataSelect[$key3]['amount'];
                    }
                }
            }

            if (isset($groupStatus['paid'])){
                foreach($groupStatus['paid'] as $key3 => $val3) {
                    //Gán giá trị cho nhân viên.
                    if (isset($arrayCustomer[$val3['customer_id']])) {
                        $arrayCustomer[$val3['customer_id']] += $dataSelect[$key3]['amount'];
                    } else {
                        $arrayCustomer[$val3['customer_id']] = $dataSelect[$key3]['amount'];
                    }
                }
            }

            //Sắp xếp số tiền của mảng khách hàng theo chiều giảm dần.
            arsort($arrayCustomer);
            foreach ($arrayCustomer as $key4 => $value4) {
                $arrayNameCustomer[] = $this->customer->getItem($key4)->full_name;
                $seriesData[] = $value4;
            }

            $totalChart = $this->totalChart($arrayCustomer, $dataSelect, 'customer_id');

            $totalChart['totalOrderNew'] = $dataSelectTmp['count_order'];
            $totalChart['totalMoneyOrderNew'] = (double)$dataSelectTmp['total_order'];
            $totalChart['totalOrder'] += $dataSelectTmp['count_order'] ;
            $totalChart['totalMoney'] += (double)$dataSelectTmp['total_order'];

            return response()->json(['list' => $arrayNameCustomer, 'seriesData' => $seriesData, 'totalChart' => $totalChart, 'countListCustomer' => count($arrayNameCustomer)]);
        } else if ($time != null && $branch != null && $numberCustomer != null) {
            //4. Từ ngày đến ngày và chi nhánh.
//            $dataSelect = $this->order->fetchValueByParameter3(null, $startTime, $endTime, 'branch_id', $branch);
            $dataSelectTmp = $this->order->getValueByParameter4($startTime, $endTime, 'orders.branch_id', $branch);
            $dataSelect = $this->receipt->getListReceipt($startTime, $endTime, 'orders.branch_id', $branch);
            //Mảng có key là id khách hàng và giá trị mặc định là 0;
//            foreach ($customerOption as $key => $value) {
//                $arrayCustomer[$key] = 0;
//            }

//            foreach ($arrayCustomer as $key2 => $val2) {
//                foreach ($dataSelect as $key3 => $val3) {
//                    //Gán giá trị cho nhân viên.
//                    if ($dataSelect[$key3]['customer_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
//                        $arrayCustomer[$key2] += $dataSelect[$key3]['amount'];
//                    }
//                }
//            }

            $groupStatus = [];
            $groupStatus = collect($dataSelect)->groupBy('status');

            if (isset($groupStatus['part-paid'])){
                foreach($groupStatus['part-paid'] as $key3 => $val3) {
                    //Gán giá trị cho nhân viên.
                    if (isset($arrayCustomer[$val3['customer_id']])) {
                        $arrayCustomer[$val3['customer_id']] += $dataSelect[$key3]['amount'];
                    } else {
                        $arrayCustomer[$val3['customer_id']] = $dataSelect[$key3]['amount'];
                    }
                }
            }

            if (isset($groupStatus['paid'])){
                foreach($groupStatus['paid'] as $key3 => $val3) {
                    //Gán giá trị cho nhân viên.
                    if (isset($arrayCustomer[$val3['customer_id']])) {
                        $arrayCustomer[$val3['customer_id']] += $dataSelect[$key3]['amount'];
                    } else {
                        $arrayCustomer[$val3['customer_id']] = $dataSelect[$key3]['amount'];
                    }
                }
            }


            //Sắp xếp mảng theo chiều giảm dần.
            arsort($arrayCustomer);

            //Gán giá trị cho mảng tên khách hàng và mảng số tiền khách mua.
            $count = 0;
            $arrayTemp = [];
            foreach ($arrayCustomer as $key4 => $value4) {
                $count++;
                if ($count <= $numberCustomer) {
                    $arrayNameCustomer[] = $this->customer->getItem($key4)->full_name;
                    $seriesData[] = $value4;
                    $arrayTemp[$key4] = $value4;
                }
            }

            //Tổng doanh thu.
            $totalChart = $this->totalChart($arrayTemp, $dataSelect, 'customer_id');

            $totalChart['totalOrderNew'] = $dataSelectTmp['count_order'];
            $totalChart['totalMoneyOrderNew'] = (double)$dataSelectTmp['total_order'];
            $totalChart['totalOrder'] += $dataSelectTmp['count_order'] ;
            $totalChart['totalMoney'] += (double)$dataSelectTmp['total_order'];

            return response()->json([
                'list' => $arrayNameCustomer,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countListCustomer' => count($seriesData)
            ]);

        } else if ($time != null && $branch != null && $numberCustomer == null) {
            //4. Từ ngày đến ngày và chi nhánh.
//            $dataSelect = $this->order->fetchValueByParameter3(null, $startTime, $endTime, 'branch_id', $branch);
            $dataSelectTmp = $this->order->getValueByParameter4($startTime, $endTime, 'orders.branch_id', $branch);
            $dataSelect = $this->receipt->getListReceipt($startTime, $endTime, 'orders.branch_id', $branch);

            //Mảng có key là id khách hàng và giá trị mặc định là 0;
//            foreach ($customerOption as $key => $value) {
//                $arrayCustomer[$key] = 0;
//            }

//            foreach ($arrayCustomer as $key2 => $val2) {
//                foreach ($dataSelect as $key3 => $val3) {
//                    //Gán giá trị cho nhân viên.
//                    if ($dataSelect[$key3]['customer_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
//                        $arrayCustomer[$key2] += $dataSelect[$key3]['amount'];
//                    }
//                }
//            }

            $groupStatus = [];
            $groupStatus = collect($dataSelect)->groupBy('status');

            if (isset($groupStatus['part-paid'])){
                foreach($groupStatus['part-paid'] as $key3 => $val3) {
                    //Gán giá trị cho nhân viên.
                    if (isset($arrayCustomer[$val3['customer_id']])) {
                        $arrayCustomer[$val3['customer_id']] += $dataSelect[$key3]['amount'];
                    } else {
                        $arrayCustomer[$val3['customer_id']] = $dataSelect[$key3]['amount'];
                    }
                }
            }

            if (isset($groupStatus['paid'])){
                foreach($groupStatus['paid'] as $key3 => $val3) {
                    //Gán giá trị cho nhân viên.
                    if (isset($arrayCustomer[$val3['customer_id']])) {
                        $arrayCustomer[$val3['customer_id']] += $dataSelect[$key3]['amount'];
                    } else {
                        $arrayCustomer[$val3['customer_id']] = $dataSelect[$key3]['amount'];
                    }
                }
            }

            //Sắp xếp mảng theo chiều giảm dần.
            arsort($arrayCustomer);

            //Gán giá trị cho mảng tên khách hàng và mảng số tiền khách mua.
            foreach ($arrayCustomer as $key4 => $value4) {
                $arrayNameCustomer[] = $this->customer->getItem($key4)->full_name;
                $seriesData[] = $value4;
            }

            //Tổng doanh thu.
            $totalChart = $this->totalChart($arrayCustomer, $dataSelect, 'customer_id');

            $totalChart['totalOrderNew'] = $dataSelectTmp['count_order'];
            $totalChart['totalMoneyOrderNew'] = (double)$dataSelectTmp['total_order'];
            $totalChart['totalOrder'] += $dataSelectTmp['count_order'] ;
            $totalChart['totalMoney'] += (double)$dataSelectTmp['total_order'];

            return response()->json(['list' => $arrayNameCustomer, 'seriesData' => $seriesData, 'totalChart' => $totalChart, 'countListCustomer' => count($arrayNameCustomer)]);

        } else if ($time != null && $branch == null && $numberCustomer != null) {
            //3. Từ ngày đến ngày.
//            $dataSelect = $this->order->getValueByYear2(null, $startTime, $endTime);
            $dataSelectTmp = $this->order->getValueByParameter4($startTime, $endTime, null, null);
            $dataSelect = $this->receipt->getListReceipt($startTime, $endTime, null, null);

            //Mảng có key là id nhân viên và giá trị mặc định là 0;
//            foreach ($customerOption as $key => $value) {
//                $arrayCustomer[$key] = 0;
//            }

//            foreach ($arrayCustomer as $key2 => $val2) {
//                foreach ($dataSelect as $key3 => $val3) {
//                    //Gán giá trị cho nhân viên.
//                    if ($dataSelect[$key3]['customer_id'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
//                        $arrayCustomer[$key2] += $dataSelect[$key3]['amount'];
//                    }
//                }
//            }

            $groupStatus = [];
            $groupStatus = collect($dataSelect)->groupBy('status');

            if (isset($groupStatus['part-paid'])){
                foreach($groupStatus['part-paid'] as $key3 => $val3) {
                    //Gán giá trị cho nhân viên.
                    if (isset($arrayCustomer[$val3['customer_id']])) {
                        $arrayCustomer[$val3['customer_id']] += $dataSelect[$key3]['amount'];
                    } else {
                        $arrayCustomer[$val3['customer_id']] = $dataSelect[$key3]['amount'];
                    }
                }
            }

            if (isset($groupStatus['paid'])){
                foreach($groupStatus['paid'] as $key3 => $val3) {
                    //Gán giá trị cho nhân viên.
                    if (isset($arrayCustomer[$val3['customer_id']])) {
                        $arrayCustomer[$val3['customer_id']] += $dataSelect[$key3]['amount'];
                    } else {
                        $arrayCustomer[$val3['customer_id']] = $dataSelect[$key3]['amount'];
                    }
                }
            }

            //Sắp xếp số tiền của mảng khách hàng theo chiều giảm dần.
            arsort($arrayCustomer);
            $count = 0;
            $arrayTemp = [];
            foreach ($arrayCustomer as $key4 => $value4) {
                $count++;
                if ($count <= $numberCustomer) {
                    $arrayNameCustomer[] = $this->customer->getItem($key4)->full_name;
                    $seriesData[] = $value4;
                    $arrayTemp[$key4] = $value4;
                }
            }

            $totalChart = $this->totalChart($arrayTemp, $dataSelect, 'customer_id');

            $totalChart['totalOrderNew'] = $dataSelectTmp['count_order'];
            $totalChart['totalMoneyOrderNew'] = (double)$dataSelectTmp['total_order'];
            $totalChart['totalOrder'] += $dataSelectTmp['count_order'] ;
            $totalChart['totalMoney'] += (double)$dataSelectTmp['total_order'];

            return response()->json([
                'list' => $arrayNameCustomer,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countListCustomer' => count($seriesData)
            ]);
        }
    }
}