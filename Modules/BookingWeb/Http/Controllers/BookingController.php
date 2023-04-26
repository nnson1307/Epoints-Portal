<?php


namespace Modules\BookingWeb\Http\Controllers;


use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Admin\Models\FaqTable;
use Modules\BookingWeb\Repositories\Booking\BookingRepositoryInterface;
use Modules\BookingWeb\Repositories\Province\ProvinceRepositoryInterface;


class BookingController extends Controller
{
    protected $booking;
    protected $province;

    public function __construct(BookingRepositoryInterface $booking, ProvinceRepositoryInterface $province)
    {
        $this->booking = $booking;
        $this->province = $province;
    }

    public function indexAction()
    {
        //spa info
        $info = $this->booking->spaInfo(['spa_id' => 1])['Result']['Data'];
        $optionProvince = $this->province->getProvinceOption();

        //step branch

        $branch = $this->booking->listBranch()['Result']['Data'];

        $default_province = [];
        foreach ($branch as $item) {
            $default_province[] = $item['provinceid'];
        }


        //step time
        $array_time = [
            "07:00", "07:15", "07:30", "07:45",
            "08:00", "08:15", "08:30", "08:45",
            "09:00", "09:15", "09:30", "09:45",
            "10:00", "10:15", "10:30", "10:45",
            "11:00", "11:15", "11:30", "11:45",
            "12:00", "12:15", "12:30", "12:45",
            "13:00", "13:15", "13:30", "13:45",
            "14:00", "14:15", "14:30", "14:45",
            "15:00", "15:15", "15:30", "15:45",
            "16:00", "16:15", "16:30", "16:45",
            "17:00", "17:15", "17:30", "17:45",
            "18:00", "18:15", "18:30", "18:45",
            "19:00", "19:15", "19:30", "19:45",
            "20:00", "20:15", "20:30", "20:45",
            "21:00", "21:15", "21:30", "21:45",
            "22:00"
        ];
        $setting = $this->booking->settingTimeBooking()['Result']['Data'];
        $time_working = $this->booking->timeWorking()['Result']['Data'];

        $list_time = [];
        for ($i = 0; $i <= $setting[3]['day']; $i++) {
            $day = Carbon::now()->addDay($i);
            foreach ($time_working as $item) {
                if ($item['eng_name'] == $day->format('l')) {
                    $arr_time = [];
                    foreach ($array_time as $time) {
                        if (strtotime($time) >= strtotime($item['start_time']) and strtotime($time) <= strtotime($item['end_time'])) {
                            $arr_time[] = $time;

                        }
                    }
                    $list_time[] = [
                        'day' => $day->format('Y-m-d'),
                        'month' => $day->format('m'),
                        'name' => $day->format('l'),
                        'is_actived' => $item['is_actived'],
                        'time' => $arr_time
                    ];
                }
            }
        }
        $result_time = collect($list_time)->forPage(1, 7);

        return view('bookingweb::booking.index', [
            'optionProvince' => $optionProvince,
            'province_default' => array_unique($default_province),
            'LIST_BRANCH' => $branch,
            'LIST_TIME' => $result_time,
            'data_time' => $list_time,
            'page_time' => 1,
            'info' => $info,
        ]);
    }
    public function getPrivacyPolicy(Request $request)
    {
        $filter = $request->all();
        $lang = isset($filter['lang']) != '' ? $filter['lang'] : 'vi';
        $mFag = new FaqTable();
        $policyTerms = $mFag->getPolicyTerms('privacy_policy', $lang);
        return view('bookingweb::policy_terms.policy', [
            'policyTerms' => $policyTerms,
        ]);
    }
    public function getTermsUse(Request $request)
    {
        $filter = $request->all();
        $lang = isset($filter['lang']) != '' ? $filter['lang'] : 'vi';
        $mFag = new FaqTable();
        $policyTerms = $mFag->getPolicyTerms('terms_use', $lang);
        return view('bookingweb::policy_terms.policy', [
            'policyTerms' => $policyTerms,
        ]);
    }
    public function getUserGuide(Request $request)
    {
        $filter = $request->all();
        $lang = isset($filter['lang']) != '' ? $filter['lang'] : 'vi';
        $mFag = new FaqTable();
        $policyTerms = $mFag->getPolicyTerms('user_guide', $lang);
        return view('bookingweb::policy_terms.policy', [
            'policyTerms' => $policyTerms,
        ]);
    }
    public function filterBranchAction(Request $request)
    {
        $data = [
            'province_id' => $request->province_id,
            'district_id' => $request->district_id
        ];
        $branch = $this->booking->listBranch($data)['Result']['Data'];

        $view = view('bookingweb::booking.list.list-step1', [
            'LIST_BRANCH' => $branch
        ])->render();

        return response()->json($view);
    }

