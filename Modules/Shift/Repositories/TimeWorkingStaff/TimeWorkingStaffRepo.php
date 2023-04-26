<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\Shift\Repositories\TimeWorkingStaff;


use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Modules\Shift\Models\BranchTable;
use Modules\Shift\Models\CheckInChangeLogTable;
use Modules\Shift\Models\CheckOutChangeLogTable;
use Modules\Shift\Models\ConfigGeneralTable;
use Modules\Shift\Models\DepartmentTable;
use Modules\Shift\Models\MapShiftBranchTable;
use Modules\Shift\Models\RecompenseTable;
use Modules\Shift\Models\ShiftCheckInLogTable;
use Modules\Shift\Models\ShiftCheckOutLogTable;
use Modules\Shift\Models\ShiftTable;
use Modules\Shift\Models\StaffHolidayTable;
use Modules\Shift\Models\StaffTable;
use Modules\Shift\Models\TimeWorkingStaffChangeLogTable;
use Modules\Shift\Models\TimeWorkingStaffRecompenseTable;
use Modules\Shift\Models\TimeWorkingStaffTable;
use Modules\Shift\Models\ManagerWorkTable;
use Modules\Shift\Repositories\WorkSchedule\WorkScheduleRepoInterface;

class TimeWorkingStaffRepo implements TimeWorkingStaffRepoInterface
{
    protected $timeWorkingStaff;

    public function __construct(
        TimeWorkingStaffTable $timeWorkingStaff
    )
    {
        $this->timeWorkingStaff = $timeWorkingStaff;
    }

    const IS_DELETED = 1;
    const IS_OFF = 1;
    const NOT_OFF = 0;
    const GROUP_BY_SHIFT = "shift";
    const GROUP_BY_STAFF = "staff";

    /**
     * Lấy data filter
     *
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataFilter()
    {
        $mShift = app()->get(ShiftTable::class);
        $mBranch = app()->get(BranchTable::class);
        $mDepartment = app()->get(DepartmentTable::class);
        $mStaff = app()->get(StaffTable::class);

        $data = [
            'optionShift' => [],
            'optionBranch' => [],
            'optionDepartment' => [],
            'optionStaff' => []
        ];

        //Lấy option ca làm việc
        $getOptionShift = $mShift->getOption();

        if (count($getOptionShift) > 0) {
            foreach ($getOptionShift as $v) {
                $data['optionShift'][$v['shift_id']] = $v['shift_name'];
            }
        }

        //Lấy option chi nhánh
        $getOptionBranch = $mBranch->getOption();

        if (count($getOptionBranch) > 0) {
            foreach ($getOptionBranch as $v) {
                $data['optionBranch'][$v['branch_id']] = $v['branch_name'];
            }
        }

        //Lấy option phòng ban
        $getOptionDepartment = $mDepartment->getOption();

        if (count($getOptionDepartment) > 0) {
            foreach ($getOptionDepartment as $v) {
                $data['optionDepartment'][$v['department_id']] = $v['department_name'];
            }
        }

        //Lấy option nhân viên
        $getOptionStaff = $mStaff->getOption();

        if (count($getOptionStaff) > 0) {
            foreach ($getOptionStaff as $v) {
                $data['optionStaff'][$v['staff_id']] = $v['full_name'];
            }
        }

        return $data;
    }

    /**
     * Lấy ds thời gian làm việc của nhân viên
     *
     * @param array $filter
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getList($filter = [])
    {
        $now = Carbon::now();

        $startTime = null;
        $endTime = null;

        if ($filter['date_type'] == 'by_week') {
            //Theo tuần
            // $date = $now->setISODate($now->format('Y'), $filter['date_object']);

            $date = $now->setISODate($filter['years'], $filter['date_object']);
            $startTime = $date->startOfWeek()->format('Y-m-d');
            $endTime = $date->endOfWeek()->format('Y-m-d');
        } else if ($filter['date_type'] == 'by_month') {
            //Theo tháng
            // $date = Carbon::create($now->format('Y'), $filter['date_object'], 1, 0, 0, 0);
            $date = Carbon::create($filter['years'], $filter['date_object'], 1, 0, 0, 0);

            $startTime = $date->startOfMonth()->format('Y-m-d');
            $endTime = $date->endOfMonth()->format('Y-m-d');
        }

        $tStart = Carbon::parse($startTime);
        $tEnd = Carbon::parse($endTime);

        $listDay = [];

        //Lấy số ngày cách nhau
        $diffDate = $tEnd->diffInDays($tStart);

        $mStaffHoliday = app()->get(StaffHolidayTable::class);
        if ($diffDate <= 6) {
            //Theo tuần
            for ($i = 0; $i <= $diffDate; $i++) {
                $fullDay = Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dayName = Carbon::parse($startTime)->addDays($i)->format('l');

                $isHoliday = 0;

                //Check ngày lễ
                $checkHoliday = $mStaffHoliday->checkDayInHoliday($fullDay);

                if (count($checkHoliday) > 0) {
                    $isHoliday = 1;
                }

                $listDay [$fullDay] = [
                    'day_name' => __($dayName),
                    'day_format' => Carbon::parse($startTime)->addDays($i)->format('d/m'),
                    'day_full_format' => Carbon::parse($startTime)->addDays($i)->format('d/m/y'),
                    'is_holiday' => $isHoliday
                ];
            }
        } else {
            //Theo tháng
            for ($i = 0; $i <= $diffDate; $i++) {
                $fullDay = Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dayName = Carbon::parse($startTime)->addDays($i)->format('l');
                $day = Carbon::parse($startTime)->addDays($i)->format('d');

                $isHoliday = 0;

                //Check ngày lễ
                $checkHoliday = $mStaffHoliday->checkDayInHoliday($fullDay);

                if (count($checkHoliday) > 0) {
                    $isHoliday = 1;
                }

                $listDay [$fullDay] = [
                    'day_name' => __($dayName),
                    'day_format' => $day,
                    'day_full_format' => Carbon::parse($startTime)->addDays($i)->format('d/m/y'),
                    'is_holiday' => $isHoliday
                ];
            }
        }

        $filter['group_by_type'] = self::GROUP_BY_STAFF;

        $mStaff = app()->get(StaffTable::class);

        //Lấy ds nhân viên
        $data = $mStaff->getList([
            'staff_id' => isset($filter['staff_id']) ? $filter['staff_id'] : null,
            'department_id' => isset($filter['department_id']) ? $filter['department_id'] : null,
            'branch_id' => isset($filter['branch_id']) ? $filter['branch_id'] : null,
            'page' => isset($filter['page']) ? $filter['page'] : null,
            'display' => isset($filter['display']) ? $filter['display'] : null,
            
        ]);

        if (count($data->items()) > 0) {
            foreach ($data->items() as $v) {
                $shift = [];

                for ($i = 0; $i <= $diffDate; $i++) {
                    $day = Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                    //Lấy số ca làm việc theo ngày của nhân viên
                    $shift[$day] = $this->timeWorkingStaff->getTimeWorkingByStaffOnList(
                        $v['staff_id'],
                        $day,
                        isset($filter['shift_id']) ? $filter['shift_id'] : null,
                        null
                    )->toArray();
                }

                $v['shift'] = $shift;
            }
        }

        return [
            'list' => $data,
            'listDay' => $listDay,
            'number_day' => $diffDate,
            'week_in_year' => $now->weeksInYear
        ];
    }

    /**
     * Lấy ds thời gian làm việc theo ca
     *
     * @param $filter
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getListShift($filter)
    {
        $now = Carbon::now();

        $startTime = null;
        $endTime = null;
     
        if ($filter['date_type'] == 'by_week') {
            //Theo tuần
            // $date = $now->setISODate($now->format('Y'), $filter['date_object']);
            $date = $now->setISODate($filter['years'], $filter['date_object']);
            $startTime = $date->startOfWeek()->format('Y-m-d');
            $endTime = $date->endOfWeek()->format('Y-m-d');
        } else if ($filter['date_type'] == 'by_month') {
            //Theo tháng
            // $date = Carbon::create($now->format('Y'), $filter['date_object'], 1, 0, 0, 0);
            $date = Carbon::create($filter['years'], $filter['date_object'], 1, 0, 0, 0);
            $startTime = $date->startOfMonth()->format('Y-m-d');
            $endTime = $date->endOfMonth()->format('Y-m-d');
        }
       
        $tStart = Carbon::parse($startTime);
        $tEnd = Carbon::parse($endTime);

        $listDay = [];

        //Lấy số ngày cách nhau
        $diffDate = $tEnd->diffInDays($tStart);

        $mStaffHoliday = app()->get(StaffHolidayTable::class);

        if ($diffDate <= 6) {
            //Theo tuần
            for ($i = 0; $i <= $diffDate; $i++) {
                $fullDay = Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dayName = Carbon::parse($startTime)->addDays($i)->format('l');

                $isHoliday = 0;

                //Check ngày lễ
                $checkHoliday = $mStaffHoliday->checkDayInHoliday($fullDay);

                if (count($checkHoliday) > 0) {
                    $isHoliday = 1;
                }

                $listDay [$fullDay] = [
                    'day_name' => __($dayName),
                    'day_format' => Carbon::parse($startTime)->addDays($i)->format('d/m'),
                    'is_holiday' => $isHoliday
                ];
            }
           
        } else {
            //Theo tháng
            for ($i = 0; $i <= $diffDate; $i++) {
                $fullDay = Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                $dayName = Carbon::parse($startTime)->addDays($i)->format('l');
                $day = Carbon::parse($startTime)->addDays($i)->format('d');

                $isHoliday = 0;

                //Check ngày lễ
                $checkHoliday = $mStaffHoliday->checkDayInHoliday($fullDay);

                if (count($checkHoliday) > 0) {
                    $isHoliday = 1;
                }

                $listDay [$fullDay] = [
                    'day_name' => __($dayName),
                    'day_format' => $day,
                    'is_holiday' => $isHoliday
                ];
            }
        }
       
        $filter['group_by_type'] = self::GROUP_BY_SHIFT;

        $mShift = app()->get(ShiftTable::class);

        //Lấy ds ca làm việc
        $data = $mShift->getList([
            'shift_id' => isset($filter['shift_id']) ? $filter['shift_id'] : null,
            'page' => isset($filter['page']) ? $filter['page'] : null,
            'display' => isset($filter['display']) ? $filter['display'] : null
        ]);

        $filterStaffId = isset($filter['staff_id']) ? $filter['staff_id'] : null;
        $filterDepartmentId = isset($filter['department_id']) ? $filter['department_id'] : null;
        $filterBranchId = isset($filter['branch_id']) ? $filter['branch_id'] : null;

        if (count($data->items()) > 0) {
            foreach ($data->items() as $v) {
                $staff = [];

                //Lấy list nhân viên theo ca (từ ngày -> ngày)
                $getStaff = $this->timeWorkingStaff->getListStaffByShift($v['shift_id'], $startTime, $endTime, $filterStaffId, $filterDepartmentId, $filterBranchId);

                if (count($getStaff) > 0) {
                    foreach ($getStaff as $v1) {
                        $staff[$v1['staff_id']] = [
                            "staff_id" => $v1['staff_id'],
                            "staff_name" => $v1['staff_name'],
                            "phone" => $v1['phone'],
                            "branch_name" => $v1['branch_name'],
                            "department_name" => $v1['department_name'],
                            "department_id" => $v1['department_id'],
                            "is_approve_time_off" => $v1['is_approve_time_off'],
                            "time_off_days_id" => $v1['time_off_days_id'],
                        ];
                    }
                }

                if (count($staff) > 0) {
                    foreach ($staff as $v2) {
                        for ($i = 0; $i <= $diffDate; $i++) {
                            $day = Carbon::parse($startTime)->addDays($i)->format('Y-m-d');
                            //Lấy ds nhân viên làm việc theo ngày
                            $staff[$v2['staff_id']]['day'][$day] = $this->timeWorkingStaff->getTimeWorkingByShift($v2['staff_id'], $day, $v['shift_id'], $filterBranchId);
                        }
                    }
                }

                $v['staff'] = array_values($staff);
            }
        };
        // dd($data);
        return [
            'list' => $data,
            'listDay' => $listDay,
            'number_day' => $diffDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'week_in_year' => $now->isoWeeksInYear
        ];
    }

    /**
     * Show pop chọn ca làm việc
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function showPopupShift($input)
    {
        //Forget session tạm
        session()->forget('shift_temp');

        //Lấy thứ làm việc
        $getDayName = Carbon::parse($input['working_day'])->format('l');

        $mStaff = app()->get(StaffTable::class);
        //Lấy thông tin nhân viên
        $staffInfo = $mStaff->getDetail($input['staff_id']);

        //Lấy ds ca làm việc
        $list = $this->listShift([
            'day_name' => $getDayName,
            'focus_shift_id' => $input['focus_shift_id'],
            'staff_salary_type_code' => $staffInfo['staff_salary_type_code']
        ]);

        $list['staff_id'] = $input['staff_id'];
        $list['working_day'] = $input['working_day'];
        $list['view'] = $input['view'];
        $list['day_name'] = $getDayName;
        $list['focus_shift_id'] = $input['focus_shift_id'];
        $list['staff_salary_type_code'] = $staffInfo['staff_salary_type_code'];

        $html = \View::make('shift::time-working-staff.pop.pop-shift', $list)->render();

        return [
            'html' => $html,
        ];
    }

    /**
     * Danh sách ca làm việc
     *
     * @param array $filter
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function listShift($filter = [])
    {
        $mShift = app()->get(ShiftTable::class);
        $mBranch = app()->get(BranchTable::class);
        $mMapShiftBranch = app()->get(MapShiftBranchTable::class);

        //Lấy option chi nhánh
        $getOptionBranch = $mBranch->getOption();

        $arrCheckTemp = [];

        $isDisableOtType = null;

        if (isset($filter['staff_salary_type_code']) && $filter['staff_salary_type_code'] != null) {
            if (in_array($filter['staff_salary_type_code'], ['hourly', 'monthly'])) {
                $isDisableOtType = 1;
            } else {
                $isDisableOtType = 0;
            }
        }

        //Lấy session tạm
        if (session()->get('shift_temp')) {
            $arrCheckTemp = session()->get('shift_temp');
        }

        $filter['is_actived'] = 1;
        //Lấy ds ca làm việc
        $list = $mShift->getList($filter);

        if (count($list->items())) {
            foreach ($list->items() as $v) {
                //Lấy chi nhánh làm việc của ca
                $v['branch'] = $mMapShiftBranch->getInfoByShift($v['shift_id']);

                $v['is_disable_ot_type'] = $isDisableOtType;
            }
        }

        return [
            'list' => $list,
            'optionBranch' => $getOptionBranch,
            'arrCheckTemp' => $arrCheckTemp,
            'focus_shift_id' => $filter['focus_shift_id'],
            'page' => $filter['page'] ?? 1
        ];
    }

    /**
     * Chọn ca làm việc
     *
     * @param $input
     * @return mixed|void
     */
    public function chooseShift($input)
    {
        $arrCheckTemp = [];

        //Lấy session tạm
        if (session()->get('shift_temp')) {
            $arrCheckTemp = session()->get('shift_temp');
        }

        //Push ca mới chọn vào
        $arrCheckTemp[$input['shift_id']] = [
            "shift_id" => $input['shift_id'],
            "branch_id" => $input['branch_id'],
            "is_ot" => $input['is_ot'],
            "overtime_type" => $input['overtime_type'],
            "timekeeping_coefficient" => $input['timekeeping_coefficient']
        ];

        //Forget session tạm
        session()->forget('shift_temp');
        //Push session tạm mới
        session()->put('shift_temp', $arrCheckTemp);
    }

