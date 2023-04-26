<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 12/25/2018
 * Time: 3:38 PM
 */

namespace Modules\Admin\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Customer\CustomerRepository;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\Product\ProductRepositoryInterface;
use Modules\Admin\Repositories\Service\ServiceRepositoryInterface;
use Modules\Admin\Repositories\ServiceCard\ServiceCardRepositoryInterface;
use Modules\Admin\Repositories\ServiceCategory\ServiceCategoryRepositoryInterface;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;

class ReportRevenueController extends Controller
{
    protected $branches;
    protected $staff;
    protected $customer;
    protected $service;
    protected $product;
    protected $serviceCard;
    protected $order;
    protected $serviceCategory;
    protected $orderDetail;

    public function __construct(
        BranchRepositoryInterface $branch,
        StaffRepositoryInterface $staff,
        ServiceRepositoryInterface $service,
        ProductRepositoryInterface $product,
        ServiceCardRepositoryInterface $serviceCard,
        CustomerRepository $customer,
        OrderRepositoryInterface $order,
        ServiceCategoryRepositoryInterface $serviceCategory,
        OrderDetailRepositoryInterface $orderDetail
    )
    {
        $this->branches = $branch;
        $this->staff = $staff;
        $this->customer = $customer;
        $this->service = $service;
        $this->product = $product;
        $this->serviceCard = $serviceCard;
        $this->order = $order;
        $this->serviceCategory = $serviceCategory;
        $this->orderDetail = $orderDetail;
    }

    public function indexAction()
    {
        $yearNow = date('Y');

        $result = $this->filterYear($yearNow);
        $branch = $this->branches->getBranch();
        return view('admin::report.report-revenue.index', [
            'branch' => $branch, 'order' => $result, 'timeOrder' => $result['timeOrder']
        ]);
    }

    public function getFilterChildAction(Request $request)
    {
        $filter = $request->filter;
        $result = null;
        switch ($filter) {
            case 'branch':
                $result = (['' => __('Tất cả')]) + $this->branches->getBranch();
                break;
            case 'staff':
                $result = (['' => __('Tất cả')]) + $this->staff->getStaffOption();
                break;
            case 'customer':
                $result = (['' => __('Tất cả')]) + $this->customer->getCustomerIdName();
                break;
        }
        return response()->json($result);
    }

    public function forGeneral($value, $dataSelect, $filter)
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

