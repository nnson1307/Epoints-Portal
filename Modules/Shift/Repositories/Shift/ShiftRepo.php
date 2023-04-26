<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 2:24 PM
 */

namespace Modules\Shift\Repositories\Shift;



use Carbon\Carbon;
use DateTime;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Modules\Shift\Models\BranchTable;
use Modules\Shift\Models\MapShiftBranchTable;
use Modules\Shift\Models\ShiftTable;
use Modules\Shift\Models\TimeWorkingStaffTable;

class ShiftRepo implements ShiftRepoInterface
{
    protected $shift;

    public function __construct(
        ShiftTable $shift
    ) {
        $this->shift = $shift;
    }

    /**
     * Danh sách ca làm việc
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->shift->getList($filters);


        $now = Carbon::now()->format('Y-m-d');
        $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');

        if ($list->items() > 0) {
            foreach ($list->items() as $v) {
                if ($v['start_work_time'] > $v['end_work_time']) {
                    //Lấy số giờ cách nhau giữa giờ kết thúc và giờ bắt đầu (qua ngày)
                    $rangeMinutes = Carbon::parse($tomorrow.' '. $v['end_work_time'])->diffInMinutes($now.' '. $v['start_work_time']);
                } else {
                    //Lấy số giờ cách nhau giữa giờ bắt đầu và giờ kết thúc (trong ngày)
                    $rangeMinutes = Carbon::parse($now.' '. $v['start_work_time'])->diffInMinutes($now.' '. $v['end_work_time']);
                }

                $rangeLunchMinutes = 0;

                //Tình giờ nghỉ trưa
                if ($v['start_lunch_break'] != null && $v['end_lunch_break'] != null) {
                    if ($v['start_lunch_break'] > $v['end_lunch_break']) {
                        //Lấy số giờ cách nhau giữa giờ kết thúc và giờ bắt đầu (qua ngày)
                        $rangeLunchMinutes = Carbon::parse($tomorrow.' '. $v['end_lunch_break'])->diffInMinutes($now.' '. $v['start_lunch_break']);
                    } else {
                        //Lấy số giờ cách nhau giữa giờ bắt đầu và giờ kết thúc (trong ngày)
                        $rangeLunchMinutes = Carbon::parse($now.' '. $v['start_lunch_break'])->diffInMinutes($now.' '. $v['end_lunch_break']);
                    }
                }

                //Thời gian nghỉ trưa
                $hourLunch = $rangeLunchMinutes / 60;
                //Tính giờ làm việc
                $hourWork = ($rangeMinutes/60) - $hourLunch;

                $v['hour_lunch'] = $hourLunch;
            }
        }

        return [
            "list" => $list
        ];
    }

    /**
     * Data view thêm ca
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function dataViewCreate($input)
    {
        $mBranch = app()->get(BranchTable::class);

        //Lấy option chi nhánh
        $getBranch = $mBranch->getOption();


        $html = \View::make('shift::shift.popup-create', [
            'load' => $input['load'],
            'optionBranch' => $getBranch
        ])->render();

        return [
            'html' => $html
        ];

    }

    /**
     * Thêm ca làm việc
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
            //Validate ngày làm việc
            $checkDayWork = 0;

            if ($input['is_monday'] == 1 || $input['is_tuesday'] == 1 || $input['is_wednesday'] == 1
                || $input['is_thursday'] == 1 || $input['is_friday'] == 1 || $input['is_saturday'] == 1 || $input['is_sunday'] == 1) {
                $checkDayWork = 1;
            }

            if ($checkDayWork == 0) {
                return response()->json([
                    "error" => true,
                    "message" => __("Hãy chọn ngày làm việc"),
                ]);
            }

            if ($input['timekeeping_coefficient'] < 1 || $input['timekeeping_coefficient'] > 999 ) {
                return response()->json([
                    "error" => true,
                    "message" => __("Hệ số công tối thiểu 1 và tối đa 999"),
                ]);
            }

            //Lấy khung giờ làm việc
            $startTime = Carbon::parse($input['start_work_time']);
            $endTime = Carbon::parse($input['end_work_time']);

            $diffHour = $startTime->diffInMinutes($endTime);

            $rangeTime = [];

            if ($diffHour > 0) {
                for ($i = 0; $i <= $diffHour; $i++) {
                    $rangeTime [] = Carbon::parse($input['start_work_time'])->addMinutes($i)->format('H:i');
                }
            }

            if ($input['start_lunch_break'] != null && $input['end_lunch_break'] == null) {
                return response()->json([
                    "error" => true,
                    "message" => __("Thời gian nghỉ không hợp lệ"),
                ]);
            }

            if ($input['start_lunch_break'] == null && $input['end_lunch_break'] != null) {
                return response()->json([
                    "error" => true,
                    "message" => __("Thời gian nghỉ không hợp lệ"),
                ]);
            }

            //Validate thời gian nghỉ trưa
            if ($input['start_lunch_break'] != null && $input['end_lunch_break'] != null) {
                $input['start_lunch_break'] = Carbon::parse($input['start_lunch_break'])->format('H:i');
                $input['end_lunch_break'] = Carbon::parse($input['end_lunch_break'])->format('H:i');

                if (!in_array($input['start_lunch_break'], $rangeTime) || !in_array($input['end_lunch_break'], $rangeTime)) {
                    return response()->json([
                        "error" => true,
                        "message" => __("Thời gian nghỉ không hợp lệ"),
                    ]);
                }
            }

            $now = Carbon::now()->format('Y-m-d');
            $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');

            if (Carbon::parse($input['start_work_time'])->format('H:i') < Carbon::parse($input['end_work_time'])->format('H:i')) {
                //Lấy số giờ cách nhau giữa giờ kết thúc và giờ bắt đầu (trong ngày)
                $rangeMinutes = Carbon::parse($now.' '. $input['end_work_time'])->diffInMinutes($now.' '. $input['start_work_time']);
            } else {
                //Lấy số giờ cách nhau giữa giờ bắt đầu và giờ kết thúc (qua ngày)
                $rangeMinutes = Carbon::parse($tomorrow.' '. $input['end_work_time'])->diffInMinutes($now.' '. $input['start_work_time']);
            }

            $rangeLunchMinutes = 0;

            //Tình giờ nghỉ trưa
            if ($input['start_lunch_break'] != null && $input['end_lunch_break'] != null) {
                if (Carbon::parse($input['start_lunch_break'])->format('H:i') < Carbon::parse($input['end_lunch_break'])->format('H:i')) {
                    //Lấy số giờ cách nhau giữa giờ kết thúc và giờ bắt đầu (trong ngày)
                    $rangeLunchMinutes = Carbon::parse($now.' '. $input['end_lunch_break'])->diffInMinutes($now.' '. $input['start_lunch_break']);
                } else {
                    //Lấy số giờ cách nhau giữa giờ bắt đầu và giờ kết thúc (qua ngày)
                    $rangeLunchMinutes = Carbon::parse($tomorrow.' '. $input['end_lunch_break'])->diffInMinutes($now.' '. $input['start_lunch_break']);
                }
            }

            //Thời gian nghỉ trưa
            $hourLunch = $rangeLunchMinutes / 60;
            //Tính giờ làm việc
            $hourWork = ($rangeMinutes/60) - $hourLunch;

            if ($hourWork < $input['min_time_work']) {
                return response()->json([
                    "error" => true,
                    "message" => __("Số giờ tối thiểu làm việc không hợp lệ"),
                ]);
            }

            //Insert shift
            $idShift = $this->shift->add([
                "shift_name" => $input["shift_name"],
                "start_work_time" => $input["start_work_time"],
                "end_work_time" => $input["end_work_time"],
                "start_lunch_break" => $input["start_lunch_break"],
                "end_lunch_break" => $input["end_lunch_break"],
                "min_time_work" => $input["min_time_work"],
                "is_monday" => $input['is_monday'],
                "is_tuesday" => $input['is_tuesday'],
                "is_wednesday" => $input['is_wednesday'],
                "is_thursday" => $input['is_thursday'],
                "is_friday" => $input['is_friday'],
                "is_saturday" => $input['is_saturday'],
                "is_sunday" => $input['is_sunday'],
                "note" => $input["note"],
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id(),
                "time_work" => $hourWork,
                "timekeeping_coefficient" => $input['timekeeping_coefficient']
            ]);

            $mMapShiftBranch = app()->get(MapShiftBranchTable::class);

            $branchMap = [];

            if (count($input['branch_id']) > 0) {
                foreach ($input['branch_id'] as $v) {
                    $branchMap [] = [
                        'shift_id' => $idShift,
                        'branch_id' => $v,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert branch map
            $mMapShiftBranch->insert($branchMap);

            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Tạo thành công")
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => true,
                "message" => __("Tạo thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Data view edit
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function dataViewEdit($input)
    {
        $mMapShiftBranch = app()->get(MapShiftBranchTable::class);
        $mBranch = app()->get(BranchTable::class);

        //Lấy option chi nhánh
        $getBranch = $mBranch->getOption();
        //Lấy thông tin ca làm việc
        $info = $this->shift->getInfo($input['shift_id']);
        //Lấy chi nhánh map với ca làm việc
        $getBranchMap = $mMapShiftBranch->getInfoByShift($input['shift_id']);

        $arrayBranch = [];

        if (count($getBranchMap) > 0) {
            foreach ($getBranchMap as $v) {
                $arrayBranch [] = $v['branch_id'];
            }
        }

        $html = \View::make('shift::shift.popup-edit', [
            'item' => $info,
            'branchMap' => $arrayBranch,
            'optionBranch' => $getBranch
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Chỉnh sửa ca làm việc
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
            //Validate ngày làm việc
            $checkDayWork = 0;

            if ($input['is_monday'] == 1 || $input['is_tuesday'] == 1 || $input['is_wednesday'] == 1
                || $input['is_thursday'] == 1 || $input['is_friday'] == 1 || $input['is_saturday'] == 1 || $input['is_sunday'] == 1) {
                $checkDayWork = 1;
            }

            if ($checkDayWork == 0) {
                return response()->json([
                    "error" => true,
                    "message" => __("Hãy chọn ngày làm việc"),
                ]);
            }

            if ($input['timekeeping_coefficient'] < 1 || $input['timekeeping_coefficient'] > 999 ) {
                return response()->json([
                    "error" => true,
                    "message" => __("Hệ số công tối thiểu 1 và tối đa 999"),
                ]);
            }

            //Lấy khung giờ làm việc
            $startTime = Carbon::parse($input['start_work_time']);
            $endTime = Carbon::parse($input['end_work_time']);

            $diffHour = $startTime->diffInMinutes($endTime);

            $rangeTime = [];

            if ($diffHour > 0) {
                for ($i = 0; $i <= $diffHour; $i++) {
                    $rangeTime [] = Carbon::parse($input['start_work_time'])->addMinutes($i)->format('H:i');
                }
            }

            if ($input['start_lunch_break'] != null && $input['end_lunch_break'] == null) {
                return response()->json([
                    "error" => true,
                    "message" => __("Thời gian nghỉ không hợp lệ"),
                ]);
            }

            if ($input['start_lunch_break'] == null && $input['end_lunch_break'] != null) {
                return response()->json([
                    "error" => true,
                    "message" => __("Thời gian nghỉ không hợp lệ"),
                ]);
            }

            //Validate thời gian nghỉ trưa
            if ($input['start_lunch_break'] != null && $input['end_lunch_break'] != null) {
                $input['start_lunch_break'] = Carbon::parse($input['start_lunch_break'])->format('H:i');
                $input['end_lunch_break'] = Carbon::parse($input['end_lunch_break'])->format('H:i');

                if (!in_array($input['start_lunch_break'], $rangeTime) || !in_array($input['end_lunch_break'], $rangeTime)) {
                    return response()->json([
                        "error" => true,
                        "message" => __("Thời gian nghỉ không hợp lệ"),
                    ]);
                }
            }

            $now = Carbon::now()->format('Y-m-d');
            $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');

            if (Carbon::parse($input['start_work_time'])->format('H:i') < Carbon::parse($input['end_work_time'])->format('H:i')) {
                //Lấy số giờ cách nhau giữa giờ kết thúc và giờ bắt đầu (trong ngày)
                $rangeMinutes = Carbon::parse($now.' '. $input['end_work_time'])->diffInMinutes($now.' '. $input['start_work_time']);
            } else {
                //Lấy số giờ cách nhau giữa giờ bắt đầu và giờ kết thúc (qua ngày)
                $rangeMinutes = Carbon::parse($tomorrow.' '. $input['end_work_time'])->diffInMinutes($now.' '. $input['start_work_time']);
            }

            $rangeLunchMinutes = 0;

            //Tình giờ nghỉ trưa
            if ($input['start_lunch_break'] != null && $input['end_lunch_break'] != null) {
                if (Carbon::parse($input['start_lunch_break'])->format('H:i') < Carbon::parse($input['end_lunch_break'])->format('H:i')) {
                    //Lấy số giờ cách nhau giữa giờ kết thúc và giờ bắt đầu (trong ngày)
                    $rangeLunchMinutes = Carbon::parse($now.' '. $input['end_lunch_break'])->diffInMinutes($now.' '. $input['start_lunch_break']);
                } else {
                    //Lấy số giờ cách nhau giữa giờ bắt đầu và giờ kết thúc (qua ngày)
                    $rangeLunchMinutes = Carbon::parse($tomorrow.' '. $input['end_lunch_break'])->diffInMinutes($now.' '. $input['start_lunch_break']);
                }
            }

            //Thời gian nghỉ trưa
            $hourLunch = $rangeLunchMinutes / 60;
            //Tính giờ làm việc
            $hourWork = ($rangeMinutes/60) - $hourLunch;

            if ($hourWork < $input['min_time_work']) {
                return response()->json([
                    "error" => true,
                    "message" => __("Số giờ tối thiểu làm việc không hợp lệ"),
                ]);
            }

            $this->shift->edit([
                "shift_name" => $input["shift_name"],
                "start_work_time" => $input["start_work_time"],
                "end_work_time" => $input["end_work_time"],
                "start_lunch_break" => $input["start_lunch_break"],
                "end_lunch_break" => $input["end_lunch_break"],
                "min_time_work" => $input["min_time_work"],
                "is_monday" => $input['is_monday'],
                "is_tuesday" => $input['is_tuesday'],
                "is_wednesday" => $input['is_wednesday'],
                "is_thursday" => $input['is_thursday'],
                "is_friday" => $input['is_friday'],
                "is_saturday" => $input['is_saturday'],
                "is_sunday" => $input['is_sunday'],
                "note" => $input["note"],
                "updated_by" => Auth()->id(),
                "time_work" => $hourWork,
                "timekeeping_coefficient" => $input['timekeeping_coefficient']
            ], $input['shift_id']);

            $mMapShiftBranch = app()->get(MapShiftBranchTable::class);

            $branchMap = [];

            if (count($input['branch_id']) > 0) {
                foreach ($input['branch_id'] as $v) {
                    $branchMap [] = [
                        'shift_id' => $input['shift_id'],
                        'branch_id' => $v,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Xoá chi nhánh map
            $mMapShiftBranch->removeBranchByShift($input['shift_id']);
            //Insert branch map
            $mMapShiftBranch->insert($branchMap);

            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Chỉnh sửa thành công"),
                "shift_id" => $input["shift_id"]
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => true,
                "message" => __("Chỉnh sửa thất bại"),
                "_message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Xóa ca làm việc
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function destroy($input)
    {
        try {
            $mTimeWorkingStaff = app()->get(TimeWorkingStaffTable::class);

            //Kiểm tra ca làm việc đã sử dụng chưa
//            $checkUsing = $mTimeWorkingStaff->getUsingByShift($input['shift_id']);
//
//            if (count($checkUsing) > 0) {
//                return [
//                    'error' => true,
//                    'message' => __('Ca làm việc đã được sử dụng, bạn không thể xoá')
//                ];
//            }

            //Xóa Ca
            $this->shift->edit([
                'is_deleted' => 1,
                'updated_by' => Auth()->id()
            ], $input['shift_id']);

            return [
                'error' => false,
                'message' => __('Xóa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xóa thất bại')
            ];
        }
    }

    /**
     * Cập nhật trạng thái ca làm việc
     *
     * @param $input
     * @return array|mixed
     */
    public function updateStatus($input)
    {
        try {
            //Xóa Ca
            $this->shift->edit([
                'is_actived' => $input['is_actived']
            ], $input['shift_id']);

            return [
                'error' => false,
                'message' => __('Thay đổi trạng thái thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thay đổi trạng thái thất bại')
            ];
        }
    }

