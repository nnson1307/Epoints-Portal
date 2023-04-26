<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 10:52 AM
 */

namespace Modules\Shift\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Department\DepartmentRepository;
use Modules\Admin\Repositories\Staffs\StaffRepository;
use Modules\Shift\Http\Requests\TimekeepingConfig\StoreRequest;
use Modules\Shift\Http\Requests\TimekeepingConfig\UpdateRequest;
use Modules\Shift\Repositories\Timekeeping\TimekeepingRepoIf;
use Modules\Shift\Repositories\TimeWorkingStaff\TimeWorkingStaffRepoInterface;


class TimekeepingController extends Controller
{
    protected $timekeeping;
    protected $branch;
    protected $department;
    protected $staff;

    public function __construct(
        TimekeepingRepoIf $timekeeping,
        BranchRepositoryInterface $branch,
        DepartmentRepository $department,
        StaffRepository $staff
    ) {
        $this->timekeeping = $timekeeping;
        $this->branch = $branch;
        $this->department = $department;
        $this->staff = $staff;
    }

    /**
     * Danh sách
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function index(Request $request)
    {
        $timeWorkingRepo = app()->get(TimeWorkingStaffRepoInterface::class);

        //Lấy cầu hình chung của ca làm việc
        $listConfig = $timeWorkingRepo->getConfigGeneral();

        //Lưu session cấu hình chung
        $this->_setSessionConfigGeneral($listConfig);

        //Danh sách châm công
        $data = $this->timekeeping->list();

        return view('shift::timekeeping.index', [
            'LIST' => $data['list'],
            'arr_branch' => $data['arr_branch'],
            'FILTER' => $this->filters(),
            'param' => $request->all(),
            'month' => $data['month'],
            'year' => $data['year'],

        ]);
    }

    /**
     * Chi tiết
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function detailStaff(Request $request)
    {

        $itemId = $request->id;
        $itemMonth = $request->m;
        $itemYear = $request->y;
        $date = Carbon::parse($itemYear . "-" . $itemMonth . "-01");
        $week_start = $date->format('Y-m-01');
        $week_end = $date->format('Y-m-t');
        $dayStart = $date->format('1');
        $dayEnd = $date->format('t');
        $arrMonth = [];
        $arrMonthSunDay = [
            'monday' => [],
            'tuesday' => [],
            'wednesday' => [],
            'thursday' => [],
            'friday' => [],
            'saturday' => [],
            'sunday' => [],
        ];
        $objStaff = $this->timekeeping->getDetailInfoStaff($itemId);
        for ($i = $dayStart; $i <= $dayEnd; $i++) {
            // printf($i);
            $dateWeek = Carbon::parse($itemYear . "-" . $itemMonth . "-" . $i);
            $week = "";
            switch ($dateWeek->dayOfWeek) {
                case 1:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['monday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;
                case 2:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['tuesday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;
                case 3:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['wednesday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;
                case 4:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['thursday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;
                case 5:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['friday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;
                case 6:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['saturday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;
                case 0:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['sunday'] = $obj;
                    array_push($arrMonth, $arrMonthSunDay);
                    $arrMonthSunDay = [
                        'monday' => [],
                        'tuesday' => [],
                        'wednesday' => [],
                        'thursday' => [],
                        'friday' => [],
                        'saturday' => [],
                        'sunday' => [],
                    ];
                    break;
                default:
            }
        }
        
        $dataStaff = $this->timekeeping->getAllStaff();

        $timeWorkingRepo = app()->get(TimeWorkingStaffRepoInterface::class);

        //Lấy cầu hình chung của ca làm việc
        $listConfig = $timeWorkingRepo->getConfigGeneral();

        //Lưu session cấu hình chung
        $this->_setSessionConfigGeneral($listConfig);

        return view('shift::timekeeping.detail', [
            'objStaff' => $objStaff,
            'arrayMonth' => $arrMonth,
            'itemMonth' => $itemMonth,
            'itemYear' => $itemYear,
            'staff' => $dataStaff->toArray()
        ]);
    }

    /**
     * Lưu cấu hình chung ca làm việc
     *
     * @param $listConfig
     */
    private function _setSessionConfigGeneral($listConfig)
    {
        //Tính đi trễ khi check in vào sau
        $lateCheckIn = 0;
        //Tính nghỉ không lương khi check in vào sau
        $offCheckIn = 0;
        //Tính về sớm khi check in ra trước
        $backSoonCheckOut = 0;
        //Tính nghỉ không lương khi check out ra trước
        $offCheckOut = 0;

        if (count($listConfig) > 0) {
            foreach ($listConfig as $v) {
                $unit = 1;

                if ($v['is_actived'] == 0) {
                    continue;
                }

                if ($v['config_general_unit'] == 'hour') {
                    $unit = 60;
                }

                switch ($v['config_general_code']) {
                    case 'late_check_in':
                        $lateCheckIn = intval($v['config_general_value']) * $unit;
                        break;
                    case 'off_check_in':
                        $offCheckIn = intval($v['config_general_value']) * $unit;
                        break;
                    case 'back_soon_check_out':
                        $backSoonCheckOut = intval($v['config_general_value']) * $unit;
                        break;
                    case 'off_check_out':
                        $offCheckOut = intval($v['config_general_value']) * $unit;
                        break;
                }
            }
        }

        //Lưu session từng case config
        session()->put('late_check_in', $lateCheckIn);
        session()->put('off_check_in', $offCheckIn);
        session()->put('back_soon_check_out', $backSoonCheckOut);
        session()->put('off_check_out', $offCheckOut);
    }

    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filters()
    {
        //Lấy data filter
        $data = $this->timekeeping->getDataFilter();

        //Phòng ban
        $groupDepartment = (['' => __('Chọn phòng ban')]) + $data['optionDepartment'];
        //Nhân viên
        $groupStaff = (['' => __('Chọn nhân viên')]) + $data['optionStaff'];

        return [
            'department_id' => [
                'data' => $groupDepartment
            ],
            'staff_id' => [
                'data' => $groupStaff
            ],
        ];
    }

