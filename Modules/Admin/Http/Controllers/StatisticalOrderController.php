<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/16/2019
 * Time: 5:33 PM
 */

namespace Modules\Admin\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\OrderSource\OrderSourceRepositoryInterface;

class StatisticalOrderController extends Controller
{
    protected $branch;
    protected $orderDetail;
    protected $order;
    protected $orderSource;

    public function __construct(
        BranchRepositoryInterface $branch,
        OrderDetailRepositoryInterface $orderDetail,
        OrderRepositoryInterface $order,
        OrderSourceRepositoryInterface $orderSource

    )
    {
        $this->branch = $branch;
        $this->orderDetail = $orderDetail;
        $this->order = $order;
        $this->orderSource = $orderSource;
    }

    public function indexAction()
    {
        $branch = $this->branch->getBranch();
        return view('admin::statistical.order', [
            'branch' => $branch
        ]);
    }

    public function chartIndexAction(Request $request)
    {
        $year = date('Y');
        $time = $request->time;
        $startTime = $endTime = null;
        $branchOption = $this->branch->getBranch();
        $arrayOrderValue = [];
        $listBranch = [];

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        //Lấy dữ liệu cho thống kê tất cả chi nhánh.
        foreach ($branchOption as $key => $value) {
            $listBranch[] = $value;
            $dataSelectByBranch = $this->order->getValueByYearAndBranch(null, $key, $startTime, $endTime);
            $calculateOrder = $this->calculateOrder($dataSelectByBranch);
            $arrayOrderValue['total'][] = $calculateOrder['total'];
            $arrayOrderValue['paysuccess'][] = $calculateOrder['paysuccess'];
            $arrayOrderValue['new'][] = $calculateOrder['new'];
            $arrayOrderValue['payfail'][] = $calculateOrder['payfail'];
        }
        //Lấy dữ liệu chung từ ngày đến ngày của tất cả chi nhánh.
        $dataByTime = $this->order->getValueByYear(null, $startTime, $endTime);
        //Lấy dữ liệu cho biểu đồ khách hàng.
        $calculateCustomer = $this->calculateCustomer($dataByTime);
        //Lấy dữ liệu cho biểu đồ nguồn đơn hàng.
        $calculateOrderSource = $this->calculateOrderSource($dataByTime);
        //Lấy dữ liệu cho biểu đồ trạng thái đơn hàng.
        $calculateOrderStatus = $this->calculateOrderStatus($dataByTime);

        //Biểu đồ thống kê số lượng theo chi nhánh
        $dataQuantity = [];
        $dataQuantity[] = ['', __('ĐƠN HÀNG MỚI'), __('HOÀN THÀNH'), __('HỦY')];
        foreach ($listBranch as $key => $value) {
            $dataQuantity[] = [
                $value,
                $arrayOrderValue['new'][$key],
                $arrayOrderValue['paysuccess'][$key],
                $arrayOrderValue['payfail'][$key],
            ];
        }

        //Biểu đồ trạng thái
        $dataStatusChart = [];
        $dataStatusChart[] = ['', ''];
        $dataStatusChart[] = [__('Mới'), array_sum($arrayOrderValue['new'])];
        $dataStatusChart[] = [__('Hoàn thành'), array_sum($arrayOrderValue['paysuccess'])];
        $dataStatusChart[] = [__('Hủy'), array_sum($arrayOrderValue['payfail'])];

        //Biểu đồ thống kê nguồn đơn hàng
        $dataOrderSource = [];
        $dataOrderSource[] = ['', ''];
        if (count($calculateOrderSource) > 0) {
            foreach ($calculateOrderSource as $key => $value) {
                $dataOrderSource[] = [
                    $value['name'],
                    $value['value'],
                ];
            }
        } else {
            $dataOrderSource[] = [
                __('Trực tiếp'),
                array_sum($arrayOrderValue['new']) + array_sum($arrayOrderValue['paysuccess']) + array_sum($arrayOrderValue['payfail'])
            ];
        }

        return response()->json([
            'listBranch' => $listBranch,
            'total' => $arrayOrderValue['total'],
            'success' => $arrayOrderValue['paysuccess'],
            'quantityAllCustomer' => $calculateCustomer['quantityAllCustomer'],
            'quantityOddCustomer' => $calculateCustomer['quantityOddCustomer'],
            'orderSource' => $calculateOrderSource,
            'orderStatus' => $calculateOrderStatus,
            'dataQuantity' => $dataQuantity,
            'dataStatusChart' => $dataStatusChart,
            'dataOrderSource' => $dataOrderSource,
        ]);
    }

