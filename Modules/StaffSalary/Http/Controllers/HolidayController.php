<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 018/04/2022
 * Time: 10:46
 */

namespace Modules\StaffSalary\Http\Controllers;

use Modules\StaffSalary\Repositories\StaffHoliday\StaffHolidayRepoInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HolidayController extends Controller
{
    protected $staffHoliday;

    public function __construct(StaffHolidayRepoInterface $staffHoliday)
    {
        $this->staffHoliday = $staffHoliday;
    }
    
    public function index(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search']);
        $list = $this->staffHoliday->getList($filter);
        return view('staff-salary::holiday.index', [
            'LIST' => $list,
        ]);
    }

    public function listAction(Request $request){
        $filter = $request->only(['page', 'display', 'search']);
        $attendancesList = $this->staffHoliday->getList($filter);
        return view('staff-salary::holiday.list', [
            'LIST' => $attendancesList,
            'page' => $filter['page']
        ]);
    }

    /**
     * Show modal add holiday
     *
     * @return mixed
     */
    public function showModalAddHolidayAction()
    {
        $html = \View::make('staff-salary::holiday.add')->render();
        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Show modal add holiday
     *
     * @return mixed
     */
    public function showModalEditHolidayAction(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->staffHoliday->getDetail($request->staff_holiday_id);
            $html = \View::make('staff-salary::holiday.edit', ['data' => $data])->render();
            return response()->json([
                'html' => $html
            ]);
        }
       
    }

    /**
     * add holiday
     *
     * @return mixed
     */
    public function addAction(Request $request)
    {
        if ($request->ajax()) {
            $startTime = Carbon::parse(Carbon::createFromFormat('d/m/Y', $request->staff_holiday_start_date)->format('Y-m-d'));
            $endTime = Carbon::parse(Carbon::createFromFormat('d/m/Y', $request->staff_holiday_end_date)->format('Y-m-d'));
            $totalDuration =  $startTime->diffInDays($endTime);
            $data = [
                'staff_holiday_title' => $request->staff_holiday_title,
                'staff_holiday_start_date' => Carbon::createFromFormat('d/m/Y', $request->staff_holiday_start_date)->format('Y-m-d'),
                'staff_holiday_end_date' => Carbon::createFromFormat('d/m/Y', $request->staff_holiday_end_date)->format('Y-m-d'),
                'staff_holiday_number' => $totalDuration + 1
            ];
           
            $id = $this->staffHoliday->add($data);
            if($id > 0){
                return response()->json(
                    [
                        'status'   => 1,
                        'message'  => 'Thêm mới thành công'
                    ]
                );
            }
            return response()->json(
                [
                    'status'   => 0,
                    'message'  => 'Thêm mới thất bại'
                ]
            );
        }
    }

    /**
     * edit holiday
     *
     * @return mixed
     */
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $startTime = Carbon::parse(Carbon::createFromFormat('d/m/Y', $request->staff_holiday_start_date)->format('Y-m-d'));
            $endTime = Carbon::parse(Carbon::createFromFormat('d/m/Y', $request->staff_holiday_end_date)->format('Y-m-d'));
            $totalDuration =  $startTime->diffInDays($endTime);
            $data = [
                'staff_holiday_title' => $request->staff_holiday_title,
                'staff_holiday_start_date' => Carbon::createFromFormat('d/m/Y', $request->staff_holiday_start_date)->format('Y-m-d'),
                'staff_holiday_end_date' => Carbon::createFromFormat('d/m/Y', $request->staff_holiday_end_date)->format('Y-m-d'),
                'staff_holiday_number' => $totalDuration + 1
            ];
           
            $id = $this->staffHoliday->edit($data, $request->staff_holiday_id);
            if($id > 0){
                return response()->json(
                    [
                        'status'   => 1,
                        'message'  => 'Chỉnh sửa thành công'
                    ]
                );
            }
            return response()->json(
                [
                    'status'   => 0,
                    'message'  => 'Chỉnh sửa thất bại'
                ]
            );
        }
    }

    /**
     * delete holiday
     *
     * @return mixed
     */
    public function deleteAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $this->staffHoliday->delete($request->staff_holiday_id);
            if($id > 0){
                return response()->json(
                    [
                        'status'   => 1,
                        'message'  => 'Chỉnh sửa thành công'
                    ]
                );
            }
            return response()->json(
                [
                    'status'   => 0,
                    'message'  => 'Chỉnh sửa thất bại'
                ]
            );
        }
    }
}