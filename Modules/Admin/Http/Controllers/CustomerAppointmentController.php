<?php

/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/12/2018
 * Time: 10:11 AM
 */

namespace Modules\Admin\Http\Controllers;

use App;
use App\Jobs\CheckMailJob;
use App\Jobs\SaveLogZns;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Http\Api\ApiQueue;
use Modules\Admin\Http\Api\BookingApi;
use Modules\Admin\Http\Api\LoyaltyApi;
use Modules\Admin\Http\Api\SendNotificationApi;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\ConfigStaffNotificationTable;
use Modules\Admin\Models\ConfigTable;
use Modules\Admin\Models\CustomerAppointmentDetailTable;
use Modules\Admin\Models\CustomerAppointmentLogTable;
use Modules\Admin\Models\CustomerAppointmentTable;
use Modules\Admin\Models\CustomerBranchMoneyLogTable;
use Modules\Admin\Models\CustomerBranchTable;
use Modules\Admin\Models\CustomerContactTable;
use Modules\Admin\Models\CustomerGroupTable;
use Modules\Admin\Models\CustomerServiceCardTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\DeliveryTable;
use Modules\Admin\Models\OrderConfigTabTable;
use Modules\Admin\Models\PaymentMethodTable;
use Modules\Admin\Models\PointHistoryTable;
use Modules\Admin\Models\PromotionLogTable;
use Modules\Admin\Models\ReceiptDetailTable;
use Modules\Admin\Models\ReceiptOnlineTable;
use Modules\Admin\Models\ReceiptTable;
use Modules\Admin\Models\RoomTable;
use Modules\Admin\Models\ServiceTable;
use Modules\Admin\Models\StaffNotificationDetailTable;
use Modules\Admin\Models\StaffsTable;
use Modules\Admin\Repositories\AppointmentService\AppointmentServiceRepositoryInterface;
use Modules\Admin\Repositories\AppointmentSource\AppointmentSourceRepositoryInterface;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\ConfigPrintBill\ConfigPrintBillRepositoryInterface;
use Modules\Admin\Repositories\Customer\CustomerRepository;
use Modules\Admin\Repositories\CustomerAppointment\CustomerAppointmentRepositoryInterface;
use Modules\Admin\Repositories\CustomerAppointmentDetail\CustomerAppointmentDetailRepositoryInterface;
use Modules\Admin\Repositories\CustomerAppointmentTime\CustomerAppointmentTimeRepositoryInterface;
use Modules\Admin\Repositories\CustomerBranchMoney\CustomerBranchMoneyRepositoryInterface;
use Modules\Admin\Repositories\CustomerDebt\CustomerDebtRepositoryInterface;
use Modules\Admin\Repositories\CustomerServiceCard\CustomerServiceCardRepositoryInterface;
use Modules\Admin\Repositories\InventoryOutput\InventoryOutputRepositoryInterface;
use Modules\Admin\Repositories\InventoryOutputDetail\InventoryOutputDetailRepositoryInterface;
use Modules\Admin\Repositories\Notification\NotificationRepoInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderApp\OrderAppRepo;
use Modules\Admin\Repositories\OrderApp\OrderAppRepoInterface;
use Modules\Admin\Repositories\OrderCommission\OrderCommissionRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\PrintBillLog\PrintBillLogRepositoryInterface;
use Modules\Admin\Repositories\ProductBranchPrice\ProductBranchPriceRepositoryInterface;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Admin\Repositories\ProductInventory\ProductInventoryRepositoryInterface;
use Modules\Admin\Repositories\Receipt\ReceiptRepositoryInterface;
use Modules\Admin\Repositories\ReceiptDetail\ReceiptDetailRepositoryInterface;
use Modules\Admin\Repositories\Room\RoomRepositoryInterface;
use Modules\Admin\Repositories\Service\ServiceRepositoryInterface;
use Modules\Admin\Repositories\ServiceBranchPrice\ServiceBranchPriceRepositoryInterface;
use Modules\Admin\Repositories\ServiceCard\ServiceCardRepositoryInterface;
use Modules\Admin\Repositories\ServiceCardList\ServiceCardListRepositoryInterface;
use Modules\Admin\Repositories\ServiceMaterial\ServiceMaterialRepositoryInterface;
use Modules\Admin\Repositories\SmsConfig\SmsConfigRepositoryInterface;
use Modules\Admin\Repositories\SmsLog\SmsLogRepositoryInterface;
use Modules\Admin\Repositories\SpaInfo\SpaInfoRepositoryInterface;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;
use Modules\Admin\Repositories\Voucher\VoucherRepositoryInterface;
use Modules\Admin\Repositories\Warehouse\WarehouseRepositoryInterface;

class CustomerAppointmentController extends Controller
{
    protected $customer_appointment;
    protected $customer;
    protected $staff;
    protected $room;
    protected $service;
    protected $appointment_service;
    protected $customer_appointment_time;
    protected $code;
    protected $customer_branch_money;
    protected $customer_service_card;
    protected $order;
    protected $voucher;
    protected $receipt;
    protected $order_detail;
    protected $receipt_detail;
    protected $service_card;
    protected $customer_appointment_detail;
    protected $appointment_source;
    protected $service_card_list;
    protected $service_branch_price;
    protected $smsLog;
    protected $spaInfo;
    protected $configPrintBill;
    protected $printBillLog;
    protected $inventoryOutput;
    protected $warehouse;
    protected $inventoryOutputDetail;
    protected $productInventory;
    protected $productChild;
    protected $serviceMaterial;
    protected $product_branch_price;
    protected $smsConfig;
    protected $customer_debt;
    protected $order_commission;

    public function __construct(
        CustomerAppointmentRepositoryInterface $customer_appointments,
        CustomerRepository $customers,
        StaffRepositoryInterface $staffs,
        RoomRepositoryInterface $rooms,
        ServiceRepositoryInterface $services,
        AppointmentServiceRepositoryInterface $appointment_services,
        CustomerAppointmentTimeRepositoryInterface $customer_appointment_times,
        CodeGeneratorRepositoryInterface $codes,
        CustomerBranchMoneyRepositoryInterface $customer_branch_moneys,
        CustomerServiceCardRepositoryInterface $customer_service_cards,
        OrderRepositoryInterface $orders,
        VoucherRepositoryInterface $vouchers,
        ReceiptRepositoryInterface $receipts,
        OrderDetailRepositoryInterface $order_details,
        ReceiptDetailRepositoryInterface $receipt_details,
        ServiceCardRepositoryInterface $service_cards,
        CustomerAppointmentDetailRepositoryInterface $customer_appointment_details,
        AppointmentSourceRepositoryInterface $appointment_sources,
        ServiceCardListRepositoryInterface $service_card_lists,
        ServiceBranchPriceRepositoryInterface $service_branch_prices,
        SmsLogRepositoryInterface $smsLog,
        SpaInfoRepositoryInterface $spaInfo,
        ConfigPrintBillRepositoryInterface $configPrintBill,
        PrintBillLogRepositoryInterface $printBillLog,
        InventoryOutputRepositoryInterface $inventoryOutput,
        WarehouseRepositoryInterface $warehouse,
        InventoryOutputDetailRepositoryInterface $inventoryOutputDetail,
        ProductInventoryRepositoryInterface $productInventory,
        ProductChildRepositoryInterface $productChild,
        ServiceMaterialRepositoryInterface $serviceMaterial,
        ProductBranchPriceRepositoryInterface $product_branch_prices,
        SmsConfigRepositoryInterface $smsConfig,
        CustomerDebtRepositoryInterface $customer_debt,
        OrderCommissionRepositoryInterface $order_commission
    ) {
        $this->customer_appointment = $customer_appointments;
        $this->customer = $customers;
        $this->staff = $staffs;
        $this->room = $rooms;
        $this->service = $services;
        $this->appointment_service = $appointment_services;
        $this->customer_appointment_time = $customer_appointment_times;
        $this->code = $codes;
        $this->customer_branch_money = $customer_branch_moneys;
        $this->customer_service_card = $customer_service_cards;
        $this->order = $orders;
        $this->receipt = $receipts;
        $this->voucher = $vouchers;
        $this->order_detail = $order_details;
        $this->receipt_detail = $receipt_details;
        $this->service_card = $service_cards;
        $this->customer_appointment_detail = $customer_appointment_details;
        $this->appointment_source = $appointment_sources;
        $this->service_card_list = $service_card_lists;
        $this->service_branch_price = $service_branch_prices;
        $this->smsLog = $smsLog;
        $this->spaInfo = $spaInfo;
        $this->configPrintBill = $configPrintBill;
        $this->printBillLog = $printBillLog;
        $this->inventoryOutput = $inventoryOutput;
        $this->warehouse = $warehouse;
        $this->inventoryOutputDetail = $inventoryOutputDetail;
        $this->productInventory = $productInventory;
        $this->productChild = $productChild;
        $this->serviceMaterial = $serviceMaterial;
        $this->product_branch_price = $product_branch_prices;
        $this->smsConfig = $smsConfig;
        $this->customer_debt = $customer_debt;
        $this->order_commission = $order_commission;
    }

    const LIVE = 1;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction()
    {

        $get = $this->customer_appointment->list();
        $optionSource = $this->appointment_source->getOption();

        //Lấy cấu hình đặt lịch từ ngày đến ngày
        $mConfig = app()->get(ConfigTable::class);
        $configToDate = $mConfig->getInfoByKey('booking_to_date')['value'];
        //Lấy số tuần trong năm
        $numberWeek = 52;

        return view('admin::customer-appointment.index', [
            'LIST' => $get,
            'FILTER' => $this->filters(),
            'optionSource' => $optionSource,
            'configToDate' => $configToDate,
            'numberWeek' => $numberWeek
        ]);
    }

