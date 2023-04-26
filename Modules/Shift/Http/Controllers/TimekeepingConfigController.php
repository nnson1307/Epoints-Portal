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
use Modules\Shift\Http\Requests\TimekeepingConfig\StoreRequest;
use Modules\Shift\Http\Requests\TimekeepingConfig\UpdateRequest;
use Modules\Shift\Repositories\TimekeepingConfig\TimekeepingConfigRepoInterface;

class TimekeepingConfigController extends Controller
{
    protected $timekeepingConfig;

    public function __construct(
        TimekeepingConfigRepoInterface $timekeepingConfig
    )
    {
        $this->timekeepingConfig = $timekeepingConfig;
    }

    /**
     * Danh sách
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Request $request)
    {
        $data = $this->timekeepingConfig->list();

        return view('shift::timekeeping-config.index', [
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
        //Lấy data filter
        $data = $this->timekeepingConfig->getDataFilter();

        //Chi nhánh
        $groupBranch = (['' => __('Chọn chi nhánh')]) + $data['optionBranch'];

        return [
            'sf_timekeeping_config$branch_id' => [
                'data' => $groupBranch
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
            'sf_timekeeping_config$branch_id'
        ]);

        $data = $this->timekeepingConfig->list($filter);

        return view('shift::timekeeping-config.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function create(Request $request)
    {
        $data = $this->timekeepingConfig->dataViewCreate($request->all());

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
        return $this->timekeepingConfig->store($request->all());
    }

    /**
     * View chỉnh sửa
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $data = $this->timekeepingConfig->dataViewEdit($request->all());

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
        return $this->timekeepingConfig->update($request->all());
    }

    /**
     * Xóa
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $data = $this->timekeepingConfig->destroy($request->all());

        return response()->json($data);
    }

    /**
     * Lấy ip hiện tại
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentIpAction()
    {
        $data = $this->timekeepingConfig->currentIp();

        return response()->json($data);
    }

    /**
     * Cập nhật trạng thái
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatusAction(Request $request)
    {
        $data = $this->timekeepingConfig->changeStatus($request->all());

        return response()->json($data);
    }
}