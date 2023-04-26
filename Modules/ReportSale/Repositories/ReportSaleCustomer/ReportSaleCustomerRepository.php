<?php

/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/24/2018
 * Time: 10:40 AM
 */

namespace Modules\ReportSale\Repositories\ReportSaleCustomer;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\ReportSale\Models\OrdersTable;
use Modules\ReportSale\Models\ReceiptsTable;
use Modules\ReportSale\Models\CustomerDeptTable;
use Modules\ReportSale\Models\BranchTable;
use Modules\ReportSale\Models\DepartmentTable;
use Modules\ReportSale\Models\CustomerGroupsTable;
use Modules\ReportSale\Models\CustomersTable;
use Carbon\Carbon;

class ReportSaleCustomerRepository implements ReportSaleCustomerRepositoryInterface
{
    protected $mOrders;
    protected $mReceipts;
    protected $mBranch;
    protected $mDepartment;
    protected $mCustomerGroups;
    protected $mCustomer;
    public function __construct(
        OrdersTable $mOrders,
        ReceiptsTable $mReceipts,
        CustomerDeptTable $mCustomerDept,
        BranchTable $mBranch,
        DepartmentTable $mDepartment,
        CustomerGroupsTable $mCustomerGroups,
        CustomersTable $mCustomer
    ) {
        $this->mOrders = $mOrders;
        $this->mReceipts = $mReceipts;
        $this->mCustomerDept = $mCustomerDept;
        $this->mBranch = $mBranch;
        $this->mDepartment = $mDepartment;
        $this->mCustomerGroups = $mCustomerGroups;
        $this->mCustomer = $mCustomer;
    }
    const ORDER_NOT_CALL = "not_call";
    const ORDER_CONFIRMED = "confirmed";
    const ORDER_COMPLETED = "ordercomplete";
    const ORDER_CANCEL = "ordercancle";
    const ORDER_PAYSUCCESS = "paysuccess";
    const ORDER_PAYFAIL = "payfail";
    const ORDER_NEW = "new";
    const ORDER_PAYHALF = "pay-half";

    public function getOption()
    {
        $optionBranch = $this->mBranch->getOption();
        $optionCustomerGroups = $this->mCustomerGroups->getOption();
        return [
            'optionBranch' => $optionBranch,
            'optionCustomerGroups' => $optionCustomerGroups
        ];
    }

    public function getTotal($request)
    {

        //Lấy tổng doanh số, tổng đơn hàng
        $totalOrders = $this->mOrders->getTotalOrderByCustomer($request['time'], $request['branch'], $request['customerGroup'], $request['customerId']);

        //Lấy tổng doanh thu
        $totalReceipt = $this->mReceipts->getTotalReceiptByCustomer($request['time'], $request['branch'], $request['customerGroup'], $request['customerId']);

        //Lấy tổng công nợ
        $totalCustomerDept = $this->mCustomerDept->getCustomerDeptByCustomer($request['time'], $request['branch'], $request['customerGroup'], $request['customerId']);

        // Lây tổng đơn hàng theo trạng thái
        $totalNumberOrders = $this->mOrders->getTotalNumberOrderByCustomer($request['time'], $request['branch'], $request['customerGroup'], $request['customerId']);
        $totalOrder = 0;
        $totalOrderPay = 0;
        $totalOrderPayHalf = 0;
        $totalOrderNotPay = 0;
        $totalOrderCancel = 0;
        foreach ($totalNumberOrders as $obj) {
            if ($obj['process_status'] == self::ORDER_PAYSUCCESS) {
                $totalOrderPay = $totalOrderPay + $obj['number_order'];
            }
            if ($obj['process_status'] == self::ORDER_NEW || $obj['process_status'] == self::ORDER_CONFIRMED || $obj['process_status'] == self::ORDER_PAYFAIL) {
                $totalOrderNotPay = $totalOrderNotPay + $obj['number_order'];
            }
            if ($obj['process_status'] == self::ORDER_CANCEL) {
                $totalOrderCancel = $totalOrderCancel + $obj['number_order'];
            }
            if ($obj['process_status'] == self::ORDER_PAYHALF) {
                $totalOrderPayHalf = $totalOrderPayHalf + $obj['number_order'];
            }
            $totalOrder =  $totalOrder + $obj['number_order'];
        }
        return [
            'totalAmount' => (float)$totalOrders->amount ?? 0,
            'totalCountOrders' => $totalOrder,
            'totalReceipt' => (float)$totalReceipt->amount ?? 0,
            'totalCustomerDept' => (float)$totalCustomerDept->amount ?? 0,
            'totalOrderCancel' => $totalOrderCancel,
            'totalOrderPay' => $totalOrderPay,
            'totalOrderNotPay' => $totalOrderNotPay,
            'totalOrderPayHalf' => $totalOrderPayHalf
        ];
    }

