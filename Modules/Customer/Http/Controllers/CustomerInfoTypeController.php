<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Customer\Http\Requests\StoreRequest;
use Modules\Customer\Http\Requests\UpdateRequest;
use Modules\Customer\Repositories\CustomerInfoType\CustomerInfoTypeRepoInterface;

class CustomerInfoTypeController extends Controller
{
    protected $customerInfoType;
    public function __construct(CustomerInfoTypeRepoInterface $customerInfoType)
    {
        $this->customerInfoType = $customerInfoType;
    }

    /**
     * View danh sach
     *
     * @return array
     */
    public function index()
    {
        $data = $this->customerInfoType->getList();
        return view('customer::customer-info-type.index', [
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
        $data = $this->customerInfoType->getList($filter);
        return view('customer::customer-info-type.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * Popup thêm loại thông tin kèm theo
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $data = $this->customerInfoType->dataViewCreate($request->all());
        return response()->json($data);
    }

    /**
     * Lưu loại thông tin kèm them
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        return $this->customerInfoType->store($data);
    }

    /**
     * Pop chỉnh sửa loại thông tin kèm them
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $data = $this->customerInfoType->dataViewEdit($request->all());
        return response()->json($data);
    }

    /**
     * Cập nhật loại thông tin kèm them
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->customerInfoType->update($data);
    }

    /**
     * Xoá loại thông tin kèm them
     *
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $data = $request->all();
        return $this->customerInfoType->delete($data);
    }

    public function updateStatus(Request $request)
    {
        $data = $request->all();
        return $this->customerInfoType->updateStatus($data);
    }
}