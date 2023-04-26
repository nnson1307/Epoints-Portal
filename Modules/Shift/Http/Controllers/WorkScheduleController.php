<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/04/2022
 * Time: 09:40
 */

namespace Modules\Shift\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Shift\Http\Requests\WorkSchedule\StoreRequest;
use Modules\Shift\Http\Requests\WorkSchedule\UpdateRequest;
use Modules\Shift\Repositories\WorkSchedule\WorkScheduleRepoInterface;

class WorkScheduleController extends Controller
{
    protected $workSchedule;

    public function __construct(
        WorkScheduleRepoInterface $workSchedule
    ) {
        $this->workSchedule = $workSchedule;
    }

    /**
     * Danh sách lịch làm việc
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //Lấy ds lịch làm việc
        $data = $this->workSchedule->list();

        return view('shift::work-schedule.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters(),
        ]);
    }

    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filters()
    {
        return [
            'sf_work_schedules$is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    '1' => __('Hoạt động'),
                    '0' => __('Không hoạt động')
                ]
            ],
            'sf_work_schedules$repeat' => [
                'data' => [
                    '' => __('Chọn hình thức lặp lại'),
                    'hard' => __('Cố định'),
                    'monthly' => __('Hàng tháng')
                ]
            ],
        ];
    }

    /**
     * Ajax filter ds lịch làm việc
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
            'sf_work_schedules$is_actived',
            'sf_work_schedules$repeat',
        ]);

        $data = $this->workSchedule->list($filter);

        return view('shift::work-schedule.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View phân ca làm
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $data = $this->workSchedule->getDataViewCreate();

        return view('shift::work-schedule.create', $data);
    }

    /**
     * Show popup chọn nhân viên
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopupStaffAction()
    {
        $data = $this->workSchedule->showPopupStaff();

        return response()->json($data);
    }

    /**
     * Chọn nhân viên
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseStaffAction(Request $request)
    {
        $data = $this->workSchedule->chooseStaff($request->all());

        return response()->json($data);
    }

    /**
     * Bỏ chọn nhân viên
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unChooseStaffAction(Request $request)
    {
        $data = $this->workSchedule->unChooseStaff($request->all());

        return response()->json($data);
    }

    /**
     * Ajax filter, phân trang ds nhân viên (pop)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listStaffPopAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'staffs$branch_id',
            'staffs$department_id',
            'staffs$staff_id',
        ]);

        //Danh sách nhân viên
        $data = $this->workSchedule->listStaffPop($filter);

        return view('shift::work-schedule.pop.list-staff', $data);
    }

    /**
     * Submit chọn nhân viên
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitChooseStaffAction()
    {
        $data = $this->workSchedule->submitChooseStaff();

        return response()->json($data);
    }

    /**
     * Ajax filter, phân trang ds nhân viên đã chọn
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
        ]);

        $data = $this->workSchedule->listStaff($filter);

        return view('shift::work-schedule.list-staff', $data);
    }

    /**
     * Xoá nhân viên ra khỏi table
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeStaffAction(Request $request)
    {
        $data = $this->workSchedule->removeStaff($request->all());

        return response()->json($data);
    }

    /**
     * Thêm lịch làm việc
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        return $this->workSchedule->store($request->all());
    }

    /**
     * Chỉnh sửa lịch làm việc
     *
     * @param $workScheduleId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($workScheduleId)
    {
        $data = $this->workSchedule->getDataViewEdit($workScheduleId);

        return view('shift::work-schedule.edit', $data);
    }

    /**
     * Chỉnh sửa lịch làm việc
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        return $this->workSchedule->update($request->all());
    }

    /**
     * Xoá lịch làm việc
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        return $this->workSchedule->destroy($request->all());
    }

    /**
     * Chọn chi nhánh làm việc
     *
     * @param Request $request
     * @return mixed
     */
    public function chooseShiftAction(Request $request)
    {
        $data = $this->workSchedule->chooseShift($request->all());

        return response()->json($data);
    }
}