    public function filterAction(Request $request)
    {
        $time = $request->time;
        $branch = $request->branch;
        $startTime = null;
        $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        if ($time != null && $branch != null) {
            $dataSelect = $this->order->getValueByYearAndBranch(null, $branch, $startTime, $endTime);

            $day = [];
            $arrayDayValue = [];
            //Số ngày.
            $datediff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            for ($i = 0; $i < $datediff; $i++) {
                $tomorrow = date('d/m/Y', strtotime($startTime . "+" . $i . " days"));
                $day[] = substr($tomorrow, 0, -5);
                $arrayDayValue[$tomorrow] = [
                    'total' => 0,
                    'paysuccess' => 0,
                    'new' => 0,
                    'payfail' => 0,
                ];
            }

            foreach ($arrayDayValue as $key => $value) {
                foreach ($dataSelect as $key2 => $value2) {
                    $days = date('d/m/Y', strtotime($value2['created_at']));
                    if ($key == $days) {
                        $arrayDayValue[$key]['total'] += 1;
                        if ($value2['process_status'] == 'paysuccess') {
                            $arrayDayValue[$key]['paysuccess'] += 1;
                        }
                        if ($value2['process_status'] == 'new') {
                            $arrayDayValue[$key]['new'] += 1;
                        }
                        if ($value2['process_status'] == 'payfail') {
                            $arrayDayValue[$key]['payfail'] += 1;
                        }
                    }
                }
            }

            $arrayTotal = [];
            $arrayPaySuccess = [];
            $arrayPayNew = [];
            $arrayPayFail = [];
            foreach ($arrayDayValue as $key => $value) {
                $arrayTotal[] = $value['total'];
                $arrayPaySuccess[] = $value['paysuccess'];
                $arrayPayNew[] = $value['new'];
                $arrayPayFail[] = $value['payfail'];
            }

            //Lấy dữ liệu biểu đồ khách hàng.
            $calculateCustomer = $this->calculateCustomer($dataSelect);
            //Lấy dữ liệu biểu đồ nguồn đơn hàng.
            $calculateOrderSource = $this->calculateOrderSource($dataSelect);
            //Lấy dữ liệu cho biểu đồ trạng thái đơn hàng.
            $calculateOrderStatus = $this->calculateOrderStatus($dataSelect);

            //Biểu đồ thống kê số lượng theo chi nhánh
            $dataQuantity = [];
            $dataQuantity[] = ['', __('ĐƠN HÀNG MỚI'), __('HOÀN THÀNH'), __('HỦY')];

            foreach ($day as $key => $value) {
                $dataQuantity[] = [
                    $value,
                    $arrayPayNew[$key],
                    $arrayPaySuccess[$key],
                    $arrayPayFail[$key],
                ];
            }

            //Biểu đồ trạng thái
            $dataStatusChart = [];
            $dataStatusChart[] = ['', ''];
            foreach ($calculateOrderStatus as $item) {
                if ($item['name'] == 'new') {
                    $dataStatusChart[] = [__('Mới'), $item['value']];
                }
                if ($item['name'] == 'paysuccess') {
                    $dataStatusChart[] = [__('Hoàn thành'), $item['value']];
                }
                if ($item['name'] == 'payfail') {
                    $dataStatusChart[] = [__('Hủy'), $item['value']];
                }
            }

            //Biểu đồ thống kê nguồn đơn hàng
            $dataOrderSource = [];
            $dataOrderSource[] = ['', ''];
            if (count($calculateOrderSource) > 0) {
                foreach ($calculateOrderSource as $key => $value) {
                    $dataOrderSource[] = [
                        $value['name'],
                        $value['value'],
                    ];
                }
            }

            return response()->json([
                'day' => $day,
                'quantityAllCustomer' => $calculateCustomer['quantityAllCustomer'],
                'quantityOddCustomer' => $calculateCustomer['quantityOddCustomer'],
                'dataStatusChart'=>$dataStatusChart,
                'dataOrderSource'=>$dataOrderSource,
                'dataQuantity'=>$dataQuantity
            ]);
        }
    }

    private function calculateOrder($data)
    {
        $result = [];
        $result['total'] = 0;
        $result['paysuccess'] = 0;
        $result['payfail'] = 0;
        $result['new'] = 0;
        foreach ($data as $key => $value) {
            $result['total'] = count($data);
            if ($value['process_status'] == 'paysuccess') {
                $result['paysuccess'] += 1;
            }
            if ($value['process_status'] == 'payfail') {
                $result['payfail'] += 1;
            }
            if ($value['process_status'] == 'new') {
                $result['new'] += 1;
            }
        }
        return $result;
    }

    private function calculateCustomer($data)
    {
        $result = [];
        $quantityAllCustomer = 0;
        $quantityOddCustomer = 0;
        foreach ($data as $key => $value) {
            $quantityAllCustomer += 1;
            if ($value['customer_id'] == 1) {
                $quantityOddCustomer += 1;
            }
        }
        $result['quantityAllCustomer'] = $quantityAllCustomer;
        $result['quantityOddCustomer'] = $quantityOddCustomer;
        return $result;
    }

    //Hàm tính nguồn đơn hàng.
    private function calculateOrderSource($data)
    {
        $result = [];
        $arrayOrderSource = [];
        $orderSourceOption = $this->orderSource->getOption();
        foreach ($orderSourceOption as $key => $value) {
            $arrayOrderSource[$key] = 0;
        }
        foreach ($arrayOrderSource as $key => $value) {
            foreach ($data as $key2 => $value2) {
                if ($value2['order_source_id'] == $key) {
                    $arrayOrderSource[$key] += 1;
                }
            }
        }
        foreach ($arrayOrderSource as $key => $value) {
            if ($value != 0) {
                $orderSourceDetail = $this->orderSource->getItem($key);
                if ($orderSourceDetail != null) {
                    $result[] = ['name' => $orderSourceDetail->order_source_name, 'value' => $value];
                }
            }
        }
        return $result;
    }

    private function calculateOrderStatus($data)
    {
        //Lấy dữ liệu cho biểu đồ trạng thái.
        $arrayTemp = [];
        $arrayStatus = [];
        $arrayStatusResult = [];
        foreach ($data as $key => $value) {
            if (!in_array($value['process_status'], $arrayTemp)) {
                $arrayStatus[$value['process_status']] = 0;
            }
        }
        foreach ($arrayStatus as $key => $value) {
            foreach ($data as $key2 => $value2) {
                if ($value2['process_status'] == $key) {
                    $arrayStatus[$key] += 1;
                }
            }
        }
        foreach ($arrayStatus as $key => $value) {
            $arrayStatusResult[] = ['name' => $key, 'value' => $value];
        }
        return $arrayStatusResult;
    }
}