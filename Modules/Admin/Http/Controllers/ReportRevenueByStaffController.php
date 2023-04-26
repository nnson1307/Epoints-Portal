<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/7/2019
 * Time: 11:36 AM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\Receipt\ReceiptRepositoryInterface;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;

class ReportRevenueByStaffController extends Controller
{
    protected $branches;
    protected $order;
    protected $staff;
    protected $receipt;

    public function __construct(
        StaffRepositoryInterface $staff,
        BranchRepositoryInterface $branch,
        OrderRepositoryInterface $order,
        ReceiptRepositoryInterface $receipt

    )
    {
        $this->branches = $branch;
        $this->order = $order;
        $this->staff = $staff;
        $this->receipt = $receipt;
    }

    public function indexAction()
    {
        $branch = $this->branches->getBranch();
        $staff = $this->staff->getStaffOption();
        return view('admin::report.report-revenue.report-revenue-by-staff', [
            'branch' => $branch,
            'staff' => $staff
        ]);
    }

    //Hàm tính tổng doanh thu theo dữ liệu truyền vào.
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
//                    if (in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"]) == 'paysuccess') {
                    if (in_array($dataSelect[$key3]['status'], ["paid", "part-paid"])) {
//                        if ($dataSelect[$key3]['receipts_status'] == 'paid') {
                        if ($dataSelect[$key3]['status'] == 'paid') {
//                            $value[$key2] += $dataSelect[$key3]['amount'];
                            $result['totalOrderPaysuccess'] += 1;
//                            $result['totalMoneyOrderPaysuccess'] += $dataSelect[$key3]['amount'];
                            $result['totalMoneyOrderPaysuccess'] += $dataSelect[$key3]['amount_paid'];
//                        $result['totalMoneyOrderNew'] += ($dataSelect[$key3]['total_amount']-$dataSelect[$key3]['amount']);
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

    public function chartIndexAction(Request $request)
    {
        $staff = $this->staff->getStaffOption();
        $arrayStaff = [];
        $arrayNameStaff = [];
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

//        foreach ($staff as $key => $value) {
//            $arrayStaff[$key] = 0;
//        }
//        foreach ($arrayStaff as $key2 => $val2) {
//            foreach ($dataSelect as $key3 => $val3) {
//                if ($dataSelect[$key3]['created_by'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
//                    $arrayStaff[$key2] += $dataSelect[$key3]['amount'];
//                }
//            }
//
//        }

        $groupStatus = [];
        $groupStatus = collect($dataSelect)->groupBy('status');
        if (isset($groupStatus['part-paid'])){
            foreach($groupStatus['part-paid'] as $key3 => $val3) {
                if ($val3['created_by'] != null){
                    //Gán giá trị cho nhân viên.
                    if (isset($arrayCustomer[$val3['created_by']])) {
                        $arrayStaff[$val3['created_by']] += $dataSelect[$key3]['amount'];
                    } else {
                        $arrayStaff[$val3['created_by']] = $dataSelect[$key3]['amount'];
                    }
                }
            }
        }

        if (isset($groupStatus['paid'])){
            foreach($groupStatus['paid'] as $key3 => $val3) {
                if ($val3['created_by'] != null) {
                    //Gán giá trị cho nhân viên.
                    if (isset($arrayCustomer[$val3['created_by']])) {
                        $arrayStaff[$val3['created_by']] += $dataSelect[$key3]['amount'];
                    } else {
                        $arrayStaff[$val3['created_by']] = $dataSelect[$key3]['amount'];
                    }
                }
            }
        }

        arsort($arrayStaff);
        foreach ($arrayStaff as $key4 => $value4) {
            $arrayNameStaff[] = $this->staff->getItem($key4)->full_name;
            $seriesData[] = round($value4);
        }

        $totalChart = $this->totalChart($arrayStaff, $dataSelect, 'created_by');

        $totalChart['totalOrderNew'] = $dataSelectTmp['count_order'];
        $totalChart['totalMoneyOrderNew'] = (double)$dataSelectTmp['total_order'];
        $totalChart['totalOrder'] += $dataSelectTmp['count_order'] ;
        $totalChart['totalMoney'] += (double)$dataSelectTmp['total_order'];

        return response()->json([
            'list' => $arrayNameStaff,
            'seriesData' => $seriesData,
            'totalChart' => $totalChart,
            'countList' => count($arrayStaff)]);
    }

    public function filterAction(Request $request)
    {
        $time = $request->time;
        $branch = $request->branch;
        $staff = null;
        $numberStaff = $request->numberStaff;
        $arrayStaff = [];
        $arrayNameStaff = [];
        $seriesData = [];
        $staffOption = $this->staff->getStaffOption();
        $startTime = $endTime = null;

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        if ($time != null && $branch == null && $numberStaff == null) {
            //Từ ngày đến ngày.
//            $dataSelect = $this->order->getValueByYear2(null, $startTime, $endTime);
            $dataSelectTmp = $this->order->getValueByParameter4($startTime, $endTime, null, null);
            $dataSelect = $this->receipt->getListReceipt($startTime, $endTime, null, null);

            //Mảng có key là id nhân viên và giá trị mặc định là 0;
//            foreach ($staffOption as $key => $value) {
//                $arrayStaff[$key] = 0;
//            }

//            foreach ($arrayStaff as $key2 => $val2) {
//                foreach ($dataSelect as $key3 => $val3) {
//                    //Gán giá trị cho nhân viên.
//                    if ($dataSelect[$key3]['created_by'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
//                        $arrayStaff[$key2] += $dataSelect[$key3]['amount'];
//                    }
//                }
//            }

            $groupStatus = [];
            $groupStatus = collect($dataSelect)->groupBy('status');
            if (isset($groupStatus['part-paid'])){
                foreach($groupStatus['part-paid'] as $key3 => $val3) {
                    if ($val3['created_by'] != null){
                        //Gán giá trị cho nhân viên.
                        if (isset($arrayCustomer[$val3['created_by']])) {
                            $arrayStaff[$val3['created_by']] += $dataSelect[$key3]['amount'];
                        } else {
                            $arrayStaff[$val3['created_by']] = $dataSelect[$key3]['amount'];
                        }
                    }
                }
            }

            if (isset($groupStatus['paid'])){
                foreach($groupStatus['paid'] as $key3 => $val3) {
                    if ($val3['created_by'] != null) {
                        //Gán giá trị cho nhân viên.
                        if (isset($arrayCustomer[$val3['created_by']])) {
                            $arrayStaff[$val3['created_by']] += $dataSelect[$key3]['amount'];
                        } else {
                            $arrayStaff[$val3['created_by']] = $dataSelect[$key3]['amount'];
                        }
                    }
                }
            }


            arsort($arrayStaff);
            foreach ($arrayStaff as $key4 => $value4) {
//                $arrayNameStaff[] = $this->staff->getItem($key4)->full_name;
                $arrayNameStaff[] = $this->staff->getNameStaff($key4)->full_name;
                $seriesData[] = round($value4);
            }
            $totalChart = $this->totalChart($arrayStaff, $dataSelect, 'created_by');

            $totalChart['totalOrderNew'] = $dataSelectTmp['count_order'];
            $totalChart['totalMoneyOrderNew'] = (double)$dataSelectTmp['total_order'];
            $totalChart['totalOrder'] += $dataSelectTmp['count_order'] ;
            $totalChart['totalMoney'] += (double)$dataSelectTmp['total_order'];

            return response()->json([
                'list' => $arrayNameStaff,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countList' => count($arrayStaff)]);
        } else if ($time != null && $branch != null && $numberStaff == null) {
            //Từ ngày đến ngày và chi nhánh.

//            $dataSelect = $this->order->fetchValueByParameter3(null, $startTime, $endTime, 'branch_id', $branch);
            $dataSelectTmp = $this->order->getValueByParameter4($startTime, $endTime, 'orders.branch_id', $branch);
            $dataSelect = $this->receipt->getListReceipt($startTime, $endTime, 'orders.branch_id', $branch);

            //Mảng có key là id nhân viên và giá trị mặc định là 0;
//            foreach ($staffOption as $key => $value) {
//                $arrayStaff[$key] = 0;
//            }
//            foreach ($arrayStaff as $key2 => $val2) {
//                foreach ($dataSelect as $key3 => $val3) {
//                    //Gán giá trị cho nhân viên.
//                    if ($dataSelect[$key3]['created_by'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
//                        $arrayStaff[$key2] += $dataSelect[$key3]['amount'];
//                    }
//                }
//            }

            $groupStatus = [];
            $groupStatus = collect($dataSelect)->groupBy('status');
            if (isset($groupStatus['part-paid'])){
                foreach($groupStatus['part-paid'] as $key3 => $val3) {
                    if ($val3['created_by'] != null){
                        //Gán giá trị cho nhân viên.
                        if (isset($arrayCustomer[$val3['created_by']])) {
                            $arrayStaff[$val3['created_by']] += $dataSelect[$key3]['amount'];
                        } else {
                            $arrayStaff[$val3['created_by']] = $dataSelect[$key3]['amount'];
                        }
                    }
                }
            }

            if (isset($groupStatus['paid'])){
                foreach($groupStatus['paid'] as $key3 => $val3) {
                    if ($val3['created_by'] != null) {
                        //Gán giá trị cho nhân viên.
                        if (isset($arrayCustomer[$val3['created_by']])) {
                            $arrayStaff[$val3['created_by']] += $dataSelect[$key3]['amount'];
                        } else {
                            $arrayStaff[$val3['created_by']] = $dataSelect[$key3]['amount'];
                        }
                    }
                }
            }

            arsort($arrayStaff);
            foreach ($arrayStaff as $key4 => $value4) {
//                $arrayNameStaff[] = $this->staff->getItem($key4)->full_name;
                $arrayNameStaff[] = $this->staff->getNameStaff($key4)->full_name;
                $seriesData[] = round($value4);
            }
            $totalChart = $this->totalChart($arrayStaff, $dataSelect, 'created_by');

            $totalChart['totalOrderNew'] = $dataSelectTmp['count_order'];
            $totalChart['totalMoneyOrderNew'] = (double)$dataSelectTmp['total_order'];
            $totalChart['totalOrder'] += $dataSelectTmp['count_order'] ;
            $totalChart['totalMoney'] += (double)$dataSelectTmp['total_order'];

            return response()->json([
                'list' => $arrayNameStaff,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countList' => count($arrayStaff)
            ]);
        } else if ($time != null && $branch == null && $numberStaff != null) {
            //Từ ngày đến ngày.
//            $dataSelect = $this->order->getValueByYear2(null, $startTime, $endTime);
            $dataSelectTmp = $this->order->getValueByParameter4($startTime, $endTime, null, null);
            $dataSelect = $this->receipt->getListReceipt($startTime, $endTime, null, null);
            //Mảng có key là id nhân viên và giá trị mặc định là 0;
//            foreach ($staffOption as $key => $value) {
//                $arrayStaff[$key] = 0;
//            }
//            foreach ($arrayStaff as $key2 => $val2) {
//                foreach ($dataSelect as $key3 => $val3) {
//                    //Gán giá trị cho nhân viên.
//                    if ($dataSelect[$key3]['created_by'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
//                        $arrayStaff[$key2] += $dataSelect[$key3]['amount'];
//                    }
//                }
//            }

            $groupStatus = [];
            $groupStatus = collect($dataSelect)->groupBy('status');
            if (isset($groupStatus['part-paid'])){
                foreach($groupStatus['part-paid'] as $key3 => $val3) {
                    if ($val3['created_by'] != null){
                        //Gán giá trị cho nhân viên.
                        if (isset($arrayCustomer[$val3['created_by']])) {
                            $arrayStaff[$val3['created_by']] += $dataSelect[$key3]['amount'];
                        } else {
                            $arrayStaff[$val3['created_by']] = $dataSelect[$key3]['amount'];
                        }
                    }
                }
            }

            if (isset($groupStatus['paid'])){
                foreach($groupStatus['paid'] as $key3 => $val3) {
                    if ($val3['created_by'] != null) {
                        //Gán giá trị cho nhân viên.
                        if (isset($arrayCustomer[$val3['created_by']])) {
                            $arrayStaff[$val3['created_by']] += $dataSelect[$key3]['amount'];
                        } else {
                            $arrayStaff[$val3['created_by']] = $dataSelect[$key3]['amount'];
                        }
                    }
                }
            }

            arsort($arrayStaff);
            $count = 0;
            $arrayTemp = [];
            foreach ($arrayStaff as $key4 => $value4) {
                $count++;
                if ($count <= $numberStaff) {
//                    $arrayNameStaff[] = $this->staff->getItem($key4)->full_name;
                    $arrayNameStaff[] = $this->staff->getNameStaff($key4)->full_name;
                    $seriesData[] = round($value4);
                    $arrayTemp[$key4] = round($value4);
                }
            }
            $totalChart = $this->totalChart($arrayTemp, $dataSelect, 'created_by');

            $totalChart['totalOrderNew'] = $dataSelectTmp['count_order'];
            $totalChart['totalMoneyOrderNew'] = (double)$dataSelectTmp['total_order'];
            $totalChart['totalOrder'] += $dataSelectTmp['count_order'] ;
            $totalChart['totalMoney'] += (double)$dataSelectTmp['total_order'];

            return response()->json([
                'list' => $arrayNameStaff,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countList' => count($seriesData)
            ]);
        } else if ($time != null && $branch != null && $numberStaff != null) {
            //Từ ngày đến ngày và chi nhánh.

//            $dataSelect = $this->order->fetchValueByParameter3(null, $startTime, $endTime, 'branch_id', $branch);
            $dataSelectTmp = $this->order->getValueByParameter4($startTime, $endTime, 'orders.branch_id', $branch);
            $dataSelect = $this->receipt->getListReceipt($startTime, $endTime, 'orders.branch_id', $branch);

            //Mảng có key là id nhân viên và giá trị mặc định là 0;
//            foreach ($staffOption as $key => $value) {
//                $arrayStaff[$key] = 0;
//            }
//            foreach ($arrayStaff as $key2 => $val2) {
//                foreach ($dataSelect as $key3 => $val3) {
//                    //Gán giá trị cho nhân viên.
//                    if ($dataSelect[$key3]['created_by'] == $key2 && in_array($dataSelect[$key3]['process_status'], ["paysuccess", "pay-half"])) {
//                        $arrayStaff[$key2] += $dataSelect[$key3]['amount'];
//                    }
//                }
//            }

            $groupStatus = [];
            $groupStatus = collect($dataSelect)->groupBy('status');
            if (isset($groupStatus['part-paid'])){
                foreach($groupStatus['part-paid'] as $key3 => $val3) {
                    if ($val3['created_by'] != null){
                        //Gán giá trị cho nhân viên.
                        if (isset($arrayCustomer[$val3['created_by']])) {
                            $arrayStaff[$val3['created_by']] += $dataSelect[$key3]['amount'];
                        } else {
                            $arrayStaff[$val3['created_by']] = $dataSelect[$key3]['amount'];
                        }
                    }
                }
            }

            if (isset($groupStatus['paid'])){
                foreach($groupStatus['paid'] as $key3 => $val3) {
                    if ($val3['created_by'] != null) {
                        //Gán giá trị cho nhân viên.
                        if (isset($arrayCustomer[$val3['created_by']])) {
                            $arrayStaff[$val3['created_by']] += $dataSelect[$key3]['amount'];
                        } else {
                            $arrayStaff[$val3['created_by']] = $dataSelect[$key3]['amount'];
                        }
                    }
                }
            }

            arsort($arrayStaff);
            $count = 0;
            $arrayTemp = [];
            foreach ($arrayStaff as $key4 => $value4) {
                $count++;
                if ($count <= $numberStaff) {
//                    $arrayNameStaff[] = $this->staff->getItem($key4)->full_name;
                    $arrayNameStaff[] = $this->staff->getNameStaff($key4)->full_name;
                    $seriesData[] = round($value4);
                    $arrayTemp[$key4] = round($value4);
                }
            }
            $totalChart = $this->totalChart($arrayTemp, $dataSelect, 'created_by');

            $totalChart['totalOrderNew'] = $dataSelectTmp['count_order'];
            $totalChart['totalMoneyOrderNew'] = (double)$dataSelectTmp['total_order'];
            $totalChart['totalOrder'] += $dataSelectTmp['count_order'] ;
            $totalChart['totalMoney'] += (double)$dataSelectTmp['total_order'];

            return response()->json([
                'list' => $arrayNameStaff,
                'seriesData' => $seriesData,
                'totalChart' => $totalChart,
                'countList' => count($seriesData)
            ]);
        }
    }
}