    /**
     * Bỏ chọn ca làm việc
     *
     * @param $input
     * @return mixed|void
     */
    public function unChooseShift($input)
    {
        $arrCheckTemp = [];

        //Lấy session tạm
        if (session()->get('shift_temp')) {
            $arrCheckTemp = session()->get('shift_temp');
        }

        unset($arrCheckTemp[$input['shift_id']]);

        //Forget session tạm
        session()->forget('shift_temp');
        //Push session tạm mới
        session()->put('shift_temp', $arrCheckTemp);
    }

    /**
     * Cập nhật các giá trị của ca làm việc đã chọn
     *
     * @param $input
     * @return mixed|void
     */
    public function updateObjectShift($input)
    {
        $arrCheckTemp = [];

        //Lấy session tạm
        if (session()->get('shift_temp')) {
            $arrCheckTemp = session()->get('shift_temp');
        }

        $arrCheckTemp[$input['shift_id']]['is_ot'] = $input['is_ot'];
        $arrCheckTemp[$input['shift_id']]['branch_id'] = $input['branch_id'];
        $arrCheckTemp[$input['shift_id']]['overtime_type'] = $input['overtime_type'];
        $arrCheckTemp[$input['shift_id']]['timekeeping_coefficient'] = $input['timekeeping_coefficient'];

        //Forget session tạm
        session()->forget('shift_temp');
        //Push session tạm mới
        session()->put('shift_temp', $arrCheckTemp);
    }

