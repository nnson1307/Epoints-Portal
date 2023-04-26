<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/04/2022
 * Time: 09:41
 */

namespace Modules\Shift\Repositories\WorkSchedule;


use Illuminate\Support\Facades\DB;
use Modules\Shift\Models\BranchTable;
use Modules\Shift\Models\DepartmentTable;
use Modules\Shift\Models\MapShiftBranchTable;
use Modules\Shift\Models\MapWorkScheduleShiftTable;
use Modules\Shift\Models\MapWorkScheduleStaffTable;
use Modules\Shift\Models\ShiftTable;
use Modules\Shift\Models\StaffTable;
use Modules\Shift\Models\TimeWorkingStaffTable;
use Modules\Shift\Models\WorkScheduleTable;
use Carbon\Carbon;

class WorkScheduleRepo implements WorkScheduleRepoInterface
{
    protected $workSchedule;

    public function __construct(
        WorkScheduleTable $workSchedule
    )
    {
        $this->workSchedule = $workSchedule;
    }

    /**
     * Danh sách lịch làm việc
     *
     * @param array $filter
     * @return array|mixed
     */
    public function list($filter = [])
    {
        //Lấy ds thời gian làm việc của nhân viên
        $data = $this->workSchedule->getList($filter);

        return [
            'list' => $data
        ];
    }

    /**
     * Lấy data view phân ca
     *
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataViewCreate()
    {
        $mBranch = app()->get(BranchTable::class);
        $mShift = app()->get(ShiftTable::class);

        session()->forget('staff_temp');
        session()->forget('staff_choose');

        //Lấy option chi nhánh
        $getOptionBranch = $mBranch->getOption();
        //Lấy option ca làm việc
        $getOptionShift = $mShift->getOption();

        return [
            'optionBranch' => $getOptionBranch,
            'optionShift' => $getOptionShift,
            'FILTER' => $this->staffFilters()
        ];
    }

    /**
     * Show popup chọn nhân viên
     *
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function showPopupStaff()
    {
        $mStaff = app()->get(StaffTable::class);

        session()->forget('staff_temp');

        $arrCheckReal = [];

        //Lấy session chính
        if (session()->get('staff_choose')) {
            $arrCheckReal = session()->get('staff_choose');
        }

        //Lấy ds nhân viên
        $list = $mStaff->getList();

        $html = \View::make('shift::work-schedule.pop.pop-staff', [
            'list' => $list,
            'FILTER' => $this->staffFilters(),
            'arrChooseStaff' => $arrCheckReal
        ])->render();

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
     * Filter, phân trang ds nhân viên (pop)
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function listStaffPop($input)
    {
        $mStaff = app()->get(StaffTable::class);

        $arrCheckReal = [];

        //Lấy session chính
        if (session()->get('staff_choose')) {
            $arrCheckReal = session()->get('staff_choose');
        }

        $arrCheckTemp = [];

        //Lấy session tạm
        if (session()->get('staff_temp')) {
            $arrCheckTemp = session()->get('staff_temp');
        }

        //Merge 2 array lại
        $arrCheckMerge = array_merge($arrCheckReal, $arrCheckTemp);

        //Lấy ds nhân viên
        $list = $mStaff->getList($input);

        return [
            'list' => $list,
            'arrChooseStaff' => array_unique($arrCheckMerge),
            'page' => $input['page']
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
        $arrCheckOld = [];

        //Lấy session tạm
        if (session()->get('staff_temp')) {
            $arrCheckOld = session()->get('staff_temp');
        }

        $arrCheckNew = [];

        if (isset($input['arrCheck']) && count($input['arrCheck']) > 0) {
            foreach ($input['arrCheck'] as $v) {
                $arrCheckNew [] = $v['staff_id'];
            }
        }

        //Merge session tạm cũ và session tạm mới
        $arrCheckTempNew = array_merge($arrCheckOld, $arrCheckNew);
        //Xoá session tạm
        session()->forget('staff_temp');
        //Lưu session tạm mới
        session()->put('staff_temp', array_unique($arrCheckTempNew));
    }

    /**
     * Bỏ chọn nhân viên
     *
     * @param $input
     * @return mixed|void
     */
    public function unChooseStaff($input)
    {
        $arrCheckOld = [];

        //Lấy session tạm
        if (session()->get('staff_temp')) {
            $arrCheckOld = session()->get('staff_temp');
        }

        $arrUnCheck = [];

        if (isset($input['arrUnCheck']) && count($input['arrUnCheck']) > 0) {
            foreach ($input['arrUnCheck'] as $v) {
                $arrUnCheck [] = $v['staff_id'];

            }
        }

        //Merge session tạm cũ và session tạm mới
        $arrCheckTempNew = array_diff($arrCheckOld, $arrUnCheck);
        //Xoá session tạm
        session()->forget('staff_temp');
        //Lưu session tạm mới
        session()->put('staff_temp', array_unique($arrCheckTempNew));
    }

