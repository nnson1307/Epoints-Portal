<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 10:52 AM
 */

namespace Modules\Shift\Http\Controllers;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Shift\Http\Requests\Shift\StoreRequest;
use Modules\Shift\Http\Requests\Shift\UpdateRequest;
use Modules\Shift\Repositories\Shift\ShiftRepoInterface;

class ShiftController extends Controller
{
    protected $shift;

    public function __construct(
        ShiftRepoInterface $shift
    )
    {
        $this->shift = $shift;
    }

    /**
     * Danh sách
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Request $request)
    {
        $data = $this->shift->list();

        return view('shift::shift.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters(),
            'param' => $request->all()
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
            'is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    '1' => __('Đang hoạt động'),
                    '0' => __('Đã tạm ngừng')
                ]
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
            'search',
            'is_actived',
            'shift_type'
        ]);

        $data = $this->shift->list($filter);

        return view('shift::shift.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm KH tiềm năng
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function create(Request $request)
    {
        $data = $this->shift->dataViewCreate($request->all());

        return response()->json($data);
    }

    /**
     * Thêm
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        return $this->shift->store($request->all());
    }

    /**
     * View chỉnh sửa
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $data = $this->shift->dataViewEdit($request->all());

        return response()->json($data);
    }

    /**
     * Chỉnh sửa
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        return $this->shift->update($request->all());
    }

    /**
     * Xóa
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $data = $this->shift->destroy($request->all());

        return response()->json($data);
    }

    /**
     * Thay đổi trạng thái ca làm việc
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatusAction(Request $request)
    {
        $data = $this->shift->updateStatus($request->all());

        return response()->json($data);
    }

    /**
     * Tính thời gian tối thiểu làm việc
     *
     * @param Request $request
     * @return mixed
     */
    public function calculateMinWorkAction(Request $request)
    {
        return $this->shift->calculateMinWork($request->all());
    }
}