    /**
     * Thêm ca làm việc cho nhân viên
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function addShift($input)
    {
        DB::beginTransaction();
        try {
            $arrCheckTemp = [];

            //Lấy session tạm
            if (session()->get('shift_temp')) {
                $arrCheckTemp = session()->get('shift_temp');
            }

            if (count($arrCheckTemp) == 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Hãy chọn ca làm việc')
                ]);
            }

            foreach ($arrCheckTemp as $v) {
                if ($v['timekeeping_coefficient'] == null || $v['timekeeping_coefficient'] > 999 || $v['timekeeping_coefficient'] < 1) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Hệ số công tối thiểu 1 và tối đa 999')
                    ]);
                }

//                if ($v['is_ot'] == 1 && $v['timekeeping_coefficient'] <= 1) {
//                    return response()->json([
//                        'error' => true,
//                        'message' => __('Hệ số công của ca tăng phải lớn hơn 1')
//                    ]);
//                }

                if ($v['is_ot'] == 0 && $v['timekeeping_coefficient'] > 1) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Hệ số công của ca thường phải bằng 1')
                    ]);
                }
            }

            //Validate các ca chọn có hợp lệ không
            $validateShift = $this->validateShift($arrCheckTemp);

            if ($validateShift['error'] == true) {
                return response()->json([
                    'error' => true,
                    'message' => __('Ca làm việc có thời gian trùng')
                ]);
            }

            $timeWorkingStaff = [];

            if (isset($validateShift['arrTimeShift']) && count($validateShift['arrTimeShift']) > 0) {
                foreach ($validateShift['arrTimeShift'] as $v) {
                    //Ngày bắt đầu làm việc
                    $workingDay = Carbon::parse($input['working_day'])->format('Y-m-d');
                    //Giờ bắt đầu làm việc
                    $workingTime = $v['start_work_time'];

                    if ($v['start_work_time'] > $v['end_work_time']) {
                        //Lấy số giờ cách nhau giữa giờ kết thúc và giờ bắt đầu (qua ngày)
                        $rangeMinutes = Carbon::parse($v['end_work_time'])->diffInMinutes(Carbon::parse($v['start_work_time']));
                    } else {
                        //Lấy số giờ cách nhau giữa giờ bắt đầu và giờ kết thúc (trong ngày)
                        $rangeMinutes = Carbon::parse($v['start_work_time'])->diffInMinutes(Carbon::parse($v['end_work_time']));
                    }

                    //Ngày kết thúc làm việc
                    $workingEndDay = Carbon::createFromFormat('Y-m-d H:i:s', $workingDay . ' ' . $workingTime)->addMinutes($rangeMinutes)->format('Y-m-d');
                    //Giờ kết thúc làm việc
                    $workingEndTime = $v['end_work_time'];

                    //                    //Tính range check in
                    //                    if ($v['start_timekeeping_on'] != null && $v['start_work_time'] < $v['start_timekeeping_on']) {
                    //                        //Thời gian làm việc nhỏ hơn thời gian cho phép check in
                    //                        $rangeStartCheckIn = Carbon::parse($v['start_timekeeping_on'])->diffInMinutes(Carbon::parse($v['start_work_time']));
                    //
                    //                    } else {
                    //                        //Thời gian làm việc lớn hơn thời gian cho phép check in
                    //                        $rangeStartCheckIn = Carbon::parse($v['start_work_time'])->diffInMinutes(Carbon::parse($v['start_timekeeping_on']));
                    //                    }
                    //
                    //                    //Ngày bắt đầu check in
                    //                    $startCheckInDay = Carbon::createFromFormat('Y-m-d H:i:s', $workingDay . ' ' . $workingTime)->subMinutes($rangeStartCheckIn)->format('Y-m-d');
                    //
                    //                    //Giờ bắt đầu check in
                    //                    $startCheckInTime = $v['start_timekeeping_on'];
                    //
                    //                    if ($v['start_timekeeping_on'] != null && $v['start_timekeeping_on'] > $v['end_timekeeping_on']) {
                    //                        //Lấy số giờ cách nhau giữa giờ kết thúc và giờ bắt đầu (qua ngày)
                    //                        $rangeCheckIn = Carbon::parse($v['end_timekeeping_on'])->diffInMinutes(Carbon::parse($v['start_timekeeping_on']));
                    //                    } else {
                    //                        //Lấy số giờ cách nhau giữa giờ bắt đầu và giờ kết thúc (trong ngày)
                    //                        $rangeCheckIn = Carbon::parse($v['start_timekeeping_on'])->diffInMinutes(Carbon::parse($v['end_timekeeping_on']));
                    //                    }
                    //
                    //                    //Ngày kết thúc check in
                    //                    $endCheckInDay = Carbon::createFromFormat('Y-m-d H:i:s', $startCheckInDay . ' ' . $startCheckInTime)->addMinutes($rangeCheckIn)->format('Y-m-d');
                    //                    //Giờ kết thúc check in
                    //                    $endCheckInTime = $v['end_timekeeping_on'];
                    //
                    //                    //Tính range check out
                    //                    if ($v['end_work_time'] < $v['start_timekeeping_out']) {
                    //                        //Thời gian kết thúc làm việc nhỏ hơn thời gian cho phép check out
                    //                        $rangeStartCheckOut = Carbon::parse($v['start_timekeeping_out'])->diffInMinutes(Carbon::parse($v['end_work_time']));
                    //
                    //                    } else {
                    //                        //Thời gian làm việc lớn hơn thời gian cho phép check in
                    //                        $rangeStartCheckOut = Carbon::parse($v['end_work_time'])->diffInMinutes(Carbon::parse($v['start_timekeeping_out']));
                    //                    }
                    //
                    //                    //Ngày bắt đầu check out
                    //                    $startCheckOutDay = Carbon::createFromFormat('Y-m-d H:i:s', $workingEndDay . ' ' . $workingEndTime)->subMinutes($rangeStartCheckOut)->format('Y-m-d');
                    //
                    //                    //Giờ bắt đầu check out
                    //                    $startCheckOutTime = $v['start_timekeeping_out'];
                    //
                    //                    if ($v['start_timekeeping_out'] > $v['end_timekeeping_out']) {
                    //                        //Lấy số giờ cách nhau giữa giờ kết thúc và giờ bắt đầu (qua ngày)
                    //                        $rangeCheckOut = Carbon::parse($v['end_timekeeping_out'])->diffInMinutes(Carbon::parse($v['start_timekeeping_out']));
                    //                    } else {
                    //                        //Lấy số giờ cách nhau giữa giờ bắt đầu và giờ kết thúc (trong ngày)
                    //                        $rangeCheckOut = Carbon::parse($v['start_timekeeping_out'])->diffInMinutes(Carbon::parse($v['end_timekeeping_out']));
                    //                    }
                    //
                    //                    //Ngày kết thúc check in
                    //                    $endCheckOutDay = Carbon::createFromFormat('Y-m-d H:i:s', $startCheckOutDay . ' ' . $startCheckOutTime)->addMinutes($rangeCheckOut)->format('Y-m-d');
                    //                    //Giờ kết thúc check in
                    //                    $endCheckOutTime = $v['end_timekeeping_out'];

                    $timeWorkingStaff[] = [
                        'staff_id' => $input['staff_id'],
                        'shift_id' => $v['shift_id'],
                        'branch_id' => $v['branch_id'],
                        'working_day' => $workingDay,
                        'working_time' => $workingTime,
                        'start_working_format_day' => Carbon::parse($input['working_day'])->format('d'),
                        'start_working_format_week' => Carbon::parse($input['working_day'])->isoWeek,
                        'start_working_format_month' => Carbon::parse($input['working_day'])->format('m'),
                        'start_working_format_year' => Carbon::parse($input['working_day'])->format('Y'),
                        'working_end_day' => $workingEndDay,
                        'working_end_time' => $workingEndTime,
                        'work_schedule_id' => 0,
                        'is_ot' => $v['is_ot'],
                        'time_work' => $v['time_work'],
                        'min_time_work' => $v['min_time_work'],
                        'overtime_type' => $v['is_ot'] == 1 ? $v['overtime_type'] : null,
                        'timekeeping_coefficient' => $v['timekeeping_coefficient']
                    ];
                }
            }

            $interfaceWorkSchedule = app()->get(WorkScheduleRepoInterface::class);

            //Validate các ca chọn với lịch làm việc khác
            $validateWorkScheduleDiff = $interfaceWorkSchedule->validateWorkScheduleDiff($timeWorkingStaff);

            if ($validateWorkScheduleDiff['error'] == true) {
                return response()->json([
                    'error' => true,
                    'message' => __('Thời gian làm việc đã trùng với lịch khác')
                ]);
            }

            $mTimeWorkingStaff = app()->get(TimeWorkingStaffTable::class);

            $arrTimeWorkingStaff = [];

            //Insert thời gian làm việc của nhân viên
            if (count($timeWorkingStaff) > 0) {
                foreach ($timeWorkingStaff as $v) {
                    $v['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                    $v['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');

                    $arrTimeWorkingStaff[] = $v;
                }
            }

            //Insert thời gian làm việc
            $mTimeWorkingStaff->insert($arrTimeWorkingStaff);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => __('Thêm ca thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => true,
                'message' => __('Thêm ca làm việc thất bại'),
                '_message' => $e->getMessage() . $e->getFile() . $e->getLine()
            ]);
        }
    }

    /**
     * Validate ca làm việc của chính nó
     *
     * @param $arrShift
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function validateShift($arrShift)
    {
        $mShift = app()->get(ShiftTable::class);

        $arrTimeShift = [];

        if (count($arrShift) > 0) {
            foreach ($arrShift as $v) {
                if ($v['timekeeping_coefficient'] < 1) {
                    return [
                        'error' => true
                    ];
                }

                //Lấy thông tin ca làm việc
                $infoShift = $mShift->getInfo($v['shift_id']);

                $arrTimeShift[] = [
                    'shift_id' => $infoShift['shift_id'],
                    'start_work_time' => $infoShift['start_work_time'],
                    'end_work_time' => $infoShift['end_work_time'],
                    'start_timekeeping_on' => $infoShift['start_timekeeping_on'],
                    'end_timekeeping_on' => $infoShift['end_timekeeping_on'],
                    'start_timekeeping_out' => $infoShift['start_timekeeping_out'],
                    'end_timekeeping_out' => $infoShift['end_timekeeping_out'],
                    'branch_id' => $v['branch_id'],
                    'is_ot' => $v['is_ot'],
                    'time_work' => $infoShift['time_work'],
                    'min_time_work' => $infoShift['min_time_work'],
                    'overtime_type' => $v['overtime_type'],
                    'timekeeping_coefficient' => $v['timekeeping_coefficient']
                ];
            }
        }

        if (count($arrTimeShift) > 0) {
            for ($i = 0; $i < count($arrTimeShift); $i++) {
                for ($u = 0; $u < count($arrTimeShift); $u++) {
                    if ($i != $u) {
                        $startTime = $arrTimeShift[$i]['start_work_time'];
                        $endTime = $arrTimeShift[$i]['end_work_time'];

                        $startTime1 = $arrTimeShift[$u]['start_work_time'];
                        $endTime1 = $arrTimeShift[$u]['end_work_time'];

                        if ($startTime >= $startTime1 && $startTime <= $endTime1) {
                            return [
                                'error' => true
                            ];
                        }

                        if ($endTime >= $startTime1 && $endTime <= $endTime1) {
                            return [
                                'error' => true
                            ];
                        }

                        if ($startTime1 >= $startTime && $startTime1 <= $endTime) {
                            return [
                                'error' => true
                            ];
                        }
                    }
                }
            }
        }

        return [
            'error' => false,
            'arrTimeShift' => $arrTimeShift
        ];
    }

    /**
     * Nghỉ việc có lương
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function paidLeave($input)
    {
        try {
            //Lấy thông tin ca làm việc vừa xoá
            $timeWorkingStaff = $this->timeWorkingStaff->getInfo($input['time_working_staff_id']);

            $data = [];

            if ($input['type'] == 'paid') {
                $data['is_deducted'] = 0;
            } else if ($input['type'] == 'unpaid') {
                $data['is_deducted'] = 1;
            }

            //Cập nhật ngày làm việc thành nghỉ có lương or không lương
            $this->timeWorkingStaff->edit($data, $input['time_working_staff_id']);

            return response()->json([
                'error' => false,
                'message' => __('Điều chỉnh thành công'),
                'info' => $timeWorkingStaff
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Điều chỉnh thất bại')
            ]);
        }
    }

    /**
     * Xoá ca làm việc của nhân viên
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function removeShift($input)
    {
        try {
            //Check ngày làm việc đã check in/ check out chưa
            //            $getApplyByTimeWorking = $this->timeWorkingStaff->getApplyByTimeWorking($input['time_working_staff_id']);
            //
            //            if ($getApplyByTimeWorking != null) {
            //                return response()->json([
            //                    'error' => true,
            //                    'message' => __('Ca làm việc đã được áp dụng')
            //                ]);
            //            }

            //Xoá ca làm việc của nhân viên
            $this->timeWorkingStaff->edit([
                'is_deleted' => 1,
                'updated_by' => Auth()->id()
            ], $input['time_working_staff_id']);

            //Lấy thông tin ca làm việc vừa xoá
            $timeWorkingStaff = $this->timeWorkingStaff->getInfo($input['time_working_staff_id']);

            return response()->json([
                'error' => false,
                'message' => __('Xoá thành công'),
                'info' => $timeWorkingStaff
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Xoá thất bại')
            ]);
        }
    }

    /**
     * Cập nhật ngày làm việc có đi làm
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function isWork($input)
    {
        try {
            //Cập nhật có đi làm
            $this->timeWorkingStaff->edit([
                'is_off' => self::NOT_OFF,
                'is_deducted' => 0,
            ], $input['time_working_staff_id']);

            //Lấy thông tin ca làm việc
            $timeWorkingStaff = $this->timeWorkingStaff->getInfo($input['time_working_staff_id']);

            return response()->json([
                'error' => false,
                'message' => __('Điều chỉnh thành công'),
                'info' => $timeWorkingStaff
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Điều chỉnh thất bại')
            ]);
        }
    }

    /**
     * Show popup ca làm việc của nhân viên
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function showPopupMyShift($input)
    {
        //Lấy ds ca làm việc của tôi
        $listTimeWorking = $this->timeWorkingStaff->getTimeWorkingByStaff($input['staff_id'], $input['working_day']);

        $mStaffHoliday = app()->get(StaffHolidayTable::class);

        $isHoliday = 0;

        //Kiểm tra ngày làm việc có phải ngày lễ không
        $checkHoliday = $mStaffHoliday->checkDayInHoliday($input['working_day']);

        if (count($checkHoliday) > 0) {
            $isHoliday = 1;
        }

        $html = \View::make('shift::time-working-staff.pop.pop-my-shift', [
            'list' => $listTimeWorking,
            'working_day' => $input['working_day'],
            'staff_id' => $input['staff_id'],
            'is_holiday' => $isHoliday
        ])->render();

        return [
            'html' => $html,
        ];
    }

    /**
     * Danh sách ca làm việc của nhân viên
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function listMyShift($input)
    {
        //Lấy ds ca làm việc của tôi
        $listTimeWorking = $this->timeWorkingStaff->getTimeWorkingByStaff($input['staff_id'], $input['working_day']);

        $mStaffHoliday = app()->get(StaffHolidayTable::class);

        $isHoliday = 0;

        //Kiểm tra ngày làm việc có phải ngày lễ không
        $checkHoliday = $mStaffHoliday->checkDayInHoliday($input['working_day']);

        if (count($checkHoliday) > 0) {
            $isHoliday = 1;
        }

        $html = \View::make('shift::time-working-staff.pop.list-my-shift', [
            'list' => $listTimeWorking,
            'working_day' => $input['working_day'],
            'staff_id' => $input['staff_id'],
            'is_holiday' => $isHoliday
        ])->render();

        return [
            'html' => $html,
        ];
    }

    /**
     * Xoá nhân viên theo ca
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function removeStaffByShift($input)
    {
        try {
            //Kiểm tra lịch làm việc của nv đã check in - check out chưa
            $getUsing = $this->timeWorkingStaff->getApplyByShift($input['staff_id'], $input['shift_id'], $input['start_time'], $input['end_time']);

            if (count($getUsing) > 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Lịch làm việc này đã được sử dụng, bạn không thể xoá nhân viên này')
                ]);
            }

            //Xoá lịch làm việc của nv theo ca
            $this->timeWorkingStaff->removeTimeWorkingByShift($input['staff_id'], $input['shift_id'], $input['start_time'], $input['end_time']);

            return response()->json([
                'error' => false,
                'message' => __('Xoá thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Xoá thất bại')
            ]);
        }
    }

    /**
     * Show popup chọn nhân viên
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function showPopupStaff($input)
    {
        //Forget session tạm
        session()->forget('staff_temp');

        $arrStaffHaveSchedule = [];

        //Lấy list nhân viên đã có ca làm việc trong thời gian này
        $getStaffByShift = $this->timeWorkingStaff->getListStaffWorking($input['start_time'], $input['end_time']);

        if (count($getStaffByShift) > 0) {
            foreach ($getStaffByShift as $v) {
                $arrStaffHaveSchedule[] = $v['staff_id'];
            }
        }

        //Lấy ds nhân viên
        $list = $this->listStaff([
            'staff_have_schedule' => $arrStaffHaveSchedule,
            'shift_id' => $input['shift_id']
        ]);

        $mShift = app()->get(ShiftTable::class);
        //Lấy thông tin ca làm việc
        $infoShift = $mShift->getInfo($input['shift_id']);

        $list['staff_id'] = $input['staff_id'];
        $list['shift_id'] = $input['shift_id'];
        $list['start_time'] = $input['start_time'];
        $list['end_time'] = $input['end_time'];
        $list['FILTER'] = $this->staffFilters();
        $list['staff_have_schedule'] = $arrStaffHaveSchedule;
        $list['infoShift'] = $infoShift;

        $html = \View::make('shift::time-working-staff.pop.pop-staff', $list)->render();

        return [
            'html' => $html,
        ];
    }

    /**
     * Filter popup chọn nhân viên
     *
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function staffFilters()
    {
        $mBranch = app()->get(BranchTable::class);
        $mDepartment = app()->get(DepartmentTable::class);
        $mStaff = app()->get(StaffTable::class);

        //Lấy option chi nhánh
        $getOptionBranch = $mBranch->getOption()->toArray();

        $groupBranch = (["" => __("Chọn chi nhánh")]) + array_combine(array_column($getOptionBranch, 'branch_id'), array_column($getOptionBranch, 'branch_name'));

        //Lấy option phòng ban
        $getOptionDepartment = $mDepartment->getOption()->toArray();

        $groupDepartment = (["" => __("Chọn phòng ban")]) + array_combine(array_column($getOptionDepartment, 'department_id'), array_column($getOptionDepartment, 'department_name'));

        //Lấy option nhân viên
        $getOptionStaff = $mStaff->getOption()->toArray();

        $groupStaff = (["" => __("Chọn nhân viên")]) + array_combine(array_column($getOptionStaff, 'staff_id'), array_column($getOptionStaff, 'full_name'));

        return [
            'staffs$branch_id' => [
                'data' => $groupBranch
            ],
            'staffs$department_id' => [
                'data' => $groupDepartment
            ],
            'staffs$staff_id' => [
                'data' => $groupStaff
            ],
        ];
    }

    /**
     * Danh sách nhân viên
     *
     * @param array $filter
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function listStaff($filter = [])
    {
        $mStaff = app()->get(StaffTable::class);
        $mMapShiftBranch = app()->get(MapShiftBranchTable::class);

        $arrCheckTemp = [];

        //Lấy session tạm
        if (session()->get('staff_temp')) {
            $arrCheckTemp = session()->get('staff_temp');
        }

        //Lấy ds ca làm việc
        $list = $mStaff->getList($filter);

        if (count($list->items()) > 0) {
            foreach ($list->items() as $v) {
                //Lấy chi nhánh làm việc của ca
                $v['branchShift'] = $mMapShiftBranch->getInfoByShift($filter['shift_id']);
            }
        }

        return [
            'list' => $list,
            'arrCheckTemp' => $arrCheckTemp
        ];
    }

    /**
     * Chọn nhân viên
     *
     * @param $input
     * @return mixed|void
     */
    public function chooseStaff($input)
    {
        $arrCheckTemp = [];

        //Lấy session tạm
        if (session()->get('staff_temp')) {
            $arrCheckTemp = session()->get('staff_temp');
        }

        //Push ca mới chọn vào
        $arrCheckTemp[$input['staff_id']] = [
            "staff_id" => $input['staff_id'],
            "is_ot" => $input['is_ot'],
            "branch_id" => $input['branch_id']
        ];

        //Forget session tạm
        session()->forget('staff_temp');
        //Push session tạm mới
        session()->put('staff_temp', $arrCheckTemp);
    }

