<?php


namespace Modules\BookingWeb\Http\Controllers;


use Illuminate\Http\Request;
use Modules\BookingWeb\Repositories\Booking\BookingRepositoryInterface;
use Modules\BookingWeb\Repositories\Service\ServiceRepositoryInterface;

class ServiceController extends Controller
{

    protected $service;
    protected $booking;
    protected $display;
    public function __construct(ServiceRepositoryInterface $service, BookingRepositoryInterface $booking )
    {
        $this->booking = $booking;
        $this->service = $service;
        $this->display = 12;
    }
//    Lấy danh sách Group Service
    public function indexAction(Request $request)
    {
        $service = $this->service->list()['Result']['Data'];
        unset($service['product_category']);
        $display = $this->display;
        if ($request->isMethod('post') ) {
            return view('bookingweb::service.index', [
                'service' => $service,
                'service_category_id' => $request->service_category_id,
                'display' => $display
            ]);
        }
        return view('bookingweb::service.index', [
            'service' => $service,
            'display' => $display
        ]);
    }
//    Lấy danh sách service theo group
    public function getServiceGroup(Request $request){
        $param = $request->all();
        $listService = $this->service->getService($param)['Result']['Data'];
        $listServiceByGroup = $listService['data'];
        $display = $this->display;
        $view = view('bookingweb::service.list', [
            'service' => $listServiceByGroup,
            'page' => $listService,
            'display' => $display
        ])->render();

        return response()->json([
            'html' => $view
        ]);
    }

    public function getServiceDetail($id){
        $service['service_id'] = $id;
        $getServiceDetailGroup = $this->service->getServiceDetailGroup($service)['Result']['Data'];
        session()->flash('service_category_id', $service);
        $display = $this->display;
        return view('bookingweb::service.detail', [
            'serviceDetail' => $getServiceDetailGroup,
            'display' => $display
        ]);
    }

}