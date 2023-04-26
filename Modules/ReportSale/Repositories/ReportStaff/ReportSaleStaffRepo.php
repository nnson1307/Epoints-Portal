<?php

namespace Modules\ReportSale\Repositories\ReportStaff;

use Carbon\Carbon;
use Modules\ReportSale\Models\BranchTable;
use Modules\ReportSale\Models\CustomerDeptTable;
use Modules\ReportSale\Models\CustomerGroupsTable;
use Modules\ReportSale\Models\CustomersTable;
use Modules\ReportSale\Models\DepartmentTable;
use Modules\ReportSale\Models\OrdersTable;
use Modules\ReportSale\Models\ReceiptsTable;
use Modules\ReportSale\Models\StaffTable;

class ReportSaleStaffRepo implements ReportSaleStaffRepoInterface
{
    protected $mOrders;
    protected $mReceipts;
    protected $mBranch;
    protected $mDepartment;
    protected $mCustomerGroups;
    protected $mCustomer;

    public function __construct(
        OrdersTable         $mOrders,
        ReceiptsTable       $mReceipts,
        CustomerDeptTable   $mCustomerDept,
        BranchTable         $mBranch,
        DepartmentTable     $mDepartment,
        CustomerGroupsTable $mCustomerGroups,
        CustomersTable      $mCustomer
    )
    {
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
        $mStaff = app()->get(StaffTable::class);

        //Lấy option nhân viên
        $optionStaff = $mStaff->getOption();

        return [
            'optionStaff' => $optionStaff,
        ];
    }