    /**
     * @return array
     */
    protected function filters()
    {
        $time = $this->customer_appointment_time->getTimeOption();
        $group = (["" => __("Chọn khung giờ")]) + $time;
        return [
            'customer_appointments$customer_appointment_time_id' => [
                'data' => $group
            ]
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page', 'display', 'search_type', 'search_keyword',
            'search'
        ]);
        $list = $this->customer_appointment->list($filter);
        return view('admin::customer-appointment.list', ['LIST' => $list]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listCalendarAction(Request $request)
    {
        //        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword',
        //            'search']);
        //        $list = $this->customer_appointment->list($filter);
        return view(
            'admin::customer-appointment.list-calendar'
            //            , ['LIST' => $list]
        );
    }

    public function modalAddAction(Request $request)
    {
        $date = $request->date_now;
        $formatDate = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
        $day = date('d/m/Y');
        $time = date('H:i');

        return response()->json([
            'date_now' => $formatDate,
            'day_now' => $day,
            'time_now' => $time,
            'date_not_format' => $date
        ]);
    }

    /**
     * Show modal thêm lịch hẹn
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modalAddTimeLineAction(Request $request)
    {
        $date = $request->date_now;
        $day = date('d/m/Y');
        $time = date('H:i');
        //Lấy option nhân viên
        $optionStaff = $this->staff->getStaffTechnician();
        //Lấy option phòng phục vụ
        $optionRoom = $this->room->getRoomOption();
        //Lấy option dịch vụ
        $optionService = $this->service_branch_price->getOptionService(Auth()->user()->branch_id);
        //Lấy nguồn lịch hẹn
        $optionSource = $this->appointment_source->getOption();
        //Lấy nhóm khách hàng
        $mCustomerGroup = new CustomerGroupTable();
        $optionGroup = $mCustomerGroup->getOption();
        //Lấy cấu hình đặt lịch từ ngày đến ngày
        $mConfig = new ConfigTable();
        $configToDate = $mConfig->getInfoByKey('booking_to_date')['value'];
        //Lấy thông tin KH load default (Thêm lịch hẹn từ chi tiết KH)
        $customerLoad = null;
        $listMemberCard = [];

        if (isset($request->customer_id) && $request->customer_id != null) {
            $mCustomer = new CustomerTable();
            //Lấy thông tin khách hàng
            $infoCustomer = $mCustomer->getItem($request->customer_id);
            //Lấy tông tin thẻ liệu trình
            $mMemberCard = new CustomerServiceCardTable();
            $listMemberCard = $mMemberCard->getMemberCard($infoCustomer['customer_id'], Auth::user()->branch_id);

            $customerLoad = $infoCustomer;
            $listMemberCard = $listMemberCard;
        }

        $is_booking_past = 0;

        //Lấy phân quyền đặt lịch lùi
        if (
            Auth()->user()->is_admin == 1
            || in_array('is_booking_past', session('routeList'))
        ) {
            $is_booking_past = 1;
        }

        //Lấy phân quyền thay đổi chi nhánh
        $is_change_branch = 0;

        if (
            Auth()->user()->is_admin == 1
            || in_array('is_change_branch', session('routeList'))
        ) {
            $is_change_branch = 1;
        }

        //Lấy option chi nhánh
        $mBranch = app()->get(BranchTable::class);
        $optionBranch = $mBranch->getBranchOption();

        //Render view
        $html = \View::make('admin::customer-appointment.modal-add', [
            'configToDate' => $configToDate,
            'optionSource' => $optionSource,
            'date_now' => $date,
            'day_now' => $day,
            'time_now' => $time,
            'optionStaff' => $optionStaff,
            'optionRoom' => $optionRoom,
            'optionService' => $optionService,
            'customerLoad' => $customerLoad,
            'listMemberCard' => $listMemberCard,
            'is_booking_past' => $is_booking_past,
            'optionGroup' => $optionGroup,
            'is_change_branch' => $is_change_branch,
            'optionBranch' => $optionBranch
        ])->render();

        return response()->json([
            'html' => $html,
            'date_now' => $date,
            'day_now' => $day,
            'time_now' => $time,
            'is_booking_past' => $is_booking_past
        ]);
    }

    /**
     * Kiểm tra đã có lịch hẹn trong ngày chưa
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkNumberAppointmentAction(Request $request)
    {
        //Lấy ngày- giờ bắt đầu, ngày- giờ kết thúc
        $getDate = $this->_getDay([
            'time_type' => $request->time_type,
            'start_date' => $request->date,
            'start_time' => $request->time,
            'end_date' => $request->endDate,
            'end_time' => $request->endTime,
            'type_number' => $request->type_number
        ]);

        $startDate = $getDate['startDate'];
        $date_now = date('Y-m-d H:i');
        //        var_dump($getDate['startDate'], $getDate['startTime'], $getDate['endDate'], $getDate['endTime']);die();
        if ($getDate['startDateTime'] >= $date_now) {
            // check range date (= giakhang)
            if ($request->endDate != '' && $request->endTime != '') {
                $checkExistsAppointment = $this->customer_appointment->checkExistsAppointment(
                    $request->customer_id,
                    $startDate,
                    $getDate['startTime'],
                    $getDate['endDate'],
                    $getDate['endTime'],
                    'check',
                    Auth::user()->branch_id
                );
                if (count($checkExistsAppointment) > 0) {
                    return response()->json([
                        'status' => -1,
                        'message' => __('Khách hàng đã có lịch trong khung giờ được chọn'),
                    ]);
                }
            }
            // check only date (!= giakhang)
            $check_number = $this->customer_appointment->checkNumberAppointment($request->customer_id, $startDate, 'check', Auth::user()->branch_id);
            $time = [];
            foreach ($check_number as $item) {
                $time[] = date("H:i", strtotime($item['time']));
            }
            if (count($check_number) > 0) {
                return response()->json([
                    'status' => 1,
                    'message' => __('Có lịch hẹn ngày hôm nay'),
                    'number' => count($check_number),
                    'time' => $time
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => __('Hôm nay không có lịch hẹn')
                ]);
            }
        } else {
            //Cho phép đăt lịch lùi
            if (
                Auth()->user()->is_admin == 1
                || in_array('is_booking_past', session('routeList'))
            ) {
                return response()->json([
                    'status' => 0,
                    'message' => __('Hôm nay không có lịch hẹn')
                ]);
            }

            return response()->json([
                'time_error' => 1,
                'message' => __('Ngày hẹn, giờ hẹn không hợp lệ')
            ]);
        }
    }


    /**
     * Tính ngày kết thúc khi thay đổi số tuần/ tháng/ năm
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeNumberTypeAction(Request $request)
    {

        if ($request->time_type != 'R') {
            //Lấy ngày- giờ bắt đầu, ngày- giờ kết thúc
            $getDate = $this->_getDay([
                'time_type' => $request->time_type,
                'start_date' => $request->date,
                'start_time' => $request->time,
                'type_number' => $request->type_number
            ]);

            return response()->json([
                'end_date' => Carbon::createFromFormat('Y-m-d', $getDate['endDate'])->format('d/m/Y'),
                'end_time' => $getDate['endTime']
            ]);
        }
    }

    /**
     * Lấy ngày bắt đầu - kết thúc khi đặt lịch hẹn
     *
     * @param array $input
     * @return array
     */
    protected function _getDay(array $input)
    {
        $startDate = null;
        $startTime = null;
        $startDateTime = null;

        $endDate = null;
        $endTime = null;
        $endDateTime = null;
        //Số ngày cách nhau giữa 2 ngày đặt
        $diffDay = 0;
        //Số ngày đặt hẹn
        $numberDay = 0;
        //Tham số t để bik ngày KM có + thêm ngày ko (nếu ngày đặt sau start_time (config) thì tham số = 1)
        $t = 0;

        switch ($input['time_type']) {
                //Theo ngày
            case 'R':
                //Ngày bắt đầu
                $startDate = Carbon::createFromFormat('d/m/Y', $input['start_date'])->format('Y-m-d');
                $startTime = strlen($input['start_time']) == 4 ? '0' . $input['start_time'] : $input['start_time'];
                $startDateTime = Carbon::createFromFormat('d/m/Y H:i', $input['start_date'] . ' ' . $input['start_time'])->format('Y-m-d H:i');
                //Ngày kết thúc
                $endDate = $input['end_date'] != null ? Carbon::createFromFormat('d/m/Y', $input['end_date'])->format('Y-m-d') : null;
                $endTime = strlen($input['end_time']) == 4 ? '0' . $input['end_time'] : $input['end_time'];
                $endDateTime = $input['end_date'] != null ? Carbon::createFromFormat('d/m/Y H:i', $input['end_date'] . ' ' . $input['end_time'])->format('Y-m-d H:i') : null;
                //Tính số giờ cách nhau giữa 2 ngày đặt
                $dtStart = Carbon::parse($startDateTime);
                $dtEnd = Carbon::parse($endDateTime);
                $diffDay = $dtEnd->diffInDays($dtStart);

                //Lấy cấu hình khung giờ hẹn
                $mConfig = new ConfigTable();

                $rangeStart = $mConfig->getInfoByKey('start_time')['value'];
                $rangeEnd = $mConfig->getInfoByKey('end_time')['value'];

                if ($input['end_date'] != null && $startDate == $endDate) {
                    //Trong ngày trả thì tính 1 ngày
                    $numberDay = 1;

                    if ($startTime >= $rangeStart) {
                        $t = 1;
                    }
                } else if ($input['end_date'] != null && $startDate != $endDate) {
                    //Lớn hơn 1 ngày
                    $tStart = Carbon::parse($startDate);
                    $tEnd = Carbon::parse($endDate);
                    //Lấy số ngày cách nhau ko tính giờ của 2 ngày đặt
                    $diffDate = $tEnd->diffInDays($tStart);
                    $arr = [];
                    //Tính số ngày đặt hẹn
                    for ($i = 1; $i <= $diffDate; $i++) {
                        //Đặt trước khung giờ
                        if ($i == 1 && $startTime < $rangeStart) {
                            $numberDay++;
                        } else if ($i == 1 && $startTime >= $rangeStart) {
                            $t = 1;
                        }
                        //Trả xe muộn
                        if ($i == $diffDate && $endTime > $rangeEnd) {
                            $numberDay++;
                        }
                        $numberDay++;
                    }
                }

                break;
                //Theo tuần
            case 'W':
                $startDateMold = Carbon::createFromFormat('d/m/Y', $input['start_date']);
                //Ngày bắt đầu
                $startDate = $startDateMold->format('Y-m-d');
                $startTime = $input['start_time'];
                $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $startDateMold->format('Y-m-d') . ' ' . $input['start_time'])->format('Y-m-d H:i');
                //Ngày kết thúc
                $endDate = Carbon::createFromFormat('Y-m-d', $startDate)->addWeeks($input['type_number'])->format('Y-m-d');
                $endTime = $input['start_time'];
                $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $endDate . ' ' . $endTime)->format('Y-m-d H:i');
                break;
                //Theo tháng
            case 'M':
                $startDateMold = Carbon::createFromFormat('d/m/Y', $input['start_date']);
                //Ngày bắt đầu
                $startDate = $startDateMold->format('Y-m-d');
                $startTime = $input['start_time'];
                $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $startDateMold->format('Y-m-d') . ' ' . $input['start_time'])->format('Y-m-d H:i');
                //Ngày kết thúc
                $endDate = Carbon::createFromFormat('Y-m-d', $startDate)->addMonths($input['type_number'])->format('Y-m-d');
                $endTime = $input['start_time'];
                $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $endDate . ' ' . $endTime)->format('Y-m-d H:i');
                break;
                //Theo năm
            case 'Y':
                $startDateMold = Carbon::createFromFormat('d/m/Y', $input['start_date']);
                //Ngày bắt đầu
                $startDate = $startDateMold->format('Y-m-d');
                $startTime = $input['start_time'];
                $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $startDateMold->format('Y-m-d') . ' ' . $input['start_time'])->format('Y-m-d H:i');
                //Ngày kết thúc
                $endDate = Carbon::createFromFormat('Y-m-d', $startDate)->addYears($input['type_number'])->format('Y-m-d');
                $endTime = $input['start_time'];
                $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $endDate . ' ' . $endTime)->format('Y-m-d H:i');
                break;
        }

        return [
            'startDate' => $startDate,
            'startTime' => $startTime,
            'startDateTime' => $startDateTime,
            'endDate' => $endDate,
            'endTime' => $endTime,
            'endDateTime' => $endDateTime,
            'diffDay' => $diffDay,
            'numberDay' => $numberDay,
            't' => $t
        ];
    }
    /**
     * Thêm lịch hẹn
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function submitModalAddAction(Request $request)
    {
 
        DB::beginTransaction();
        try {
            $arrService = [];
            $arrStaffCharge = [];
            $service_using_name = "";
            if ($request->table_quantity != '') {
                foreach ($request->table_quantity as $key => $value) {
                    if ($value['sv'] != null) {
                        $arrStaffCharge[] = $value['staff'];

                        foreach ($value['sv'] as $k => $v) {
                            if ($value['object_type'] == 'service') {
                                $arrService[] = $v;
                                $objService = $this->service->getItem($v);

                                if ($service_using_name == "") {
                                    $service_using_name = $objService['service_name'];
                                } else {
                                    $service_using_name = $service_using_name . ', ' . $objService['service_name'];
                                }
                            }
                        }
                    }
                }
            }

            $branchId = isset($request->branch_id) && $request->branch_id != null ? $request->branch_id : Auth()->user()->branch_id;

            //Lấy ngày- giờ bắt đầu, ngày- giờ kết thúc
            $getDate = $this->_getDay([
                'time_type' => $request->time_type,
                'start_date' => $request->date,
                'start_time' => $request->time,
                'end_date' => $request->endDate,
                'end_time' => $request->endTime,
                'type_number' => $request->type_number
            ]);

            $startDate = $getDate['startDateTime'];
            $endDate = $getDate['endDate'] != null ? Carbon::createFromFormat('d/m/Y H:i', $request->endDate . ' ' . $request->endTime)
                ->format('Y-m-d H:i') : null;

            //Validate ngày theo ngày, tuần, tháng, năm
            switch ($request->time_type) {
                case 'R':
                    if ($endDate != null && $startDate >= $endDate) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Thời gian kết thúc phải lớn hơn thời gian bắt đầu'),
                        ]);
                    }

                    //Đặt từ ngày -> ngày, khoảng cách 2 ngày lớn hơn 7 thì lỗi
                    if ($endDate != null && $getDate['diffDay'] >= 7) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Thời gian hẹn không được cách nhau quá 7 ngày'),
                        ]);
                    }
                    break;
                default:
                    if ($endDate != null && $startDate >= $endDate) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Thời gian kết thúc phải lớn hơn thời gian bắt đầu'),
                        ]);
                    }

                    if ($request->type_number <= 0) {
                        return response()->json([
                            'error' => true,
                            'message' => __('The number of weeks / month / year must be greater than 0'),
                        ]);
                    }
            }

            if ($request->customer_hidden == '' || $request->customer_hidden == null) {
                $mCustomerBranch = new CustomerBranchTable();

                //Kiểm tra KH đã tồn tại chưa
                $testPhone = $this->customer->testPhone($request->phone1, 0);

                if (empty($testPhone['phone1'])) {
                    //Thêm khách hàng
                    $id_add = $this->customer->add([
                        'full_name' => $request->full_name,
                        'phone1' => $request->phone1,
                        'branch_id' => $branchId,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'gender' => 'other',
                        'customer_source_id' => 1,
                        'customer_group_id' => $request->customer_group_id
                    ]);

                    if ($id_add < 10) {
                        $id_add = '0' . $id_add;
                    }
                    //Cập nhật mã khách hàng
                    $this->customer->edit([
                        'customer_code' => 'KH_' . date('dmY') . $id_add
                    ], $id_add);
                    //Tự động insert chi nhánh và lấy customer_id ra
                    $mCustomerBranch->add([
                        'customer_id' =>  $id_add,
                        'branch_id' => Auth()->user()->branch_id
                    ]);
                } else {
                    $mConfig = new ConfigTable();
                    //Kiểm tra KH đó có ở chi nhánh này không
                    $getCustomerBranch = $mCustomerBranch->getBranchByCustomer($testPhone['customer_id'], $branchId);

                    if ($getCustomerBranch != null || Auth()->user()->is_admin == 1 || !in_array('permission-customer-branch', session('routeList'))) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Số điện thoại đã tồn tại')
                        ]);
                    }

                    //Khách hàng chưa có chi nhánh (Kiểm tra cấu hình có tự động insert chi nhánh không)
                    $getInsertBranch = $mConfig->getInfoByKey('is_insert_customer_branch')['value'];

                    if ($getInsertBranch == 1) {
                        //Tự động insert chi nhánh và lấy customer_id ra
                        $mCustomerBranch->add([
                            'customer_id' =>  $testPhone['customer_id'],
                            'branch_id' => $branchId
                        ]);

                        //Lấy id khách hàng ra để thêm lịch hẹn
                        $id_add = $testPhone['customer_id'];
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => __('Khách hàng đã tồn tại ở chi nhánh khác, bạn vui lòng liên hệ quản trị viên để cấp quyền hiển thị khách hàng này')
                        ]);
                    }
                }
            }

            $data = [
                'customer_id' => $request->customer_hidden,
                'time' => $getDate['startTime'],
                'description' => $request->description,
                'customer_appointment_type' => $request->customer_appointment_type,
                //                'customer_refer' => $request->customer_refer,
                'appointment_source_id' => $request->appointment_source_id,
                'customer_quantity' => $request->customer_quantity,
                'branch_id' => $branchId,
                'status' => $request->status,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'end_date' => $request->endDate != null ? Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d') : null,
                'end_time' => $request->endTime,
                'time_type' => $request->time_type,
                'number_start' => $request->type_number,
                'service_using_name' => $service_using_name
            ];

            $format = $getDate['startDate'];
            $data['date'] = $format;

            if ($request->customer_hidden == '') {
                $data['customer_id'] = $id_add;
            }

            $customerId = $data['customer_id'];

            //Kiểm tra ngày giờ hẹn của user đã có chưa
            $mBooking = new CustomerAppointmentTable();
            $checkBooking = $mBooking->checkDateTimeCustomer($customerId, $format, $request->time, $branchId, '');

            if ($checkBooking != null) {
                return response()->json([
                    'error' => true,
                    'message' => __('Bạn đã có lịch hẹn vào khung giờ này'),
                ]);
            }

            if ($request->endDate != '' && $request->endTime != '') {
                $mCustomerAppointment = new CustomerAppointmentTable();
                $checkAppointment = $mCustomerAppointment->checkExistsAppointmentService(
                    $getDate['startDate'],
                    $getDate['startTime'],
                    $getDate['endDate'],
                    $getDate['endTime'],
                    'check',
                    $branchId,
                    $arrService
                );
                if (count($checkAppointment) > 0) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Dịch vụ đã có lịch trong khung giờ được chọn'),
                    ]);
                }
            }

            if ($request->customer_appointment_type == 'appointment') {
                $date_now = date('Y-m-d H:i');
                $timeCheck = $getDate['startTime'];

                if (
                    Auth()->user()->is_admin == 0
                    && !in_array('is_booking_past', session('routeList'))
                ) {
                    if ($format . ' ' . $timeCheck < $date_now) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Ngày hẹn, giờ hẹn không hợp lệ')
                        ]);
                    }
                }

                $id_add_appointment = $this->customer_appointment->add($data);
                $day_code = date('dmY');
                $customer_appointment_code = 'LH_' . $day_code . $id_add_appointment;
                $data_code = [
                    'customer_appointment_code' => $customer_appointment_code
                ];
                $this->customer_appointment->edit($data_code, $id_add_appointment);
            } else {
                $id_add_appointment = $this->customer_appointment->add($data);
                $day_code = date('dmY');
                if ($id_add_appointment < 10) {
                    $id_add_appointment = '0' . $id_add_appointment;
                }

                $customer_appointment_code = 'LH_' . $day_code . $id_add_appointment;

                $data_code = [
                    'customer_appointment_code' => 'LH_' . $day_code . $id_add_appointment
                ];

                $this->customer_appointment->edit($data_code, $id_add_appointment);
            }

            $dataDetail = [];

            // service
            if ($request->table_quantity != '') {
                $mService = app()->get(ServiceTable::class);

                $total = 0;
                foreach ($request->table_quantity as $key => $value) {
                    if ($value['sv'] != null) {
                        //Nếu có data dịch vụ or thẻ liệu trình mới lưu
                        foreach ($value['sv'] as $k => $v) {
                            $price = 0;

                            if ($value['object_type'] == 'service') {
                                //Lấy giá chi nhánh sản phẩm
                                $price_branch = $this->service_branch_price->getItemByBranchIdAndServiceId($branchId, $v);

                                if ($price_branch == null) {
                                    //Lấy thông tin basic của dịch vụ
                                    $price_branch = $mService->getItem($v);
                                }

                                switch ($request->time_type) {
                                    case 'R':
                                        if ($getDate['numberDay'] == 0) {
                                            //Config ko đặt lịch từ ngày đến ngày

                                            //Lấy giá KM của dv
                                            $getPrice = $this->order->getPromotionDetail(
                                                'service',
                                                $price_branch['service_code'],
                                                $customerId,
                                                'live',
                                                1
                                            );

                                            // Nếu có nhưng promotion > giá base thì lấy giá base
                                            if ($getPrice != null && $getPrice > $price_branch['new_price']) {
                                                $getPrice = $price_branch['new_price'];
                                            }
                                            // Nếu không có promotion
                                            if ($getPrice == null) {
                                                $getPrice = $price_branch['new_price'];
                                            }

                                            $dataDetail[] = [
                                                'customer_appointment_id' => $id_add_appointment,
                                                'service_id' => $v,
                                                'staff_id' => $value['staff'],
                                                'room_id' => $value['room'],
                                                'customer_order' => $value['stt'],
                                                'created_by' => Auth::id(),
                                                'updated_by' => Auth::id(),
                                                'price' => (float)$getPrice,
                                                'object_type' => $value['object_type'],
                                                'object_id' => $v,
                                                'object_code' => $price_branch['service_code'],
                                                'object_name' => $price_branch['service_name'],
                                                'is_check_promotion' => 1
                                            ];
                                            $total += $getPrice;
                                        } else {
                                            //Config đặt lịch từ ngày -> ngày
                                            for ($i = 1; $i <= $getDate['numberDay']; $i++) {
                                                $time = '';

                                                if ($i == 1) {
                                                    $time = $getDate['t'] == 0 ? $getDate['startTime'] : '00:00';
                                                } else if ($i == $getDate['numberDay']) {
                                                    $time = $getDate['endTime'];
                                                } else {
                                                    $time = '00:00';
                                                }

                                                $dateTime = Carbon::createFromFormat('Y-m-d H:i', $getDate['startDate'] . ' ' . $time)->addDays(intval($getDate['t']) + $i - 1);

                                                //Lấy giá KM của dv
                                                $getPrice = $this->order->getPromotionDetail(
                                                    'service',
                                                    $price_branch['service_code'],
                                                    $customerId,
                                                    'live',
                                                    1,
                                                    1,
                                                    $dateTime->format('Y-m-d H:i')
                                                );

                                                // Nếu có nhưng promotion > giá base thì lấy giá base
                                                if ($getPrice != null && $getPrice > $price_branch['new_price']) {
                                                    $getPrice = $price_branch['new_price'];
                                                }
                                                // Nếu không có promotion
                                                if ($getPrice == null) {
                                                    $getPrice = $price_branch['new_price'];
                                                }

                                                $dataDetail[] = [
                                                    'customer_appointment_id' => $id_add_appointment,
                                                    'service_id' => $v,
                                                    'staff_id' => $value['staff'],
                                                    'room_id' => $value['room'],
                                                    'customer_order' => $value['stt'],
                                                    'created_by' => Auth::id(),
                                                    'updated_by' => Auth::id(),
                                                    'price' => (float)$getPrice,
                                                    'object_type' => $value['object_type'],
                                                    'object_id' => $v,
                                                    'object_code' => $price_branch['service_code'],
                                                    'object_name' => $price_branch['service_name'] . ' (' . $dateTime->format('d/m/Y H:i') . ')',
                                                    'is_check_promotion' => 0
                                                ];
                                                $total += $getPrice;
                                            }
                                        }

                                        break;
                                    case 'W':
                                        $startDateName = Carbon::createFromFormat('Y-m-d H:i', $getDate['startDateTime'])->format('d/m/Y H:i');
                                        $endDateName = $request->endDate . ' ' . $request->endTime;

                                        //Lấy giá tuần của dv
                                        $dataDetail[] = [
                                            'customer_appointment_id' => $id_add_appointment,
                                            'service_id' => $v,
                                            'staff_id' => $value['staff'],
                                            'room_id' => $value['room'],
                                            'customer_order' => $value['stt'],
                                            'created_by' => Auth::id(),
                                            'updated_by' => Auth::id(),
                                            'price' => (float)$price_branch['price_week'] * $request->type_number,
                                            'object_type' => $value['object_type'],
                                            'object_id' => $v,
                                            'object_code' => $price_branch['service_code'],
                                            'object_name' => $price_branch['service_name'] . ' (' . $startDateName . ' ->' . $endDateName . ')',
                                            'is_check_promotion' => 0
                                        ];
                                        $total += $price_branch['price_week'];
                                        break;
                                    case 'M':
                                        $startDateName = Carbon::createFromFormat('Y-m-d H:i', $getDate['startDateTime'])->format('d/m/Y H:i');
                                        $endDateName = $endDateName = $request->endDate . ' ' . $request->endTime;

                                        //Lấy giá tháng của dv
                                        $dataDetail[] = [
                                            'customer_appointment_id' => $id_add_appointment,
                                            'service_id' => $v,
                                            'staff_id' => $value['staff'],
                                            'room_id' => $value['room'],
                                            'customer_order' => $value['stt'],
                                            'created_by' => Auth::id(),
                                            'updated_by' => Auth::id(),
                                            'price' => (float)$price_branch['price_month'] * $request->type_number,
                                            'object_type' => $value['object_type'],
                                            'object_id' => $v,
                                            'object_code' => $price_branch['service_code'],
                                            'object_name' => $price_branch['service_name'] . ' (' . $startDateName . ' ->' . $endDateName . ')',
                                            'is_check_promotion' => 0
                                        ];
                                        $total += $price_branch['price_month'];
                                        break;
                                    case 'Y':
                                        $startDateName = Carbon::createFromFormat('Y-m-d H:i', $getDate['startDateTime'])->format('d/m/Y H:i');
                                        $endDateName = $endDateName = $request->endDate . ' ' . $request->endTime;

                                        //Lấy giá năm của dịch vụ
                                        $dataDetail[] = [
                                            'customer_appointment_id' => $id_add_appointment,
                                            'service_id' => $v,
                                            'staff_id' => $value['staff'],
                                            'room_id' => $value['room'],
                                            'customer_order' => $value['stt'],
                                            'created_by' => Auth::id(),
                                            'updated_by' => Auth::id(),
                                            'price' => (float)$price_branch['price_year'] * $request->type_number,
                                            'object_type' => $value['object_type'],
                                            'object_id' => $v,
                                            'object_code' => $price_branch['service_code'],
                                            'object_name' => $price_branch['service_name'] . ' (' . $startDateName . ' ->' . $endDateName . ')',
                                            'is_check_promotion' => 0
                                        ];
                                        $total += $price_branch['price_year'];
                                        break;
                                }
                            } else {
                                $dataDetail[] = [
                                    'customer_appointment_id' => $id_add_appointment,
                                    'service_id' => $v,
                                    'staff_id' => $value['staff'],
                                    'room_id' => $value['room'],
                                    'customer_order' => $value['stt'],
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
                                    'price' => (float)$price,
                                    'object_type' => $value['object_type'],
                                    'object_id' => $v,
                                    'object_code' => '',
                                    'object_name' => null,
                                    'is_check_promotion' => 0
                                ];
                            }
                        }
                    }
                }
                // Cập nhật total, amount, discount cho customer-appointment
                $data_update = [
                    'total' => $total,
                    'discount' => 0,
                    'amount' => $total
                ];
                $this->customer_appointment->edit($data_update, $id_add_appointment);
            }

            //Lưu chi tiết lịch hẹn
            $mAppointmentDetail = new CustomerAppointmentDetailTable();
            $mAppointmentDetail->insert($dataDetail);

            //Insert log lịch hẹn
            $this->insertLogAdd($id_add_appointment, $request->status);

            //Cộng điểm
            $mPlusPoint = new LoyaltyApi();
            if ($request->appointment_source_id == 1) {
                //Cộng điểm khi đặt lịch trực tiếp
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $customerId,
                    'rule_code' => 'appointment_direct',
                    'object_id' => $id_add_appointment
                ]);
            } else if ($request->appointment_source_id == 2) {
                //Cộng điểm khi đặt lịch từ facebook
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $customerId,
                    'rule_code' => 'appointment_fb',
                    'object_id' => $id_add_appointment
                ]);
            } else if ($request->appointment_source_id == 3) {
                //Cộng điểm khi đặt lịch từ zalo
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $customerId,
                    'rule_code' => 'appointment_zalo',
                    'object_id' => $id_add_appointment
                ]);
            } else if ($request->appointment_source_id == 4) {
                //Cộng điểm khi đặt lịch bằng gọi điện
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $customerId,
                    'rule_code' => 'appointment_call',
                    'object_id' => $id_add_appointment
                ]);
            } else if ($request->appointment_source_id == 5) {
                //Cộng điểm khi đặt lịch online
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $customerId,
                    'rule_code' => 'appointment_online',
                    'object_id' => $id_add_appointment
                ]);
            }

            DB::commit();

            //Send notification
            if ($request->status == 'new') {
                //Insert email log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_EMAIL_CUSTOMER,
                    'event' => 'is_event',
                    'key' => 'new_appointment',
                    'object_id' => $id_add_appointment,
                    'tenant_id' => session()->get('idTenant')
                ]);
                // Insert sms log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_SMS_CUSTOMER,
                    'key' => 'new_appointment',
                    'object_id' => $id_add_appointment,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Gửi thông báo KH
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_CUSTOMER,
                    'key' => 'appointment_W',
                    'customer_id' => $customerId,
                    'object_id' => $id_add_appointment,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Gửi thông báo NV có LH mới
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_STAFF,
                    'key' => 'appointment_W',
                    'customer_id' => $data['customer_id'],
                    'object_id' => $id_add_appointment,
                    'branch_id' => Auth()->user()->branch_id,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Lưu log ZNS
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'new_appointment',
                    'customer_id' => $customerId,
                    'object_id' => $id_add_appointment,
                    'tenant_id' => session()->get('idTenant')
                ]);
            } else if ($request->status == 'confirm') {
                //Gửi thông báo KH
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_CUSTOMER,
                    'key' => 'appointment_A',
                    'customer_id' => $customerId,
                    'object_id' => $id_add_appointment,
                    'tenant_id' => session()->get('idTenant')
                ]);
            }

            if (count($arrStaffCharge) > 0) {
                //Gửi thông báo cho nhân viên phục vụ
                $this->_sendNotifyStaffCharge($arrStaffCharge, $id_add_appointment);
            }

            return response()->json([
                'error' => false,
                'message' => __('Thêm thành công'),
                'data' => [
                    'customer_appointment_id' => $id_add_appointment,
                    'customer_appointment_code' => $customer_appointment_code,
                    'customer_phone' => $request->phone1,
                    'customer_name' => $request->full_name,
                    'service_name' => $service_using_name,
                    'datetime_appointment' => $request->date,
                    'hour_appointment' => $request->time,
                    'note' => $request->description,
                ] // data for chathub
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Thêm lịch hẹn thất bại'),
                '_message' => $e->getMessage() . $e->getLine() . $e->getFile()
            ]);
        }
    }

    /**
     * Gửi thông báo cho nhân viên phục vụ
     *
     * @param $arrayStaffId
     * @param $idAppointment
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function _sendNotifyStaffCharge($arrayStaffId, $idAppointment)
    {
        $arrayStaffId = array_unique($arrayStaffId);

        $mCustomerAppointment = app()->get(CustomerAppointmentTable::class);
        $mConfig = app()->get(ConfigStaffNotificationTable::class);
        $mNotificationDetail = app()->get(StaffNotificationDetailTable::class);
        $mApiQueue = app()->get(ApiQueue::class);

        //Lấy cấu hình thông báo
        $config = $mConfig->getInfo('appointment_staff');

        if ($config == null) {
            return '';
        }

        //Lấy thông tin lịch hẹn
        $info = $mCustomerAppointment->getInfo($idAppointment);

        $message = str_replace(
            [
                '[branch_name]',
                '[date]',
                '[time]',
                '[appointment_code]'
            ],
            [
                $info['branch_name'],
                Carbon::parse($info['date'])->format('d/m/Y'),
                Carbon::parse($info['time'])->format('H:i'),
                $info['customer_appointment_code']
            ],
            $config['message']
        );

        $content = str_replace(
            [
                '[branch_name]',
                '[date]',
                '[time]',
                '[appointment_code]'
            ],
            [
                $info['branch_name'],
                Carbon::parse($info['date'])->format('d/m/Y'),
                Carbon::parse($info['time'])->format('H:i'),
                $info['customer_appointment_code']
            ],
            $config['detail_content']
        );

        $params = str_replace(
            [
                '[:customer_appointment_id]',
                '[:user_id]',
                '[:brand_url]',
                '[:brand_name]',
                '[:brand_id]'
            ],
            [
                $info['customer_appointment_id'],
                $info['customer_id'],
                '',
                '',
                0
            ],
            $config['detail_action_params']
        );

        //Insert notification detail
        $idNotificationDetail = $mNotificationDetail->add([
            'background' => $config['detail_background'],
            'content' => $content,
            'action_name' => $config['detail_action_name'],
            'action' => $config['detail_action'],
            'action_params' => $params
        ]);

        foreach ($arrayStaffId as $v) {
            //Push notify
            $mApiQueue->functionPushNotifyStaff([
                'tenant_id' => session()->get('idTenant'),
                'staff_id' => $v,
                'title' => $config['title'],
                'message' => $message,
                'detail_id' => $idNotificationDetail,
                'avatar' => $config['avatar']
            ]);
        }
    }

    /**
     * Tự động cập nhật lịch hẹn gần nhất khi đã vượt quá số lượng lịch cho phép
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \MyCore\Api\ApiException
     */
    public function updateNumberAppointmentAction(Request $request)
    {
        $branchId = isset($request->branch_id) && $request->branch_id != null ? $request->branch_id : Auth()->user()->branch_id;

        //Lấy ngày- giờ bắt đầu, ngày- giờ kết thúc
        $getDate = $this->_getDay([
            'time_type' => $request->time_type,
            'start_date' => $request->date,
            'start_time' => $request->time,
            'end_date' => $request->endDate,
            'end_time' => $request->endTime,
            'type_number' => $request->type_number
        ]);

        $startDate = $getDate['startDateTime'];
        $endDate = $getDate['endDate'] != null ? Carbon::createFromFormat('d/m/Y H:i', $request->endDate . ' ' . $request->endTime)
            ->format('Y-m-d H:i') : null;

        //Validate ngày theo ngày, tuần, tháng, năm
        switch ($request->time_type) {
            case 'R':
                if ($endDate != null && $startDate >= $endDate) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Thời gian kết thúc phải lớn hơn thời gian bắt đầu'),
                    ]);
                }

                //Đặt từ ngày -> ngày, khoảng cách 2 ngày lớn hơn 7 thì lỗi
                if ($getDate['diffDay'] >= 7) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Thời gian hẹn không được cách nhau quá 7 ngày'),
                    ]);
                }
                break;
            default:
                if ($endDate != null && $startDate >= $endDate) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Thời gian kết thúc phải lớn hơn thời gian bắt đầu'),
                    ]);
                }

                if ($request->type_number <= 0) {
                    return response()->json([
                        'error' => true,
                        'message' => __('The number of weeks / month / year must be greater than 0'),
                    ]);
                }
        }
        $service_using_name = "";
        if ($request->table_quantity != '') {
            foreach ($request->table_quantity as $key => $value) {
                if ($value['sv'] != null) {
                    $arrStaffCharge[] = $value['staff'];

                    foreach ($value['sv'] as $k => $v) {
                        if ($value['object_type'] == 'service') {
                            $arrService[] = $v;
                            $objService = $this->service->getItem($v);

                            if ($service_using_name == "") {
                                $service_using_name = $objService['service_name'];
                            } else {
                                $service_using_name = $service_using_name . ', ' . $objService['service_name'];
                            }
                        }
                    }
                }
            }
        }
       
        $format = $getDate['startDate'];
        //Kiểm tra số lượng lịch đặt trong ngày
        $check_number = $this->customer_appointment->checkNumberAppointment($request->customer_id, $format, 'check', $branchId);

        $data = [
            'date' => $format,
            'time' => $getDate['startTime'],
            'customer_appointment_type' => $request->type,
            'status' => $request->status,
            'appointment_source_id' => $request->appointment_source_id,
            'description' => $request->description,
            'customer_quantity' => $request->customer_quantity,
            'branch_id' => $branchId,
            'end_date' => $request->endDate != null ? Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d') : null,
            'end_time' => $request->endTime,
            'time_type' => $request->time_type,
            'number_start' => $request->type_number,
            'service_using_name' => $service_using_name,
        ];

        $this->customer_appointment->edit($data, $check_number[0]['customer_appointment_id']);

        if ($request->status == 'new') {
            //Insert email log
            App\Jobs\FunctionSendNotify::dispatch([
                'type' => SEND_EMAIL_CUSTOMER,
                'event' => 'is_event',
                'key' => 'new_appointment',
                'object_id' => $check_number[0]['customer_appointment_id'],
                'tenant_id' => session()->get('idTenant')
            ]);
            //Insert sms log
            App\Jobs\FunctionSendNotify::dispatch([
                'type' => SEND_SMS_CUSTOMER,
                'key' => 'new_appointment',
                'object_id' => $check_number[0]['customer_appointment_id'],
                'tenant_id' => session()->get('idTenant')
            ]);
            //Lưu log ZNS
            App\Jobs\FunctionSendNotify::dispatch([
                'type' => SEND_ZNS_CUSTOMER,
                'key' => 'new_appointment',
                'customer_id' => $request->customer_id,
                'object_id' => $check_number[0]['customer_appointment_id'],
                'tenant_id' => session()->get('idTenant')
            ]);
            //Send notification
            if ($request->customer_id != 1) {
                //Gửi thông báo khách hàng
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_CUSTOMER,
                    'key' => 'appointment_W',
                    'customer_id' => $request->customer_id,
                    'object_id' => $check_number[0]['customer_appointment_id'],
                    'tenant_id' => session()->get('idTenant')
                ]);
            }
            //Gửi thông báo NV có LH mới
            App\Jobs\FunctionSendNotify::dispatch([
                'type' => SEND_NOTIFY_STAFF,
                'key' => 'appointment_W',
                'customer_id' => $request->customer_id,
                'object_id' => $check_number[0]['customer_appointment_id'],
                'branch_id' => $branchId,
                'tenant_id' => session()->get('idTenant')
            ]);
        }
        //Xoá lịch hẹn cũ
        $detail = $this->customer_appointment_detail->getItem($check_number[0]['customer_appointment_id']);
        foreach ($detail as $item) {
            $this->customer_appointment_detail->remove($item['customer_appointment_detail_id']);
        }

        $dataDetail = [];
        $arrStaffCharge = [];
        if ($request->table_quantity != '') {
            $total = 0;
            foreach ($request->table_quantity as $key => $value) {
                if ($value['sv'] != null) {
                    $arrStaffCharge[] = $value['staff'];

                    //Nếu có data dịch vụ or thẻ liệu trình mới lưu
                    foreach ($value['sv'] as $k => $v) {
                        $price = 0;

                        if ($value['object_type'] == 'service') {
                            //Lấy giá chi nhánh sản phẩm
                            $price_branch = $this->service_branch_price->getItemByBranchIdAndServiceId($branchId, $v);

                            switch ($request->time_type) {
                                case 'R':
                                    if ($getDate['numberDay'] == 0) {
                                        //Config ko đặt lịch từ ngày đến ngày

                                        //Lấy giá KM của dv
                                        $getPrice = $this->order->getPromotionDetail(
                                            'service',
                                            $price_branch['service_code'],
                                            $request->customer_id,
                                            'live',
                                            1
                                        );

                                        // Nếu có nhưng promotion > giá base thì lấy giá base
                                        if ($getPrice != null && $getPrice > $price_branch['new_price']) {
                                            $getPrice = $price_branch['new_price'];
                                        }
                                        // Nếu không có promotion
                                        if ($getPrice == null) {
                                            $getPrice = $price_branch['new_price'];
                                        }

                                        $dataDetail[] = [
                                            'customer_appointment_id' => $check_number[0]['customer_appointment_id'],
                                            'service_id' => $v,
                                            'staff_id' => $value['staff'],
                                            'room_id' => $value['room'],
                                            'customer_order' => $value['stt'],
                                            'created_by' => Auth::id(),
                                            'updated_by' => Auth::id(),
                                            'price' => (float)$getPrice,
                                            'object_type' => $value['object_type'],
                                            'object_id' => $v,
                                            'object_code' => $price_branch['service_code'],
                                            'object_name' => $price_branch['service_name'],
                                            'is_check_promotion' => 1
                                        ];
                                        $total += $getPrice;
                                    } else {
                                        //Config đặt lịch từ ngày -> ngày
                                        for ($i = 1; $i <= $getDate['numberDay']; $i++) {
                                            $time = '';

                                            if ($i == 1) {
                                                $time = $getDate['t'] == 0 ? $getDate['startTime'] : '00:00';
                                            } else if ($i == $getDate['numberDay']) {
                                                $time = $getDate['endTime'];
                                            } else {
                                                $time = '00:00';
                                            }

                                            $dateTime = Carbon::createFromFormat('Y-m-d H:i', $getDate['startDate'] . ' ' . $time)->addDays(intval($getDate['t']) + $i - 1)->format('Y-m-d H:i');

                                            //Lấy giá KM của dv
                                            $getPrice = $this->order->getPromotionDetail(
                                                'service',
                                                $price_branch['service_code'],
                                                $request->customer_id,
                                                'live',
                                                1,
                                                1,
                                                $dateTime
                                            );

                                            // Nếu có nhưng promotion > giá base thì lấy giá base
                                            if ($getPrice != null && $getPrice > $price_branch['new_price']) {
                                                $getPrice = $price_branch['new_price'];
                                            }
                                            // Nếu không có promotion
                                            if ($getPrice == null) {
                                                $getPrice = $price_branch['new_price'];
                                            }

                                            $dataDetail[] = [
                                                'customer_appointment_id' => $check_number[0]['customer_appointment_id'],
                                                'service_id' => $v,
                                                'staff_id' => $value['staff'],
                                                'room_id' => $value['room'],
                                                'customer_order' => $value['stt'],
                                                'created_by' => Auth::id(),
                                                'updated_by' => Auth::id(),
                                                'price' => (float)$getPrice,
                                                'object_type' => $value['object_type'],
                                                'object_id' => $v,
                                                'object_code' => $price_branch['service_code'],
                                                'object_name' => $price_branch['service_name'] . ' (' . $dateTime . ')',
                                                'is_check_promotion' => 0
                                            ];
                                            $total += $getPrice;
                                        }
                                    }

                                    break;
                                case 'W':
                                    $startDateName = Carbon::createFromFormat('Y-m-d H:i', $getDate['startDateTime'])->format('d/m/Y H:i');
                                    $endDateName = $endDateName = $request->endDate . ' ' . $request->endTime;

                                    //Lấy giá tuần của dv
                                    $dataDetail[] = [
                                        'customer_appointment_id' => $check_number[0]['customer_appointment_id'],
                                        'service_id' => $v,
                                        'staff_id' => $value['staff'],
                                        'room_id' => $value['room'],
                                        'customer_order' => $value['stt'],
                                        'created_by' => Auth::id(),
                                        'updated_by' => Auth::id(),
                                        'price' => (float)$price_branch['price_week'] * $request->type_number,
                                        'object_type' => $value['object_type'],
                                        'object_id' => $v,
                                        'object_code' => $price_branch['service_code'],
                                        'object_name' => $price_branch['service_name'] . ' (' . $startDateName . ' ->' . $endDateName . ')',
                                        'is_check_promotion' => 0
                                    ];
                                    $total += $price_branch['price_week'];
                                    break;
                                case 'M':
                                    $startDateName = Carbon::createFromFormat('Y-m-d H:i', $getDate['startDateTime'])->format('d/m/Y H:i');
                                    $endDateName = $endDateName = $request->endDate . ' ' . $request->endTime;

                                    //Lấy giá tháng của dv
                                    $dataDetail[] = [
                                        'customer_appointment_id' => $check_number[0]['customer_appointment_id'],
                                        'service_id' => $v,
                                        'staff_id' => $value['staff'],
                                        'room_id' => $value['room'],
                                        'customer_order' => $value['stt'],
                                        'created_by' => Auth::id(),
                                        'updated_by' => Auth::id(),
                                        'price' => (float)$price_branch['price_month'] * $request->type_number,
                                        'object_type' => $value['object_type'],
                                        'object_id' => $v,
                                        'object_code' => $price_branch['service_code'],
                                        'object_name' => $price_branch['service_name'] . ' (' . $startDateName . ' ->' . $endDateName . ')',
                                        'is_check_promotion' => 0
                                    ];
                                    $total += $price_branch['price_month'];
                                    break;
                                case 'Y':
                                    $startDateName = Carbon::createFromFormat('Y-m-d H:i', $getDate['startDateTime'])->format('d/m/Y H:i');
                                    $endDateName = $endDateName = $request->endDate . ' ' . $request->endTime;

                                    //Lấy giá năm của dịch vụ
                                    $dataDetail[] = [
                                        'customer_appointment_id' => $check_number[0]['customer_appointment_id'],
                                        'service_id' => $v,
                                        'staff_id' => $value['staff'],
                                        'room_id' => $value['room'],
                                        'customer_order' => $value['stt'],
                                        'created_by' => Auth::id(),
                                        'updated_by' => Auth::id(),
                                        'price' => (float)$price_branch['price_year'] * $request->type_number,
                                        'object_type' => $value['object_type'],
                                        'object_id' => $v,
                                        'object_code' => $price_branch['service_code'],
                                        'object_name' => $price_branch['service_name'] . ' (' . $startDateName . ' ->' . $endDateName . ')',
                                        'is_check_promotion' => 0
                                    ];
                                    $total += $price_branch['price_year'];
                                    break;
                            }
                           
                        } else {
                            $dataDetail[] = [
                                'customer_appointment_id' => $check_number[0]['customer_appointment_id'],
                                'service_id' => $v,
                                'staff_id' => $value['staff'],
                                'room_id' => $value['room'],
                                'customer_order' => $value['stt'],
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                                'price' => (float)$price,
                                'object_type' => $value['object_type'],
                                'object_id' => $v,
                                'object_code' => '',
                                'object_name' => null,
                                'is_check_promotion' => 0
                            ];
                        }
                    }
                }
            }
            // Cập nhật total, amount, discount cho customer-appointment
            $data_update = [
                'total' => $total,
                'discount' => 0,
                'amount' => $total,
            ];
            $this->customer_appointment->edit($data_update, $check_number[0]['customer_appointment_id']);
           
        }

        //Lưu chi tiết lịch hẹn
        $mAppointmentDetail = new CustomerAppointmentDetailTable();
        $mAppointmentDetail->insert($dataDetail);

        if (count($arrStaffCharge) > 0) {
            //Gửi thông báo cho nhân viên phục vụ
            $this->_sendNotifyStaffCharge($arrStaffCharge, $check_number[0]['customer_appointment_id']);
        }

        return response()->json([
            'error' => false,
            'message' => __('Cập nhật thành công'),
            'data' => [
                'customer_appointment_id' => $$check_number[0]['customer_appointment_id'],
                'customer_appointment_code' => $customer_appointment_code,
                'customer_phone' => $request->phone1,
                'customer_name' => $request->full_name,
                'service_name' => $service_using_name,
                'datetime_appointment' => $request->date,
                'hour_appointment' => $request->time,
                'note' => $request->description,
            ] // data for chathub
        ]);
    }

    /**
     * Chỉnh sửa lịch hẹn
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitEditModalAction(Request $request)
    {
        DB::beginTransaction();
        try {
            //Lấy thông tin LH cũ
            //            $mAppointment = new CustomerAppointmentTable();
            //            $getInfo = $mAppointment->getInfo($request->customer_appointment_id);
            //Lấy ngày- giờ bắt đầu, ngày- giờ kết thúc
            $getDate = $this->_getDay([
                'time_type' => $request->time_type,
                'start_date' => $request->date,
                'start_time' => $request->time,
                'end_date' => $request->endDate,
                'end_time' => $request->endTime,
                'type_number' => $request->type_number
            ]);

            $branchId = isset($request->branch_id) && $request->branch_id != null ? $request->branch_id : Auth()->user()->branch_id;

            $startDate = $getDate['startDateTime'];
            $endDate = $getDate['endDate'] != null ? Carbon::createFromFormat('d/m/Y H:i', $request->endDate . ' ' . $request->endTime)
                ->format('Y-m-d H:i') : null;
            //Validate ngày theo ngày, tuần, tháng, năm
            switch ($request->time_type) {
                case 'R':
                    if ($endDate != null && $startDate >= $endDate) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Thời gian kết thúc phải lớn hơn thời gian bắt đầu'),
                        ]);
                    }

                    //Đặt từ ngày -> ngày, khoảng cách 2 ngày lớn hơn 7 thì lỗi
                    if ($endDate != null && $getDate['diffDay'] >= 7) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Thời gian hẹn không được cách nhau quá 7 ngày'),
                        ]);
                    }
                    break;
                default:
                    if ($endDate != null && $startDate >= $endDate) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Thời gian kết thúc phải lớn hơn thời gian bắt đầu'),
                        ]);
                    }

                    if ($request->type_number <= 0) {
                        return response()->json([
                            'error' => true,
                            'message' => __('The number of weeks / month / year must be greater than 0'),
                        ]);
                    }
            }

            $id = $request->customer_appointment_id;

            $format = $getDate['startDate'];
            //Insert log lịch hẹn
            $this->insertLogEdit($id, $request->status);
            //Data update lịch hẹn
            $data = [
                'customer_quantity' => $request->customer_quantity,
                'status' => $request->status,
                'description' => $request->description,
                'updated_by' => Auth::id(),
                'end_date' => $request->endDate != null ? Carbon::createFromFormat('d/m/Y', $request->endDate)->format('Y-m-d') : null,
                'end_time' => $request->endTime,
                'time_type' => $request->time_type,
                'number_start' => $request->type_number
            ];
            $data['date'] = $format;
            $data['time'] = $getDate['startTime'];

            if (isset($request->branch_id) && $request->branch_id != null) {
                $data['branch_id'] = $branchId;
            }

            //Kiểm tra ngày giờ hẹn của user đã có chưa
            $mBooking = new CustomerAppointmentTable();
            $checkBooking = $mBooking->checkDateTimeCustomer($request->customer_id, $format, $data['time'], $branchId, $id);
            if ($checkBooking != null && $request->time_type == 'R') {
                return response()->json([
                    'error' => false,
                    'message' => __('Bạn đã có lịch hẹn vào khung giờ này'),
                ]);
            }
            $service_using_name = "";
            if ($request->endDate != '' && $request->endTime != '') {
                $arrService = [];
                if ($request->table_quantity != '') {
                    foreach ($request->table_quantity as $key => $value) {
                        if ($value['sv'] != null) {

                            foreach ($value['sv'] as $k => $v) {
                                if ($value['object_type'] == 'service') {
                                    $arrService[] = $v;
                                    $objService = $this->service->getItem($v);

                                    if ($service_using_name == "") {
                                        $service_using_name = $objService['service_name'];
                                    } else {
                                        $service_using_name = $service_using_name . ', ' . $objService['service_name'];
                                    }
                                }
                            }
                        }
                    }
                }
                $mCustomerAppointment = new CustomerAppointmentTable();
                $checkAppointment = $mCustomerAppointment->checkExistsAppointmentService(
                    $getDate['startDate'],
                    $getDate['startTime'],
                    $getDate['endDate'],
                    $getDate['endTime'],
                    'update',
                    $branchId,
                    $arrService,
                    $id
                );
                if (count($checkAppointment) > 0) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Dịch vụ đã có lịch trong khung giờ được chọn'),
                    ]);
                }
            }
            //Update lịch hẹn
            $data['service_using_name'] = $service_using_name;

            $this->customer_appointment->edit($data, $id);

            //Lấy  chi tiết lịch hẹn
            $detail = $this->customer_appointment_detail->getItem($id);
            foreach ($detail as $item) {
                //Xóa chi tiết lịch hẹn cũ
                $this->customer_appointment_detail->remove($item['customer_appointment_detail_id']);
            }
            $dataDetail = [];
            $arrStaffCharge = [];

            if ($request->table_quantity != '') {

                // customer loop
                $total = 0;
                foreach ($request->table_quantity as $key => $value) {
                    if ($value['sv'] != null) {
                        if ($value['staff'] != $value['staff_old']) {
                            $arrStaffCharge[] = $value['staff'];
                        }

                        //Nếu có data dịch vụ or thẻ liệu trình mới lưu
                        foreach ($value['sv'] as $k => $v) {
                            $price = 0;

                            if ($value['object_type'] == 'service') {
                                //Lấy giá chi nhánh sản phẩm
                                $price_branch = $this->service_branch_price->getItemByBranchIdAndServiceId($branchId, $v);

                                switch ($request->time_type) {
                                    case 'R':
                                        if ($getDate['numberDay'] == 0) {
                                            //Config ko đặt lịch từ ngày đến ngày

                                            //Lấy giá KM của dv
                                            $getPrice = $this->order->getPromotionDetail(
                                                'service',
                                                $price_branch['service_code'],
                                                $request->customer_id,
                                                'live',
                                                1
                                            );

                                            // Nếu có nhưng promotion > giá base thì lấy giá base
                                            if ($getPrice != null && $getPrice > $price_branch['new_price']) {
                                                $getPrice = $price_branch['new_price'];
                                            }
                                            // Nếu không có promotion
                                            if ($getPrice == null) {
                                                $getPrice = $price_branch['new_price'];
                                            }

                                            $dataDetail[] = [
                                                'customer_appointment_id' => $id,
                                                'service_id' => $v,
                                                'staff_id' => $value['staff'],
                                                'room_id' => $value['room'],
                                                'customer_order' => $value['stt'],
                                                'created_by' => Auth::id(),
                                                'updated_by' => Auth::id(),
                                                'price' => (float)$getPrice,
                                                'object_type' => $value['object_type'],
                                                'object_id' => $v,
                                                'object_code' => $price_branch['service_code'],
                                                'object_name' => $price_branch['service_name'],
                                                'is_check_promotion' => 1
                                            ];
                                            $total += $getPrice;
                                        } else {
                                            //Config đặt lịch từ ngày -> ngày
                                            for ($i = 1; $i <= $getDate['numberDay']; $i++) {
                                                $time = '';

                                                if ($i == 1) {
                                                    $time = $getDate['t'] == 0 ? $getDate['startTime'] : '00:00';
                                                } else if ($i == $getDate['numberDay']) {
                                                    $time = $getDate['endTime'];
                                                } else {
                                                    $time = '00:00';
                                                }

                                                $dateTime = Carbon::createFromFormat('Y-m-d H:i', $getDate['startDate'] . ' ' . $time)->addDays(intval($getDate['t']) + $i - 1)->format('Y-m-d H:i');

                                                //Lấy giá KM của dv
                                                $getPrice = $this->order->getPromotionDetail(
                                                    'service',
                                                    $price_branch['service_code'],
                                                    $request->customer_id,
                                                    'live',
                                                    1,
                                                    1,
                                                    $dateTime
                                                );

                                                // Nếu có nhưng promotion > giá base thì lấy giá base
                                                if ($getPrice != null && $getPrice > $price_branch['new_price']) {
                                                    $getPrice = $price_branch['new_price'];
                                                }
                                                // Nếu không có promotion
                                                if ($getPrice == null) {
                                                    $getPrice = $price_branch['new_price'];
                                                }

                                                $dataDetail[] = [
                                                    'customer_appointment_id' => $id,
                                                    'service_id' => $v,
                                                    'staff_id' => $value['staff'],
                                                    'room_id' => $value['room'],
                                                    'customer_order' => $value['stt'],
                                                    'created_by' => Auth::id(),
                                                    'updated_by' => Auth::id(),
                                                    'price' => (float)$getPrice,
                                                    'object_type' => $value['object_type'],
                                                    'object_id' => $v,
                                                    'object_code' => $price_branch['service_code'],
                                                    'object_name' => $price_branch['service_name'] . ' (' . $dateTime . ')',
                                                    'is_check_promotion' => 0
                                                ];
                                                $total += $getPrice;
                                            }
                                        }

                                        break;
                                    case 'W':
                                        $startDateName = Carbon::createFromFormat('Y-m-d H:i', $getDate['startDateTime'])->format('d/m/Y H:i');
                                        $endDateName = $endDateName = $request->endDate . ' ' . $request->endTime;

                                        //Lấy giá tuần của dv
                                        $dataDetail[] = [
                                            'customer_appointment_id' => $id,
                                            'service_id' => $v,
                                            'staff_id' => $value['staff'],
                                            'room_id' => $value['room'],
                                            'customer_order' => $value['stt'],
                                            'created_by' => Auth::id(),
                                            'updated_by' => Auth::id(),
                                            'price' => (float)$price_branch['price_week'] * $request->type_number,
                                            'object_type' => $value['object_type'],
                                            'object_id' => $v,
                                            'object_code' => $price_branch['service_code'],
                                            'object_name' => $price_branch['service_name'] . ' (' . $startDateName . ' ->' . $endDateName . ')',
                                            'is_check_promotion' => 0
                                        ];
                                        $total += $price_branch['price_week'];
                                        break;
                                    case 'M':
                                        $startDateName = Carbon::createFromFormat('Y-m-d H:i', $getDate['startDateTime'])->format('d/m/Y H:i');
                                        $endDateName = $endDateName = $request->endDate . ' ' . $request->endTime;

                                        //Lấy giá tháng của dv
                                        $dataDetail[] = [
                                            'customer_appointment_id' => $id,
                                            'service_id' => $v,
                                            'staff_id' => $value['staff'],
                                            'room_id' => $value['room'],
                                            'customer_order' => $value['stt'],
                                            'created_by' => Auth::id(),
                                            'updated_by' => Auth::id(),
                                            'price' => (float)$price_branch['price_month'] * $request->type_number,
                                            'object_type' => $value['object_type'],
                                            'object_id' => $v,
                                            'object_code' => $price_branch['service_code'],
                                            'object_name' => $price_branch['service_name'] . ' (' . $startDateName . ' ->' . $endDateName . ')',
                                            'is_check_promotion' => 0
                                        ];
                                        $total += $price_branch['price_month'];
                                        break;
                                    case 'Y':
                                        $startDateName = Carbon::createFromFormat('Y-m-d H:i', $getDate['startDateTime'])->format('d/m/Y H:i');
                                        $endDateName = $request->endDate . ' ' . $request->endTime;

                                        //Lấy giá năm của dịch vụ
                                        $dataDetail[] = [
                                            'customer_appointment_id' => $id,
                                            'service_id' => $v,
                                            'staff_id' => $value['staff'],
                                            'room_id' => $value['room'],
                                            'customer_order' => $value['stt'],
                                            'created_by' => Auth::id(),
                                            'updated_by' => Auth::id(),
                                            'price' => (float)$price_branch['price_year'] * $request->type_number,
                                            'object_type' => $value['object_type'],
                                            'object_id' => $v,
                                            'object_code' => $price_branch['service_code'],
                                            'object_name' => $price_branch['service_name'] . ' (' . $startDateName . ' ->' . $endDateName . ')',
                                            'is_check_promotion' => 0
                                        ];
                                        $total += $price_branch['price_year'];
                                        break;
                                }
                                $objService = $this->service->getItem($v);

                                if ($service_using_name == "") {
                                    $service_using_name = $objService['service_name'];
                                } else {
                                    $service_using_name = $service_using_name . ', ' . $objService['service_name'];
                                }
                            } else {
                                $dataDetail[] = [
                                    'customer_appointment_id' => $id,
                                    'service_id' => $v,
                                    'staff_id' => $value['staff'],
                                    'room_id' => $value['room'],
                                    'customer_order' => $value['stt'],
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
                                    'price' => (float)$price,
                                    'object_type' => $value['object_type'],
                                    'object_id' => $v,
                                    'object_code' => '',
                                    'object_name' => null,
                                    'is_check_promotion' => 0
                                ];
                            }
                        }
                    }
                }
                // Cập nhật total, amount, discount cho customer-appointment
                $discount = $request->discount;
                $data_update = [
                    'total' => $total,
                    'amount' => $total - $discount,
                    'service_using_name' => $service_using_name
                ];
                $this->customer_appointment->edit($data_update, $id);
            }

            //Lưu chi tiết lịch hẹn
            $mAppointmentDetail = new CustomerAppointmentDetailTable();
            $mAppointmentDetail->insert($dataDetail);

            DB::commit();

            //Send Notification
            if ($request->status == 'cancel') {
                //Insert email log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_EMAIL_CUSTOMER,
                    'event' => 'is_event',
                    'key' => 'cancel_appointment',
                    'object_id' => $id,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Insert sms log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_SMS_CUSTOMER,
                    'key' => 'cancel_appointment',
                    'object_id' => $id,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Lưu log ZNS
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'cancel_appointment',
                    'customer_id' => $request->customer_id,
                    'object_id' => $id,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Send notification hủy lịch hẹn
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_CUSTOMER,
                    'key' => 'appointment_C',
                    'customer_id' => $request->customer_id,
                    'object_id' => $id,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Gửi thông báo nhân viên
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_STAFF,
                    'key' => 'appointment_C',
                    'customer_id' => $request->customer_id,
                    'object_id' => $id,
                    'branch_id' => $branchId,
                    'tenant_id' => session()->get('idTenant')
                ]);
            } else if ($request->status == 'confirm') {
                //Send notification xác nhận lịch hẹn
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_CUSTOMER,
                    'key' => 'appointment_A',
                    'customer_id' => $request->customer_id,
                    'object_id' => $id,
                    'tenant_id' => session()->get('idTenant')
                ]);
            }

            if (count($arrStaffCharge) > 0) {
                //Gửi thông báo cho nhân viên phục vụ
                $this->_sendNotifyStaffCharge($arrStaffCharge, $id);
            }

            return response()->json([
                'error' => false,
                'message' => __('Cập nhật lịch hẹn thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Cập nhật lịch hẹn thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    public function optionServiceStaffRoomAction()
    {
        $staff = $this->staff->getItem(Auth::id());
        $optionStaff = $this->staff->getStaffTechnician();
        $optionRoom = $this->room->getRoomOption();
        $optionService = $this->service_branch_price->getOptionService($staff['branch_id']);
        return response()->json([
            'optionStaff' => $optionStaff,
            'optionRoom' => $optionRoom,
            'optionService' => $optionService,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addAction()
    {
        $optionCustomer = $this->customer->getCustomerOption();
        $optionStaff = $this->staff->getStaffOption();
        $optionRoom = $this->room->getRoomOption();
        $optionTime = $this->customer_appointment_time->getTimeOption();
        $optionService = $this->service->getServiceOption();
        $day = date('d/m/Y');
        $time = date('H:i');
        //        dd($day,$time);
        return view('admin::customer-appointment.add', [
            'optionCustomer' => $optionCustomer,
            'optionStaff' => $optionStaff,
            'optionRoom' => $optionRoom,
            'optionService' => $optionService,
            'optionTime' => $optionTime,
            'day' => $day,
            'time' => $time
        ]);
    }

    public function loadTimeAction(Request $request)
    {
        $id = $request->id;
        $time_sv = $this->service->getItem($id);
        return response()->json([
            'time' => $time_sv['time']
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchReferAction(Request $request)
    {
        $data = $request->all();
        $value = $this->customer->getCustomerSearch($data['search']);
        $search = [];
        foreach ($value as $item) {
            $search['results'][] = [
                'id' => $item['customer_id'],
                'text' => $item['full_name'] . " - " . $item['phone1']
            ];
        }
        return response()->json([
            'search' => $search,
            'pagination' => $value->nextPageUrl() ? true : false
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchServiceAction(Request $request)
    {
        $data = $request->all();
        $value = $this->service->getServiceSearch($data['search']);
        $search = [];
        foreach ($value as $item) {
            $search['results'][] = [
                'id' => $item['service_id'],
                'text' => $item['service_name'],
                'time' => $item['time']
            ];
        }
        //        dd($search);
        return response()->json($search);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAddCustomerRefer(Request $request)
    {
        $phone = $request->phone1;
        $testPhone = $this->customer->testPhone($phone, 0);
        if ($testPhone['phone1'] == '') {
            $staff = $this->staff->getItem(Auth::id());
            $data = [
                'full_name' => $request->full_name,
                'phone1' => $phone,
                'branch_id' => $staff['branch_id'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'gender' => 'other',
                'customer_source_id' => 1
            ];

            $id_add = $this->customer->add($data);
            $day_code = date('dmY');
            $data_code = [
                'customer_code' => 'KH_' . $day_code . $id_add
            ];
            $this->customer->edit($data_code, $id_add);
            return response()->json([
                'status' => 1,
                'data' => $data,
                'id_add' => $id_add,
                'close' => $request->close
            ]);
        } else {
            return response()->json([
                'status' => 0
            ]);
        }
    }

    /**
     * Tìm kiếm khách hàng bằng sđt
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchPhoneAction(Request $request)
    {
        //Lấy danh sách khách hàng theo sđt
        $list_phone = $this->customer->searchPhone($request->phone);

        $data = [];
        foreach ($list_phone as $item) {
            $data[] = [
                'customer_id' => $item['customer_id'],
                'full_name' => $item['full_name'],
                'phone' => $item['phone1']
            ];
        }
        return response()->json([
            'list_phone' => $data
        ]);
    }

    /**
     * Lấy thông tin KH theo phone
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerByPhoneAction(Request $request)
    {
        //Lấy thông tin KH bằng sđt
        $cus_phone = $this->customer->getCusPhone($request->phone);

        if ($cus_phone != null) {
            session()->put('customer_id_appointment', $cus_phone['customer_id']);

            $mMemberCard = new CustomerServiceCardTable();
            $mConfig = app()->get(ConfigTable::class);

            $branchId = null;
            //Lấy cấu hình 1 chi nhánh or liên chi nhánh
            $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];

            if ($configBranch == 0) {
                //Lấy chi nhánh của nv đăng nhập
                $branchId = Auth()->user()->branch_id;
            }

            //Lấy thông tin member card của KH
            $listMemberCard = $mMemberCard->getMemberCard($cus_phone['customer_id'], $branchId);
            //Lấy option nhân viên
            $optionStaff = $this->staff->getStaffTechnician();
            //Lấy option phòng phục vụ
            $optionRoom = $this->room->getRoomOption();

            return response()->json([
                'cus' => $cus_phone,
                'success' => 1,
                'numberMemberCard' => count($listMemberCard),
                'listCard' => $listMemberCard,
                'optionStaff' => $optionStaff,
                'optionRoom' => $optionRoom
            ]);
        } else {
            return response()->json([
                'phone_new' => 1,
                'message' => __('Số điện thoại mới'),
                'numberMemberCard' => 0
            ]);
        }
    }

    /**
     * Ds lịch sử đặt lịch ở các view lịch hẹn, phân trang
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function getListHistoryAppointment(Request $request)
    {
        $filter = $request->all();
        $filter['customer_id'] = '000';
        if (session()->get('customer_id_appointment')) {
            $filter['customer_id'] = session()->get('customer_id_appointment');
        }
        $lstHistory = $this->customer_appointment->listHistoryAppointment($filter);

        return view('admin::calendar.pop.list-history', [
            'LIST' => $lstHistory,
            'page' => $request->page
        ]);
    }

    /**
     * Remove session customer_id_appointment
     *
     * @param Request $request
     * @return mixed|string
     */
    public function removeSessionAction(Request $request)
    {
        $data = "111";
        if (session()->get('customer_id_appointment')) {
            session()->forget('customer_id_appointment');
            $data = "000";
        }
        return $data;
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadCustomerAction(Request $request)
    {
        $id = $request->id;
        $item = $this->customer->getItem($id);
        $data = [
            'full_name' => $item->full_name,
            'phone1' => $item->phone1,
            'address' => $item->address
        ];
        return response()->json($data);
    }

    /**
     * Thêm lịch hẹn
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAddAction(Request $request)
    {
        $staff = $this->staff->getItem(Auth::id());

        if ($request->customer_hidden == '') {
            $testPhone = $this->customer->testPhone($request->phone1, 0);

            if ($testPhone['phone1'] == '') {
                //Thêm khách hàng
                $id_add = $this->customer->add([
                    'full_name' => $request->full_name,
                    'phone1' => $request->phone1,
                    'branch_id' => Auth()->user()->branch_id,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'gender' => 'other',
                    'customer_source_id' => 1,
                    'is_actived' => 1
                ]);

                $day_code = date('dmY');
                $data_code = [
                    'customer_code' => 'KH_' . $day_code . $id_add
                ];
                //Cập nhật mã khách hàng
                $this->customer->edit($data_code, $id_add);
            } else {
                return response()->json([
                    'status' => 'phone_exist'
                ]);
            }
        }

        $date = $request->date;
        $data = [
            'customer_id' => $request->customer_hidden,
            'time' => $request->time,
            'staff_id' => $request->staff_id,
            'room_id' => $request->room_id,
            'description' => $request->description,
            'customer_refer' => $request->customer_refer,
            'branch_id' => $staff['branch_id'],
            'status' => $request->status,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ];

        $arr_date = explode(" / ", $date);
        $format = Carbon::createFromFormat('d/m/Y', $arr_date[0])->format('Y-m-d');
        $data['date'] = $format;
        if ($request->customer_hidden == '') {
            $data['customer_id'] = $id_add;
        }
        $id_add_appointment = $this->customer_appointment->add($data);
        if ($request->service_appointment != '') {
            $aData = array_chunk($request->service_appointment, 2, false);
            //            dd($aData);
            foreach ($aData as $key => $value) {
                $data = [
                    'customer_appointment_id' => $id_add_appointment,
                    'service_id' => $value[0],
                    'quantity' => $value[1]
                ];
                $this->appointment_service->add($data);
            }
        }
        return response()->json([
            'status' => 1
        ]);
    }

    /**
     * View lịch hẹn theo ngày
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listDayAction(Request $request)
    {
        if (session()->get("customer_id_appointment")) {
            session()->forget("customer_id_appointment");
        }
        $day = date('Y-m-d');
        $optionSource = $this->appointment_source->getOption();
        $get = $this->customer_appointment->listDay($day);
        $dateGroup = $this->customer_appointment->listDayGroupBy($day);
        $dateGroupNew = $this->customer_appointment->listDayStatus($day, 'new');
        $dateGroupConfirm = $this->customer_appointment->listDayStatus($day, 'confirm');
        $dateGroupFinish = $this->customer_appointment->listDayStatus($day, 'finish');
        $dateGroupWait = $this->customer_appointment->listDayStatus($day, 'wait');
        $arr = [];
        $list_default = [];

        foreach ($get as $key => $item) {
            if ($item['status'] != 'cancel') {
                $arr[] = [
                    'time' => date("H:i", strtotime($item->time)),
                    'status' => $item->status,
                    'full_name' => $item->full_name_cus,
                    'phone' => $item->phone1,
                    'id' => $item->customer_appointment_id,
                    'avatar' => $item->customer_avatar,
                    'description' => $item->description
                    //                'service_name' => $this->getListByTimeAction(date("H:i", strtotime($item->time_join)), $item->date_appointment, $item->customer_appointment_id)
                ];
                if ($arr != null) {
                    if ($request->session()->has('idCustomerAppointmentSearchDashboard')) {
                        $idCustomerAppointments = $request->session()->get('idCustomerAppointmentSearchDashboard');;
                        $list_default = $this->customer_appointment->getItemDetail($idCustomerAppointments);
                    } else {
                        $list_default = $this->customer_appointment->getItemDetail($arr[0]['id']);
                    }
                }
            }
        }

        //Lấy cấu hình đặt lịch từ ngày đến ngày
        $mConfig = app()->get(ConfigTable::class);
        $configToDate = $mConfig->getInfoByKey('booking_to_date')['value'];
        //Lấy số tuần trong năm
        $numberWeek = 52;

        return view('admin::customer-appointment.list-day', [
            'list' => $arr,
            'dateGroup' => $dateGroup,
            'dateGroupNew' => $dateGroupNew,
            'dateGroupConfirm' => $dateGroupConfirm,
            'dateGroupFinish' => $dateGroupFinish,
            'dateGroupWait' => $dateGroupWait,
            'day' => $day,
            'list_default' => $list_default,
            'optionSource' => $optionSource,
            'configToDate' => $configToDate,
            'numberWeek' => $numberWeek
        ]);
    }

    public function loadTimeDayAction(Request $request)
    {
        $arr_cut = explode(" - ", $request->day);
        $day = Carbon::createFromFormat('d/m/Y', $arr_cut[0])->format('Y-m-d');
        $get = $this->customer_appointment->listDay($day);
        $arr = [];
        $list_default = [];
        $time = [];
        foreach ($get as $key => $item) {
            $arr['list'][] = [
                'time' => date("H:i", strtotime($item->time)),
                'status' => $item->status,
                'full_name' => $item->full_name_cus,
                'phone' => $item->phone1,
                'id' => $item->customer_appointment_id,
                'service_name' => $this->getListByTimeAction(date("H:i", strtotime($item->time_join)), $item->date_appointment, $item->customer_appointment_id)
            ];
            if ($arr != null) {
                $list_default = $this->customer_appointment->getItemDetail($arr['list'][0]['id']);
                foreach ($list_default as $k => $v) {

                    $time = date("H:i", strtotime($v['time_join']));
                }
            }
        }
        return response()->json([
            'list_time' => $time,
        ]);
    }

    public function getListByTimeAction($time, $day, $id)
    {
        $list = $this->customer_appointment->listByTime($time, $day, $id);
        return $list;
    }

    public function searchTimeAction(Request $request)
    {
        $day = $request->day_search;
        $arr_date = explode(" / ", $day);
        $format = Carbon::createFromFormat('d/m/Y', $arr_date[0])->format('Y-m-d');
        $get = $this->customer_appointment->listDay($format);

        $dateCalendarGroup = $this->customer_appointment->listDayGroupBy($format);
        $dateGroupNew = $this->customer_appointment->listDayStatus($format, 'new');
        $dateGroupConfirm = $this->customer_appointment->listDayStatus($format, 'confirm');
        $dateGroupFinish = $this->customer_appointment->listDayStatus($format, 'finish');
        $dateGroupWait = $this->customer_appointment->listDayStatus($format, 'wait');

        $arr = [];
        foreach ($get as $key => $item) {
            if ($item['status'] != 'cancel') {
                $arr[] = [
                    'time' => date("H:i", strtotime($item['time'])),
                    'full_name' => $item['full_name_cus'],
                    'phone' => $item['phone1'],
                    'status' => $item['status'],
                    'id' => $item['customer_appointment_id'],
                    'date' => $item['date_appointment'],
                    'avatar' => $item['customer_avatar'],
                    'description' => $item['description']
                ];
            }
        }

        $view = view('admin::customer-appointment.inc.timeline-list', [
            'list' => $arr,
            'dateGroup' => $dateCalendarGroup,
            'dateGroupNew' => $dateGroupNew,
            'dateGroupConfirm' => $dateGroupConfirm,
            'dateGroupFinish' => $dateGroupFinish,
            'dateGroupWait' => $dateGroupWait
        ])->render();

        return response()->json($view);
    }

    public function searchNameAction(Request $request)
    {
        $search = $request->search;
        $day = $request->day;
        $arr_date = explode(" / ", $day);
        $format = Carbon::createFromFormat('d/m/Y', $arr_date[0])->format('Y-m-d');
        $getList = $this->customer_appointment->listNameSearch($search, $format);
        $dateCalendarGroup = $this->customer_appointment->listDayGroupBy($format);
        $dateGroupNew = $this->customer_appointment->listDayStatus($format, 'new');
        $dateGroupConfirm = $this->customer_appointment->listDayStatus($format, 'confirm');
        $dateGroupFinish = $this->customer_appointment->listDayStatus($format, 'finish');
        $dateGroupWait = $this->customer_appointment->listDayStatus($format, 'wait');

        $data = [];
        foreach ($getList as $item) {
            if ($item['status'] != 'cancel') {
                $data[] = [
                    'full_name' => $item['full_name_cus'],
                    'time' => date("H:i", strtotime($item['time'])),
                    'phone' => $item['phone1'],
                    'id' => $item['customer_appointment_id'],
                    'status' => $item['status'],
                    'avatar' => $item['customer_avatar']
                ];
            }
        }
        $view = view('admin::customer-appointment.inc.timeline-list', [
            'list' => $data,
            'dateGroup' => $dateCalendarGroup,
            'dateGroupNew' => $dateGroupNew,
            'dateGroupConfirm' => $dateGroupConfirm,
            'dateGroupFinish' => $dateGroupFinish,
            'dateGroupWait' => $dateGroupWait
        ])->render();
        return response()->json($view);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function listEventCalendarAction()
    {
        $day_now = date("Y/m/d");
        $data = $this->customer_appointment->listCalendar($day_now);
        return response()->json([
            'data' => $data,
        ]);
    }

    public function detailAction(Request $request)
    {

        $id = $request->id;
        //Lấy thông tin lịch hẹn
        $item = $this->customer_appointment->getItemDetail($id);

        $mAppointmentDetail = new CustomerAppointmentDetailTable();
        //Tên dv + thẻ liệu trình được đặt lịch
        $objectName = '';
        //Lấy chi tiết lịch hẹn
        $detail = $mAppointmentDetail->getItem($id);
        $arrayStaff = [];

        if (count($detail) > 0) {
            foreach ($detail as $k => $v) {
                $comma = $k + 1 < count($detail) ? '; ' : '';

                $objectName .= $v['service_name'] . $comma;
                // $arrayStaff[] = $v['full_name'];
                if (in_array($v['full_name'], $arrayStaff)) {
                } else {
                    if ($v['full_name'] != "") {
                        $arrayStaff[] = $v['full_name'];
                    }
                }
            }
        }

        $view = view('admin::customer-appointment.inc.timeline-info-calendar', [
            'list_default' => $item,
            'object_name' => $objectName,
            'array_staff' => $arrayStaff
        ])->render();
        return response()->json($view);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * get item modal edit
     */
    public function getItemDetailAction(Request $request)
    {
        $mAppointment = new CustomerAppointmentTable();
        $mAppointmentDetail = new CustomerAppointmentDetailTable();
        $id = $request->id;
        //Lấy thông tin lịch hẹn
        $info = $mAppointment->getInfo($id);
        //Lấy thông tin chi tiết lịch hẹn
        $detail = $mAppointmentDetail->getDetail($id);

        $arrServiceDetail = [];
        $staffService = [];
        $roomService = [];
        $arrMemberCardDetail = [];
        $staffMemberCard = [];
        $roomMemberCard = [];
        foreach ($detail as $v) {
            if ($v['object_type'] == 'service') {
                $arrServiceDetail[] = $v['object_id'];
                $staffService[] = $v['staff_id'];
                $roomService[] = $v['room_id'];
            } else if ($v['object_type'] == 'member_card') {
                $arrMemberCardDetail[] = $v['object_id'];
                $staffMemberCard[] = $v['staff_id'];
                $roomMemberCard[] = $v['room_id'];
            }
        }

        //Lấy giờ đặt lịch mới
        $time_new = [
            '07:00', '07:15', '07:30', '07:45', '08:00', '08:15', '08:30', '08:45', '09:00', '09:15', '09:30', '09:45',
            '10:00', '10:15', '10:30', '10:45', '11:00', '11:15', '11:30', '11:45', '12:00', '12:15', '12:30', '12:45', '13:00',
            '13:15', '13:30', '13:45', '14:00', '14:15', '14:30', '14:45', '15:00', '15:15', '15:30', '15:45', '16:00', '16:15',
            '16:30', '16:45', '17:00', '17:15', '17:30', '17:45', '18:00', '18:15', '18:30', '18:45', '19:00', '19:15', '19:30',
            '19:45', '20:00', '20:15', '20:30', '20:45', '21:00', '21:15', '21:30', '21:45', '22:00'
        ];
        $time_now = date("H:i");
        $arr_time = [];
        foreach ($time_new as $item) {
            if ($item > $time_now) {
                $arr_time[] = [
                    'time_new' => $item
                ];
            }
        }
        //Lấy option nhân viên
        $optionStaff = $this->staff->getStaffTechnician();
        //Lấy option phòng phục vụ
        $optionRoom = $this->room->getRoomOption();
        //Lấy option dịch vụ
        $optionService = $this->service_branch_price->getOptionService($info['branch_id']);
        //Lấy nguồn lịch hẹn
        $optionSource = $this->appointment_source->getOption();
        $mConfig = new ConfigTable();
        //Lấy cấu hình đặt lịch từ ngày đến ngày
        $configToDate = $mConfig->getInfoByKey('booking_to_date')['value'];
        //Lấy thông tin member card của KH
        $mMemberCard = new CustomerServiceCardTable();
        $mConfig = app()->get(ConfigTable::class);

        $branchId = null;
        //Lấy cấu hình 1 chi nhánh or liên chi nhánh
        $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];

        if ($configBranch == 0) {
            //Lấy chi nhánh của nv đăng nhập
            $branchId = Auth()->user()->branch_id;
        }
        $listMemberCard = $mMemberCard->getMemberCard($info['customer_id'], $branchId);
        //Lấy số tuần trong năm
        $numberWeek = 52;
        // session customer_id_appointment
        session()->put('customer_id_appointment', $info['customer_id']);

        $is_booking_past = 0;
        //Lấy phân quyền đặt lịch lùi
        if (
            Auth()->user()->is_admin == 1
            || in_array('is_booking_past', session('routeList'))
        ) {
            $is_booking_past  = 1;
        }

        $isEnabledEditMoreThanDay = 0;
        // lấy phân quyền chỉnh sửa lịch hẹn khi qua 24h
        if (
            Auth()->user()->is_admin == 1
            || in_array('is_enabled_edit_more_than_day', session('routeList'))
        ) {
            $isEnabledEditMoreThanDay  = 1;
        }
        // nếu user không được phân quyền tại chỉ chỉnh sửa được khi thời gian chưa tới 24h cùng ngày đặt
        if ($isEnabledEditMoreThanDay == 0) {
            $tomorrowDateAppointment = Carbon::createFromFormat('Y-m-d', $info['date'])->addDays(1)->format('Y-m-d 00:00:01');
            if ($tomorrowDateAppointment > Carbon::now()->format('Y-m-d H:i:s')) {
                $isEnabledEditMoreThanDay = 1;
            }
        }

        //Lấy phân quyền thay đổi chi nhánh
        $is_change_branch = 0;

        if (
            Auth()->user()->is_admin == 1
            || in_array('is_change_branch', session('routeList'))
        ) {
            $is_change_branch = 1;
        }

        //Lấy option chi nhánh
        $mBranch = app()->get(BranchTable::class);
        $optionBranch = $mBranch->getBranchOption();

        //Kiểm tra lịch hẹn có trùng với chi nhánh user login ko
        $sameBranch = 0;
        if ($info['branch_id'] == Auth::user()->branch_id || $is_change_branch == 1) {
            $sameBranch = 1;
        }

        //Render view
        $html = \View::make('admin::customer-appointment.modal-edit', [
            'configToDate' => $configToDate,
            'optionSource' => $optionSource,
            'optionStaff' => $optionStaff,
            'optionRoom' => $optionRoom,
            'optionService' => $optionService,
            'timeNew' => $arr_time,
            'sameBranch' => $sameBranch,
            'item' => $info,
            'detail' => $detail,
            'listMemberCard' => $listMemberCard,
            'arrServiceDetail' => $arrServiceDetail,
            'arrMemberCardDetail' => $arrMemberCardDetail,
            'staffService' => $staffService,
            'roomService' => $roomService,
            'staffMemberCard' => $staffMemberCard,
            'roomMemberCard' => $roomMemberCard,
            'numberWeek' => $numberWeek,
            'is_booking_past' => $is_booking_past,
            'isEnabledEditMoreThanDay' => $isEnabledEditMoreThanDay,
            'is_change_branch' => $is_change_branch,
            'optionBranch' => $optionBranch
        ])->render();
        return response()->json([
            'html' => $html,
            'is_booking_past' => $is_booking_past
        ]);
    }

    /**
     * Xác nhận lịch hẹn
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \MyCore\Api\ApiException
     * @throws \Throwable
     */
    public function submitConfirmAction(Request $request)
    {
        $id = $request->id;
        //Thông tin lịch hẹn
        $getInfo = $this->customer_appointment->getItemEdit($id);
        $data = [
            'time' => $request->time,
            'status' => 'confirm'
        ];
        //Insert log cập nhật trang thái
        $this->insertLogEdit($id, 'confirm');
        //Chỉnh sửa xác nhận lịch hẹn
        $this->customer_appointment->edit($data, $id);
        //Send Notification xác nhận lịch hẹn
        App\Jobs\FunctionSendNotify::dispatch([
            'type' => SEND_NOTIFY_CUSTOMER,
            'key' => 'appointment_A',
            'customer_id' => $getInfo['customer_id'],
            'object_id' => $id,
            'tenant_id' => session()->get('idTenant')
        ]);

        $search = $request->search;
        $day = $request->day;
        $arr_date = explode(" / ", $day);
        $format = Carbon::createFromFormat('d/m/Y', $arr_date[0])->format('Y-m-d');
        $getList = $this->customer_appointment->listNameSearch($search, $format);
        $dateCalendarGroup = $this->customer_appointment->listDayGroupBy($format);
        $dateGroupNew = $this->customer_appointment->listDayStatus($format, 'new');
        $dateGroupConfirm = $this->customer_appointment->listDayStatus($format, 'confirm');
        $dateGroupFinish = $this->customer_appointment->listDayStatus($format, 'finish');
        $dateGroupWait = $this->customer_appointment->listDayStatus($format, 'wait');

        $data = [];
        foreach ($getList as $item) {
            if ($item['status'] != 'cancel') {
                $data[] = [
                    'full_name' => $item['full_name_cus'],
                    'time' => date("H:i", strtotime($item['time'])),
                    'phone' => $item['phone1'],
                    'id' => $item['customer_appointment_id'],
                    'status' => $item['status'],
                    'avatar' => $item['customer_avatar'],
                    'description' => $item['description']
                ];
            }
        }
        $view = view('admin::customer-appointment.inc.timeline-list', [
            'list' => $data,
            'dateGroup' => $dateCalendarGroup,
            'dateGroupNew' => $dateGroupNew,
            'dateGroupConfirm' => $dateGroupConfirm,
            'dateGroupFinish' => $dateGroupFinish,
            'dateGroupWait' => $dateGroupWait
        ])->render();
        return response()->json($view);
    }

    /**
     * @param Request $request
     */
    public function editStatusAction(Request $request)
    {
        $id = $request->id;
        $data = [
            'status' => $request->status
        ];
        $this->customer_appointment->edit($data, $id);
        return response()->json([
            'error' => 0
        ]);
    }

    public function editAction($id)
    {
        $optionCustomer = $this->customer->getCustomerOption();
        $optionStaff = $this->staff->getStaffOption();
        $optionRoom = $this->room->getRoomOption();
        $optionTime = $this->customer_appointment_time->getTimeOption();
        $optionService = $this->service->getServiceOption();
        $getItem = $this->customer_appointment->getItemEdit($id);
        $itemService = $this->customer_appointment->getItemServiceDetail($id);
        $itemRefer = $this->customer_appointment->getItemRefer($id);
        return view('admin::customer-appointment.edit', [
            'item' => $getItem,
            'itemSv' => $itemService,
            'itemRefer' => $itemRefer,
            'optionCustomer' => $optionCustomer,
            'optionStaff' => $optionStaff,
            'optionRoom' => $optionRoom,
            'optionTime' => $optionTime,
            'optionService' => $optionService
        ]);
    }

    public function loadTimeEditAction(Request $request)
    {
        $id_appointment = $request->id_appointment;
        $item_time = $this->customer_appointment->getItemEdit($id_appointment);
        return response()->json([
            'time' => date("H:i", strtotime($item_time['time_join']))
        ]);
    }

    public function submitEditFormAction(Request $request)
    {
        $id = $request->id;
        $date = $request->date;
        $arr_date = explode(" / ", $date);
        $format = Carbon::createFromFormat('d/m/Y', $arr_date[0])->format('Y-m-d');
        $data = [
            'staff_id' => $request->staff_id,
            'room_id' => $request->room_id,
            'time' => $request->time,
            'date' => $format,
            'status' => $request->status,
            'description' => $request->description
        ];
        $this->customer_appointment->edit($data, $id);
        if ($request->sv_table != '') {
            $aData = array_chunk($request->sv_table, 3, false);
            foreach ($aData as $key => $value) {
                $data = [
                    'appointment_service_id' => $value[0],
                    'service_id' => $value[1],
                    'quantity' => $value[2]
                ];
                $this->appointment_service->edit($data, $value[0]);
            }
        }
        if ($request->sv_table_add != '') {
            $aData = array_chunk($request->sv_table_add, 2, false);

            foreach ($aData as $key => $value) {
                $data = [
                    'customer_appointment_id' => $id,
                    'service_id' => $value[0],
                    'quantity' => $value[1]
                ];
                $this->appointment_service->add($data);
            }
        }
        if ($request->remove_sv != '') {
            $aData = array_chunk($request->remove_sv, 1, false);
            foreach ($aData as $key => $value) {
                $data = [
                    'appointment_service_id' => $value[0],
                    'is_deleted' => 1
                ];
                $this->appointment_service->edit($data, $value[0]);
            }
        }

        return response()->json([
            'error' => 0
        ]);
    }

    const PLUS = "plus";
    const SUBTRACT = "subtract";

    /**
     * Giao diện đơn hàng từ lịch hẹn
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function receiptAction($id)
    {
        $mPaymentMethod = new PaymentMethodTable();
        $mConfig = app()->get(ConfigTable::class);
        $mBranchMoneyLog = app()->get(CustomerBranchMoneyLogTable::class);

        $branchId = null;
        //Lấy cấu hình 1 chi nhánh or liên chi nhánh
        $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];

        if ($configBranch == 0) {
            //Lấy chi nhánh của nv đăng nhập
            $branchId = Auth()->user()->branch_id;
        }

        //Lấy phương thức thanh toán
        $optionPaymentMethod = $mPaymentMethod->getOption();
        $customer_default = $this->customer->getCustomerOption();
        //Lấy nv phục vụ
        $staff_technician = $this->staff->getStaffTechnician();
        //Lấy thông tin lịch hẹn
        $getItem = $this->customer_appointment->getItemEdit($id);
        //Lấy thông tin chi tiết lịch hẹn
        $itemDetail = $this->customer_appointment_detail->getItem($id);
        //Lấy thông tin nhân viên
        $staff = $this->staff->getItem(Auth::id());

        //Lấy tổng tiền thành viên cộng vào
        $totalPlusMoney = $mBranchMoneyLog->getTotalMoney($getItem['customer_id'], $branchId, self::PLUS);
        //Lấy tổng tiền thành viên trừ ra
        $totalSubtractMoney = $mBranchMoneyLog->getTotalMoney($getItem['customer_id'], $branchId, self::SUBTRACT);
        $money_branch = floatval($totalPlusMoney['total']) - floatval($totalSubtractMoney['total']);
        //Lấy thẻ liệu trình của KH
        $list_card_active = $this->customer_service_card->loadCardMember($getItem['customer_id'], $branchId);

        $data = [];
        foreach ($list_card_active as $key => $item) {
            if ($item['expired_date'] == null) {
                if ($item['number_using'] == 0) {
                    $data[] = [
                        'customer_service_card_id' => $item['customer_service_card_id'],
                        'card_code' => $item['card_code'],
                        'card_name' => $item['name_code'],
                        'image' => $item['image'],
                        'number_using' => $item['number_using'],
                        'count_using' => __('Không giới hạn'),
                    ];
                } else {
                    if ($item['number_using'] > $item['count_using']) {
                        $data[] = [
                            'customer_service_card_id' => $item['customer_service_card_id'],
                            'card_code' => $item['card_code'],
                            'card_name' => $item['name_code'],
                            'image' => $item['image'],
                            'number_using' => $item['number_using'],
                            'count_using' => $item['number_using'] - $item['count_using'],
                        ];
                    }
                }
            } else {
                if (date('Y-m-d', strtotime($item['expired_date'])) >= date('Y-m-d')) {
                    if ($item['number_using'] == 0) {
                        $data[] = [
                            'customer_service_card_id' => $item['customer_service_card_id'],
                            'card_code' => $item['card_code'],
                            'card_name' => $item['name_code'],
                            'image' => $item['image'],
                            'number_using' => $item['number_using'],
                            'count_using' => __('Không giới hạn'),
                            'expired_date' => date('d/m/Y', strtotime($item['expired_date']))
                        ];
                    } else {
                        if ($item['number_using'] > $item['count_using']) {
                            $data[] = [
                                'customer_service_card_id' => $item['customer_service_card_id'],
                                'card_code' => $item['card_code'],
                                'card_name' => $item['name_code'],
                                'image' => $item['image'],
                                'number_using' => $item['number_using'],
                                'count_using' => $item['number_using'] - $item['count_using'],
                                'expired_date' => date('d/m/Y', strtotime($item['expired_date']))
                            ];
                        }
                    }
                }
            }
        }
        $data_detail = [];

        foreach ($itemDetail as $v) {
            if ($v['service_id'] != null) {
                $price = $v['price'];

                // End check promotion price
                $data_detail[] = [
                    'service_id' => $v['service_id'],
                    'service_name' => $v['object_name'] != null ? $v['object_name'] : $v['service_name'],
                    'service_code' => $v['object_code'],
                    'price' => $price,
                    'quantity' => 1,
                    'staff_id' => isset($v['staff_id']) != '' ? explode(',', $v['staff_id']) : null,
                    'number_ran' => md5(uniqid(rand(1, 8), true)),
                    'object_type' => $v['object_type'],
                    'number_using' => $v['number_using'],
                    'count_using' => $v['count_using'],
                    'is_check_promotion' => $v['is_check_promotion'],
                    'is_change_price' => 0,
                ];
            }
        }

        $mConfig = new ConfigTable();
        //Lấy cấu hình thay đổi giá
        $customPrice = $mConfig->getInfoByKey('customize_price')['value'];

        //Lấy option dịch vụ
        $optionService = $this->service_branch_price->getOptionService(Auth()->user()->branch_id);
        //Lấy option phòng
        $mRoom = new RoomTable();
        $optionRoom = [];
        foreach ($mRoom->getRoomOption() as $item) {
            $optionRoom[$item['room_id']] = $item['name'];
        }
        $configToDate = $mConfig->getInfoByKey('booking_to_date')['value'];

        // sale : 1 1 0 1
        // bs: 0 1 0 1
        // thu ngân: 0 0 1 0

        $is_edit_full = 0;
        $is_edit_staff = 0;
        $is_payment_order = 0;
        if (in_array('is_payment_order', session('routeList'))) {
            $is_payment_order = 1;
        }
        if (in_array('is_edit_full', session('routeList'))) {
            $is_edit_full = 1;
        }
        if (in_array('is_edit_staff', session('routeList'))) {
            $is_edit_staff = 1;
        }

        $is_update_order = 0;
        if ($is_edit_full == 1) {
            $is_update_order = 1;
            $is_edit_staff = 1;
        }
        if ($is_edit_staff == 1) {
            $is_update_order = 1;
        }
        //Lấy công nợ của KH
        $amountDebt = $this->customer_debt->getItemDebt($getItem['customer_id']);

        $debt = 0;
        if (count($amountDebt) > 0) {
            foreach ($amountDebt as $item) {
                $debt += $item['amount'] - $item['amount_paid'];
            }
        }

        $mConfigTab = app()->get(OrderConfigTabTable::class);

        //Lấy cấu hình tab
        $getTab = $mConfigTab->getConfigTab();

        return view('admin::customer-appointment.receipt.receipt', [
            'item' => $getItem,
            'money_branch' => $money_branch,
            'data_detail' => $data_detail,
            'data_card' => $data,
            'staff_technician' => $staff_technician,
            'customer_refer' => $customer_default,
            'optionPaymentMethod' => $optionPaymentMethod,
            'customPrice' => $customPrice,
            'optionStaff' => $staff_technician,
            'optionService' => $optionService,
            'optionRoom' => $optionRoom,
            'configToDate' => $configToDate,
            'is_edit_full' => $is_edit_full,
            'is_edit_staff' => $is_edit_staff,
            'is_payment_order' => $is_payment_order,
            'is_update_order' => $is_update_order,
            'debt' => $debt,
            'getTab' => $getTab
        ]);
    }

    /**
     * Thanh toán đơn hàng từ lịch hẹn
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitReceiptAction(Request $request)
    {
        DB::beginTransaction();
        try {
            $mPromotionLog = new PromotionLogTable();
            $mOrderApp = app()->get(OrderAppRepoInterface::class);
            $mStaff = new StaffsTable();
            $mBranchMoneyLog = app()->get(CustomerBranchMoneyLogTable::class);
            $id_order = $request->order_id;
            $orderCode = $request->order_code;
            //Lấy số lẻ decimal
            $decimal = isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0;


            $data_order = [
                'customer_id' => $request->customer_id,
                'total' => $request->total_bill,
                'discount' => $request->discount_bill,
                'branch_id' => Auth()->user()->branch_id,
                'amount' => str_replace(',', '', $request->amount_bill),
                'voucher_code' => $request->voucher_bill,
                'process_status' => 'paysuccess',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'order_source_id' => 1,
                'refer_id' => $request->refer_id,
                'discount_member' => $request->discount_member,
                'cashier_by' => Auth()->id(),
                'cashier_date' => Carbon::now()->format('Y-m-d H:i:s')
            ];
            //Chỉnh sửa đơn hàng
            $this->order->edit($data_order, $id_order);

            if ($request->voucher_bill != null) {
                $get = $this->voucher->getCodeItem($request->voucher_bill);
                $data = [
                    'total_use' => ($get['total_use'] + 1)
                ];
                $this->voucher->editVoucherOrder($data, $request->voucher_bill);
            }
            $list_card_print = [];
            $arrObjectBuy = [];
            $arrRemindUse = [];

            if (count($request->table_add) > 0) {
                //Xoá chi tiết đơn hàng
                $this->order_detail->remove($id_order);

                foreach ($request->table_add as $v) {
                    $position = $v['number_row'] ?? null;

                    if (in_array($v['object_type'], ['product', 'service', 'service_card'])) {
                        if ($v['is_check_promotion'] == 1) {
                            $arrObjectBuy[] = [
                                'object_type' => $v['object_type'],
                                'object_code' => $v['object_code'],
                                'object_id' => $v['object_id'],
                                'price' => $v['price'],
                                'quantity' => $v['quantity'],
                                'customer_id' => $request->customer_id,
                                'order_source' => self::LIVE,
                                'order_id' => $id_order,
                                'order_code' => $orderCode
                            ];
                        }
                        //Lấy array nhắc sử dụng lại
                        $arrRemindUse[] = [
                            'object_type' => $v['object_type'],
                            'object_id' => $v['object_id'],
                            'object_code' => $v['object_code'],
                            'object_name' => $v['object_name'],
                        ];
                    }

                    //Thêm chi tiết đơn hàng
                    $id_detail = $this->order_detail->add([
                        'order_id' => $id_order,
                        'object_id' => $v['object_id'],
                        'object_name' => $v['object_name'],
                        'object_type' => $v['object_type'],
                        'object_code' => $v['object_code'],
                        'price' => $v['price'],
                        'quantity' => $v['quantity'],
                        'discount' => str_replace(',', '', $v['discount']),
                        'voucher_code' => $v['voucher_code'],
                        'amount' => $v['amount'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'staff_id' => isset($v['staff_id']) && $v['staff_id'] != null ? implode(',', $v['staff_id']) : null,
                        'refer_id' => $request->refer_id,
                        'is_change_price' => $v['is_change_price'],
                        'is_check_promotion' => $v['is_check_promotion'],
                        'note' => $v['note'] ?? null,
                        'created_at_day' => Carbon::now()->format('d'),
                        'created_at_month' => Carbon::now()->format('m'),
                        'created_at_year' => Carbon::now()->format('Y'),
                    ]);

                    //Lưu dịch vụ kèm theo
                    if (isset($v['array_attach']) && count($v['array_attach']) > 0) {
                        foreach ($v['array_attach'] as $v1) {
                            //Lưu chi tiết đơn hàng
                            $this->order_detail->add([
                                'order_id' => $id_order,
                                'object_id' => $v1['object_id'],
                                'object_name' => $v1['object_name'],
                                'object_type' => $v1['object_type'],
                                'object_code' => $v1['object_code'],
                                'price' => $v1['price'],
                                'quantity' => $v1['quantity'],
                                'amount' => $v1['price'] * $v1['quantity'],
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                                'order_detail_id_parent' => $id_detail,
                                'created_at_day' => Carbon::now()->format('d'),
                                'created_at_month' => Carbon::now()->format('m'),
                                'created_at_year' => Carbon::now()->format('Y'),
                            ]);
                        }
                    }

                    switch ($v['object_type']) {
                        case 'service':
                            //Lấy hoa hồng dịch vụ
                            $check_commission = $this->service->getItem($v['object_id']);
                            $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->order->calculatedCommission($v['quantity'], $request->refer_id, $check_commission, $id_detail, $v['object_name'], null, $v['amount'], $v['staff_id'] ?? null);

                            break;
                        case 'product':
                            //Lấy hoa hồng sản phẩm
                            $check_commission = $this->product_child->getItem($v['object_id']);
                            $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->order->calculatedCommission($v['quantity'], $request->refer_id, $check_commission, $id_detail, $v['object_name'], null, $v['amount'], $v['staff_id'] ?? null);

                            break;
                        case 'service_card':
                            //Lấy thông tin thẻ dịch vụ
                            $sv_card = $this->service_card->getServiceCardOrder($v['object_code']);
                            //Lấy hoa hồng thẻ dịch vụ
                            $check_commission = $this->service_card->getServiceCardInfo($v['object_id']);
                            $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;

                            $refer_money = 0;
                            $staff_money = 0;
                            $staffCardCommission = 0;
                            if ($check_commission['type_refer_commission'] == 'money') {
                                if (isset($check_commission['refer_commission_value'])) {
                                    $refer_money = ($check_commission['refer_commission_value']);
                                }
                            } else {
                                if (isset($check_commission['refer_commission_value'])) {
                                    $refer_money = ($v['amount'] / 100) * ($check_commission['refer_commission_value']);
                                }
                            }
                            if ($check_commission['type_staff_commission'] == 'money') {
                                if (isset($check_commission['staff_commission_value'])) {
                                    $staffCardCommission = round($check_commission['staff_commission_value'], $decimal, PHP_ROUND_HALF_DOWN);
                                }
                            } else {
                                if (isset($check_commission['staff_commission_value'])) {
                                    $staffMoney = ($v['amount'] / 100) * ($check_commission['staff_commission_value']);
                                    $staffCardCommission = round($staffMoney, $decimal, PHP_ROUND_HALF_DOWN);
                                }
                            }

                            $arr_result = [];
                            for ($i = 0; $i < $v['quantity']; $i++) {
                                //Generate mã thẻ liệu trình
                                $code = $this->code->generateCardListCode();
                                while (array_search($code, $arr_result)) {
                                    $code = $this->code->generateCardListCode();
                                }

                                $data_card_list = [
                                    'service_card_id' => $v['object_id'],
                                    'order_code' => $orderCode,
                                    'branch_id' => Auth()->user()->branch_id,
                                    'price' => $v['price'],
                                    'code' => $code,
                                    'refer_commission' => $refer_money,
                                    'staff_commission' => $staffCardCommission,
                                    'created_by' => Auth::id()
                                ];

                                if ($request->customer_id != 1 && $request->check_active == 1) {
                                    $data_card_list['is_actived'] = $request->check_active;
                                    $data_card_list['actived_at'] = date("Y-m-d H:i");

                                    $data_cus_card = [
                                        'customer_id' => $request->customer_id,
                                        'card_code' => $code,
                                        'service_card_id' => $v['object_id'],
                                        'number_using' => $sv_card['number_using'],
                                        'count_using' => $sv_card['service_card_type'] == 'money' ? 1 : 0,
                                        'money' => $sv_card['money'],
                                        'actived_date' => date("Y-m-d"),
                                        'is_actived' => 1,
                                        'created_by' => Auth::id(),
                                        'updated_by' => Auth::id(),
                                        'branch_id' => Auth()->user()->branch_id
                                    ];

                                    if ($sv_card['date_using'] != 0) {
                                        $data_cus_card['expired_date'] = strftime("%Y-%m-%d", strtotime(date("Y-m-d", strtotime(date("Y-m-d") . '+ ' . $sv_card['date_using'] . 'days'))));
                                    }
                                    if ($sv_card['service_card_type'] == 'money') {
                                        //Lấy thông tin KH
                                        $customer = $this->customer->getItem($request->customer_id);
                                        //Cập nhật tổng tiền của KH
                                        $this->customer->edit([
                                            'account_money' => $customer['account_money'] + $sv_card['money']
                                        ], $request->customer_id);

                                        //Lưu log + tiền
                                        $mBranchMoneyLog->add([
                                            "customer_id" => $request->customer_id,
                                            "branch_id" => Auth()->user()->branch_id,
                                            "source" => "member_money",
                                            "type" => 'plus',
                                            "money" => $sv_card['money'],
                                            "screen" => 'active_card',
                                            "screen_object_code" => $code
                                        ]);
                                    }

                                    //Thêm vào customer service card thẻ đã active
                                    $this->customer_service_card->add($data_cus_card);
                                    //Thêm vào service card list thẻ đã active
                                    $this->service_card_list->add($data_card_list);
                                    array_push($list_card_print, $code);
                                    $arr_result[] = $code;
                                } else {
                                    $data_card_list['is_actived'] = 0;
                                    $this->service_card_list->add($data_card_list);
                                    array_push($list_card_print, $code);
                                    $arr_result[] = $code;
                                }
                            }

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->order->calculatedCommission($v['quantity'], $request->refer_id, $check_commission, $id_detail, $v['object_name'], null, $v['amount'], $v['staff_id'] ?? null);

                            break;
                        case 'member_card':
                            //Lấy thông tin thẻ liệu trình
                            $list_cus_card = $this->customer_service_card->getItemCard($v['object_id']);
                            //Trừ số lần sử dụng thẻ liệu trình
                            $this->customer_service_card->editByCode([
                                'count_using' => $list_cus_card['count_using'] + $v['quantity']
                            ], $v['object_code']);

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->order->calculatedCommission($v['quantity'], $request->refer_id, null, $id_detail, $v['object_name'], $v['object_code'], $v['amount'], $v['staff_id'] ?? null, 0, 0, "member_card"
                            );

                            DB::commit();

                            //Send email
                            App\Jobs\FunctionSendNotify::dispatch([
                                'type' => SEND_EMAIL_CUSTOMER,
                                'event' => 'is_event',
                                'key' => 'service_card_over_number_used',
                                'object_id' => $v['object_id'],
                                'tenant_id' => session()->get('idTenant')
                            ]);
                            //Send sms
                            App\Jobs\FunctionSendNotify::dispatch([
                                'type' => SEND_SMS_CUSTOMER,
                                'key' => 'service_card_over_number_used',
                                'object_id' => $v['object_id'],
                                'tenant_id' => session()->get('idTenant')
                            ]);
                            //Lưu log ZNS
                            App\Jobs\FunctionSendNotify::dispatch([
                                'type' => SEND_ZNS_CUSTOMER,
                                'key' => 'service_card_over_number_used',
                                'customer_id' => $request->customer_id,
                                'object_id' => $v['object_id'],
                                'tenant_id' => session()->get('idTenant')
                            ]);
                            //Send notification
                            App\Jobs\FunctionSendNotify::dispatch([
                                'type' => SEND_NOTIFY_CUSTOMER,
                                'key' => 'service_card_over_number_used',
                                'customer_id' => $request->customer_id,
                                'object_id' => $v['object_id'],
                                'tenant_id' => session()->get('idTenant')
                            ]);

                            break;
                    }
                }
            } else {
                return response()->json([
                    'table_error' => 1
                ]);
            }

            //Trừ quota_user khi đơn hàng có promotion quà tặng
            $mOrderApp->subtractQuotaUsePromotion($request->order_id);
            //remove promotion log
            $mPromotionLog->removeByOrder($request->order_id);

            if (!isset($request->custom_price) || $request->custom_price == 0) {
                //Lấy thông tin CTKM dc áp dụng cho đơn hàng
                $getPromotionLog = $mOrderApp->groupQuantityObjectBuy($arrObjectBuy);
                //Insert promotion log
                $arrPromotionLog = $getPromotionLog['promotion_log'];
                $mPromotionLog->insert($arrPromotionLog);
                //Cộng quota_use promotion quà tặng
                $arrQuota = $getPromotionLog['promotion_quota'];
                $mOrderApp->plusQuotaUsePromotion($arrQuota);
            }

            $amount_bill = str_replace(',', '', $request->amount_bill);

            if ($request->amount_all != '') {
                $amount_receipt_all = str_replace(',', '', $request->amount_all);
                $amount_receipt_all > $amount_bill ? $amount_receipt_all = $amount_bill : $amount_receipt_all;
            } else {
                $amount_receipt_all = 0;
            }
            $receipt_type = $request->receipt_type;
            $status = '';
            if ($amount_receipt_all >= $amount_bill) {
                $status = 'paid';
            } else {
                //Cập nhật trạng thái đơn hàng thanh toán còn thiếu
                $this->order->edit(['process_status' => 'pay-half'], $id_order);
            }
            if ($amount_bill != 0) {
                if ($amount_receipt_all < $amount_bill) {
                    //Check KH là hội viên
                    if ($request->customer_id != 1) {
                        //Check cấu hình thanh toán nhiều lần
                        $check_info = $this->spaInfo->getInfoSpa();
                        if ($check_info['is_part_paid'] == 1) {
                            $status = 'paid';
                            //insert customer debt
                            $data_debt = [
                                'customer_id' => $request->customer_id,
                                'debt_code' => 'debt',
                                'staff_id' => Auth::id(),
                                'note' => $request->note,
                                'debt_type' => 'order',
                                'order_id' => $id_order,
                                'status' => 'unpaid',
                                'amount' => $amount_bill - $amount_receipt_all,
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id()
                            ];
                            $debt_id = $this->customer_debt->add($data_debt);
                            //update debt code
                            $day_code = date('dmY');
                            if ($debt_id < 10) {
                                $debt_id = '0' . $debt_id;
                            }
                            $debt_code = [
                                'debt_code' => 'CN_' . $day_code . $debt_id
                            ];
                            $this->customer_debt->edit($debt_code, $debt_id);
                        } else {
                            return response()->json([
                                'amount_detail_small' => 1,
                                'message' => __('Số tiền không hợp lệ')
                            ]);
                        }
                    } else {
                        return response()->json([
                            'amount_detail_small' => 1,
                            'message' => __('Số tiền không hợp lệ')
                        ]);
                    }
                }
            }

            $mReceipt = new ReceiptTable();
            $mReceiptDetail = new ReceiptDetailTable();
            // get receipt by order id => remove receipt and receipt detail
            $dataReceipt = $mReceipt->getItem($request->order_id);
            if ($dataReceipt != null) {
                $mReceipt->removeReceipt($request->order_id);
                $mReceiptDetail->removeReceiptDetail($dataReceipt['receipt_id']);
            }
            $data_receipt = [
                'customer_id' => $request->customer_id,
                'staff_id' => Auth::id(),
                'object_id' => $id_order,
                'object_type' => 'order',
                'order_id' => $id_order,
                //                'total_money' => $request->total_bill,
                'total_money' => $amount_receipt_all,
                'voucher_code' => $request->voucher_bill,
                'status' => $status,
                'is_discount' => 1,
                'amount' => $amount_receipt_all,
                'amount_paid' => $amount_receipt_all,
                'amount_return' => str_replace(',', '', $request->amount_return),
                'note' => $request->note,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'receipt_type_code' => 'RTC_ORDER',
                'object_accounting_type_code' => $orderCode, // order code
                'object_accounting_id' => $id_order, // order id
            ];
            if ($request->voucher_bill != null) {
                $data_receipt['discount'] = $request->discount_bill;
            } else {
                $data_receipt['custom_discount'] = $request->discount_bill;
            }
            $receipt_id = $this->receipt->add($data_receipt);
            $day_code = date('dmY');
            $data_code = [
                'receipt_code' => 'TT_' . $day_code . $receipt_id
            ];
            //Chỉnh sửa phiếu thu
            $this->receipt->edit($data_code, $receipt_id);

            if ($request->table_add != null) {
                foreach ($request->table_add as $key => $value) {
                    if ($value['object_type'] == 'member_card') {
                        $data_receipt_detail = [
                            'receipt_id' => $receipt_id,
                            'cashier_id' => Auth::id(),
                            //                            'receipt_type' => 'member_card',
                            'payment_method_code' => 'MEMBER_CARD',
                            'card_code' => $v['object_code'],
                            'amount' => $v['amount'],
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ];
                        $this->receipt_detail->add($data_receipt_detail);
                    }
                }
            }
            // Chi tiết thanh toán
            $arrMethodWithMoney = $request->array_method;
            $mReceiptOnline = new ReceiptOnlineTable();
            $mPaymentMethod = new \Modules\Payment\Models\PaymentMethodTable();

            $isNotifyMinAccount = 0;

            foreach ($arrMethodWithMoney as $methodCode => $money) {
                $itemMethod = $mPaymentMethod->getPaymentMethodByCode($methodCode);
                if ($money > 0) {
                    $dataReceiptDetail = [
                        'receipt_id' => $receipt_id,
                        'cashier_id' => Auth::id(),
                        //                        'receipt_type' => 'cash',
                        'amount' => $money,
                        'payment_method_code' => $methodCode,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ];
                    if ($methodCode == 'MEMBER_MONEY') {
                        // Check số tiền thành viên
                        if ($money <= $amount_bill) { // trừ tiên thành viên
                            if ($money < $request->member_money) {
                                //Lưu chi tiết thanh toán
                                $this->receipt_detail->add($dataReceiptDetail);
                                //Lấy thông tin khách hàng
                                $customerMoney = $this->customer->getItem($request->customer_id);
                                //Cập nhật tiền KH
                                $this->customer->edit([
                                    'account_money' => $customerMoney['account_money'] - $money
                                ], $request->customer_id);

                                $mConfig = app()->get(ConfigTable::class);
                                //Lấy cấu hình số tiền tối thiểu
                                $configMinAccount = $mConfig->getInfoByKey('money_account_min')['value'];

                                if (($customerMoney['account_money'] - $money) <= $configMinAccount) {
                                    $isNotifyMinAccount = 1;
                                }
                                //Lưu log - tiền
                                $mBranchMoneyLog->add([
                                    "customer_id" => $request->customer_id,
                                    "branch_id" => Auth()->id(),
                                    "source" => "member_money",
                                    "type" => 'subtract',
                                    "money" => $money,
                                    "screen" => 'order',
                                    "screen_object_code" => $orderCode
                                ]);
                            } else {
                                return response()->json([
                                    'error_account_money' => 1,
                                    'message' => __('Số tiền còn lại trong tài khoản không đủ'),
                                    'money' => $request->member_money
                                ]);
                            }
                        } else {
                            return response()->json([
                                'money_large_moneybill' => 1,
                                'message' => __('Tiền tài khoản lớn hơn tiền thanh toán')
                            ]);
                        }
                    } elseif ($methodCode == 'VNPAY') {
                        $this->receipt_detail->add($dataReceiptDetail);
                        // update receipt_id of receipt online
                        $mReceiptOnline->updateReceiptOnlineByTypeAndOrderId([
                            'receipt_id' => $receipt_id,
                            'status' => 'success'
                        ], 'order', $request->order_id, $methodCode);
                    } elseif ($methodCode == 'TRANSFER') {
                        $this->receipt_detail->add($dataReceiptDetail);
                        // get receipt_online of method/order
                        $dataReceiptOnline = $mReceiptOnline->getReceiptOnlineByTypeAndOrderId('order', $id_order, $methodCode);
                        if ($dataReceiptOnline != null) {
                            // update status, receipt_id of receipt_online
                            $mReceiptOnline->updateReceiptOnlineByTypeAndOrderId([
                                'amount_paid' => $money,
                                'receipt_id' => $receipt_id,
                                'status' => 'success'
                            ], 'order', $id_order, $methodCode);
                        } else {
                            // create status, receipt_id of receipt_online
                            $dataReceiptOnline = [
                                'receipt_id' => $receipt_id,
                                'object_type' => 'order',
                                'object_id' => $id_order,
                                'object_code' => $orderCode,
                                'payment_method_code' => $methodCode,
                                'amount_paid' => $money,
                                'payment_time' => Carbon::now(),
                                'status' => 'success',
                                'performer_name' => Auth()->user()->full_name,
                                'performer_phone' => Auth()->user()->phoné,
                                'type' => $itemMethod['payment_method_type'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                            $mReceiptOnline->createReceiptOnline($dataReceiptOnline);
                        }
                    } else {
                        $this->receipt_detail->add($dataReceiptDetail);
                    }
                }
            }

            //Insert log cập nhật trang thái lịch hẹn
            $this->insertLogEdit($request->customer_appointment_id, 'finish');
            //Cập nhật trang thái lịch hẹn
            $data_app = [
                'status' => 'finish'
            ];
            $this->customer_appointment->edit($data_app, $request->customer_appointment_id);
            $data_print = [];
            if (count($list_card_print) > 0) {
                foreach ($list_card_print as $k => $v) {
                    $get_cus_card = $this->service_card_list->searchCard($v);
                    $get_sv_card = $this->service_card->getServiceCardInfo($get_cus_card['service_card_id']);

                    $data_print[] = [
                        'customer_id' => $request->customer_id,
                        'type' => $get_sv_card['service_card_type'],
                        'card_name' => $get_cus_card['card_name'],
                        'card_code' => $get_cus_card['code'],
                        'number_using' => $get_sv_card['number_using'],
                        'date_using' => $get_sv_card['date_using'],
                        'money' => $get_sv_card['money'],
                        'service_card_id' => $get_sv_card['service_card_id'],
                    ];
                }
            }
            //TẠO PHIẾU XUẤT KHO
            $listOrderProduct = $this->order_detail->getValueByOrderIdAndObjectType($id_order, 'product');
            $listService = $this->order_detail->getValueByOrderIdAndObjectType($id_order, 'service');
            $listServiceMaterials = [];
            foreach ($listService as $item) {
                //Lấy sản phẩm đi kèm dịch vụ.
                $serviceMaterial = $this->serviceMaterial->getItem($item['object_id']);
                foreach ($serviceMaterial as $value) {
                    $currentPrice = $this->product_branch_price->getProductBranchPriceByCode(Auth::user()->branch_id, $value['material_code'])['new_price'];
                    $listServiceMaterials[] = [
                        'product_code' => $value['material_code'],
                        'quantity' => $item['quantity'] * $value['quantity'],
                        'current_price' => $currentPrice,
                        'total' => $value['quantity'] * $currentPrice * $item['quantity']
                    ];
                }
            }
            $checkWarehouse = $this->warehouse->getWarehouseByBranch(Auth::user()->branch_id);
            $warehouseId = 0;
            if (count($checkWarehouse) == 1) {
                $warehouseId = $checkWarehouse[0]['warehouse_id'];
            } else {
                foreach ($checkWarehouse as $item) {
                    if ($item['is_retail'] == 1) {
                        $warehouseId = $item['warehouse_id'];
                    }
                }
            }
            $dataInventoryOutput = [
                'warehouse_id' => $warehouseId,
                'po_code' => 'XK',
                'created_by' => Auth::user()->staff_id,
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 'success',
                'note' => '',
                'type' => 'retail',
                'object_id' => $id_order
            ];
            $idInventoryOutput = $this->inventoryOutput->add($dataInventoryOutput);
            $idCode = $idInventoryOutput;
            if ($idInventoryOutput < 10) {
                $idCode = '0' . $idCode;
            }
            $this->inventoryOutput->edit(['po_code' => $this->code->codeDMY('XK', $idCode)], $idInventoryOutput);
            // Lấy thông tin bán âm
            $mConfig = new ConfigTable();
            $configSellMinus = $mConfig->getInfoByKey('sell_minus');
            $sellMinus = 1;
            $configSellMinus != null ? $sellMinus = $configSellMinus['value'] : $sellMinus = 1;
            // Danh sách sản phẩm
            foreach ($listOrderProduct as $item) {
                $dataInventoryOutputDetail = [
                    'inventory_output_id' => $idInventoryOutput,
                    'product_code' => $item['object_code'],
                    'quantity' => $item['quantity'],
                    'current_price' => $item['price'],
                    'total' => $item['amount'],
                ];
                $idIOD = $this->inventoryOutputDetail->add($dataInventoryOutputDetail);
                //Trừ tồn kho.
                $productId = $this->productChild->getProductChildByCode($item['object_code'])['product_child_id'];
                $checkProductInventory = $this->productInventory->checkProductInventory($item['object_code'], $warehouseId);
                $quantityss = $checkProductInventory != null ? $checkProductInventory['quantity'] - $item['quantity'] : 0;;
                // Nếu k cho bán âm thì kiểm tra số lượng trong kho có đủ cho đơn hàng không
                if ($sellMinus == 0 && $quantityss < 0) {
                    // Lấy tên sản phẩm
                    DB::rollback();
                    return response()->json([
                        'error' => false,
                        'message' => __("Trong kho không đủ sản phẩm ") . $productId['product_child_name']
                    ]);
                }
                if ($checkProductInventory != null) {
                    $dataEditProductInventory = [
                        'product_id' => $productId,
                        'product_code' => $item['object_code'],
                        'warehouse_id' => $warehouseId,
                        'export' => $item['quantity'] + $checkProductInventory['export'],
                        'quantity' => $quantityss,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_by' => Auth::id(),
                    ];
                    $this->productInventory->edit($dataEditProductInventory, $checkProductInventory['product_inventory_id']);
                } else {
                    if ($productId != null) {
                        $dataEditProductInventoryInsert = [
                            'product_id' => $productId,
                            'product_code' => $item['object_code'],
                            'warehouse_id' => $warehouseId,
                            'import' => 0,
                            'export' => $item['quantity'],
                            'quantity' => $quantityss,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ];
                        $this->productInventory->add($dataEditProductInventoryInsert);
                    }
                }
            }

            if (count($listServiceMaterials) > 0) {
                foreach ($listServiceMaterials as $item) {
                    $dataInventoryOutputDetail = [
                        'inventory_output_id' => $idInventoryOutput,
                        'product_code' => $item['product_code'],
                        'quantity' => $item['quantity'],
                        'current_price' => $item['current_price'],
                        'total' => $item['total'],
                    ];
                    $idIOD = $this->inventoryOutputDetail->add($dataInventoryOutputDetail);

                    //Trừ tồn kho.
                    $productId = $this->productChild->getProductChildByCode($item['product_code'])['product_child_id'];
                    $checkProductInventory = $this->productInventory->checkProductInventory($item['product_code'], $warehouseId);
                    $quantitysss = $checkProductInventory != null ? $checkProductInventory['quantity'] - $item['quantity'] : 0;

                    if ($checkProductInventory != null) {
                        $dataEditProductInventory = [
                            'product_id' => $productId,
                            'product_code' => $item['product_code'],
                            'warehouse_id' => $warehouseId,
                            'export' => $item['quantity'] + $checkProductInventory['export'],
                            'quantity' => $quantitysss,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => Auth::id(),
                        ];
                        $this->productInventory->edit($dataEditProductInventory, $checkProductInventory['product_inventory_id']);
                    } else {
                        if ($productId != null) {
                            $dataEditProductInventoryInsert = [
                                'product_id' => $productId,
                                'product_code' => $item['product_code'],
                                'warehouse_id' => $warehouseId,
                                'import' => 0,
                                'export' => $item['quantity'],
                                'quantity' => $quantitysss,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id()
                            ];
                            $this->productInventory->add($dataEditProductInventoryInsert);
                        }
                    }
                }
            }


            $checkSendSms = $this->smsConfig->getItemByType('paysuccess');
            // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
            if (isset($request->arrAppointment)) {
                $arrAppointment = $request->arrAppointment;
                if ($arrAppointment['checked'] == 1) {
                    // Thêm lịch hẹn
                    $repoOrderApp = app()->get(OrderAppRepo::class);
                    $result = $repoOrderApp->_addQuickAppointment($arrAppointment, $request->customer_id);
                    if ($result['error'] == false) {
                        return response()->json($result);
                    }
                }
            }
            // END UPDATE

            // Thêm phiếu bảo hành điện tử
            $customer = $this->customer->getItem($request->customer_id);
            $dataTableAdd = $request->table_add;
            if ($customer['customer_code'] != null) {
                $this->customer_appointment->addWarrantyCard($customer['customer_code'], $id_order, $orderCode, $dataTableAdd);
            }

            $mOrder = app()->get(OrderRepositoryInterface::class);
            //Lưu log dự kiến nhắc sử dụng lại
            $mOrder->insertRemindUse($id_order, $request->customer_id, $arrRemindUse);

            DB::commit();

            //Send notification
            if ($request->customer_id != 1) {
                //Insert email log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_EMAIL_CUSTOMER,
                    'event' => 'is_event',
                    'key' => 'paysuccess',
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Insert sms log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_SMS_CUSTOMER,
                    'key' => 'paysuccess',
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Gửi thông báo khách hàng
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_CUSTOMER,
                    'key' => 'order_status_S',
                    'customer_id' => $request->customer_id,
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Cộng điểm khi mua hàng trực tiếp
                $mPlusPoint = new LoyaltyApi();
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $request->customer_id,
                    'rule_code' => 'order_direct',
                    'object_id' => $id_order
                ]);
                //Lưu log ZNS (thanh toán thành công)
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'order_thanks',
                    'customer_id' => $request->customer_id,
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);
                if ($isNotifyMinAccount == 1) {
                    //Gửi thông báo tiền trong tài khoản sắp hết
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_NOTIFY_CUSTOMER,
                        'key' => 'money_account_min',
                        'customer_id' => $request->customer_id,
                        'object_id' => $id_order,
                        'tenant_id' => session()->get('idTenant')
                    ]);
                }
            }
            //Thông báo NV khi có đơn hàng mới
            App\Jobs\FunctionSendNotify::dispatch([
                'type' => SEND_NOTIFY_STAFF,
                'key' => 'order_status_W',
                'customer_id' => $request->customer_id,
                'object_id' => $id_order,
                'branch_id' => Auth()->user()->branch_id,
                'tenant_id' => session()->get('idTenant')
            ]);
            //Tính điểm thưởng khi thanh toán
            $mBookingApi = new BookingApi();
            if ($amount_receipt_all >= $amount_bill) {
                $mBookingApi->plusPointReceiptFull(['receipt_id' => $receipt_id]);
            } else {
                $mBookingApi->plusPointReceipt(['receipt_id' => $receipt_id]);
            }

            return response()->json([
                'error' => true,
                'message' => __('Thanh toán thành công'),
                'print_card' => $data_print,
                'orderId' => $id_order,
                'isSMS' => $checkSendSms['is_active']
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
                'message' => $e->getMessage() . $e->getFile() . $e->getLine()
            ]);
        }
    }

    public function confirmAction($id)
    {
        $check = $this->customer_appointment->getItemEdit(Crypt::decryptString($id));
        if ($check['status'] == 'new') {
            $data = [
                'status' => 'confirm'
            ];
            $this->customer_appointment->edit($data, Crypt::decryptString($id));
        }
    }

    public function indexCancelAction()
    {
        $list_cancel = $this->customer_appointment->listCancel();

        return view('admin::customer-appointment.list-cancel.index', [
            'LIST' => $list_cancel,
            'FILTER' => $this->filtersCancel(),
        ]);
    }

    protected function filtersCancel()
    {
        $optionSource = $this->appointment_source->getOption();
        $source = (["" => __('Chọn nguồn')]) + $optionSource;
        return [
            'customer_appointments$appointment_source_id' => [
                'data' => $source
            ],
        ];
    }

    public function listCancelAction(Request $request)
    {
        $filter = $request->only([
            'page', 'display', 'search',
            'customer_appointments$appointment_source_id', 'created_at'
        ]);
        $list_cancel = $this->customer_appointment->listCancel($filter);

        return view('admin::customer-appointment.list-cancel.list', [
            'LIST' => $list_cancel,
            'page' => $filter['page'],
            'FILTER' => $this->filtersCancel(),
        ]);
    }

    public function indexLateAction()
    {
        $list_late = $this->customer_appointment->listLate();
        return view('admin::customer-appointment.list-late.index', [
            'LIST' => $list_late,
            'FILTER' => $this->filtersLate(),
        ]);
    }

    protected function filtersLate()
    {
        $optionSource = $this->appointment_source->getOption();
        $source = (["" => __('Chọn nguồn')]) + $optionSource;
        return [
            'customer_appointments$appointment_source_id' => [
                'data' => $source
            ],
            'customer_appointments$status' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    'new' => __('Mới'),
                    'confirm' => __('Xác nhận'),
                    'wait' => __('Chờ phục vụ'),
                    'finish' => __('Hoàn thành')
                ]
            ],
        ];
    }

    public function listLateAction(Request $request)
    {
        $filter = $request->only([
            'page', 'display', 'search',
            'customer_appointments$appointment_source_id', 'created_at', 'customer_appointments$status'
        ]);
        $list_late = $this->customer_appointment->listLate($filter);

        return view('admin::customer-appointment.list-late.list', [
            'LIST' => $list_late,
            'page' => $filter['page'],
        ]);
    }

    /**
     * Lưu log khi tạo lịch hẹn
     *
     * @param $appointmentId
     * @param $status
     */
    public function insertLogAdd($appointmentId, $status)
    {
        $mAppointmentLog = new CustomerAppointmentLogTable();

        switch ($status) {
            case 'new':
                //Insert log new
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'new',
                    'note' => __('Tạo lịch hẹn mới'),
                    'created_by' => Auth()->id()
                ]);
                break;
            case 'confirm':
                //Insert log new
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'new',
                    'note' => __('Tạo lịch hẹn mới'),
                    'created_by' => Auth()->id()
                ]);
                //Insert log confirm
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'confirm',
                    'note' => __('Xác nhận lịch hẹn'),
                    'created_by' => Auth()->id()
                ]);
                break;
            case 'wait':
                //Insert log new
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'new',
                    'note' => __('Tạo lịch hẹn mới'),
                    'created_by' => Auth()->id()
                ]);
                //Insert log confirm
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'confirm',
                    'note' => __('Xác nhận lịch hẹn'),
                    'created_by' => Auth()->id()
                ]);
                //Insert log wait
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'wait',
                    'note' => __('Lịch hẹn được cập nhật đang phục vụ từ backend'),
                    'created_by' => Auth()->id()
                ]);
                break;
        }
    }

    /**
     * Lưu log khi chỉnh sửa lịch hẹn
     *
     * @param $appointmentId
     * @param $status
     */
    public function insertLogEdit($appointmentId, $status)
    {
        $mAppointmentLog = new CustomerAppointmentLogTable();
        //Check trạng thái có thay đổi thì mới insert log

        $checkStatus = $this->customer_appointment->getItemEdit($appointmentId);

        if ($checkStatus['status'] != $status) {
            switch ($status) {
                case 'confirm':
                    //Check log new chưa có thì insert
                    $checkLogNew = $mAppointmentLog->getLogByStatus($appointmentId, 'new');
                    if ($checkLogNew == null) {
                        $mAppointmentLog->add([
                            'customer_appointment_id' => $appointmentId,
                            'created_type' => 'backend',
                            'status' => 'new',
                            'note' => __('Tạo lịch hẹn mới'),
                            'created_by' => Auth()->id()
                        ]);
                    }
                    //Insert log confirm
                    $mAppointmentLog->add([
                        'customer_appointment_id' => $appointmentId,
                        'created_type' => 'backend',
                        'status' => 'confirm',
                        'note' => __('Xác nhận lịch hẹn'),
                        'created_by' => Auth()->id()
                    ]);
                    break;
                case 'wait':
                    //Check log new chưa có thì insert
                    $checkLogNew = $mAppointmentLog->getLogByStatus($appointmentId, 'new');
                    if ($checkLogNew == null) {
                        $mAppointmentLog->add([
                            'customer_appointment_id' => $appointmentId,
                            'created_type' => 'backend',
                            'status' => 'new',
                            'note' => __('Tạo lịch hẹn mới'),
                            'created_by' => Auth()->id()
                        ]);
                    }
                    //Check log confirm chưa có thì insert
                    $checkLogConfirm = $mAppointmentLog->getLogByStatus($appointmentId, 'confirm');
                    if ($checkLogConfirm == null) {
                        $mAppointmentLog->add([
                            'customer_appointment_id' => $appointmentId,
                            'created_type' => 'backend',
                            'status' => 'confirm',
                            'note' => __('Xác nhận lịch hẹn'),
                            'created_by' => Auth()->id()
                        ]);
                    }
                    //Insert log wait
                    $mAppointmentLog->add([
                        'customer_appointment_id' => $appointmentId,
                        'created_type' => 'backend',
                        'status' => 'wait',
                        'note' => __('Lịch hẹn được cập nhật đang phục vụ từ backend'),
                        'created_by' => Auth()->id()
                    ]);
                    break;
                case 'finish':
                    //Check log new chưa có thì insert
                    $checkLogNew = $mAppointmentLog->getLogByStatus($appointmentId, 'new');
                    if ($checkLogNew == null) {
                        $mAppointmentLog->add([
                            'customer_appointment_id' => $appointmentId,
                            'created_type' => 'backend',
                            'status' => 'new',
                            'note' => __('Tạo lịch hẹn mới'),
                            'created_by' => Auth()->id()
                        ]);
                    }
                    //Check log confirm chưa có thì insert
                    $checkLogConfirm = $mAppointmentLog->getLogByStatus($appointmentId, 'confirm');
                    if ($checkLogConfirm == null) {
                        $mAppointmentLog->add([
                            'customer_appointment_id' => $appointmentId,
                            'created_type' => 'backend',
                            'status' => 'confirm',
                            'note' => __('Xác nhận lịch hẹn'),
                            'created_by' => Auth()->id()
                        ]);
                    }
                    //Check log wait chưa có thì insert
                    $checkLogWait = $mAppointmentLog->getLogByStatus($appointmentId, 'wait');
                    if ($checkLogWait == null) {
                        $mAppointmentLog->add([
                            'customer_appointment_id' => $appointmentId,
                            'created_type' => 'backend',
                            'status' => 'wait',
                            'note' => __('Lịch hẹn được cập nhật đang phục vụ từ backend'),
                            'created_by' => Auth()->id()
                        ]);
                    }
                    //Insert log finish
                    $mAppointmentLog->add([
                        'customer_appointment_id' => $appointmentId,
                        'created_type' => 'backend',
                        'status' => 'finish',
                        'note' => __('Lịch hẹn được cập nhật hoàn tất từ backend'),
                        'created_by' => Auth()->id()
                    ]);
                    break;
                case 'cancel':
                    //Insert log finish
                    $mAppointmentLog->add([
                        'customer_appointment_id' => $appointmentId,
                        'created_type' => 'backend',
                        'status' => 'cancel',
                        'note' => __('Lịch hẹn được hủy từ backend'),
                        'created_by' => Auth()->id()
                    ]);
                    break;
            }
        }
    }

    /**
     * View chi tiết lịch hẹn (load trang)
     *
     * @param $appointmentId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function detailBookingAction($appointmentId)
    {
        $mAppointment = new CustomerAppointmentTable();
        $mAppointmentDetail = new CustomerAppointmentDetailTable();
        //Lấy thông tin lịch hẹn
        $info = $mAppointment->getInfo($appointmentId);
        //Lấy thông tin chi tiết lịch hẹn
        $detail = $mAppointmentDetail->getDetail($appointmentId);

        $arrServiceDetail = [];
        $staffService = [];
        $roomService = [];
        $arrMemberCardDetail = [];
        $staffMemberCard = [];
        $roomMemberCard = [];
        foreach ($detail as $v) {
            if ($v['object_type'] == 'service') {
                $arrServiceDetail[] = $v['object_id'];
                $staffService[] = $v['staff_id'];
                $roomService[] = $v['room_id'];
            } else if ($v['object_type'] == 'member_card') {
                $arrMemberCardDetail[] = $v['object_id'];
                $staffMemberCard[] = $v['staff_id'];
                $roomMemberCard[] = $v['room_id'];
            }
        }
        //Kiểm tra lịch hẹn có trùng với chi nhánh user login ko
        $sameBranch = 0;
        if ($info['branch_id'] == Auth::user()->branch_id) {
            $sameBranch = 1;
        }
        //Lấy giờ đặt lịch mới
        $time_new = [
            '07:00', '07:15', '07:30', '07:45', '08:00', '08:15', '08:30', '08:45', '09:00', '09:15', '09:30', '09:45',
            '10:00', '10:15', '10:30', '10:45', '11:00', '11:15', '11:30', '11:45', '12:00', '12:15', '12:30', '12:45', '13:00',
            '13:15', '13:30', '13:45', '14:00', '14:15', '14:30', '14:45', '15:00', '15:15', '15:30', '15:45', '16:00', '16:15',
            '16:30', '16:45', '17:00', '17:15', '17:30', '17:45', '18:00', '18:15', '18:30', '18:45', '19:00', '19:15', '19:30',
            '19:45', '20:00', '20:15', '20:30', '20:45', '21:00', '21:15', '21:30', '21:45', '22:00'
        ];
        $time_now = date("H:i");
        $arr_time = [];
        foreach ($time_new as $item) {
            if ($item > $time_now) {
                $arr_time[] = [
                    'time_new' => $item
                ];
            }
        }
        //Lấy option nhân viên
        $optionStaff = $this->staff->getStaffTechnician();
        //Lấy option phòng phục vụ
        $optionRoom = $this->room->getRoomOption();
        //Lấy option dịch vụ
        $optionService = $this->service_branch_price->getOptionService(Auth()->user()->branch_id);
        //Lấy nguồn lịch hẹn
        $optionSource = $this->appointment_source->getOption();
        //Lấy cấu hình đặt lịch từ ngày đến ngày
        $mConfig = new ConfigTable();
        $configToDate = $mConfig->getInfoByKey('booking_to_date')['value'];
        //Lấy thông tin member card của KH
        $mMemberCard = new CustomerServiceCardTable();
        $mConfig = app()->get(ConfigTable::class);

        $branchId = null;
        //Lấy cấu hình 1 chi nhánh or liên chi nhánh
        $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];

        if ($configBranch == 0) {
            //Lấy chi nhánh của nv đăng nhập
            $branchId = Auth()->user()->branch_id;
        }

        $listMemberCard = $mMemberCard->getMemberCard($info['customer_id'], $branchId);
        //Lấy số tuần trong năm
        $numberWeek = 52;
        session()->put('customer_id_appointment', $info['customer_id']);

        $is_booking_past = 0;
        //Kiểm tra phân quyền đặt lịch lùi
        if (
            Auth()->user()->is_admin == 1
            || in_array('is_booking_past', session('routeList'))
        ) {
            $is_booking_past  = 1;
        }

        //        return view('admin::customer-appointment.detail-load', [
        return view('admin::customer-appointment.detail-edit-load', [
            'item' => $info,
            'detail' => $detail,
            'configToDate' => $configToDate, 'optionSource' => $optionSource,
            'optionStaff' => $optionStaff,
            'optionRoom' => $optionRoom,
            'optionService' => $optionService,
            'timeNew' => $arr_time,
            'sameBranch' => $sameBranch,
            'listMemberCard' => $listMemberCard,
            'arrServiceDetail' => $arrServiceDetail,
            'arrMemberCardDetail' => $arrMemberCardDetail,
            'staffService' => $staffService,
            'roomService' => $roomService,
            'staffMemberCard' => $staffMemberCard,
            'roomMemberCard' => $roomMemberCard,
            'numberWeek' => $numberWeek,
            'is_booking_past' => $is_booking_past
        ]);
    }


    /**
     * Lưu log khi tạo lịch hẹn
     *
     * @param $appointmentId
     * @param $status
     */
    public function getStaffByBranch(Request $request)
    {
           //Lấy option nhân viên
           $optionStaff = $this->staff->getStaffByBranch($request->branch);
           return response()->json([
            'data' => $optionStaff
           ]);
    }
}