    /**
     * Bỏ chọn nhân viên
     *
     * @param $input
     * @return mixed|void
     */
    public function unChooseStaff($input)
    {
        $arrCheckTemp = [];

        //Lấy session tạm
        if (session()->get('staff_temp')) {
            $arrCheckTemp = session()->get('staff_temp');
        }

        unset($arrCheckTemp[$input['staff_id']]);

        //Forget session tạm
        session()->forget('staff_temp');
        //Push session tạm mới
        session()->put('staff_temp', $arrCheckTemp);
    }

    /**
     * Cập nhật các giá trị của nhân viên làm việc đã chọn
     *
     * @param $input
     * @return mixed|void
     */
    public function updateObjectStaff($input)
    {
        $arrCheckTemp = [];

        //Lấy session tạm
        if (session()->get('staff_temp')) {
            $arrCheckTemp = session()->get('staff_temp');
        }

        $arrCheckTemp[$input['staff_id']]['is_ot'] = $input['is_ot'];
        $arrCheckTemp[$input['staff_id']]['branch_id'] = $input['branch_id'];

        //Forget session tạm
        session()->forget('staff_temp');
        //Push session tạm mới
        session()->put('staff_temp', $arrCheckTemp);
    }

    /**
     * Thêm nhân viên làm việc
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function addStaff($input)
    {
        DB::beginTransaction();
        try {
            $arrCheckTemp = [];

            //Lấy session tạm
            if (session()->get('staff_temp')) {
                $arrCheckTemp = session()->get('staff_temp');
            }

            if (count($arrCheckTemp) == 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Hãy chọn nhân viên làm việc')
                ]);
            }

            $mShift = app()->get(ShiftTable::class);
            $mStaffHoliday = app()->get(StaffHolidayTable::class);

            //Lấy thông tin ca làm việc
            $infoShift = $mShift->getInfo($input['shift_id']);

            $arrHoliday = [];
            //Lấy thông tin ngày lễ
            $getHoliday = $mStaffHoliday->getHoliday();

            if (count($getHoliday) > 0) {
                foreach ($getHoliday as $v) {
                    for ($i = 0; $i <= $v['staff_holiday_number']; $i++) {
                        $arrHoliday[] = Carbon::parse($v['staff_holiday_start_date'])->addDays($i)->format('Y-m-d');
                    }
                }
            }

            $timeWorkingStaff = [];

            //Ngày bắt đầu làm việc

            $startDay = Carbon::createFromFormat('Y-m-d', $input['start_time'])->format('Y-m-d');
            //Ngày kết thúc làm việc
            $endDay = Carbon::createFromFormat('Y-m-d', $input['end_time'])->format('Y-m-d');

            $tStart = Carbon::parse($startDay);
            $tEnd = Carbon::parse($endDay);

            //Lấy số ngày cách nhau
            $diffDate = $tEnd->diffInDays($tStart);

            if ($diffDate > 0) {
                for ($i = 0; $i <= $diffDate; $i++) {
                    foreach ($arrCheckTemp as $v) {
                        $getDayName = Carbon::parse($startDay)->addDays($i)->format('l');

                        $dateIsValid = 1;

                        switch ($getDayName) {
                            case  'Monday':
                                if ($infoShift['is_monday'] == 0) {
                                    $dateIsValid = 0;
                                }
                                break;
                            case  'Tuesday':
                                if ($infoShift['is_tuesday'] == 0) {
                                    $dateIsValid = 0;
                                }
                                break;
                            case  'Wednesday':
                                if ($infoShift['is_wednesday'] == 0) {
                                    $dateIsValid = 0;
                                }
                                break;
                            case  'Thursday':
                                if ($infoShift['is_thursday'] == 0) {
                                    $dateIsValid = 0;
                                }
                                break;
                            case  'Friday':
                                if ($infoShift['is_friday'] == 0) {
                                    $dateIsValid = 0;
                                }
                                break;
                            case  'Saturday':
                                if ($infoShift['is_saturday'] == 0) {
                                    $dateIsValid = 0;
                                }
                                break;
                            case  'Sunday':
                                if ($infoShift['is_sunday'] == 0) {
                                    $dateIsValid = 0;
                                }
                                break;
                        }

                        if ($dateIsValid == 0) {
                            continue;
                        }

                        //Ngày bắt đầu làm việc
                        $workingDay = Carbon::parse($startDay)->addDays($i)->format('Y-m-d');
                        //Giờ bắt đầu làm việc
                        $workingTime = $infoShift['start_work_time'];

                        //                        //Kiểm tra ngày làm việc có là quá khứ chưa
                        //                        if (Carbon::parse($workingDay . ' ' . $workingTime)->format('Y-m-d H:i') < Carbon::now()->format('Y-m-d H:i')) {
                        //                            continue;
                        //                        }

                        //Kiểm tra ngày làm việc có trong ngày lễ ko
                        if (in_array($workingDay, array_unique($arrHoliday))) {
                            continue;
                        }

                        if ($infoShift['start_work_time'] > $infoShift['end_work_time']) {
                            //Lấy số giờ cách nhau giữa giờ kết thúc và giờ bắt đầu (qua ngày)
                            $rangeMinutes = Carbon::parse($infoShift['end_work_time'])->diffInMinutes(Carbon::parse($infoShift['start_work_time']));
                        } else {
                            //Lấy số giờ cách nhau giữa giờ bắt đầu và giờ kết thúc (trong ngày)
                            $rangeMinutes = Carbon::parse($infoShift['start_work_time'])->diffInMinutes(Carbon::parse($infoShift['end_work_time']));
                        }

                        //Ngày kết thúc làm việc
                        $workingEndDay = Carbon::createFromFormat('Y-m-d H:i:s', $workingDay . ' ' . $workingTime)->addMinutes($rangeMinutes)->format('Y-m-d');
                        //Giờ kết thúc làm việc
                        $workingEndTime = $infoShift['end_work_time'];

                        $timeWorkingStaff[] = [
                            'staff_id' => $v['staff_id'],
                            'shift_id' => $input['shift_id'],
                            'branch_id' => $v['branch_id'],
                            'working_day' => $workingDay,
                            'working_time' => $workingTime,
                            'start_working_format_day' => Carbon::parse($startDay)->addDays($i)->format('d'),
                            'start_working_format_week' => Carbon::parse($startDay)->addDays($i)->isoWeek,
                            'start_working_format_month' => Carbon::parse($startDay)->addDays($i)->format('m'),
                            'start_working_format_year' => Carbon::parse($startDay)->addDays($i)->format('Y'),
                            'working_end_day' => $workingEndDay,
                            'working_end_time' => $workingEndTime,
                            'work_schedule_id' => 0,
                            'is_ot' => $v['is_ot'],
                            'min_time_work' => $infoShift['min_time_work'],
                            'time_work' => $infoShift['time_work']
                        ];
                    }
                }
            }

            if (count($timeWorkingStaff) == 0) {
                return response()->json([
                    'error' => true,
                    //                    'message' => __('Bạn vui lòng kiểm tra lại ngày làm việc của nhân viên đã chọn đã ở quá khứ hoặc trùng với ngày lễ')
                    'message' => __('Bạn vui lòng kiểm tra lại ngày làm việc của nhân viên đã chọn trùng với ngày lễ')
                ]);
            }

            $interfaceWorkSchedule = app()->get(WorkScheduleRepoInterface::class);

            //Validate các ca chọn với lịch làm việc khác
            $validateWorkScheduleDiff = $interfaceWorkSchedule->validateWorkScheduleDiff($timeWorkingStaff);

            if ($validateWorkScheduleDiff['error'] == true) {
                return response()->json([
                    'error' => true,
                    'message' => __('Thời gian làm việc đã trùng với lịch khác')
                ]);
            }

            $mTimeWorkingStaff = app()->get(TimeWorkingStaffTable::class);

            $arrTimeWorkingStaff = [];

            //Insert thời gian làm việc của nhân viên
            if (count($timeWorkingStaff) > 0) {
                foreach ($timeWorkingStaff as $v) {
                    $v['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                    $v['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');

                    $arrTimeWorkingStaff[] = $v;
                }
            }

            //Insert thời gian làm việc
            $mTimeWorkingStaff->insert($arrTimeWorkingStaff);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => __('Thêm nhân viên thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => true,
                'message' => __('Thêm nhân viên thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    /**
     * Show popup chi tiết lịch làm việc
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function showTimeWorkingDetail($input)
    {
        //Lấy thông tin lịch làm việc
        $info = $this->timeWorkingStaff->getInfo($input['time_working_staff_id']);

        $isToWork = 0;

        $workingDate = Carbon::parse($info['working_day'] . ' ' . $info['working_time'])->format('Y-m-d H:i');
        $dateNow = Carbon::now()->format('Y-m-d H:i');

        //Kiểm tra ca làm việc đã tới giờ làm chưa
        if ($workingDate < $dateNow) {
            //Đã qua giờ làm
            $isToWork = 1;
        }

        $mTimeWorkingChangeLog = app()->get(TimeWorkingStaffChangeLogTable::class);
        $mCheckInChangeLog = app()->get(CheckInChangeLogTable::class);
        $mCheckOutChangeLog = app()->get(CheckOutChangeLogTable::class);

        $mManagerWorkTable = app()->get(ManagerWorkTable::class);

        $filter = [
            'created_at' => Carbon::parse($info['working_day'])->format('d/m/Y'),
            'staff_id' => $info['staff_id']
        ];

        $lstWork = $mManagerWorkTable->getListWork($filter);
        //Lấy log change time working
        $timeWorkingChangeLog = $mTimeWorkingChangeLog->getChangeLog($info['time_working_staff_id']);
        //Lấy log change check in
        $checkInChangeLog = $mCheckInChangeLog->getLogChange($info['check_in_log_id']);
        //Lấy log change check out
        $checkOutChangeLog = $mCheckOutChangeLog->getLogChange($info['check_out_log_id']);

        $html = \View::make('shift::time-working-staff.pop.pop-detail', [
            'item' => $info,
            'is_to_work' => $isToWork,
            'time_working_change_log' => $timeWorkingChangeLog,
            'check_in_change_log' => $checkInChangeLog,
            'check_out_change_log' => $checkOutChangeLog,
            'lst_work' => $lstWork,
            'view' => $input['view']
        ])->render();

        return [
            'html' => $html,
        ];
    }

    /**
     * Chỉnh sửa thời gian làm việc
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function updateTimeWorking($input)
    {
        try {
            $mTimeWorkingStaff = app()->get(TimeWorkingStaffTable::class);

            $workingDay = Carbon::createFromFormat('d/m/Y H:i', $input['time_start'])->format('Y-m-d');
            $workingTime = Carbon::createFromFormat('d/m/Y H:i', $input['time_start'])->format('H:i');

            $workingEndDay = Carbon::createFromFormat('d/m/Y H:i', $input['time_end'])->format('Y-m-d');
            $workingEndTime = Carbon::createFromFormat('d/m/Y H:i', $input['time_end'])->format('H:i');

            //Check start time > end time
            if (Carbon::createFromFormat('d/m/Y H:i', $input['time_start'])->format('Y-m-d H:i')
                >= Carbon::createFromFormat('d/m/Y H:i', $input['time_end'])->format('Y-m-d H:i')) {
                return response()->json([
                    'error' => true,
                    'message' => __('Ngày bắt đầu phải lớn hơn ngày kết thúc'),
                ]);
            }

            //Validate hệ số công
            if ($input['timekeeping_coefficient'] < 1) {
                return response()->json([
                    'error' => true,
                    'message' => __('Hệ số công tối thiểu 1'),
                ]);
            }

            //Validate ngày giờ bắt đầu - kết thúc phải nhỏ hơn 24h
            $start = Carbon::createFromFormat('d/m/Y H:i', $input['time_start']);
            $end = Carbon::createFromFormat('d/m/Y H:i', $input['time_end']);

            $timeWork = floatval($end->diffInMinutes($start) / 60);

            if ($timeWork > 24) {
                return response()->json([
                    'error' => true,
                    'message' => __('Thời gian làm việc đã vượt quá 24 giờ'),
                ]);
            }

            //Lấy thông tin thời gian làm việc
            $info = $this->timeWorkingStaff->getInfo($input['time_working_staff_id']);

//            if ($info['is_ot'] == 1 && $input['timekeeping_coefficient'] <= 1) {
//                return response()->json([
//                    'error' => true,
//                    'message' => __('Hệ số công của ca tăng phải lớn hơn 1')
//                ]);
//            }

            //Kiểm tra thời gian làm việc của nv
            $checkTime = $mTimeWorkingStaff->checkTimeWorkingStaff(
                0,
                $info['staff_id'],
                $workingDay . ' ' . $workingTime,
                $workingEndDay . ' ' . $workingEndTime,
                $input['time_working_staff_id']
            );

            if ($checkTime != null) {
                return response()->json([
                    'error' => true,
                    'message' => __('Thời gian làm việc đã trùng với lịch khác')
                ]);
            }

            //Cập nhật thời gian làm việc
            $this->timeWorkingStaff->edit([
                'branch_id' => $input['branch_id'],
                'min_time_work' => $input['min_time_work'],
                'timekeeping_coefficient' => $input['timekeeping_coefficient'],
                'working_day' => $workingDay,
                'working_time' => $workingTime,
                'start_working_format_day' => Carbon::parse($workingDay)->format('d'),
                'start_working_format_week' => Carbon::parse($workingDay)->isoWeek,
                'start_working_format_month' => Carbon::parse($workingDay)->format('m'),
                'start_working_format_year' => Carbon::parse($workingDay)->format('Y'),
                'working_end_day' => $workingEndDay,
                'working_end_time' => $workingEndTime,
                'time_work' => $timeWork
            ], $input['time_working_staff_id']);

            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    /**
     * Show popup chấm công hộ
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function showPopTimeAttendance($input)
    {
        $mCheckInLog = app()->get(ShiftCheckInLogTable::class);
        $mCheckOutLog = app()->get(ShiftCheckOutLogTable::class);

        //Lấy thông tin ca làm việc
        $info = $this->timeWorkingStaff->getInfo($input['time_working_staff_id']);

        //Lấy log check in
        $logCheckIn = $mCheckInLog->getInfoLog($input['time_working_staff_id']);
        //Lấy log check out
        $logCheckOut = $mCheckOutLog->getInfoLog($input['time_working_staff_id']);

        $html = \View::make('shift::time-working-staff.pop.pop-time-attendance', [
            'item' => $info,
            'log_check_in' => $logCheckIn,
            'log_check_out' => $logCheckOut,
            'view' => $input['view']
        ])->render();

        return [
            'html' => $html,
        ];
    }

    /**
     * Lưu chấm công hộ
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function submitTimeAttendance($input)
    {
        DB::beginTransaction();
        try {
            $mCheckInLog = app()->get(ShiftCheckInLogTable::class);
            $mCheckOutLog = app()->get(ShiftCheckOutLogTable::class);

            $checkinTime = Carbon::parse($input['check_in_time']);
            $checkoutTime = Carbon::parse($input['check_out_time']);
            if ($input['check_in_day'] == $input['check_out_day']) {
                if (isset($input['check_out_time'])) {
                    if ($checkinTime > $checkoutTime) {
                        return response()->json([
                            'error' => true,
                            'message' => __('Giờ ra ca phải lớn hơn giờ vào ca'),
                        ]);
                    }
                }
            }

            //Lấy thông tin ca làm việc
            $info = $this->timeWorkingStaff->getInfo($input['time_working_staff_id']);

            //Xử lý luồng check in
            if ($input['lock_check_in'] == 0 && $input['check_in_time'] != null) {
                //Validate thời gian check in
                //                $validCheckIn = $this->_validateCheckInTime($info, $input['check_in_time']);
                //
                //                if ($validCheckIn['error'] == true) {
                //                    return response()->json([
                //                        'error' => true,
                //                        'message' => $validCheckIn['message'],
                //                    ]);
                //                }

                //Kiểm tra đã check in chưa
                $logCheckIn = $mCheckInLog->getInfoLog($input['time_working_staff_id']);

                if ($logCheckIn == null) {
                    //Insert
                    $mCheckInLog->add([
                        'time_working_staff_id' => $input['time_working_staff_id'],
                        'staff_id' => $info['staff_id'],
                        'branch_id' => $info['branch_id'],
                        'shift_id' => $info['shift_id'],
                        'check_in_day' => Carbon::createFromFormat('d/m/Y', $input['check_in_day'])->format('Y-m-d'),
                        'check_in_time' => Carbon::createFromFormat('H:i', $input['check_in_time'])->format('H:i'),
                        'status' => 'ok',
                        'created_type' => 'admin',
                        'created_by' => Auth()->id()
                    ]);
                } else {
                    $mCheckInChangeLog = app()->get(CheckInChangeLogTable::class);
                    //Lưu log thay đổi của check in
                    $mCheckInChangeLog->add([
                        "check_in_log_id" => $logCheckIn['check_in_log_id'],
                        "time_working_staff_id" => $info['time_working_staff_id'],
                        "staff_id" => $info['staff_id'],
                        "branch_id" => $info['branch_id'],
                        "shift_id" => $info['shift_id'],
                        "check_in_day_old" => $logCheckIn['check_in_day'],
                        "check_in_time_old" => $logCheckIn['check_in_time'],
                        "created_type_old" => $logCheckIn['created_type'],
                        "created_by_old" => $logCheckIn['created_by'],
                        "check_in_day_new" => Carbon::createFromFormat('d/m/Y', $input['check_in_day'])->format('Y-m-d'),
                        "check_in_time_new" => Carbon::createFromFormat('H:i', $input['check_in_time'])->format('H:i'),
                        "created_type_new" => 'admin',
                        "created_by_new" => Auth()->id()
                    ]);

                    //Update check in
                    $mCheckInLog->edit([
                        'check_in_day' => Carbon::createFromFormat('d/m/Y', $input['check_in_day'])->format('Y-m-d'),
                        'check_in_time' => Carbon::createFromFormat('H:i', $input['check_in_time'])->format('H:i'),
                        'created_type' => 'admin',
                        'created_by' => Auth()->id()
                    ], $logCheckIn['check_in_log_id']);
                }

                $startTime = Carbon::parse($input['check_in_time']);
                $endTime = Carbon::parse($info['working_time'])->addMinutes(session()->get('late_check_in'));
                $totalDuration = $startTime->diffInMinutes($endTime);
                $number_late_time = 0;

                if ($startTime > $endTime) {
                    $number_late_time = $totalDuration;
                }
                //Cập nhật trạng thái is_check_in (nếu vô trễ thì tính số phút vô trễ)
                $this->timeWorkingStaff->edit([
                    'is_check_in' => 1,
                    'number_late_time' => $number_late_time,
                    'check_in_by' => Auth()->id(),
                ], $info['time_working_staff_id']);
            }

            //Xử lý luồng check out
            if ($input['lock_check_out'] == 0 && $input['check_out_time'] != null) {
                //Validate thời gian check out
                //                $validCheckOut = $this->_validateCheckOutTime($info, $input['check_out_time']);
                //
                //                if ($validCheckOut['error'] == true) {
                //                    return response()->json([
                //                        'error' => true,
                //                        'message' => $validCheckOut['message'],
                //                    ]);
                //                }

                //Kiểm tra đã check out chưa
                $logCheckOut = $mCheckOutLog->getInfoLog($input['time_working_staff_id']);

                if ($logCheckOut == null) {
                    //Insert
                    $mCheckOutLog->add([
                        'time_working_staff_id' => $input['time_working_staff_id'],
                        'staff_id' => $info['staff_id'],
                        'branch_id' => $info['branch_id'],
                        'shift_id' => $info['shift_id'],
                        'check_out_day' => Carbon::createFromFormat('d/m/Y', $input['check_out_day'])->format('Y-m-d'),
                        'check_out_time' => Carbon::createFromFormat('H:i', $input['check_out_time'])->format('H:i'),
                        'created_type' => 'admin',
                        'status' => 'ok',
                        'created_by' => Auth()->id()
                    ]);
                } else {
                    $mCheckOutChangeLog = app()->get(CheckOutChangeLogTable::class);
                    //Lưu log thay đổi của check in
                    $mCheckOutChangeLog->add([
                        "check_out_log_id" => $logCheckOut['check_out_log_id'],
                        "time_working_staff_id" => $info['time_working_staff_id'],
                        "staff_id" => $info['staff_id'],
                        "branch_id" => $info['branch_id'],
                        "shift_id" => $info['shift_id'],
                        "check_out_day_old" => $logCheckOut['check_out_day'],
                        "check_out_time_old" => $logCheckOut['check_out_time'],
                        "created_type_old" => $logCheckOut['created_type'],
                        "created_by_old" => $logCheckOut['created_by'],
                        "check_out_day_new" => Carbon::createFromFormat('d/m/Y', $input['check_out_day'])->format('Y-m-d'),
                        "check_out_time_new" => Carbon::createFromFormat('H:i', $input['check_out_time'])->format('H:i'),
                        "created_type_new" => 'admin',
                        "created_by_new" => Auth()->id()
                    ]);

                    //Update check in
                    $mCheckOutLog->edit([
                        'check_out_day' => Carbon::createFromFormat('d/m/Y', $input['check_out_day'])->format('Y-m-d'),
                        'check_out_time' => Carbon::createFromFormat('H:i', $input['check_out_time'])->format('H:i'),
                        'created_type' => 'admin',
                        'created_by' => Auth()->id()
                    ], $logCheckOut['check_out_log_id']);
                }

                $startTime = Carbon::parse($input['check_out_time']);
                $endTime = Carbon::parse($info['working_end_time'])->subMinutes(session()->get('back_soon_check_out'));
                $totalDuration = $startTime->diffInMinutes($endTime);

                //Tính giờ thực tế làm việc
                $dateTimeCheckIn = Carbon::createFromFormat('d/m/Y H:i', $input['check_in_day'] . ' ' . $input['check_in_time']);
                $dateTimeCheckOut = Carbon::createFromFormat('d/m/Y H:i', $input['check_out_day'] . ' ' . $input['check_out_time']);

                $actuleTimeWork = $dateTimeCheckOut->diffInMinutes($dateTimeCheckIn) / 60;

                if ($actuleTimeWork > $info['time_work']) {
                    $actuleTimeWork = $info['time_work'];
                }

                $number_time_back_soon = 0;

                if ($startTime < $endTime) {
                    $number_time_back_soon = $totalDuration;
                }

                //Cập nhật trạng thái is_check_out (nếu về sớm thì tính só phút về sớm)
                $this->timeWorkingStaff->edit([
                    'is_check_out' => 1,
                    'number_time_back_soon' => $number_time_back_soon,
                    "actual_time_work" => $actuleTimeWork,
                    'check_out_by' => Auth()->id()
                ], $info['time_working_staff_id']);
            }

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => __('Chấm công hộ thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => true,
                'message' => __('Chấm công hộ thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    /**
     * Validate thời gian check in
     *
     * @param $infoShift
     * @param $checkInTime
     * @return array
     */
    protected function _validateCheckInTime($infoShift, $checkInTime)
    {
        //Lấy thời gian check in giùm
        $carbonCheckInTime = Carbon::createFromFormat('d/m/Y H:i', $checkInTime);
        //Lấy thời gian check in của ca
        $carbonCheckInShift = Carbon::parse($infoShift['working_day'] . ' ' . $infoShift['working_time']);
        //Lấy thời gian check out của ca
        $carbonCheckOutShift = Carbon::parse($infoShift['working_end_day'] . ' ' . $infoShift['working_end_time']);

        if ($carbonCheckInShift->format('Y-m-d H:i') >= $carbonCheckInTime->format('Y-m-d H:i')) {
            //Check in đúng giờ or sớm (check sớm quá 1h thì ko cho check in)
            $hourSoon = 3;

            $diffHour = $carbonCheckInShift->diffInHours($carbonCheckInTime);

            if ($diffHour >= $hourSoon) {
                return [
                    'error' => true,
                    'message' => __('Thời gian vào ca quá sớm, vui lòng kiểm tra lại')
                ];
            }
        } else {
            //Check in trễ giờ (check trễ quá thời gian làm thì ko cho check in)
            if ($carbonCheckInTime->format('Y-m-d H:i') > $carbonCheckOutShift->format('Y-m-d H:i')) {
                return [
                    'error' => true,
                    'message' => __('Thời gian vào ca lớn hơn thời gian tan ca, vui lòng kiểm tra lại')
                ];
            }
        }

        return [
            'error' => false
        ];
    }

