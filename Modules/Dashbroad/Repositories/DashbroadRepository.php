<?php

/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 09:27
 */

namespace Modules\Dashbroad\Repositories;


use Carbon\Carbon;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\CustomerGroupTable;
use Modules\Admin\Models\DepartmentTable;
use Modules\Admin\Models\ReceiptTable;
use Modules\Admin\Models\StaffsTable;
use Modules\CustomerLead\Models\CustomerSourceTable;
use Modules\CustomerLead\Models\PipelineTable;
use Modules\Dashbroad\Models\AppointmentTable;
use Modules\Dashbroad\Models\CustomerAppointmentDetailTable;
use Modules\Dashbroad\Models\CustomerTable;
use Modules\Dashbroad\Models\OrderTable;
use Modules\Dashbroad\Models\ServiceTable;
use Modules\Dashbroad\Models\CustomerRequestTable;

class DashbroadRepository implements DashbroadRepositoryInterface
{

    protected $orders;
    protected $appointment;
    protected $customer;
    protected $branch;
    protected $staff;
    protected $receipt;
    protected $mCustomerAppointmentDetail;
    protected $mService;
    protected $mCustomerRequest;

    public function __construct(
        OrderTable $orders,
        AppointmentTable $appointment,
        CustomerTable $customer,
        BranchTable $branch,
        StaffsTable $staffs,
        ReceiptTable $receipt,
        CustomerAppointmentDetailTable $mCustomerAppointmentDetail,
        ServiceTable $mService,
        CustomerRequestTable $mCustomerRequest
    ) {
        $this->orders = $orders;
        $this->appointment = $appointment;
        $this->customer = $customer;
        $this->branch = $branch;
        $this->staff = $staffs;
        $this->receipt = $receipt;
        $this->mCustomerAppointmentDetail = $mCustomerAppointmentDetail;
        $this->mService = $mService;
        $this->mCustomerRequest = $mCustomerRequest;
    }


    public function getAppointmentByDate($date)
    {

        return $this->appointment->appointmentByDate($date);
    }

    public function getOrders($status)
    {

        return $this->orders->getOrders($status);
    }

    public function getAppointment($status)
    {
        return $this->appointment->getAppointment($status);
    }

    public function getOrderbyMonthYear($month, $year)
    {

        return $this->orders->orderByMonthYear($month, $year);
    }

    public function getOrderbyDateMonth($date, $month, $year)
    {

        return $this->orders->orderByDateMonth($date, $month, $year);
    }

    public function listOrder($filter = [])
    {
        $list = $this->orders->getDataTable($filter);
        if (isset($list['data']) && count($list['data']) != 0) {
            foreach ($list['data'] as $value) {
                $branch = $this->branch->getItem($value['branch_id']);
                $staff = $this->staff->getItem($value['created_by']);
                $receipt = $this->receipt->getReceiptOrderId($value['order_id']);
                $customer = $this->customer->getItem($value['customer_id']);
                $value['staffs'] = $staff != null ? $staff['full_name'] : null;
                $value['branch_name'] = $branch != null ? $branch['branch_name'] : null;
                $value['amount_paid'] = $receipt != null ? $receipt['amount_paid'] : null;
                $value['full_name'] = $customer != null ? $customer['full_name'] : null;
            }
        }
        return $list;
    }

    public function listAppointment($filter = [])
    {

        return $this->appointment->getDataTable($filter);
    }
    public function listServices($filter = [])
    {
        $data = $this->mService->listServicesCarStill($filter);
        return $data;
    }

    public function listBirthday($filter = [])
    {
        return $this->customer->getDataTable($filter);
    }

    public function getOrderByObjectType($type, $date)
    {
        return $this->orders->getOrderByObjectType($type, $date);
    }

    public function getTopService($date)
    {
        return $this->orders->getTopService($date);
    }

    public function getTotalCustomer()
    {
        return $this->customer->getTotal();
    }
    public function getTotalCustomerOnDay()
    {
        return $this->customer->getTotalOnDay();
    }
    public function getTotalCustomerOnMonth()
    {
        return $this->customer->getTotalOnMonth();
    }

    /**
     * Lấy tổng số tiếp nhận
     */
    public function getTotalCustomerRequest()
    {
        return $this->mCustomerRequest->getTotal();
    }
    /**
     * Danh sách service đã được đặt/ service
     * @return array
     */
    public function getService()
    {
        $param = [
            'datetime' => Carbon::now()->format('Y-m-d'),
            'object_type' => 'service',
        ];
        //Service đã đặt trong ngày
        $cusAppointDetail = $this->mCustomerAppointmentDetail
            ->getServiceByCondition($param);
        //Tất cả các service
        $service = count($this->mService->getAll());
        $use = $service - count($cusAppointDetail);
        return [
            'use' => $use,
            'total' => $service,
        ];
    }

    public function dataViewIndex()
    {
        $mBranch = new BranchTable();
        $mCustomerGroup = new CustomerGroupTable();
        $mReceipt = new \Modules\Dashbroad\Models\ReceiptTable();
        $mStaff = new StaffsTable();
        $mDepartment = new DepartmentTable();
        $mCustomerSource = new CustomerSourceTable();
        $mPipeline = new PipelineTable();
        $optionBranch = $mBranch->getBranchOption();
        $optionCustomerGroup = $mCustomerGroup->getOption();
        $optionDepartment = $mDepartment->getStaffDepartmentOption();
        $optionStaff = $mStaff->getStaffOption();
        $optionCs = $mCustomerSource->getOption();
        $optionPipeline = $mPipeline->getOption('CUSTOMER');
        // lấy tổng doanh thu chi nhánh theo ngày theo người đăng nhập
        $branchId = Auth()->user()->branch_id;
        $getData = $mReceipt->getRevenueInDayByBranchId($branchId);
        $sum = 0;
        if ($getData != null) {
            $sum = $getData['total_receipt'];
        }

        return [
            'optionPipeline' => $optionPipeline,
            'optionCs' => $optionCs,
            'optionStaff' => $optionStaff,
            'optionDepartment' => $optionDepartment,
            'optionBranch' => $optionBranch,
            'optionCustomerGroup' => $optionCustomerGroup,
            'sumRevenueInDay' => $sum
        ];
    }
    /*
    * Lấy danh sách yêu cầu khách hàng
    *
    * @param $input
    * @return \Illuminate\Http\JsonResponse|mixed
    */
    public function getListCustomerRequestToDay()
    {
        return [
            'LIST' => $this->mCustomerRequest->getListToDay(),
            'optionConfigShow' => $this->mCustomerRequest->getConfigShowInfo(),
        ];
    }
}