        foreach ($value as $key2 => $val2) {
            foreach ($dataSelect as $key3 => $val3) {
                if ($dataSelect[$key3][$filter] == $key2) {
                    $result['totalOrder'] += 1;
                    $result['totalMoney'] += $dataSelect[$key3]['amount'];
                    if ($dataSelect[$key3]['process_status'] == 'paysuccess') {
                        $value[$key2] += $dataSelect[$key3]['amount'];
                        $result['totalOrderPaysuccess'] += 1;
                        $result['totalMoneyOrderPaysuccess'] += $dataSelect[$key3]['amount'];
                    }
                    if ($dataSelect[$key3]['process_status'] == 'new') {
                        $result['totalOrderNew'] += 1;
                        $result['totalMoneyOrderNew'] += $dataSelect[$key3]['amount'];
                    }
                    if ($dataSelect[$key3]['process_status'] == 'payfail') {
                        $result['totalOrderPayFail'] += 1;
                        $result['totalMoneyOrderPayFail'] += $dataSelect[$key3]['amount'];
                    }
                }
            }
        }
        $result['values'] = $value;
        return $result;
    }

    public function filterAction(Request $request)
    {
        $year = $request->year;
        $filter = $request->filter;
        $filterChild = $request->filterChild;
        $startTime = $endTime = null;
        $dataSelect2 = null;
        $value = [];
        $timeDate = $request->time;
        $result = [];
        $result['totalOrder'] = 0;
        $result['totalMoney'] = 0;
        $result['totalOrderPaysuccess'] = 0;
        $result['totalMoneyOrderPaysuccess'] = 0;
        $result['totalOrderNew'] = 0;
        $result['totalMoneyOrderNew'] = 0;
        $result['totalOrderPayFail'] = 0;
        $result['totalMoneyOrderPayFail'] = 0;
        if ($timeDate != null) {
            if ($filterChild == null) {
                //Chỉ có từ ngày tới ngày (hoặc từ ngày tới ngày và loại doanh thu).
                $time = explode(" - ", $timeDate);
                $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
                $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');
                $dataSelect = $this->order->getValueByYear(null, $startTime, $endTime);
                //Số ngày.
                $datediff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
                //Danh sách ngày
                $arraTime = [];
                foreach ($dataSelect as $kk => $vv) {
                    $timee = date('Y-m-d', strtotime($dataSelect[$kk]['created_at']));
                    $arraTime[date('d/m/Y', strtotime($timee))] = $this->order->getValueByDate($timee);
                }
                for ($i = 0; $i < $datediff; $i++) {
                    $tomorrow = date('d/m/Y', strtotime($startTime . "+" . $i . " days"));
                    $value[$tomorrow] = 0;
                    foreach ($arraTime as $ii => $jj) {
                        if ($ii == $tomorrow) {
                            $result[] = $jj;
                            $value[$tomorrow] = $jj;
                        }
                    }
                }
                $day = [];
                $valueDay = [];
                foreach ($value as $jjj => $jjjj) {
                    $day[] = substr($jjj, 0, -5);
                    $valueDay[] = intval($jjjj);
                }
                $result2 = [];
                $result2['totalOrder'] = count($dataSelect);
                $result2['totalMoney'] = 0;
                $result2['totalOrderPaysuccess'] = 0;
                $result2['totalMoneyOrderPaysuccess'] = 0;
                $result2['totalOrderNew'] = 0;
                $result2['totalMoneyOrderNew'] = 0;
                $result2['totalOrderPayFail'] = 0;
                $result2['totalMoneyOrderPayFail'] = 0;
                foreach ($dataSelect as $kk1 => $kk2) {
                    $result2['totalMoney'] += $dataSelect[$kk1]['amount'];
                    if ($dataSelect[$kk1]['process_status'] == 'paysuccess') {
                        $result2['totalOrderPaysuccess'] += 1;
                        $result2['totalMoneyOrderPaysuccess'] += $dataSelect[$kk1]['amount'];
                    }
                    if ($dataSelect[$kk1]['process_status'] == 'new') {
                        $result2['totalOrderNew'] += 1;
                        $result2['totalMoneyOrderNew'] += $dataSelect[$kk1]['amount'];
                    }
                    if ($dataSelect[$kk1]['process_status'] == 'payfail') {
                        $result2['totalOrderPayFail'] += 1;
                        $result2['totalMoneyOrderPayFail'] += $dataSelect[$kk1]['amount'];
                    }
                }
                return response()->json(['day' => $day, 'valueDay' => $valueDay, 'result' => $result2]);
            } else {
                $time = explode(" - ", $request->time);
                $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
                $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');

                //Số ngày.
                $datediff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
                //Danh sách ngày
                for ($i = 0; $i < $datediff; $i++) {
                    $tomorrow = date('d/m/Y', strtotime($startTime . "+" . $i . " days"));
                    $value[$tomorrow] = 0;
                }
                switch ($filter) {
                    case 'branch':
                        foreach ($value as $kV => $vV) {
                            $timee = Carbon::createFromFormat('d/m/Y', $kV)->format('Y-m-d');
                            $valueSelect = $this->order->getValueByParameter($timee, 'branch_id', $filterChild);
                            $value[$kV] = intval($valueSelect);
                        }
                        foreach ($value as $k => $v) {
                            $result['day'][] = substr($k, 0, -5);
                            $result['valueDay'][] = $v;
                        }
                        //Lấy ra giá trị của mảng từ ngày - đến ngày + năm.
                        $dataSelect = $this->order->getValueByParameter2($startTime, $endTime, 'branch_id', $filterChild);
                        $general = $this->forGeneral($value, $dataSelect, 'branch_id');
                        $result['totalOrder'] = $general['totalOrder'];
                        $result['totalMoney'] = $general['totalMoney'];;
                        $result['totalOrderPaysuccess'] = $general['totalOrderPaysuccess'];;
                        $result['totalMoneyOrderPaysuccess'] = $general['totalMoneyOrderPaysuccess'];;
                        $result['totalOrderNew'] = $general['totalOrderNew'];;
                        $result['totalMoneyOrderNew'] = $general['totalMoneyOrderNew'];;
                        $result['totalOrderPayFail'] = $general['totalOrderPayFail'];;
                        $result['totalMoneyOrderPayFail'] = $general['totalMoneyOrderPayFail'];
                        break;
                    case 'staff':
                        foreach ($value as $kV => $vV) {
                            $timee = Carbon::createFromFormat('d/m/Y', $kV)->format('Y-m-d');
                            $valueSelect = $this->order->getValueByParameter($timee, 'created_by', $filterChild);
                            $value[$kV] = intval($valueSelect);
                        }
                        foreach ($value as $k => $v) {
                            $result['day'][] = substr($k, 0, -5);
                            $result['valueDay'][] = $v;
                        }
                        //Lấy ra giá trị của mảng từ ngày - đến ngày + năm.
                        $dataSelect = $this->order->getValueByParameter2($startTime, $endTime, 'created_by', $filterChild);
                        $general = $this->forGeneral($value, $dataSelect, 'created_by');
                        $result['totalOrder'] = $general['totalOrder'];
                        $result['totalMoney'] = $general['totalMoney'];;
                        $result['totalOrderPaysuccess'] = $general['totalOrderPaysuccess'];;
                        $result['totalMoneyOrderPaysuccess'] = $general['totalMoneyOrderPaysuccess'];;
                        $result['totalOrderNew'] = $general['totalOrderNew'];;
                        $result['totalMoneyOrderNew'] = $general['totalMoneyOrderNew'];;
                        $result['totalOrderPayFail'] = $general['totalOrderPayFail'];;
                        $result['totalMoneyOrderPayFail'] = $general['totalMoneyOrderPayFail'];
                        break;
                    case 'customer':
                        foreach ($value as $kV => $vV) {
                            $timee = Carbon::createFromFormat('d/m/Y', $kV)->format('Y-m-d');
                            $valueSelect = $this->order->getValueByParameter($timee, 'customer_id', $filterChild);
                            $value[$kV] = intval($valueSelect);
                        }
                        foreach ($value as $k => $v) {
                            $result['day'][] = substr($k, 0, -5);
                            $result['valueDay'][] = $v;
                        }
                        //Lấy ra giá trị của mảng từ ngày - đến ngày + năm.
                        $dataSelect = $this->order->getValueByParameter2($startTime, $endTime, 'customer_id', $filterChild);
                        $general = $this->forGeneral($value, $dataSelect, 'customer_id');
                        $result['totalOrder'] = $general['totalOrder'];
                        $result['totalMoney'] = $general['totalMoney'];;
                        $result['totalOrderPaysuccess'] = $general['totalOrderPaysuccess'];;
                        $result['totalMoneyOrderPaysuccess'] = $general['totalMoneyOrderPaysuccess'];;
                        $result['totalOrderNew'] = $general['totalOrderNew'];;
                        $result['totalMoneyOrderNew'] = $general['totalMoneyOrderNew'];;
                        $result['totalOrderPayFail'] = $general['totalOrderPayFail'];;
                        $result['totalMoneyOrderPayFail'] = $general['totalMoneyOrderPayFail'];
                        break;
                }
            }
            return response()->json($result);
        } else if ($filter != "" && $timeDate == null) {
            switch ($filter) {
                case 'branch':
                    if ($filterChild == null) {
                        $branch = $this->branches->getBranch();
                        $nameBranch = [];
                        $valueBranch = [];
                        $dataSelect = $this->order->getValueByYear($year);

                        foreach ($branch as $key => $val) {
                            $nameBranch[] = $val;
                            $valueBranch[$key] = 0;
                        }

                        $forGeneral = $this->forGeneral($valueBranch, $dataSelect, 'branch_id');
                        $result['totalOrder'] = $forGeneral['totalOrder'];
                        $result['totalMoney'] = $forGeneral['totalMoney'];;
                        $result['totalOrderPaysuccess'] = $forGeneral['totalOrderPaysuccess'];;
                        $result['totalMoneyOrderPaysuccess'] = $forGeneral['totalMoneyOrderPaysuccess'];;
                        $result['totalOrderNew'] = $forGeneral['totalOrderNew'];;
                        $result['totalMoneyOrderNew'] = $forGeneral['totalMoneyOrderNew'];;
                        $result['totalOrderPayFail'] = $forGeneral['totalOrderPayFail'];;
                        $result['totalMoneyOrderPayFail'] = $forGeneral['totalMoneyOrderPayFail'];
                        foreach ($valueBranch as $key2 => $val2) {
                            foreach ($dataSelect as $key3 => $val3) {
                                if ($dataSelect[$key3]['branch_id'] == $key2 && $dataSelect[$key3]['process_status'] == 'paysuccess') {
                                    $valueBranch[$key2] += $dataSelect[$key3]['amount'];
                                }
                            }
                        }
                        $valueBranch2 = [];
                        foreach ($valueBranch as $i => $j) {
                            $valueBranch2[] = $j;
                        }
                        $result['name'] = $nameBranch;
                        $result['value'] = $valueBranch2;
                    } else {
                        $branch = [];
                        foreach ($this->branches->getBranch() as $kk => $vv) {
                            if ($kk == $filterChild) {
                                $branch[$kk] = $vv;
                            }
                        }
                        $name = [];
                        $dataSelect = $this->order->getValueByYear($year);
                        foreach ($branch as $key => $val) {
                            $name[] = $val;
                            $value[$key] = 0;
                        }
                        $forGeneral = $this->forGeneral($value, $dataSelect, 'branch_id');
                        $result['totalOrder'] = $forGeneral['totalOrder'];
                        $result['totalMoney'] = $forGeneral['totalMoney'];;
                        $result['totalOrderPaysuccess'] = $forGeneral['totalOrderPaysuccess'];;
                        $result['totalMoneyOrderPaysuccess'] = $forGeneral['totalMoneyOrderPaysuccess'];;
                        $result['totalOrderNew'] = $forGeneral['totalOrderNew'];;
                        $result['totalMoneyOrderNew'] = $forGeneral['totalMoneyOrderNew'];;
                        $result['totalOrderPayFail'] = $forGeneral['totalOrderPayFail'];;
                        $result['totalMoneyOrderPayFail'] = $forGeneral['totalMoneyOrderPayFail'];
                        $valueBranch2 = [];
                        foreach ($forGeneral['values'] as $i => $j) {
                            $valueBranch2[] = $j;
                        }
                        $result['name'] = $name;
                        $result['value'] = $valueBranch2;
                    }
                    break;

                case 'staff':
                    if ($filterChild == null) {
                        $staff = $this->staff->getStaffOption();
                        $name = [];
                        $valueName = [];
                        $dataSelect = $this->order->getValueByYear($year);
                        foreach ($staff as $key => $val) {
                            $name[] = $val;
                            $valueName[$key] = 0;
                        }
                        $forGeneral = $this->forGeneral($valueName, $dataSelect, 'created_by');
                        $result['totalOrder'] = $forGeneral['totalOrder'];
                        $result['totalMoney'] = $forGeneral['totalMoney'];;
                        $result['totalOrderPaysuccess'] = $forGeneral['totalOrderPaysuccess'];;
                        $result['totalMoneyOrderPaysuccess'] = $forGeneral['totalMoneyOrderPaysuccess'];;
                        $result['totalOrderNew'] = $forGeneral['totalOrderNew'];;
                        $result['totalMoneyOrderNew'] = $forGeneral['totalMoneyOrderNew'];;
                        $result['totalOrderPayFail'] = $forGeneral['totalOrderPayFail'];;
                        $result['totalMoneyOrderPayFail'] = $forGeneral['totalMoneyOrderPayFail'];
                        foreach ($valueName as $key2 => $val2) {
                            foreach ($dataSelect as $key3 => $val3) {
                                if ($dataSelect[$key3]['created_by'] == $key2 && $dataSelect[$key3]['process_status'] == 'paysuccess') {
                                    $valueName[$key2] += $dataSelect[$key3]['amount'];
                                }
                            }
                        }
                        $value2 = [];
                        foreach ($valueName as $i => $j) {
                            $value2[] = $j;
                        }
                        $result['name'] = $name;
                        $result['value'] = $value2;
                    } else {
                        $staff = [];
                        foreach ($this->staff->getStaffOption() as $kk => $vv) {
                            if ($kk == $filterChild) {
                                $staff[$kk] = $vv;
                            }
                        }
                        $name = [];
                        $value = [];
                        $dataSelect = $this->order->getValueByYear($year);
                        foreach ($staff as $key => $val) {
                            $name[] = $val;
                            $value[$key] = 0;
                        }

                        $forGeneral = $this->forGeneral($value, $dataSelect, 'created_by');
                        $result['totalOrder'] = $forGeneral['totalOrder'];
                        $result['totalMoney'] = $forGeneral['totalMoney'];;
                        $result['totalOrderPaysuccess'] = $forGeneral['totalOrderPaysuccess'];;
                        $result['totalMoneyOrderPaysuccess'] = $forGeneral['totalMoneyOrderPaysuccess'];;
                        $result['totalOrderNew'] = $forGeneral['totalOrderNew'];;
                        $result['totalMoneyOrderNew'] = $forGeneral['totalMoneyOrderNew'];;
                        $result['totalOrderPayFail'] = $forGeneral['totalOrderPayFail'];;
                        $result['totalMoneyOrderPayFail'] = $forGeneral['totalMoneyOrderPayFail'];
                        $value2 = [];
                        foreach ($forGeneral['values'] as $i => $j) {
                            $value2[] = $j;
                        }
                        $result['name'] = $name;
                        $result['value'] = $value2;
                    }
                    break;

                case 'customer':
                    if ($filterChild == null) {
                        $customer = $this->customer->getCustomerIdName();
                        $name = [];
                        $valueName = [];
                        $dataSelect = $this->order->getValueByYear($year);
                        foreach ($customer as $key => $val) {
                            $name[] = $val;
                            $valueName[$key] = 0;
                        }
                        $forGeneral = $this->forGeneral($valueName, $dataSelect, 'customer_id');
                        $result['totalOrder'] = $forGeneral['totalOrder'];
                        $result['totalMoney'] = $forGeneral['totalMoney'];;
                        $result['totalOrderPaysuccess'] = $forGeneral['totalOrderPaysuccess'];;
                        $result['totalMoneyOrderPaysuccess'] = $forGeneral['totalMoneyOrderPaysuccess'];;
                        $result['totalOrderNew'] = $forGeneral['totalOrderNew'];;
                        $result['totalMoneyOrderNew'] = $forGeneral['totalMoneyOrderNew'];;
                        $result['totalOrderPayFail'] = $forGeneral['totalOrderPayFail'];;
                        $result['totalMoneyOrderPayFail'] = $forGeneral['totalMoneyOrderPayFail'];

                        foreach ($valueName as $key2 => $val2) {
                            foreach ($dataSelect as $key3 => $val3) {
                                if ($dataSelect[$key3]['customer_id'] == $key2 && $dataSelect[$key3]['process_status'] == 'paysuccess') {
                                    $valueName[$key2] += $dataSelect[$key3]['amount'];
                                }
                            }
                        }
                        $value2 = [];
                        foreach ($valueName as $i => $j) {
                            $value2[] = $j;
                        }
                        $result['name'] = $name;
                        $result['value'] = $value2;
                    } else {
                        $customer = [];
                        foreach ($this->customer->getCustomerIdName() as $kk => $vv) {
                            if ($kk == $filterChild) {
                                $customer[$kk] = $vv;
                            }
                        }
                        $name = [];
                        $value = [];
                        $dataSelect = $this->order->getValueByYear($year);
                        foreach ($customer as $key => $val) {
                            $name[] = $val;
                            $value[$key] = 0;
                        }

                        $forGeneral = $this->forGeneral($value, $dataSelect, 'customer_id');
                        $result['totalOrder'] = $forGeneral['totalOrder'];
                        $result['totalMoney'] = $forGeneral['totalMoney'];;
                        $result['totalOrderPaysuccess'] = $forGeneral['totalOrderPaysuccess'];;
                        $result['totalMoneyOrderPaysuccess'] = $forGeneral['totalMoneyOrderPaysuccess'];;
                        $result['totalOrderNew'] = $forGeneral['totalOrderNew'];;
                        $result['totalMoneyOrderNew'] = $forGeneral['totalMoneyOrderNew'];;
                        $result['totalOrderPayFail'] = $forGeneral['totalOrderPayFail'];;
                        $result['totalMoneyOrderPayFail'] = $forGeneral['totalMoneyOrderPayFail'];
                        $value2 = [];
                        foreach ($forGeneral['values'] as $i => $j) {
                            $value2[] = $j;
                        }
                        $result['name'] = $name;
                        $result['value'] = $value2;
                    }
                    break;
            }
            return response()->json($result);
        } else if ($year != null && $timeDate == null && $filter == null) {
            $result = $this->filterYear($year);
            return response()->json($result);
        }
    }

    private function filterGeneral($dataSelect)
    {
        $result = [];
        $result['totalOrder'] = count($dataSelect);
        $result['totalMoney'] = 0;
        $result['totalOrderPaysuccess'] = 0;
        $result['totalMoneyOrderPaysuccess'] = 0;
        $result['totalOrderNew'] = 0;
        $result['totalMoneyOrderNew'] = 0;
        $result['totalOrderPayFail'] = 0;
        $result['totalMoneyOrderPayFail'] = 0;
        foreach ($dataSelect as $key => $value) {
            foreach ($value as $k => $v) {
                if ($k == 'total') {
                    $result['totalMoney'] += $value[$k];
                }
                if ($k == 'process_status' && $value[$k] == 'paysuccess') {
                    $result['totalOrderPaysuccess'] += 1;
                    $result['totalMoneyOrderPaysuccess'] += $value['total'];
                }
                if ($k == 'process_status' && $value[$k] == 'new') {
                    $result['totalOrderNew'] += 1;
                    $result['totalMoneyOrderNew'] += $value['total'];
                }
                if ($k == 'process_status' && $value[$k] == 'payfail') {
                    $result['totalOrderPayFail'] += 1;
                    $result['totalMoneyOrderPayFail'] += $value['total'];
                }
            }
        }
        return $result;
    }

    private function filterYear($year)
    {
        $result = [];
        $order = $this->order->getValueByYear($year);
        $result['totalOrder'] = count($order);
        $result['totalMoney'] = 0;
        $result['totalOrderPaysuccess'] = 0;
        $result['totalMoneyOrderPaysuccess'] = 0;
        $result['totalOrderNew'] = 0;
        $result['totalMoneyOrderNew'] = 0;
        $result['totalOrderPayFail'] = 0;
        $result['totalMoneyOrderPayFail'] = 0;
        $timeOrder = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0];
        $yearNow = $year;
        foreach ($order as $key => $value) {
            if ($value['process_status'] == 'paysuccess') {
                $year = Carbon::createFromFormat('Y-m-d H:i:s', $value['created_at'])->year;
                $month = Carbon::createFromFormat('Y-m-d H:i:s', $value['created_at'])->month;
                foreach ($timeOrder as $mo => $total) {
                    if ($year == $yearNow && $month == $mo) {
                        $timeOrder[$mo] += $value['amount'];
                    }
                }
            }
            foreach ($value as $k => $v) {
                if ($k == 'amount') {
                    $result['totalMoney'] += $value[$k];
                }
                if ($k == 'process_status' && $value[$k] == 'paysuccess') {
                    $result['totalOrderPaysuccess'] += 1;
                    $result['totalMoneyOrderPaysuccess'] += $value['amount'];
                }
                if ($k == 'process_status' && $value[$k] == 'new') {
                    $result['totalOrderNew'] += 1;
                    $result['totalMoneyOrderNew'] += $value['amount'];
                }
                if ($k == 'process_status' && $value[$k] == 'payfail') {
                    $result['totalOrderPayFail'] += 1;
                    $result['totalMoneyOrderPayFail'] += $value['amount'];
                }
            }
        }
        $result['timeOrder'] = $timeOrder;
        return $result;
    }
}