    public function getTotal($request)
    {

        //Lấy tổng doanh số, tổng đơn hàng
        $totalOrders = $this->mOrders->getTotalOrderByStaff($request['time'], $request['staff']);

        //Lấy tổng doanh thu
        $totalReceipt = $this->mReceipts->getTotalReceiptByStaff($request['time'], $request['staff']);

        //Lấy tổng công nợ
        $totalCustomerDept = $this->mCustomerDept->getCustomerDeptByStaff($request['time'], $request['staff']);

        //Lây tổng đơn hàng theo trạng thái
        $totalNumberOrders = $this->mOrders->getTotalNumberOrderByStaff($request['time'], $request['staff']);

        //Lây tổng KH đăng ki
        $totalNumberCustomer = $this->mCustomer->getTotalCustomer($request['time'], $request['staff']);

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
            $totalOrder = $totalOrder + $obj['number_order'];
        }
        return [
            'totalAmount' => (float)$totalOrders->amount ?? 0,
            'totalCountOrders' => $totalOrder,
            'totalCustomer' => $totalNumberCustomer->number ?? 0,
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

        $mStaff = app()->get(StaffTable::class);
        //Lấy option nhân viên
        $optionStaff = $mStaff->getOption($request['staff']);

        $dataOrders = $this->mOrders->getChartTotalCountOrderByStaff($request['time'], $request['staff']);

        $collectionDataOrders = collect($dataOrders->toArray());
        $arrSeries = [];
        $arrDate = [];

        foreach ($optionStaff as $objStaff) {
            $dataAmount = [];
            for ($i = 0; $i <= $interval->d; $i++) {
                $dateFilter = Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dataBranch = $collectionDataOrders->where('date', $dateFilter)->where('staff_id', $objStaff['staff_id'])->first();
                if ($dataBranch != null) {
                    $dataAmount[] = (float)$dataBranch['number_order'];
                } else {
                    $dataAmount[] = 0;
                }
            }
            $arrSeries[] = [
                "name" => $objStaff['staff_name'],
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

        $mStaff = app()->get(StaffTable::class);
        //Lấy option nhân viên
        $optionStaff = $mStaff->getOption($request['staff']);

        $dataOrders = $this->mOrders->getChartTotalOrderByStaff($request['time'], $request['staff']);

        $collectionDataOrders = collect($dataOrders->toArray());
        $arrSeries = [];
        $arrDate = [];

        foreach ($optionStaff as $objStaff) {
            $dataAmount = [];
            for ($i = 0; $i <= $interval->d; $i++) {
                $dateFilter = Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dataBranch = $collectionDataOrders->where('date', $dateFilter)->where('staff_id', $objStaff['staff_id'])->first();
                if ($dataBranch != null) {
                    $dataAmount[] = (float)$dataBranch['amount'];
                } else {
                    $dataAmount[] = 0;
                }
            }
            $arrSeries[] = [
                "name" => $objStaff['staff_name'],
                "data" => $dataAmount
            ];
        }
        for ($i = 0; $i <= $interval->d; $i++) {
            $arrDate[] = Carbon::parse($startTime)->addDays($i)->format('d-m-Y');
        }

//        dd($arrDate, $arrSeries);

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

        $mStaff = app()->get(StaffTable::class);
        //Lấy option nhân viên
        $optionStaff = $mStaff->getOption($request['staff']);

        $dataReceipt = $this->mReceipts->getChartTotalReceiptByStaff($request['time'], $request['staff']);

        $collectionDataReceipt = collect($dataReceipt->toArray());
        $arrSeries = [];
        $arrDate = [];
        foreach ($optionStaff as $objStaff) {
            $dataAmount = [];
            for ($i = 0; $i <= $interval->d; $i++) {
                $dateFilter = Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dataBranch = $collectionDataReceipt->where('date', $dateFilter)->where('staff_id', $objStaff['staff_id'])->first();
                if ($dataBranch != null) {
                    $dataAmount[] = (float)$dataBranch['amount'];
                } else {
                    $dataAmount[] = 0;
                }
            }
            $arrSeries[] = [
                "name" => $objStaff['staff_name'],
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

        $mStaff = app()->get(StaffTable::class);
        //Lấy option nhân viên
        $optionStaff = $mStaff->getOption($request['staff']);

        $dataReceipt = $this->mCustomerDept->getTotalCustomerDeptByStaff($request['time'], $request['staff']);

        $collectionDataReceipt = collect($dataReceipt->toArray());
        $arrSeries = [];
        $arrDate = [];
        foreach ($optionStaff as $objStaff) {
            $dataAmount = [];
            for ($i = 0; $i <= $interval->d; $i++) {
                $dateFilter = Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dataBranch = $collectionDataReceipt->where('date', $dateFilter)->where('staff_id', $objStaff['staff_id'])->first();
                if ($dataBranch != null) {
                    $dataAmount[] = (float)$dataBranch['amount'];
                } else {
                    $dataAmount[] = 0;
                }
            }
            $arrSeries[] = [
                "name" => $objStaff['staff_name'],
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

    public function getTotalAmountByStaff($request)
    {
        $mStaff = app()->get(StaffTable::class);
        //Lấy option nhân viên
        $optionStaff = $mStaff->getOption($request['staff']);

        $arrSeries = [];
        $arrCtegories = [];
        $arrSeriesAmount = [];
        $arrSeriesReceipt = [];
        $arrSeriesDept = [];

        foreach ($optionStaff as $objStaff) {
            $arrCtegories[] = $objStaff['staff_name'];

            //Lấy tổng doanh số, tổng đơn hàng
            $totalOrders = $this->mOrders->getTotalOrderByStaff($request['time'], $objStaff['staff_id']);
            $arrSeriesAmount[] = $totalOrders != null ? (float)$totalOrders->amount : 0;

            //Lấy tổng doanh thu
            $totalReceipt = $this->mReceipts->getTotalReceiptByStaff($request['time'], $objStaff['staff_id']);
            $arrSeriesReceipt[] = $totalReceipt != null ? (float)$totalReceipt->amount : 0;

            //Lấy tổng công nợ
            $totalCustomerDept = $this->mCustomerDept->getCustomerDeptByStaff($request['time'], $objStaff['staff_id']);
            $arrSeriesDept[] = $totalCustomerDept != null ? (float)$totalCustomerDept->amount : 0;

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

    public function getTotalOrdersByStaff($request)
    {

        $totalOrders = $this->mOrders->getTotalOrderByStaffGet($request['time'], $request['staff']);

        $arrSeries = [];
        $arrDataSeries = [];
        $totalOrder = 0;
        foreach ($totalOrders as $obj) {
            $arrSeries[] = [
                "staff_name" => $obj['staff_name'],
                "total" => $obj['number_order']
            ];
            $totalOrder = $totalOrder + $obj['number_order'];
        }
        foreach ($arrSeries as $objSeries) {
            $y = $objSeries['total'] * 100 / $totalOrder;
            $arrDataSeries[] = [
                "name" => $objSeries['staff_name'],
                "y" => $y
            ];
        }
        return $arrDataSeries;
    }
}