    public function pagingTimeAction(Request $request)
    {
        //step time
        $array_time = [
            "07:00", "07:15", "07:30", "07:45",
            "08:00", "08:15", "08:30", "08:45",
            "09:00", "09:15", "09:30", "09:45",
            "10:00", "10:15", "10:30", "10:45",
            "11:00", "11:15", "11:30", "11:45",
            "12:00", "12:15", "12:30", "12:45",
            "13:00", "13:15", "13:30", "13:45",
            "14:00", "14:15", "14:30", "14:45",
            "15:00", "15:15", "15:30", "15:45",
            "16:00", "16:15", "16:30", "16:45",
            "17:00", "17:15", "17:30", "17:45",
            "18:00", "18:15", "18:30", "18:45",
            "19:00", "19:15", "19:30", "19:45",
            "20:00", "20:15", "20:30", "20:45",
            "21:00", "21:15", "21:30", "21:45",
            "22:00"
        ];
        $setting = $this->booking->settingTimeBooking()['Result']['Data'];
        $time_working = $this->booking->timeWorking()['Result']['Data'];
        $list_time = [];
        for ($i = 0; $i <= $setting[3]['day']; $i++) {
            $day = Carbon::now()->addDay($i);
            foreach ($time_working as $item) {
                if ($item['eng_name'] == $day->format('l')) {
                    $arr_time = [];
                    foreach ($array_time as $time) {
                        if (strtotime($time) >= strtotime($item['start_time']) and strtotime($time) <= strtotime($item['end_time'])) {
                            $arr_time[] = $time;

                        }
                    }
                    $list_time[] = [
                        'day' => $day->format('Y-m-d'),
                        'month' => $day->format('m'),
                        'name' => $day->format('l'),
                        'is_actived' => $item['is_actived'],
                        'time' => $arr_time,
                    ];

                }
            }

        }
        $result_time = collect($list_time)->forPage($request->page, 7);


        $arr_time_tile = [];

        foreach ($result_time as $item) {
            $arr_time_tile[] = Carbon::parse($item['day'])->format('d/m');
        }


        $view = view('bookingweb::booking.paging.paging-step2', [
            'LIST_TIME' => $result_time,
            'data_time' => $list_time,
            'page_time' => $request->page,
            'title_time' => $arr_time_tile
        ])->render();

        return response()->json($view);
    }

    public function checkBranchAction(Request $request)
    {
        //step service
        $setting = $this->booking->settingTimeBooking()['Result']['Data'];
        $optionService = $this->booking->optionServiceBooking(['branch_id' => $request->branch_id])['Result']['Data'];

        $list_service = $this->booking->listServiceBooking([
            'branch_id' => $request->branch_id,
            'page' => 1
        ])['Result']['Data'];

        $view_service = view('bookingweb::booking.list.list-step3', [
            'LIST_SERVICE' => $list_service,
            'arr_service' => [],
            'setting' => $setting
        ])->render();

        //step kỹ thuật viên
        $list_staff = $this->booking->listStaffBooking([
            'branch_id' => $request->branch_id
        ])['Result']['Data'];

        $view_staff = view('bookingweb::booking.list.list-step4', [
            'LIST_STAFF' => $list_staff,
        ])->render();

        return response()->json([
            'optionService' => $optionService,
            'view_service' => $view_service,
            'view_staff' => $view_staff
        ]);
    }

    public function pagingServiceAction(Request $request)
    {
        $arr_service = [];
        if (isset($request->arr_service)) {
            $arr_service = $request->arr_service;
        }

        $setting = $this->booking->settingTimeBooking()['Result']['Data'];
        $list_service = $this->booking->listServiceBooking([
            'branch_id' => $request->branch_id,
            'page' => $request->page
        ])['Result']['Data'];

        $view_service = view('bookingweb::booking.list.list-step3', [
            'LIST_SERVICE' => $list_service,
            'arr_service' => $arr_service,
            'setting' => $setting
        ])->render();

        return response()->json($view_service);
    }

