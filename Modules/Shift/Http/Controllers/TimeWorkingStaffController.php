<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 10:46
 */

namespace Modules\Shift\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;

use Modules\Shift\Http\Requests\TimeWorkingStaff\CreateOvertimeRequest;
use Modules\Shift\Http\Requests\TimeWorkingStaff\UpdateTimeWorkingRequest;
use Modules\Shift\Repositories\TimeWorkingStaff\TimeWorkingStaffRepoInterface;

class TimeWorkingStaffController extends Controller
{
    protected $timeWorkingStaff;

    public function __construct(
        TimeWorkingStaffRepoInterface $timeWorkingStaff
    ) {
        $this->timeWorkingStaff = $timeWorkingStaff;
    }

    /**
     * View ds lịch làm việc theo tuần - tháng
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //Lấy cầu hình chung của ca làm việc
        $listConfig = $this->timeWorkingStaff->getConfigGeneral();
        //Lưu session cấu hình chung
        $this->_setSessionConfigGeneral($listConfig);

        $now = Carbon::now();

       
        //Lấy ds lịch làm việc của NV
        // $data = $this->timeWorkingStaff->getList([
        //     'date_type' => 'by_week',
        //     'date_object' => $now->isoWeek,
        //     'years' => Carbon::now()->format('y')
        // ]);

        return view('shift::time-working-staff.week.index', [
            'LIST' => null,
            'FILTER' => $this->filters(),
            'listDay' => null,
            'number_day' => null,
            'week_in_year' => $now->weeksInYear,
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
                if ($v['is_actived'] == 0) {
                    continue;
                }

                $unit = 1;

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
        $data = $this->timeWorkingStaff->getDataFilter();

        //Ca làm việc
        $groupShift = (['' => __('Chọn ca làm')]) + $data['optionShift'];
        //Chi nhánh
        $groupBranch = (['' => __('Chọn chi nhánh nhân viên')]) + $data['optionBranch'];
        //Phòng ban
        $groupDepartment = (['' => __('Chọn phòng ban')]) + $data['optionDepartment'];
        //Nhân viên
        $groupStaff = (['' => __('Chọn nhân viên')]) + $data['optionStaff'];

        return [
            'shift_id' => [
                'data' => $groupShift
            ],
            'branch_id' => [
                'data' => $groupBranch
            ],
            'department_id' => [
                'data' => $groupDepartment
            ],
            'staff_id' => [
                'data' => $groupStaff
            ],
        ];
    }

    /**
     * Ajax load filter, phân trang CTKM
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {

        $filter = $request->only([
            'page',
            'display',
            'date_type',
            'date_object',
            'shift_id',
            'branch_id',
            'department_id',
            'staff_id',
            'years'
        ]);
        //        $filter['staff_id'] = 143;
        //        $filter['date_object'] = 19;
        $data = $this->timeWorkingStaff->getList($filter);
        $view = 'shift::time-working-staff.week.list';

        if ($data['number_day'] > 6) {
            $view = 'shift::time-working-staff.week.list-month';
        }

        return view($view, [
            'LIST' => $data['list'],
            'page' => $filter['page'],
            'workingDayLoad' => $request->working_day,
            'listDay' => $data['listDay']
        ]);
    }

    /**
     * Ds lịch làm việc theo ca
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexShift()
    {
        //Lấy cầu hình chung của ca làm việc
        $listConfig = $this->timeWorkingStaff->getConfigGeneral();
        //Lưu session cấu hình chung
        $this->_setSessionConfigGeneral($listConfig);

        $now = Carbon::now();
        //Lấy ds lịch làm việc của NV
        $data = $this->timeWorkingStaff->getListShift([
            'date_type' => 'by_week',
            'date_object' => $now->isoWeek,
            'years' => Carbon::now()->format('y')
        ]);

        return view('shift::time-working-staff.shift.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filterShift(),
            'listDay' => $data['listDay'],
            'number_day' => $data['number_day'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'week_in_year' => $data['week_in_year'],
            'years' => Carbon::now()->format('y')
        ]);
    }

    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filterShift()
    {
        //Lấy data filter
        $data = $this->timeWorkingStaff->getDataFilter();

        //Ca làm việc
        $groupShift = (['' => __('Chọn ca làm')]) + $data['optionShift'];
        //Chi nhánh
        $groupBranch = (['' => __('Chọn chi nhánh làm việc')]) + $data['optionBranch'];
        //Phòng ban
        $groupDepartment = (['' => __('Chọn phòng ban')]) + $data['optionDepartment'];
        //Nhân viên
        $groupStaff = (['' => __('Chọn nhân viên')]) + $data['optionStaff'];

        return [
            'shift_id' => [
                'data' => $groupShift
            ],
            'branch_id' => [
                'data' => $groupBranch
            ],
            'department_id' => [
                'data' => $groupDepartment
            ],
            'staff_id' => [
                'data' => $groupStaff
            ],
        ];
    }

    /**
     * Ajax filter, phân trang lịch làm việc theo ca
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listShiftIndexAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'date_type',
            'date_object',
            'shift_id',
            'sf_shifts$shift__type',
            'branch_id',
            'department_id',
            'staff_id',
            'years',
        ]);

        $data = $this->timeWorkingStaff->getListShift($filter);

        $view = 'shift::time-working-staff.shift.list';

        if ($data['number_day'] > 6) {
            $view = 'shift::time-working-staff.shift.list-month';
        }

        return view($view, [
            'LIST' => $data['list'],
            'page' => $filter['page'],
            'workingDayLoad' => $request->working_day,
            'listDay' => $data['listDay'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time']
        ]);
    }

    /**
     * Show pop chọn ca làm việc
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupShiftAction(Request $request)
    {
        $data = $this->timeWorkingStaff->showPopupShift($request->all());

        return response()->json($data);
    }

    /**
     * Ajax filter, phân trang ca làm việc
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listShiftAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
            'day_name',
            'focus_shift_id',
            'staff_salary_type_code'
        ]);

        //Danh sách nhân viên
        $list = $this->timeWorkingStaff->listShift($filter);

        return view('shift::time-working-staff.pop.list-shift', $list);
    }

    /**
     * Chọn ca làm việc
     *
     * @param Request $request
     * @return mixed
     */
    public function chooseShiftAction(Request $request)
    {
        return $this->timeWorkingStaff->chooseShift($request->all());
    }

