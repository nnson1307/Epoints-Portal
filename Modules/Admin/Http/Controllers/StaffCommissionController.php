<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\StaffCommission\StoreRequest;
use Modules\Admin\Http\Requests\StaffCommission\UpdateRequest;
use Modules\Admin\Repositories\StaffCommission\StaffCommissionRepoInterface;

class StaffCommissionController extends Controller
{
    protected $staffCommission;
    public function __construct(StaffCommissionRepoInterface $staffCommission)
    {
        $this->staffCommission = $staffCommission;
    }

    /**
     * View danh sach
     *
     * @return array
     */
    public function index()
    {
        $data = $this->staffCommission->getList();
        return view('admin::staff-commission.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters()
        ]);
    }

    public function filters()
    {
        return [

        ];
    }

    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
            'created_at',
        ]);
        $data = $this->staffCommission->getList($filter);
        return view('admin::staff-commission.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * Popup thêm hoa hồng nhân viên
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $data = $this->staffCommission->dataViewCreate($request->all());
        return response()->json($data);
    }

    /**
     * Lưu hoa hông nhân viên
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        return $this->staffCommission->store($data);
    }

    /**
     * Pop chỉnh sửa hoa hồng nhân viên
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $data = $this->staffCommission->dataViewEdit($request->all());
        return response()->json($data);
    }

    /**
     * Cập nhật hoa hồng nhân viên
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->staffCommission->update($data);
    }

    /**
     * Xoá hoa hồng nhân viên
     *
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $data = $request->all();
        return $this->staffCommission->delete($data);
    }
}