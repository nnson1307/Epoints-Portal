<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 10:46
 */

namespace Modules\Shift\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Shift\Repositories\Attendances\AttendancesRepoInterface;
use Carbon\Carbon;
use Modules\Shift\Repositories\TimeWorkingStaff\TimeWorkingStaffRepoInterface;

class AttendancesController extends Controller
{
    protected $attendances;

    public function __construct(AttendancesRepoInterface $attendances)
    {
        $this->attendances = $attendances;
    }
    
    public function index(Request $request)
    {
        $timeWorkingRepo = app()->get(TimeWorkingStaffRepoInterface::class);

        //Lấy cầu hình chung của ca làm việc
        $listConfig = $timeWorkingRepo->getConfigGeneral();

        //Lưu session cấu hình chung
        $this->_setSessionConfigGeneral($listConfig);

        $filter = $request->only(['page', 'display', 'branch_id', 'department_id', 'status']);
        $attendancesList = $this->attendances->getListHistoryCheckIn($filter);
        $branchList = $this->attendances->getListBranch();
        $department = $this->attendances->getListDepartment();
        
        return view('shift::attendances.index', [
            'LIST' => $attendancesList,
            'branch' => $branchList->toArray(),
            'department' => $department->toArray(),
            'isValid'   => 0
        ]);
    }

    public function listAction(Request $request){
        
        $filter = $request->only(['page', 'display', 'branch_id', 'department_id','search','created_at', 'status']);
        $attendancesList = $this->attendances->getListHistoryCheckIn($filter);
        return view('shift::attendances.list', [
            'LIST' => $attendancesList,
            'page' => $filter['page'],
            'isValid'   => $filter['isValid'] ?? 0
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
     * Show modal checkin
     *
     * @return mixed
     */
    public function showModalCheckInAction(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->attendances->getInfo($request->time_working_staff_id);
            if($data['type'] == 1){
                $html = \View::make('shift::attendances.checkin', [ 'data' => $data])->render();
            }else {
                $html = \View::make('shift::attendances.checkout', [
                    'data' => $data
                ])->render();
            }

            return response()->json([
                'html' => $html
            ]);
        }

        
        
        // return $this->attendances->showModalCheckin();
    }

    /**
     * Checkin
     *
     * @return mixed
     */
    public function checkInAction(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'staff_id' => Auth()->id(),
                'time_working_staff_id' => $request->time_working_staff_id,
                'branch_id' => $request->branch_id,
                'shift_id' => $request->shift_id,
                'check_in_day' => Carbon::now()->format('Y-m-d'),
                'check_in_time' => Carbon::now()->format('H:i:s'),
                'status' => 'ok',
                'reason' => ''
            ];
           
            $id = $this->attendances->checkin($data);
            if($id > 0){
                $startTime = Carbon::parse($request->working_time);
                $endTime = Carbon::parse(Carbon::now()->format('H:i:s'));
                $totalDuration =  $startTime->diffInMinutes($endTime);
                $number_late_time = 0;
                if($startTime < $endTime){
                    $number_late_time = $totalDuration;
                }
                $dataUpdate = [
                    'is_check_in' => 1,
                    'number_late_time' => $number_late_time
                ];
                $data = $this->attendances->updateWorkingTime($dataUpdate,$request->time_working_staff_id);
            }
            if($id > 0){
                return response()->json(
                    [
                        'status'   => 1,
                        'message'  => 'Checkin thành công'
                    ]
                );
            }else {
                return response()->json(
                    [
                        'status'   => 0,
                        'message'  => 'Checkin thất bại'
                    ]
                );
            }
        }
        
    }
    
    /**
     * Checkin
     *
     * @return mixed
     */
    public function checkOutAction(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'staff_id' => Auth()->id(),
                'time_working_staff_id' => $request->time_working_staff_id,
                'branch_id' => $request->branch_id,
                'shift_id' => $request->shift_id,
                'check_out_day' => Carbon::parse($request['working_day'])->format('Y-m-d'),
                'check_out_time' => $request->checkout_time,
                'status' => 'ok',
                'reason' => ''
            ];
            $id = $this->attendances->checkout($data);
            if($id > 0){
                $startTime = Carbon::parse($request->checkout_time);
                $endTime = Carbon::parse($request->working_end_time);
                $totalDuration =  $startTime->diffInMinutes($endTime);
                $number_time_back_soon = 0;
                if($startTime < $endTime){
                    $number_time_back_soon = $totalDuration;
                }
                $dataUpdate = [
                    'is_check_out' => 1,
                    'number_time_back_soon' => $number_time_back_soon,
                    'check_out_by' => Auth()->id()
                ];
                $idCheckOut = $this->attendances->updateWorkingTime($dataUpdate,$request->time_working_staff_id);

            }
            if($id > 0){
                return response()->json(
                    [
                        'status'   => 1,
                        'message'  => 'Checkout thành công'
                    ]
                );
            }else {
                return response()->json(
                    [
                        'status'   => 0,
                        'message'  => 'Checkout thất bại'
                    ]
                );
            }
        }
    }

    public function getDepartmentAction(){
        $department = $this->attendances->getListDepartment();

        $data = [];
        foreach ($department as $key => $value) {
            $data[] = [
                'id' => (int)$value['department_id'],
                'name' => $value['department_name'],
            ];
        }
        return response()->json([
            'optionDepartment' => $data,
        ]);
    }

    public function approveLateSoonAction(Request $request)
    {
        if ($request->ajax()) {
            $data = [];

            //đi trễ
            if($request->type == "1"){
                $data = [
                    'updated_by' => Auth()->id(),
                    "is_approve_late" => 1,
                    "approve_late_by" => Auth()->id(),
                    "updated_at" => Carbon::now()->format('Y-m-d H:i:s'),
                ];
            }else {
                $data = [
                    'updated_by' => Auth()->id(),
                    "is_approve_soon" => 1,
                    "approve_soon_by" => Auth()->id(),
                    "updated_at" => Carbon::now()->format('Y-m-d H:i:s'),
                ];
            }
            $id = $this->attendances->updateWorkingTime($data,$request->time_working_staff_id);
            if($id > 0){
                return response()->json(
                    [
                        'status'   => 1,
                        'message'  => 'Thao tác thành công'
                    ]
                );
            }else {
                return response()->json(
                    [
                        'status'   => 0,
                        'message'  => 'Thao tác thất bại'
                    ]
                );
            }
        }
    }
}