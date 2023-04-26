<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 3/21/2019
 * Time: 1:38 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Customer\CustomerRepository;
use Modules\Admin\Repositories\CustomerAppointment\CustomerAppointmentRepositoryInterface;
use Modules\Admin\Repositories\CustomerAppointmentDetail\CustomerAppointmentDetailRepositoryInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;

class LayoutController extends Controller
{
    protected $customer;
    protected $customerAppointment;
    protected $order;
    protected $customerAppointmentDetail;

    public function __construct(
        CustomerRepository $customer,
        CustomerAppointmentRepositoryInterface $customerAppointment,
        OrderRepositoryInterface $order,
        CustomerAppointmentDetailRepositoryInterface $customerAppointmentDetail
    )
    {
        $this->customer = $customer;
        $this->customerAppointment = $customerAppointment;
        $this->order = $order;
        $this->customerAppointmentDetail = $customerAppointmentDetail;
    }

    public function searchDashboard(Request $request)
    {
        $keyword = $request->keyword;
        $customer = $this->customer->searchDashboard($keyword);
        $order = $this->order->searchDashboard($keyword);
        $customerAppointment = $this->customerAppointment->searchDashboard($keyword);
        $result = [];
        $count1 = 0;
        $count2 = 0;
        $count3 = 0;
        foreach ($customer as $item) {
            $result[] = [
                'id' => $item['customer_id'],
                'name' => $item['full_name'],
                'phone' => $item['phone1'],
                'customer_avatar' => $item['customer_avatar']
            ];
            $count1++;
            if ($count1 == 5) {
                break;
            }
        }

        foreach ($customerAppointment as $item) {
            $result[] = [
                'id' => $item['customer_appointment_id'],
                'name' => $item['full_name'],
                'code' => $item['customer_appointment_code'],
                'phone' => $item['phone1'],
                'customer_avatar' => $item['customer_avatar']
                ];

            $count2++;
            if ($count2 == 5) {
                break;
            }
        }

        foreach ($order as $item) {
            $result[] = [
                'id' => $item['order_id'],
                'name' => $item['full_name'],
                'code' => $item['order_code'],
                'phone' => $item['phone1'],
                'customer_avatar' => $item['customer_avatar']
                ];
            $count3++;
            if ($count3 == 5) {
                break;
            }
        }
        return view('components.inc.list-search',['DATA'=>$result]);
    }

    public function searchIndexAction(Request $request)
    {
        $keyword = $request->keyword;
        $dataCustomer = $this->customer->searchDashboard($keyword);
        $listCustomer = collect($dataCustomer)->forPage(1, 10);

        $dataOrder = $this->order->searchDashboard($keyword);

        $listOrder = collect($dataOrder)->forPage(1, 10);

        $customerAppointment = $this->customerAppointment->searchDashboard($keyword);
        $dataCustomerAppointment = $this->calculateCustomerAppointment($customerAppointment);
        $listCustomerAppointment = collect($dataCustomerAppointment)->forPage(1, 10);

        return view('admin::search-dashboard.search-dashboard',
            [
                'keyword' => $keyword,
                'dataCustomer' => $dataCustomer,
                'listCustomer' => $listCustomer,
                'dataOrder' => $dataOrder,
                'listOrder' => $listOrder,
                'dataCustomerAppointment' => $dataCustomerAppointment,
                'listCustomerAppointment' => $listCustomerAppointment,
                'page' => 1
            ]);
    }

    private function calculateCustomerAppointment($customerAppointment)
    {
        $arrayCustomerAppointment = [];
        foreach ($customerAppointment as $item) {

            $service = $this->customerAppointmentDetail->groupItemDetail($item['customer_appointment_id']);
            $strTemp = '';
            $count = 0;
            foreach ($service as $item2) {
                $count++;
                if ($item2['service_name'] != null) {
                    $phay = ', ';
                    if ($count == count($service)) {
                        $phay = '';
                    }
                    $strTemp .= $item2['service_name'] . $phay;
                }
            }
            $arrayCustomerAppointment[] = [
                'customer_appointment_code' => $item['customer_appointment_code'],
                'full_name' => $item['full_name'],
                'phone1' => $item['phone1'],
                'status' => $item['status'],
                'dateTime' => Carbon::createFromFormat('Y-m-d', $item['date'])->format('d/m/Y') . ' ' . $item['time'],
                'service' => $strTemp
            ];

        }
        return $arrayCustomerAppointment;
    }

    public function pagingCustomerAction(Request $request)
    {
        $pageCustomer = $request->page;
        $keyword = $request->keyword;
        $dataCustomer = $this->customer->searchDashboard($keyword);
        $listCustomer = collect($dataCustomer)->forPage($pageCustomer, 10);
        $contents = view('admin::search-dashboard.customer.paging', [
            'dataCustomer' => $dataCustomer,
            'listCustomer' => $listCustomer,
            'pageCustomer' => $pageCustomer
        ])->render();
        return $contents;
    }

    public function pagingCustomerAppointmentAction(Request $request)
    {
        $pageCustomerAppointment = $request->page;
        $keyword = $request->keyword;
        $customerAppointment = $this->customerAppointment->searchDashboard($keyword);
        $dataCustomerAppointment = $this->calculateCustomerAppointment($customerAppointment);
        $listCustomerAppointment = collect($dataCustomerAppointment)->forPage($pageCustomerAppointment, 10);
        $contents = view('admin::search-dashboard.customer-appointment.paging', [
            'dataCustomerAppointment' => $dataCustomerAppointment,
            'listCustomerAppointment' => $listCustomerAppointment,
            'pageCustomerAppointment' => $pageCustomerAppointment
        ])->render();
        return $contents;
    }

    public function pagingOrderAction(Request $request)
    {
        $pageOrder = $request->page;
        $keyword = $request->keyword;
        $dataOrder = $this->order->searchDashboard($keyword);
        $listOrder = collect($dataOrder)->forPage($pageOrder, 10);

        $contents = view('admin::search-dashboard.order.paging', [
            'listOrder' => $listOrder,
            'dataOrder' => $dataOrder,
            'pageOrder' => $pageOrder
        ])->render();
        return $contents;
    }


    public function detailSearchAction(Request $request)
    {
        $param = $request->only(['idSearchDashboard', 'nameSearchDashboard']);

        $id = $param['idSearchDashboard'];
        $name = $param['nameSearchDashboard'];
        $type = $result = substr($name, 0, 2);
        if ($type == 'LH') {
            return redirect()->route('admin.customer_appointment.list-day')->with('idCustomerAppointmentSearchDashboard', $id);;
        } else if ($type == 'DH') {
            return redirect()->route('admin.order.detail', $id);
        } else {
            return redirect()->route('admin.customer.detail', $id);
        }
    }
}