    /**
     * Submit chọn nhân viên
     *
     * @return mixed|void
     */
    public function submitChooseStaff()
    {
        $arrCheckTemp = [];

        //Lấy session tạm
        if (session()->get('staff_temp')) {
            $arrCheckTemp = session()->get('staff_temp');
        }

        $arrCheckReal = [];

        //Lấy session chính
        if (session()->get('staff_choose')) {
            $arrCheckReal = session()->get('staff_choose');
        }

        //Merge session tạm cũ và session tạm mới
        $arrCheckNew = array_merge($arrCheckTemp, $arrCheckReal);
        //Xoá session tạm
        session()->forget('staff_temp');
        //Xoá session chính
        session()->forget('staff_choose');
        //Lưu session chính mới
        session()->put('staff_choose', array_unique($arrCheckNew));
    }

    /**
     * Filter, phân trang ds nhân viên
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function listStaff($input)
    {
        $mStaff = app()->get(StaffTable::class);

        $arrCheckReal = [];

        //Lấy session chính
        if (session()->get('staff_choose')) {
            $arrCheckReal = session()->get('staff_choose');
        }

        $input['list_staff'] = $arrCheckReal;

        //Lấy ds nhân viên
        $list = $mStaff->getList($input);

        return [
            'list' => $list,
            'page' => $input['page']
        ];
    }

    /**
     * Xoá nhân viên ra khỏi table
     *
     * @param $input
     * @return mixed|void
     */
    public function removeStaff($input)
    {
        $arrCheckOld = [];

        //Lấy session chính
        if (session()->get('staff_choose')) {
            $arrCheckOld = session()->get('staff_choose');
        }

        //Merge session chính cũ và id chọn xoá
        $arrCheckNew = array_diff($arrCheckOld, [$input['staff_id']]);

        //Xoá session chính
        session()->forget('staff_choose');
        //Lưu session chính mới
        session()->put('staff_choose', array_unique($arrCheckNew));
    }

