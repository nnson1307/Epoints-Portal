<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/15/2018
 * Time: 5:18 PM
 */

namespace Modules\Admin\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\CustomerAppointmentTime\CustomerAppointmentTimeRepositoryInterface;
use Illuminate\Http\Request;

class CustomerAppointmentTimeController extends Controller
{
    protected $customer_appointment_time;

    /**
     * CustomerAppointmentTimeController constructor.
     * @param CustomerAppointmentTimeRepositoryInterface $customer_appointment_times
     */
    public function __construct(CustomerAppointmentTimeRepositoryInterface $customer_appointment_times)
    {
        $this->customer_appointment_time = $customer_appointment_times;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction()
    {
        $get = $this->customer_appointment_time->list();
        return view('admin::customer-appointment-time.index', [
            'LIST' => $get,
            'FILTER' => $this->filters()
        ]);
    }

    /**
     * @return array
     */
    protected function filters()
    {

        return [

        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword']);
        $list = $this->customer_appointment_time->list($filter);
        return view('admin::customer-appointment-time.list', ['LIST' => $list]);
    }

    public function addAction(Request $request)
    {
        $time=$request->time;
        $testTime=$this->customer_appointment_time->testTime($time,0);
        if($testTime=="")
        {
            $data = [
                'time' => $time,
                'created_by'=>Auth::id(),
                'updated_by'=>Auth::id()
            ];
//            dd($data);
            $this->customer_appointment_time->add($data);
            return response()->json([
                'status'=>1,
                'close'=>$request->close
            ]);
        }else{
            return response()->json([
               'status'=>0
            ]);
        }

    }
}