    /**
     * Ajax filter, phân trang ds
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'department_id',
            'staff_id',
            'date_object',
            'branch_object',
            'years'
        ]);

        $data = $this->timekeeping->list($filter);

        return view('shift::timekeeping.list', [
            'LIST' => $data['list'],
            'page' => $filter['page'],
            'month' => $data['month'],
            'year' => $data['year'],
        ]);
    }

    public function listDetailAction(Request $request)
    {
        dd(1);
        $filter = $request->only([
            'page',
            'display',
            'department_id',
            'staff_id',
            'date_object',
            'date_year',
        ]);

        $itemId = $filter['staff_id'] ?? $request->id;
        $itemMonth = $filter['date_object'] ?? $request->m;
        $itemYear = $filter['date_year'] ?? $request->y;
        $date = Carbon::parse($itemYear . "-" . $itemMonth . "-01");
        $week_start = $date->format('Y-m-01');
        $week_end = $date->format('Y-m-t');
        $dayStart = $date->format('1');
        $dayEnd = $date->format('t');
        $arrMonth = [];
        $arrMonthSunDay = [
            'monday' => [],
            'tuesday' => [],
            'wednesday' => [],
            'thursday' => [],
            'friday' => [],
            'saturday' => [],
            'sunday' => [],
        ];
        $objStaff = $this->timekeeping->getDetailInfoStaff($itemId);
        for ($i = $dayStart; $i <= $dayEnd; $i++) {
            $dateWeek = Carbon::parse($itemYear . "-" . $itemMonth . "-" . $i);
            $week = "";
            switch ($dateWeek->dayOfWeek) {
                case 1:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['monday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;
                case 2:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['tuesday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;
                case 3:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['wednesday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;
                case 4:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['thursday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;
                case 5:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['friday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;
                case 6:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['saturday'] = $obj;
                    if ($i == $dayEnd) {
                        array_push($arrMonth, $arrMonthSunDay);
                    }
                    break;
                case 0:
                    $data = $this->timekeeping->detailStaffByWorkingDay($itemId, $itemMonth, $itemYear, $i);
                    $obj = [
                        'day' => $i . "/" . $itemMonth,
                        'data' => $this->checkShift($data)
                    ];
                    $arrMonthSunDay['sunday'] = $obj;
                    array_push($arrMonth, $arrMonthSunDay);
                    $arrMonthSunDay = [
                        'monday' => [],
                        'tuesday' => [],
                        'wednesday' => [],
                        'thursday' => [],
                        'friday' => [],
                        'saturday' => [],
                        'sunday' => [],
                    ];
                    break;
                default:
            }
        }
       dd(1);
        return view('shift::timekeeping.list_detail', [
            'objStaff' => $objStaff,
            'arrayMonth' => $arrMonth,
            'itemMonth' => $itemMonth,
            'itemYear' => $itemYear
        ]);
    }

    function checkShift($arr = [])
    {
        if (count($arr) == 0) {
            return [];
        }
        $arrData = [];
        foreach ($arr as $value => $item) {
            $strBackground = "";
            if (Carbon::createFromFormat('Y-m-d', $item['working_day'])->format('Y-m-d') > \Carbon\Carbon::now()->format('Y-m-d')) {
                $strBackground = "#D3D3D3";

                if ($item['is_deducted'] === 0) {
                    $strBackground = "#D9DCF0";
                }
                if ($item['is_deducted'] === 1) {
                    $strBackground = "#EBD4EF";
                }
            } elseif (Carbon::createFromFormat('Y-m-d', $item['working_day'])->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                $strBackground = "#DBEFDC";
                if ($item['is_check_in'] == 0 || $item['is_check_out'] == 0) {
                    $strBackground = "#FDD9D7";
                }
                if ($item['is_deducted'] === 0) {
                    $strBackground = "#D9DCF0";
                }
                if ($item['is_deducted'] === 1) {
                    $strBackground = "#EBD4EF";
                }
                if ($item['is_check_in'] === 1 && Carbon::createFromFormat('Y-m-d H:i:s', $item['working_day'] . ' ' . $item['working_time'])->addMinutes(session()->get('late_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_in_day'] . ' ' . $item['check_in_time'])) {
                    //Vào trễ
                    $strBackground = "#FFEACC";
                }
                if ($item['is_check_out'] === 1 && Carbon::createFromFormat('Y-m-d H:i:s', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_out_day'] . ' ' . $item['check_out_time'])) {
                    //Ra sớm
                    $strBackground = "#FFEACC";
                }

                if (($item['is_check_out'] === 1 && Carbon::createFromFormat('Y-m-d H:i:s', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) <= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_out_day'] . ' ' . $item['check_out_time']))
                    && ($item['is_check_in'] === 1 && Carbon::createFromFormat('Y-m-d H:i:s', $item['working_day'] . ' ' . $item['working_time'])->addMinutes(session()->get('late_check_in')) >= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_in_day'] . ' ' . $item['check_in_time']))
                ) {
                    //Ra vào đúng giờ
                    $strBackground = "#DBEFDC";
                }

                //Check có check in (nghỉ không lương so với cấu hình)
                if ($item['is_check_in'] === 1 &&
                    \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['working_day'] . ' ' . $item['working_time'])->addMinutes(session()->get('off_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_in_day'] . ' ' . $item['check_in_time'])
                    && session()->get('off_check_in') > 0) {
                    //Nghĩ không lương
                    $strBackground = "#EBD4EF";
                }

                //Check có check out (nghỉ không lương so với cấu hình)
                if ($item['is_check_out'] === 1 &&
                    \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes(session()->get('off_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_out_day'] . ' ' . $item['check_out_time'])
                    && session()->get('off_check_out') > 0) {
                    //Nghĩ không lương
                    $strBackground = "#EBD4EF";
                }
            } else {
                if ($item['is_deducted'] === 0) {
                    $strBackground = "#D9DCF0";
                } elseif ($item['is_deducted'] === 1) {
                    $strBackground = "#EBD4EF";
                } else {
                    if ($item['is_check_in'] === 0 && $item['is_check_out'] === 0) {
                        if ($item['is_deducted'] === 0) {
                            $strBackground = "#D9DCF0";
                        } else {
                            $strBackground = "#EBD4EF";
                        }
                    } else {
                        if ($item['is_check_in'] === 0 || $item['is_check_out'] === 0) {
                            $strBackground = "#FDD9D7";
                        }
                    }
                    if ($item['is_check_in'] === 1 && Carbon::createFromFormat('Y-m-d H:i:s', $item['working_day'] . ' ' . $item['working_time'])->addMinutes(session()->get('late_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_in_day'] . ' ' . $item['check_in_time'])) {
                        //Vào trễ
                        $strBackground = "#FFEACC";
                    }
                    if ($item['is_check_out'] === 1 && Carbon::createFromFormat('Y-m-d H:i:s', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_out_day'] . ' ' . $item['check_out_time'])) {
                        //Ra sớm
                        $strBackground = "#FFEACC";
                    }

                    if (($item['is_check_out'] === 1 && Carbon::createFromFormat('Y-m-d H:i:s', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) <= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_out_day'] . ' ' . $item['check_out_time']))
                        && ($item['is_check_in'] === 1 && Carbon::createFromFormat('Y-m-d H:i:s', $item['working_day'] . ' ' . $item['working_time'])->addMinutes(session()->get('late_check_in')) >= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_in_day'] . ' ' . $item['check_in_time']))
                    ) {
                        //Ra vào đúng giờ
                        $strBackground = "#DBEFDC";
                    }

                    //Check có check in (nghỉ không lương so với cấu hình)
                    if ($item['is_check_in'] === 1 &&
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['working_day'] . ' ' . $item['working_time'])->addMinutes(session()->get('off_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_in_day'] . ' ' . $item['check_in_time'])
                        && session()->get('off_check_in') > 0) {
                        //Nghĩ không lương
                        $strBackground = "#EBD4EF";
                    }

                    //Check có check out (nghỉ không lương so với cấu hình)
                    if ($item['is_check_out'] === 1 &&
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes(session()->get('off_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['check_out_day'] . ' ' . $item['check_out_time'])
                        && session()->get('off_check_out') > 0) {
                        //Nghĩ không lương
                        $strBackground = "#EBD4EF";
                    }
                }
            }
            $obj = [
                'working_end_day' => $item['working_end_day'],
                'working_end_time' => $item['working_end_time'],
                'working_day' => $item['working_day'],
                'is_check_in' => $item['is_check_in'],
                'is_check_out' => $item['is_check_out'],
                'check_in_day' => $item['check_in_day'],
                'check_out_day' => $item['check_out_day'],
                'check_in_time' => $item['check_in_time'],
                'check_out_time' => $item['check_out_time'],
                'is_deducted' => $item['is_deducted'],
                'is_ot' => $item['is_ot'],
                'number_time_back_soon' => $item['number_time_back_soon'],
                'number_late_time' => $item['number_late_time'],
                'branch_name' => $item['branch_name'],
                'branch_id' => $item['branch_id'],
                'shift_name' => $item['shift_name'],
                'shift_id' => $item['shift_id'],
                'staff_id' => $item['staff_id'],
                'time_working_staff_id' => $item['time_working_staff_id'],
                'background' => $strBackground,
                'working_time' => $item['working_time'],
                'is_close' => $item['is_close'],
                'is_approve_time_off' => $item['is_approve_time_off'],
                'time_off_days_id' => $item['time_off_days_id']

            ];
            array_push($arrData, $obj);
        }
        return $arrData;
    }
}