    /**
     * Bỏ chọn ca làm việc
     *
     * @param Request $request
     * @return mixed
     */
    public function unChooseShiftAction(Request $request)
    {
        return $this->timeWorkingStaff->unChooseShift($request->all());
    }

    /**
     * Cập nhật các giá trị của ca làm việc đã chọn
     *
     * @param Request $request
     * @return mixed
     */
    public function updateObjectShiftAction(Request $request)
    {
        return $this->timeWorkingStaff->updateObjectShift($request->all());
    }

    /**
     * Thêm ca làm việc cho nhân viên
     *
     * @param Request $request
     * @return mixed
     */
    public function addShiftAction(Request $request)
    {
        return $this->timeWorkingStaff->addShift($request->all());
    }

    /**
     * Nghỉ việc có lương
     *
     * @param Request $request
     * @return mixed
     */
    public function paidOrUnPaidLeaveAction(Request $request)
    {
        return $this->timeWorkingStaff->paidLeave($request->all());
    }

    /**
     * Xoá ca làm việc của nhân viên
     *
     * @param Request $request
     * @return mixed
     */
    public function removeShiftAction(Request $request)
    {
        return $this->timeWorkingStaff->removeShift($request->all());
    }

    /**
     * Cập nhật ngày làm việc có đi làm
     *
     * @param Request $request
     * @return mixed
     */
    public function isWorkAction(Request $request)
    {
        return $this->timeWorkingStaff->isWork($request->all());
    }

    /**
     * Show popup ca làm việc của nhân viên
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupMyShiftAction(Request $request)
    {
        $data = $this->timeWorkingStaff->showPopupMyShift($request->all());

        return response()->json($data);
    }

    /**
     * Danh sách ca làm việc của nhân viên
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listMyShiftAction(Request $request)
    {
        $data = $this->timeWorkingStaff->listMyShift($request->all());

        return response()->json($data);
    }

    /**
     * Xoá nhân viên theo ca
     *
     * @param Request $request
     * @return mixed
     */
    public function removeStaffByShiftAction(Request $request)
    {
        return $this->timeWorkingStaff->removeStaffByShift($request->all());
    }

    /**
     * Show pop chọn nhân viên làm việc
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupStaffAction(Request $request)
    {
        $data = $this->timeWorkingStaff->showPopupStaff($request->all());

        return response()->json($data);
    }

    /**
     * Ajax filter, phân trang nhân viên làm việc
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listStaffAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'staffs$branch_id',
            'staffs$department_id',
            'staffs$staff_id',
            'staff_have_schedule',
            'shift_id'
        ]);

        //Danh sách nhân viên
        $list = $this->timeWorkingStaff->listStaff($filter);

        return view('shift::time-working-staff.pop.list-staff', $list);
    }

    /**
     * Chọn nhân viên
     *
     * @param Request $request
     * @return mixed
     */
    public function chooseStaffAction(Request $request)
    {
        return $this->timeWorkingStaff->chooseStaff($request->all());
    }

