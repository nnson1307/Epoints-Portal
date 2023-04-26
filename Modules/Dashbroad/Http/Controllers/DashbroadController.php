<?php

namespace Modules\Dashbroad\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Dashbroad\Models\DashboardComponentTable;
use Modules\Dashbroad\Models\DashboardComponentWidgetTable;
use Modules\Dashbroad\Models\DashboardTable;
use Modules\Dashbroad\Repositories\DashbroadRepositoryInterface;
use Modules\Ticket\Models\StaffQueueMapTable;
use Modules\Ticket\Models\TicketTable;

class DashbroadController extends Controller
{

    protected $dashbroad;
    const GIA_KHANG_TENANT_ID = '2d31780a0108715b3fa530aaaaa99bda';

    protected $order;

    public function __construct(DashbroadRepositoryInterface $dashbroad, OrderRepositoryInterface $orders)
    {
        $this->dashbroad = $dashbroad;
        $this->order = $orders;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        $status = 'new';
        $orders = $this->dashbroad->getOrders($status);
        $appointment = $this->dashbroad->getAppointment($status);
        $totalcustomer = $this->dashbroad->getTotalCustomer();
        $totalcustomerOnDay = $this->dashbroad->getTotalCustomerOnDay();
        $totalcustomerOnMonth = $this->dashbroad->getTotalCustomerOnMonth();
        $data = $this->dashbroad->dataViewIndex();

        //Lấy tổng tiếp nhận
        $totalCustomerRequest = 0;
        if (in_array('call-center.list', session('routeList'))) {
            $totalCustomerRequest = $this->dashbroad->getTotalCustomerRequest()->total;
        }
        //Danh sách service đã được đặt/ service
        $service = $this->dashbroad->getService();

        // lấy cấu hình dashboard
        $mDashboard = new DashboardTable();
        $mDashboardComponent = new DashboardComponentTable();
        $mDashboardComponentWidget = new DashboardComponentWidgetTable();
        $activeDashboard = $mDashboard->getActiveDashboard();
        $idDashboard = 0; // mặc định lấy dashboard tiêu chuẩn
        if ($activeDashboard != null) {
            $idDashboard = $activeDashboard['dashboard_id'];
        }
        $lstComponentDefault = $mDashboardComponent->getComponent($idDashboard);
        foreach ($lstComponentDefault as $key => $value) {
            $lstComponentWidget = $mDashboardComponentWidget->getWidgetOfComponent($value['dashboard_component_id']);
            $lstComponentDefault[$key]['widget'] = $lstComponentWidget;
        }
        return view('dashbroad::index.index', [
            'orders' => $orders,
            'appointment' => $appointment,
            'totalcustomer' => $totalcustomer,
            'totalcustomerOnDay' => $totalcustomerOnDay,
            'totalcustomerOnMonth' => $totalcustomerOnMonth,
            'branch' => $data['optionBranch'],
            'customerGroup' => $data['optionCustomerGroup'],
            'department' => $data['optionDepartment'],
            'staff' => $data['optionStaff'],
            'optionPipeline' => $data['optionPipeline'],
            'optionCs' => $data['optionCs'],
            'service' => $service,
            'sumRevenueInDay' => $data['sumRevenueInDay'],
            'lstComponentDefault' => $lstComponentDefault,
            'totalCustomerRequest' => $totalCustomerRequest
        ]);
    }

    public function getListOrder(Request $request)
    {
        $param = $request->all();

        $filter['pagination'] = $param['pagination'];
        $filter['search'] = isset($param['query']['generalSearch']) ? $param['query']['generalSearch'] : null;

        $orders = $this->dashbroad->listOrder($filter);

        return response()->json($orders);
    }

    public function getListAppointment(Request $request)
    {

        $param = $request->all();

        $filter['pagination'] = $param['pagination'];
        $filter['search'] = isset($param['query']['search']) ? $param['query']['search'] : null;

        $appointment = $this->dashbroad->listAppointment($filter);

        return response()->json($appointment);
    }

    public function getListServices(Request $request)
    {

        $param = $request->all();

        $filter['pagination'] = $param['pagination'];
        $filter['search'] = isset($param['query']['search_service']) ? $param['query']['search_service'] : null;

        $services = $this->dashbroad->listServices($filter);
        $i = 0;
        $newService = [];
        foreach ($services['data'] as $item) {
            $getPrice = $this->order->getPromotionDetail('service', $item['service_code'], null, 'live', 1);
            //            dd($services['data'][$i]->toArray()['new_price']);
            $item['new_price'] = $getPrice;
            $newService[] = $item;
        }
        //        dd($newService);
        $services['data'] = $newService;
        //        dd($services['data']);
        return response()->json($services);
    }


    public function getListBirthday(Request $request)
    {

        $param = $request->all();

        $filter['pagination'] = $param['pagination'];
        $filter['search'] = isset($param['query']['search_customer']) ? $param['query']['search_customer'] : null;

        $appointment = $this->dashbroad->listBirthday($filter);
        //        dd($appointment);
        return response()->json($appointment);
    }

