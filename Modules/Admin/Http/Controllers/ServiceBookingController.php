<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 22/06/2021
 * Time: 10:06
 */

namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Repositories\ServiceBooking\ServiceBookingRepoInterface;

class ServiceBookingController extends Controller
{
    protected $serviceBooking;

    public function __construct(
        ServiceBookingRepoInterface $serviceBooking
    )
    {
        $this->serviceBooking = $serviceBooking;
    }

    /**
     * View ds xe đã book
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        //Lấy cấu hình đặt lịch
        $getConfig = $this->serviceBooking->getConfig();
        //Lấy ds xe đã book
        $list = $this->serviceBooking->list();

        return view('admin::service-booking.index', [
            'LIST' => $list['list'],
            'FILTER' => $this->filters($getConfig),
            "configToDate" => $getConfig['configToDate'],
            "numberWeek" => $getConfig['numberWeek']
        ]);
    }

    //Filter
    protected function filters($config)
    {
        $arrStaff = (['' => __('Chọn nhân viên')]) + $config['optionStaff'];

        return [
            'status' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    'new' => __('Mới'),
                    'confirm' => __('Xác nhận'),
                    'wait' => __('Chờ phục vụ'),
                    'finish' => __('Đã hoàn thành'),
                    'processing' => __('Đang thực hiện')
                ]
            ],
            'staff_id' => [
                'data' => $arrStaff
            ]
        ];
    }

    /**
     * Ajax filter/ phân trang xe đã book
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search', 'status', 'created_at', 'staff_id']);

        $data = $this->serviceBooking->list($filters);

        return view('admin::service-booking.list', [
            'LIST' => $data['list'],
            'page' => $filters['page']
        ]);
    }
}