    /**
     * Bỏ chọn nhân viên
     *
     * @param Request $request
     * @return mixed
     */
    public function unChooseStaffAction(Request $request)
    {
        return $this->timeWorkingStaff->unChooseStaff($request->all());
    }

    /**
     * Cập nhật các giá trị của nhân viên làm việc đã chọn
     *
     * @param Request $request
     * @return mixed
     */
    public function updateObjectStaffAction(Request $request)
    {
        return $this->timeWorkingStaff->updateObjectStaff($request->all());
    }

    /**
     * Thêm nhân viên làm việc
     *
     * @param Request $request
     * @return mixed
     */
    public function addStaffAction(Request $request)
    {
        return $this->timeWorkingStaff->addStaff($request->all());
    }

    /**
     * Show popup chi tiết lịch làm việc
     *
     * @param Request $request
     * @return mixed
     */
    public function showTimeWorkingDetailAction(Request $request)
    {
        return $this->timeWorkingStaff->showTimeWorkingDetail($request->all());
    }

    /**
     * Chỉnh sửa thời gian làm việc
     *
     * @param Request $request
     * @return mixed
     */
    public function updateTimeWorkingAction(UpdateTimeWorkingRequest $request)
    {
        return $this->timeWorkingStaff->updateTimeWorking($request->all());
    }

    /**
     * Show popup chấm công hộ
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopTimeAttendanceAction(Request $request)
    {
        $data = $this->timeWorkingStaff->showPopTimeAttendance($request->all());

        return response()->json($data);
    }

    /**
     * Lưu chấm công hộ
     *
     * @param Request $request
     * @return mixed
     */
    public function submitTimeAttendanceAction(Request $request)
    {
        return $this->timeWorkingStaff->submitTimeAttendance($request->all());
    }

    /**
     * Show popup chỉnh sửa ca làm việc
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupEditAction(Request $request)
    {
        $data = $this->timeWorkingStaff->getDataViewEdit($request->all());

        $html = \View::make('shift::time-working-staff.pop.pop-edit', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Show popup làm thêm giờ
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupOvertimeAction(Request $request)
    {
        $data = $this->timeWorkingStaff->getDataViewEdit($request->all());

        $html = \View::make('shift::time-working-staff.pop.pop-overtime', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Thêm ca làm thêm giờ
     *
     * @param CreateOvertimeRequest $request
     * @return mixed
     */
    public function storeOvertimeAction(CreateOvertimeRequest $request)
    {
        return $this->timeWorkingStaff->storeOvertime($request->all());
    }

    /**
     * DS thưởng - phạt ngày làm việc
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listRecompenseAction(Request $request)
    {
        //Lấy data thưởng - phạt
        $data = $this->timeWorkingStaff->getListRecompense($request->all());

        if ($request->type == 'R') {
            //View thưởng
            $view = 'shift::time-working-staff.pop.list-reward';
        } else {
            //View phạt
            $view = 'shift::time-working-staff.pop.list-punishment';
        }

        return view($view, [
            'list' => $data['list'],
            'page' => $request->page,
        ]);
    }

    /**
     * Show popup thêm thưởng - phạt
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupCreateRecompenseAction(Request $request)
    {
        //Lấy data view thêm hình thức thưởng - phạt
        $data = $this->timeWorkingStaff->getDataCreateRecompense($request->all());

        $html = \View::make('shift::time-working-staff.pop.pop-create-recompense', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Thêm thưởng - phạt
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitCreateRecompenseAction(Request $request)
    {
        $data = $this->timeWorkingStaff->submitCreateRecompense($request->all());

        return response()->json($data);
    }

    /**
     * Xoá thưởng - phạt
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeRecompenseAction(Request $request)
    {
        $data = $this->timeWorkingStaff->removeRecompense($request->all());

        return response()->json($data);
    }

     /**
     * Show popup làm thêm giờ
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSelectWeekAction(Request $request)
    {
        $year = $request->years;
        $date_type = $request->date_type;
        $now = Carbon::parse($year.'-01-01');
        $html  = "";
        if($date_type == 'by_week'){
            $html = \View::make('shift::time-working-staff.append.select_week', [
                'week_in_year'  => $now->isoWeek,
                'year' => $year
            ])->render();
        }else {
            $html = \View::make('shift::time-working-staff.append.select_month', [
                'year' => $year
            ])->render();
        }
        

        return response()->json([
            'html' => $html
        ]);
    }
}