    public function getAppointmentByDate()
    {

        $column = [];
        for ($i = 1; $i <= 7; $i++) {
            $day = Carbon::now()->addDay($i);

            $column[] = [

                'date' => $day->format('d/m'),
                'appointment' => $this->dashbroad->getAppointmentByDate($day->format('Y-m-d'))

            ];
        }

        return response()->json($column);
    }


    public function getOrderByMonthYear(Request $request)
    {

        $param = $request->all();
        $year = Carbon::now()->year;

        $column = [];

        if (isset($param['year']) != '') {
            $year = $param['year'];
        }

        if (isset($param['month']) != '') {
            $month = $param['month'];
            $days = Carbon::createFromDate($year, $month);
            for ($i = 1; $i <= $days->daysInMonth; $i++) {
                $column[] = [
                    'month' => $i . '/' . $month,
                    'order' => $this->dashbroad->getOrderbyDateMonth($i, $month, $year)
                ];
            }
        } else {
            for ($i = 1; $i <= 12; $i++) {
                $day = Carbon::now()->month($i);

                $column[] = [
                    'month' => $day->format('m'),
                    'order' => $this->dashbroad->getOrderbyMonthYear($i, $year)
                ];
            }
        }

        return response()->json($column);
    }

    public function getOrderByObjectType(Request $request)
    {

        $param = $request->all();

        $date = Carbon::now()->format('Y-m-d');

        $fromDate = $date . ' 00:00:00';
        $todate = $date . ' 23:59:59';

        if (count($param) > 0) {
            if ($param['date'] != null) {
                $time2 = explode(" - ", $param['date']);
                $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
                $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
                $fromDate = $startTime . ' 00:00:00';
                $todate = $endTime . ' 23:59:59';
            }
        }

        $arrDate = [$fromDate, $todate];

        $service_card = $this->dashbroad->getOrderByObjectType('service_card', $arrDate);
        $services = $this->dashbroad->getOrderByObjectType('service', $arrDate);
        $products = $this->dashbroad->getOrderByObjectType('product', $arrDate);
        $member_card = $this->dashbroad->getOrderByObjectType('member_card', $arrDate);

        $arrData = array_merge($service_card, $services, $products, $member_card);

        return response()->json($arrData);
    }


    public function getTopService(Request $request)
    {

        $param = $request->all();

        $date = Carbon::now()->format('Y-m-d');

        $fromDate = $date . ' 00:00:00';
        $todate = $date . ' 23:59:59';

        if (count($param) > 0) {
            $fromDate = $param['formDate'] . ' 00:00:00';
            $todate = $param['toDate'] . ' 23:59:59';
        }

        $arrDate = [$fromDate, $todate];

        $list = $this->dashbroad->getTopService($arrDate);

        return response()->json($list);
    }


    /**
     * return data of widget dashboard ticket
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboardTicket()
    {
        $mTicket = new TicketTable();

        $mStaffQueueMap = app()->get(StaffQueueMapTable::class);

        //Lấy queue của nhân viên
        $getQueue = $mStaffQueueMap->getQueueByStaff(Auth()->id());

        $arrQueueStaff = [];

        if (count($getQueue) > 0) {
            foreach ($getQueue as $v) {
                $arrQueueStaff [] = $v['ticket_queue_id'];
            }
        }

        $filters['arr_queue_staff'] = $arrQueueStaff;
        /*
            1 => Mới
            2 => Đang xử lý
            3 => Hoàn tất
            4 => Đóng
            5 => Huỷ
            6 => Reopen
            7 => Quá hạn
        */
        // lấy số lượng qua status
        $expiredTicket = $mTicket->getTicketByStatus([7], $filters);
        $newTicket = $mTicket->getTicketByStatus([1], $filters);
        $inprocessTicket = $mTicket->getTicketByStatus([2], $filters);
        $total = $expiredTicket + $newTicket + $inprocessTicket;
        // chuyển số lượng qua phần trăm
        $number_after = 1; # làm tròn
        $expiredTicketPercent = $expiredTicket != 0 ? round(100 / $total * $expiredTicket, $number_after) : 0;
        $newTicketPercent = $newTicket != 0 ? round(100 / $total * $newTicket, $number_after) : 0;
        $inprocessTicketPercent = $inprocessTicket != 0 ? round(100 / $total * $inprocessTicket, $number_after) : 0;
        $ticketDashboad = [
            'total' => $total,
            'expiredTicket' => $expiredTicket,
            'newTicket' => $newTicket,
            'inprocessTicket' => $inprocessTicket,
            'expiredTicketPercent' => $expiredTicketPercent,
            'newTicketPercent' => $newTicketPercent,
            'inprocessTicketPercent' => $inprocessTicketPercent,
        ];

        return response()->json([
            'ticketDashboad' => $ticketDashboad,
        ]);
    }

    /**
     * Danh sách tiếp nhận yêu cầu khách hàng
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function getListCustomerRequestToDay(Request $request)
    {

        $data = $this->dashbroad->getListCustomerRequestToDay();
        $html = \View::make(
            'dashbroad::inc.list-customer-request',
            [
                'LIST' => $data['LIST'],
                'optionConfigShow' => $data['optionConfigShow']
            ]
        )->render();

        return response()->json([
            'html' => $html
        ]);
    }
}
