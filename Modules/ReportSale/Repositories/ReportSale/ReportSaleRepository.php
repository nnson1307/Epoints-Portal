<?php

/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/24/2018
 * Time: 10:40 AM
 */

namespace Modules\ReportSale\Repositories\ReportSale;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\ReportSale\Models\OrdersTable;
use Modules\ReportSale\Models\ReceiptsTable;
use Modules\ReportSale\Models\CustomerDeptTable;
use Modules\ReportSale\Models\BranchTable;
use Modules\ReportSale\Models\DepartmentTable;
use Modules\ReportSale\Models\CustomersTable;
use Carbon\Carbon;

class ReportSaleRepository implements ReportSaleRepositoryInterface
{
    protected $mOrders;
    protected $mReceipts;
    protected $mBranch;
    protected $mDepartment;
    protected $mCustomer;
    protected $mCustomerDept;
    public function __construct(
        OrdersTable $mOrders,
        ReceiptsTable $mReceipts,
        CustomerDeptTable $mCustomerDept,
        BranchTable $mBranch,
        DepartmentTable $mDepartment,
        CustomersTable $mCustomer
    ) {
        $this->mOrders = $mOrders;
        $this->mReceipts = $mReceipts;
        $this->mCustomerDept = $mCustomerDept;
        $this->mBranch = $mBranch;
        $this->mDepartment = $mDepartment;
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

    public function getOption(){
        $optionBranch = $this->mBranch->getOption();
        $optionDepartment = $this->mDepartment->getOption();

        return [
            'optionBranch' => $optionBranch,
            'optionDepartment' => $optionDepartment
        ];
    }

    public function getTotal($request){

        //Lấy tổng doanh số, tổng đơn hàng
        $totalOrders = $this->mOrders->getTotalOrder($request['time'], $request['branch']);

        //Lấy tổng doanh thu
        $totalReceipt = $this->mReceipts->getTotalReceipt($request['time'], $request['branch']);

        //Lấy tổng công nợ
        $totalCustomerDept = $this->mCustomerDept->getCustomerDept($request['time'], $request['branch']);

        //Lây tổng đơn hàng theo trạng thái
        $totalNumberOrders = $this->mOrders->getTotalNumberOrder($request['time'], $request['branch']);

        //Lây tổng KH đăng ki
        $totalNumberCustomer = $this->mCustomer->getTotalCustomer($request['time'], $request['branch']);

        $totalOrder = 0;
        $totalOrderPay = 0;
        $totalOrderPayHalf = 0;
        $totalOrderNotPay = 0;
        $totalOrderCancel = 0;
        foreach ($totalNumberOrders as $obj) {
            if($obj['process_status'] == self::ORDER_PAYSUCCESS){
                $totalOrderPay = $totalOrderPay + $obj['number_order'];
            }
            if($obj['process_status'] == self::ORDER_NEW || $obj['process_status'] == self::ORDER_CONFIRMED || $obj['process_status'] == self::ORDER_PAYFAIL){
                $totalOrderNotPay = $totalOrderNotPay + $obj['number_order'];
            }
            if($obj['process_status'] == self::ORDER_CANCEL){
                $totalOrderCancel = $totalOrderCancel + $obj['number_order'];
            }
            if($obj['process_status'] == self::ORDER_PAYHALF){
                $totalOrderPayHalf = $totalOrderPayHalf + $obj['number_order'];
            }
            $totalOrder =  $totalOrder + $obj['number_order'];
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

    public function getChartTotal($request){
      
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

    public function getChartTotalCountOrder($request){
        
        $startTime = $endTime = null;
        if ($request['time'] != null) {
            $time2 = explode(" - ", $request['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        } 
        $date1 = Carbon::parse($startTime);   
        $date2 = Carbon::parse($endTime);  
        $interval = $date1->diff($date2);

        $optionBranch = $this->mBranch->getOption($request['branch']);

        $dataOrders = $this->mOrders->getChartTotalCountOrder($request['time'], $request['branch']);

        $collectionDataOrders = collect($dataOrders->toArray());
        $arrSeries = [];
        $arrDate = [];
     
        foreach ($optionBranch as $objBranch) {
            $dataAmount = [];
            for ($i=0; $i <= $interval->d; $i++) { 
                $dateFilter =  Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dataBranch =  $collectionDataOrders->where('date', $dateFilter)->where('branch_name', $objBranch['branch_name'])->first();
                if($dataBranch != null){
                    $dataAmount[] = (float)$dataBranch['number_order'];
                }else {
                    $dataAmount[] = 0;
                }
            }
            $arrSeries[] = [
                "name" => $objBranch['branch_name'],
                "data" => $dataAmount
            ];
        }
        for ($i=0; $i <= $interval->d; $i++) { 
            $arrDate[] = Carbon::parse($startTime)->addDays($i)->format('d-m-Y');
        }
        return [
            "categories" => $arrDate,
            "series" => $arrSeries,
        ];
    }

    public function getTotalAmount($request){
        $startTime = $endTime = null;
        if ($request['time'] != null) {
            $time2 = explode(" - ", $request['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        } 
        $date1 = Carbon::parse($startTime);   
        $date2 = Carbon::parse($endTime);  
        $interval = $date1->diff($date2);

        $optionBranch = $this->mBranch->getOption($request['branch']);
       
        $dataOrders = $this->mOrders->getChartTotalOrder($request['time'], $request['branch']);

        $collectionDataOrders = collect($dataOrders->toArray());
        $arrSeries = [];
        $arrDate = [];
    
        foreach ($optionBranch as $objBranch) {
            $dataAmount = [];
            for ($i=0; $i <= $interval->d; $i++) { 
                $dateFilter =  Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dataBranch =  $collectionDataOrders->where('date', $dateFilter)->where('branch_name', $objBranch['branch_name'])->first();
                if($dataBranch != null){
                    $dataAmount[] = (float)$dataBranch['amount'];
                }else {
                    $dataAmount[] = 0;
                }
            }
            $arrSeries[] = [
                "name" => $objBranch['branch_name'],
                "data" => $dataAmount
            ];
        }
        for ($i=0; $i <= $interval->d; $i++) { 
            $arrDate[] = Carbon::parse($startTime)->addDays($i)->format('d-m-Y');
        }
        return [
            "categories" => $arrDate,
            "series" => $arrSeries,
        ];
    }

    public function getTotalReceipt($request){
        $startTime = $endTime = null;
        if ($request['time'] != null) {
            $time2 = explode(" - ", $request['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        } 
        $date1 = Carbon::parse($startTime);   
        $date2 = Carbon::parse($endTime);  
        $interval = $date1->diff($date2);

        $optionBranch = $this->mBranch->getOption($request['branch']);

        $dataReceipt = $this->mReceipts->getChartTotalReceipt($request['time'], $request['branch']);

        $collectionDataReceipt = collect($dataReceipt->toArray());
        $arrSeries = [];
        $arrDate = [];
        foreach ($optionBranch as $objBranch) {
            $dataAmount = [];
            for ($i=0; $i <= $interval->d; $i++) { 
                $dateFilter =  Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dataBranch =  $collectionDataReceipt->where('date', $dateFilter)->where('branch_name', $objBranch['branch_name'])->first();
                if($dataBranch != null){
                    $dataAmount[] = (float)$dataBranch['amount'];
                }else {
                    $dataAmount[] = 0;
                }
            }
            $arrSeries[] = [
                "name" => $objBranch['branch_name'],
                "data" => $dataAmount
            ];
        }
        for ($i=0; $i <= $interval->d; $i++) { 
            $arrDate[] = Carbon::parse($startTime)->addDays($i)->format('d-m-Y');
        }
        return [
            "categories" => $arrDate,
            "series" => $arrSeries,
        ];
    }

    public function getTotalCustomerDept($request){
        $startTime = $endTime = null;
        if ($request['time'] != null) {
            $time2 = explode(" - ", $request['time']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        } 
        $date1 = Carbon::parse($startTime);   
        $date2 = Carbon::parse($endTime);  
        $interval = $date1->diff($date2);

        $optionBranch = $this->mBranch->getOption($request['branch']);

        $dataReceipt = $this->mCustomerDept->getTotalCustomerDept($request['time'], $request['branch']);

        $collectionDataReceipt = collect($dataReceipt->toArray());
        $arrSeries = [];
        $arrDate = [];
        foreach ($optionBranch as $objBranch) {
            $dataAmount = [];
            for ($i=0; $i <= $interval->d; $i++) { 
                $dateFilter =  Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dataBranch =  $collectionDataReceipt->where('date', $dateFilter)->where('branch_name', $objBranch['branch_name'])->first();
                if($dataBranch != null){
                    $dataAmount[] = (float)$dataBranch['amount'];
                }else {
                    $dataAmount[] = 0;
                }
            }
            $arrSeries[] = [
                "name" => $objBranch['branch_name'],
                "data" => $dataAmount
            ];
        }
        for ($i=0; $i <= $interval->d; $i++) { 
            $arrDate[] = Carbon::parse($startTime)->addDays($i)->format('d-m-Y');
        }
        return [
            "categories" => $arrDate,
            "series" => $arrSeries,
        ];
    }

    public function getTotalAmountByBranch($request){

        $optionBranch = $this->mBranch->getOption($request['branch']);
       
        $arrSeries = [];
        $arrCtegories = [];
        $arrSeriesAmount = [];
        $arrSeriesReceipt = [];
        $arrSeriesDept = [];
        
        foreach ($optionBranch as $objBranch) {

            $arrCtegories[] = $objBranch['branch_name'];
           
            //Lấy tổng doanh số, tổng đơn hàng
            $totalOrders = $this->mOrders->getTotalOrder($request['time'], $objBranch['branch_id']);
            $arrSeriesAmount[] = (float)$totalOrders->amount ?? 0;

            //Lấy tổng doanh thu
            $totalReceipt = $this->mReceipts->getTotalReceipt($request['time'], $objBranch['branch_id']);
            $arrSeriesReceipt[] = (float)$totalReceipt->amount ?? 0;

            //Lấy tổng công nợ
            $totalCustomerDept = $this->mCustomerDept->getCustomerDept($request['time'], $objBranch['branch_id']);
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

    public function getTotalOrdersByBranch($request){

        $totalOrders = $this->mOrders->getTotalOrderByBranch($request['time'], $request['branch']);
        $arrSeries = [];
        $arrDataSeries = [];
        $totalOrder = 0;
        foreach ($totalOrders as $obj) {
            $arrSeries[] = [
                "branch_name" => $obj['branch_name'],
                "total" => $obj['number_order']
            ];
            $totalOrder =  $totalOrder + $obj['number_order'];
        }
        foreach ($arrSeries as $objSeries) {
            $y = $objSeries['total'] * 100 / $totalOrder;
            $arrDataSeries[] = [
                "name" => $objSeries['branch_name'],
                "y" => $y
            ];
        }
        return $arrDataSeries;
    }

    /**
     * Danh sách đơn hàng
     *
     * @param array $filters
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getList($filters)
    {
        $filters['branch'] = null;
        $filters['time'] = null;
        if($filters['branch_search'] != null) {
            $filters['branch'] = $filters['branch_search'];
        }
        if($filters['time_search'] != null) {
            $filters['time'] = $filters['time_search'];
        }
        $list = $this->mOrders->getList($filters);

        return $list;
    }
}