    public function filterServiceAction(Request $request)
    {
        $arr_service = [];
        if (isset($request->arr_service)) {
            $arr_service = $request->arr_service;
        }

        $list_service = $this->booking->listServiceBooking([
            'branch_id' => $request->branch_id,
            'service_id' => $request->service_id
        ])['Result']['Data'];

        $view_service = view('bookingweb::booking.list.list-step3', [
            'LIST_SERVICE' => $list_service,
            'arr_service' => $arr_service
        ])->render();

        return response()->json($view_service);
    }

    public function confirmAction(Request $request)
    {
        $data = [
            'branch_name' => $request->branch_name,
            'staff_name' => $request->staff_name,
            'date' => $request->date,
            'time' => $request->time,
            'customer_name' => $request->customer_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'description' => $request->description,
        ];


        $arr_service = [];
        if (isset($request->arr_service)) {
            foreach ($request->arr_service as $item) {
                $arr_service[] = $this->booking->listServiceBooking([
                    'branch_id' => $request->branch_id,
                    'service_id' => $item
                ])['Result']['Data']['data'][0];
            }
        }
        $view = view('bookingweb::booking.list.list-step6', [
            'data' => $data,
            'service' => $arr_service
        ])->render();

        return response()->json($view);
    }

    public function submitBookingAction(Request $request)
    {
        $param = $request->all();

        $validator = \Validator::make($param, [
            'branch_id' => 'required',
            'staff_id' => 'nullable',
            'fullname' => 'required',
            'phone' => 'required|numeric|digits_between:10,11',
            'date' => 'required',
            'time' => 'required'
        ], [

        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                '_error' => $validator->errors()->all(),
                'message' => 'Thêm thất bại'
            ]);
        } else {
            if (isset($request->service_id)) {
                $arr_service = $request->service_id;
            } else {
                $arr_service = [];
            }
            $data = [
                'branch_id' => $request->branch_id,
                'service_id' => $arr_service,
                'staff_id' => $request->staff_id,
                'date' => $request->date,
                'time' => $request->time,
                'fullname' => strip_tags($request->fullname),
                'phone' => strip_tags($request->phone),
                'email' => strip_tags($request->email),
                'description' => strip_tags($request->description)
            ];
            $this->booking->submitBooking($data);
            return response()->json([
                'error' => false,
                'message' => 'Thêm thành công'
            ]);
        }


    }

    public function infoAction()
    {
        $setting = $this->booking->settingTimeBooking()['Result']['Data'];

        $info = $this->booking->spaInfo(['spa_id' => 1])['Result']['Data'];
        $time_working = $this->booking->timeWorking()['Result']['Data'];

        $view = view('bookingweb::inc.info', [
            'info' => $info,
            'time_working' => $time_working,
            'setting' => $setting
        ])->render();

        return response()->json([
            'html' => $view
        ]);
    }
//    Phú
    public function getListSliderHeader(){
        $slider = $this->booking->getSliderHeader();
        $bannerSlider = $slider['Result']['Data'];
        unset($bannerSlider['logo']);
        $arr = [];
        foreach ($bannerSlider as $value) {
            foreach ($value as $valueItem) {
               if (strpos($valueItem['link'],'https') !== false){
//                    if (strpos($valueItem['link'],'www') === false){
//                        $valueItem['link'] = str_replace('https://','https://',$valueItem['link'] );
//                    }
                   $valueItem['link'] = $valueItem['link'];
               } elseif (strpos($valueItem['link'],'http') !== false){
//                   if (strpos($valueItem['link'],'www') === false){
//                       $valueItem['link'] = str_replace('http://','http://',$valueItem['link'] );
//                   }
                   $valueItem['link'] = $valueItem['link'];
               } else {
//                   if (strpos($valueItem['link'],'www') !== false){
//                       $valueItem['link'] = str_replace('www.','https://www.',$valueItem['link'] );
//                   } else {
                       $valueItem['link'] ='https://'.$valueItem['link'] ;
//                   }
               }
               $arr[] = $valueItem;
            }
        }
        $logo = $slider['Result']['Data']['logo']['logo'];
        $view = view('bookingweb::inc.header', [
            'slider' => $arr,
            'logo' => $logo
        ])->render();

        return response()->json([
            'html' => $view,
        ]);
    }

    public function nameSpaAction()
    {
        $name = $this->booking->spaInfo(['spa_id' => 1])['Result']['Data'];

        $view = view('bookingweb::inc.name', [
            'info' => $name,
        ])->render();

        return response()->json([
            'html' => $view
        ]);
    }


}