    /**
     * Validate thời gian check out
     *
     * @param $infoShift
     * @param $checkOutTime
     * @return array
     */
    protected function _validateCheckOutTime($infoShift, $checkOutTime)
    {
        //Lấy thời gian check out giùm
        $carbonCheckOutTime = Carbon::createFromFormat('d/m/Y H:i', $checkOutTime);
        //Lấy thời gian check in của ca
        $carbonCheckInShift = Carbon::parse($infoShift['working_day'] . ' ' . $infoShift['working_time']);
        //Lấy thời gian check out của ca
        $carbonCheckOutShift = Carbon::parse($infoShift['working_end_day'] . ' ' . $infoShift['working_end_time']);

        if ($carbonCheckOutShift->format('Y-m-d H:i') >= $carbonCheckOutTime->format('Y-m-d H:i')) {
            //Check out đúng giờ or sớm (check sớm quá thời gian bắt đầu làm việc thi ko cho check out)
            if ($carbonCheckOutTime->format('Y-m-d H:i') <= $carbonCheckInShift->format('Y-m-d H:i')) {
                return [
                    'error' => true,
                    'message' => __('Thời gian ra ca nhỏ hơn thời gian tan ca, vui lòng kiểm tra lại')
                ];
            }
        } else {
            //Check out trễ giờ (check trễ quá 1h thì ko cho check out)
            $hourLate = 3;

            $diffHour = $carbonCheckOutShift->diffInHours($carbonCheckOutTime);

            if ($diffHour >= $hourLate) {
                return [
                    'error' => true,
                    'message' => __('Thời gian ra ca quá trễ, vui lòng kiểm tra lại')
                ];
            }
        }

        return [
            'error' => false
        ];
    }