    /**
     * Tính thời gian làm việc của ca
     *
     * @param $input
     * @return array|mixed
     */
    public function calculateMinWork($input)
    {
        $now = Carbon::now()->format('Y-m-d');
        $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');

        if (Carbon::parse($input['start_work_time'])->format('H:i') < Carbon::parse($input['end_work_time'])->format('H:i')) {
            //Lấy số giờ cách nhau giữa giờ kết thúc và giờ bắt đầu (trong ngày)
            $rangeMinutes = Carbon::parse($now.' '. $input['end_work_time'])->diffInMinutes($now.' '. $input['start_work_time']);
        } else {
            //Lấy số giờ cách nhau giữa giờ bắt đầu và giờ kết thúc (qua ngày)
            $rangeMinutes = Carbon::parse($tomorrow.' '. $input['end_work_time'])->diffInMinutes($now.' '. $input['start_work_time']);
        }

        $rangeLunchMinutes = 0;

        //Tình giờ nghỉ trưa
        if ($input['start_lunch_break'] != null && $input['end_lunch_break'] != null) {
            if (Carbon::parse($input['start_lunch_break'])->format('H:i') < Carbon::parse($input['end_lunch_break'])->format('H:i')) {
                //Lấy số giờ cách nhau giữa giờ kết thúc và giờ bắt đầu (trong ngày)
                $rangeLunchMinutes = Carbon::parse($now.' '. $input['end_lunch_break'])->diffInMinutes($now.' '. $input['start_lunch_break']);
            } else {
                //Lấy số giờ cách nhau giữa giờ bắt đầu và giờ kết thúc (qua ngày)
                $rangeLunchMinutes = Carbon::parse($tomorrow.' '. $input['end_lunch_break'])->diffInMinutes($now.' '. $input['start_lunch_break']);
            }
        }

        //Thời gian nghỉ trưa
        $hourLunch = $rangeLunchMinutes / 60;
        //Tính giờ làm việc
        $hourWork = ($rangeMinutes/60) - $hourLunch;

        if ($hourWork < 0) {
            $hourWork = 0;
        }

        return [
            'hourWork' => $hourWork
        ];
    }
}