    public function getChartTotal($request)
    {

        switch ($request['type']) {
            case 'amount':
                return $this->getTotalAmount($request);
            case 'receipt':
                return $this->getTotalReceipt($request);
            case 'dept':
                return $this->getTotalCustomerDept($request);
            default:
                return null;
        }
    }

    public function getChartTotalCountOrder($request)
    {

        $startTime = $endTime = null;
        if ($request['time'] != null) {
            $time2 = explode(" - ", $request['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $date1 = Carbon::parse($startTime);
        $date2 = Carbon::parse($endTime);
        $interval = $date1->diff($date2);

        $optionCustomerGroups = $this->mCustomerGroups->getOption($request['customerGroup']);

        $dataOrders = $this->mOrders->getChartTotalCountOrderByCustomer($request['time'], $request['branch'], $request['customerGroup'], $request['customerId']);

        $collectionDataOrders = collect($dataOrders->toArray());
        $arrSeries = [];
        $arrDate = [];

        foreach ($optionCustomerGroups as $objCustomerGroups) {
            $dataAmount = [];
            for ($i = 0; $i <= $interval->d; $i++) {
                $dateFilter =  Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dataBranch =  $collectionDataOrders->where('date', $dateFilter)->where('group_name', $objCustomerGroups['group_name'])->first();
                if ($dataBranch != null) {
                    $dataAmount[] = (float)$dataBranch['number_order'];
                } else {
                    $dataAmount[] = 0;
                }
            }
            $arrSeries[] = [
                "name" => $objCustomerGroups['group_name'],
                "data" => $dataAmount
            ];
        }
        for ($i = 0; $i <= $interval->d; $i++) {
            $arrDate[] = Carbon::parse($startTime)->addDays($i)->format('d-m-Y');
        }
        return [
            "categories" => $arrDate,
            "series" => $arrSeries,
        ];
    }

    public function getTotalAmount($request)
    {
        $startTime = $endTime = null;
        if ($request['time'] != null) {
            $time2 = explode(" - ", $request['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $date1 = Carbon::parse($startTime);
        $date2 = Carbon::parse($endTime);
        $interval = $date1->diff($date2);

        $optionCustomerGroups = $this->mCustomerGroups->getOption($request['customerGroup']);

        $dataOrders = $this->mOrders->getChartTotalOrderByCustomer($request['time'], $request['branch'], $request['customerGroup'], $request['customerId']);

        $collectionDataOrders = collect($dataOrders->toArray());
        $arrSeries = [];
        $arrDate = [];

        foreach ($optionCustomerGroups as $objCustomerGroups) {
            $dataAmount = [];
            for ($i = 0; $i <= $interval->d; $i++) {
                $dateFilter =  Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dataBranch =  $collectionDataOrders->where('date', $dateFilter)->where('group_name', $objCustomerGroups['group_name'])->first();
                if ($dataBranch != null) {
                    $dataAmount[] = (float)$dataBranch['amount'];
                } else {
                    $dataAmount[] = 0;
                }
            }
            $arrSeries[] = [
                "name" => $objCustomerGroups['group_name'],
                "data" => $dataAmount
            ];
        }
        for ($i = 0; $i <= $interval->d; $i++) {
            $arrDate[] = Carbon::parse($startTime)->addDays($i)->format('d-m-Y');
        }
        return [
            "categories" => $arrDate,
            "series" => $arrSeries,
        ];
    }

    public function getTotalReceipt($request)
    {
        $startTime = $endTime = null;
        if ($request['time'] != null) {
            $time2 = explode(" - ", $request['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $date1 = Carbon::parse($startTime);
        $date2 = Carbon::parse($endTime);
        $interval = $date1->diff($date2);

        $optionCustomerGroups = $this->mCustomerGroups->getOption($request['customerGroup']);

        $dataReceipt = $this->mReceipts->getChartTotalReceiptCustomer($request['time'], $request['branch'], $request['customerGroup'], $request['customerId']);

        $collectionDataReceipt = collect($dataReceipt->toArray());
        $arrSeries = [];
        $arrDate = [];
        foreach ($optionCustomerGroups as $objCustomerGroups) {
            $dataAmount = [];
            for ($i = 0; $i <= $interval->d; $i++) {
                $dateFilter =  Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dataBranch =  $collectionDataReceipt->where('date', $dateFilter)->where('group_name', $objCustomerGroups['group_name'])->first();
                if ($dataBranch != null) {
                    $dataAmount[] = (float)$dataBranch['amount'];
                } else {
                    $dataAmount[] = 0;
                }
            }
            $arrSeries[] = [
                "name" => $objCustomerGroups['group_name'],
                "data" => $dataAmount
            ];
        }
        for ($i = 0; $i <= $interval->d; $i++) {
            $arrDate[] = Carbon::parse($startTime)->addDays($i)->format('d-m-Y');
        }
        return [
            "categories" => $arrDate,
            "series" => $arrSeries,
        ];
    }

    public function getTotalCustomerDept($request)
    {
        $startTime = $endTime = null;
        if ($request['time'] != null) {
            $time2 = explode(" - ", $request['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $date1 = Carbon::parse($startTime);
        $date2 = Carbon::parse($endTime);
        $interval = $date1->diff($date2);

        $optionCustomerGroups = $this->mCustomerGroups->getOption($request['customerGroup']);

        $dataReceipt = $this->mCustomerDept->getTotalCustomerDeptByCustomer($request['time'], $request['branch'], $request['customerGroup'], $request['customerId']);

        $collectionDataReceipt = collect($dataReceipt->toArray());
        $arrSeries = [];
        $arrDate = [];
        foreach ($optionCustomerGroups as $objCustomerGroups) {
            $dataAmount = [];
            for ($i = 0; $i <= $interval->d; $i++) {
                $dateFilter =  Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dataBranch =  $collectionDataReceipt->where('date', $dateFilter)->where('group_name', $objCustomerGroups['group_name'])->first();
                if ($dataBranch != null) {
                    $dataAmount[] = (float)$dataBranch['amount'];
                } else {
                    $dataAmount[] = 0;
                }
            }
            $arrSeries[] = [
                "name" => $objCustomerGroups['group_name'],
                "data" => $dataAmount
            ];
        }
        for ($i = 0; $i <= $interval->d; $i++) {
            $arrDate[] = Carbon::parse($startTime)->addDays($i)->format('d-m-Y');
        }
        return [
            "categories" => $arrDate,
            "series" => $arrSeries,
        ];
    }

    public function getTotalAmountByCustomerGroup($request)
    {

        $optionCustomerGroups = $this->mCustomerGroups->getOption($request['customerGroup']);

        $arrSeries = [];
        $arrCtegories = [];
        $arrSeriesAmount = [];
        $arrSeriesReceipt = [];
        $arrSeriesDept = [];

        foreach ($optionCustomerGroups as $objCustomerGroups) {

            $arrCtegories[] = $objCustomerGroups['group_name'];

            //Lấy tổng doanh số, tổng đơn hàng
            $totalOrders = $this->mOrders->getTotalOrderByCustomer($request['time'], $request['branch'], $objCustomerGroups['customer_group_id'], $request['customerId']);
            $arrSeriesAmount[] = (float)$totalOrders->amount ?? 0;

            //Lấy tổng doanh thu
            $totalReceipt = $this->mReceipts->getTotalReceiptByCustomer($request['time'], $request['branch'], $objCustomerGroups['customer_group_id'], $request['customerId']);
            $arrSeriesReceipt[] = (float)$totalReceipt->amount ?? 0;

            //Lấy tổng công nợ
            $totalCustomerDept = $this->mCustomerDept->getCustomerDeptByCustomer($request['time'], $request['branch'], $objCustomerGroups['customer_group_id'], $request['customerId']);
            $arrSeriesDept[] = (float)$totalCustomerDept->amount ?? 0;
        }

        $arrSeries = [
            [
                "name" => __('Doanh số'),
                "data" => $arrSeriesAmount
            ],
            [
                "name" => __('Doanh thu'),
                "data" => $arrSeriesReceipt
            ],
            [
                "name" => __('Công nợ'),
                "data" => $arrSeriesDept
            ]
        ];
        return [
            "categories" => $arrCtegories,
            "series" => $arrSeries,
        ];
    }

    public function getTotalOrdersByCustomerGroup($request)
    {

        $totalOrders = $this->mOrders->getTotalOrderByCustomerGroup($request['time'], $request['branch'], $request['customer_group_id'], $request['customerId']);
        $arrSeries = [];
        $arrDataSeries = [];
        $totalOrder = 0;
        foreach ($totalOrders as $obj) {
            $arrSeries[] = [
                "group_name" => $obj['group_name'],
                "total" => $obj['number_order']
            ];
            $totalOrder =  $totalOrder + $obj['number_order'];
        }
        foreach ($arrSeries as $objSeries) {
            $y = $objSeries['total'] * 100 / $totalOrder;
            $arrDataSeries[] = [
                "name" => $objSeries['group_name'],
                "y" => $y
            ];
        }
        return $arrDataSeries;
    }
    public function getCustomer($request){
        $value = $this->mCustomer->searchCustomer($request['search'], $request['customer_group_id']);
        $search = [];
        foreach ($value as $item) {
            $search['results'][] = [
                'id' => $item['customer_id'],
                'text' => $item['full_name']
            ];

        }
        return $search;
    }
}