    /**
     * Thêm lịch làm việc
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $staffChoose = [];

            if (session()->get('staff_choose')) {
                $staffChoose = session()->get('staff_choose');
            }

            //Kiểm tra có chọn nhân viên chưa
            if (count($staffChoose) <= 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Hãy chọn nhân viên')
                ]);
            }

            //Validate ngày bắt đầu với ngày kết thúc
            if (Carbon::createFromFormat('d/m/Y', $input['start_day_shift'])->format('Y-m-d')
                >= Carbon::createFromFormat('d/m/Y', $input['end_day_shift'])->format('Y-m-d')) {
                return response()->json([
                    'error' => true,
                    'message' => __('Ngày bắt đầu phải nhỏ hơn ngày kết thúc'),
                ]);
            }

            //Validate ca đã chọn có bị trùng nhau không
            $validateShift = $this->validateShift($input['listShift']);

            if ($validateShift['error'] == true) {
                return response()->json([
                    'error' => true,
                    'message' => __('Ca làm việc không hợp lệ')
                ]);
            }

            $timeWorkingStaff = [];

            //Ngày bắt đầu làm việc
            $startDay = Carbon::createFromFormat('d/m/Y', $input['start_day_shift'])->format('Y-m-d');
            //Ngày kết thúc làm việc
            $endDay = Carbon::createFromFormat('d/m/Y', $input['end_day_shift'])->format('Y-m-d');

            $tStart = Carbon::parse($startDay);
            $tEnd = Carbon::parse($endDay);

            //Lấy số ngày cách nhau
            $diffDate = $tEnd->diffInDays($tStart);

            if ($diffDate > 0) {
                for ($i = 0; $i <= $diffDate; $i++) {
                    foreach ($validateShift['arrTimeShift'] as $v) {
                        //Ngày bắt đầu làm việc
                        $workingDay = Carbon::parse($startDay)->addDays($i)->format('Y-m-d');
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

                        if (Carbon::parse($startDay)->addDays($i)->format('l') == 'Monday' && $v['is_monday'] == 0) {
                            continue;
                        }

                        if (Carbon::parse($startDay)->addDays($i)->format('l') == 'Tuesday' && $v['is_tuesday'] == 0) {
                            continue;
                        }

                        if (Carbon::parse($startDay)->addDays($i)->format('l') == 'Wednesday' && $v['is_wednesday'] == 0) {
                            continue;
                        }

                        if (Carbon::parse($startDay)->addDays($i)->format('l') == 'Thursday' && $v['is_thursday'] == 0) {
                            continue;
                        }

                        if (Carbon::parse($startDay)->addDays($i)->format('l') == 'Friday' && $v['is_friday'] == 0) {
                            continue;
                        }

                        if (Carbon::parse($startDay)->addDays($i)->format('l') == 'Saturday' && $v['is_saturday'] == 0) {
                            continue;
                        }

                        if (Carbon::parse($startDay)->addDays($i)->format('l') == 'Sunday' && $v['is_sunday'] == 0) {
                            continue;
                        }

                        foreach ($staffChoose as $v1) {
                            $timeWorkingStaff [] = [
                                'staff_id' => $v1,
                                'shift_id' => $v['shift_id'],
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
                                'is_ot' => $v['is_ot']
                            ];
                        }
                    }

                }
            }

            //Validate nhân viên - ngày làm việc so với những lịch khác có bị trùng giờ không
            $validateWorkScheduleDiff = $this->validateWorkScheduleDiff($timeWorkingStaff);

            if ($validateWorkScheduleDiff['error'] == true) {
                return response()->json([
                    'error' => true,
                    'message' => __('Thời gian làm việc đã trùng với lịch khác')
                ]);
            }

            //Lưu thông tin lịch làm việc
            $workScheduleId = $this->workSchedule->add([
                'work_schedule_name' => $input['work_schedule_name'],
                'start_day_shift' => $startDay,
                'end_day_shift' => $endDay,
                'repeat' => $input['repeat'],
                'note' => $input['note'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            $mMapScheduleStaff = app()->get(MapWorkScheduleStaffTable::class);
            $mMapScheduleShift = app()->get(MapWorkScheduleShiftTable::class);
            $mTimeWorkingStaff = app()->get(TimeWorkingStaffTable::class);

            $arrInsertShift = [];

            if (count($input['listShift']) > 0) {
                foreach ($input['listShift'] as $v) {
                    $arrInsertShift [] = [
                        "work_schedule_id" => $workScheduleId,
                        "branch_id" => $v['branch_id'],
                        "shift_id" => $v['shift_id'],
                        "is_monday" => $v['is_monday'],
                        "is_tuesday" => $v['is_tuesday'],
                        "is_wednesday" => $v['is_wednesday'],
                        "is_thursday" => $v['is_thursday'],
                        "is_friday" => $v['is_friday'],
                        "is_saturday" => $v['is_saturday'],
                        "is_sunday" => $v['is_sunday'],
                        "is_ot" => $v['is_ot'],
                        "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                        "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert bảng map lịch - ca
            $mMapScheduleShift->insert($arrInsertShift);

            $arrInsertStaff = [];

            if (count($staffChoose) > 0) {
                foreach ($staffChoose as $v) {
                    $arrInsertStaff [] = [
                        "work_schedule_id" => $workScheduleId,
                        "staff_id" => $v,
                        "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                        "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert bảng map lịch - nhân viên
            $mMapScheduleStaff->insert($arrInsertStaff);

            $arrTimeWorkingStaff = [];

            //Insert thời gian làm việc của nhân viên
            if (count($timeWorkingStaff) > 0) {
                foreach ($timeWorkingStaff as $v) {
                    $v['work_schedule_id'] = $workScheduleId;
                    $v['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                    $v['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');

                    $arrTimeWorkingStaff [] = $v;
                }
            }

            //Insert thời gian làm việc
            $mTimeWorkingStaff->insert($arrTimeWorkingStaff);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => __('Phân ca thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Phân ca thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Validate ca làm việc của chính nó
     *
     * @param array $listShift
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function validateShift($listShift = [])
    {
        $mShift = app()->get(ShiftTable::class);

        $arrTimeShift = [];

        if (count($listShift) > 0) {
            foreach ($listShift as $v) {
                //Lấy thông tin ca làm việc
                $infoShift = $mShift->getInfo($v['shift_id']);

                $arrTimeShift [] = [
                    'shift_id' => $infoShift['shift_id'],
                    'start_work_time' => $infoShift['start_work_time'],
                    'end_work_time' => $infoShift['end_work_time'],
                    'start_timekeeping_on' => $infoShift['start_timekeeping_on'],
                    'end_timekeeping_on' => $infoShift['end_timekeeping_on'],
                    'start_timekeeping_out' => $infoShift['start_timekeeping_out'],
                    'end_timekeeping_out' => $infoShift['end_timekeeping_out'],
                    'branch_id' => $v['branch_id'],
                    'is_monday' => $v['is_monday'],
                    'is_tuesday' => $v['is_tuesday'],
                    'is_wednesday' => $v['is_wednesday'],
                    'is_thursday' => $v['is_thursday'],
                    'is_friday' => $v['is_friday'],
                    'is_saturday' => $v['is_saturday'],
                    'is_sunday' => $v['is_sunday'],
                    'is_ot' => $v['is_ot']
                ];
            }

            if (count($arrTimeShift) > 0) {
                for ($i = 0; $i < count($arrTimeShift); $i++) {
                    for ($u = 0; $u < count($arrTimeShift); $u++) {
                        if ($i != $u) {
                            $startTime = $arrTimeShift[$i]['start_work_time'];
                            $endTime = $arrTimeShift[$i]['end_work_time'];

                            $startTime1 = $arrTimeShift[$u]['start_work_time'];
                            $endTime1 = $arrTimeShift[$u]['end_work_time'];

                            $check = true;

                            if ($startTime >= $startTime1 && $startTime <= $endTime1) {
                                $check = false;
                            }

                            if ($endTime >= $startTime1 && $endTime <= $endTime1) {
                                $check = false;
                            }

                            if ($startTime1 >= $startTime && $startTime1 <= $endTime) {
                                $check = false;
                            }

                            if ($arrTimeShift[$i]['is_monday'] == 1 && $arrTimeShift[$u]['is_monday'] == 1 && $check == false) {
                                return [
                                    'error' => true
                                ];
                            }

                            if ($arrTimeShift[$i]['is_tuesday'] == 1 && $arrTimeShift[$u]['is_tuesday'] == 1 && $check == false) {
                                return [
                                    'error' => true
                                ];
                            }

                            if ($arrTimeShift[$i]['is_wednesday'] == 1 && $arrTimeShift[$u]['is_wednesday'] == 1 && $check == false) {
                                return [
                                    'error' => true
                                ];
                            }

                            if ($arrTimeShift[$i]['is_thursday'] == 1 && $arrTimeShift[$u]['is_thursday'] == 1 && $check == false) {
                                return [
                                    'error' => true
                                ];
                            }

                            if ($arrTimeShift[$i]['is_friday'] == 1 && $arrTimeShift[$u]['is_friday'] == 1 && $check == false) {
                                return [
                                    'error' => true
                                ];
                            }

                            if ($arrTimeShift[$i]['is_saturday'] == 1 && $arrTimeShift[$u]['is_saturday'] == 1 && $check == false) {
                                return [
                                    'error' => true
                                ];
                            }

                            if ($arrTimeShift[$i]['is_sunday'] == 1 && $arrTimeShift[$u]['is_sunday'] == 1 && $check == false) {
                                return [
                                    'error' => true
                                ];
                            }
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
     * Validate thời gian làm việc so với các lịch làm việc khác
     *
     * @param array $timeWorkingStaff
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function validateWorkScheduleDiff($timeWorkingStaff = [])
    {
        if (count($timeWorkingStaff) > 0) {
            $mTimeWorkingStaff = app()->get(TimeWorkingStaffTable::class);

            foreach ($timeWorkingStaff as $k => $v) {
                //Kiểm tra thời gian làm việc của nv
                $checkTime = $mTimeWorkingStaff->checkTimeWorkingStaff(
                    $v['work_schedule_id'],
                    $v['staff_id'],
                    $v['working_day'] . ' ' . $v['working_time'],
                    $v['working_end_day'] . ' ' . $v['working_end_time']
                );

                if ($checkTime != null) {
                    return [
                        'error' => true
                    ];
                }
            }
        }

        return [
            'error' => false,
            'timeWorkingStaff' => $timeWorkingStaff
        ];
    }

    /**
     * Lấy dữ liệu view chỉnh sửa lịch làm việc
     *
     * @param $workScheduleId
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataViewEdit($workScheduleId)
    {
        $mBranch = app()->get(BranchTable::class);
        $mShift = app()->get(ShiftTable::class);
        $mMapScheduleStaff = app()->get(MapWorkScheduleStaffTable::class);
        $mMapScheduleShift = app()->get(MapWorkScheduleShiftTable::class);
        $mMapShiftBranch = app()->get(MapShiftBranchTable::class);

        session()->forget('staff_temp');
        session()->forget('staff_choose');

        //Lấy option chi nhánh
        $getOptionBranch = $mBranch->getOption();
        //Lấy option ca làm việc
        $getOptionShift = $mShift->getOption();

        //Lấy thông tin lịch làm việc
        $info = $this->workSchedule->getInfo($workScheduleId);
        //Lấy data map - ca làm việc
        $getMapStaff = $mMapScheduleStaff->getWorkScheduleStaff($workScheduleId);
        //Lấy data map - nhân viên
        $getMapShift = $mMapScheduleShift->getWorkScheduleShift($workScheduleId);

        $arrStaffChoose = [];

        if (count($getMapStaff) > 0) {
            foreach ($getMapStaff as $v) {
                $arrStaffChoose [] = $v['staff_id'];
            }
        }

        if (count($getMapShift) > 0) {
            foreach ($getMapShift as $v) {
                $v['branch'] = $mMapShiftBranch->getInfoByShift($v['shift_id']);
            }
        }

        //Push session nhân viên đã chọn
        session()->put('staff_choose', array_unique($arrStaffChoose));

        return [
            'optionBranch' => $getOptionBranch,
            'optionShift' => $getOptionShift,
            'FILTER' => $this->staffFilters(),
            'info' => $info,
            'mapStaff' => $getMapStaff,
            'mapShift' => $getMapShift
        ];
    }

    /**
     * Chỉnh sửa lịch làm việc
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $staffChoose = [];

            if (session()->get('staff_choose')) {
                $staffChoose = session()->get('staff_choose');
            }

            //Kiểm tra có chọn nhân viên chưa
            if (count($staffChoose) <= 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Hãy chọn nhân viên')
                ]);
            }

            //Validate ngày bắt đầu với ngày kết thúc
            if (Carbon::createFromFormat('d/m/Y', $input['start_day_shift'])->format('Y-m-d')
                >= Carbon::createFromFormat('d/m/Y', $input['end_day_shift'])->format('Y-m-d')) {
                return response()->json([
                    'error' => true,
                    'message' => __('Ngày bắt đầu phải nhỏ hơn ngày kết thúc'),
                ]);
            }

            //Validate ca đã chọn có bị trùng nhau không
            $validateShift = $this->validateShift($input['listShift']);

            if ($validateShift['error'] == true) {
                return response()->json([
                    'error' => true,
                    'message' => __('Ca làm việc không hợp lệ')
                ]);
            }

            $timeWorkingStaff = [];

            //Ngày bắt đầu làm việc
            $startDay = Carbon::createFromFormat('d/m/Y', $input['start_day_shift'])->format('Y-m-d');
            //Ngày kết thúc làm việc
            $endDay = Carbon::createFromFormat('d/m/Y', $input['end_day_shift'])->format('Y-m-d');

            $tStart = Carbon::parse($startDay);
            $tEnd = Carbon::parse($endDay);

            //Lấy số ngày cách nhau
            $diffDate = $tEnd->diffInDays($tStart);

            //Range time hôp lệ
            $rangeTimeInValid = [];

            if ($diffDate > 0) {
                for ($i = 0; $i <= $diffDate; $i++) {
                    foreach ($validateShift['arrTimeShift'] as $v) {
                        //Ngày bắt đầu làm việc
                        $workingDay = Carbon::parse($startDay)->addDays($i)->format('Y-m-d');
                        //Giờ bắt đầu làm việc
                        $workingTime = $v['start_work_time'];

                        //Nếu ngày bắt đầu < thời gian hiện tại thì khoá những dữ liệu cũ lại
                        if ($workingDay <= Carbon::now()->format('Y-m-d')) {
                            continue;
                        }

                        $rangeTimeInValid [] = $workingDay;


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

                        if (Carbon::parse($startDay)->addDays($i)->format('l') == 'Monday' && $v['is_monday'] == 0) {
                            continue;
                        }

                        if (Carbon::parse($startDay)->addDays($i)->format('l') == 'Tuesday' && $v['is_tuesday'] == 0) {
                            continue;
                        }

                        if (Carbon::parse($startDay)->addDays($i)->format('l') == 'Wednesday' && $v['is_wednesday'] == 0) {
                            continue;
                        }

                        if (Carbon::parse($startDay)->addDays($i)->format('l') == 'Thursday' && $v['is_thursday'] == 0) {
                            continue;
                        }

                        if (Carbon::parse($startDay)->addDays($i)->format('l') == 'Friday' && $v['is_friday'] == 0) {
                            continue;
                        }

                        if (Carbon::parse($startDay)->addDays($i)->format('l') == 'Saturday' && $v['is_saturday'] == 0) {
                            continue;
                        }

                        if (Carbon::parse($startDay)->addDays($i)->format('l') == 'Sunday' && $v['is_sunday'] == 0) {
                            continue;
                        }

                        foreach ($staffChoose as $v1) {
                            $timeWorkingStaff [] = [
                                'staff_id' => $v1,
                                'shift_id' => $v['shift_id'],
                                'branch_id' => $v['branch_id'],
                                'working_day' => $workingDay,
                                'working_time' => $workingTime,
                                'start_working_format_day' => Carbon::parse($startDay)->addDays($i)->format('d'),
                                'start_working_format_week' => Carbon::parse($startDay)->addDays($i)->isoWeek,
                                'start_working_format_month' => Carbon::parse($startDay)->addDays($i)->format('m'),
                                'start_working_format_year' => Carbon::parse($startDay)->addDays($i)->format('Y'),
                                'working_end_day' => $workingEndDay,
                                'working_end_time' => $workingEndTime,
                                'work_schedule_id' => $input['work_schedule_id'],
                                'is_ot' => $v['is_ot']
                            ];
                        }
                    }
                }
            }

            $rangeTimeInValid = array_values(array_unique($rangeTimeInValid));

            //Validate nhân viên - ngày làm việc so với những lịch khác có bị trùng giờ không
            $validateWorkScheduleDiff = $this->validateWorkScheduleDiff($timeWorkingStaff);

            if ($validateWorkScheduleDiff['error'] == true) {
                return response()->json([
                    'error' => true,
                    'message' => __('Thời gian làm việc đã trùng với lịch khác')
                ]);
            }

            //Lưu thông tin lịch làm việc
            $this->workSchedule->edit([
                'work_schedule_name' => $input['work_schedule_name'],
                'start_day_shift' => $startDay,
                'end_day_shift' => $endDay,
                'repeat' => $input['repeat'],
                'note' => $input['note'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ], $input['work_schedule_id']);

            $mMapScheduleStaff = app()->get(MapWorkScheduleStaffTable::class);
            $mMapScheduleShift = app()->get(MapWorkScheduleShiftTable::class);
            $mTimeWorkingStaff = app()->get(TimeWorkingStaffTable::class);

            $arrInsertShift = [];

            //Xoá dữ liệu những ca cũ
            $mMapScheduleShift->removeBySchedule($input['work_schedule_id']);


            if (count($input['listShift']) > 0) {
                foreach ($input['listShift'] as $v) {
                    $arrInsertShift [] = [
                        "work_schedule_id" => $input['work_schedule_id'],
                        "branch_id" => $v['branch_id'],
                        "shift_id" => $v['shift_id'],
                        "is_monday" => $v['is_monday'],
                        "is_tuesday" => $v['is_tuesday'],
                        "is_wednesday" => $v['is_wednesday'],
                        "is_thursday" => $v['is_thursday'],
                        "is_friday" => $v['is_friday'],
                        "is_saturday" => $v['is_saturday'],
                        "is_sunday" => $v['is_sunday'],
                        "is_ot" => $v['is_ot'],
                        "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                        "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert bảng map lịch - ca
            $mMapScheduleShift->insert($arrInsertShift);

            //Xoá dữ liệu nhân viên cũ
            $mMapScheduleStaff->removeBySchedule($input['work_schedule_id']);

            $arrInsertStaff = [];

            if (count($staffChoose) > 0) {
                foreach ($staffChoose as $v) {
                    $arrInsertStaff [] = [
                        "work_schedule_id" => $input['work_schedule_id'],
                        "staff_id" => $v,
                        "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                        "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert bảng map lịch - nhân viên
            $mMapScheduleStaff->insert($arrInsertStaff);

            if (count($rangeTimeInValid) > 0) {
                //Xoá dữ liệu thời gian làm việc theo khoảng thời gian hợp lệ để insert lịch mới
                $mTimeWorkingStaff->removeTimeWorkingByScheduleTime($input['work_schedule_id'], $rangeTimeInValid[0], $rangeTimeInValid[count($rangeTimeInValid) - 1]);
            }

            $arrTimeWorkingStaff = [];

            //Insert thời gian làm việc của nhân viên
            if (count($timeWorkingStaff) > 0) {
                foreach ($timeWorkingStaff as $v) {
                    $v['work_schedule_id'] = $input['work_schedule_id'];
                    $v['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                    $v['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');

                    $arrTimeWorkingStaff [] = $v;
                }
            }

            //Insert thời gian làm việc
            $mTimeWorkingStaff->insert($arrTimeWorkingStaff);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Xoá lịch làm việc
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function destroy($input)
    {
        DB::beginTransaction();
        try {
            $mTimeWorkingStaff = app()->get(TimeWorkingStaffTable::class);

            //Kiểm tra lịch làm việc đã được áp dụng chưa (đã check in - check out)
            $getWorkingUsing = $mTimeWorkingStaff->getUsingBySchedule($input['work_schedule_id']);

            if (count($getWorkingUsing) > 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Lịch làm việc này đã được sử dụng, bạn không thể xoá')
                ]);
            }

            //Cập nhật trạng thái xoá
            $this->workSchedule->edit([
                'is_deleted' => 1
            ], $input['work_schedule_id']);

            //Xoá hết tất cả lịch làm việc của nv được tạo từ lịch làm việc
            $mTimeWorkingStaff->removeTimeWorkingBySchedule($input['work_schedule_id']);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => __('Xóa thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => true,
                'message' => __('Xóa thất bại')
            ]);
        }
    }

    /**
     * Chọn ca
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function chooseShift($input)
    {
        $mShift = app()->get(ShiftTable::class);
        $mMapShiftBranch = app()->get(MapShiftBranchTable::class);

        //Lấy thông tin ca
        $info = $mShift->getInfo($input['shift_id']);
        //Lấy chi nhánh làm việc của ca
        $branchMap = $mMapShiftBranch->getInfoByShift($input['shift_id']);

        return [
            'info' => $info,
            'branchMap' => $branchMap
        ];
    }
}