    /**
     * Lấy data view chỉnh sửa
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataViewEdit($input)
    {
        $mMapShiftBranch = app()->get(MapShiftBranchTable::class);

        //Lấy thông tin lịch làm việc
        $info = $this->timeWorkingStaff->getInfo($input['time_working_staff_id']);

        $info['day_name'] = __(Carbon::parse($info['working_day'])->format('l'));

        //Lấy chi nhánh làm việc của ca
        $branchShift = $mMapShiftBranch->getInfoByShift($info['shift_id']);

        return [
            'item' => $info,
            'branchShift' => $branchShift,
            'view' => $input['view']
        ];
    }

    /**
     * Thêm ca làm thêm giờ
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function storeOvertime($input)
    {
        DB::beginTransaction();
        try {
            $workingDay = Carbon::createFromFormat('d/m/Y H:i', $input['time_start'])->format('Y-m-d');
            $workingTime = Carbon::createFromFormat('d/m/Y H:i', $input['time_start'])->format('H:i');

            $workingEndDay = Carbon::createFromFormat('d/m/Y H:i', $input['time_end'])->format('Y-m-d');
            $workingEndTime = Carbon::createFromFormat('d/m/Y H:i', $input['time_end'])->format('H:i');

            //Check start time > end time
            if (Carbon::createFromFormat('d/m/Y H:i', $input['time_start'])->format('Y-m-d H:i')
                >= Carbon::createFromFormat('d/m/Y H:i', $input['time_end'])->format('Y-m-d H:i')) {
                return response()->json([
                    'error' => true,
                    'message' => __('Ngày bắt đầu phải lớn hơn ngày kết thúc'),
                ]);
            }

            //Lấy thông tin thời gian làm việc
            $info = $this->timeWorkingStaff->getInfo($input['time_working_staff_id']);

            //Check thời gian bắt đầu của ca làm thêm nhỏ hơn thời gian kết thúc của ca trước thì báo lỗi
            $fullEndDateOld = Carbon::parse($info['working_end_day'] . ' ' . $info['working_end_time'])->format('Y-m-d H:i');
            $fullStartTimeNew = Carbon::createFromFormat('d/m/Y H:i', $input['time_start'])->format('Y-m-d H:i');


            if ($fullEndDateOld > $fullStartTimeNew) {
                return response()->json([
                    'error' => true,
                    'message' => __('Ngày bắt đầu ca làm thêm phải nhở hơn ngày kết thúc của ca cũ'),
                ]);
            }

            //Kiểm tra thời gian làm việc của nv
            $checkTime = $this->timeWorkingStaff->checkTimeWorkingStaff(
                0,
                $info['staff_id'],
                $workingDay . ' ' . $workingTime,
                $workingEndDay . ' ' . $workingEndTime,
                ""
            );

            if ($checkTime != null) {
                return response()->json([
                    'error' => true,
                    'message' => __('Thời gian làm việc đã trùng với lịch khác')
                ]);
            }

            //Tính giờ làm việc của ca làm thêm
            $startTime = Carbon::createFromFormat('d/m/Y H:i', $input['time_start']);
            $endTime = Carbon::createFromFormat('d/m/Y H:i', $input['time_end']);

            $data = [
                'staff_id' => $info['staff_id'],
                'shift_id' => $info['shift_id'],
                'branch_id' => $input['branch_id'],
                'working_day' => $workingDay,
                'working_time' => $workingTime,
                'start_working_format_day' => Carbon::parse($workingDay)->format('d'),
                'start_working_format_week' => Carbon::parse($workingDay)->isoWeek,
                'start_working_format_month' => Carbon::parse($workingDay)->format('m'),
                'start_working_format_year' => Carbon::parse($workingDay)->format('Y'),
                'working_end_day' => $workingEndDay,
                'working_end_time' => $workingEndTime,
                'work_schedule_id' => 0,
                'is_ot' => 1,
                'timekeeping_coefficient' => $input['timekeeping_coefficient'],
                'time_work' => round($endTime->diffInMinutes($startTime) / 60, 2),
                'min_time_work' => round($endTime->diffInMinutes($startTime) / 60, 2),
                'overtime_type' => "H",
                "actual_time_work" => round($endTime->diffInMinutes($startTime) / 60, 2)
            ];

            if ($input['is_not_check_in'] == 1) {
                $data['is_check_in'] = 1;
                $data['is_check_out'] = 1;
            }

            //Thêm thời gian làm việc
            $timeWorkingId = $this->timeWorkingStaff->add($data);

            if ($input['is_not_check_in'] == 1) {
                $mCheckInLog = app()->get(ShiftCheckInLogTable::class);
                $mCheckOutLog = app()->get(ShiftCheckOutLogTable::class);

                //Thêm lịch sử vào ca
                $mCheckInLog->add([
                    "time_working_staff_id" => $timeWorkingId,
                    "staff_id" => $info['staff_id'],
                    "branch_id" => $input['branch_id'],
                    "shift_id" => $info['shift_id'],
                    "check_in_day" => $workingDay,
                    "check_in_time" => $workingTime,
                    "status" => "ok",
                    "created_type" => "admin",
                    "created_by" => Auth()->id()
                ]);
                //Thêm lịch sử ra ca
                $mCheckOutLog->add([
                    "time_working_staff_id" => $timeWorkingId,
                    "staff_id" => $info['staff_id'],
                    "branch_id" => $input['branch_id'],
                    "shift_id" => $info['shift_id'],
                    "check_out_day" => $workingEndDay,
                    "check_out_time" => $workingEndTime,
                    "status" => "ok",
                    "created_type" => "admin",
                    "created_by" => Auth()->id()
                ]);
            }

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => __('Thêm thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => true,
                'message' => __('Thêm thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    /**
     * Lấy ds thưởng - phạt của ngày làm việc
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getListRecompense($input)
    {
        $mTimeWorkingRecompense = app()->get(TimeWorkingStaffRecompenseTable::class);

        //Lấy ds thưởng-phạt của ngày làm việc
        $list = $mTimeWorkingRecompense->getList($input);

        return [
            'list' => $list
        ];
    }

    /**
     * Lấy data view thêm thưởng - phạt
     *
     * @param $input
     * @return mixed|void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataCreateRecompense($input)
    {
        $mRecompense = app()->get(RecompenseTable::class);

        //Lấy option loại thưởng - phạt
        $getRecompense = $mRecompense->getRecompense($input['type']);

        return [
            'optionRecompense' => $getRecompense,
            'type' => $input['type'],
            'time_working_staff_id' => $input['time_working_staff_id']
        ];
    }

    /**
     * Thêm thưởng - phạt
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function submitCreateRecompense($input)
    {
        try {
            $mTimeWorkingRecompense = app()->get(TimeWorkingStaffRecompenseTable::class);

            //Thêm thưởng - phạt
            $mTimeWorkingRecompense->add([
                'time_working_staff_id' => $input['time_working_staff_id'],
                'recompense_id' => $input['recompense_id'],
                'money' => $input['money'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            return [
                'error' => false,
                'message' => __('Thêm thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm thất bại')
            ];
        }
    }

    /**
     * Xoá thưởng - phạt
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function removeRecompense($input)
    {
        try {
            $mTimeWorkingRecompense = app()->get(TimeWorkingStaffRecompenseTable::class);

            //Xoá thưởng phạt
            $mTimeWorkingRecompense->removeRecompense($input['time_working_staff_recompense_id']);

            return [
                'error' => false,
                'message' => __('Xoá thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xoá thất bại')
            ];
        }
    }

    /**
     * Lấy cấu hình chung của ca làm việc
     *
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getConfigGeneral()
    {
        $mConfigGeneral = app()->get(ConfigGeneralTable::class);

        //Lấy cấu hình chung của ca
        return $mConfigGeneral->getConfig();
    }
}